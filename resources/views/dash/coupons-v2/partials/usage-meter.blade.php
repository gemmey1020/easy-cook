@php($usageCount = $coupon->paidUsersCount())
@php($usagePercent = $coupon->usagePercent())
@php($usageTone = $coupon->usageTone())

<div class="coupon-usage">
    <div class="coupon-usage__meta">
        <span>{{ $usageCount }} / {{ $coupon->max_redemptions }}</span>
        <small>{{ $usagePercent }}%</small>
    </div>
    <div class="coupon-usage__track" aria-hidden="true">
        <span class="coupon-usage__fill coupon-usage__fill--{{ $usageTone }}" style="width: {{ $usagePercent }}%"></span>
    </div>
</div>
