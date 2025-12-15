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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('card_holder_name');
            $table->text('card_number'); // Encrypted
            $table->string('card_last_four', 4); // Last 4 digits for display
            $table->string('expiry_date'); // MM/YY format
            $table->text('cvv')->nullable(); // Encrypted, optional
            $table->string('card_type')->nullable(); // visa, mastercard, etc.
            $table->boolean('is_default')->default(false);
            $table->boolean('is_saved')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
