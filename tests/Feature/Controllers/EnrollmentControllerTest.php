<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Category;
use App\Models\Term;
use App\Models\Level;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $student;
    protected $instructor;
    protected $course;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->student = User::factory()->create(['role' => 'student']);
        $this->instructor = User::factory()->create(['role' => 'instructor']);
        $this->token = $this->student->createToken('api-token')->plainTextToken;

        // Give student wallet balance for enrollment
        $this->student->wallet->update(['balance' => 10000.00]);

        $category = Category::factory()->create();
        $term = Term::factory()->create();
        $level = Level::factory()->create();

        $this->course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test',
            'category_id' => $category->id,
            'instructor_id' => $this->instructor->id,
            'term_id' => $term->id,
            'level_id' => $level->id,
            'price' => 100.00,
            'status' => 'published'
        ]);
    }

    public function test_student_can_enroll_in_course()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->postJson('/api/enrollments', [
                            'course_id' => $this->course->id
                        ]);

        $response->assertStatus(201)
                ->assertJsonStructure(['success', 'data']);

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $this->student->id,
            'course_id' => $this->course->id
        ]);
    }

    public function test_student_cannot_enroll_twice()
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active'
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->postJson('/api/enrollments', [
                            'course_id' => $this->course->id
                        ]);

        // Controller returns 400 for "Already enrolled"
        $response->assertStatus(400);
    }

    public function test_student_can_view_enrollments()
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active'
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->getJson('/api/enrollments');

        $response->assertStatus(200)
                ->assertJsonStructure(['success', 'data']);
    }

    public function test_student_can_view_single_enrollment()
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active'
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->getJson("/api/enrollments/{$enrollment->id}");

        $response->assertStatus(200)
                ->assertJsonStructure(['success', 'data']);
    }

    public function test_student_can_view_enrollment_progress()
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
            'progress_percentage' => 50
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->getJson("/api/enrollments/{$enrollment->id}/progress");

        $response->assertStatus(200)
                ->assertJsonStructure(['success', 'data']);
    }

    public function test_student_can_unenroll_from_course()
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
            'enrolled_at' => now()
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->deleteJson("/api/enrollments/{$enrollment->id}");

        $response->assertStatus(200);

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'status' => 'cancelled'
        ]);
    }

    public function test_student_cannot_view_other_students_enrollment()
    {
        $otherStudent = User::factory()->create(['role' => 'student']);
        $enrollment = Enrollment::create([
            'user_id' => $otherStudent->id,
            'course_id' => $this->course->id,
            'status' => 'active'
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->getJson("/api/enrollments/{$enrollment->id}");

        // The show method returns 404 when enrollment not found for user
        $response->assertStatus(404);
    }

    public function test_unauthenticated_user_cannot_enroll()
    {
        $response = $this->postJson('/api/enrollments', [
            'course_id' => $this->course->id
        ]);

        $response->assertStatus(401);
    }

    public function test_student_can_view_my_courses()
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active'
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->getJson('/api/courses/my-courses');

        $response->assertStatus(200)
                ->assertJsonStructure(['success', 'data']);
    }

    public function test_enrollment_status_can_be_updated()
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active'
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->putJson("/api/enrollments/{$enrollment->id}", [
                            'status' => 'paused'
                        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'status' => 'paused'
        ]);
    }
}

