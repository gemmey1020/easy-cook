<?php

namespace App\Services;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressService
{
    public function createAddress(array $userAddress, $user_id = null)
    {
        // If user_id have value, then set primary to true come from the register form
        $primary = $user_id ? true : null;

        $user_id = $user_id ?? Auth::id();

        Address::create([
            'user_id' => $user_id,
            'street_address' => $userAddress['street_address'],
            //            'apartment_suite_unit' => $userAddress['apartment_suite_unit'],
            'town_city' => $userAddress['town_city'],
            'phone' => $userAddress['phone'],
            'primary' => $primary,
        ]);

        return true;
    }
}
