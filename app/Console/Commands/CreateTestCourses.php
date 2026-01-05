<?php

namespace App\Console\Commands;

use App\Models\Course;
use Illuminate\Console\Command;

class CreateTestCourses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-test-courses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test courses for different levels';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $testCourses = [
            // Level 2 courses
            [
                'title' => 'English Literature',
                'description' => 'Study of English literature and language',
                'curriculum_category_id' => 1,
                'course_category_id' => 3,
                'instructor_id' => 4,
                'level_id' => 2,
                'term_id' => 1,
                'price' => 7500,
                'free' => false,
                'status' => 'published'
            ],
            [
                'title' => 'Advanced Mathematics',
                'description' => 'Advanced topics in mathematics',
                'curriculum_category_id' => 1,
                'course_category_id' => 5,
                'instructor_id' => 4,
                'level_id' => 2,
                'term_id' => 1,
                'price' => 8000,
                'free' => false,
                'status' => 'published'
            ],
            // Level 3 courses
            [
                'title' => 'Biology Essentials',
                'description' => 'Essential biology concepts',
                'curriculum_category_id' => 1,
                'course_category_id' => 2,
                'instructor_id' => 9,
                'level_id' => 3,
                'term_id' => 1,
                'price' => 6500,
                'free' => false,
                'status' => 'published'
            ],
            [
                'title' => 'History and Culture',
                'description' => 'Explore history and cultural studies',
                'curriculum_category_id' => 1,
                'course_category_id' => 3,
                'instructor_id' => 4,
                'level_id' => 3,
                'term_id' => 1,
                'price' => 5500,
                'free' => false,
                'status' => 'published'
            ],
        ];

        foreach ($testCourses as $courseData) {
            try {
                $course = Course::create($courseData);
                $this->info("✓ Created: {$course->title} (Level ID: {$course->level_id})");
            } catch (\Exception $e) {
                $this->error("✗ Error creating course: {$e->getMessage()}");
            }
        }

        $this->info("\nTest courses created successfully!");
    }
}
