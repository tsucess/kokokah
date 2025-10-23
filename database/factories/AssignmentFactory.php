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
            'instructions' => $this->faker->paragraph(),
            'deadline' => $this->faker->dateTimeBetween('+1 day', '+30 days'),
            'max_score' => $this->faker->numberBetween(50, 100),
            'allowed_file_types' => json_encode(['pdf', 'doc', 'docx']),
            'max_file_size_mb' => 10,
        ];
    }
}
