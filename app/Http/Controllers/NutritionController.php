<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use Illuminate\Http\Request;

class NutritionController extends Controller
{
    // عرض التقييمات المرتبطة بأخصائي التغذية فقط
    public function index()
    {
        $assessments = Assessment::select('id', 'user_id', 'status')
            ->where('status', 'pending')
            ->with('user') // Fixed the relationship columns
            ->get();

        return view('dash.online-nutrition.index', compact('assessments'));
    }

    // عرض تفاصيل تقييم معين
    public function show($id)
    {
        $assessment = Assessment::with('user')->findOrFail($id);

        return view('dash.online-nutrition.show', compact('assessment'));
    }

    // قبول أو رفض تقييم
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'nullable|string',
        ]);

        $assessment = Assessment::findOrFail($id);
        $assessment->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('nutrition.index')->with('success', 'Assessment status updated successfully!');
    }

    // إرسال ملاحظات للعميل
    public function sendNotes(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string',
        ]);

        $assessment = Assessment::findOrFail($id);
        $assessment->update([
            'notes' => $request->notes,
        ]);

        // إضافة منطق إرسال الملاحظات عبر البريد الإلكتروني إذا لزم الأمر
        return redirect()->route('nutrition.show', $id)->with('success', 'Notes sent successfully!');
    }
}
