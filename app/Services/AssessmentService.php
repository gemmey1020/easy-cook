<?php

namespace App\Services;

use App\Models\Assessment;

class AssessmentService
{
    public function createAssessment($assessment, $user_id)
    {
        Assessment::create([
            'user_id' => $user_id,
            'weight' => $assessment['weight'] ?? null,
            'height' => $assessment['height'] ?? null,
            'gender' => $assessment['gender'] ?? null,
            'consultation_type' => $assessment['consultation_type'] ?? null,
            'age' => $assessment['age'] ?? null,
            'fat_percentage' => $assessment['fats'] ?? null,
            'goal' => $assessment['goal'] ?? null,
            'birth_date' => $assessment['birth_date'] ?? null,
            'activity_level' => $assessment['activity_level'] ?? null,
            'calories_needed' => $assessment['calories_needed'] ?? null,
            'protein_needed' => $assessment['protein_needed'] ?? null,
            'carbohydrates' => $assessment['carbohydrates'] ?? null,
        ]);

        return true;
    }
}
