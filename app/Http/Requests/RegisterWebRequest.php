<?php

namespace App\Http\Requests;

use App\Models\InvitationJourney;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterWebRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'street_address' => 'required|string|max:255',
            //            'apartment_suite_unit' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'town_city' => 'required|string|max:255',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users,email',
            'phone' => 'required|string|unique:users,phone|regex:/^01[0125][0-9]{8}$/|size:11',
            'password' => 'required|string|min:8|confirmed',
            'final_data' => 'nullable|string',
            'invitation_journey_token' => 'nullable|string|max:100|exists:invitation_journeys,journey_token',
            'invitation_code' => 'nullable|string|max:100',
            'referral_source' => ['required', Rule::in(InvitationJourney::referralSources())],
            'referral_source_note' => 'nullable|string|max:255',
        ];
    }
}
