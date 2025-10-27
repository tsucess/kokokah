<?php

namespace Tests\Feature\Endpoints;

use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\Term;
use App\Models\Level;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected $instructor;
    protected $student;
    protected $course;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->instructor = User::factory()->create(['role' => 'instructor']);
        $this->student = User::factory()->create(['role' => 'student']);
        $this->token = $this->student->createToken('api-token')->plainTextToken;

        $category = Category::factory()->create();
        $term = Term::factory()->create();
        $level = Level::factory()->create();

        $this->course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test Description',
            'category_id' => $category->id,
            'instructor_id' => $this->instructor->id,
            'term_id' => $term->id,
            'level_id' => $level->id,
            'price' => 100.00,
            'status' => 'published'
        ]);
    }

    /**
     * Test get all courses endpoint
     */
    public function test_get_all_courses()
    {
        $response = $this->getJson('/api/courses');

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'data']);
    }

    /**
     * Test get single course endpoint
     */
    public function test_get_single_course()
    {
        $response = $this->getJson("/api/courses/{$this->course->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'data']);
    }

    /**
     * Test search courses endpoint
     */
    public function test_search_courses()
    {
        $response = $this->getJson('/api/courses/search?q=test');

        $response->assertStatus(200);
    }

    /**
     * Test featured courses endpoint
     */
    public function test_featured_courses()
    {
        $response = $this->getJson('/api/courses/featured');

        $response->assertStatus(200);
    }

    /**
     * Test popular courses endpoint
     */
    public function test_popular_courses()
    {
        $response = $this->getJson('/api/courses/popular');

        $response->assertStatus(200);
    }

    /**
     * Test get my courses endpoint
     */
    public function test_get_my_courses()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->getJson('/api/courses/my-courses');

        $response->assertStatus(200);
    }

    /**
     * Test create course endpoint
     */
    public function test_create_course()
    {
        $instructorToken = $this->instructor->createToken('api-token')->plainTextToken;
        $category = Category::factory()->create();
        $term = Term::factory()->create();
        $level = Level::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $instructorToken")
                        ->postJson('/api/courses', [
                            'title' => 'New Course',
                            'description' => 'New Description',
                            'category_id' => $category->id,
                            'term_id' => $term->id,
                            'level_id' => $level->id,
                            'difficulty' => 'beginner',
                            'price' => 150.00
                        ]);

        $response->assertStatus(201);
    }

    /**
     * Test update course endpoint
     */
    public function test_update_course()
    {
        $instructorToken = $this->instructor->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $instructorToken")
                        ->putJson("/api/courses/{$this->course->id}", [
                            'title' => 'Updated Course',
                            'price' => 200.00
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test delete course endpoint
     */
    public function test_delete_course()
    {
        $instructorToken = $this->instructor->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $instructorToken")
                        ->deleteJson("/api/courses/{$this->course->id}");

        $response->assertStatus(200);
    }

    /**
     * Test publish course endpoint
     */
    public function test_publish_course()
    {
        // Create a lesson for the course (required for publishing)
        Lesson::factory()->create(['course_id' => $this->course->id]);

        $instructorToken = $this->instructor->createToken('api-token')->plainTextToken;

        // First unpublish the course to test publishing
        $this->course->update(['status' => 'draft']);

        $response = $this->withHeader('Authorization', "Bearer $instructorToken")
                        ->postJson("/api/courses/{$this->course->id}/publish");

        $response->assertStatus(200);
    }

    /**
     * Test unpublish course endpoint
     */
    public function test_unpublish_course()
    {
        $instructorToken = $this->instructor->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $instructorToken")
                        ->postJson("/api/courses/{$this->course->id}/unpublish");

        $response->assertStatus(200);
    }

    /**
     * Test get course students endpoint
     */
    public function test_get_course_students()
    {
        $instructorToken = $this->instructor->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $instructorToken")
                        ->getJson("/api/courses/{$this->course->id}/students");

        $response->assertStatus(200);
    }

    /**
     * Test get course analytics endpoint
     */
    public function test_get_course_analytics()
    {
        $instructorToken = $this->instructor->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $instructorToken")
                        ->getJson("/api/courses/{$this->course->id}/analytics");

        $response->assertStatus(200);
    }
}

