<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run()
    {
        Recipe::insert([
            [
                'title' => 'Roasted healthy whole grain bread',
                'ingredients' => "• 1 slice of whole grain bread (25g)\n• 20g Healthy Spread Chocolate\n• 50g pumpkin mash\n• 1 cup skimmed milk\n• 1 tsp honey\n• 1 tsp pistachio",
                'calories' => 320,
                'protein' => 13.5,
                'carbs' => 47,
                'fats' => 8.5,
                'file_path' => 'recipes_pdfs/Recipe_1.pdf',
            ],
            [
                'title' => 'Mediterranean Shrimp Delight',
                'ingredients' => "• 200 grams Prawns (cooked)\n• 2 tsp Olive Oil\n• 1/2 tsp Dried Oregano\n• 1/2 tsp Dried Parsley\n• 30g Red, Yellow Bell Pepper\n• 50 grams Quinoa\n• 1 clove Garlic\n• 60 ml sea-food Stock\n• 1 wedge Lemon\n• 5 Basil Leaves",
                'calories' => 390,
                'protein' => 42,
                'carbs' => 18,
                'fats' => 11,
                'file_path' => 'recipes_pdfs/Recipe_2.pdf',
            ],
            [
                'title' => 'Chocolate Vanilla Protein Shake',
                'ingredients' => "• 15 grams Vanilla Whey Protein (80%)\n• 15 grams Chocolate Whey Protein (80%)\n• 250 ml Almond Milk",
                'calories' => 156,
                'protein' => 25,
                'carbs' => 5,
                'fats' => 4,
                'file_path' => 'recipes_pdfs/Recipe_3.pdf',
            ],
            [
                'title' => 'Classic Sun-dried Tomato Tuna Omelet',
                'ingredients' => "• 2 whole Eggs\n• 1 tsp Olive Oil\n• 10g Sun-Dried Tomatoes\n• 100g White Tuna (canned)\n• 1/2 Whole Grain Bread (25g)\n• 10g Red Pepper Flakes",
                'calories' => 396,
                'protein' => 41,
                'carbs' => 22,
                'fats' => 16,
                'file_path' => 'recipes_pdfs/Recipe_4.pdf',
            ],
        ]);
    }
}
