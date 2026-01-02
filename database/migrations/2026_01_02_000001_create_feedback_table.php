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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('feedback_type', ['bug', 'feature_request', 'general', 'other'])->default('general');
            $table->integer('rating')->nullable()->comment('1-5 star rating');
            $table->string('subject')->nullable();
            $table->longText('message');
            $table->enum('status', ['new', 'read', 'in_progress', 'resolved'])->default('new');
            $table->text('admin_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index(['user_id', 'created_at']);
            $table->index(['feedback_type', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};

