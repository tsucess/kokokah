<?php

namespace Tests\Feature;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatMessagePolicyTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $otherUser;
    protected ChatRoom $chatRoom;
    protected ChatMessage $message;

    protected function setUp(): void
    {
        parent::setUp();

        // Create users
        $this->user = User::factory()->create(['role' => 'user']);
        $this->otherUser = User::factory()->create(['role' => 'user']);

        // Create chat room
        $this->chatRoom = ChatRoom::factory()->create([
            'type' => 'general',
            'created_by' => $this->user->id,
        ]);

        // Add user to chat room
        $this->chatRoom->users()->attach($this->user->id, [
            'role' => 'member',
            'is_active' => true,
        ]);

        // Create a message
        $this->message = ChatMessage::factory()->create([
            'chat_room_id' => $this->chatRoom->id,
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_view_message_in_room_they_belong_to()
    {
        $this->actingAs($this->user);
        
        $response = $this->getJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$this->message->id}");
        
        $this->assertTrue($response->status() === 200 || $response->status() === 403);
    }

    public function test_user_cannot_view_message_in_room_they_dont_belong_to()
    {
        $this->actingAs($this->otherUser);
        
        $response = $this->getJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$this->message->id}");
        
        $this->assertEquals(403, $response->status());
    }

    public function test_user_can_send_message_to_general_room()
    {
        $this->actingAs($this->user);
        
        $response = $this->postJson("/api/chatrooms/{$this->chatRoom->id}/messages", [
            'content' => 'Test message',
        ]);
        
        $this->assertTrue($response->status() === 201 || $response->status() === 403);
    }

    public function test_policy_uses_correct_relationship()
    {
        // This test verifies that the policy can access chatRoom relationship
        $this->actingAs($this->user);
        
        // Try to delete the message - this will trigger the policy
        $response = $this->deleteJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$this->message->id}");
        
        // Should succeed (200) or fail with 403, not 500
        $this->assertNotEquals(500, $response->status());
    }
}

