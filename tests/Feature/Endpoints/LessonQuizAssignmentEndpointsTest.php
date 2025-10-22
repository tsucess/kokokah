<?php

namespace Tests\Feature\Endpoints;

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Assignment;
use App\Models\Category;
use App\Models\Term;
use App\Models\Level;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonQuizAssignmentEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected $instructor;
    protected $student;
    protected $course;
    protected $lesson;
    protected $quiz;
    protected $assignment;
    protected $instructorToken;
    protected $studentToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->instructor = User::factory()->create(['role' => 'instructor']);
        $this->student = User::factory()->create(['role' => 'student']);

        $this->instructorToken = $this->instructor->createToken('api-token')->plainTextToken;
        $this->studentToken = $this->student->createToken('api-token')->plainTextToken;

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

        $this->lesson = Lesson::factory()->create(['course_id' => $this->course->id]);
        $this->quiz = Quiz::factory()->create(['lesson_id' => $this->lesson->id]);
        $this->assignment = Assignment::factory()->create(['course_id' => $this->course->id]);
    }

    /**
     * Test get course lessons endpoint
     */
    public function test_get_course_lessons()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson("/api/courses/{$this->course->id}/lessons");

        $response->assertStatus(200);
    }

    /**
     * Test create lesson endpoint
     */
    public function test_create_lesson()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->postJson("/api/courses/{$this->course->id}/lessons", [
                            'title' => 'New Lesson',
                            'content' => 'Lesson content',
                            'order' => 1
                        ]);

        $response->assertStatus(201);
    }

    /**
     * Test get single lesson endpoint
     */
    public function test_get_single_lesson()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson("/api/lessons/{$this->lesson->id}");

        $response->assertStatus(200);
    }

    /**
     * Test update lesson endpoint
     */
    public function test_update_lesson()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->putJson("/api/lessons/{$this->lesson->id}", [
                            'title' => 'Updated Lesson'
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test delete lesson endpoint
     */
    public function test_delete_lesson()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->deleteJson("/api/lessons/{$this->lesson->id}");

        $response->assertStatus(200);
    }

    /**
     * Test mark lesson complete endpoint
     */
    public function test_mark_lesson_complete()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->postJson("/api/lessons/{$this->lesson->id}/complete");

        $response->assertStatus(200);
    }

    /**
     * Test get lesson progress endpoint
     */
    public function test_get_lesson_progress()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson("/api/lessons/{$this->lesson->id}/progress");

        $response->assertStatus(200);
    }

    /**
     * Test track watch time endpoint
     */
    public function test_track_watch_time()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->postJson("/api/lessons/{$this->lesson->id}/watch-time", [
                            'duration' => 300
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test get lesson attachments endpoint
     */
    public function test_get_lesson_attachments()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson("/api/lessons/{$this->lesson->id}/attachments");

        $response->assertStatus(200);
    }

    /**
     * Test get lesson quizzes endpoint
     */
    public function test_get_lesson_quizzes()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson("/api/lessons/{$this->lesson->id}/quizzes");

        $response->assertStatus(200);
    }

    /**
     * Test create quiz endpoint
     */
    public function test_create_quiz()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->postJson("/api/lessons/{$this->lesson->id}/quizzes", [
                            'title' => 'New Quiz',
                            'description' => 'Quiz description'
                        ]);

        $response->assertStatus(201);
    }

    /**
     * Test get single quiz endpoint
     */
    public function test_get_single_quiz()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson("/api/quizzes/{$this->quiz->id}");

        $response->assertStatus(200);
    }

    /**
     * Test start quiz attempt endpoint
     */
    public function test_start_quiz_attempt()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->postJson("/api/quizzes/{$this->quiz->id}/start");

        $response->assertStatus(200);
    }

    /**
     * Test get course assignments endpoint
     */
    public function test_get_course_assignments()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson("/api/courses/{$this->course->id}/assignments");

        $response->assertStatus(200);
    }

    /**
     * Test create assignment endpoint
     */
    public function test_create_assignment()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->instructorToken")
                        ->postJson("/api/courses/{$this->course->id}/assignments", [
                            'title' => 'New Assignment',
                            'description' => 'Assignment description'
                        ]);

        $response->assertStatus(201);
    }

    /**
     * Test get single assignment endpoint
     */
    public function test_get_single_assignment()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->studentToken")
                        ->getJson("/api/assignments/{$this->assignment->id}");

        $response->assertStatus(200);
    }
}

