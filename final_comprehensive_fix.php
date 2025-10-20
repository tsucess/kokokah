<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FINAL COMPREHENSIVE FIX\n";
echo "==================================================\n\n";

try {
    // 1. Fix chat_sessions table - add rating column
    echo "💬 Adding rating column to chat_sessions...\n";
    if (!Schema::hasColumn('chat_sessions', 'rating')) {
        DB::statement("ALTER TABLE chat_sessions ADD COLUMN rating TINYINT UNSIGNED NULL AFTER status");
        echo "  ✅ Added rating column to chat_sessions table\n";
    } else {
        echo "  ✅ rating column already exists in chat_sessions table\n";
    }

    // 2. Create lesson_completions table if missing
    echo "📚 Checking lesson_completions table...\n";
    if (!Schema::hasTable('lesson_completions')) {
        Schema::create('lesson_completions', function ($table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('completed_at');
            $table->integer('time_spent')->default(0); // in seconds
            $table->timestamps();
            
            $table->unique(['lesson_id', 'user_id']);
            $table->index(['user_id', 'completed_at']);
        });
        echo "  ✅ Created lesson_completions table\n";
    } else {
        echo "  ✅ lesson_completions table already exists\n";
    }

    // 3. Create course_progress table if missing
    echo "📊 Checking course_progress table...\n";
    if (!Schema::hasTable('course_progress')) {
        Schema::create('course_progress', function ($table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->integer('lessons_completed')->default(0);
            $table->integer('total_lessons')->default(0);
            $table->integer('quizzes_completed')->default(0);
            $table->integer('assignments_completed')->default(0);
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'course_id']);
        });
        echo "  ✅ Created course_progress table\n";
    } else {
        echo "  ✅ course_progress table already exists\n";
    }

    // 4. Create payment_gateways table if missing
    echo "💳 Checking payment_gateways table...\n";
    if (!Schema::hasTable('payment_gateways')) {
        Schema::create('payment_gateways', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('configuration')->nullable();
            $table->decimal('transaction_fee_percentage', 5, 2)->default(0);
            $table->decimal('transaction_fee_fixed', 10, 2)->default(0);
            $table->string('currency', 3)->default('NGN');
            $table->timestamps();
        });
        
        // Insert default payment gateways
        DB::table('payment_gateways')->insert([
            [
                'name' => 'Paystack',
                'slug' => 'paystack',
                'description' => 'Paystack payment gateway for Nigeria',
                'is_active' => true,
                'configuration' => json_encode(['public_key' => '', 'secret_key' => '']),
                'transaction_fee_percentage' => 1.5,
                'transaction_fee_fixed' => 0,
                'currency' => 'NGN',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Flutterwave',
                'slug' => 'flutterwave',
                'description' => 'Flutterwave payment gateway',
                'is_active' => false,
                'configuration' => json_encode(['public_key' => '', 'secret_key' => '']),
                'transaction_fee_percentage' => 1.4,
                'transaction_fee_fixed' => 0,
                'currency' => 'NGN',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
        echo "  ✅ Created payment_gateways table with default gateways\n";
    } else {
        echo "  ✅ payment_gateways table already exists\n";
    }

    // 5. Create certificate_templates table if missing
    echo "🏆 Checking certificate_templates table...\n";
    if (!Schema::hasTable('certificate_templates')) {
        Schema::create('certificate_templates', function ($table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('template_html');
            $table->json('template_variables')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Insert default certificate template
        DB::table('certificate_templates')->insert([
            'name' => 'Default Certificate',
            'description' => 'Default certificate template for course completion',
            'template_html' => '<div class="certificate"><h1>Certificate of Completion</h1><p>This is to certify that</p><h2>{{student_name}}</h2><p>has successfully completed the course</p><h3>{{course_title}}</h3><p>Date: {{completion_date}}</p></div>',
            'template_variables' => json_encode(['student_name', 'course_title', 'completion_date', 'instructor_name']),
            'is_default' => true,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "  ✅ Created certificate_templates table with default template\n";
    } else {
        echo "  ✅ certificate_templates table already exists\n";
    }

    // 6. Create sample course progress records
    echo "📊 Creating sample course progress...\n";
    $enrollments = DB::table('enrollments')->get();
    
    foreach ($enrollments as $enrollment) {
        $progressExists = DB::table('course_progress')
            ->where('user_id', $enrollment->user_id)
            ->where('course_id', $enrollment->course_id)
            ->exists();
        
        if (!$progressExists) {
            $totalLessons = DB::table('lessons')->where('course_id', $enrollment->course_id)->count();
            $completedLessons = DB::table('lesson_completions')
                ->join('lessons', 'lesson_completions.lesson_id', '=', 'lessons.id')
                ->where('lessons.course_id', $enrollment->course_id)
                ->where('lesson_completions.user_id', $enrollment->user_id)
                ->count();
            
            $progressPercentage = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
            
            DB::table('course_progress')->insert([
                'user_id' => $enrollment->user_id,
                'course_id' => $enrollment->course_id,
                'progress_percentage' => round($progressPercentage, 2),
                'lessons_completed' => $completedLessons,
                'total_lessons' => $totalLessons,
                'quizzes_completed' => 0,
                'assignments_completed' => 0,
                'last_accessed_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
    echo "  ✅ Created course progress records for " . count($enrollments) . " enrollments\n";

    // 7. Update users with total_study_time if missing
    echo "👤 Adding total_study_time to users...\n";
    if (!Schema::hasColumn('users', 'total_study_time')) {
        DB::statement("ALTER TABLE users ADD COLUMN total_study_time INT DEFAULT 0 AFTER last_login_ip");
        echo "  ✅ Added total_study_time column to users table\n";
    } else {
        echo "  ✅ total_study_time column already exists in users table\n";
    }

    // 8. Update courses with total_enrollments if missing
    echo "📚 Adding total_enrollments to courses...\n";
    if (!Schema::hasColumn('courses', 'total_enrollments')) {
        DB::statement("ALTER TABLE courses ADD COLUMN total_enrollments INT DEFAULT 0 AFTER max_students");
        echo "  ✅ Added total_enrollments column to courses table\n";
    } else {
        echo "  ✅ total_enrollments column already exists in courses table\n";
    }

    // Update course enrollment counts
    $courses = DB::table('courses')->get();
    foreach ($courses as $course) {
        $enrollmentCount = DB::table('enrollments')->where('course_id', $course->id)->count();
        DB::table('courses')->where('id', $course->id)->update(['total_enrollments' => $enrollmentCount]);
    }
    echo "  ✅ Updated course enrollment counts\n";

    // 9. Create sample certificates
    echo "🏆 Creating sample certificates...\n";
    $completedEnrollments = DB::table('enrollments')
        ->where('status', 'completed')
        ->orWhere('progress', '>=', 100)
        ->get();
    
    foreach ($completedEnrollments as $enrollment) {
        $certificateExists = DB::table('certificates')
            ->where('user_id', $enrollment->user_id)
            ->where('course_id', $enrollment->course_id)
            ->exists();
        
        if (!$certificateExists) {
            DB::table('certificates')->insert([
                'user_id' => $enrollment->user_id,
                'course_id' => $enrollment->course_id,
                'certificate_number' => 'CERT-' . strtoupper(uniqid()),
                'issued_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
    echo "  ✅ Created certificates for completed courses\n";

    echo "\n🎉 Final comprehensive fix completed!\n";
    echo "✅ Chat sessions rating column added\n";
    echo "✅ Lesson completions table verified\n";
    echo "✅ Course progress table created\n";
    echo "✅ Payment gateways table created\n";
    echo "✅ Certificate templates table created\n";
    echo "✅ Sample course progress created\n";
    echo "✅ User study time column added\n";
    echo "✅ Course enrollment counts updated\n";
    echo "✅ Sample certificates created\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
