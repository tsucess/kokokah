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
        // Add missing columns to answers table
        if (Schema::hasTable('answers')) {
            Schema::table('answers', function (Blueprint $table) {
                // Add points_earned column if it doesn't exist
                if (!Schema::hasColumn('answers', 'points_earned')) {
                    $table->integer('points_earned')->default(0)->after('score');
                }
                
                // Add points_possible column if it doesn't exist
                if (!Schema::hasColumn('answers', 'points_possible')) {
                    $table->integer('points_possible')->default(0)->after('points_earned');
                }
                
                // Add attempt_number column if it doesn't exist
                if (!Schema::hasColumn('answers', 'attempt_number')) {
                    $table->integer('attempt_number')->default(1)->after('points_possible');
                }
                
                // Add is_correct column if it doesn't exist
                if (!Schema::hasColumn('answers', 'is_correct')) {
                    $table->boolean('is_correct')->default(false)->after('attempt_number');
                }
                
                // Add answer_text column if it doesn't exist (rename from answer)
                if (!Schema::hasColumn('answers', 'answer_text') && Schema::hasColumn('answers', 'answer')) {
                    $table->renameColumn('answer', 'answer_text');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('answers')) {
            Schema::table('answers', function (Blueprint $table) {
                // Drop the new columns
                if (Schema::hasColumn('answers', 'points_earned')) {
                    $table->dropColumn('points_earned');
                }
                
                if (Schema::hasColumn('answers', 'points_possible')) {
                    $table->dropColumn('points_possible');
                }
                
                if (Schema::hasColumn('answers', 'attempt_number')) {
                    $table->dropColumn('attempt_number');
                }
                
                if (Schema::hasColumn('answers', 'is_correct')) {
                    $table->dropColumn('is_correct');
                }
                
                // Rename answer_text back to answer
                if (Schema::hasColumn('answers', 'answer_text') && !Schema::hasColumn('answers', 'answer')) {
                    $table->renameColumn('answer_text', 'answer');
                }
            });
        }
    }
};

