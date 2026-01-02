<?php

namespace Tests\Feature;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\MessageReaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatReactionsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $otherUser;
    protected ChatRoom $chatRoom;
    protected ChatMessage $message;

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

        // Create a message
        $this->message = ChatMessage::factory()->create([
            'chat_room_id' => $this->chatRoom->id,
            'user_id' => $this->otherUser->id,
        ]);
    }

    /**
     * Test adding reaction to message.
     */
    public function test_add_reaction_to_message(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$this->message->id}/reactions", [
                'emoji' => '👍',
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('message_reactions', [
            'message_id' => $this->message->id,
            'user_id' => $this->user->id,
            'emoji' => '👍',
        ]);
    }

    /**
     * Test removing reaction from message.
     */
    public function test_remove_reaction_from_message(): void
    {
        // Add reaction first
        MessageReaction::create([
            'message_id' => $this->message->id,
            'user_id' => $this->user->id,
            'emoji' => '👍',
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$this->message->id}/reactions/👍");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('message_reactions', [
            'message_id' => $this->message->id,
            'user_id' => $this->user->id,
            'emoji' => '👍',
        ]);
    }

    /**
     * Test getting message reactions.
     */
    public function test_get_message_reactions(): void
    {
        // Add multiple reactions
        MessageReaction::create([
            'message_id' => $this->message->id,
            'user_id' => $this->user->id,
            'emoji' => '👍',
        ]);

        MessageReaction::create([
            'message_id' => $this->message->id,
            'user_id' => $this->otherUser->id,
            'emoji' => '👍',
        ]);

        MessageReaction::create([
            'message_id' => $this->message->id,
            'user_id' => $this->user->id,
            'emoji' => '❤️',
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$this->message->id}/reactions");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test reaction count is accurate.
     */
    public function test_reaction_count_is_accurate(): void
    {
        // Add reactions
        MessageReaction::create([
            'message_id' => $this->message->id,
            'user_id' => $this->user->id,
            'emoji' => '👍',
        ]);

        MessageReaction::create([
            'message_id' => $this->message->id,
            'user_id' => $this->otherUser->id,
            'emoji' => '👍',
        ]);

        $this->message->refresh();

        $this->assertEquals(2, $this->message->reactions()->count());
    }

    /**
     * Test user cannot add duplicate reaction.
     */
    public function test_user_cannot_add_duplicate_reaction(): void
    {
        // Add reaction first
        MessageReaction::create([
            'message_id' => $this->message->id,
            'user_id' => $this->user->id,
            'emoji' => '👍',
        ]);

        // Try to add same reaction again
        $response = $this->actingAs($this->user)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$this->message->id}/reactions", [
                'emoji' => '👍',
            ]);

        $response->assertStatus(409); // Conflict
    }

    /**
     * Test user can add different reactions.
     */
    public function test_user_can_add_different_reactions(): void
    {
        // Add first reaction
        $this->actingAs($this->user)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$this->message->id}/reactions", [
                'emoji' => '👍',
            ]);

        // Add second reaction
        $response = $this->actingAs($this->user)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$this->message->id}/reactions", [
                'emoji' => '❤️',
            ]);

        $response->assertStatus(201);

        $this->assertEquals(2, $this->message->reactions()->where('user_id', $this->user->id)->count());
    }

    /**
     * Test reaction validation.
     */
    public function test_reaction_validation(): void
    {
        // Missing emoji
        $response = $this->actingAs($this->user)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$this->message->id}/reactions", []);

        $response->assertStatus(422);
    }

    /**
     * Test non-member cannot add reaction.
     */
    public function test_non_member_cannot_add_reaction(): void
    {
        $nonMember = User::factory()->create();

        $response = $this->actingAs($nonMember)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$this->message->id}/reactions", [
                'emoji' => '👍',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test reaction summary by emoji.
     */
    public function test_reaction_summary_by_emoji(): void
    {
        // Add multiple reactions
        MessageReaction::create([
            'message_id' => $this->message->id,
            'user_id' => $this->user->id,
            'emoji' => '👍',
        ]);

        MessageReaction::create([
            'message_id' => $this->message->id,
            'user_id' => $this->otherUser->id,
            'emoji' => '👍',
        ]);

        MessageReaction::create([
            'message_id' => $this->message->id,
            'user_id' => $this->user->id,
            'emoji' => '❤️',
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$this->message->id}/reactions/summary");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['emoji', 'count', 'users']
                ]
            ]);
    }
}

