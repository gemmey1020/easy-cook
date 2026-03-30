<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'street_address' => $this->faker->streetAddress(),
            'apartment_suite_unit' => $this->faker->secondaryAddress(),
            'town_city' => $this->faker->city(),
            'postcode' => $this->faker->postcode(),
            'phone' => '01' . $this->faker->randomElement(['0', '1', '2', '5']) . $this->faker->numerify('########'),
            'primary' => $this->faker->boolean(),
        ];
    }
}
