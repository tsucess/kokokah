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
        // Enhance user_badges table
        if (Schema::hasTable('user_badges')) {
            Schema::table('user_badges', function (Blueprint $table) {
                // Add new columns if they don't exist
                if (!Schema::hasColumn('user_badges', 'revoked_at')) {
                    $table->timestamp('revoked_at')->nullable()->after('earned_at');
                }
                if (!Schema::hasColumn('user_badges', 'is_featured')) {
                    $table->boolean('is_featured')->default(false)->after('revoked_at');
                }
                if (!Schema::hasColumn('user_badges', 'progress')) {
                    $table->integer('progress')->default(0)->after('is_featured');
                }
            });
        }

        // Create user_points_history table for tracking points changes
        if (!Schema::hasTable('user_points_history')) {
            Schema::create('user_points_history', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->integer('points_change');
                $table->integer('points_before');
                $table->integer('points_after');
                $table->string('reason')->nullable();
                $table->string('action_type')->nullable(); // lesson_completion, quiz_pass, course_completion, enrollment, etc.
                $table->unsignedBigInteger('action_id')->nullable(); // ID of the action (lesson_id, quiz_id, etc.)
                $table->string('action_model')->nullable(); // Model name (Lesson, Quiz, Course, etc.)
                $table->json('metadata')->nullable(); // Additional data
                $table->timestamps();
                
                $table->index(['user_id', 'created_at']);
                $table->index('action_type');
            });
        }

        // Create badge_criteria_log table for tracking badge qualification attempts
        if (!Schema::hasTable('badge_criteria_log')) {
            Schema::create('badge_criteria_log', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('badge_id')->constrained()->onDelete('cascade');
                $table->boolean('qualified')->default(false);
                $table->json('criteria_data')->nullable(); // Current user data for criteria
                $table->string('reason')->nullable(); // Why they didn't qualify
                $table->timestamps();
                
                $table->index(['user_id', 'badge_id']);
                $table->index('qualified');
            });
        }

        // Create user_level_history table for tracking level changes
        if (!Schema::hasTable('user_level_history')) {
            Schema::create('user_level_history', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('previous_level')->nullable();
                $table->string('new_level');
                $table->integer('points_at_change');
                $table->timestamps();
                
                $table->index(['user_id', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_level_history');
        Schema::dropIfExists('badge_criteria_log');
        Schema::dropIfExists('user_points_history');

        if (Schema::hasTable('user_badges')) {
            Schema::table('user_badges', function (Blueprint $table) {
                if (Schema::hasColumn('user_badges', 'revoked_at')) {
                    $table->dropColumn('revoked_at');
                }
                if (Schema::hasColumn('user_badges', 'is_featured')) {
                    $table->dropColumn('is_featured');
                }
                if (Schema::hasColumn('user_badges', 'progress')) {
                    $table->dropColumn('progress');
                }
            });
        }
    }
};

