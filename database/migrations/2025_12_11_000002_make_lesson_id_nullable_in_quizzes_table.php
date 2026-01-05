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
            // Make lesson_id nullable to support topic-level quizzes
            // Topic quizzes don't have a lesson_id, only a topic_id
            $table->unsignedBigInteger('lesson_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // Revert lesson_id to required
            $table->unsignedBigInteger('lesson_id')->nullable(false)->change();
        });
    }
};

