<?php

namespace App\Services;

use App\Models\CouponV2Coupon;
use App\Models\Subscription;
use InvalidArgumentException;

class SubscriptionPricingService
{
    public const VAT_RATE = 0.14;

    public function matrix(): array
    {
        return config('subscription_pricing.catalog', []);
    }

    public function plansCatalog(?bool $threeWeekUiEnabled = null): array
    {
        $includeThreeWeek = $threeWeekUiEnabled ?? $this->threeWeekUiEnabled();
        $plansCatalog = [];

        foreach ($this->matrix() as $planId => $daysCatalog) {
            foreach ($daysCatalog as $daysPerWeek => $weeksCatalog) {
                foreach ($weeksCatalog as $weeksCount => $tier) {
                    $resolvedWeeksCount = (int) $weeksCount;

                    if (! $includeThreeWeek && $resolvedWeeksCount !== 4) {
                        continue;
                    }

                    $plansCatalog[(int) $planId][$resolvedWeeksCount][(int) $daysPerWeek] = [
                        'with_vat' => round((float) $tier['with_vat'], 2),
                        'without_vat' => round((float) $tier['without_vat'], 2),
                    ];
                }
            }
        }

        return $plansCatalog;
    }

    public function tierFor($planId, $daysPerWeek, $weeksCount = null): array
    {
        $resolvedPlanId = $this->resolvePlanId($planId);
        $resolvedDaysPerWeek = (int) $daysPerWeek;
        $resolvedWeeksCount = $this->resolveWeeksCount($weeksCount);
        $tier = $this->matrix()[$resolvedPlanId][$resolvedDaysPerWeek][$resolvedWeeksCount] ?? null;

        if ($tier === null) {
            throw new InvalidArgumentException('Unsupported subscription pricing combination.');
        }

        return [
            'plan_id' => $resolvedPlanId,
            'days_per_week' => $resolvedDaysPerWeek,
            'weeks_count' => $resolvedWeeksCount,
            'with_vat' => round((float) $tier['with_vat'], 2),
            'without_vat' => round((float) $tier['without_vat'], 2),
        ];
    }

    public function calculateForSubscription(array $subscription, ?array $coupon = null): array
    {
        $tier = $this->tierFor(
            $subscription['plan_id'] ?? null,
            $subscription['days_per_week'] ?? 0,
            $subscription['weeks_count'] ?? null
        );
        $couponPayload = $this->normalizeCouponPayload($coupon);

        $totalBeforeDiscount = $tier['without_vat'];
        $discountAmount = 0.0;

        if ($couponPayload !== null) {
            $discountAmount = $this->calculateDiscountAmount($totalBeforeDiscount, $couponPayload);
        }

        $totalAfterDiscount = round(max($totalBeforeDiscount - $discountAmount, 0), 2);
        $vatAmount = round($totalAfterDiscount * self::VAT_RATE, 2);
        $grandTotal = round($totalAfterDiscount + $vatAmount, 2);

        return [
            'plan_id' => $tier['plan_id'],
            'days_per_week' => $tier['days_per_week'],
            'weeks_count' => $tier['weeks_count'],
            'list_with_vat' => $tier['with_vat'],
            'total_before_discount' => $totalBeforeDiscount,
            'discount_amount' => $discountAmount,
            'total_after_discount' => $totalAfterDiscount,
            'vat_rate' => self::VAT_RATE,
            'vat_amount' => $vatAmount,
            'grand_total' => $grandTotal,
            'discount_type' => $couponPayload['type'] ?? null,
            'discount_value' => $couponPayload['value'] ?? null,
            'discount_label' => $this->formatDiscountLabel($couponPayload),
        ];
    }

    public function resolvePlanId($planId): int
    {
        $value = preg_replace('/\D+/', '', (string) $planId) ?? '';

        if ($value === '') {
            throw new InvalidArgumentException('Plan id is required.');
        }

        $resolved = (int) $value;

        if (! array_key_exists($resolved, $this->matrix())) {
            throw new InvalidArgumentException('Unsupported plan id.');
        }

        return $resolved;
    }

    public function calculateForStoredSubscription(Subscription $subscription): array
    {
        $subscription->loadMissing(['plan', 'coupon', 'couponV2', 'couponV2Usage']);

        $couponPayload = $this->couponPayloadForStoredSubscription($subscription);
        $planId = $this->resolvePlanIdFromStoredSubscription($subscription);
        $usage = $subscription->couponV2Usage;

        if ($usage !== null) {
            return [
                'plan_id' => $planId,
                'days_per_week' => (int) $subscription->days_per_week,
                'weeks_count' => $this->resolveWeeksCount($subscription->weeks_count),
                'list_with_vat' => round((float) $usage->total_before_discount * (1 + self::VAT_RATE), 2),
                'total_before_discount' => round((float) $usage->total_before_discount, 2),
                'discount_amount' => round((float) $usage->discount_amount, 2),
                'total_after_discount' => round((float) $usage->total_after_discount, 2),
                'vat_rate' => self::VAT_RATE,
                'vat_amount' => round((float) $usage->vat_amount, 2),
                'grand_total' => round((float) $usage->grand_total, 2),
                'discount_type' => $couponPayload['type'] ?? null,
                'discount_value' => $couponPayload['value'] ?? null,
                'discount_label' => $this->formatDiscountLabel($couponPayload),
                'price_snapshot' => round((float) $subscription->price, 2),
            ];
        }

        $derived = $this->deriveStoredBreakdownFromSnapshot(
            round((float) $subscription->price, 2),
            $couponPayload
        );

        if ($derived === null) {
            $derived = $this->calculateForSubscription([
                'plan_id' => $planId,
                'days_per_week' => (int) $subscription->days_per_week,
                'weeks_count' => $subscription->weeks_count,
            ], $couponPayload);
            $derived['price_snapshot'] = round((float) $subscription->price, 2);

            return $derived;
        }

        return [
            'plan_id' => $planId,
            'days_per_week' => (int) $subscription->days_per_week,
            'weeks_count' => $this->resolveWeeksCount($subscription->weeks_count),
            'list_with_vat' => round($derived['total_before_discount'] * (1 + self::VAT_RATE), 2),
            'total_before_discount' => $derived['total_before_discount'],
            'discount_amount' => $derived['discount_amount'],
            'total_after_discount' => $derived['total_after_discount'],
            'vat_rate' => self::VAT_RATE,
            'vat_amount' => $derived['vat_amount'],
            'grand_total' => $derived['grand_total'],
            'discount_type' => $couponPayload['type'] ?? null,
            'discount_value' => $couponPayload['value'] ?? null,
            'discount_label' => $this->formatDiscountLabel($couponPayload),
            'price_snapshot' => round((float) $subscription->price, 2),
        ];
    }

    public function couponPayloadForStoredSubscription(Subscription $subscription): ?array
    {
        if ($subscription->couponV2 !== null) {
            return $this->normalizeCouponPayload([
                'type' => $subscription->couponV2->discount_type,
                'value' => (float) $subscription->couponV2->discount_value,
            ]);
        }

        if ($subscription->coupon !== null) {
            return $this->normalizeCouponPayload([
                'type' => CouponV2Coupon::TYPE_PERCENT,
                'value' => (float) $subscription->coupon->discount,
            ]);
        }

        return null;
    }

    public function formatDiscountLabel(?array $coupon): ?string
    {
        if ($coupon === null) {
            return null;
        }

        return match ($coupon['type']) {
            CouponV2Coupon::TYPE_PERCENT => rtrim(rtrim(number_format((float) $coupon['value'], 2, '.', ''), '0'), '.') . '%',
            CouponV2Coupon::TYPE_FIXED => number_format((float) $coupon['value'], 2) . ' LE',
            default => null,
        };
    }

    private function resolvePlanIdFromStoredSubscription(Subscription $subscription): int
    {
        $candidate = $subscription->plan?->name ?? $subscription->plan?->description ?? null;

        if ($candidate === null || trim((string) $candidate) === '') {
            throw new InvalidArgumentException('Unable to resolve subscription pricing plan.');
        }

        return $this->resolvePlanId($candidate);
    }

    private function resolveWeeksCount($weeksCount): int
    {
        if ($weeksCount === null || $weeksCount === '') {
            return 4;
        }

        $resolvedWeeksCount = (int) $weeksCount;

        if ($resolvedWeeksCount < 1) {
            throw new InvalidArgumentException('Weeks count must be at least 1.');
        }

        return $resolvedWeeksCount;
    }

    public function threeWeekUiEnabled(): bool
    {
        return (bool) config('subscription_pricing.three_week_ui_enabled', false);
    }

    private function normalizeCouponPayload(?array $coupon): ?array
    {
        if ($coupon === null) {
            return null;
        }

        $type = $coupon['type'] ?? null;
        $value = round((float) ($coupon['value'] ?? 0), 2);

        if ($type === CouponV2Coupon::TYPE_PERCENT) {
            if ($value < 0 || $value > 100) {
                throw new InvalidArgumentException('Percent discounts must be between 0 and 100.');
            }

            return [
                'type' => $type,
                'value' => $value,
            ];
        }

        if ($type === CouponV2Coupon::TYPE_FIXED) {
            if ($value < 0) {
                throw new InvalidArgumentException('Fixed discounts must not be negative.');
            }

            return [
                'type' => $type,
                'value' => $value,
            ];
        }

        throw new InvalidArgumentException('Unsupported discount type.');
    }

    private function calculateDiscountAmount(float $totalBeforeDiscount, array $coupon): float
    {
        return match ($coupon['type']) {
            CouponV2Coupon::TYPE_PERCENT => round($totalBeforeDiscount * ($coupon['value'] / 100), 2),
            CouponV2Coupon::TYPE_FIXED => round(min($coupon['value'], $totalBeforeDiscount), 2),
            default => throw new InvalidArgumentException('Unsupported discount type.'),
        };
    }

    private function deriveStoredBreakdownFromSnapshot(float $grandTotal, ?array $coupon): ?array
    {
        $grandTotal = round($grandTotal, 2);
        $totalAfterDiscount = round($grandTotal / (1 + self::VAT_RATE), 2);
        $vatAmount = round($grandTotal - $totalAfterDiscount, 2);

        if ($coupon === null) {
            return [
                'total_before_discount' => $totalAfterDiscount,
                'discount_amount' => 0.0,
                'total_after_discount' => $totalAfterDiscount,
                'vat_amount' => $vatAmount,
                'grand_total' => $grandTotal,
            ];
        }

        if ($coupon['type'] === CouponV2Coupon::TYPE_FIXED) {
            $totalBeforeDiscount = round($totalAfterDiscount + $coupon['value'], 2);

            return [
                'total_before_discount' => $totalBeforeDiscount,
                'discount_amount' => round(min($coupon['value'], $totalBeforeDiscount), 2),
                'total_after_discount' => $totalAfterDiscount,
                'vat_amount' => $vatAmount,
                'grand_total' => $grandTotal,
            ];
        }

        if ($coupon['type'] === CouponV2Coupon::TYPE_PERCENT) {
            if ((float) $coupon['value'] >= 100.0) {
                return null;
            }

            $multiplier = 1 - ((float) $coupon['value'] / 100);

            if ($multiplier <= 0) {
                return null;
            }

            $totalBeforeDiscount = round($totalAfterDiscount / $multiplier, 2);

            return [
                'total_before_discount' => $totalBeforeDiscount,
                'discount_amount' => round($totalBeforeDiscount - $totalAfterDiscount, 2),
                'total_after_discount' => $totalAfterDiscount,
                'vat_amount' => $vatAmount,
                'grand_total' => $grandTotal,
            ];
        }

        return null;
    }
}
