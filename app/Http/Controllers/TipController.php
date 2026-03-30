<?php

namespace App\Http\Controllers;

use App\Models\Tip;
use Illuminate\Http\Request;

class TipController extends Controller
{
    // عرض كل الـ tips
    public function index()
    {
        $tips = Tip::all();

        return view('pages.tips', compact('tips'));
    }

    // إضافة Tip جديد مع رفع PDF
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'file' => 'nullable|mimes:pdf|max:2048', // ملف PDF اختياري
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tips_pdfs', 'public');
        }

        Tip::create([
            'title' => $request->title,
            'content' => $request->content,
            'file_path' => $filePath,
        ]);

        return back()->with('success', 'Tip Added Successfully!');
    }

    // البحث باستخدام Laravel Scout
    public function search(Request $request)
    {
        $query = $request->input('query');
        $tips = Tip::search($query)->get(); // Laravel Scout

        return view('tips.index', compact('tips'));
    }
}
