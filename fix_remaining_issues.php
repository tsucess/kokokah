<?php

/**
 * Fix Remaining Issues
 * Fix the remaining database and model issues
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🔧 FIXING REMAINING ISSUES\n";
echo str_repeat("=", 50) . "\n\n";

try {
    // 1. Add missing columns to chat_sessions
    echo "💬 Adding missing chat_sessions columns...\n";
    
    if (!Schema::hasColumn('chat_sessions', 'last_activity_at')) {
        DB::statement("ALTER TABLE chat_sessions ADD COLUMN last_activity_at TIMESTAMP NULL AFTER status");
        echo "  ✅ Added last_activity_at column\n";
    }
    
    if (!Schema::hasColumn('chat_sessions', 'started_at')) {
        DB::statement("ALTER TABLE chat_sessions ADD COLUMN started_at TIMESTAMP NULL AFTER last_activity_at");
        echo "  ✅ Added started_at column\n";
    }

    // Update existing chat sessions with timestamps
    DB::table('chat_sessions')->update([
        'last_activity_at' => now(),
        'started_at' => now()
    ]);
    echo "  ✅ Updated existing chat sessions with timestamps\n\n";

    // 2. Create badges if none exist
    echo "🏆 Creating badges...\n";
    if (DB::table('badges')->count() === 0) {
        $badges = [
            [
                'name' => 'First Login',
                'icon' => 'login-icon.png',
                'criteria' => json_encode(['type' => 'login', 'count' => 1]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Course Completer',
                'icon' => 'course-icon.png',
                'criteria' => json_encode(['type' => 'course_completion', 'count' => 1]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Quiz Master',
                'icon' => 'quiz-icon.png',
                'criteria' => json_encode(['type' => 'quiz_perfect', 'count' => 5]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Study Streak',
                'icon' => 'streak-icon.png',
                'criteria' => json_encode(['type' => 'daily_login', 'count' => 7]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Knowledge Seeker',
                'icon' => 'seeker-icon.png',
                'criteria' => json_encode(['type' => 'lesson_completion', 'count' => 10]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('badges')->insert($badges);
        echo "  ✅ Created 5 badges\n";

        // Award some badges to users
        $userIds = DB::table('users')->where('role', 'student')->pluck('id')->toArray();
        if (!empty($userIds)) {
            $badgeIds = DB::table('badges')->pluck('id')->toArray();
            
            foreach (array_slice($userIds, 0, 3) as $userId) {
                foreach (array_slice($badgeIds, 0, 2) as $badgeId) {
                    DB::table('user_badges')->insert([
                        'user_id' => $userId,
                        'badge_id' => $badgeId,
                        'earned_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
            echo "  ✅ Awarded badges to users\n";
        }
    } else {
        echo "  ✅ Badges already exist\n";
    }
    echo "\n";

    // 3. Publish course 10 so it can be used in tests
    echo "📚 Publishing course 10...\n";
    DB::table('courses')->where('id', 10)->update([
        'status' => 'published',
        'published_at' => now()
    ]);
    echo "  ✅ Course 10 is now published\n\n";

    // 4. Create enrollments for testing
    echo "🎓 Creating test enrollments...\n";
    $studentIds = DB::table('users')->where('role', 'student')->pluck('id')->toArray();
    $publishedCourseIds = DB::table('courses')->where('status', 'published')->pluck('id')->toArray();
    
    if (!empty($studentIds) && !empty($publishedCourseIds)) {
        foreach (array_slice($studentIds, 0, 2) as $index => $studentId) {
            foreach (array_slice($publishedCourseIds, 0, 2) as $courseId) {
                // Check if enrollment already exists
                $exists = DB::table('enrollments')
                    ->where('user_id', $studentId)
                    ->where('course_id', $courseId)
                    ->exists();
                
                if (!$exists) {
                    DB::table('enrollments')->insert([
                        'user_id' => $studentId,
                        'course_id' => $courseId,
                        'status' => 'active',
                        'amount_paid' => 99.99,
                        'enrolled_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }
        echo "  ✅ Created test enrollments\n";
    }
    echo "\n";

    // 5. Add missing columns to other tables
    echo "🔧 Adding other missing columns...\n";
    
    // Add missing columns to users table for dashboard
    if (!Schema::hasColumn('users', 'total_study_time')) {
        DB::statement("ALTER TABLE users ADD COLUMN total_study_time INT DEFAULT 0 AFTER last_login_ip");
        echo "  ✅ Added total_study_time to users\n";
    }

    // Add missing columns to courses for analytics
    if (!Schema::hasColumn('courses', 'total_enrollments')) {
        DB::statement("ALTER TABLE courses ADD COLUMN total_enrollments INT DEFAULT 0 AFTER published_at");
        echo "  ✅ Added total_enrollments to courses\n";
    }

    // Update course enrollment counts
    $courses = DB::table('courses')->get();
    foreach ($courses as $course) {
        $enrollmentCount = DB::table('enrollments')->where('course_id', $course->id)->count();
        DB::table('courses')->where('id', $course->id)->update(['total_enrollments' => $enrollmentCount]);
    }
    echo "  ✅ Updated course enrollment counts\n\n";

    // 6. Create some transactions for wallet testing
    echo "💰 Creating wallet transactions...\n";
    $wallets = DB::table('wallets')->get();
    foreach ($wallets as $wallet) {
        // Check if transactions already exist
        $transactionExists = DB::table('transactions')->where('wallet_id', $wallet->id)->exists();
        
        if (!$transactionExists) {
            DB::table('transactions')->insert([
                [
                    'wallet_id' => $wallet->id,
                    'amount' => 1000.00,
                    'type' => 'credit',
                    'reference' => 'INITIAL_CREDIT_' . $wallet->user_id,
                    'status' => 'success',
                    'description' => 'Initial wallet credit',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'wallet_id' => $wallet->id,
                    'amount' => 99.99,
                    'type' => 'debit',
                    'reference' => 'COURSE_PURCHASE_' . $wallet->user_id,
                    'status' => 'success',
                    'description' => 'Course purchase',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }
    }
    echo "  ✅ Created wallet transactions\n\n";

    // 7. Create some notifications (using Laravel's notification structure)
    echo "🔔 Creating notifications...\n";
    $userIds = DB::table('users')->pluck('id')->toArray();

    if (!empty($userIds)) {
        foreach (array_slice($userIds, 0, 5) as $userId) {
            $notificationExists = DB::table('notifications')
                ->where('notifiable_type', 'App\\Models\\User')
                ->where('notifiable_id', $userId)
                ->exists();

            if (!$notificationExists) {
                DB::table('notifications')->insert([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'type' => 'App\\Notifications\\WelcomeNotification',
                    'notifiable_type' => 'App\\Models\\User',
                    'notifiable_id' => $userId,
                    'data' => json_encode([
                        'title' => 'Welcome to Kokokah!',
                        'message' => 'Welcome to our learning platform. Start your learning journey today!',
                        'action_url' => '/dashboard'
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        echo "  ✅ Created welcome notifications\n";
    }
    echo "\n";

    echo "🎉 All remaining issues have been fixed!\n";
    echo "✅ Chat sessions columns added\n";
    echo "✅ Badges created and awarded\n";
    echo "✅ Course 10 published\n";
    echo "✅ Test enrollments created\n";
    echo "✅ Missing columns added\n";
    echo "✅ Wallet transactions created\n";
    echo "✅ Notifications created\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
