<?php

namespace App\Http\Controllers;

use App\Models\DailyMeal;
use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    // عرض جميع الوجبات
    public function index(Request $request)
    {
        $query = Meal::query();

        // تصفية حسب السعرات الحرارية
        if ($request->has('calorie_level')) {
            $query->where('calorie_level', $request->calorie_level);
        }

        // تصفية حسب اليوم
        if ($request->has('day')) {
            $query->where('day', $request->day);
        }

        // تصفية حسب نوع الوجبة (فطور، غداء، عشاء، إلخ)
        if ($request->has('meal_type')) {
            $query->where('meal_type', $request->meal_type);
        }

        return response()->json($query->get());
    }

    // إضافة وجبة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'calories' => 'required|integer',
            'protein' => 'nullable|integer',
            'carbs' => 'nullable|integer',
            'fats' => 'nullable|integer',
        ]);

        $meal = Meal::create($request->all());

        return response()->json(['message' => 'Meal added successfully', 'meal' => $meal]);
    }

    // عرض وجبة معينة
    public function show($id)
    {
        $meal = Meal::findOrFail($id);

        return response()->json($meal);
    }

    // حذف وجبة
    public function destroy($id)
    {
        $meal = Meal::findOrFail($id);
        $meal->delete();

        return response()->json(['message' => 'Meal deleted successfully']);
    }

    public function saveMeals(Request $request)
    {
        $meals = $request->input('meals');

        foreach ($meals as $meal) {
            DailyMeal::updateOrCreate([
                'subscription_id' => $meal['subscription_id'],
                'day_id' => $meal['day_id'],
                'meal_type' => $meal['meal_type'],
            ], [
                'meal_id' => $meal['meal_id'],
            ]);
        }

        return response()->json(['message' => 'Meals saved successfully']);
    }
}
