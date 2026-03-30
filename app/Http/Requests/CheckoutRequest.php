<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'plan_id' => 'required',
            'weeks_count' => 'required',
            'days_per_week' => 'required',
        ];
    }
}
