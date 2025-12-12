<?php

require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\Course;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Create test courses for different levels
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
        echo "✓ Created: {$course->title} (Level ID: {$course->level_id})\n";
    } catch (\Exception $e) {
        echo "✗ Error creating course: {$e->getMessage()}\n";
    }
}

echo "\nTest courses created successfully!\n";

