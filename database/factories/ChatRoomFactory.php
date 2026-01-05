<?php

namespace Database\Factories;

use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatRoom>
 */
class ChatRoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChatRoom::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['general', 'course']),
            'course_id' => null,
            'created_by' => User::factory(),
            'background_image' => null,
            'icon' => null,
            'color' => $this->faker->hexColor(),
            'is_active' => true,
            'is_archived' => false,
            'member_count' => 0,
            'message_count' => 0,
            'last_message_at' => null,
        ];
    }

    /**
     * Indicate that the chat room is general.
     */
    public function general(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'general',
            'course_id' => null,
        ]);
    }

    /**
     * Indicate that the chat room is for a course.
     */
    public function course(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'course',
            'course_id' => \App\Models\Course::factory(),
        ]);
    }

    /**
     * Indicate that the chat room is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_archived' => true,
        ]);
    }

    /**
     * Indicate that the chat room is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}

