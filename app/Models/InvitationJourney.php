<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvitationJourney extends Model
{
    use HasFactory;

    public const STATE_CAPTURED = 'captured';
    public const STATE_REGISTERED = 'registered';
    public const STATE_CHECKOUT_STARTED = 'checkout_started';
    public const STATE_PAID = 'paid';
    public const STATE_INVALID_CODE = 'invalid_code';

    public const CHANNEL_LINK = 'link';
    public const CHANNEL_MANUAL = 'manual';

    protected $fillable = [
        'journey_token',
        'invitation_code_id',
        'user_id',
        'payment_id',
        'state',
        'capture_channel',
        'raw_invitation_code',
        'last_invalid_code',
        'invalid_reason',
        'invalid_recorded_at',
        'referral_source',
        'referral_source_note',
        'default_coupon_v2_coupon_id',
        'active_coupon_version',
        'active_coupon_id',
        'active_coupon_code',
        'override_coupon_version',
        'override_coupon_id',
        'override_coupon_code',
        'captured_at',
        'registered_at',
        'checkout_started_at',
        'paid_at',
        'last_seen_at',
    ];

    protected $casts = [
        'invalid_recorded_at' => 'datetime',
        'captured_at' => 'datetime',
        'registered_at' => 'datetime',
        'checkout_started_at' => 'datetime',
        'paid_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function invitationCode(): BelongsTo
    {
        return $this->belongsTo(InvitationCode::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function defaultCouponV2(): BelongsTo
    {
        return $this->belongsTo(CouponV2Coupon::class, 'default_coupon_v2_coupon_id');
    }

    public static function referralSources(): array
    {
        return ['meta', 'google', 'direct_link', 'captain_gym', 'other'];
    }

    public static function states(): array
    {
        return [
            self::STATE_CAPTURED,
            self::STATE_REGISTERED,
            self::STATE_CHECKOUT_STARTED,
            self::STATE_PAID,
            self::STATE_INVALID_CODE,
        ];
    }

    public function isValidInvitation(): bool
    {
        return $this->invitation_code_id !== null;
    }

    public function hasActiveOverride(): bool
    {
        return filled($this->override_coupon_version)
            && $this->override_coupon_id !== null
            && filled($this->override_coupon_code);
    }

    public function dashboardStateLabel(): string
    {
        return match ($this->state) {
            self::STATE_CAPTURED => 'Captured',
            self::STATE_REGISTERED => 'Registered',
            self::STATE_CHECKOUT_STARTED => 'Checkout Started',
            self::STATE_PAID => 'Paid',
            self::STATE_INVALID_CODE => 'Invalid',
            default => ucfirst(str_replace('_', ' ', (string) $this->state)),
        };
    }

    public function defaultCouponPayload(): ?array
    {
        if ($this->invitationCode !== null) {
            $discount = $this->invitationCode->discountPayload();

            if ($discount !== null) {
                return [
                    'version' => 'invitation',
                    'id' => $this->invitationCode->id,
                    'code' => $this->invitationCode->code,
                    'discount_type' => $discount['type'],
                    'discount_value' => $discount['value'],
                    'discount_label' => $this->invitationCode->discountLabel(),
                ];
            }
        }

        $coupon = $this->defaultCouponV2 ?? $this->invitationCode?->defaultCouponV2;

        if ($coupon === null) {
            return null;
        }

        return [
            'version' => 'v2',
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount_type' => $coupon->discount_type,
            'discount_value' => (float) $coupon->discount_value,
        ];
    }

    public function currentCouponPayload(): ?array
    {
        if (filled($this->active_coupon_version) && $this->active_coupon_id !== null) {
            if (
                $this->active_coupon_version === 'invitation'
                && $this->invitationCode !== null
                && (int) $this->invitationCode->id === (int) $this->active_coupon_id
            ) {
                $discount = $this->invitationCode->discountPayload();

                return [
                    'version' => 'invitation',
                    'id' => (int) $this->active_coupon_id,
                    'code' => $this->active_coupon_code ?: $this->invitationCode->code,
                    'discount_type' => $discount['type'] ?? null,
                    'discount_value' => $discount['value'] ?? null,
                    'discount_label' => $this->invitationCode->discountLabel(),
                ];
            }

            return [
                'version' => $this->active_coupon_version,
                'id' => (int) $this->active_coupon_id,
                'code' => $this->active_coupon_code,
            ];
        }

        return $this->defaultCouponPayload();
    }
}
