<?php

namespace App\Http\Controllers;

use App\Services\CouponV2Service;
use App\Services\InvitationService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class CouponController extends Controller
{
    public function __construct(
        private readonly CouponV2Service $couponV2Service,
        private readonly InvitationService $invitationService
    ) {
    }

    public function apply(Request $request)
    {
        if (! $this->invitationService->publicEnabled() && ! $this->couponV2Service->publicEnabled()) {
            abort(404);
        }

        $request->validate([
            'code' => 'required|string|max:100',
            'subscription' => 'nullable|array',
            'subscription.plan_id' => 'required_with:subscription|string',
            'subscription.days_per_week' => 'required_with:subscription|integer|min:5|max:6',
            'subscription.weeks_count' => 'nullable|integer|min:1',
            'subscription.type' => 'nullable|string',
            'invitation_journey_token' => 'nullable|string|max:100',
            'confirm_invitation_override' => 'nullable|boolean',
        ]);

        try {
            $journey = $this->invitationService->resolveJourney(
                $request->input('invitation_journey_token'),
                auth()->user(),
                $request
            );

            $result = $this->invitationService->applyCapturedInvitationCode(
                journey: $journey,
                code: $request->input('code'),
                subscriptionContext: $request->input('subscription'),
                userId: auth()->id()
            ) ?? $this->couponV2Service->applyCode(
                code: $request->input('code'),
                subscriptionContext: $request->input('subscription'),
                userId: auth()->id()
            );

            $defaultCoupon = $journey?->defaultCouponPayload();
            $eligibility = $this->invitationService->discountEligibility($journey, auth()->id());
            $requestedCouponIsDefault = $defaultCoupon !== null
                && ($result['version'] ?? null) === ($defaultCoupon['version'] ?? null)
                && (int) ($result['id'] ?? 0) === (int) ($defaultCoupon['id'] ?? 0);

            if (
                $journey !== null
                && $defaultCoupon !== null
                && ($eligibility['is_discount_eligible'] ?? false)
                && ! $requestedCouponIsDefault
                && ! $request->boolean('confirm_invitation_override')
            ) {
                return response()->json([
                    'success' => false,
                    'requires_confirmation' => true,
                    'message' => 'Please confirm replacing the invitation default coupon before applying another coupon.',
                    'invitation' => $this->invitationService->buildSnapshot($journey),
                ], 409);
            }

            $this->invitationService->syncJourneyAfterCouponApplied(
                $journey,
                $result,
                $request->boolean('confirm_invitation_override')
            );

            return response()->json(array_merge($result, [
                'invitation' => $this->invitationService->buildSnapshot($journey),
            ]));
        } catch (ValidationException $e) {
            $message = collect($e->errors())->flatten()->first() ?? 'Invalid or expired coupon code.';

            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $e->errors(),
            ], 422);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
