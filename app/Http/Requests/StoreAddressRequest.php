<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'street_address' => 'required|string|max:255',
            //            'apartment_suite_unit' => 'nullable|string|max:255',
            'town_city' => 'required|string|max:255',
            //            'postcode' => 'required|digits_between:4,10',
            'phone' => 'required|string|regex:/^01[0125][0-9]{8}$/|size:11',
        ];
    }
}
