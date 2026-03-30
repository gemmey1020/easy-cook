<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'plan_id', 'subscription_type', 'calorie_range',
        'weeks_count', 'days_per_week', 'excluded_days',
        'price', 'total_meals', 'start_date', 'end_date', 'status', 'subscription_plan_type',
        'coupon_id',
        'coupon_v2_coupon_id',
    ];

    protected $casts = [
        'excluded_days' => 'array',
    ];

    const STATUS_PENDING = 'pending';

    const STATUS_ACTIVE = 'active';

    const STATUS_CANCELLED = 'cancelled';

    const STATUS_COMPLETED = 'completed';

    public function meals()
    {
        return $this->hasMany(SubscriptionMeal::class);
    }

    public function dailyMeals()
    {
        return $this->hasMany(DailyMeal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function dailySelectedMeals()
    {
        return $this->hasMany(DailySelectedMeal::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function couponV2()
    {
        return $this->belongsTo(CouponV2Coupon::class, 'coupon_v2_coupon_id');
    }

    public function couponV2Usage()
    {
        return $this->hasOne(CouponV2Usage::class);
    }
}
