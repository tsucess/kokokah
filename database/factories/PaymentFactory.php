<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'course_id' => \App\Models\Course::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'currency' => 'NGN',
            'gateway' => $this->faker->randomElement(['paystack', 'flutterwave', 'stripe', 'paypal']),
            'gateway_transaction_id' => $this->faker->uuid(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'cancelled']),
            'payment_method' => $this->faker->randomElement(['card', 'bank_transfer', 'wallet']),
        ];
    }
}
