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
        Schema::create('learning_path_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('learning_path_id')->constrained()->onDelete('cascade');
            $table->timestamp('enrolled_at');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->foreignId('current_course_id')->nullable()->constrained('courses')->onDelete('set null');
            $table->enum('status', ['active', 'completed', 'dropped', 'paused'])->default('active');
            $table->decimal('completion_time_hours', 8, 2)->nullable();
            $table->boolean('certificate_issued')->default(false);
            $table->foreignId('certificate_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamp('dropped_at')->nullable();
            $table->text('drop_reason')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'learning_path_id']);
            $table->index(['learning_path_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['enrolled_at', 'completed_at']);
            $table->unique(['user_id', 'learning_path_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_path_enrollments');
    }
};
