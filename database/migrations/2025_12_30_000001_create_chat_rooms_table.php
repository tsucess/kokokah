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
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            
            // Basic info
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['general', 'course'])->default('general');
            
            // Course relationship (nullable for general rooms)
            $table->foreignId('course_id')
                  ->nullable()
                  ->constrained('courses')
                  ->onDelete('cascade');
            
            // Creator/Owner
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // UI/UX
            $table->string('background_image')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable()->default('#007bff');
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_archived')->default(false);
            
            // Metadata
            $table->integer('member_count')->default(0);
            $table->integer('message_count')->default(0);
            $table->timestamp('last_message_at')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('type');
            $table->index('course_id');
            $table->index('created_by');
            $table->index('is_active');
            $table->index('created_at');
            $table->unique(['course_id', 'type']); // One chat room per course
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};

