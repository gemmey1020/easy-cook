<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    // ✅ عرض جميع الوصفات (مع البحث)
    public function index(Request $request)
    {
        $query = $request->input('search');

        if ($query) {
            $recipes = Recipe::search($query)->get();
        } else {
            $recipes = Recipe::latest()->get();
        }

        return view('recipes.index', compact('recipes'));
    }

    // ✅ إضافة وصفة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'ingredients' => 'required|string',
            'file' => 'nullable|mimes:pdf|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('recipes_pdfs', 'public');
        }

        Recipe::create([
            'title' => $request->title,
            'ingredients' => $request->ingredients,
            'calories' => $request->calories,
            'protein' => $request->protein,
            'carbs' => $request->carbs,
            'fats' => $request->fats,
            'file_path' => $filePath,
        ]);

        return back()->with('success', 'Recipe Added Successfully!');
    }

    // ✅ تنزيل ملف PDF
    public function download($id)
    {
        $recipe = Recipe::findOrFail($id);
        if ($recipe->file_path) {
            return response()->download(storage_path("app/public/{$recipe->file_path}"));
        }

        return back()->with('error', 'File not found!');
    }
}
