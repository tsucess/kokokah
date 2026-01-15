<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubscriptionPlan>
 */
class SubscriptionPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word() . ' Plan',
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 100, 10000),
            'duration' => $this->faker->randomElement([1, 7, 30, 365]),
            'duration_type' => $this->faker->randomElement(['daily', 'weekly', 'monthly', 'yearly']),
            'features' => [
                $this->faker->word(),
                $this->faker->word(),
                $this->faker->word(),
            ],
            'is_active' => true,
            'max_users' => null,
        ];
    }
}

