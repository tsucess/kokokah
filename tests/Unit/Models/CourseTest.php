<?php

namespace Tests\Unit\Models;

use App\Models\Course;
use App\Models\User;
use App\Models\Category;
use App\Models\Term;
use App\Models\Level;
use App\Models\Lesson;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    protected $instructor;
    protected $category;
    protected $term;
    protected $level;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->instructor = User::factory()->create(['role' => 'instructor']);
        $this->category = Category::factory()->create();
        $this->term = Term::factory()->create();
        $this->level = Level::factory()->create();
    }

    public function test_course_can_be_created()
    {
        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test Description',
            'category_id' => $this->category->id,
            'instructor_id' => $this->instructor->id,
            'term_id' => $this->term->id,
            'level_id' => $this->level->id,
            'price' => 100.00,
            'status' => 'published',
            'duration_hours' => 10,
            'difficulty' => 'beginner'
        ]);

        $this->assertDatabaseHas('courses', [
            'title' => 'Test Course',
            'status' => 'published'
        ]);
    }

    public function test_course_belongs_to_instructor()
    {
        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test',
            'category_id' => $this->category->id,
            'instructor_id' => $this->instructor->id,
            'term_id' => $this->term->id,
            'level_id' => $this->level->id,
            'price' => 100.00,
            'status' => 'published'
        ]);

        $this->assertEquals($this->instructor->id, $course->instructor->id);
    }

    public function test_course_has_lessons()
    {
        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test',
            'category_id' => $this->category->id,
            'instructor_id' => $this->instructor->id,
            'term_id' => $this->term->id,
            'level_id' => $this->level->id,
            'price' => 100.00,
            'status' => 'published'
        ]);

        Lesson::factory()->count(3)->create(['course_id' => $course->id]);

        $this->assertEquals(3, $course->lessons()->count());
    }

    public function test_course_has_enrollments()
    {
        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test',
            'category_id' => $this->category->id,
            'instructor_id' => $this->instructor->id,
            'term_id' => $this->term->id,
            'level_id' => $this->level->id,
            'price' => 100.00,
            'status' => 'published'
        ]);

        $students = User::factory()->count(2)->create(['role' => 'student']);
        
        foreach ($students as $student) {
            Enrollment::factory()->create([
                'course_id' => $course->id,
                'user_id' => $student->id
            ]);
        }

        $this->assertEquals(2, $course->enrollments()->count());
    }

    public function test_course_has_students()
    {
        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test',
            'category_id' => $this->category->id,
            'instructor_id' => $this->instructor->id,
            'term_id' => $this->term->id,
            'level_id' => $this->level->id,
            'price' => 100.00,
            'status' => 'published'
        ]);

        $students = User::factory()->count(2)->create(['role' => 'student']);
        
        foreach ($students as $student) {
            Enrollment::factory()->create([
                'course_id' => $course->id,
                'user_id' => $student->id
            ]);
        }

        $this->assertEquals(2, $course->students()->count());
    }

    public function test_course_status_can_be_published()
    {
        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test',
            'category_id' => $this->category->id,
            'instructor_id' => $this->instructor->id,
            'term_id' => $this->term->id,
            'level_id' => $this->level->id,
            'price' => 100.00,
            'status' => 'published'
        ]);

        $this->assertEquals('published', $course->status);
    }

    public function test_course_can_be_draft()
    {
        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test',
            'category_id' => $this->category->id,
            'instructor_id' => $this->instructor->id,
            'term_id' => $this->term->id,
            'level_id' => $this->level->id,
            'price' => 100.00,
            'status' => 'draft'
        ]);

        $this->assertEquals('draft', $course->status);
    }

    public function test_course_price_is_numeric()
    {
        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test',
            'category_id' => $this->category->id,
            'instructor_id' => $this->instructor->id,
            'term_id' => $this->term->id,
            'level_id' => $this->level->id,
            'price' => 99.99,
            'status' => 'published'
        ]);

        // Decimal fields are cast to strings in Laravel
        $this->assertIsString($course->price);
        $this->assertEquals('99.99', $course->price);
    }

    public function test_course_belongs_to_category()
    {
        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test',
            'category_id' => $this->category->id,
            'instructor_id' => $this->instructor->id,
            'term_id' => $this->term->id,
            'level_id' => $this->level->id,
            'price' => 100.00,
            'status' => 'published'
        ]);

        $this->assertEquals($this->category->id, $course->category->id);
    }
}

