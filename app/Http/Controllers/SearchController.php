<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Meal;
use App\Models\Recipe;
use App\Models\Tip;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // public function search(Request $request)
    // {
    //     // التحقق من وجود كلمة البحث
    //     $query = $request->input('search');
    //     if (!$query) {
    //         return response()->json(['error' => 'يرجى إدخال كلمة للبحث'], 400);
    //     }

    //     // البحث في جميع الموديلات باستخدام MySQL FULLTEXT SEARCH
    //     $recipes = Recipe::whereRaw("MATCH(title, content) AGAINST(? IN BOOLEAN MODE)", [$query])->get();
    //     $tips = Tip::whereRaw("MATCH(title, content) AGAINST(? IN BOOLEAN MODE)", [$query])->get();
    //     // $meals = Meal::whereRaw("MATCH(title, description) AGAINST(? IN BOOLEAN MODE)", [$query])->get();
    //     // $blogs = Blog::whereRaw("MATCH(title, description) AGAINST(? IN BOOLEAN MODE)", [$query])->get();

    //     // تنسيق النتائج
    //     return response()->json([
    //         'recipes' => $recipes ?? [] ,
    //         'tips' => $tips ?? [],
    //         'meals' => $meals ?? [],
    //         'blogs' => $blogs ?? []
    //     ]);
    // }

    public function search(Request $request)
    {
        $query = $request->input('search');
        if (! $query) {
            return response()->json(['error' => 'يرجى إدخال كلمة للبحث'], 400);
        }

        return response()->json([
            'recipes' => Recipe::whereRaw('MATCH(title, content) AGAINST(? IN BOOLEAN MODE)', [$query])->get(),
            'tips' => Tip::whereRaw('MATCH(title, content) AGAINST(? IN BOOLEAN MODE)', [$query])->get(),
            'meals' => Meal::whereRaw('MATCH(title, description) AGAINST(? IN BOOLEAN MODE)', [$query])->get(),
            'blogs' => [],
        ]);
    }
}
