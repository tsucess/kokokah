<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
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
            'status' => $this->faker->randomElement(['active', 'completed', 'dropped', 'paused', 'cancelled']),
            'progress' => $this->faker->numberBetween(0, 100),
            'enrolled_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'completed_at' => $this->faker->optional(30)->dateTimeBetween('now', '+1 month'),
        ];
    }
}
