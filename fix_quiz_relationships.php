<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING QUIZ RELATIONSHIPS\n";
echo "============================================================\n\n";

try {
    // Get all existing courses
    $courses = \App\Models\Course::all();
    echo "Available courses:\n";
    foreach ($courses as $course) {
        echo "  - Course {$course->id}: {$course->title}\n";
    }
    
    if ($courses->isEmpty()) {
        echo "❌ No courses found! Cannot fix relationships.\n";
        exit;
    }
    
    $defaultCourse = $courses->first();
    echo "\nUsing default course: {$defaultCourse->id} - {$defaultCourse->title}\n\n";
    
    // Fix all lessons with invalid course_id
    echo "🔍 Checking all lessons...\n";
    $lessons = \App\Models\Lesson::all();
    $fixedLessons = 0;
    
    foreach ($lessons as $lesson) {
        $course = \App\Models\Course::find($lesson->course_id);
        if (!$course) {
            echo "❌ Lesson {$lesson->id} points to non-existent course {$lesson->course_id}\n";
            
            // Update to point to default course
            $lesson->update(['course_id' => $defaultCourse->id]);
            echo "✅ Fixed lesson {$lesson->id} to point to course {$defaultCourse->id}\n";
            $fixedLessons++;
        } else {
            echo "✅ Lesson {$lesson->id} correctly points to course {$lesson->course_id}\n";
        }
    }
    
    echo "\nFixed {$fixedLessons} lessons\n\n";
    
    // Fix all quizzes with invalid lesson_id
    echo "🔍 Checking all quizzes...\n";
    $quizzes = \App\Models\Quiz::all();
    $fixedQuizzes = 0;
    
    foreach ($quizzes as $quiz) {
        $lesson = \App\Models\Lesson::find($quiz->lesson_id);
        if (!$lesson) {
            echo "❌ Quiz {$quiz->id} points to non-existent lesson {$quiz->lesson_id}\n";
            
            // Create a new lesson for this quiz
            $newLesson = \App\Models\Lesson::create([
                'course_id' => $defaultCourse->id,
                'title' => "Lesson for Quiz: {$quiz->title}",
                'content' => 'This lesson was created to contain quiz: ' . $quiz->title,
                'order' => 999,
                'is_free' => false
            ]);
            
            $quiz->update(['lesson_id' => $newLesson->id]);
            echo "✅ Created lesson {$newLesson->id} and fixed quiz {$quiz->id}\n";
            $fixedQuizzes++;
        } else {
            echo "✅ Quiz {$quiz->id} correctly points to lesson {$quiz->lesson_id}\n";
        }
    }
    
    echo "\nFixed {$fixedQuizzes} quizzes\n\n";
    
    // Verify all relationships are working
    echo "🔗 Verifying all quiz relationships...\n";
    $allQuizzes = \App\Models\Quiz::with(['lesson.course'])->get();
    $workingQuizzes = 0;
    
    foreach ($allQuizzes as $quiz) {
        if ($quiz->lesson && $quiz->lesson->course) {
            echo "✅ Quiz {$quiz->id}: {$quiz->title} → Lesson: {$quiz->lesson->title} → Course: {$quiz->lesson->course->title}\n";
            $workingQuizzes++;
        } else {
            echo "❌ Quiz {$quiz->id}: Relationship still broken\n";
        }
    }
    
    echo "\n============================================================\n";
    echo "📊 QUIZ RELATIONSHIP FIX RESULTS\n";
    echo "============================================================\n";
    echo "Total Quizzes: " . $allQuizzes->count() . "\n";
    echo "✅ Working Relationships: {$workingQuizzes}\n";
    echo "❌ Broken Relationships: " . ($allQuizzes->count() - $workingQuizzes) . "\n";
    echo "📈 Success Rate: " . round(($workingQuizzes / $allQuizzes->count()) * 100, 2) . "%\n";
    
    // Ensure student is enrolled in all courses for testing
    echo "\n👤 Ensuring student enrollment for testing...\n";
    $student = \App\Models\User::where('role', 'student')->first();
    if ($student) {
        foreach ($courses as $course) {
            $enrollment = $course->enrollments()->where('user_id', $student->id)->first();
            if (!$enrollment) {
                $course->enrollments()->create([
                    'user_id' => $student->id,
                    'status' => 'active',
                    'enrolled_at' => now()
                ]);
                echo "✅ Enrolled student in course: {$course->title}\n";
            }
        }
    }
    
    echo "\n🎉 Quiz relationship fixes completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
