<?php

namespace Tests\Unit;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteModelBindingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that route model binding resolves chatRoom parameter correctly.
     */
    public function test_route_model_binding_resolves_chat_room()
    {
        // Create a user
        $user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);

        // Create a chat room
        $chatRoom = ChatRoom::factory()->create([
            'type' => 'general',
            'created_by' => $user->id,
            'is_active' => true,
        ]);

        // Add user to chat room
        $chatRoom->users()->attach($user->id, [
            'role' => 'member',
            'is_active' => true,
            'is_muted' => false,
        ]);

        $this->actingAs($user);

        // Make a request to the chat room messages endpoint
        $response = $this->getJson("/api/chatrooms/{$chatRoom->id}/messages");

        // Should not return 500 error
        $this->assertNotEquals(500, $response->status());

        // Should return 200 or 403 (depending on authorization)
        $this->assertTrue(in_array($response->status(), [200, 403]),
            "Expected status 200 or 403, got {$response->status()}");
    }

    /**
     * Test that middleware receives ChatRoom object, not string.
     */
    public function test_middleware_receives_chat_room_object()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);

        $chatRoom = ChatRoom::factory()->create([
            'type' => 'general',
            'created_by' => $user->id,
            'is_active' => true,
        ]);

        $chatRoom->users()->attach($user->id, [
            'role' => 'member',
            'is_active' => true,
            'is_muted' => false,
        ]);

        $this->actingAs($user);

        // Try to send a message - this will trigger the middleware
        $response = $this->postJson(
            "/api/chatrooms/{$chatRoom->id}/messages",
            ['content' => 'Test message']
        );

        // Should not return 500 error (which would indicate string->users() call)
        $this->assertNotEquals(500, $response->status());
    }

    /**
     * Test that muted users cannot send messages.
     */
    public function test_muted_user_cannot_send_message()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);

        $chatRoom = ChatRoom::factory()->create([
            'type' => 'general',
            'created_by' => $user->id,
            'is_active' => true,
        ]);

        // Add user as muted
        $chatRoom->users()->attach($user->id, [
            'role' => 'member',
            'is_active' => true,
            'is_muted' => true,
        ]);

        $this->actingAs($user);

        $response = $this->postJson(
            "/api/chatrooms/{$chatRoom->id}/messages",
            ['content' => 'Test message']
        );

        // Should return 403 Forbidden
        $this->assertEquals(403, $response->status());
        $this->assertEquals('user_muted', $response->json('error'));
    }
}

