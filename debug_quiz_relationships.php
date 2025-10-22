<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 DEBUGGING QUIZ RELATIONSHIPS\n";
echo "============================================================\n\n";

try {
    // Check quiz data
    $quiz = \App\Models\Quiz::first();
    if (!$quiz) {
        echo "❌ No quiz found\n";
        exit;
    }
    
    echo "📋 Quiz Information:\n";
    echo "Quiz ID: {$quiz->id}\n";
    echo "Quiz Title: {$quiz->title}\n";
    echo "Lesson ID: {$quiz->lesson_id}\n";
    
    // Check lesson
    $lesson = $quiz->lesson;
    if (!$lesson) {
        echo "❌ Lesson not found for quiz\n";
        
        // Check if lesson exists
        $lessonExists = \App\Models\Lesson::find($quiz->lesson_id);
        if ($lessonExists) {
            echo "✅ Lesson {$quiz->lesson_id} exists in database\n";
            echo "Lesson title: {$lessonExists->title}\n";
            echo "Lesson course_id: {$lessonExists->course_id}\n";
        } else {
            echo "❌ Lesson {$quiz->lesson_id} does NOT exist in database\n";
            
            // Create missing lesson
            $course = \App\Models\Course::first();
            if ($course) {
                $newLesson = \App\Models\Lesson::create([
                    'course_id' => $course->id,
                    'title' => 'Quiz Lesson',
                    'content' => 'This lesson contains quizzes',
                    'order' => 1,
                    'is_free' => false
                ]);
                
                // Update quiz to point to new lesson
                $quiz->update(['lesson_id' => $newLesson->id]);
                
                echo "✅ Created new lesson {$newLesson->id} and updated quiz\n";
                
                // Re-fetch quiz with new lesson
                $quiz = \App\Models\Quiz::with(['lesson.course'])->find($quiz->id);
                $lesson = $quiz->lesson;
            }
        }
    } else {
        echo "✅ Lesson found: {$lesson->title}\n";
        echo "Lesson course_id: {$lesson->course_id}\n";
    }
    
    // Check course
    if ($lesson) {
        $course = $lesson->course;
        if (!$course) {
            echo "❌ Course not found for lesson\n";
            
            // Check if course exists
            $courseExists = \App\Models\Course::find($lesson->course_id);
            if ($courseExists) {
                echo "✅ Course {$lesson->course_id} exists in database\n";
                echo "Course title: {$courseExists->title}\n";
            } else {
                echo "❌ Course {$lesson->course_id} does NOT exist in database\n";
                
                // Update lesson to point to existing course
                $existingCourse = \App\Models\Course::first();
                if ($existingCourse) {
                    $lesson->update(['course_id' => $existingCourse->id]);
                    echo "✅ Updated lesson to point to course {$existingCourse->id}\n";
                }
            }
        } else {
            echo "✅ Course found: {$course->title}\n";
            echo "Course ID: {$course->id}\n";
        }
    }
    
    // Test the full relationship chain
    echo "\n🔗 Testing Relationship Chain:\n";
    $quiz = \App\Models\Quiz::with(['lesson.course'])->first();
    
    if ($quiz && $quiz->lesson && $quiz->lesson->course) {
        echo "✅ Quiz → Lesson → Course relationship working\n";
        echo "Quiz: {$quiz->title}\n";
        echo "Lesson: {$quiz->lesson->title}\n";
        echo "Course: {$quiz->lesson->course->title}\n";
        
        // Test enrollments
        $enrollments = $quiz->lesson->course->enrollments;
        echo "✅ Enrollments count: " . $enrollments->count() . "\n";
        
    } else {
        echo "❌ Relationship chain broken\n";
        
        if (!$quiz) echo "  - Quiz is null\n";
        if ($quiz && !$quiz->lesson) echo "  - Lesson is null\n";
        if ($quiz && $quiz->lesson && !$quiz->lesson->course) echo "  - Course is null\n";
    }
    
    // Check all quizzes
    echo "\n📊 All Quizzes Status:\n";
    $allQuizzes = \App\Models\Quiz::with(['lesson.course'])->get();
    foreach ($allQuizzes as $q) {
        $status = "❌";
        if ($q->lesson && $q->lesson->course) {
            $status = "✅";
        }
        echo "{$status} Quiz {$q->id}: {$q->title} (Lesson: {$q->lesson_id})\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
