<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            ['name' => '1400 Calories', 'description' => 'Low-calorie meal plan'],
            ['name' => '1700 Calories', 'description' => 'Medium-calorie meal plan'],
            ['name' => '2000 Calories', 'description' => 'High-calorie meal plan'],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['name' => $plan['name']],
                ['description' => $plan['description']]
            );
        }
    }
}
