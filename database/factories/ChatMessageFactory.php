<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatMessage>
 */
class ChatMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChatMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chat_room_id' => ChatRoom::factory(),
            'user_id' => User::factory(),
            'content' => $this->faker->sentence(),
            'type' => 'text',
            'reply_to_id' => null,
            'is_pinned' => false,
            'is_deleted' => false,
            'edited_at' => null,
        ];
    }

    /**
     * Indicate that the message is a reply.
     */
    public function reply(ChatMessage $message): static
    {
        return $this->state(fn (array $attributes) => [
            'reply_to_id' => $message->id,
        ]);
    }

    /**
     * Indicate that the message is pinned.
     */
    public function pinned(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_pinned' => true,
        ]);
    }

    /**
     * Indicate that the message is deleted.
     */
    public function deleted(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_deleted' => true,
        ]);
    }

    /**
     * Indicate that the message is an image.
     */
    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'image',
            'content' => $this->faker->imageUrl(),
        ]);
    }

    /**
     * Indicate that the message is a file.
     */
    public function file(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'file',
            'content' => $this->faker->word() . '.pdf',
        ]);
    }
}

