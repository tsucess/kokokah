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
        // Create forum_topic_subscribers pivot table if it doesn't exist
        if (!Schema::hasTable('forum_topic_subscribers')) {
            Schema::create('forum_topic_subscribers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('topic_id')->constrained('forum_topics')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->timestamp('subscribed_at')->useCurrent();
                $table->timestamps();

                // Unique constraint to prevent duplicate subscriptions
                $table->unique(['topic_id', 'user_id']);
                
                // Indexes for faster queries
                $table->index('topic_id');
                $table->index('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_topic_subscribers');
    }
};

