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
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('attempt_number');
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->decimal('score', 8, 2)->default(0);
            $table->decimal('max_score', 8, 2)->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->boolean('passed')->default(false);
            $table->integer('time_taken')->nullable(); // in seconds
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('answers_data')->nullable(); // Store answers snapshot
            $table->enum('status', ['in_progress', 'completed', 'abandoned'])->default('in_progress');
            $table->timestamps();

            // Indexes
            $table->index(['quiz_id', 'user_id']);
            $table->index(['user_id', 'started_at']);
            $table->index(['quiz_id', 'attempt_number']);
            $table->index(['status', 'started_at']);
            $table->unique(['quiz_id', 'user_id', 'attempt_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
