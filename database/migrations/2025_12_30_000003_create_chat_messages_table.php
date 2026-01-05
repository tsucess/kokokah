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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('chat_room_id')
                  ->constrained('chat_rooms')
                  ->onDelete('cascade');
            
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // Message content
            $table->longText('content');
            $table->enum('type', ['text', 'image', 'file', 'system'])->default('text');
            
            // Reply to another message (threading)
            $table->foreignId('reply_to_id')
                  ->nullable()
                  ->constrained('chat_messages')
                  ->onDelete('set null');
            
            // Editing
            $table->longText('edited_content')->nullable();
            $table->timestamp('edited_at')->nullable();
            
            // Reactions count (denormalized for performance)
            $table->integer('reaction_count')->default(0);
            
            // Status
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_deleted')->default(false);
            
            // Metadata
            $table->json('metadata')->nullable(); // For storing file info, etc.
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('chat_room_id');
            $table->index('user_id');
            $table->index('created_at');
            $table->index(['chat_room_id', 'created_at']); // Messages in room
            $table->index(['user_id', 'created_at']); // User's messages
            $table->index('is_pinned');
            $table->index('reply_to_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};

