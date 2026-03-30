<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssessmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'weight' => ['nullable', 'string', 'max:255'],
            'height' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', Rule::in(['Male', 'Female'])],
            'consultation_type' => ['nullable', 'string', 'max:255'],
            'age' => ['nullable', 'numeric', 'min:1', 'max:120'],
            'fats' => ['nullable', 'numeric', 'max:255'],
            'goal' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'activity_level' => ['nullable', 'string', 'max:255'],
            'calories_needed' => ['nullable', 'numeric', 'min:1'],
            'protein_needed' => ['nullable', 'string', 'max:255'],
            'carbohydrates' => ['nullable', 'numeric', 'max:255'],
        ];
    }
}
