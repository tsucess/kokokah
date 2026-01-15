<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use App\Models\Level;
use App\Models\CourseCategory;
use App\Models\Enrollment;
use App\Models\SubscriptionPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollPageEnrolledCoursesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $instructor;
    protected $level;
    protected $courseCategory;
    protected $freePlan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'student']);
        $this->instructor = User::factory()->create(['role' => 'instructor']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->courseCategory = CourseCategory::create([
            'user_id' => $admin->id,
            'title' => 'Test Category',
            'description' => 'Test category'
        ]);

        $this->level = Level::factory()->create();

        $this->freePlan = SubscriptionPlan::create([
            'title' => 'Free Plan',
            'description' => 'Free courses',
            'price' => 0,
            'duration' => 1,
            'duration_type' => 'free',
            'features' => ['Free access'],
            'is_active' => true
        ]);
    }

    public function test_enrolled_courses_are_checked_and_disabled()
    {
        // Create a course and enroll user
        $course = Course::create([
            'title' => 'Enrolled Course',
            'description' => 'Test course',
            'course_category_id' => $this->courseCategory->id,
            'instructor_id' => $this->instructor->id,
            'level_id' => $this->level->id,
            'status' => 'published'
        ]);

        Enrollment::create([
            'user_id' => $this->user->id,
            'course_id' => $course->id,
            'status' => 'active'
        ]);

        $this->actingAs($this->user);
        $response = $this->getJson('/api/courses/my-courses');

        // Debug response
        $responseData = $response->json();
        if (!$responseData['success']) {
            $this->fail('API returned error: ' . json_encode($responseData));
        }

        $this->assertTrue($response->json('success'));
        $courses = $response->json('data');
        $this->assertIsArray($courses['courses']);
        $this->assertCount(1, $courses['courses']);
        $this->assertEquals('enrolled', $courses['courses'][0]['access_type']);
    }

    public function test_free_courses_are_checked_and_disabled()
    {
        // Create a free course
        $course = Course::create([
            'title' => 'Free Course',
            'description' => 'Test free course',
            'course_category_id' => $this->courseCategory->id,
            'instructor_id' => $this->instructor->id,
            'level_id' => $this->level->id,
            'status' => 'published',
            'free_subscription' => true
        ]);

        // Attach to free plan
        $this->freePlan->courses()->sync([$course->id]);

        $this->actingAs($this->user);
        $response = $this->getJson('/api/courses/my-courses');

        $this->assertTrue($response->json('success'));
        $courses = $response->json('data');
        $this->assertIsArray($courses['courses']);
        $this->assertCount(1, $courses['courses']);
        $this->assertEquals('free_subscription', $courses['courses'][0]['access_type']);
    }

    public function test_available_courses_are_unchecked_and_enabled()
    {
        // Create an available course (not enrolled, not free)
        $course = Course::create([
            'title' => 'Available Course',
            'description' => 'Test available course',
            'course_category_id' => $this->courseCategory->id,
            'instructor_id' => $this->instructor->id,
            'level_id' => $this->level->id,
            'status' => 'published'
        ]);

        $this->actingAs($this->user);
        $response = $this->getJson('/api/courses/my-courses');

        // Available courses should not appear in my-courses
        $this->assertTrue($response->json('success'));
        $courses = $response->json('data');
        $this->assertIsArray($courses['courses']);
        $this->assertCount(0, $courses['courses']);
    }
}

