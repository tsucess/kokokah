<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'type' => $this->faker->randomElement(['Exams', 'Events', 'Alert', 'General Info']),
            'priority' => $this->faker->randomElement(['Info', 'Urgent', 'Warning']),
            'audience' => $this->faker->randomElement(['All students', 'Specific class', 'Specific level']),
            'audience_value' => null,
            'status' => $this->faker->randomElement(['draft', 'published']),
            'scheduled_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'published',
            ];
        });
    }

    public function draft()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'draft',
            ];
        });
    }
}

