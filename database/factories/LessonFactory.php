<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
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
            'content' => $this->faker->text(1000),
            'video_url' => $this->faker->optional()->url(),
            'duration_minutes' => $this->faker->numberBetween(5, 120),
            'order' => $this->faker->numberBetween(1, 20),
            'is_free' => $this->faker->boolean(30), // 30% chance of being free
        ];
    }
}
