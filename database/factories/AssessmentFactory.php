<?php

namespace Database\Factories;

use App\Models\Assessment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentFactory extends Factory
{
    protected $model = Assessment::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'mobile' => $this->faker->unique()->phoneNumber,
            'city' => $this->faker->city,
            'weight' => $this->faker->randomFloat(2, 50, 120),
            'height' => $this->faker->randomFloat(2, 150, 200),
            'bmi' => $this->faker->randomFloat(2, 18, 35),
            'fat_percentage' => $this->faker->randomFloat(2, 10, 40),
            'muscle_mass' => $this->faker->randomFloat(2, 20, 70),
            'water_percentage' => $this->faker->randomFloat(2, 50, 70),
            'consultation_type' => $this->faker->randomElement(['in_person', 'phone']),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'notes' => $this->faker->sentence,
        ];
    }
}
