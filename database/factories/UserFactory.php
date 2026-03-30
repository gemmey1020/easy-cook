<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,  // ✅ استخدام first_name
            'last_name' => $this->faker->lastName,    // ✅ استخدام last_name
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // كلمة مرور افتراضية
            'remember_token' => Str::random(10),
        ];
    }
}
