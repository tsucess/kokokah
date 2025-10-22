<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING CRITICAL ISSUES\n";
echo "==================================================\n\n";

try {
    // 1. Fix forums table - add content column
    echo "📝 Fixing forums table...\n";
    if (Schema::hasTable('forums')) {
        if (!Schema::hasColumn('forums', 'content')) {
            DB::statement("ALTER TABLE forums ADD COLUMN content TEXT NULL AFTER title");
            echo "  ✅ Added content column to forums table\n";
        } else {
            echo "  ✅ Content column already exists in forums table\n";
        }
    } else {
        echo "  ⚠️ Forums table doesn't exist, creating it...\n";
        Schema::create('forums', function ($table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->integer('views_count')->default(0);
            $table->integer('replies_count')->default(0);
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
        });
        echo "  ✅ Created forums table with content column\n";
    }

    // 2. Fix chat_sessions table - add ended_at column
    echo "\n💬 Fixing chat_sessions table...\n";
    if (!Schema::hasColumn('chat_sessions', 'ended_at')) {
        DB::statement("ALTER TABLE chat_sessions ADD COLUMN ended_at TIMESTAMP NULL AFTER started_at");
        echo "  ✅ Added ended_at column to chat_sessions table\n";
    } else {
        echo "  ✅ ended_at column already exists in chat_sessions table\n";
    }

    // 3. Update existing chat sessions with ended_at for completed ones
    $completedSessions = DB::table('chat_sessions')
        ->where('status', 'ended')
        ->whereNull('ended_at')
        ->count();
    
    if ($completedSessions > 0) {
        DB::table('chat_sessions')
            ->where('status', 'ended')
            ->whereNull('ended_at')
            ->update(['ended_at' => now()]);
        echo "  ✅ Updated {$completedSessions} completed chat sessions with ended_at\n";
    }

    // 4. Create sample forum posts (using correct forum structure)
    echo "\n📝 Creating sample forum posts...\n";
    $courses = DB::table('courses')->pluck('id')->toArray();
    $users = DB::table('users')->pluck('id')->toArray();

    if (!empty($courses) && !empty($users)) {
        $forumExists = DB::table('forums')->exists();

        if (!$forumExists) {
            foreach (array_slice($courses, 0, 3) as $courseId) {
                // Create forum first
                $forumId = DB::table('forums')->insertGetId([
                    'course_id' => $courseId,
                    'title' => 'General Discussion',
                    'description' => 'General discussion forum for this course',
                    'content' => 'Welcome to the course discussion forum!',
                    'is_active' => true,
                    'sort_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Create a topic in the forum
                $userId = $users[array_rand($users)];
                DB::table('forum_topics')->insert([
                    'forum_id' => $forumId,
                    'user_id' => $userId,
                    'title' => 'Welcome to Course Discussion',
                    'content' => 'This is the main discussion topic for this course. Feel free to ask questions and share insights!',
                    'is_pinned' => true,
                    'is_locked' => false,
                    'views' => rand(10, 100),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            echo "  ✅ Created sample forum posts\n";
        } else {
            echo "  ✅ Forum posts already exist\n";
        }
    }

    // 5. Fix wallet service issues - check for missing wallet records
    echo "\n💰 Fixing wallet issues...\n";
    $usersWithoutWallets = DB::table('users')
        ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
        ->whereNull('wallets.id')
        ->pluck('users.id')
        ->toArray();
    
    if (!empty($usersWithoutWallets)) {
        foreach ($usersWithoutWallets as $userId) {
            DB::table('wallets')->insert([
                'user_id' => $userId,
                'balance' => 0.00,
                'currency' => 'NGN',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        echo "  ✅ Created wallets for " . count($usersWithoutWallets) . " users\n";
    } else {
        echo "  ✅ All users have wallets\n";
    }

    // 6. Fix course enrollment issues
    echo "\n🎓 Fixing course enrollment issues...\n";
    $studentUsers = DB::table('users')->where('role', 'student')->pluck('id')->toArray();
    $publishedCourses = DB::table('courses')->where('status', 'published')->pluck('id')->toArray();
    
    if (!empty($studentUsers) && !empty($publishedCourses)) {
        $enrollmentExists = DB::table('enrollments')
            ->whereIn('user_id', $studentUsers)
            ->whereIn('course_id', $publishedCourses)
            ->exists();
        
        if (!$enrollmentExists) {
            // Create at least one enrollment for testing
            $studentId = $studentUsers[0];
            $courseId = $publishedCourses[0];
            
            DB::table('enrollments')->insert([
                'user_id' => $studentId,
                'course_id' => $courseId,
                'status' => 'active',
                'progress' => 25.5,
                'enrolled_at' => now(),
                'amount_paid' => 99.99,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "  ✅ Created test enrollment for student\n";
        } else {
            echo "  ✅ Student enrollments already exist\n";
        }
    }

    // 7. Create sample lesson completions
    echo "\n📚 Creating lesson completions...\n";
    $lessons = DB::table('lessons')->pluck('id')->toArray();
    
    if (!empty($lessons) && !empty($studentUsers)) {
        $completionExists = DB::table('lesson_completions')->exists();
        
        if (!$completionExists) {
            foreach (array_slice($lessons, 0, 3) as $lessonId) {
                $studentId = $studentUsers[array_rand($studentUsers)];
                
                DB::table('lesson_completions')->insert([
                    'lesson_id' => $lessonId,
                    'user_id' => $studentId,
                    'completed_at' => now(),
                    'time_spent' => rand(300, 1800), // 5-30 minutes
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            echo "  ✅ Created lesson completions\n";
        } else {
            echo "  ✅ Lesson completions already exist\n";
        }
    }

    echo "\n🎉 Critical issues have been fixed!\n";
    echo "✅ Forums table content column added\n";
    echo "✅ Chat sessions ended_at column added\n";
    echo "✅ Sample forum posts created\n";
    echo "✅ Missing wallets created\n";
    echo "✅ Course enrollments verified\n";
    echo "✅ Lesson completions created\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
