<?php

namespace Tests\Feature\Endpoints;

use App\Models\User;
use App\Models\Badge;
use App\Models\Certificate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CertificateBadgeProgressEndpointsTest extends TestCase
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
     * Test get certificates endpoint
     */
    public function test_get_certificates()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/certificates');

        $response->assertStatus(200);
    }

    /**
     * Test get certificate templates endpoint
     */
    public function test_get_certificate_templates()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/certificates/templates');

        $response->assertStatus(200);
    }

    /**
     * Test generate certificate endpoint
     */
    public function test_generate_certificate()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->postJson('/api/certificates/generate', [
                            'course_id' => 1,
                            'template_id' => 1
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test bulk generate certificates endpoint
     */
    public function test_bulk_generate_certificates()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->postJson('/api/certificates/bulk-generate', [
                            'course_id' => 1,
                            'user_ids' => [1, 2, 3]
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test get single certificate endpoint
     */
    public function test_get_single_certificate()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/certificates/1');

        $response->assertStatus(404);
    }

    /**
     * Test download certificate endpoint
     */
    public function test_download_certificate()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/certificates/1/download');

        $response->assertStatus(404);
    }

    /**
     * Test revoke certificate endpoint
     */
    public function test_revoke_certificate()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->postJson('/api/certificates/1/revoke');

        $response->assertStatus(404);
    }

    /**
     * Test verify certificate endpoint (public)
     */
    public function test_verify_certificate()
    {
        $response = $this->getJson('/api/certificates/verify/TEST-CERT-123');

        $response->assertStatus(404);
    }

    /**
     * Test get badges endpoint
     */
    public function test_get_badges()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/badges');

        $response->assertStatus(200);
    }

    /**
     * Test get badge analytics endpoint
     */
    public function test_get_badge_analytics()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->getJson('/api/badges/analytics');

        $response->assertStatus(200);
    }

    /**
     * Test get badge leaderboard endpoint
     */
    public function test_get_badge_leaderboard()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/badges/leaderboard');

        $response->assertStatus(200);
    }

    /**
     * Test create badge endpoint
     */
    public function test_create_badge()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->postJson('/api/badges', [
                            'name' => 'New Badge',
                            'icon' => 'badge-icon.png',
                            'criteria' => 'Complete 5 courses'
                        ]);

        $response->assertStatus(201);
    }

    /**
     * Test award badge endpoint
     */
    public function test_award_badge()
    {
        $badge = Badge::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->postJson('/api/badges/award', [
                            'user_id' => $this->student->id,
                            'badge_id' => $badge->id
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test get course progress endpoint
     */
    public function test_get_course_progress()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/progress/courses');

        $response->assertStatus(200);
    }

    /**
     * Test get lesson progress endpoint
     */
    public function test_get_lesson_progress()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/progress/lessons');

        $response->assertStatus(200);
    }

    /**
     * Test get overall progress endpoint
     */
    public function test_get_overall_progress()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/progress/overall');

        $response->assertStatus(200);
    }

    /**
     * Test update progress endpoint
     */
    public function test_update_progress()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->postJson('/api/progress/update', [
                            'course_id' => 1,
                            'progress' => 50
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test get available certificates endpoint
     */
    public function test_get_available_certificates()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/progress/certificates');

        $response->assertStatus(200);
    }

    /**
     * Test get achievement progress endpoint
     */
    public function test_get_achievement_progress()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/progress/achievements');

        $response->assertStatus(200);
    }

    /**
     * Test get streak progress endpoint
     */
    public function test_get_streak_progress()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson('/api/progress/streaks');

        $response->assertStatus(200);
    }
}

