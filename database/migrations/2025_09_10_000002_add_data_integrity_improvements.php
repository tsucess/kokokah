<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add unique constraints and improve existing tables
        Schema::table('enrollments', function (Blueprint $table) {
            // Prevent duplicate enrollments
            $table->unique(['user_id', 'course_id']);
            
            // Add enrollment date for better tracking
            $table->timestamp('enrolled_at')->nullable()->after('status');
            $table->timestamp('completed_at')->nullable()->after('enrolled_at');
        });

        Schema::table('answers', function (Blueprint $table) {
            // Prevent multiple answers to same question by same student
            $table->unique(['student_id', 'question_id']);
        });

        Schema::table('submissions', function (Blueprint $table) {
            // Prevent multiple submissions (unless resubmission is allowed)
            $table->unique(['student_id', 'assignment_id']);
            $table->timestamp('submitted_at')->nullable()->after('feedback');
        });

        Schema::table('course_reviews', function (Blueprint $table) {
            // One review per user per course
            $table->unique(['user_id', 'course_id']);
        });

        Schema::table('wallets', function (Blueprint $table) {
            // One wallet per user
            $table->unique('user_id');
            
            // Add currency support
            $table->string('currency', 3)->default('USD')->after('balance');
        });

        Schema::table('courses', function (Blueprint $table) {
            // Add more course metadata
            $table->integer('duration_hours')->nullable()->after('price'); // Course duration
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced'])->default('beginner')->after('duration_hours');
            $table->integer('max_students')->nullable()->after('difficulty'); // Enrollment limit
            $table->timestamp('published_at')->nullable()->after('status');
        });

        Schema::table('lessons', function (Blueprint $table) {
            // Add lesson metadata
            $table->integer('duration_minutes')->nullable()->after('order');
            $table->boolean('is_free')->default(false)->after('duration_minutes'); // Free preview lessons
        });

        Schema::table('quizzes', function (Blueprint $table) {
            // Add quiz configuration
            $table->integer('time_limit_minutes')->nullable()->after('type'); // Quiz time limit
            $table->integer('max_attempts')->default(1)->after('time_limit_minutes');
            $table->integer('passing_score')->default(70)->after('max_attempts'); // Percentage
            $table->boolean('shuffle_questions')->default(false)->after('passing_score');
        });

        Schema::table('assignments', function (Blueprint $table) {
            // Add assignment metadata
            $table->integer('max_score')->default(100)->after('deadline');
            $table->json('allowed_file_types')->nullable()->after('max_score'); // ['pdf', 'doc', 'docx']
            $table->integer('max_file_size_mb')->default(10)->after('allowed_file_types');
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'course_id']);
            $table->dropColumn(['enrolled_at', 'completed_at']);
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->dropUnique(['student_id', 'question_id']);
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropUnique(['student_id', 'assignment_id']);
            $table->dropColumn('submitted_at');
        });

        Schema::table('course_reviews', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'course_id']);
        });

        Schema::table('wallets', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
            $table->dropColumn('currency');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['duration_hours', 'difficulty', 'max_students', 'published_at']);
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['duration_minutes', 'is_free']);
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['time_limit_minutes', 'max_attempts', 'passing_score', 'shuffle_questions']);
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn(['max_score', 'allowed_file_types', 'max_file_size_mb']);
        });
    }
};
