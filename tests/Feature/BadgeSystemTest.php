<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Badge;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Services\PointsAndBadgesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BadgeSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $badgeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'student']);
        $this->badgeService = new PointsAndBadgesService();
    }

    /** @test */
    public function user_receives_signup_badge_on_registration()
    {
        $response = $this->postJson('/api/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student'
        ]);

        $response->assertStatus(201);
        
        $newUser = User::where('email', 'john@example.com')->first();
        $signupBadge = Badge::where('criteria', 'signup:1')->first();
        
        $this->assertTrue($newUser->badges()->where('badge_id', $signupBadge->id)->exists());
    }

    /** @test */
    public function user_receives_profile_completion_badge()
    {
        $this->user->update([
            'profile_photo' => 'path/to/photo.jpg'
        ]);

        $this->badgeService->awardProfileCompletionBadge($this->user);

        $profileBadge = Badge::where('criteria', 'profile_complete:1')->first();
        $this->assertTrue($this->user->badges()->where('badge_id', $profileBadge->id)->exists());
    }

    /** @test */
    public function user_receives_lesson_completion_badges()
    {
        // Create lessons and mark as completed
        for ($i = 0; $i < 10; $i++) {
            LessonCompletion::create([
                'user_id' => $this->user->id,
                'lesson_id' => $i + 1,
                'completed_at' => now()
            ]);
        }

        $this->badgeService->checkAndAwardBadges($this->user);

        // Check if lesson badges were awarded
        $firstLessonBadge = Badge::where('criteria', 'lesson_completion:1')->first();
        $this->assertTrue($this->user->badges()->where('badge_id', $firstLessonBadge->id)->exists());
    }

    /** @test */
    public function user_receives_course_completion_badge()
    {
        $course = Course::factory()->create();
        
        Enrollment::create([
            'user_id' => $this->user->id,
            'course_id' => $course->id,
            'status' => 'completed',
            'progress' => 100
        ]);

        $this->badgeService->checkAndAwardBadges($this->user);

        $courseCompletionBadge = Badge::where('criteria', 'course_completion:1')->first();
        $this->assertTrue($this->user->badges()->where('badge_id', $courseCompletionBadge->id)->exists());
    }

    /** @test */
    public function user_receives_enrollment_badges()
    {
        for ($i = 0; $i < 10; $i++) {
            Course::factory()->create();
            Enrollment::create([
                'user_id' => $this->user->id,
                'course_id' => $i + 1,
                'status' => 'active'
            ]);
        }

        $this->badgeService->awardEnrollmentBadges($this->user);

        $enrollmentBadge = Badge::where('criteria', 'enrollment:10')->first();
        $this->assertTrue($this->user->badges()->where('badge_id', $enrollmentBadge->id)->exists());
    }

    /** @test */
    public function user_receives_chat_activity_badges()
    {
        $chatRoom = ChatRoom::factory()->create();

        for ($i = 0; $i < 10; $i++) {
            ChatMessage::create([
                'chat_room_id' => $chatRoom->id,
                'user_id' => $this->user->id,
                'content' => 'Test message ' . $i,
                'type' => 'text'
            ]);
        }

        $this->badgeService->awardChatActivityBadges($this->user);

        $chatBadge = Badge::where('criteria', 'chatroom_posts:10')->first();
        $this->assertTrue($this->user->badges()->where('badge_id', $chatBadge->id)->exists());
    }

    /** @test */
    public function user_cannot_receive_duplicate_badges()
    {
        $badge = Badge::where('criteria', 'signup:1')->first();
        
        $this->badgeService->awardSignupBadge($this->user);
        $this->badgeService->awardSignupBadge($this->user);

        $badgeCount = $this->user->badges()->where('badge_id', $badge->id)->count();
        $this->assertEquals(1, $badgeCount);
    }

    /** @test */
    public function badges_are_displayed_in_leaderboard()
    {
        $this->badgeService->awardSignupBadge($this->user);

        $response = $this->getJson('/api/leaderboard');

        $response->assertStatus(200);
        $this->assertNotEmpty($response->json('data'));
    }
}

