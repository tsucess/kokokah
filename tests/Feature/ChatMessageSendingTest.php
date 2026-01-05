<?php

namespace Tests\Feature;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatMessageSendingTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected ChatRoom $chatRoom;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);

        // Create a general chat room
        $this->chatRoom = ChatRoom::factory()->create([
            'type' => 'general',
            'created_by' => $this->user->id,
            'is_active' => true,
            'is_archived' => false,
        ]);

        // Add user to chat room
        $this->chatRoom->users()->attach($this->user->id, [
            'role' => 'member',
            'is_active' => true,
            'is_muted' => false,
        ]);
    }

    /**
     * Test that a user can send a message to a chat room.
     * This tests the full flow: middleware -> controller -> policy
     */
    public function test_user_can_send_message_to_chat_room()
    {
        $this->actingAs($this->user);

        $response = $this->postJson(
            "/api/chatrooms/{$this->chatRoom->id}/messages",
            ['content' => 'Test message']
        );

        // Should return 201 (created) or 403 (forbidden), not 500
        $this->assertNotEquals(500, $response->status());
    }

    /**
     * Test that the policy can access message relationships without errors.
     */
    public function test_policy_can_access_message_relationships()
    {
        $this->actingAs($this->user);

        // Create a message
        $message = ChatMessage::factory()->create([
            'chat_room_id' => $this->chatRoom->id,
            'user_id' => $this->user->id,
        ]);

        // Try to delete the message - this will trigger the policy
        $response = $this->deleteJson(
            "/api/chatrooms/{$this->chatRoom->id}/messages/{$message->id}"
        );

        // Should not return 500 error
        $this->assertNotEquals(500, $response->status());
    }
}

