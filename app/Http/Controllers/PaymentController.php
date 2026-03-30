<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Mail\InvoicePaidMail;
use App\Models\Payment;
use App\Models\Subscription;
use App\Services\CouponV2Service;
use App\Services\InvitationService;
use App\Services\PaymobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    protected PaymobService $paymobService;

    public function __construct(
        PaymobService $paymobService,
        private readonly CouponV2Service $couponV2Service,
        private readonly InvitationService $invitationService
    ) {
        $this->paymobService = $paymobService;
    }

    public function initiatePayment(Request $request)
    {
        try {
            Log::info('Payment initiation request received', ['request_data' => $request->all()]);

            // Validate request
            $request->validate([
                'subscription_ids' => 'required|array|min:1',
                'subscription_ids.*' => 'required|integer|exists:subscriptions,id',
                'invitation_journey_token' => 'nullable|string|max:100',
            ]);

            $subscriptionIds = $request->input('subscription_ids'); // this is now a clean array
            $journey = $this->invitationService->resolveJourney(
                $request->input('invitation_journey_token'),
                Auth::user(),
                $request
            );
            $ownedSubscriptions = Subscription::whereIn('id', $subscriptionIds)
                ->where('user_id', Auth::id())
                ->count();

            if ($ownedSubscriptions !== count($subscriptionIds)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'One or more subscriptions do not belong to the current user.',
                ], 403);
            }

            $amount = (int) round(Subscription::whereIn('id', $subscriptionIds)->sum('price'));

            // Prepare user data
            $user = Auth::user();
            $address = Address::where('user_id', $user->id)->where('primary', true)->first();

            $billingData = [
                'first_name' => $user->first_name ?? 'N/A',
                'last_name' => $user->last_name ?? 'N/A',
                'email' => $user->email,
                'phone_number' => $user->phone ?? '+20100000000',
                'city' => $address->town_city ?? 'Cairo',
                'country' => 'EG',
                'street' => $address->street_address ?? 'N/A',
                'building' => 'N/A',
                'floor' => 'N/A',
                'apartment' => $address->apartment_suite_unit ?? 'N/A',
                'state' => $address->state ?? 'Cairo',
            ];

            $customerData = [
                'first_name' => $user->first_name ?? 'N/A',
                'last_name' => $user->last_name ?? 'N/A',
                'email' => $user->email,
                'extras' => ['user_id' => $user->id],
            ];

            // Create intention
            $intentionData = $this->paymobService->createIntention(
                $amount,
                $billingData,
                $customerData,
                route('payment.success')
            );

            if (! $intentionData || ! isset($intentionData['client_secret'])) {
                return redirect()->back()->with('error', 'فشل في إنشاء الطلب لدى PayMob');
            }

            // Store payment record
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'invitation_journey_id' => $journey?->id,
                'subscription_ids' => $subscriptionIds,
                'transaction_id' => $intentionData['intention_id'],
                'order_id' => $intentionData['order_id'], // ✅ new
                'amount' => $amount,
                'payment_status' => 'pending',
                'payment_method' => 'card',
            ]);

            Log::info('Payment record created', [$payment]);

            // Redirect to Paymob checkout
            $checkoutUrl = $this->paymobService->getCheckoutUrl($intentionData['client_secret']);

            return response()->json([
                'status' => 'success',
                'payment_url' => $checkoutUrl,
            ]);
        } catch (\Exception $e) {
            Log::error('Payment initiation failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function paymentSuccess(Request $request)
    {
        Log::info('Payment success callback received', $request->all());

        try {
            // Get payment info from request
            $orderId       = $request->input('order');        // The order you sent in the intention
            $success       = $request->input('success');
            $paymentStatus = filter_var($success, FILTER_VALIDATE_BOOLEAN) ? 'paid' : 'failed';

//            if (! $intentionId) {
//                Log::error('No intention ID found in success callback');
//
//                return redirect()->route('user-dashboard')->with([
//                    'payment_status' => 'failed',
//                    'message' => 'فشل في تأكيد عملية الدفع',
//                ]);
//            }

            // Find payment by intention ID or transaction ID
            $payment = Payment::where('order_id', $orderId)->first(); // ✅ fixed line

            if (! $payment) {
                Log::error('Payment not found', ['order_id' => $orderId]);
                return redirect()->route('user-dashboard')->with([
                    'payment_status' => 'failed',
                    'message' => 'لم يتم العثور على عملية الدفع',
                ]);
            }

            $justPaid = false;

            // Update payment status
            DB::transaction(function () use ($payment, $paymentStatus, $request, &$justPaid) {
                if ($payment->payment_status !== 'pending') {
                    Log::info('Payment already processed', ['payment_id' => $payment->id]);

                    return;
                }

                $payment->update([
                    'payment_status' => $paymentStatus,
                    'payment_date' => now(),
                    'payment_response' => $request->all(),
                ]);

                if ($paymentStatus === 'paid') {
                    $this->activateSubscriptions($payment);
                    $justPaid = true;
                }

                $this->couponV2Service->syncUsagesForPayment($payment, $paymentStatus);
                $this->invitationService->syncJourneyForPayment($payment, $paymentStatus);
            });

            $data = [
                'success' => $request->success,
                'order_id' => $request->order,
            ];

            if ($justPaid) {
                $user = $payment->user ?? \App\Models\User::find($payment->user_id);

                $ids = is_array($payment->subscription_ids)
                    ? $payment->subscription_ids
                    : json_decode($payment->subscription_ids, true);

                $subscriptions = Subscription::with(['coupon', 'couponV2', 'couponV2Usage', 'plan', 'user.addresses'])
                    ->whereIn('id', $ids)
                    ->where('user_id', $payment->user_id)
                    ->get();

                // \Log::info('Sending invoice email', ['user' => $user->email, 'subs' => $subscriptions->pluck('id')]);
                // Mail::to($user->email)->send(new InvoicePaidMail($user, $payment, $subscriptions));
            }
            
            return redirect()->route('payment.thank-q')->with($data);

        } catch (\Exception $e) {
            Log::error('Payment success callback failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('user-dashboard')->with([
                'payment_status' => 'failed',
                'message' => 'حدث خطأ أثناء معالجة النتيجة',
            ]);
        }
    }

    private function activateSubscriptions(Payment $payment)
    {
        try {
            $subscriptionIds = is_array($payment->subscription_ids)
                ? $payment->subscription_ids
                : json_decode($payment->subscription_ids, true);

            foreach ($subscriptionIds as $subscriptionId) {
                $subscription = Subscription::where('id', $subscriptionId)
                    ->where('user_id', $payment->user_id)
                    ->first();

                if ($subscription) {
                    $subscription->update(['status' => 'active']);
                    Log::info('Subscription activated', ['subscription_id' => $subscription->id]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to activate subscriptions', [
                'payment_id' => $payment->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function show($id)
    {
        $payment = Payment::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        return response()->json($payment);
    }

    public function handlePaymobCallback(Request $request)
    {
        Log::info('Paymob callback acknowledged', $request->all());

        return response()->json([
            'status' => 'acknowledged',
        ], 200);
    }
}
