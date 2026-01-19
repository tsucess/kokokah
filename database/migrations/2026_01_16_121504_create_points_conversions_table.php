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
        Schema::create('points_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('points_converted')->comment('Number of points converted');
            $table->decimal('wallet_amount', 10, 2)->comment('Amount added to wallet (₦)');
            $table->decimal('conversion_ratio', 5, 2)->default(10)->comment('Conversion ratio used (10 points = 1 wallet unit)');
            $table->string('reference')->unique()->comment('Unique reference for this conversion');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable()->comment('Additional metadata about the conversion');
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points_conversions');
    }
};
