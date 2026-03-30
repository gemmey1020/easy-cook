<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponV2Usage extends Model
{
    use HasFactory;

    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'coupon_v2_coupon_id',
        'user_id',
        'subscription_id',
        'payment_id',
        'payment_status',
        'total_before_discount',
        'discount_amount',
        'total_after_discount',
        'vat_amount',
        'grand_total',
        'consumed_at',
    ];

    protected $casts = [
        'total_before_discount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_after_discount' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'consumed_at' => 'datetime',
    ];

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(CouponV2Coupon::class, 'coupon_v2_coupon_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
