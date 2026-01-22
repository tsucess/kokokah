<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(['course', 'assignment', 'quiz', 'system', 'payment', 'social']),
            'title' => $this->faker->sentence(),
            'message' => $this->faker->paragraph(),
            'data' => [],
            'action_url' => $this->faker->url(),
            'priority' => $this->faker->randomElement(['low', 'normal', 'high']),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function read()
    {
        return $this->state(function (array $attributes) {
            return [
                'read_at' => now(),
            ];
        });
    }

    public function unread()
    {
        return $this->state(function (array $attributes) {
            return [
                'read_at' => null,
            ];
        });
    }
}

