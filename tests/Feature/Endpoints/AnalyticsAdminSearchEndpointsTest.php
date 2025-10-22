<?php

namespace Tests\Feature\Endpoints;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsAdminSearchEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected $instructor;
    protected $admin;
    protected $student;
    protected $instructorToken;
    protected $adminToken;
    protected $studentToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->instructor = User::factory()->create(['role' => 'instructor']);
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->student = User::factory()->create(['role' => 'student']);

        $this->instructorToken = $this->instructor->createToken('api-token')->plainTextToken;
        $this->adminToken = $this->admin->createToken('api-token')->plainTextToken;
        $this->studentToken = $this->student->createToken('api-token')->plainTextToken;
    }

    /**
     * Test learning analytics endpoint
     */
    public function test_learning_analytics()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->getJson('/api/analytics/learning');

        $response->assertStatus(200);
    }

    /**
     * Test course performance analytics endpoint
     */
    public function test_course_performance_analytics()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->getJson('/api/analytics/course-performance');

        $response->assertStatus(200);
    }

    /**
     * Test student progress analytics endpoint
     */
    public function test_student_progress_analytics()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->getJson('/api/analytics/student-progress');

        $response->assertStatus(200);
    }

    /**
     * Test revenue analytics endpoint
     */
    public function test_revenue_analytics()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->getJson('/api/analytics/revenue');

        $response->assertStatus(200);
    }

    /**
     * Test engagement analytics endpoint
     */
    public function test_engagement_analytics()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->getJson('/api/analytics/engagement');

        $response->assertStatus(200);
    }

    /**
     * Test comparative analytics endpoint
     */
    public function test_comparative_analytics()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->postJson('/api/analytics/comparative', [
                            'metric' => 'enrollment',
                            'period' => 'month'
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test export analytics endpoint
     */
    public function test_export_analytics()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->postJson('/api/analytics/export', [
                            'format' => 'csv'
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test real-time analytics endpoint
     */
    public function test_real_time_analytics()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->getJson('/api/analytics/real-time');

        $response->assertStatus(200);
    }

    /**
     * Test predictive analytics endpoint
     */
    public function test_predictive_analytics()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->getJson('/api/analytics/predictive');

        $response->assertStatus(200);
    }

    /**
     * Test admin dashboard endpoint
     */
    public function test_admin_dashboard()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->getJson('/api/admin/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test admin users endpoint
     */
    public function test_admin_users()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->getJson('/api/admin/users');

        $response->assertStatus(200);
    }

    /**
     * Test admin courses endpoint
     */
    public function test_admin_courses()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->getJson('/api/admin/courses');

        $response->assertStatus(200);
    }

    /**
     * Test admin payments endpoint
     */
    public function test_admin_payments()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->getJson('/api/admin/payments');

        $response->assertStatus(200);
    }

    /**
     * Test admin reports endpoint
     */
    public function test_admin_reports()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->getJson('/api/admin/reports');

        $response->assertStatus(200);
    }

    /**
     * Test admin settings endpoint
     */
    public function test_admin_settings()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->getJson('/api/admin/settings');

        $response->assertStatus(200);
    }

    /**
     * Test global search endpoint
     */
    public function test_global_search()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/search/global?q=test');

        $response->assertStatus(200);
    }

    /**
     * Test course search endpoint
     */
    public function test_course_search()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/search/courses?q=test');

        $response->assertStatus(200);
    }

    /**
     * Test user search endpoint
     */
    public function test_user_search()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/search/users?q=test');

        $response->assertStatus(200);
    }

    /**
     * Test content search endpoint
     */
    public function test_content_search()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/search/content?q=test');

        $response->assertStatus(200);
    }

    /**
     * Test search suggestions endpoint
     */
    public function test_search_suggestions()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/search/suggestions?q=test');

        $response->assertStatus(200);
    }

    /**
     * Test search filters endpoint
     */
    public function test_search_filters()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/search/filters');

        $response->assertStatus(200);
    }
}

