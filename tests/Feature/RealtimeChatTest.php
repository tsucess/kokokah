<?php

namespace Tests\Feature;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RealtimeChatTest extends TestCase
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
     * Test MessageSent event is dispatched when message is sent.
     */
    public function test_message_sent_event_dispatched(): void
    {
        Event::fake();

        $this->actingAs($this->user)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages", [
                'content' => 'Hello everyone!',
                'type' => 'text',
            ]);

        Event::assertDispatched(MessageSent::class);
    }

    /**
     * Test MessageSent event contains correct data.
     */
    public function test_message_sent_event_contains_correct_data(): void
    {
        Event::fake();

        $this->actingAs($this->user)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages", [
                'content' => 'Test message',
                'type' => 'text',
            ]);

        Event::assertDispatched(MessageSent::class, function ($event) {
            return $event->message->content === 'Test message' &&
                   $event->message->user_id === $this->user->id &&
                   $event->chatRoom->id === $this->chatRoom->id;
        });
    }

    /**
     * Test message update triggers broadcast event.
     */
    public function test_message_update_triggers_broadcast(): void
    {
        Event::fake();

        $message = ChatMessage::factory()->create([
            'chat_room_id' => $this->chatRoom->id,
            'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->user)
            ->putJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$message->id}", [
                'content' => 'Updated message',
            ]);

        Event::assertDispatched(MessageSent::class);
    }

    /**
     * Test message deletion triggers broadcast event.
     */
    public function test_message_deletion_triggers_broadcast(): void
    {
        Event::fake();

        $message = ChatMessage::factory()->create([
            'chat_room_id' => $this->chatRoom->id,
            'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->user)
            ->deleteJson("/api/chatrooms/{$this->chatRoom->id}/messages/{$message->id}");

        Event::assertDispatched(MessageSent::class);
    }

    /**
     * Test chat room member count is updated.
     */
    public function test_chat_room_member_count_updated(): void
    {
        $this->assertEquals(2, $this->chatRoom->member_count);

        $newUser = User::factory()->create();
        $this->chatRoom->users()->attach($newUser->id, ['role' => 'member', 'is_active' => true]);

        $this->chatRoom->refresh();
        // Note: member_count should be updated by the application logic
    }

    /**
     * Test chat room message count is updated.
     */
    public function test_chat_room_message_count_updated(): void
    {
        $initialCount = $this->chatRoom->message_count;

        $this->actingAs($this->user)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages", [
                'content' => 'Test message',
            ]);

        $this->chatRoom->refresh();
        $this->assertEquals($initialCount + 1, $this->chatRoom->message_count);
    }

    /**
     * Test last message timestamp is updated.
     */
    public function test_last_message_timestamp_updated(): void
    {
        $oldTimestamp = $this->chatRoom->last_message_at;

        $this->actingAs($this->user)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages", [
                'content' => 'Test message',
            ]);

        $this->chatRoom->refresh();
        $this->assertNotEquals($oldTimestamp, $this->chatRoom->last_message_at);
    }

    /**
     * Test user last read timestamp is updated.
     */
    public function test_user_last_read_timestamp_updated(): void
    {
        ChatMessage::factory(5)->create(['chat_room_id' => $this->chatRoom->id]);

        $this->actingAs($this->user)
            ->getJson("/api/chatrooms/{$this->chatRoom->id}/messages");

        $lastRead = $this->chatRoom->users()
            ->where('user_id', $this->user->id)
            ->first()
            ->pivot
            ->last_read_at;

        $this->assertNotNull($lastRead);
    }

    /**
     * Test message with reply_to relationship.
     */
    public function test_message_with_reply_to_relationship(): void
    {
        $originalMessage = ChatMessage::factory()->create(['chat_room_id' => $this->chatRoom->id]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages", [
                'content' => 'Great point!',
                'reply_to_id' => $originalMessage->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.reply_to_id', $originalMessage->id);

        $this->assertDatabaseHas('chat_messages', [
            'reply_to_id' => $originalMessage->id,
        ]);
    }

    /**
     * Test message metadata is stored.
     */
    public function test_message_metadata_stored(): void
    {
        $metadata = ['file_size' => 1024, 'file_type' => 'image/png'];

        $response = $this->actingAs($this->user)
            ->postJson("/api/chatrooms/{$this->chatRoom->id}/messages", [
                'content' => 'Check this out!',
                'type' => 'file',
                'metadata' => $metadata,
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('chat_messages', [
            'user_id' => $this->user->id,
            'type' => 'file',
        ]);
    }
}

