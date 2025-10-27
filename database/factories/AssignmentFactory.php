<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assignment>
 */
class AssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => \App\Models\Course::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(3),
            'instructions' => $this->faker->paragraph(2),
            'due_date' => $this->faker->dateTimeBetween('+1 day', '+30 days'),
            'max_points' => $this->faker->numberBetween(50, 100),
            'submission_type' => $this->faker->randomElement(['text', 'file', 'both']),
            'allow_late_submissions' => $this->faker->boolean(),
            'late_submission_penalty' => $this->faker->numberBetween(0, 20),
        ];
    }
}
