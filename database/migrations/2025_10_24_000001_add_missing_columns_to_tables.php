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
        // Add description column to quizzes table if it doesn't exist
        if (Schema::hasTable('quizzes') && !Schema::hasColumn('quizzes', 'description')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->text('description')->nullable()->after('title');
            });
        }

        // Add amount_paid column to enrollments table if it doesn't exist
        if (Schema::hasTable('enrollments') && !Schema::hasColumn('enrollments', 'amount_paid')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->decimal('amount_paid', 10, 2)->default(0)->after('status');
            });
        }

        // Add is_active column to quizzes table if it doesn't exist
        if (Schema::hasTable('quizzes') && !Schema::hasColumn('quizzes', 'is_active')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('passing_score');
            });
        }

        // Add time_limit column to quizzes table if it doesn't exist
        if (Schema::hasTable('quizzes') && !Schema::hasColumn('quizzes', 'time_limit')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->integer('time_limit')->nullable()->after('passing_score');
            });
        }

        // Add description column to badges table if it doesn't exist
        if (Schema::hasTable('badges') && !Schema::hasColumn('badges', 'description')) {
            Schema::table('badges', function (Blueprint $table) {
                $table->text('description')->nullable()->after('name');
            });
        }

        // Add points column to badges table if it doesn't exist
        if (Schema::hasTable('badges') && !Schema::hasColumn('badges', 'points')) {
            Schema::table('badges', function (Blueprint $table) {
                $table->integer('points')->default(0)->after('description');
            });
        }

        // Add difficulty column to courses table if it doesn't exist
        if (Schema::hasTable('courses') && !Schema::hasColumn('courses', 'difficulty')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->enum('difficulty', ['beginner', 'intermediate', 'advanced'])->default('beginner')->after('description');
            });
        }

        // Add revoked_at column to user_badges table if it doesn't exist
        if (Schema::hasTable('user_badges') && !Schema::hasColumn('user_badges', 'revoked_at')) {
            Schema::table('user_badges', function (Blueprint $table) {
                $table->timestamp('revoked_at')->nullable()->after('earned_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('quizzes') && Schema::hasColumn('quizzes', 'description')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }

        if (Schema::hasTable('enrollments') && Schema::hasColumn('enrollments', 'amount_paid')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->dropColumn('amount_paid');
            });
        }

        if (Schema::hasTable('quizzes') && Schema::hasColumn('quizzes', 'is_active')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }

        if (Schema::hasTable('quizzes') && Schema::hasColumn('quizzes', 'time_limit')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->dropColumn('time_limit');
            });
        }

        if (Schema::hasTable('badges') && Schema::hasColumn('badges', 'description')) {
            Schema::table('badges', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }

        if (Schema::hasTable('badges') && Schema::hasColumn('badges', 'points')) {
            Schema::table('badges', function (Blueprint $table) {
                $table->dropColumn('points');
            });
        }

        if (Schema::hasTable('courses') && Schema::hasColumn('courses', 'difficulty')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->dropColumn('difficulty');
            });
        }

        if (Schema::hasTable('user_badges') && Schema::hasColumn('user_badges', 'revoked_at')) {
            Schema::table('user_badges', function (Blueprint $table) {
                $table->dropColumn('revoked_at');
            });
        }
    }
};

