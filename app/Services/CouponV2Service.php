<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponV2Coupon;
use App\Models\CouponV2Usage;
use App\Models\InvitationCode;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Validation\ValidationException;
use Throwable;

class CouponV2Service
{
    public function __construct(
        private readonly SubscriptionPricingService $pricingService
    ) {
    }

    public function publicEnabled(): bool
    {
        return (bool) config('release_flags.coupon_public_enabled');
    }

    public function applyCode(string $code, ?array $subscriptionContext = null, ?int $userId = null): array
    {
        if (! $this->publicEnabled()) {
            throw ValidationException::withMessages([
                'code' => ['Coupon codes are not available right now.'],
            ]);
        }

        $couponV2 = $this->findV2ByCode($code);

        if ($couponV2 !== null) {
            $this->assertCouponUsable($couponV2, $userId);

            return $this->buildResponse(
                version: 'v2',
                id: $couponV2->id,
                code: $couponV2->code,
                discountType: $couponV2->discount_type,
                discountValue: (float) $couponV2->discount_value,
                pricing: $subscriptionContext ? $this->pricingService->calculateForSubscription($subscriptionContext, [
                    'type' => $couponV2->discount_type,
                    'value' => (float) $couponV2->discount_value,
                ]) : null,
                ownerType: $couponV2->owner_type,
                ownerUserId: $couponV2->owner_user_id,
                state: $couponV2->derivedStatus(),
            );
        }

        $legacyCoupon = $this->findLegacyByCode($code);

        if ($legacyCoupon === null) {
            throw ValidationException::withMessages([
                'code' => ['Invalid or expired coupon code.'],
            ]);
        }

        if ($this->isLegacyExpired($legacyCoupon)) {
            throw ValidationException::withMessages([
                'code' => ['Invalid or expired coupon code.'],
            ]);
        }

        return $this->buildResponse(
            version: 'v1',
            id: $legacyCoupon->id,
            code: $legacyCoupon->code,
            discountType: CouponV2Coupon::TYPE_PERCENT,
            discountValue: (float) $legacyCoupon->discount,
            pricing: $subscriptionContext ? $this->pricingService->calculateForSubscription($subscriptionContext, [
                'type' => CouponV2Coupon::TYPE_PERCENT,
                'value' => (float) $legacyCoupon->discount,
            ]) : null,
            ownerType: CouponV2Coupon::OWNER_SYSTEM,
            ownerUserId: null,
            state: $this->isLegacyExpired($legacyCoupon) ? 'expired' : CouponV2Coupon::STATUS_ACTIVE,
        );
    }

    public function resolveForCheckout(?array $appliedCoupon, ?int $legacyCouponId, array $subscriptions, ?int $userId = null): array
    {
        $version = $appliedCoupon['version'] ?? null;

        if ($version === 'invitation') {
            if (! config('release_flags.invitation_public_enabled')) {
                throw ValidationException::withMessages([
                    'applied_coupon' => ['Invitation discounts are not available right now.'],
                ]);
            }

            if (count($subscriptions) !== 1) {
                throw ValidationException::withMessages([
                    'applied_coupon' => ['Invitation discounts currently support exactly one subscription per checkout.'],
                ]);
            }

            $invitationCode = null;

            if (! empty($appliedCoupon['id'])) {
                $invitationCode = InvitationCode::find($appliedCoupon['id']);
            }

            if ($invitationCode === null && ! empty($appliedCoupon['code'])) {
                $invitationCode = InvitationCode::query()
                    ->matchingCode($appliedCoupon['code'])
                    ->first();
            }

            if ($invitationCode === null || ! $invitationCode->hasDiscountConfigured()) {
                throw ValidationException::withMessages([
                    'applied_coupon' => ['The selected invitation discount could not be found.'],
                ]);
            }

            if ($invitationCode->status !== InvitationCode::STATUS_ACTIVE) {
                throw ValidationException::withMessages([
                    'applied_coupon' => ['This invitation code is not active right now.'],
                ]);
            }

            if ($invitationCode->starts_at !== null && now()->lt($invitationCode->starts_at)) {
                throw ValidationException::withMessages([
                    'applied_coupon' => ['This invitation code is not active yet.'],
                ]);
            }

            if ($invitationCode->expires_at !== null && now()->gt($invitationCode->expires_at)) {
                throw ValidationException::withMessages([
                    'applied_coupon' => ['This invitation code has expired.'],
                ]);
            }

            if ($invitationCode->isExhausted()) {
                throw ValidationException::withMessages([
                    'applied_coupon' => ['This invitation code has reached its usage limit.'],
                ]);
            }

            if ($userId !== null && $invitationCode->hasPaidUsageForUser($userId)) {
                throw ValidationException::withMessages([
                    'applied_coupon' => ['You have already used this invitation code.'],
                ]);
            }

            $discount = $invitationCode->discountPayload();

            return [
                'version' => 'invitation',
                'invitation_code' => $invitationCode,
                'coupon_v2' => null,
                'legacy_coupon' => null,
                'pricing_by_index' => [
                    0 => $this->pricingService->calculateForSubscription($subscriptions[0], $discount),
                ],
            ];
        }

        if ($version === 'v2') {
            if (! $this->publicEnabled()) {
                throw ValidationException::withMessages([
                    'applied_coupon' => ['Coupon codes are not available right now.'],
                ]);
            }

            if (count($subscriptions) !== 1) {
                throw ValidationException::withMessages([
                    'applied_coupon' => ['Coupon V2 currently supports exactly one subscription per checkout.'],
                ]);
            }

            $coupon = null;

            if (! empty($appliedCoupon['id'])) {
                $coupon = CouponV2Coupon::find($appliedCoupon['id']);
            }

            if ($coupon === null && ! empty($appliedCoupon['code'])) {
                $coupon = $this->findV2ByCode($appliedCoupon['code']);
            }

            if ($coupon === null) {
                throw ValidationException::withMessages([
                    'applied_coupon' => ['The selected coupon could not be found.'],
                ]);
            }

            $this->assertCouponUsable($coupon, $userId);

            return [
                'version' => 'v2',
                'coupon_v2' => $coupon,
                'legacy_coupon' => null,
                'pricing_by_index' => [
                    0 => $this->pricingService->calculateForSubscription($subscriptions[0], [
                        'type' => $coupon->discount_type,
                        'value' => (float) $coupon->discount_value,
                    ]),
                ],
            ];
        }

        $legacyCoupon = null;

        if ($legacyCouponId !== null) {
            $legacyCoupon = Coupon::find($legacyCouponId);
        } elseif ($version === 'v1' && ! empty($appliedCoupon['id'])) {
            $legacyCoupon = Coupon::find($appliedCoupon['id']);
        } elseif ($version === 'v1' && ! empty($appliedCoupon['code'])) {
            $legacyCoupon = $this->findLegacyByCode($appliedCoupon['code']);
        }

        if ($legacyCoupon !== null && $this->isLegacyExpired($legacyCoupon)) {
            throw ValidationException::withMessages([
                'coupon_id' => ['Invalid or expired coupon.'],
            ]);
        }

        if ($legacyCoupon !== null && ! $this->publicEnabled()) {
            throw ValidationException::withMessages([
                'coupon_id' => ['Coupon codes are not available right now.'],
            ]);
        }

        $pricingByIndex = [];

        foreach ($subscriptions as $index => $subscription) {
            $pricingByIndex[$index] = $this->pricingService->calculateForSubscription(
                $subscription,
                $legacyCoupon ? [
                    'type' => CouponV2Coupon::TYPE_PERCENT,
                    'value' => (float) $legacyCoupon->discount,
                ] : null
            );
        }

        return [
            'version' => $legacyCoupon ? 'v1' : null,
            'coupon_v2' => null,
            'legacy_coupon' => $legacyCoupon,
            'pricing_by_index' => $pricingByIndex,
        ];
    }

    public function createPendingUsage(CouponV2Coupon $coupon, Subscription $subscription, array $pricing): CouponV2Usage
    {
        return CouponV2Usage::updateOrCreate(
            ['subscription_id' => $subscription->id],
            [
                'coupon_v2_coupon_id' => $coupon->id,
                'user_id' => $subscription->user_id,
                'payment_id' => null,
                'payment_status' => CouponV2Usage::STATUS_PENDING_PAYMENT,
                'total_before_discount' => $pricing['total_before_discount'],
                'discount_amount' => $pricing['discount_amount'],
                'total_after_discount' => $pricing['total_after_discount'],
                'vat_amount' => $pricing['vat_amount'],
                'grand_total' => $pricing['grand_total'],
                'consumed_at' => null,
            ]
        );
    }

    public function syncUsagesForPayment(Payment $payment, string $paymentStatus): void
    {
        $subscriptionIds = is_array($payment->subscription_ids)
            ? $payment->subscription_ids
            : json_decode((string) $payment->subscription_ids, true);

        if (! is_array($subscriptionIds) || empty($subscriptionIds)) {
            return;
        }

        $query = CouponV2Usage::query()
            ->where('user_id', $payment->user_id)
            ->whereIn('subscription_id', $subscriptionIds)
            ->where('payment_status', CouponV2Usage::STATUS_PENDING_PAYMENT);

        $payload = [
            'payment_id' => $payment->id,
            'payment_status' => $paymentStatus === CouponV2Usage::STATUS_PAID
                ? CouponV2Usage::STATUS_PAID
                : CouponV2Usage::STATUS_FAILED,
            'consumed_at' => $paymentStatus === CouponV2Usage::STATUS_PAID ? now() : null,
        ];

        $query->update($payload);
    }

    public function prepareCode(?string $code, bool $autoGenerate, string $ownerType): array
    {
        if ($autoGenerate || blank($code)) {
            $code = $this->generateUniqueCode($ownerType);
        } else {
            $code = CouponV2Coupon::normalizeCode($code);
        }

        if ($code === '') {
            throw ValidationException::withMessages([
                'code' => ['Coupon code is required.'],
            ]);
        }

        return [
            'code' => $code,
            'code_normalized' => CouponV2Coupon::normalizeCode($code),
        ];
    }

    public function assertNoCodeCollision(string $code, ?int $ignoreCouponId = null): void
    {
        $normalized = CouponV2Coupon::normalizeCode($code);

        $v2Query = CouponV2Coupon::query()->where('code_normalized', $normalized);

        if ($ignoreCouponId !== null) {
            $v2Query->where('id', '!=', $ignoreCouponId);
        }

        if ($v2Query->exists()) {
            throw ValidationException::withMessages([
                'code' => ['This coupon code is already used by Coupon V2.'],
            ]);
        }

        $legacyCollision = Coupon::query()
            ->whereRaw('LOWER(code) = ?', [strtolower($normalized)])
            ->whereDate('expiration_date', '>=', now()->toDateString())
            ->exists();

        if ($legacyCollision) {
            throw ValidationException::withMessages([
                'code' => ['This coupon code collides with an active legacy coupon.'],
            ]);
        }
    }

    public function findV2ByCode(?string $code): ?CouponV2Coupon
    {
        if (blank($code)) {
            return null;
        }

        return CouponV2Coupon::query()
            ->matchingCode($code)
            ->first();
    }

    public function findLegacyByCode(?string $code): ?Coupon
    {
        if (blank($code)) {
            return null;
        }

        return Coupon::query()
            ->whereRaw('LOWER(code) = ?', [strtolower(trim((string) $code))])
            ->first();
    }

    public function assertCouponUsable(CouponV2Coupon $coupon, ?int $userId = null): void
    {
        if ($coupon->status === CouponV2Coupon::STATUS_SUSPENDED) {
            throw ValidationException::withMessages([
                'code' => ['This coupon is currently suspended.'],
            ]);
        }

        if ($coupon->isExpired()) {
            throw ValidationException::withMessages([
                'code' => ['This coupon has expired.'],
            ]);
        }

        if ($coupon->isExhausted()) {
            throw ValidationException::withMessages([
                'code' => ['This coupon has reached its usage limit.'],
            ]);
        }

        if ($userId !== null && $coupon->hasPaidUsageForUser($userId)) {
            throw ValidationException::withMessages([
                'code' => ['You have already used this coupon.'],
            ]);
        }
    }

    public function generateUniqueCode(string $ownerType): string
    {
        $prefix = $ownerType === CouponV2Coupon::OWNER_PARTNER ? 'PTR' : 'SYS';
        $length = max((int) config('coupon_v2.auto_code_length', 6), 4);

        do {
            $random = strtoupper(substr(bin2hex(random_bytes($length)), 0, $length));
            $code = "{$prefix}-{$random}";
        } while ($this->findV2ByCode($code) !== null || $this->findLegacyByCode($code) !== null);

        return $code;
    }

    private function buildResponse(
        string $version,
        int $id,
        string $code,
        string $discountType,
        float $discountValue,
        ?array $pricing,
        string $ownerType,
        ?int $ownerUserId,
        string $state
    ): array {
        return [
            'success' => true,
            'message' => 'Coupon applied successfully.',
            'version' => $version,
            'id' => $id,
            'code' => $code,
            'discount' => $discountValue,
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'discount_label' => $this->pricingService->formatDiscountLabel([
                'type' => $discountType,
                'value' => $discountValue,
            ]),
            'pricing' => $pricing,
            'owner_type' => $ownerType,
            'owner_user_id' => $ownerUserId,
            'state' => $state,
        ];
    }

    private function isLegacyExpired(Coupon $coupon): bool
    {
        try {
            return now()->greaterThan(\Carbon\Carbon::parse($coupon->expiration_date)->endOfDay());
        } catch (Throwable) {
            return true;
        }
    }
}
