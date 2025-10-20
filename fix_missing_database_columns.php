<?php

/**
 * Fix Missing Database Columns
 * Add all missing columns that are causing 500 errors
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🔧 FIXING MISSING DATABASE COLUMNS\n";
echo str_repeat("=", 50) . "\n\n";

try {
    // Check and add missing columns
    $columnsToAdd = [
        'enrollments' => [
            'amount_paid' => "ALTER TABLE enrollments ADD COLUMN amount_paid DECIMAL(10,2) DEFAULT 0.00 AFTER status"
        ],
        'chat_sessions' => [
            'status' => "ALTER TABLE chat_sessions ADD COLUMN status ENUM('active', 'ended', 'paused') DEFAULT 'active' AFTER updated_at"
        ],
        'quizzes' => [
            'description' => "ALTER TABLE quizzes ADD COLUMN description TEXT NULL AFTER title"
        ],
        'assignments' => [
            'description' => "ALTER TABLE assignments ADD COLUMN description TEXT NULL AFTER title"
        ],
        'lessons' => [
            'description' => "ALTER TABLE lessons ADD COLUMN description TEXT NULL AFTER title"
        ],
        'course_reviews' => [
            'status' => "ALTER TABLE course_reviews ADD COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' AFTER rating"
        ],
        'notifications' => [
            'user_id' => "ALTER TABLE notifications ADD COLUMN user_id BIGINT UNSIGNED NULL AFTER id, ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE"
        ],
        'coupons' => [
            'status' => "ALTER TABLE coupons ADD COLUMN status ENUM('active', 'inactive', 'expired') DEFAULT 'active' AFTER expires_at"
        ]
    ];

    foreach ($columnsToAdd as $table => $columns) {
        echo "📋 Checking table: {$table}\n";
        
        if (!Schema::hasTable($table)) {
            echo "  ⚠️  Table {$table} does not exist, skipping...\n";
            continue;
        }

        foreach ($columns as $column => $sql) {
            if (!Schema::hasColumn($table, $column)) {
                echo "  ➕ Adding column: {$column}\n";
                try {
                    DB::statement($sql);
                    echo "  ✅ Successfully added {$column} to {$table}\n";
                } catch (Exception $e) {
                    echo "  ❌ Failed to add {$column}: " . $e->getMessage() . "\n";
                }
            } else {
                echo "  ✅ Column {$column} already exists\n";
            }
        }
        echo "\n";
    }

    // Add some sample data for testing
    echo "📊 Adding sample data for testing...\n\n";

    // Add sample lessons if none exist
    if (DB::table('lessons')->count() === 0) {
        echo "➕ Adding sample lessons...\n";
        $courseIds = DB::table('courses')->pluck('id')->toArray();

        if (!empty($courseIds)) {
            foreach ($courseIds as $courseId) {
                DB::table('lessons')->insert([
                    [
                        'title' => 'Introduction to Course',
                        'description' => 'Welcome to this course! This lesson covers the basics.',
                        'content' => 'This is the introductory lesson content.',
                        'course_id' => $courseId,
                        'order' => 1,
                        'duration_minutes' => 30,
                        'is_free' => true,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'title' => 'Advanced Topics',
                        'description' => 'This lesson covers advanced topics in the subject.',
                        'content' => 'Advanced lesson content goes here.',
                        'course_id' => $courseId,
                        'order' => 2,
                        'duration_minutes' => 45,
                        'is_free' => false,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]);
            }
            echo "✅ Added sample lessons\n";
        }
    }

    // Add sample quizzes if none exist
    if (DB::table('quizzes')->count() === 0) {
        echo "➕ Adding sample quizzes...\n";
        $lessonIds = DB::table('lessons')->pluck('id')->toArray();

        if (!empty($lessonIds)) {
            foreach (array_slice($lessonIds, 0, 3) as $lessonId) {
                DB::table('quizzes')->insert([
                    'title' => 'Lesson Quiz',
                    'description' => 'Test your knowledge from this lesson.',
                    'lesson_id' => $lessonId,
                    'type' => 'mcq',
                    'time_limit_minutes' => 30,
                    'max_attempts' => 3,
                    'passing_score' => 70,
                    'shuffle_questions' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            echo "✅ Added sample quizzes\n";
        }
    }

    // Add sample assignments if none exist
    if (DB::table('assignments')->count() === 0) {
        echo "➕ Adding sample assignments...\n";
        $courseIds = DB::table('courses')->pluck('id')->toArray();

        if (!empty($courseIds)) {
            foreach ($courseIds as $courseId) {
                DB::table('assignments')->insert([
                    'title' => 'Course Assignment',
                    'description' => 'Complete this assignment to demonstrate your understanding.',
                    'instructions' => 'Please read the course materials and complete this assignment. Submit your work in PDF format.',
                    'course_id' => $courseId,
                    'deadline' => now()->addDays(7),
                    'max_score' => 100,
                    'allowed_file_types' => json_encode(['pdf', 'doc', 'docx']),
                    'max_file_size_mb' => 10,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            echo "✅ Added sample assignments\n";
        }
    }

    // Update enrollments with amount_paid
    echo "➕ Updating enrollments with amount_paid...\n";
    DB::table('enrollments')
        ->whereNull('amount_paid')
        ->update(['amount_paid' => 99.99]);
    echo "✅ Updated enrollment amounts\n";

    // Add sample chat sessions if none exist
    if (Schema::hasTable('chat_sessions') && DB::table('chat_sessions')->count() === 0) {
        echo "➕ Adding sample chat sessions...\n";
        $userIds = DB::table('users')->where('role', 'student')->pluck('id')->toArray();

        if (!empty($userIds)) {
            foreach (array_slice($userIds, 0, 3) as $index => $userId) {
                DB::table('chat_sessions')->insert([
                    'user_id' => $userId,
                    'session_token' => 'session_' . $userId . '_' . time() . '_' . $index,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            echo "✅ Added sample chat sessions\n";
        }
    }

    echo "\n🎉 Database column fixes completed!\n";
    echo "All missing columns have been added and sample data created.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
