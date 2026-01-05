<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Performance indexes for frequently queried columns
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('is_active');
            $table->index(['role', 'is_active']); // Composite index
            $table->index('level_id');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->index('status');
            $table->index('category_id');
            $table->index('instructor_id');
            $table->index(['status', 'category_id']); // For filtering published courses by category
            $table->index(['level_id', 'term_id']); // For academic filtering
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->index('status');
            $table->index(['user_id', 'status']); // User's active enrollments
            $table->index(['course_id', 'status']); // Course enrollment stats
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->index(['course_id', 'order']); // Ordered lessons in course
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->index('status');
            $table->index('type');
            $table->index(['wallet_id', 'status']); // Wallet transaction history
            $table->index('created_at'); // For date-based queries
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->index(['student_id', 'question_id']); // Unique student answers
        });

        // chat_messages indexes are now created in the main migration (2025_12_30_000003)
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['role', 'is_active']);
            $table->dropIndex(['level_id']);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['instructor_id']);
            $table->dropIndex(['status', 'category_id']);
            $table->dropIndex(['level_id', 'term_id']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['course_id', 'status']);
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropIndex(['course_id', 'order']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['type']);
            $table->dropIndex(['wallet_id', 'status']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->dropIndex(['student_id', 'question_id']);
        });

        // chat_messages indexes are now created in the main migration (2025_12_30_000003)
    }
};
