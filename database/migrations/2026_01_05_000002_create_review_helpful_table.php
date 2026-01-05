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
        // Only create if table doesn't exist
        if (!Schema::hasTable('review_helpful')) {
            Schema::create('review_helpful', function (Blueprint $table) {
                $table->id();
                $table->foreignId('review_id')
                      ->constrained('course_reviews')
                      ->onDelete('cascade');
                $table->foreignId('user_id')
                      ->constrained('users')
                      ->onDelete('cascade');
                $table->timestamps();
                
                // Prevent duplicate helpful marks
                $table->unique(['review_id', 'user_id']);
                
                // Add indexes for queries
                $table->index('review_id');
                $table->index('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_helpful');
    }
};

