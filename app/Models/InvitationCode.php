<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvitationCode extends Model
{
    use HasFactory;

    public const OWNER_SYSTEM = 'system';
    public const OWNER_PARTNER = 'partner';

    public const TYPE_PERCENT = CouponV2Coupon::TYPE_PERCENT;
    public const TYPE_FIXED = CouponV2Coupon::TYPE_FIXED;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'code',
        'code_normalized',
        'owner_type',
        'owner_user_id',
        'default_coupon_v2_coupon_id',
        'discount_type',
        'discount_value',
        'max_redemptions',
        'status',
        'starts_at',
        'expires_at',
        'auto_generated',
        'notes',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'auto_generated' => 'boolean',
        'discount_value' => 'decimal:2',
        'max_redemptions' => 'integer',
    ];

    public function ownerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function defaultCouponV2(): BelongsTo
    {
        return $this->belongsTo(CouponV2Coupon::class, 'default_coupon_v2_coupon_id');
    }

    public function journeys(): HasMany
    {
        return $this->hasMany(InvitationJourney::class);
    }

    public function scopeMatchingCode($query, string $code)
    {
        return $query->where('code_normalized', static::normalizeCode($code));
    }

    public static function normalizeCode(?string $code): string
    {
        $value = strtoupper(trim((string) $code));
        $value = preg_replace('/\s+/', '-', $value) ?? '';
        $value = preg_replace('/[^A-Z0-9-]/', '', $value) ?? '';
        $value = preg_replace('/-+/', '-', $value) ?? '';

        return trim($value, '-');
    }

    public function isActiveForCapture(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        if ($this->starts_at !== null && now()->lt($this->starts_at)) {
            return false;
        }

        if ($this->expires_at !== null && now()->gt($this->expires_at)) {
            return false;
        }

        if (! $this->hasDiscountConfigured()) {
            return false;
        }

        if ($this->isExhausted()) {
            return false;
        }

        return true;
    }

    public function derivedStatus(): string
    {
        if ($this->status === self::STATUS_INACTIVE) {
            return self::STATUS_INACTIVE;
        }

        if ($this->starts_at !== null && now()->lt($this->starts_at)) {
            return 'scheduled';
        }

        if ($this->expires_at !== null && now()->gt($this->expires_at)) {
            return 'expired';
        }

        if ($this->isExhausted()) {
            return 'exhausted';
        }

        return self::STATUS_ACTIVE;
    }

    public function hasDiscountConfigured(): bool
    {
        return $this->nativeDiscountPayload() !== null || $this->legacyDiscountPayload() !== null;
    }

    public function hasNativeDiscountConfigured(): bool
    {
        return $this->nativeDiscountPayload() !== null;
    }

    public function hasBackingCoupon(): bool
    {
        return $this->defaultCouponV2 !== null;
    }

    public function nativeDiscountPayload(): ?array
    {
        $type = $this->discount_type;
        $value = $this->discount_value !== null ? (float) $this->discount_value : null;

        if (! in_array($type, [self::TYPE_PERCENT, self::TYPE_FIXED], true) || $value === null) {
            return null;
        }

        return [
            'type' => $type,
            'value' => $value,
        ];
    }

    public function legacyDiscountPayload(): ?array
    {
        $coupon = $this->defaultCouponV2;

        if ($coupon === null) {
            return null;
        }

        return [
            'type' => $coupon->discount_type,
            'value' => (float) $coupon->discount_value,
        ];
    }

    public function discountPayload(): ?array
    {
        return $this->nativeDiscountPayload() ?? $this->legacyDiscountPayload();
    }

    public function discountLabel(): ?string
    {
        $discount = $this->discountPayload();

        if ($discount === null) {
            return null;
        }

        return match ($discount['type']) {
            self::TYPE_PERCENT => rtrim(rtrim(number_format($discount['value'], 2, '.', ''), '0'), '.') . '%',
            self::TYPE_FIXED => number_format($discount['value'], 2) . ' LE',
            default => null,
        };
    }

    public function dashboardDiscountSourceType(): string
    {
        if ($this->hasNativeDiscountConfigured()) {
            return 'native_invitation';
        }

        if ($this->hasBackingCoupon()) {
            return 'backed_coupon_v2';
        }

        return 'missing_setup';
    }

    public function dashboardDiscountSourceLabel(): string
    {
        return match ($this->dashboardDiscountSourceType()) {
            'native_invitation' => 'Native Invitation Discount',
            'backed_coupon_v2' => 'Backed by Coupon V2',
            default => 'Needs Discount Setup',
        };
    }

    public function paidUsersCount(): int
    {
        if (array_key_exists('paid_users_count', $this->attributes)) {
            return (int) $this->attributes['paid_users_count'];
        }

        return (int) $this->journeys()
            ->where('state', InvitationJourney::STATE_PAID)
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');
    }

    public function hasPaidUsageForUser(int $userId): bool
    {
        return $this->journeys()
            ->where('state', InvitationJourney::STATE_PAID)
            ->where('user_id', $userId)
            ->exists();
    }

    public function isExhausted(): bool
    {
        $max = (int) ($this->max_redemptions ?? 0);

        if ($max <= 0) {
            return false;
        }

        return $this->paidUsersCount() >= $max;
    }

    public function remainingRedemptions(): ?int
    {
        $max = (int) ($this->max_redemptions ?? 0);

        if ($max <= 0) {
            return null;
        }

        return max($max - $this->paidUsersCount(), 0);
    }
}
