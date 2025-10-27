<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // User badges (many-to-many)
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('badge_id')->constrained()->onDelete('cascade');
            $table->timestamp('earned_at');
            $table->timestamps();
            
            $table->unique(['user_id', 'badge_id']); // Prevent duplicate badges
            $table->index(['user_id', 'earned_at']); // User's badge timeline
        });

        // Course prerequisites (self-referencing many-to-many)
        Schema::create('course_prerequisites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('prerequisite_course_id')->constrained('courses')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['course_id', 'prerequisite_course_id']);
            $table->index('course_id');
        });

        // Course tags for better categorization
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('color', 7)->default('#3B82F6'); // Hex color
            $table->timestamps();
        });

        Schema::create('course_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['course_id', 'tag_id']);
        });

        // User favorites/wishlist
        Schema::create('user_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['user_id', 'course_id']);
            $table->index('user_id');
        });

        // Lesson completion tracking
        Schema::create('lesson_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->timestamp('completed_at');
            $table->integer('time_spent')->default(0); // seconds
            $table->timestamps();
            
            $table->unique(['user_id', 'lesson_id']);
            $table->index(['user_id', 'completed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_completions');
        Schema::dropIfExists('user_favorites');
        Schema::dropIfExists('course_tags');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('course_prerequisites');
        Schema::dropIfExists('user_badges');
    }
};
