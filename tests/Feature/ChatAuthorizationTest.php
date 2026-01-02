<?php

namespace Tests\Feature;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected User $instructor;
    protected User $admin;
    protected User $otherStudent;
    protected ChatRoom $generalRoom;
    protected ChatRoom $courseRoom;
    protected Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        // Create users
        $this->student = User::factory()->create(['role' => 'student']);
        $this->instructor = User::factory()->create(['role' => 'instructor']);
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->otherStudent = User::factory()->create(['role' => 'student']);

        // Create course
        $this->course = Course::factory()->create(['instructor_id' => $this->instructor->id]);

        // Create general room
        $this->generalRoom = ChatRoom::factory()->create([
            'type' => 'general',
            'created_by' => $this->student->id,
        ]);

        // Create course room
        $this->courseRoom = ChatRoom::factory()->create([
            'type' => 'course',
            'course_id' => $this->course->id,
            'created_by' => $this->instructor->id,
        ]);

        // Add student to general room
        $this->generalRoom->users()->attach($this->student->id, ['role' => 'member']);

        // Enroll student in course
        Enrollment::factory()->create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);
    }

    /**
     * Test unauthenticated user cannot access chat.
     */
    public function test_unauthenticated_user_cannot_access_chat(): void
    {
        $response = $this->getJson('/api/chatrooms/1/messages');

        $response->assertStatus(401);
        $response->assertJsonPath('success', false);
    }

    /**
     * Test user can view room they belong to.
     */
    public function test_user_can_view_room_they_belong_to(): void
    {
        $response = $this->actingAs($this->student)
            ->getJson("/api/chatrooms/{$this->generalRoom->id}/messages");

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    /**
     * Test user can view general room even if not explicitly added.
     * General chatrooms are accessible to all authenticated users.
     */
    public function test_user_can_view_general_room_without_explicit_membership(): void
    {
        $response = $this->actingAs($this->otherStudent)
            ->getJson("/api/chatrooms/{$this->generalRoom->id}/messages");

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    /**
     * Test enrolled student can view course room.
     */
    public function test_enrolled_student_can_view_course_room(): void
    {
        $response = $this->actingAs($this->student)
            ->getJson("/api/chatrooms/{$this->courseRoom->id}/messages");

        $response->assertStatus(200);
    }

    /**
     * Test non-enrolled student cannot view course room.
     */
    public function test_non_enrolled_student_cannot_view_course_room(): void
    {
        $response = $this->actingAs($this->otherStudent)
            ->getJson("/api/chatrooms/{$this->courseRoom->id}/messages");

        $response->assertStatus(403);
    }

    /**
     * Test instructor can view course room.
     */
    public function test_instructor_can_view_course_room(): void
    {
        $response = $this->actingAs($this->instructor)
            ->getJson("/api/chatrooms/{$this->courseRoom->id}/messages");

        $response->assertStatus(200);
    }

    /**
     * Test admin can view any room.
     */
    public function test_admin_can_view_any_room(): void
    {
        $response = $this->actingAs($this->admin)
            ->getJson("/api/chatrooms/{$this->generalRoom->id}/messages");

        $response->assertStatus(200);
    }

    /**
     * Test user can send message in room they belong to.
     */
    public function test_user_can_send_message_in_room_they_belong_to(): void
    {
        $response = $this->actingAs($this->student)
            ->postJson("/api/chatrooms/{$this->generalRoom->id}/messages", [
                'content' => 'Hello!',
                'type' => 'text',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('chat_messages', [
            'content' => 'Hello!',
            'user_id' => $this->student->id,
        ]);
    }

    /**
     * Test user cannot send message in room they don't belong to.
     */
    public function test_user_cannot_send_message_in_room_they_dont_belong_to(): void
    {
        $response = $this->actingAs($this->otherStudent)
            ->postJson("/api/chatrooms/{$this->generalRoom->id}/messages", [
                'content' => 'Hello!',
                'type' => 'text',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test muted user cannot send message.
     */
    public function test_muted_user_cannot_send_message(): void
    {
        // Mute the student
        $this->generalRoom->users()
            ->updateExistingPivot($this->student->id, ['is_muted' => true]);

        $response = $this->actingAs($this->student)
            ->postJson("/api/chatrooms/{$this->generalRoom->id}/messages", [
                'content' => 'Hello!',
                'type' => 'text',
            ]);

        $response->assertStatus(403);
        $response->assertJsonPath('error', 'user_muted');
    }

    /**
     * Test user can edit their own message.
     */
    public function test_user_can_edit_their_own_message(): void
    {
        $message = ChatMessage::factory()->create([
            'chat_room_id' => $this->generalRoom->id,
            'user_id' => $this->student->id,
        ]);

        $response = $this->actingAs($this->student)
            ->putJson("/api/chatrooms/{$this->generalRoom->id}/messages/{$message->id}", [
                'content' => 'Updated message',
            ]);

        $response->assertStatus(200);
    }

    /**
     * Test user cannot edit others' messages.
     */
    public function test_user_cannot_edit_others_messages(): void
    {
        $message = ChatMessage::factory()->create([
            'chat_room_id' => $this->generalRoom->id,
            'user_id' => $this->student->id,
        ]);

        $this->generalRoom->users()->attach($this->otherStudent->id, ['role' => 'member']);

        $response = $this->actingAs($this->otherStudent)
            ->putJson("/api/chatrooms/{$this->generalRoom->id}/messages/{$message->id}", [
                'content' => 'Updated message',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test admin can edit any message.
     */
    public function test_admin_can_edit_any_message(): void
    {
        $message = ChatMessage::factory()->create([
            'chat_room_id' => $this->generalRoom->id,
            'user_id' => $this->student->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->putJson("/api/chatrooms/{$this->generalRoom->id}/messages/{$message->id}", [
                'content' => 'Updated by admin',
            ]);

        $response->assertStatus(200);
    }

    /**
     * Test user can delete their own message.
     */
    public function test_user_can_delete_their_own_message(): void
    {
        $message = ChatMessage::factory()->create([
            'chat_room_id' => $this->generalRoom->id,
            'user_id' => $this->student->id,
        ]);

        $response = $this->actingAs($this->student)
            ->deleteJson("/api/chatrooms/{$this->generalRoom->id}/messages/{$message->id}");

        $response->assertStatus(200);
    }

    /**
     * Test user cannot delete others' messages.
     */
    public function test_user_cannot_delete_others_messages(): void
    {
        $message = ChatMessage::factory()->create([
            'chat_room_id' => $this->generalRoom->id,
            'user_id' => $this->student->id,
        ]);

        $this->generalRoom->users()->attach($this->otherStudent->id, ['role' => 'member']);

        $response = $this->actingAs($this->otherStudent)
            ->deleteJson("/api/chatrooms/{$this->generalRoom->id}/messages/{$message->id}");

        $response->assertStatus(403);
    }

    /**
     * Test room creator can delete messages.
     */
    public function test_room_creator_can_delete_messages(): void
    {
        $message = ChatMessage::factory()->create([
            'chat_room_id' => $this->generalRoom->id,
            'user_id' => $this->otherStudent->id,
        ]);

        $response = $this->actingAs($this->student)
            ->deleteJson("/api/chatrooms/{$this->generalRoom->id}/messages/{$message->id}");

        $response->assertStatus(200);
    }

    /**
     * Test instructor can delete messages in course room.
     */
    public function test_instructor_can_delete_messages_in_course_room(): void
    {
        $message = ChatMessage::factory()->create([
            'chat_room_id' => $this->courseRoom->id,
            'user_id' => $this->student->id,
        ]);

        $response = $this->actingAs($this->instructor)
            ->deleteJson("/api/chatrooms/{$this->courseRoom->id}/messages/{$message->id}");

        $response->assertStatus(200);
    }

    /**
     * Test inactive user cannot access chat.
     */
    public function test_inactive_user_cannot_access_chat(): void
    {
        $this->student->update(['is_active' => false]);

        $response = $this->actingAs($this->student)
            ->getJson("/api/chatrooms/{$this->generalRoom->id}/messages");

        $response->assertStatus(403);
    }
}

