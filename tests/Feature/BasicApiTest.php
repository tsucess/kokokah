<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\Term;
use App\Models\Level;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BasicApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_root_endpoint()
    {
        $response = $this->getJson('/api/');
        $response->assertStatus(200);
    }

    public function test_can_create_basic_models()
    {
        // Create basic models manually to test relationships
        $user = User::factory()->create(['role' => 'instructor']);
        $term = Term::factory()->create();
        $level = Level::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        
        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test Description',
            'category_id' => $category->id,
            'instructor_id' => $user->id,
            'term_id' => $term->id,
            'level_id' => $level->id,
            'price' => 100.00,
            'status' => 'published',
            'duration_hours' => 10,
            'difficulty' => 'beginner',
        ]);

        $this->assertDatabaseHas('courses', [
            'title' => 'Test Course',
            'status' => 'published'
        ]);
    }

    public function test_courses_api_endpoint()
    {
        // Create the required models
        $user = User::factory()->create(['role' => 'instructor']);
        $term = Term::factory()->create();
        $level = Level::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        Course::create([
            'title' => 'Published Course',
            'description' => 'Test Description',
            'category_id' => $category->id,
            'instructor_id' => $user->id,
            'term_id' => $term->id,
            'level_id' => $level->id,
            'price' => 100.00,
            'status' => 'published',
            'duration_hours' => 10,
            'difficulty' => 'beginner',
        ]);

        $response = $this->getJson('/api/courses');

        if ($response->status() !== 200) {
            // Debug the error
            dump($response->getContent());
            dump($response->status());
        }

        $response->assertStatus(200);
    }
}
