<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DaysSeeder::class,
            PlansSeeder::class,
            MealsSeeder::class,
            MenuSeeder::class,
            ProductsSeeder::class,
            RecipeSeeder::class,
            TipSeeder::class,
        ]);
    }
}
