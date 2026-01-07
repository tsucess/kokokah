<?php

namespace Tests\Feature;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatMessageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $otherUser;
    protected ChatRoom $chatRoom;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->user = User::factory()->create(['role' => 'student']);
        $this->otherUser = User::factory()->create(['role' => 'student']);

        // Create chat room
        $this->chatRoom = ChatRoom::factory()->create();

        // Add users to chat room
        $this->chatRoom->users()->attach($this->user->id, ['role' => 'member', 'is_active' => true]);
        $this->chatRoom->users()->attach($this->otherUser->id, ['role' => 'member', 'is_active' => true]);
    }

    /**
     * Test fetching messages with pagination.
     */
    public function test_fetch_messages_with_pagination(): void
    {
        // Create test messages
        ChatMessage::factory(75)->create(['chat_room_id' => $this->chatRoom->id]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/chatrooms/{$this->chatRoom->id}/messages?per_page=50&page=1");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'content', 'user_id', 'created_at']
                ],
                'pagination' => ['total', 'per_page', 'current_page', 'last_page']
            ])
            ->assertJsonPath('pagination.total', 75)
            ->assertJsonPath('pagination.per_page', 50)
            ->assertJsonPath('pagination.current_page', 1);
    }

    /**
     * Test sending a message.
     */
    public function test_send_message(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages", [
                'content' => 'Hello everyone!',
                'type' => 'text',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.content', 'Hello everyone!')
            ->assertJsonPath('data.user_id', $this->user->id);

        $this->assertDatabaseHas('chat_messages', [
            'chat_room_id' => $this->chatRoom->id,
            'user_id' => $this->user->id,
            'content' => 'Hello everyone!',
        ]);
    }

    /**
     * Test non-member cannot send message.
     */
    public function test_non_member_cannot_send_message(): void
    {
        $nonMember = User::factory()->create();

        $response = $this->actingAs($nonMember)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages", [
                'content' => 'Hello!',
            ]);

        $response->assertStatus(403)
            ->assertJsonPath('success', false);
    }

    /**
     * Test muted user cannot send message.
     */
    public function test_muted_user_cannot_send_message(): void
    {
        // Mute the user
        $this->chatRoom->users()
            ->updateExistingPivot($this->user->id, ['is_muted' => true]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages", [
                'content' => 'Hello!',
            ]);

        $response->assertStatus(403)
            ->assertJsonPath('success', false);
    }

    /**
     * Test replying to a message.
     */
    public function test_reply_to_message(): void
    {
        $originalMessage = ChatMessage::factory()->create(['chat_room_id' => $this->chatRoom->id]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages", [
                'content' => 'Great point!',
                'type' => 'text',
                'reply_to_id' => $originalMessage->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.reply_to_id', $originalMessage->id);
    }

    /**
     * Test updating own message.
     */
    public function test_update_own_message(): void
    {
        $message = ChatMessage::factory()->create([
            'chat_room_id' => $this->chatRoom->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->putJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$message->id}", [
                'content' => 'Updated content',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.edited_content', 'Updated content')
            ->assertJsonPath('data.is_edited', true);
    }

    /**
     * Test cannot update other's message.
     */
    public function test_cannot_update_others_message(): void
    {
        $message = ChatMessage::factory()->create([
            'chat_room_id' => $this->chatRoom->id,
            'user_id' => $this->otherUser->id,
        ]);

        $response = $this->actingAs($this->user)
            ->putJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$message->id}", [
                'content' => 'Updated content',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test deleting own message.
     */
    public function test_delete_own_message(): void
    {
        $message = ChatMessage::factory()->create([
            'chat_room_id' => $this->chatRoom->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$message->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('chat_messages', [
            'id' => $message->id,
            'is_deleted' => true,
        ]);
    }

    /**
     * Test filtering messages by type.
     */
    public function test_filter_messages_by_type(): void
    {
        ChatMessage::factory(5)->create([
            'chat_room_id' => $this->chatRoom->id,
            'type' => 'text',
        ]);
        ChatMessage::factory(3)->create([
            'chat_room_id' => $this->chatRoom->id,
            'type' => 'image',
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/chatrooms/{$this->chatRoom->id}/messages?type=image");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test message validation.
     */
    public function test_message_validation(): void
    {
        // Empty content
        $response = $this->actingAs($this->user)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages", [
                'content' => '',
            ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);

        // Content too long
        $response = $this->actingAs($this->user)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages", [
                'content' => str_repeat('a', 5001),
            ]);

        $response->assertStatus(422);
    }

    /**
     * Test getting specific message.
     */
    public function test_get_specific_message(): void
    {
        $message = ChatMessage::factory()->create(['chat_room_id' => $this->chatRoom->id]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$message->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $message->id)
            ->assertJsonPath('data.content', $message->content);
    }

    /**
     * Test unauthenticated access denied.
     */
    public function test_unauthenticated_access_denied(): void
    {
        $response = $this->getJson("/api/chatrooms/{$this->chatRoom->id}/messages");

        $response->assertStatus(401);
    }

    /**
     * Test admin can edit other user's message.
     */
    public function test_admin_can_edit_others_message(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        // Add admin to chatroom so they can access it
        $this->chatRoom->users()->attach($admin->id, ['role' => 'admin', 'is_active' => true]);

        $message = ChatMessage::factory()->create([
            'chat_room_id' => $this->chatRoom->id,
            'user_id' => $this->otherUser->id,
        ]);

        $response = $this->actingAs($admin)
            ->putJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$message->id}", [
                'content' => 'Admin edited content',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.edited_content', 'Admin edited content');
    }

    /**
     * Test superadmin can edit other user's message.
     */
    public function test_superadmin_can_edit_others_message(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        // Add superadmin to chatroom so they can access it
        $this->chatRoom->users()->attach($superadmin->id, ['role' => 'admin', 'is_active' => true]);

        $message = ChatMessage::factory()->create([
            'chat_room_id' => $this->chatRoom->id,
            'user_id' => $this->otherUser->id,
        ]);

        $response = $this->actingAs($superadmin)
            ->putJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$message->id}", [
                'content' => 'Superadmin edited content',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.edited_content', 'Superadmin edited content');
    }

    /**
     * Test admin can delete other user's message.
     */
    public function test_admin_can_delete_others_message(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        // Add admin to chatroom so they can access it
        $this->chatRoom->users()->attach($admin->id, ['role' => 'admin', 'is_active' => true]);

        $message = ChatMessage::factory()->create([
            'chat_room_id' => $this->chatRoom->id,
            'user_id' => $this->otherUser->id,
        ]);

        $response = $this->actingAs($admin)
            ->deleteJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$message->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('chat_messages', [
            'id' => $message->id,
            'is_deleted' => true,
        ]);
    }

    /**
     * Test superadmin can delete other user's message.
     */
    public function test_superadmin_can_delete_others_message(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        // Add superadmin to chatroom so they can access it
        $this->chatRoom->users()->attach($superadmin->id, ['role' => 'admin', 'is_active' => true]);

        $message = ChatMessage::factory()->create([
            'chat_room_id' => $this->chatRoom->id,
            'user_id' => $this->otherUser->id,
        ]);

        $response = $this->actingAs($superadmin)
            ->deleteJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$message->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('chat_messages', [
            'id' => $message->id,
            'is_deleted' => true,
        ]);
    }
}

