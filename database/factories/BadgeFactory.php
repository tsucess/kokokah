<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Badge>
 */
class BadgeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word() . ' Badge',
            'icon' => $this->faker->randomElement(['trophy', 'star', 'lightning', 'fire', 'medal', 'crown']),
            'criteria' => json_encode([
                'type' => $this->faker->randomElement(['courses_completed', 'quiz_score', 'login_streak', 'participation']),
                'value' => $this->faker->numberBetween(1, 100)
            ]),
        ];
    }
}
