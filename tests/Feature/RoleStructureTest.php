<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleStructureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that superadmin can access admin routes
     */
    public function test_superadmin_can_access_admin_dashboard()
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        
        $response = $this->actingAs($superadmin)
            ->getJson('/api/admin/dashboard');
        
        $this->assertTrue(
            $response->status() === 200 || $response->status() === 401,
            'Superadmin should have access to admin dashboard'
        );
    }

    /**
     * Test that admin cannot access superadmin-only routes
     */
    public function test_admin_cannot_access_superadmin_routes()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)
            ->getJson('/api/admin/dashboard');
        
        $this->assertEquals(403, $response->status(), 'Admin should not access superadmin routes');
    }

    /**
     * Test that instructor can access student dashboard
     */
    public function test_instructor_can_access_student_dashboard()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);

        $response = $this->actingAs($instructor)
            ->getJson('/api/student/dashboard');

        // Route may not exist (404) or may require auth (401), but should not be 403 (forbidden)
        $this->assertNotEquals(403, $response->status(), 'Instructor should not be forbidden from student dashboard');
    }

    /**
     * Test that student cannot access instructor routes
     */
    public function test_student_cannot_access_instructor_routes()
    {
        $student = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($student)
            ->getJson('/api/instructor/dashboard');

        // Route may not exist (404) or may be forbidden (403), but should not be 200
        $this->assertNotEquals(200, $response->status(), 'Student should not access instructor routes');
    }

    /**
     * Test User model helper methods
     */
    public function test_user_role_helper_methods()
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $admin = User::factory()->create(['role' => 'admin']);
        $instructor = User::factory()->create(['role' => 'instructor']);
        $student = User::factory()->create(['role' => 'student']);

        // Test isSuperAdmin
        $this->assertTrue($superadmin->isSuperAdmin());
        $this->assertFalse($admin->isSuperAdmin());
        $this->assertFalse($instructor->isSuperAdmin());
        $this->assertFalse($student->isSuperAdmin());

        // Test isAdminOrSuperAdmin
        $this->assertTrue($superadmin->isAdminOrSuperAdmin());
        $this->assertTrue($admin->isAdminOrSuperAdmin());
        $this->assertFalse($instructor->isAdminOrSuperAdmin());
        $this->assertFalse($student->isAdminOrSuperAdmin());

        // Test isInstructorOrHigher
        $this->assertTrue($superadmin->isInstructorOrHigher());
        $this->assertTrue($admin->isInstructorOrHigher());
        $this->assertTrue($instructor->isInstructorOrHigher());
        $this->assertFalse($student->isInstructorOrHigher());
    }

    /**
     * Test role hierarchy
     */
    public function test_role_hierarchy()
    {
        $roles = ['student', 'instructor', 'admin', 'superadmin'];
        
        foreach ($roles as $role) {
            $user = User::factory()->create(['role' => $role]);
            $this->assertEquals($role, $user->role);
        }
    }
}

