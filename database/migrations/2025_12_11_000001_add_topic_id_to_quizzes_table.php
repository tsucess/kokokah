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
        Schema::table('quizzes', function (Blueprint $table) {
            // Add topic_id to allow quizzes to be attached to topics
            // This allows quizzes to be used for both topic-level and lesson-level assessment
            if (!Schema::hasColumn('quizzes', 'topic_id')) {
                $table->unsignedBigInteger('topic_id')->nullable()->after('lesson_id');

                // Add foreign key constraint
                $table->foreign('topic_id')
                      ->references('id')->on('topics')
                      ->onDelete('cascade');
            }

            // Add slug for reference purposes (to link back to lesson/topic)
            if (!Schema::hasColumn('quizzes', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            if (Schema::hasColumn('quizzes', 'topic_id')) {
                $table->dropForeign(['topic_id']);
                $table->dropColumn('topic_id');
            }
            if (Schema::hasColumn('quizzes', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};

