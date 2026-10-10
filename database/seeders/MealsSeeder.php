<?php

namespace Database\Seeders;

use App\Models\Meal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class MealsSeeder extends Seeder
{
    public function run()
    {
        // قراءة البيانات من ملف JSON
        $json = File::get(database_path('seeders/data2.json'));
        $data = json_decode($json, true);

        Meal::withoutSyncingToSearch(function () use ($data) {
            foreach ($data as $calorieLevel => $days) {
                foreach ($days as $day => $meals) {
                    foreach ($meals as $mealType => $mealList) {
                        foreach ($mealList as $meal) {
                            Meal::create([
                                'meal_id' => $meal['id'],
                                'calorie_level' => $calorieLevel,
                                'day' => $day,
                                'meal_type' => $mealType,
                                'title' => $meal['title'],
                                'description' => $meal['description'],
                                'image' => $meal['image'] ?? null,
                            ]);
                        }
                    }
                }
            }
        });
    }
}
