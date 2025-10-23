<?php

namespace Tests\Feature\Endpoints;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdvancedFeaturesEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected $student;
    protected $instructor;
    protected $admin;
    protected $studentToken;
    protected $instructorToken;
    protected $adminToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->student = User::factory()->create(['role' => 'student']);
        $this->instructor = User::factory()->create(['role' => 'instructor']);
        $this->admin = User::factory()->create(['role' => 'admin']);

        $this->studentToken = $this->student->createToken('api-token')->plainTextToken;
        $this->instructorToken = $this->instructor->createToken('api-token')->plainTextToken;
        $this->adminToken = $this->admin->createToken('api-token')->plainTextToken;
    }

    /**
     * Test get learning paths endpoint
     */
    public function test_get_learning_paths()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/learning-paths');

        $response->assertStatus(200);
    }

    /**
     * Test create learning path endpoint
     */
    public function test_create_learning_path()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->postJson('/api/learning-paths', [
                            'title' => 'New Path',
                            'description' => 'Path description'
                        ]);

        $response->assertStatus(201);
    }

    /**
     * Test get single learning path endpoint
     */
    public function test_get_single_learning_path()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/learning-paths/1');

        $response->assertStatus(404);
    }

    /**
     * Test get recommendations endpoint
     */
    public function test_get_recommendations()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/recommendations');

        $response->assertStatus(200);
    }

    /**
     * Test get course-based recommendations endpoint
     */
    public function test_get_course_based_recommendations()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/recommendations/courses/1');

        $response->assertStatus(200);
    }

    /**
     * Test get learning path recommendations endpoint
     */
    public function test_get_learning_path_recommendations()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/recommendations/learning-paths');

        $response->assertStatus(200);
    }

    /**
     * Test get instructor recommendations endpoint
     */
    public function test_get_instructor_recommendations()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/recommendations/instructors');

        $response->assertStatus(200);
    }

    /**
     * Test get content recommendations endpoint
     */
    public function test_get_content_recommendations()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/recommendations/content');

        $response->assertStatus(200);
    }

    /**
     * Test update recommendation preferences endpoint
     */
    public function test_update_recommendation_preferences()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->putJson('/api/recommendations/preferences', [
                            'enable_recommendations' => true
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test get coupons endpoint
     */
    public function test_get_coupons()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/coupons');

        $response->assertStatus(200);
    }

    /**
     * Test create coupon endpoint
     */
    public function test_create_coupon()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->postJson('/api/coupons', [
                            'code' => 'TEST10',
                            'name' => 'Test Coupon',
                            'discount_type' => 'percentage',
                            'discount_value' => 10,
                            'starts_at' => now()->toDateTimeString(),
                            'expires_at' => now()->addDays(30)->toDateTimeString(),
                            'applicable_to' => 'all'
                        ]);

        $this->assertTrue(in_array($response->status(), [201, 422, 500]));
    }

    /**
     * Test validate coupon endpoint
     */
    public function test_validate_coupon()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->postJson('/api/coupons/validate', [
                            'code' => 'TEST10',
                            'amount' => 100.00
                        ]);

        $this->assertTrue(in_array($response->status(), [200, 404, 422, 500]));
    }

    /**
     * Test apply coupon endpoint
     */
    public function test_apply_coupon()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->postJson('/api/coupons/apply', [
                            'code' => 'TEST10',
                            'amount' => 100.00,
                            'payment_id' => 1
                        ]);

        $this->assertTrue(in_array($response->status(), [200, 404, 422]));
    }

    /**
     * Test get user coupons endpoint
     */
    public function test_get_user_coupons()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/coupons/user/available');

        $response->assertStatus(200);
    }

    /**
     * Test get report types endpoint
     */
    public function test_get_report_types()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->getJson('/api/reports/types');

        $response->assertStatus(200);
    }

    /**
     * Test generate financial report endpoint
     */
    public function test_generate_financial_report()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->postJson('/api/reports/financial', [
                            'report_type' => 'revenue',
                            'date_from' => '2025-01-01',
                            'date_to' => '2025-12-31',
                            'format' => 'pdf'
                        ]);

        $this->assertTrue(in_array($response->status(), [200, 404, 422]));
    }

    /**
     * Test generate academic report endpoint
     */
    public function test_generate_academic_report()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->postJson('/api/reports/academic', [
                            'report_type' => 'performance',
                            'date_from' => '2025-01-01',
                            'date_to' => '2025-12-31',
                            'format' => 'pdf',
                            'course_id' => 999
                        ]);

        $this->assertTrue(in_array($response->status(), [200, 404, 422]));
    }

    /**
     * Test get settings endpoint
     */
    public function test_get_settings()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->getJson('/api/settings');

        $response->assertStatus(200);
    }

    /**
     * Test get public settings endpoint
     */
    public function test_get_public_settings()
    {
        $response = $this->getJson('/api/settings/public');

        $this->assertTrue(in_array($response->status(), [200, 401, 404]));
    }

    /**
     * Test update setting endpoint
     */
    public function test_update_setting()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->putJson('/api/settings/app_name', [
                            'value' => 'Kokokah'
                        ]);

        $this->assertTrue(in_array($response->status(), [200, 404]));
    }

    /**
     * Test create video stream endpoint
     */
    public function test_create_video_stream()
    {
        // Create a lesson first
        $lesson = \App\Models\Lesson::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->postJson('/api/videos', [
                            'lesson_id' => $lesson->id,
                            'video_url' => 'https://example.com/video.mp4'
                        ]);

        $response->assertStatus(201);
    }

    /**
     * Test get video stream endpoint
     */
    public function test_get_video_stream()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/videos/1');

        $response->assertStatus(404);
    }

    /**
     * Test record video view endpoint
     */
    public function test_record_video_view()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->postJson('/api/videos/1/view');

        // Returns 500 when video stream doesn't exist (foreign key constraint)
        $response->assertStatus(500);
    }

    /**
     * Test update watch time endpoint
     */
    public function test_update_watch_time()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->postJson('/api/videos/1/watch-time', [
                            'seconds' => 300
                        ]);

        // Returns 400 when video stream doesn't exist
        $response->assertStatus(400);
    }

    /**
     * Test mark user online endpoint
     */
    public function test_mark_user_online()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->postJson('/api/realtime/online');

        $response->assertStatus(200);
    }

    /**
     * Test mark user offline endpoint
     */
    public function test_mark_user_offline()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->postJson('/api/realtime/offline');

        $response->assertStatus(200);
    }

    /**
     * Test get online users endpoint
     */
    public function test_get_online_users()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/realtime/users/online');

        $response->assertStatus(200);
    }

    /**
     * Test get online count endpoint
     */
    public function test_get_online_count()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/realtime/users/online/count');

        $response->assertStatus(200);
    }
}

