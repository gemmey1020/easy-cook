<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'subscription_ids' => [],
            'amount' => $this->faker->randomFloat(2, 100, 500),
            'payment_status' => 'pending',
            'payment_method' => 'Paymob',
            'transaction_id' => 'order_'.uniqid(),
            'order_id' => 'intent_'.uniqid(),
        ];
    }
}
