<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Badge;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created()
    {
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'role' => 'student'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'role' => 'student'
        ]);
    }

    public function test_user_has_wallet()
    {
        $user = User::factory()->create();
        // User model auto-creates wallet, so just verify it exists
        $this->assertTrue($user->wallet()->exists());
        $this->assertNotNull($user->wallet->id);
    }

    public function test_user_can_have_enrollments()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        
        Enrollment::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id
        ]);

        $this->assertTrue($user->enrollments()->exists());
        $this->assertEquals(1, $user->enrollments()->count());
    }

    public function test_user_can_have_badges()
    {
        $user = User::factory()->create();
        $badge = Badge::factory()->create();

        $user->badges()->attach($badge->id, ['earned_at' => now()]);

        $this->assertTrue($user->badges()->exists());
        $this->assertEquals(1, $user->badges()->count());
    }

    public function test_user_has_role()
    {
        $student = User::factory()->create(['role' => 'student']);
        $instructor = User::factory()->create(['role' => 'instructor']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($student->hasRole('student'));
        $this->assertTrue($instructor->hasRole('instructor'));
        $this->assertTrue($admin->hasRole('admin'));
    }

    public function test_user_can_instruct_courses()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['instructor_id' => $instructor->id]);

        $this->assertTrue($instructor->instructedCourses()->exists());
        $this->assertEquals(1, $instructor->instructedCourses()->count());
    }

    public function test_user_email_is_unique()
    {
        User::factory()->create(['email' => 'unique@example.com']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        User::factory()->create(['email' => 'unique@example.com']);
    }

    public function test_user_password_is_hashed()
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->assertNotEquals('password123', $user->password);
    }

    public function test_user_can_have_multiple_enrollments()
    {
        $user = User::factory()->create();
        $courses = Course::factory()->count(3)->create();

        foreach ($courses as $course) {
            Enrollment::factory()->create([
                'user_id' => $user->id,
                'course_id' => $course->id
            ]);
        }

        $this->assertEquals(3, $user->enrollments()->count());
    }

    public function test_user_enrolled_courses_relationship()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        
        Enrollment::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'active'
        ]);

        $enrolledCourses = $user->enrolledCourses;
        $this->assertEquals(1, $enrolledCourses->count());
        $this->assertEquals($course->id, $enrolledCourses->first()->id);
    }

    public function test_user_has_notification_preferences()
    {
        $user = User::factory()->create();

        // User model automatically creates notification preferences
        $this->assertNotNull($user->notificationPreferences);
        $this->assertTrue($user->notificationPreferences->email_enabled);
    }

    public function test_user_can_have_chat_sessions()
    {
        $user = User::factory()->create();
        
        $this->assertEquals(0, $user->chatSessions()->count());
    }
}

