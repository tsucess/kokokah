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
        if (Schema::hasTable('payments')) {
            try {
                Schema::table('payments', function (Blueprint $table) {
                    $table->index(['status', 'created_at'], 'idx_payments_status_created');
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }

            try {
                Schema::table('payments', function (Blueprint $table) {
                    $table->index(['gateway', 'status'], 'idx_payments_gateway_status');
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        }

        // Add indexes for enrollments table (admin queries)
        if (Schema::hasTable('enrollments')) {
            try {
                Schema::table('enrollments', function (Blueprint $table) {
                    $table->index(['status', 'created_at'], 'idx_enrollments_status_created');
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }

            try {
                Schema::table('enrollments', function (Blueprint $table) {
                    $table->index(['status', 'completed_at'], 'idx_enrollments_status_completed');
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        }

        // Add indexes for courses table (admin queries)
        if (Schema::hasTable('courses')) {
            try {
                Schema::table('courses', function (Blueprint $table) {
                    $table->index(['status', 'created_at'], 'idx_courses_status_created');
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }

            try {
                Schema::table('courses', function (Blueprint $table) {
                    $table->index(['instructor_id', 'status'], 'idx_courses_instructor_status');
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        }

        // Add indexes for course_reviews table (admin queries)
        if (Schema::hasTable('course_reviews')) {
            try {
                Schema::table('course_reviews', function (Blueprint $table) {
                    $table->index(['course_id', 'created_at'], 'idx_reviews_course_created');
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }

            try {
                Schema::table('course_reviews', function (Blueprint $table) {
                    $table->index(['rating', 'created_at'], 'idx_reviews_rating_created');
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        }

        // Add indexes for forum_topics table (admin queries)
        if (Schema::hasTable('forum_topics')) {
            try {
                Schema::table('forum_topics', function (Blueprint $table) {
                    $table->index('created_at', 'idx_forum_topics_created');
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        }

        // Add indexes for certificates table (admin queries)
        if (Schema::hasTable('certificates')) {
            try {
                Schema::table('certificates', function (Blueprint $table) {
                    $table->index('created_at', 'idx_certificates_created');
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop payment indexes
        if (Schema::hasTable('payments')) {
            try {
                Schema::table('payments', function (Blueprint $table) {
                    $table->dropIndex('idx_payments_status_created');
                });
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }

            try {
                Schema::table('payments', function (Blueprint $table) {
                    $table->dropIndex('idx_payments_gateway_status');
                });
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }
        }

        // Drop enrollment indexes
        if (Schema::hasTable('enrollments')) {
            try {
                Schema::table('enrollments', function (Blueprint $table) {
                    $table->dropIndex('idx_enrollments_status_created');
                });
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }

            try {
                Schema::table('enrollments', function (Blueprint $table) {
                    $table->dropIndex('idx_enrollments_status_completed');
                });
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }
        }

        // Drop course indexes
        if (Schema::hasTable('courses')) {
            try {
                Schema::table('courses', function (Blueprint $table) {
                    $table->dropIndex('idx_courses_status_created');
                });
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }

            try {
                Schema::table('courses', function (Blueprint $table) {
                    $table->dropIndex('idx_courses_instructor_status');
                });
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }
        }

        // Drop course_reviews indexes
        if (Schema::hasTable('course_reviews')) {
            try {
                Schema::table('course_reviews', function (Blueprint $table) {
                    $table->dropIndex('idx_reviews_course_created');
                });
            } catch (\Exception $e) {
                // Index doesn't exist or is part of foreign key, skip
            }

            try {
                Schema::table('course_reviews', function (Blueprint $table) {
                    $table->dropIndex('idx_reviews_rating_created');
                });
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }
        }

        // Drop forum_topics indexes
        if (Schema::hasTable('forum_topics')) {
            try {
                Schema::table('forum_topics', function (Blueprint $table) {
                    $table->dropIndex('idx_forum_topics_created');
                });
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }
        }

        // Drop certificates indexes
        if (Schema::hasTable('certificates')) {
            try {
                Schema::table('certificates', function (Blueprint $table) {
                    $table->dropIndex('idx_certificates_created');
                });
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }
        }
    }
};
