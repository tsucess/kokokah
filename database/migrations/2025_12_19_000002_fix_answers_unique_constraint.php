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
        // Fix the unique constraint on answers table to allow multiple attempts
        if (Schema::hasTable('answers')) {
            Schema::table('answers', function (Blueprint $table) {
                // Drop the old unique constraint that prevents multiple attempts
                if (Schema::hasIndex('answers', 'answers_student_id_question_id_unique')) {
                    $table->dropUnique(['student_id', 'question_id']);
                }
                
                // Add new unique constraint that includes attempt_number
                // This allows the same student to answer the same question in different attempts
                $table->unique(['student_id', 'question_id', 'attempt_number']);
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
                // Drop the new unique constraint
                if (Schema::hasIndex('answers', 'answers_student_id_question_id_attempt_number_unique')) {
                    $table->dropUnique(['student_id', 'question_id', 'attempt_number']);
                }
                
                // Restore the old unique constraint
                $table->unique(['student_id', 'question_id']);
            });
        }
    }
};

