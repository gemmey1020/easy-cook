<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index()
    {
        $assessments = Assessment::with('user')->get();

        return view('dash.online.index', compact('assessments'));
    }

    public function show($id)
    {
        $assessment = Assessment::with('notes', 'user')->findOrFail($id);
        return view('dash.online.show', compact('assessment'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string',
        ]);

        $assessment = Assessment::findOrFail($id);

        // Update the assessment status
        $assessment->update([
            'status' => $request->status,
//            'notes' => $request->notes,
        ]);

        // If note was provided, store it in the notes table
        if ($request->filled('notes')) {
            $assessment->notes()->create([
                'note' => $request->notes,
            ]);
        }

        return redirect()->route('assessments.index')->with('success', 'Assessment updated successfully!');
    }
}
