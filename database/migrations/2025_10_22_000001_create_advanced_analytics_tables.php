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
        // Student Success Predictions Table
        Schema::create('student_success_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->float('success_probability')->default(0.5); // 0-1 scale
            $table->json('risk_factors')->nullable();
            $table->dateTime('predicted_completion_date')->nullable();
            $table->float('confidence_score')->default(0.5); // 0-1 scale
            $table->dateTime('last_updated')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'course_id']);
            $table->index('success_probability');
            $table->index('student_id');
            $table->index('course_id');
        });

        // Cohort Analysis Table
        Schema::create('cohort_analyses', function (Blueprint $table) {
            $table->id();
            $table->string('cohort_name');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('student_count')->default(0);
            $table->float('completion_rate')->default(0); // 0-1 scale
            $table->float('average_score')->default(0); // 0-1 scale
            $table->float('retention_rate')->default(0); // 0-1 scale
            $table->integer('dropout_count')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('start_date');
            $table->index('end_date');
            $table->index('completion_rate');
        });

        // Cohort Students Pivot Table
        Schema::create('cohort_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cohort_analysis_id')->constrained('cohort_analyses')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['cohort_analysis_id', 'user_id']);
        });

        // Engagement Scores Table
        Schema::create('engagement_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->float('score')->default(0); // 0-100 scale
            $table->float('lesson_completion_rate')->default(0); // 0-100 scale
            $table->float('quiz_participation_rate')->default(0); // 0-100 scale
            $table->float('forum_activity_score')->default(0); // 0-100 scale
            $table->float('assignment_submission_rate')->default(0); // 0-100 scale
            $table->float('time_spent_score')->default(0); // 0-100 scale
            $table->dateTime('last_updated')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
            $table->index('score');
            $table->index('user_id');
            $table->index('course_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engagement_scores');
        Schema::dropIfExists('cohort_students');
        Schema::dropIfExists('cohort_analyses');
        Schema::dropIfExists('student_success_predictions');
    }
};

