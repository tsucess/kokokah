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
        // Create forum_posts table if it doesn't exist
        if (!Schema::hasTable('forum_posts')) {
            Schema::create('forum_posts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('topic_id')->constrained('forum_topics')->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('content');
                $table->foreignId('parent_id')->nullable()->constrained('forum_posts')->onDelete('cascade');
                $table->enum('status', ['active', 'hidden', 'deleted'])->default('active');
                $table->timestamp('edited_at')->nullable();
                $table->foreignId('edited_by')->nullable()->constrained('users')->onDelete('set null');
                $table->integer('likes_count')->default(0);
                $table->boolean('is_solution')->default(false);
                $table->timestamps();

                $table->index(['topic_id', 'status']);
                $table->index('user_id');
                $table->index('parent_id');
            });
        }

        // Create forum_post_likes table if it doesn't exist
        if (!Schema::hasTable('forum_post_likes')) {
            Schema::create('forum_post_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->constrained('forum_posts')->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                $table->unique(['post_id', 'user_id']);
                $table->index('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_post_likes');
        Schema::dropIfExists('forum_posts');
    }
};

