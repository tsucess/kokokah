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
        Schema::create('chat_room_users', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('chat_room_id')
                  ->constrained('chat_rooms')
                  ->onDelete('cascade');
            
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // Role in the chat room
            $table->enum('role', ['member', 'moderator', 'admin'])->default('member');
            
            // Membership status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_muted')->default(false);
            $table->boolean('is_pinned')->default(false);
            
            // Read tracking
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('last_read_at')->nullable();
            $table->integer('unread_count')->default(0);
            
            // Notification preferences
            $table->enum('notification_level', ['all', 'mentions', 'none'])->default('all');
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->unique(['chat_room_id', 'user_id']); // Prevent duplicate memberships
            $table->index('user_id');
            $table->index('role');
            $table->index('is_active');
            $table->index('last_read_at');
            $table->index(['chat_room_id', 'is_active']); // Active members in room
            $table->index(['user_id', 'is_active']); // User's active rooms
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_room_users');
    }
};

