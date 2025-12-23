<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\Badge;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ConversationTest extends TestCase
{
    use RefreshDatabase;

    protected $instructor;
    protected $student;
    protected $course;
    protected $conversation;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->instructor = User::create([
            'first_name' => 'Test',
            'last_name' => 'Instructor',
            'email' => 'instructor@test.com',
            'password' => bcrypt('password'),
            'role' => 'instructor'
        ]);
        $this->student = User::create([
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'role' => 'student'
        ]);

        // Create a curriculum category
        $category = \App\Models\CurriculumCategory::create([
            'title' => 'Test Category',
            'name' => 'Test Category',
            'description' => 'Test category',
            'user_id' => $this->instructor->id
        ]);

        // Create a course without using factory to avoid term issues
        $this->course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test course description',
            'instructor_id' => $this->instructor->id,
            'curriculum_category_id' => $category->id,
            'price' => 99.99,
            'free' => false
        ]);

        // Enroll student in course
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active'
        ]);

        // Create a conversation
        $this->conversation = Conversation::create([
            'course_id' => $this->course->id,
            'name' => 'General Discussion',
            'description' => 'General discussion for the course',
            'created_by' => $this->instructor->id
        ]);

        // Add participants
        $this->conversation->addParticipant($this->instructor->id);
        $this->conversation->addParticipant($this->student->id);
    }

    /**
     * Test that a conversation is created when a course is created
     */
    public function test_conversation_created_with_course()
    {
        // Create a curriculum category for the new course
        $category = \App\Models\CurriculumCategory::create([
            'title' => 'New Category',
            'name' => 'New Category',
            'description' => 'New category',
            'user_id' => $this->instructor->id
        ]);

        // Create a new course directly
        $newCourse = Course::create([
            'title' => 'New Test Course',
            'description' => 'Test course description',
            'instructor_id' => $this->instructor->id,
            'curriculum_category_id' => $category->id,
            'price' => 99.99,
            'free' => false
        ]);

        // Verify conversation was created
        $this->assertNotNull($newCourse);
        $this->assertTrue($newCourse->conversations()->exists());
    }

    /**
     * Test getting conversations for a course
     */
    public function test_get_conversations_by_course()
    {
        Sanctum::actingAs($this->student);

        $response = $this->getJson("/api/conversations/course/{$this->course->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'course_id',
                                'name',
                                'description'
                            ]
                        ]
                    ]
                ]);
    }

    /**
     * Test sending a message to a conversation
     */
    public function test_send_message_to_conversation()
    {
        Sanctum::actingAs($this->student);

        $response = $this->postJson("/api/conversations/{$this->conversation->id}/messages", [
            'message' => 'This is a test message'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'conversation_id',
                        'user_id',
                        'message'
                    ]
                ]);

        // Verify message was saved
        $this->assertDatabaseHas('conversation_messages', [
            'conversation_id' => $this->conversation->id,
            'user_id' => $this->student->id,
            'message' => 'This is a test message'
        ]);
    }

    /**
     * Test getting messages from a conversation
     */
    public function test_get_messages_from_conversation()
    {
        // Create some messages
        ConversationMessage::create([
            'conversation_id' => $this->conversation->id,
            'user_id' => $this->student->id,
            'message' => 'Test message 1'
        ]);

        ConversationMessage::create([
            'conversation_id' => $this->conversation->id,
            'user_id' => $this->instructor->id,
            'message' => 'Test message 2'
        ]);

        Sanctum::actingAs($this->student);

        $response = $this->getJson("/api/conversations/{$this->conversation->id}/messages");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'conversation_id',
                                'user_id',
                                'message'
                            ]
                        ]
                    ]
                ]);
    }

    /**
     * Test joining a conversation
     */
    public function test_join_conversation()
    {
        $newStudent = User::create([
            'first_name' => 'New',
            'last_name' => 'Student',
            'email' => 'newstudent@test.com',
            'password' => bcrypt('password'),
            'role' => 'student'
        ]);

        Enrollment::create([
            'user_id' => $newStudent->id,
            'course_id' => $this->course->id,
            'status' => 'active'
        ]);

        // Remove the new student from conversation first (they weren't added in setUp)
        // Then test joining
        $response = $this->actingAs($newStudent)->postJson("/api/conversations/{$this->conversation->id}/join", []);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data'
                ]);

        // Verify participant was added
        $this->assertTrue($this->conversation->hasParticipant($newStudent->id));
    }

    /**
     * Test marking a message as helpful
     */
    public function test_mark_message_as_helpful()
    {
        $message = ConversationMessage::create([
            'conversation_id' => $this->conversation->id,
            'user_id' => $this->student->id,
            'message' => 'Helpful message'
        ]);

        Sanctum::actingAs($this->instructor);

        $response = $this->postJson("/api/conversations/messages/{$message->id}/helpful", []);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data'
                ]);

        // Verify message was marked as helpful
        $this->assertTrue($message->fresh()->is_helpful);
    }

    /**
     * Test that non-instructors cannot mark messages as helpful
     */
    public function test_non_instructor_cannot_mark_helpful()
    {
        $message = ConversationMessage::create([
            'conversation_id' => $this->conversation->id,
            'user_id' => $this->student->id,
            'message' => 'Test message'
        ]);

        Sanctum::actingAs($this->student);

        $response = $this->postJson("/api/conversations/messages/{$message->id}/helpful", []);

        $response->assertStatus(403);
    }

    /**
     * Test that unenrolled users cannot access conversations
     */
    public function test_unenrolled_user_cannot_access_conversation()
    {
        $unenrolledUser = User::factory()->create(['role' => 'student']);

        Sanctum::actingAs($unenrolledUser);

        $response = $this->getJson("/api/conversations/course/{$this->course->id}");

        $response->assertStatus(403);
    }

    /**
     * Test chatroom badge awarding
     */
    public function test_chatroom_badge_awarded_on_message_count()
    {
        // Create badge with criteria matching the controller's expectations
        $badge = Badge::create([
            'name' => 'Social Butterfly',
            'description' => 'Participate in 10 chatroom discussions',
            'points' => 20,
            'icon' => '💬',
            'criteria' => 'chatroom_posts',
            'category' => 'social',
            'type' => 'participation',
            'is_active' => true
        ]);

        Sanctum::actingAs($this->student);

        // Send 10 messages
        for ($i = 0; $i < 10; $i++) {
            $this->postJson("/api/conversations/{$this->conversation->id}/messages", [
                'message' => "Message {$i}"
            ]);
        }

        // Verify badge was awarded
        $this->student->refresh();
        $this->assertTrue($this->student->badges()->where('badge_id', $badge->id)->exists());
    }
}

