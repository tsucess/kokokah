<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentMethod>
 */
class PaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cardNumber = $this->faker->creditCardNumber('Visa');
        $lastFour = substr($cardNumber, -4);

        return [
            'user_id' => User::factory(),
            'card_holder_name' => $this->faker->name(),
            'card_number' => Crypt::encryptString($cardNumber),
            'card_last_four' => $lastFour,
            'expiry_date' => $this->faker->creditCardExpirationDateString(),
            'cvv' => Crypt::encryptString($this->faker->numerify('###')),
            'card_type' => 'visa',
            'is_default' => false,
            'is_saved' => true,
            'last_used_at' => null
        ];
    }

    /**
     * Mark this payment method as default
     */
    public function default(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_default' => true,
            ];
        });
    }

    /**
     * Set a specific user
     */
    public function forUser(User $user): static
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }
}
