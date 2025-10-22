<?php

namespace Tests\Unit\Models;

use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\Term;
use App\Models\Level;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    protected $student;
    protected $course;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->student = User::factory()->create(['role' => 'student']);
        $instructor = User::factory()->create(['role' => 'instructor']);
        $category = Category::factory()->create();
        $term = Term::factory()->create();
        $level = Level::factory()->create();
        
        $this->course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'term_id' => $term->id,
            'level_id' => $level->id,
            'price' => 100.00,
            'status' => 'published'
        ]);
    }

    public function test_enrollment_can_be_created()
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active'
        ]);

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active'
        ]);
    }

    public function test_enrollment_belongs_to_user()
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active'
        ]);

        $this->assertEquals($this->student->id, $enrollment->user->id);
    }

    public function test_enrollment_belongs_to_course()
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active'
        ]);

        $this->assertEquals($this->course->id, $enrollment->course->id);
    }

    public function test_enrollment_status_can_be_active()
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active'
        ]);

        $this->assertEquals('active', $enrollment->status);
    }

    public function test_enrollment_status_can_be_completed()
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'completed'
        ]);

        $this->assertEquals('completed', $enrollment->status);
    }

    public function test_enrollment_status_can_be_dropped()
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'dropped'
        ]);

        $this->assertEquals('dropped', $enrollment->status);
    }

    public function test_enrollment_has_progress_tracking()
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
            'progress' => 50
        ]);

        $this->assertEquals(50, $enrollment->progress);
    }

    public function test_enrollment_tracks_enrollment_date()
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active'
        ]);

        $this->assertNotNull($enrollment->created_at);
    }

    public function test_enrollment_can_track_completion_date()
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'completed',
            'completed_at' => now()
        ]);

        $this->assertNotNull($enrollment->completed_at);
    }

    public function test_user_cannot_enroll_twice_in_same_course()
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active'
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active'
        ]);
    }

    public function test_enrollment_progress_percentage_is_numeric()
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
            'progress' => 75
        ]);

        $this->assertIsInt($enrollment->progress);
    }
}

