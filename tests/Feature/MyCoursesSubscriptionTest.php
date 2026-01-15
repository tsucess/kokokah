<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use App\Models\Level;
use App\Models\CourseCategory;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Carbon\Carbon;

class MyCoursesSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $instructor;
    protected $courseCategory;
    protected $level;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'student']);
        $this->instructor = User::factory()->create(['role' => 'instructor']);
        $admin = User::factory()->create(['role' => 'admin']);

        // Create course category with user_id
        $this->courseCategory = CourseCategory::create([
            'user_id' => $admin->id,
            'title' => 'Test Category',
            'description' => 'Test category for courses'
        ]);

        $this->level = Level::factory()->create();
    }

    protected function createFreePlan()
    {
        return SubscriptionPlan::create([
            'title' => 'Free Plan ' . uniqid(),
            'description' => 'Free access to selected courses',
            'price' => 0,
            'duration' => 1,
            'duration_type' => 'free',
            'features' => ['Access to free courses'],
            'is_active' => true
        ]);
    }

    public function test_new_user_sees_free_courses()
    {
        // Create free plan
        $freePlan = $this->createFreePlan();

        // Create free courses directly with unique titles
        $freeCourse1 = Course::create([
            'title' => 'Free Course ' . uniqid(),
            'description' => 'Test free course 1',
            'course_category_id' => $this->courseCategory->id,
            'instructor_id' => $this->instructor->id,
            'level_id' => $this->level->id,
            'status' => 'published',
            'free_subscription' => true
        ]);

        $freeCourse2 = Course::create([
            'title' => 'Free Course ' . uniqid(),
            'description' => 'Test free course 2',
            'course_category_id' => $this->courseCategory->id,
            'instructor_id' => $this->instructor->id,
            'level_id' => $this->level->id,
            'status' => 'published',
            'free_subscription' => true
        ]);

        // Attach courses to free plan (use sync to avoid duplicates)
        $freePlan->courses()->sync([$freeCourse1->id, $freeCourse2->id]);

        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/courses/my-courses');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'courses' => [
                            '*' => [
                                'course_id',
                                'course',
                                'access_type'
                            ]
                        ],
                        'total'
                    ]
                ]);

        $courses = $response->json('data.courses');
        $this->assertCount(2, $courses);
        $this->assertEquals('free_subscription', $courses[0]['access_type']);
    }

    public function test_enrolled_user_sees_enrolled_courses()
    {
        // Create and enroll in a course
        $enrolledCourse = Course::create([
            'title' => 'Enrolled Course',
            'description' => 'Test enrolled course',
            'course_category_id' => $this->courseCategory->id,
            'instructor_id' => $this->instructor->id,
            'level_id' => $this->level->id,
            'status' => 'published'
        ]);

        Enrollment::create([
            'user_id' => $this->user->id,
            'course_id' => $enrolledCourse->id,
            'status' => 'active',
            'enrolled_at' => Carbon::now()
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/courses/my-courses');

        $response->assertStatus(200);
        $courses = $response->json('data.courses');
        $this->assertCount(1, $courses);
        $this->assertEquals('enrolled', $courses[0]['access_type']);
    }

    public function test_user_with_subscription_sees_subscription_courses()
    {
        // Create subscription plan with courses
        $paidPlan = SubscriptionPlan::create([
            'title' => 'Premium Plan',
            'price' => 99.99,
            'duration' => 1,
            'duration_type' => 'monthly',
            'features' => ['Premium courses'],
            'is_active' => true
        ]);

        $premiumCourse = Course::create([
            'title' => 'Premium Course',
            'description' => 'Test premium course',
            'course_category_id' => $this->courseCategory->id,
            'instructor_id' => $this->instructor->id,
            'level_id' => $this->level->id,
            'status' => 'published'
        ]);

        $paidPlan->courses()->attach($premiumCourse->id);

        // Subscribe user
        UserSubscription::create([
            'user_id' => $this->user->id,
            'subscription_plan_id' => $paidPlan->id,
            'started_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonth(),
            'status' => 'active',
            'amount_paid' => 99.99
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/courses/my-courses');

        $response->assertStatus(200);
        $courses = $response->json('data.courses');
        $this->assertCount(1, $courses);
        $this->assertEquals('subscription', $courses[0]['access_type']);
    }

    public function test_no_duplicate_courses_in_results()
    {
        // Create a course that user is enrolled in
        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test course for duplicates',
            'course_category_id' => $this->courseCategory->id,
            'instructor_id' => $this->instructor->id,
            'level_id' => $this->level->id,
            'status' => 'published'
        ]);

        // Enroll user
        Enrollment::create([
            'user_id' => $this->user->id,
            'course_id' => $course->id,
            'status' => 'active',
            'enrolled_at' => Carbon::now()
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/courses/my-courses');

        $response->assertStatus(200);
        $courses = $response->json('data.courses');

        // Should only appear once
        $courseIds = array_column($courses, 'course_id');
        $this->assertEquals(1, count(array_filter($courseIds, fn($id) => $id === $course->id)));
    }
}

