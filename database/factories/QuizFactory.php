<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lesson_id' => \App\Models\Lesson::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'time_limit' => $this->faker->numberBetween(10, 120), // minutes
            'max_attempts' => $this->faker->numberBetween(1, 5),
            'passing_score' => $this->faker->numberBetween(60, 90),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
