<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(3),
            'curriculum_category_id' => \App\Models\CurriculumCategory::factory(),
            'instructor_id' => \App\Models\User::factory()->state(['role' => 'instructor']),
            'term_id' => \App\Models\Term::factory(),
            'level_id' => \App\Models\Level::factory(),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
        ];
    }
}
