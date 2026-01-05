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
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('points')->default(0);
            $table->text('criteria')->nullable();
            $table->enum('category', ['learning', 'achievement', 'social', 'special'])->default('learning');
            $table->enum('type', ['lesson_completion', 'topic_completion', 'course_completion', 'course_enrollment', 'quiz_mastery', 'points', 'speed', 'time', 'streak', 'participation', 'instructor', 'milestone'])->default('lesson_completion');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('category');
            $table->index('type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
