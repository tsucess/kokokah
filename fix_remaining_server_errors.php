<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "🔧 FIXING REMAINING SERVER ERRORS\n";
echo "==================================\n\n";

// 1. Fix course_reviews table - add missing 'comment' column
echo "1. Fixing course_reviews table...\n";
if (Schema::hasTable('course_reviews')) {
    $needsComment = !Schema::hasColumn('course_reviews', 'comment');
    $needsStatus = !Schema::hasColumn('course_reviews', 'status');

    if ($needsComment || $needsStatus) {
        echo "   Adding missing columns to course_reviews table...\n";
        Schema::table('course_reviews', function (Blueprint $table) use ($needsComment, $needsStatus) {
            if ($needsComment) {
                $table->text('comment')->nullable()->after('rating');
            }
            if ($needsStatus) {
                $table->string('status')->default('pending')->after('comment');
            }
        });
        echo "   ✅ course_reviews table fixed\n";
    } else {
        echo "   ✅ course_reviews table already has required columns\n";
    }
} else {
    echo "   ⚠️  course_reviews table doesn't exist, creating it...\n";
    Schema::create('course_reviews', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('course_id')->constrained()->onDelete('cascade');
        $table->integer('rating')->default(5);
        $table->text('comment')->nullable();
        $table->string('status')->default('pending');
        $table->timestamps();
    });
    echo "   ✅ course_reviews table created\n";
}

// 2. Fix forum_topics table - add missing 'course_id' column
echo "\n2. Fixing forum_topics table...\n";
if (Schema::hasTable('forum_topics')) {
    if (!Schema::hasColumn('forum_topics', 'course_id')) {
        echo "   Adding missing 'course_id' column to forum_topics table...\n";
        Schema::table('forum_topics', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade')->after('id');
        });
        echo "   ✅ forum_topics table fixed\n";
    } else {
        echo "   ✅ forum_topics table already has course_id column\n";
    }
} else {
    echo "   ⚠️  forum_topics table doesn't exist, creating it...\n";
    Schema::create('forum_topics', function (Blueprint $table) {
        $table->id();
        $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('title');
        $table->text('content');
        $table->boolean('is_pinned')->default(false);
        $table->boolean('is_locked')->default(false);
        $table->integer('views_count')->default(0);
        $table->integer('replies_count')->default(0);
        $table->timestamp('last_activity_at')->nullable();
        $table->timestamps();
    });
    echo "   ✅ forum_topics table created\n";
}

// 3. Fix quizzes table - add missing 'course_id' column
echo "\n3. Fixing quizzes table...\n";
if (Schema::hasTable('quizzes')) {
    if (!Schema::hasColumn('quizzes', 'course_id')) {
        echo "   Adding missing 'course_id' column to quizzes table...\n";
        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade')->after('id');
        });
        echo "   ✅ quizzes table fixed\n";
    } else {
        echo "   ✅ quizzes table already has course_id column\n";
    }
} else {
    echo "   ⚠️  quizzes table doesn't exist, creating it...\n";
    Schema::create('quizzes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade');
        $table->foreignId('lesson_id')->nullable()->constrained()->onDelete('cascade');
        $table->string('title');
        $table->text('description')->nullable();
        $table->integer('time_limit')->nullable(); // in minutes
        $table->integer('max_attempts')->default(1);
        $table->decimal('passing_score', 5, 2)->default(70.00);
        $table->boolean('is_published')->default(false);
        $table->integer('order')->default(0);
        $table->timestamps();
    });
    echo "   ✅ quizzes table created\n";
}

echo "\n==================================\n";
echo "✅ REMAINING SERVER ERROR FIXES APPLIED\n";
echo "==================================\n";
echo "Fixed database schema issues:\n";
echo "• course_reviews table: comment, status columns\n";
echo "• forum_topics table: course_id column\n";
echo "• quizzes table: course_id column\n";
echo "\nNow testing the remaining endpoints...\n";
