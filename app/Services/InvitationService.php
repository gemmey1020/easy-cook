<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponV2Coupon;
use App\Models\InvitationCode;
use App\Models\InvitationJourney;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InvitationService
{
    public const SESSION_KEY = 'easy_cook.invitation_journey_token';
    public const COOKIE_KEY = 'easy_cook_invitation_journey';
    public const COOKIE_TTL_MINUTES = 20160;

    public function __construct(
        private readonly SubscriptionPricingService $pricingService
    ) {
    }

    public function publicEnabled(): bool
    {
        return (bool) config('release_flags.invitation_public_enabled');
    }

    public function captureFromRequest(Request $request, ?User $user = null): ?InvitationJourney
    {
        if (! $this->publicEnabled()) {
            return null;
        }

        $code = $this->extractIncomingCode($request);
        $journey = $this->restoreCurrentJourney($request, $user);

        if (blank($code)) {
            return $journey;
        }

        return $this->capture(
            code: $code,
            channel: InvitationJourney::CHANNEL_LINK,
            referralSource: $this->extractReferralSource($request),
            referralSourceNote: $request->query('referral_source_note'),
            journey: $journey
        );
    }

    public function hasIncomingCode(Request $request): bool
    {
        if (! $this->publicEnabled()) {
            return false;
        }

        return filled($this->extractIncomingCode($request));
    }

    public function restoreCurrentJourney(?Request $request = null, ?User $user = null): ?InvitationJourney
    {
        if (! $this->publicEnabled()) {
            return null;
        }

        $token = session(self::SESSION_KEY);

        if (blank($token) && $request !== null) {
            $token = $request->cookie(self::COOKIE_KEY);
        }

        $journey = $this->findJourneyByToken($token);

        if ($journey === null && $user !== null) {
            $journey = InvitationJourney::query()
                ->with(['invitationCode.defaultCouponV2', 'defaultCouponV2'])
                ->where('user_id', $user->id)
                ->whereIn('state', [
                    InvitationJourney::STATE_CAPTURED,
                    InvitationJourney::STATE_REGISTERED,
                    InvitationJourney::STATE_CHECKOUT_STARTED,
                    InvitationJourney::STATE_INVALID_CODE,
                ])
                ->latest('updated_at')
                ->first();
        }

        if ($journey !== null) {
            $journey->forceFill([
                'last_seen_at' => now(),
            ])->save();

            $this->rememberJourney($journey);
        }

        return $journey;
    }

    public function resolveJourney(?string $journeyToken = null, ?User $user = null, ?Request $request = null): ?InvitationJourney
    {
        if (! $this->publicEnabled()) {
            return null;
        }

        if (filled($journeyToken)) {
            $journey = $this->findJourneyByToken($journeyToken);

            if ($journey !== null) {
                $this->rememberJourney($journey);
            }

            return $journey;
        }

        return $this->restoreCurrentJourney($request, $user);
    }

    public function syncJourneyAfterLogin(Request $request, User $user): ?InvitationJourney
    {
        if (! $this->publicEnabled()) {
            return null;
        }

        $currentJourney = $this->resolveJourney(
            request: $request,
            user: $user
        );

        if ($currentJourney !== null && (int) ($currentJourney->user_id ?? 0) === (int) $user->id) {
            return $currentJourney;
        }

        if ($currentJourney !== null && $currentJourney->invitation_code_id !== null) {
            $matchingJourney = InvitationJourney::query()
                ->with(['invitationCode.defaultCouponV2', 'defaultCouponV2'])
                ->where('user_id', $user->id)
                ->where('invitation_code_id', $currentJourney->invitation_code_id)
                ->whereIn('state', [
                    InvitationJourney::STATE_REGISTERED,
                    InvitationJourney::STATE_CHECKOUT_STARTED,
                    InvitationJourney::STATE_PAID,
                ])
                ->latest('updated_at')
                ->first();

            if ($matchingJourney !== null) {
                $matchingJourney->forceFill([
                    'last_seen_at' => now(),
                ])->save();

                $this->rememberJourney($matchingJourney);

                return $matchingJourney;
            }
        }

        if ($currentJourney !== null) {
            return $currentJourney;
        }

        return $this->restoreCurrentJourney($request, $user);
    }

    public function capture(
        string $code,
        string $channel = InvitationJourney::CHANNEL_MANUAL,
        ?string $referralSource = null,
        ?string $referralSourceNote = null,
        ?InvitationJourney $journey = null
    ): InvitationJourney {
        if (! $this->publicEnabled()) {
            throw ValidationException::withMessages([
                'code' => ['Invitation codes are not available right now.'],
            ]);
        }

        $trimmedCode = trim($code);

        if ($trimmedCode === '') {
            throw ValidationException::withMessages([
                'code' => ['Invitation code is required.'],
            ]);
        }

        $journey ??= new InvitationJourney([
            'journey_token' => (string) Str::uuid(),
        ]);

        $invitationCode = InvitationCode::query()
            ->with('defaultCouponV2')
            ->matchingCode($trimmedCode)
            ->first();

        $journey->fill([
            'capture_channel' => $channel,
            'raw_invitation_code' => $trimmedCode,
            'referral_source' => $referralSource ?: $journey->referral_source,
            'referral_source_note' => $referralSourceNote,
            'last_seen_at' => now(),
        ]);

        if ($invitationCode !== null && $invitationCode->isActiveForCapture()) {
            $defaultCoupon = $invitationCode->defaultCouponV2;

            $journey->fill([
                'invitation_code_id' => $invitationCode->id,
                'state' => InvitationJourney::STATE_CAPTURED,
                'default_coupon_v2_coupon_id' => $defaultCoupon?->id,
                'active_coupon_version' => 'invitation',
                'active_coupon_id' => $invitationCode->id,
                'active_coupon_code' => $invitationCode->code,
                'override_coupon_version' => null,
                'override_coupon_id' => null,
                'override_coupon_code' => null,
                'last_invalid_code' => null,
                'invalid_reason' => null,
                'invalid_recorded_at' => null,
                'captured_at' => $journey->captured_at ?? now(),
            ]);
        } else {
            $journey->fill([
                'invitation_code_id' => null,
                'state' => InvitationJourney::STATE_INVALID_CODE,
                'default_coupon_v2_coupon_id' => null,
                'active_coupon_version' => null,
                'active_coupon_id' => null,
                'active_coupon_code' => null,
                'override_coupon_version' => null,
                'override_coupon_id' => null,
                'override_coupon_code' => null,
                'last_invalid_code' => $trimmedCode,
                'invalid_reason' => $this->resolveInvalidReason($invitationCode),
                'invalid_recorded_at' => now(),
            ]);
        }

        $journey->save();
        $journey->loadMissing(['invitationCode.defaultCouponV2', 'defaultCouponV2']);

        $this->rememberJourney($journey);

        return $journey;
    }

    public function resolveJourneyForRegistration(
        ?string $journeyToken,
        ?string $invitationCode,
        ?string $referralSource,
        ?string $referralSourceNote
    ): ?InvitationJourney {
        if (! $this->publicEnabled()) {
            return null;
        }

        $journey = $this->resolveJourney($journeyToken);

        if (filled($invitationCode) && $this->normalizedCode($journey?->raw_invitation_code) !== $this->normalizedCode($invitationCode)) {
            $journey = $this->capture(
                code: $invitationCode,
                channel: InvitationJourney::CHANNEL_MANUAL,
                referralSource: $referralSource,
                referralSourceNote: $referralSourceNote,
                journey: $journey
            );
        }

        if ($journey !== null) {
            $this->updateReferral($journey, $referralSource, $referralSourceNote);
        }

        return $journey;
    }

    public function attachRegisteredUser(
        InvitationJourney $journey,
        User $user,
        ?string $referralSource = null,
        ?string $referralSourceNote = null
    ): InvitationJourney {
        $this->updateReferral($journey, $referralSource, $referralSourceNote);

        $journey->fill([
            'user_id' => $user->id,
            'state' => InvitationJourney::STATE_REGISTERED,
            'registered_at' => $journey->registered_at ?? now(),
            'last_seen_at' => now(),
        ])->save();

        $journey->loadMissing(['invitationCode', 'defaultCouponV2']);

        if (
            $journey->invitationCode !== null
            && $journey->invitationCode->owner_type === InvitationCode::OWNER_PARTNER
            && $journey->invitationCode->owner_user_id !== null
        ) {
            $user->forceFill([
                'user_origin_id' => $journey->invitationCode->owner_user_id,
            ])->save();
        }

        $this->rememberJourney($journey);

        return $journey;
    }

    public function prepareCouponSelection(
        ?InvitationJourney $journey,
        ?array $appliedCoupon,
        ?int $legacyCouponId,
        bool $confirmOverride = false
    ): array {
        if (! $this->publicEnabled()) {
            return [
                'applied_coupon' => $appliedCoupon,
                'legacy_coupon_id' => $legacyCouponId,
            ];
        }

        if ($journey === null) {
            return [
                'applied_coupon' => $appliedCoupon,
                'legacy_coupon_id' => $legacyCouponId,
            ];
        }

        $defaultCoupon = $journey->defaultCouponPayload();
        $activeCoupon = $journey->currentCouponPayload();
        $requestedCoupon = $this->normalizeRequestedCoupon($appliedCoupon, $legacyCouponId);
        $eligibility = $this->resolveDiscountEligibility($journey);

        if ($requestedCoupon === null) {
            if ($activeCoupon === null) {
                return [
                    'applied_coupon' => null,
                    'legacy_coupon_id' => null,
                ];
            }

            if (
                ($activeCoupon['version'] ?? null) === 'invitation'
                && $defaultCoupon !== null
                && $this->couponsMatch($activeCoupon, $defaultCoupon)
            ) {
                return [
                    'applied_coupon' => null,
                    'legacy_coupon_id' => null,
                ];
            }

            return [
                'applied_coupon' => $activeCoupon,
                'legacy_coupon_id' => $activeCoupon['version'] === 'v1' ? $activeCoupon['id'] : null,
            ];
        }

        if (($requestedCoupon['version'] ?? null) === 'invitation') {
            if ($defaultCoupon === null || ! $this->couponsMatch($requestedCoupon, $defaultCoupon)) {
                throw ValidationException::withMessages([
                    'applied_coupon' => ['Invitation discount can be applied only from the active invitation journey.'],
                ]);
            }

            if (! ($eligibility['is_discount_eligible'] ?? false)) {
                throw ValidationException::withMessages([
                    'applied_coupon' => [$eligibility['eligibility_message'] ?? 'This invitation discount is not available for this account.'],
                ]);
            }

            $this->restoreDefaultCouponAsActive($journey);

            return [
                'applied_coupon' => $defaultCoupon,
                'legacy_coupon_id' => null,
            ];
        }

        if (
            $defaultCoupon !== null
            && ($eligibility['is_discount_eligible'] ?? false)
            && ! $this->couponsMatch($requestedCoupon, $defaultCoupon)
            && ! $confirmOverride
        ) {
            throw ValidationException::withMessages([
                'confirm_invitation_override' => ['Please confirm replacing the invitation default coupon before checkout.'],
            ]);
        }

        if ($defaultCoupon !== null && $this->couponsMatch($requestedCoupon, $defaultCoupon)) {
            $this->restoreDefaultCouponAsActive($journey);
        }

        return [
            'applied_coupon' => $appliedCoupon,
            'legacy_coupon_id' => $legacyCouponId,
        ];
    }

    public function syncJourneyAfterCouponApplied(
        ?InvitationJourney $journey,
        array $couponPayload,
        bool $confirmedOverride = false
    ): void {
        if ($journey === null) {
            return;
        }

        $defaultCoupon = $journey->defaultCouponPayload();
        $resolvedCoupon = [
            'version' => $couponPayload['version'] ?? null,
            'id' => isset($couponPayload['id']) ? (int) $couponPayload['id'] : null,
            'code' => $couponPayload['code'] ?? null,
        ];

        if ($defaultCoupon !== null && $this->couponsMatch($resolvedCoupon, $defaultCoupon)) {
            $this->restoreDefaultCouponAsActive($journey);

            return;
        }

        if (! $confirmedOverride) {
            return;
        }

        $journey->fill([
            'active_coupon_version' => $resolvedCoupon['version'],
            'active_coupon_id' => $resolvedCoupon['id'],
            'active_coupon_code' => $resolvedCoupon['code'],
            'override_coupon_version' => $resolvedCoupon['version'],
            'override_coupon_id' => $resolvedCoupon['id'],
            'override_coupon_code' => $resolvedCoupon['code'],
            'last_seen_at' => now(),
        ])->save();
    }

    public function restoreDefaultCouponAsActive(InvitationJourney $journey): void
    {
        $defaultCoupon = $journey->defaultCouponPayload();

        $journey->fill([
            'active_coupon_version' => $defaultCoupon['version'] ?? null,
            'active_coupon_id' => $defaultCoupon['id'] ?? null,
            'active_coupon_code' => $defaultCoupon['code'] ?? null,
            'override_coupon_version' => null,
            'override_coupon_id' => null,
            'override_coupon_code' => null,
            'last_seen_at' => now(),
        ])->save();
    }

    public function markCheckoutStarted(InvitationJourney $journey, ?array $couponResolution = null): InvitationJourney
    {
        if ($couponResolution !== null) {
            if (($couponResolution['version'] ?? null) === 'invitation') {
                $this->restoreDefaultCouponAsActive($journey);
            }

            if (($couponResolution['version'] ?? null) === 'v2' && ($couponResolution['coupon_v2'] ?? null) !== null) {
                $resolvedCoupon = [
                    'version' => 'v2',
                    'id' => $couponResolution['coupon_v2']->id,
                    'code' => $couponResolution['coupon_v2']->code,
                ];

                $defaultCoupon = $journey->defaultCouponPayload();

                if ($defaultCoupon !== null && ! $this->couponsMatch($resolvedCoupon, $defaultCoupon)) {
                    $journey->fill([
                        'active_coupon_version' => 'v2',
                        'active_coupon_id' => $couponResolution['coupon_v2']->id,
                        'active_coupon_code' => $couponResolution['coupon_v2']->code,
                        'override_coupon_version' => 'v2',
                        'override_coupon_id' => $couponResolution['coupon_v2']->id,
                        'override_coupon_code' => $couponResolution['coupon_v2']->code,
                    ])->save();
                } elseif ($defaultCoupon === null) {
                    $journey->fill([
                        'active_coupon_version' => 'v2',
                        'active_coupon_id' => $couponResolution['coupon_v2']->id,
                        'active_coupon_code' => $couponResolution['coupon_v2']->code,
                    ])->save();
                } else {
                    $this->restoreDefaultCouponAsActive($journey);
                }
            }

            if (($couponResolution['version'] ?? null) === 'v1' && ($couponResolution['legacy_coupon'] ?? null) !== null) {
                $journey->fill([
                    'active_coupon_version' => 'v1',
                    'active_coupon_id' => $couponResolution['legacy_coupon']->id,
                    'active_coupon_code' => $couponResolution['legacy_coupon']->code,
                    'override_coupon_version' => 'v1',
                    'override_coupon_id' => $couponResolution['legacy_coupon']->id,
                    'override_coupon_code' => $couponResolution['legacy_coupon']->code,
                ])->save();
            }
        }

        $journey->fill([
            'state' => InvitationJourney::STATE_CHECKOUT_STARTED,
            'checkout_started_at' => $journey->checkout_started_at ?? now(),
            'last_seen_at' => now(),
        ])->save();

        return $journey;
    }

    public function discountEligibility(?InvitationJourney $journey, ?int $userId = null): array
    {
        if (! $this->publicEnabled()) {
            return [
                'registered_with_same_code' => false,
                'is_discount_eligible' => false,
                'eligibility_message' => null,
            ];
        }

        return $this->resolveDiscountEligibility($journey, $userId);
    }

    public function syncJourneyForPayment(Payment $payment, string $paymentStatus): void
    {
        $journey = $payment->invitationJourney;

        if ($journey === null) {
            return;
        }

        if ($paymentStatus === 'paid') {
            $journey->fill([
                'payment_id' => $payment->id,
                'state' => InvitationJourney::STATE_PAID,
                'paid_at' => $journey->paid_at ?? now(),
                'last_seen_at' => now(),
            ])->save();

            $this->forgetActiveJourney($journey->journey_token);

            return;
        }

        $journey->fill([
            'payment_id' => $payment->id,
            'last_seen_at' => now(),
        ])->save();
    }

    public function findJourneyByToken(?string $token): ?InvitationJourney
    {
        if (blank($token)) {
            return null;
        }

        return InvitationJourney::query()
            ->with(['invitationCode.defaultCouponV2', 'defaultCouponV2'])
            ->where('journey_token', $token)
            ->first();
    }

    public function applyCapturedInvitationCode(
        ?InvitationJourney $journey,
        string $code,
        ?array $subscriptionContext = null,
        ?int $userId = null
    ): ?array {
        if (! $this->publicEnabled()) {
            return null;
        }

        $normalizedCode = $this->normalizedCode($code);

        if ($journey === null) {
            return null;
        }

        if (
            $journey->state === InvitationJourney::STATE_INVALID_CODE
            && $normalizedCode !== ''
            && $normalizedCode === $this->normalizedCode($journey->last_invalid_code ?? $journey->raw_invitation_code)
        ) {
            throw ValidationException::withMessages([
                'code' => [$this->buildExplicitInvalidCodeMessage($journey->last_invalid_code ?? $journey->raw_invitation_code ?? $code)],
            ]);
        }

        $invitationCode = $journey->invitationCode;

        if ($invitationCode === null) {
            return null;
        }

        if ($normalizedCode !== $this->normalizedCode($invitationCode->code)) {
            return null;
        }

        $eligibility = $this->resolveDiscountEligibility($journey, $userId);

        if (! ($eligibility['is_discount_eligible'] ?? false)) {
            throw ValidationException::withMessages([
                'code' => [$eligibility['eligibility_message'] ?? 'This invitation code cannot be used right now.'],
            ]);
        }

        $discount = $invitationCode->discountPayload();

        if ($discount === null) {
            throw ValidationException::withMessages([
                'code' => [$this->buildExplicitInvalidCodeMessage($invitationCode->code)],
            ]);
        }

        return [
            'success' => true,
            'message' => "Invitation code {$invitationCode->code} was applied successfully.",
            'version' => 'invitation',
            'id' => $invitationCode->id,
            'code' => $invitationCode->code,
            'discount_type' => $discount['type'],
            'discount_value' => $discount['value'],
            'discount_label' => $invitationCode->discountLabel(),
            'pricing' => $subscriptionContext
                ? $this->pricingService->calculateForSubscription($subscriptionContext, $discount)
                : null,
            'owner_type' => $invitationCode->owner_type,
            'owner_user_id' => $invitationCode->owner_user_id,
            'state' => $invitationCode->derivedStatus(),
        ];
    }

    public function findCouponSummary(?string $version, ?int $id): ?array
    {
        if ($version === 'invitation' && $id !== null) {
            $invitationCode = InvitationCode::find($id);

            if ($invitationCode !== null) {
                $discount = $invitationCode->discountPayload();

                return [
                    'version' => 'invitation',
                    'id' => $invitationCode->id,
                    'code' => $invitationCode->code,
                    'discount_type' => $discount['type'] ?? null,
                    'discount_value' => $discount['value'] ?? null,
                    'discount_label' => $invitationCode->discountLabel(),
                ];
            }
        }

        if ($version === 'v2' && $id !== null) {
            $coupon = CouponV2Coupon::find($id);

            if ($coupon !== null) {
                return [
                    'version' => 'v2',
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'discount_type' => $coupon->discount_type,
                    'discount_value' => (float) $coupon->discount_value,
                ];
            }
        }

        if ($version === 'v1' && $id !== null) {
            $coupon = Coupon::find($id);

            if ($coupon !== null) {
                return [
                    'version' => 'v1',
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'discount_type' => CouponV2Coupon::TYPE_PERCENT,
                    'discount_value' => (float) $coupon->discount,
                ];
            }
        }

        return null;
    }

    public function buildSnapshot(?InvitationJourney $journey): array
    {
        if (! $this->publicEnabled()) {
            return [
                'token' => null,
                'state' => null,
                'is_valid' => false,
                'code' => null,
                'message' => null,
                'referral_source' => null,
                'referral_source_note' => null,
                'default_coupon' => null,
                'active_coupon' => null,
                'has_override' => false,
                'owner_type' => null,
                'owner_user_id' => null,
                'registered_with_same_code' => false,
                'is_discount_eligible' => false,
                'eligibility_message' => null,
            ];
        }

        if ($journey === null) {
            return [
                'token' => null,
                'state' => null,
                'is_valid' => false,
                'code' => null,
                'message' => null,
                'referral_source' => null,
                'referral_source_note' => null,
                'default_coupon' => null,
                'active_coupon' => null,
                'has_override' => false,
                'owner_type' => null,
                'owner_user_id' => null,
                'registered_with_same_code' => false,
                'is_discount_eligible' => false,
                'eligibility_message' => null,
            ];
        }

        $journey->loadMissing(['invitationCode.defaultCouponV2', 'defaultCouponV2']);

        $defaultCoupon = $journey->defaultCouponPayload();
        $activeCoupon = $journey->currentCouponPayload();
        $eligibility = $this->resolveDiscountEligibility($journey);

        if ($activeCoupon !== null && blank($activeCoupon['code'])) {
            $activeCoupon = $this->findCouponSummary($activeCoupon['version'], $activeCoupon['id']) ?? $activeCoupon;
        }

        return [
            'token' => $journey->journey_token,
            'state' => $journey->state,
            'is_valid' => $journey->isValidInvitation(),
            'code' => $journey->invitationCode?->code ?? $journey->raw_invitation_code,
            'message' => $this->buildJourneyMessage($journey),
            'referral_source' => $journey->referral_source,
            'referral_source_note' => $journey->referral_source_note,
            'default_coupon' => $defaultCoupon,
            'active_coupon' => $activeCoupon,
            'has_override' => $journey->hasActiveOverride(),
            'owner_type' => $journey->invitationCode?->owner_type,
            'owner_user_id' => $journey->invitationCode?->owner_user_id,
            'registered_with_same_code' => $eligibility['registered_with_same_code'] ?? false,
            'is_discount_eligible' => $eligibility['is_discount_eligible'] ?? false,
            'eligibility_message' => $eligibility['eligibility_message'] ?? null,
            'discount_type' => $defaultCoupon['discount_type'] ?? null,
            'discount_value' => $defaultCoupon['discount_value'] ?? null,
            'discount_label' => $defaultCoupon['discount_label'] ?? $journey->invitationCode?->discountLabel(),
            'max_redemptions' => $journey->invitationCode?->max_redemptions,
            'remaining_redemptions' => $journey->invitationCode?->remainingRedemptions(),
            'invalid_reason' => $journey->invalid_reason,
            'last_invalid_code' => $journey->last_invalid_code,
        ];
    }

    public function generateUniqueCode(string $ownerType): string
    {
        $prefix = $ownerType === InvitationCode::OWNER_PARTNER ? 'PTR' : 'INV';

        do {
            $code = $prefix.'-'.strtoupper(Str::random(6));
        } while (InvitationCode::query()->matchingCode($code)->exists());

        return $code;
    }

    public function rememberJourney(InvitationJourney $journey): void
    {
        session([self::SESSION_KEY => $journey->journey_token]);

        Cookie::queue(cookie(
            self::COOKIE_KEY,
            $journey->journey_token,
            self::COOKIE_TTL_MINUTES,
            '/',
            null,
            false,
            false,
            false,
            'lax'
        ));
    }

    public function forgetActiveJourney(?string $expectedToken = null): void
    {
        $current = session(self::SESSION_KEY);

        if ($expectedToken !== null && filled($current) && $current !== $expectedToken) {
            return;
        }

        session()->forget(self::SESSION_KEY);
        Cookie::queue(Cookie::forget(self::COOKIE_KEY));
    }

    private function updateReferral(InvitationJourney $journey, ?string $referralSource, ?string $referralSourceNote): void
    {
        $journey->fill([
            'referral_source' => $referralSource ?: $journey->referral_source,
            'referral_source_note' => $referralSource === 'other'
                ? $referralSourceNote
                : ($referralSource ? null : $journey->referral_source_note),
            'last_seen_at' => now(),
        ])->save();
    }

    private function extractIncomingCode(Request $request): ?string
    {
        foreach (['invitation_code', 'invite_code', 'invite', 'code'] as $key) {
            $value = $request->query($key);

            if (filled($value)) {
                return (string) $value;
            }
        }

        return null;
    }

    private function extractReferralSource(Request $request): ?string
    {
        foreach (['referral_source', 'source', 'ref'] as $key) {
            $value = $request->query($key);

            if (filled($value) && in_array($value, InvitationJourney::referralSources(), true)) {
                return (string) $value;
            }
        }

        return null;
    }

    private function resolveInvalidReason(?InvitationCode $invitationCode): string
    {
        if ($invitationCode === null) {
            return 'not_found';
        }

        if (! $invitationCode->hasDiscountConfigured()) {
            return 'discount_not_configured';
        }

        if ($invitationCode->status !== InvitationCode::STATUS_ACTIVE) {
            return 'inactive';
        }

        if ($invitationCode->starts_at !== null && now()->lt($invitationCode->starts_at)) {
            return 'not_started';
        }

        if ($invitationCode->expires_at !== null && now()->gt($invitationCode->expires_at)) {
            return 'expired';
        }

        if ($invitationCode->isExhausted()) {
            return 'exhausted';
        }

        return 'inactive';
    }

    private function normalizeRequestedCoupon(?array $appliedCoupon, ?int $legacyCouponId): ?array
    {
        if ($appliedCoupon !== null && ! empty($appliedCoupon['version']) && ! empty($appliedCoupon['id'])) {
            return [
                'version' => $appliedCoupon['version'],
                'id' => (int) $appliedCoupon['id'],
                'code' => $appliedCoupon['code'] ?? null,
            ];
        }

        if ($legacyCouponId !== null) {
            return [
                'version' => 'v1',
                'id' => $legacyCouponId,
                'code' => null,
            ];
        }

        return null;
    }

    private function couponsMatch(?array $left, ?array $right): bool
    {
        if ($left === null || $right === null) {
            return false;
        }

        return ($left['version'] ?? null) === ($right['version'] ?? null)
            && (int) ($left['id'] ?? 0) === (int) ($right['id'] ?? 0);
    }

    private function buildJourneyMessage(InvitationJourney $journey): string
    {
        if ($journey->state === InvitationJourney::STATE_INVALID_CODE) {
            return match ($journey->invalid_reason) {
                'inactive' => "Invitation code {$this->displayCode($journey)} is not active right now. You can continue without it.",
                'not_started' => "Invitation code {$this->displayCode($journey)} is not active yet. You can continue without it.",
                'expired' => "Invitation code {$this->displayCode($journey)} has expired. You can continue without it.",
                'exhausted' => "Invitation code {$this->displayCode($journey)} has reached its usage limit. You can continue without it.",
                default => $this->buildExplicitInvalidCodeMessage($journey->last_invalid_code ?? $journey->raw_invitation_code),
            };
        }

        if ($journey->hasActiveOverride()) {
            return 'Invitation truth is saved and another coupon is currently active.';
        }

        if ($journey->state === InvitationJourney::STATE_PAID) {
            return 'Invitation journey has already reached payment.';
        }

        $eligibility = $this->resolveDiscountEligibility($journey);

        if (! ($eligibility['is_discount_eligible'] ?? false) && filled($eligibility['eligibility_message'] ?? null)) {
            return $eligibility['eligibility_message'];
        }

        $discountLabel = $journey->invitationCode?->discountLabel();

        if ($discountLabel !== null) {
            return "Invitation code captured successfully. {$discountLabel} discount is ready for checkout.";
        }

        return 'Invitation code captured successfully. You can keep it or choose another coupon later in checkout.';
    }

    private function normalizedCode(?string $code): string
    {
        return InvitationCode::normalizeCode($code);
    }

    private function buildExplicitInvalidCodeMessage(?string $code): string
    {
        $displayCode = $this->displayCode($code);

        return "Invitation code {$displayCode} is invalid. You can continue without it.";
    }

    private function resolveDiscountEligibility(?InvitationJourney $journey, ?int $userId = null): array
    {
        if (! $this->publicEnabled()) {
            return [
                'registered_with_same_code' => false,
                'is_discount_eligible' => false,
                'eligibility_message' => null,
            ];
        }

        $resolvedUserId = $userId ?? auth()->id();

        if ($journey === null || $journey->invitationCode === null) {
            return [
                'registered_with_same_code' => false,
                'is_discount_eligible' => false,
                'eligibility_message' => null,
            ];
        }

        $invitationCode = $journey->invitationCode;

        if (! $invitationCode->hasDiscountConfigured()) {
            return [
                'registered_with_same_code' => false,
                'is_discount_eligible' => false,
                'eligibility_message' => $this->buildExplicitInvalidCodeMessage($invitationCode->code),
            ];
        }

        if ($invitationCode->status !== InvitationCode::STATUS_ACTIVE) {
            return [
                'registered_with_same_code' => false,
                'is_discount_eligible' => false,
                'eligibility_message' => "Invitation code {$invitationCode->code} is not active right now.",
            ];
        }

        if ($invitationCode->starts_at !== null && now()->lt($invitationCode->starts_at)) {
            return [
                'registered_with_same_code' => false,
                'is_discount_eligible' => false,
                'eligibility_message' => "Invitation code {$invitationCode->code} is not active yet.",
            ];
        }

        if ($invitationCode->expires_at !== null && now()->gt($invitationCode->expires_at)) {
            return [
                'registered_with_same_code' => false,
                'is_discount_eligible' => false,
                'eligibility_message' => "Invitation code {$invitationCode->code} has expired.",
            ];
        }

        if ($invitationCode->isExhausted()) {
            return [
                'registered_with_same_code' => false,
                'is_discount_eligible' => false,
                'eligibility_message' => "Invitation code {$invitationCode->code} has reached its usage limit.",
            ];
        }

        if ($resolvedUserId === null) {
            return [
                'registered_with_same_code' => false,
                'is_discount_eligible' => false,
                'eligibility_message' => $journey->user_id !== null
                    ? 'Sign in to the account that registered with this invitation code to use the discount.'
                    : 'Register your account with this invitation code first to unlock the discount in checkout.',
            ];
        }

        $registeredWithSameCode = (int) ($journey->user_id ?? 0) === (int) $resolvedUserId
            && in_array($journey->state, [
                InvitationJourney::STATE_REGISTERED,
                InvitationJourney::STATE_CHECKOUT_STARTED,
                InvitationJourney::STATE_PAID,
            ], true);

        if (! $registeredWithSameCode) {
            return [
                'registered_with_same_code' => false,
                'is_discount_eligible' => false,
                'eligibility_message' => 'This invitation discount is available only for accounts registered with this invitation code.',
            ];
        }

        if ($invitationCode->hasPaidUsageForUser($resolvedUserId)) {
            return [
                'registered_with_same_code' => true,
                'is_discount_eligible' => false,
                'eligibility_message' => 'You have already used this invitation code.',
            ];
        }

        return [
            'registered_with_same_code' => true,
            'is_discount_eligible' => true,
            'eligibility_message' => $invitationCode->discountLabel() !== null
                ? "Invitation code captured successfully. {$invitationCode->discountLabel()} discount is ready for checkout."
                : 'Invitation code captured successfully. The discount is ready for checkout.',
        ];
    }

    private function displayCode(InvitationJourney|string|null $value): string
    {
        if ($value instanceof InvitationJourney) {
            $value = $value->last_invalid_code ?? $value->raw_invitation_code ?? $value->invitationCode?->code;
        }

        $code = trim((string) $value);

        return $code !== '' ? $code : 'UNKNOWN';
    }
}
