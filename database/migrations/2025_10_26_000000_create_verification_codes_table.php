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
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('code', 10)->index();
            $table->enum('type', ['email', 'phone', 'password_reset'])->default('email');
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->integer('attempts')->default(0);
            $table->integer('max_attempts')->default(5);
            $table->timestamps();

            // Indexes for faster queries
            $table->index(['user_id', 'type']);
            $table->index(['code', 'type']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_codes');
    }
};

