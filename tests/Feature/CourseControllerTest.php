<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use App\Models\CurriculumCategory;
use App\Models\CourseCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CourseControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a category for testing
        $this->category = CurriculumCategory::factory()->create();
    }

    public function test_guest_can_view_published_courses()
    {
        // Create published courses
        Course::factory()->count(3)->create([
            'status' => 'published',
            'category_id' => $this->category->id
        ]);

        $response = $this->getJson('/api/courses');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'title',
                                'description',
                                'price',
                                'status',
                                'difficulty'
                            ]
                        ]
                    ]
                ]);
    }

    public function test_instructor_can_create_course()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        Sanctum::actingAs($instructor);

        $courseData = [
            'title' => 'Test Course',
            'description' => 'This is a test course description that is long enough to pass validation.',
            'category_id' => $this->category->id,
            'price' => 99.99,
            'difficulty' => 'beginner',
            'duration_hours' => 10,
            'max_students' => 100
        ];

        $response = $this->postJson('/api/courses', $courseData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'title',
                        'description',
                        'instructor_id'
                    ]
                ]);

        $this->assertDatabaseHas('courses', [
            'title' => 'Test Course',
            'instructor_id' => $instructor->id
        ]);
    }

    public function test_student_cannot_create_course()
    {
        $student = User::factory()->create(['role' => 'student']);
        Sanctum::actingAs($student);

        $courseData = [
            'title' => 'Test Course',
            'description' => 'This is a test course description.',
            'category_id' => $this->category->id,
            'price' => 99.99,
            'difficulty' => 'beginner'
        ];

        $response = $this->postJson('/api/courses', $courseData);

        $response->assertStatus(403);
    }

    public function test_course_creation_validation()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        Sanctum::actingAs($instructor);

        // Test missing required fields
        $response = $this->postJson('/api/courses', [
            'title' => '',
            'description' => '',
            'category_id' => '',
            'price' => '',
            'difficulty' => ''
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['title', 'category_id', 'price', 'difficulty']);

        // Test invalid data
        $response = $this->postJson('/api/courses', [
            'title' => '', // Empty title
            'description' => 'Short', // Too short description
            'category_id' => 999, // Non-existent category
            'price' => -10, // Negative price
            'difficulty' => 'invalid' // Invalid difficulty
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'title', 'category_id', 'price', 'difficulty'
                ]);
    }

    public function test_instructor_can_update_own_course()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create([
            'instructor_id' => $instructor->id,
            'category_id' => $this->category->id
        ]);

        Sanctum::actingAs($instructor);

        $updateData = [
            'title' => 'Updated Course Title',
            'description' => 'This is an updated course description that is long enough.',
            'price' => 149.99
        ];

        $response = $this->putJson("/api/courses/{$course->id}", $updateData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Updated Course Title',
            'price' => 149.99
        ]);
    }

    public function test_instructor_cannot_update_other_instructor_course()
    {
        $instructor1 = User::factory()->create(['role' => 'instructor']);
        $instructor2 = User::factory()->create(['role' => 'instructor']);

        $course = Course::factory()->create([
            'instructor_id' => $instructor1->id,
            'category_id' => $this->category->id
        ]);

        Sanctum::actingAs($instructor2);

        $response = $this->putJson("/api/courses/{$course->id}", [
            'title' => 'Hacked Course'
        ]);

        $response->assertStatus(403);
    }

    public function test_course_search_functionality()
    {
        Course::factory()->create([
            'title' => 'Laravel Development',
            'status' => 'published',
            'category_id' => $this->category->id
        ]);

        Course::factory()->create([
            'title' => 'React Frontend',
            'status' => 'published',
            'category_id' => $this->category->id
        ]);

        $response = $this->getJson('/api/courses/search?q=Laravel');

        $response->assertStatus(200)
                ->assertJsonFragment(['title' => 'Laravel Development'])
                ->assertJsonMissing(['title' => 'React Frontend']);
    }
}
