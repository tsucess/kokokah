<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for payments table (admin queries)
        Schema::table('payments', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'idx_payments_status_created');
            $table->index(['gateway', 'status'], 'idx_payments_gateway_status');
        });

        // Add indexes for enrollments table (admin queries)
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'idx_enrollments_status_created');
            $table->index(['status', 'completed_at'], 'idx_enrollments_status_completed');
        });

        // Add indexes for courses table (admin queries)
        Schema::table('courses', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'idx_courses_status_created');
            $table->index(['instructor_id', 'status'], 'idx_courses_instructor_status');
        });

        // Add indexes for course_reviews table (admin queries)
        if (Schema::hasTable('course_reviews')) {
            Schema::table('course_reviews', function (Blueprint $table) {
                $table->index(['course_id', 'created_at'], 'idx_reviews_course_created');
                $table->index(['rating', 'created_at'], 'idx_reviews_rating_created');
            });
        }

        // Add indexes for forum_topics table (admin queries)
        if (Schema::hasTable('forum_topics')) {
            Schema::table('forum_topics', function (Blueprint $table) {
                $table->index('created_at', 'idx_forum_topics_created');
            });
        }

        // Add indexes for certificates table (admin queries)
        if (Schema::hasTable('certificates')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->index('created_at', 'idx_certificates_created');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Indexes are not critical, so we skip the down migration
        // This prevents issues with foreign key constraints and missing indexes
    }
};
