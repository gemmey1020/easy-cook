<?php

namespace App\Helpers;

use App\Models\Subscription;
use Carbon\Carbon;

class SubscriptionDetails
{
    public static function getSubscriptionDetails($subscription_id)
    {
        $subscription = Subscription::whereNot('status', Subscription::STATUS_PENDING)->with(['dailySelectedMeals.variant.nutrition'])->findOrFail($subscription_id);

        $mealsData = self::transformMealsData($subscription);

        $progress = GeneralHelper::calculateMealProgress($subscription->dailySelectedMeals);

        // Initialize macro totals
        $macros = [
            'protein' => 0,
            'carb' => 0,
            'fat' => 0,
            'kcal' => 0,
        ];

        // Sum up all macros
        foreach ($subscription->dailySelectedMeals as $meal) {
            if ($meal->variant && $meal->variant->nutrition) {
                $macros['protein'] += $meal->variant->nutrition->protein;
                $macros['carb'] += $meal->variant->nutrition->carb;
                $macros['fat'] += $meal->variant->nutrition->fat;
                $macros['kcal'] += $meal->variant->nutrition->kcal;
            }
        }

        return view('components.dashboard.partials.subscription-details-content', [
            'subscription' => $subscription,
            'mealsData' => $mealsData,
            'progress' => $progress,
            'macros' => $macros,
        ])->render();
    }

    private static function transformMealsData(Subscription $subscription): array
    {
        $mealsData = [];
        $startDate = Carbon::parse($subscription->start_date);

        foreach ($subscription->dailySelectedMeals as $selectedMeal) {
            $mealDate = Carbon::parse($selectedMeal->day);
            $variant = $selectedMeal->variant;
            $nutrition = $variant->nutrition;

            // Calculate week and day numbers
            $weekNumber = (int) $mealDate->diffInWeeks($startDate) + 1;
            $dayNumber = $mealDate->dayOfWeekIso; // 1 (Monday) to 7 (Sunday)

            // Map meal type to frontend format
            $mealType = $selectedMeal->meal_type;

            // Generate placeholder meal name (since not provided in data)
            $mealName = ucwords(str_replace('_', ' ', $variant->meal_id));

            // Placeholder image URL (replace with actual logic if available)
            $imageUrl = asset($variant->image);

            // Structure meal data
            $meal = [
                'id' => $selectedMeal->id,
                'name' => $mealName,
                'image' => $imageUrl,
                'calories' => (float) $nutrition->kcal,
                'protein' => (float) $nutrition->protein,
                'carbs' => (float) $nutrition->carb,
                'fats' => (float) $nutrition->fat,
                'arrived' => $mealDate->isPast(), // Example logic: mark as arrived if date is past
            ];

            // Assign to nested structure
            $mealsData[$weekNumber][$dayNumber][$mealType] = $meal;
        }

        // Ensure all weeks and days are initialized (optional, for consistency)
        for ($week = 1; $week <= $subscription->weeks_count; $week++) {
            for ($day = 1; $day <= 7; $day++) {
                if (! isset($mealsData[$week][$day])) {
                    $mealsData[$week][$day] = [];
                }
            }
        }

        // Sort by week and day for consistency
        ksort($mealsData);
        foreach ($mealsData as &$week) {
            ksort($week);
        }

        return $mealsData;
    }
}
