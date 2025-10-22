<?php

namespace Tests\Feature\Endpoints;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDashboardEndpointsTest extends TestCase
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
     * Test get user profile endpoint
     */
    public function test_get_user_profile()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/users/profile');

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'data']);
    }

    /**
     * Test update user profile endpoint
     */
    public function test_update_user_profile()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->putJson('/api/users/profile', [
                            'first_name' => 'Updated',
                            'last_name' => 'Name'
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test get user dashboard endpoint
     */
    public function test_get_user_dashboard()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/users/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test get user achievements endpoint
     */
    public function test_get_user_achievements()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/users/achievements');

        $response->assertStatus(200);
    }

    /**
     * Test get learning stats endpoint
     */
    public function test_get_learning_stats()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/users/learning-stats');

        $response->assertStatus(200);
    }

    /**
     * Test update user preferences endpoint
     */
    public function test_update_user_preferences()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->putJson('/api/users/preferences', [
                            'language' => 'en',
                            'theme' => 'dark'
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test get user notifications endpoint
     */
    public function test_get_user_notifications()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/users/notifications');

        $response->assertStatus(200);
    }

    /**
     * Test mark notifications read endpoint
     */
    public function test_mark_notifications_read()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->postJson('/api/users/notifications/read');

        $response->assertStatus(200);
    }

    /**
     * Test change password endpoint
     */
    public function test_change_password()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->postJson('/api/users/change-password', [
                            'current_password' => 'password',
                            'new_password' => 'newpassword123',
                            'new_password_confirmation' => 'newpassword123'
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test student dashboard endpoint
     */
    public function test_student_dashboard()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/dashboard/student');

        $response->assertStatus(200);
    }

    /**
     * Test instructor dashboard endpoint
     */
    public function test_instructor_dashboard()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->getJson('/api/dashboard/instructor');

        $response->assertStatus(200);
    }

    /**
     * Test admin dashboard endpoint
     */
    public function test_admin_dashboard()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->getJson('/api/dashboard/admin');

        $response->assertStatus(200);
    }

    /**
     * Test dashboard analytics endpoint
     */
    public function test_dashboard_analytics()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/dashboard/analytics');

        $response->assertStatus(200);
    }

    /**
     * Test get user badges endpoint
     */
    public function test_get_user_badges()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson("/api/users/{$this->student->id}/badges");

        $response->assertStatus(200);
    }

    /**
     * Test get my badges endpoint
     */
    public function test_get_my_badges()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/my-badges');

        $response->assertStatus(200);
    }
}

