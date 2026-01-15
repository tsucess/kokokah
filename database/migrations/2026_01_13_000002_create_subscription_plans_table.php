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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('duration'); // e.g., 1, 7, 30, 365
            $table->enum('duration_type', ['free', 'daily', 'weekly', 'monthly', 'yearly'])->default('monthly');
            $table->json('features')->nullable(); // Store features as JSON array
            $table->boolean('is_active')->default(true);
            $table->integer('max_users')->nullable(); // Limit number of users for this plan
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('is_active');
            $table->index('duration_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};

