<?php

namespace Database\Factories;

use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'plan_id' => Plan::first()->id ?? 1,
            'subscription_type' => 'weight_loss',
            'calorie_range' => '1300-1500kcal', // ✅ إعطاء قيمة افتراضية
            'weeks_count' => $this->faker->numberBetween(1, 4),
            'days_per_week' => $this->faker->randomElement([5, 6]),
            'excluded_days' => json_encode([]),
            'price' => 100.00,
            'total_meals' => 14,
            'start_date' => Carbon::now()->addDays(3),
            'end_date' => Carbon::now()->addDays(17),
            'status' => 'pending',
        ];
    }
}
