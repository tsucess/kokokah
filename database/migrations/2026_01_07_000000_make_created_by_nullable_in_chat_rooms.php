<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Makes the created_by field nullable in chat_rooms table to allow
     * creating chatrooms during migration before admin users exist.
     */
    public function up(): void
    {
        Schema::table('chat_rooms', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['created_by']);
            
            // Modify the column to be nullable
            $table->foreignId('created_by')
                  ->nullable()
                  ->change()
                  ->constrained('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_rooms', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['created_by']);
            
            // Revert to non-nullable with cascade delete
            $table->foreignId('created_by')
                  ->change()
                  ->constrained('users')
                  ->onDelete('cascade');
        });
    }
};

