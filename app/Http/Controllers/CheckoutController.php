<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\DailySelectedMeal;
use App\Models\MealVariant;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\CouponV2Service;
use App\Services\InvitationService;
use App\Services\SubscriptionPricingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly SubscriptionPricingService $pricingService,
        private readonly CouponV2Service $couponV2Service,
        private readonly InvitationService $invitationService
    ) {
    }

    public function checkout(Request $request)
    {
        try {
            $validated = $request->validate([
                'subscriptions' => 'required|array|min:1',
                'subscriptions.*.user_id' => 'nullable|integer',
                'subscriptions.*.plan_id' => 'required|string',
                'subscriptions.*.weeks_count' => 'required|integer|min:1',
                'subscriptions.*.days_per_week' => 'required|integer|min:1|max:7',
                'subscriptions.*.type' => 'required|string|in:standard,customized',
                'subscriptions.*.meals' => 'required|array',
                'subscriptions.*.meals.*.meal_id' => 'required|string|exists:meal_variants,meal_id',
                'subscriptions.*.meals.*.day' => 'required|string|exists:days,name',
                'subscriptions.*.meals.*.meal_type' => 'required|string|in:breakfast,lunch,dinner,snack1,snack2',
                'coupon_id' => 'nullable|integer|exists:coupons,id',
                'applied_coupon' => 'nullable|array',
                'applied_coupon.version' => 'nullable|string|in:invitation,v1,v2',
                'applied_coupon.id' => 'nullable|integer',
                'applied_coupon.code' => 'nullable|string|max:100',
                'invitation_journey_token' => 'nullable|string|max:100',
                'confirm_invitation_override' => 'nullable|boolean',
                'billing_address' => 'nullable|array',
                'billing_address.town_city' => 'required_with:billing_address|string|max:255',
                'billing_address.street_address' => 'required_with:billing_address|string|max:255',
            ]);

            $subscriptions = $validated['subscriptions'];
            $journey = $this->invitationService->resolveJourney(
                $validated['invitation_journey_token'] ?? null,
                Auth::user(),
                $request
            );
            $preparedCouponSelection = $this->invitationService->prepareCouponSelection(
                $journey,
                $validated['applied_coupon'] ?? null,
                $validated['coupon_id'] ?? null,
                (bool) ($validated['confirm_invitation_override'] ?? false)
            );
            $couponResolution = $this->couponV2Service->resolveForCheckout(
                $preparedCouponSelection['applied_coupon'],
                $preparedCouponSelection['legacy_coupon_id'],
                $subscriptions,
                Auth::id()
            );

            $createdSubscriptions = DB::transaction(function () use ($subscriptions, $couponResolution, $validated) {
                $createdSubscriptions = [];

                $this->syncPrimaryAddress($validated['billing_address'] ?? null);

                foreach ($subscriptions as $index => $sub) {
                    if (! Auth::check() && ! $sub['user_id']) {
                        throw new \RuntimeException('User not authenticated');
                    }

                    $resolvedPlanId = $this->pricingService->resolvePlanId($sub['plan_id']);
                    $plan = Plan::where('name', 'like', '%'.$resolvedPlanId.'%')->firstOrFail();
                    $pricing = $couponResolution['pricing_by_index'][$index]
                        ?? $this->pricingService->calculateForSubscription($sub);

                    $mealIds = collect($sub['meals'])->pluck('meal_id')->values();
                    $startDate = $this->calcStartDate((int) $sub['days_per_week']);
                    $endDate = $this->calcEndDate($startDate, (int) $sub['weeks_count'], (int) $sub['days_per_week']);

                    $subscription = Subscription::create([
                        'user_id' => Auth::id(),
                        'plan_id' => $plan->id,
                        'total_meals' => $mealIds->count(),
                        'price' => $pricing['grand_total'],
                        'coupon_id' => $couponResolution['version'] === 'v1'
                            ? ($validated['coupon_id'] ?? $couponResolution['legacy_coupon']?->id)
                            : null,
                        'coupon_v2_coupon_id' => $couponResolution['version'] === 'v2'
                            ? $couponResolution['coupon_v2']?->id
                            : null,
                        'weeks_count' => $sub['weeks_count'],
                        'days_per_week' => $sub['days_per_week'],
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'subscription_plan_type' => $sub['type'],
                    ]);

                    $variantMap = MealVariant::whereIn('meal_id', $mealIds)->pluck('id', 'meal_id');
                    $dayOrder = (int) $sub['days_per_week'] === 5
                        ? ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday']
                        : ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday'];

                    $rows = collect($sub['meals'])->map(function ($meal) use ($subscription, $startDate, $sub, $dayOrder, $variantMap) {
                        preg_match('/week(\d+)/i', $meal['meal_id'], $matches);
                        $weekIndex = isset($matches[1]) ? ((int) $matches[1]) - 1 : 0;

                        $dayIndex = array_search(strtolower($meal['day']), $dayOrder, true);
                        if ($dayIndex === false) {
                            throw new \RuntimeException("Invalid day: {$meal['day']}");
                        }

                        $offset = $weekIndex * $sub['days_per_week'] + $dayIndex;
                        $deliveryDate = $startDate->copy()->addDays($offset)->format('Y-m-d');

                        $optionNo = (int) Str::afterLast($meal['meal_id'], '_');
                        $variantId = $variantMap[$meal['meal_id']]
                            ?? throw new \RuntimeException("Meal variant not found: {$meal['meal_id']}");

                        return [
                            'subscription_id' => $subscription->id,
                            'meal_variant_id' => $variantId,
                            'day' => $deliveryDate,
                            'option_no' => $optionNo,
                            'meal_type' => $meal['meal_type'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    });

                    DailySelectedMeal::insert($rows->all());

                    if ($couponResolution['version'] === 'v2' && $couponResolution['coupon_v2'] !== null) {
                        $this->couponV2Service->createPendingUsage(
                            $couponResolution['coupon_v2'],
                            $subscription,
                            $pricing
                        );
                    }

                    $createdSubscriptions[] = $subscription->id;
                }

                return $createdSubscriptions;
            });

            if ($journey !== null) {
                $journey = $this->invitationService->markCheckoutStarted($journey, $couponResolution);
            }

            return response()->json([
                'status' => 'success',
                'subscriptions' => $createdSubscriptions,
                'invitation' => $this->invitationService->buildSnapshot($journey ?? null),
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: '.$e->getMessage(),
            ], 500);
        }
    }

    public function showCheckout(Request $request)
    {
        $addresses = Address::where('user_id', Auth::id())->get();
        $autoCapturedFromLink = $this->invitationService->hasIncomingCode($request);
        $journey = $this->invitationService->captureFromRequest($request, Auth::user());

        return view('pages.checkout', [
            'addresses' => $addresses,
            'pricingMatrix' => $this->pricingService->matrix(),
            'vatRate' => SubscriptionPricingService::VAT_RATE,
            'invitationState' => $this->invitationService->buildSnapshot($journey),
            'invitationAutoCaptured' => $autoCapturedFromLink,
            'invitationPublicEnabled' => $this->invitationService->publicEnabled(),
            'couponPublicEnabled' => $this->couponV2Service->publicEnabled(),
        ]);
    }

    private function syncPrimaryAddress(?array $billingAddress): void
    {
        if (! Auth::check() || empty($billingAddress)) {
            return;
        }

        Address::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'primary' => true,
            ],
            [
                'street_address' => trim((string) ($billingAddress['street_address'] ?? '')),
                'town_city' => trim((string) ($billingAddress['town_city'] ?? '')),
                'phone' => Auth::user()?->phone,
            ]
        );
    }

    /**
     * Return the first valid delivery day that is ≥ (today + leadTime).
     */
    public function calcStartDate(int $daysPerWeek, int $leadTimeDays = 2): Carbon
    {
        $tentative = Carbon::today()->addDays($leadTimeDays);   // earliest possible

        // Which weekday must the first delivery fall on?
        // Carbon: 0=Sun … 6=Sat
        $firstDow = $daysPerWeek === 5 ? Carbon::SUNDAY : Carbon::SATURDAY;

        // jump forward until we’re on the correct weekday
        while ($tentative->dayOfWeek !== $firstDow) {
            $tentative->addDay();
        }

        return $tentative;      // 00:00 of that day
    }

    /**
     * Count forward, skipping off-days, until we’ve reached
     * $weeks × $daysPerWeek deliveries.
     */
    public function calcEndDate(Carbon $start, int $weeks, int $daysPerWeek): Carbon
    {
        $allowedDows = $daysPerWeek === 5
            ? [Carbon::SUNDAY, Carbon::MONDAY, Carbon::TUESDAY, Carbon::WEDNESDAY, Carbon::THURSDAY]
            : [Carbon::SATURDAY, Carbon::SUNDAY, Carbon::MONDAY, Carbon::TUESDAY, Carbon::WEDNESDAY, Carbon::THURSDAY];

        $deliveriesNeeded = $weeks * $daysPerWeek;
        $delivered = 1;                // first day is already counted
        $current = $start->copy();

        while ($delivered < $deliveriesNeeded) {
            $current->addDay();

            if (in_array($current->dayOfWeek, $allowedDows, true)) {
                $delivered++;
            }
        }

        return $current;
    }
}
