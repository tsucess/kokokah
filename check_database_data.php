<?php

/**
 * Check Database Data
 * Check what data exists in the database
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 CHECKING DATABASE DATA\n";
echo str_repeat("=", 50) . "\n\n";

try {
    // Check courses
    echo "📚 COURSES:\n";
    $courses = DB::table('courses')->select('id', 'title', 'status')->get();
    if ($courses->count() > 0) {
        foreach ($courses as $course) {
            echo "  ID: {$course->id} - {$course->title} ({$course->status})\n";
        }
    } else {
        echo "  ❌ No courses found\n";
    }
    echo "\n";

    // Check lessons
    echo "📝 LESSONS:\n";
    $lessons = DB::table('lessons')->select('id', 'title', 'course_id')->get();
    if ($lessons->count() > 0) {
        foreach ($lessons as $lesson) {
            echo "  ID: {$lesson->id} - {$lesson->title} (Course: {$lesson->course_id})\n";
        }
    } else {
        echo "  ❌ No lessons found\n";
    }
    echo "\n";

    // Check quizzes
    echo "📋 QUIZZES:\n";
    $quizzes = DB::table('quizzes')->select('id', 'title', 'lesson_id')->get();
    if ($quizzes->count() > 0) {
        foreach ($quizzes as $quiz) {
            echo "  ID: {$quiz->id} - {$quiz->title} (Lesson: {$quiz->lesson_id})\n";
        }
    } else {
        echo "  ❌ No quizzes found\n";
    }
    echo "\n";

    // Check assignments
    echo "📄 ASSIGNMENTS:\n";
    $assignments = DB::table('assignments')->select('id', 'title', 'course_id')->get();
    if ($assignments->count() > 0) {
        foreach ($assignments as $assignment) {
            echo "  ID: {$assignment->id} - {$assignment->title} (Course: {$assignment->course_id})\n";
        }
    } else {
        echo "  ❌ No assignments found\n";
    }
    echo "\n";

    // Check enrollments
    echo "🎓 ENROLLMENTS:\n";
    $enrollments = DB::table('enrollments')->select('id', 'user_id', 'course_id', 'status')->get();
    if ($enrollments->count() > 0) {
        foreach ($enrollments as $enrollment) {
            echo "  ID: {$enrollment->id} - User: {$enrollment->user_id}, Course: {$enrollment->course_id} ({$enrollment->status})\n";
        }
    } else {
        echo "  ❌ No enrollments found\n";
    }
    echo "\n";

    // Check wallets
    echo "💰 WALLETS:\n";
    $wallets = DB::table('wallets')->select('id', 'user_id', 'balance')->get();
    if ($wallets->count() > 0) {
        foreach ($wallets as $wallet) {
            echo "  ID: {$wallet->id} - User: {$wallet->user_id}, Balance: {$wallet->balance}\n";
        }
    } else {
        echo "  ❌ No wallets found\n";
    }
    echo "\n";

    // Check chat_sessions table structure
    echo "💬 CHAT_SESSIONS TABLE STRUCTURE:\n";
    $columns = DB::select("DESCRIBE chat_sessions");
    foreach ($columns as $column) {
        echo "  {$column->Field} - {$column->Type}\n";
    }
    echo "\n";

    // Check chat sessions data
    echo "💬 CHAT SESSIONS:\n";
    $chatSessions = DB::table('chat_sessions')->select('id', 'user_id', 'session_token')->get();
    if ($chatSessions->count() > 0) {
        foreach ($chatSessions as $session) {
            echo "  ID: {$session->id} - User: {$session->user_id}, Token: {$session->session_token}\n";
        }
    } else {
        echo "  ❌ No chat sessions found\n";
    }
    echo "\n";

    // Check badges
    echo "🏆 BADGES:\n";
    $badges = DB::table('badges')->select('id', 'name')->get();
    if ($badges->count() > 0) {
        foreach ($badges as $badge) {
            echo "  ID: {$badge->id} - {$badge->name}\n";
        }
    } else {
        echo "  ❌ No badges found\n";
    }
    echo "\n";

    // Check user_badges
    echo "🏆 USER BADGES:\n";
    $userBadges = DB::table('user_badges')->select('user_id', 'badge_id')->get();
    if ($userBadges->count() > 0) {
        foreach ($userBadges as $userBadge) {
            echo "  User: {$userBadge->user_id}, Badge: {$userBadge->badge_id}\n";
        }
    } else {
        echo "  ❌ No user badges found\n";
    }
    echo "\n";

    echo "✅ Database data check completed!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
