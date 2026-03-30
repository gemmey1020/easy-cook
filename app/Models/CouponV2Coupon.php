<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CouponV2Coupon extends Model
{
    use HasFactory;

    public const OWNER_SYSTEM = 'system';
    public const OWNER_PARTNER = 'partner';

    public const TYPE_PERCENT = 'percent';
    public const TYPE_FIXED = 'fixed';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_SUSPENDED = 'suspended';

    protected $fillable = [
        'code',
        'code_normalized',
        'owner_type',
        'owner_user_id',
        'discount_type',
        'discount_value',
        'max_redemptions',
        'expires_at',
        'status',
        'auto_generated',
        'notes',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'auto_generated' => 'boolean',
        'discount_value' => 'decimal:2',
        'max_redemptions' => 'integer',
    ];

    public function ownerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function usages(): HasMany
    {
        return $this->hasMany(CouponV2Usage::class, 'coupon_v2_coupon_id');
    }

    public function backingInvitations(): HasMany
    {
        return $this->hasMany(InvitationCode::class, 'default_coupon_v2_coupon_id');
    }

    public function overrideInvitationJourneys(): HasMany
    {
        return $this->hasMany(InvitationJourney::class, 'override_coupon_id')
            ->where('override_coupon_version', 'v2');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'coupon_v2_coupon_id');
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

    public function paidUsersCount(): int
    {
        if (array_key_exists('paid_users_count', $this->attributes)) {
            return (int) $this->attributes['paid_users_count'];
        }

        return (int) $this->usages()
            ->where('payment_status', CouponV2Usage::STATUS_PAID)
            ->distinct('user_id')
            ->count('user_id');
    }

    public function hasPaidUsageForUser(int $userId): bool
    {
        return $this->usages()
            ->where('user_id', $userId)
            ->where('payment_status', CouponV2Usage::STATUS_PAID)
            ->exists();
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && now()->greaterThan($this->expires_at);
    }

    public function isExhausted(): bool
    {
        return $this->paidUsersCount() >= $this->max_redemptions;
    }

    public function derivedStatus(): string
    {
        if ($this->status === self::STATUS_SUSPENDED) {
            return self::STATUS_SUSPENDED;
        }

        if ($this->isExpired()) {
            return 'expired';
        }

        if ($this->isExhausted()) {
            return 'exhausted';
        }

        return self::STATUS_ACTIVE;
    }

    public function usagePercent(): int
    {
        if ((int) $this->max_redemptions <= 0) {
            return 0;
        }

        return (int) min(
            100,
            round(($this->paidUsersCount() / (int) $this->max_redemptions) * 100)
        );
    }

    public function usageTone(): string
    {
        if ($this->isExhausted()) {
            return 'full';
        }

        $usagePercent = $this->usagePercent();

        if ($usagePercent >= 75) {
            return 'near-full';
        }

        if ($usagePercent > 0) {
            return 'active';
        }

        return 'idle';
    }

    public function dashboardRoleTypes(): array
    {
        $roles = [];

        if ($this->backingInvitationsCount() > 0) {
            $roles[] = 'backing_invitation';
        }

        if ($this->overrideJourneyCount() > 0) {
            $roles[] = 'seen_as_override';
        }

        if ($roles === []) {
            $roles[] = 'standalone';
        }

        return $roles;
    }

    public function dashboardPrimaryRoleType(): string
    {
        return $this->dashboardRoleTypes()[0];
    }

    public function dashboardRoleLabel(string $role): string
    {
        return match ($role) {
            'backing_invitation' => 'Backing Invitation',
            'seen_as_override' => 'Seen As Override',
            default => 'Standalone Coupon',
        };
    }

    public function dashboardPrimaryRoleLabel(): string
    {
        return $this->dashboardRoleLabel($this->dashboardPrimaryRoleType());
    }

    public function dashboardUsageStory(): string
    {
        $usageCount = $this->usageCount();

        if ($usageCount > 0) {
            return $usageCount === 1
                ? '1 usage record captured'
                : $usageCount . ' usage records captured';
        }

        if ($this->backingInvitationsCount() > 0) {
            return 'Backing invitation only';
        }

        if ($this->derivedStatus() === 'expired') {
            return 'Expired before usage';
        }

        return 'No usage yet';
    }

    private function backingInvitationsCount(): int
    {
        if (array_key_exists('backing_invitations_count', $this->attributes)) {
            return (int) $this->attributes['backing_invitations_count'];
        }

        return (int) $this->backingInvitations()->count();
    }

    private function overrideJourneyCount(): int
    {
        if (array_key_exists('override_invitation_journeys_count', $this->attributes)) {
            return (int) $this->attributes['override_invitation_journeys_count'];
        }

        return (int) $this->overrideInvitationJourneys()->count();
    }

    private function usageCount(): int
    {
        if (array_key_exists('usages_count', $this->attributes)) {
            return (int) $this->attributes['usages_count'];
        }

        return (int) $this->usages()->count();
    }
}
