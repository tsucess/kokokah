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
        Schema::create('message_reactions', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('chat_message_id')
                  ->constrained('chat_messages')
                  ->onDelete('cascade');
            
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // Reaction emoji/type
            $table->string('reaction'); // e.g., '👍', '❤️', 'like', 'love'
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->unique(['chat_message_id', 'user_id', 'reaction']); // One reaction per user per message
            $table->index('user_id');
            $table->index('reaction');
            $table->index(['chat_message_id', 'reaction']); // Reactions on message
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_reactions');
    }
};

