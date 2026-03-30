<?php

namespace App\Helpers;

use Carbon\Carbon;

class GeneralHelper
{
    public static function calculateMealProgress($dailySelectedMeals)
    {
        $total = $dailySelectedMeals->count();

        $completed = $dailySelectedMeals->filter(function ($meal) {
            return Carbon::parse($meal->day)->isPast();
        })->count();

        $percent = $total > 0 ? round(($completed / $total) * 100) : 0;

        return [
            'total' => $total,
            'completed' => $completed,
            'percent' => $percent,
        ];
    }

    public static function transformUserSubscriptionsWithProgress($subscriptions)
    {
        return $subscriptions->map(function ($subscription) {
            $progress = self::calculateMealProgress($subscription->dailySelectedMeals);

            $subscription->meal_progress_total = $progress['total'];
            $subscription->meal_progress_completed = $progress['completed'];
            $subscription->meal_progress_percent = $progress['percent'];

            return $subscription;
        });
    }
}
