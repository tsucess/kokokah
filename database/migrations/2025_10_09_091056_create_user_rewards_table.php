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
        Schema::create('user_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('reward_type', ['daily_login', 'study_time', 'course_completion', 'quiz_perfect', 'streak_bonus', 'referral']);
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->integer('streak_count')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'reward_type', 'date']);
            $table->index(['user_id', 'date']);
            $table->index('reward_type');

            // Unique constraint to prevent duplicate rewards
            $table->unique(['user_id', 'reward_type', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_rewards');
    }
};
