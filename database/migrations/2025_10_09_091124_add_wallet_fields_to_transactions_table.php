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
        Schema::table('transactions', function (Blueprint $table) {
            // Add new fields for wallet functionality
            $table->text('description')->nullable()->after('status');
            $table->json('metadata')->nullable()->after('description');
            $table->foreignId('related_user_id')->nullable()->constrained('users')->onDelete('set null')->after('metadata');
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('set null')->after('related_user_id');
            $table->enum('reward_type', ['daily_login', 'study_time', 'course_completion', 'quiz_perfect', 'streak_bonus', 'referral'])->nullable()->after('course_id');

            // Add indexes
            $table->index('related_user_id');
            $table->index('course_id');
            $table->index('reward_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['related_user_id']);
            $table->dropForeign(['course_id']);
            $table->dropIndex(['related_user_id']);
            $table->dropIndex(['course_id']);
            $table->dropIndex(['reward_type']);
            $table->dropColumn(['description', 'metadata', 'related_user_id', 'course_id', 'reward_type']);
        });
    }
};
