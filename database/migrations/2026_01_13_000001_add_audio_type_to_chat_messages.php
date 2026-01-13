<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds 'audio' type to the chat_messages type enum to support audio message recording.
     */
    public function up(): void
    {
        // For MySQL, we need to modify the ENUM column
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE chat_messages MODIFY COLUMN type ENUM('text', 'image', 'audio', 'file', 'system') DEFAULT 'text'");
        }
        // For other databases, we can use the Schema builder
        else {
            Schema::table('chat_messages', function (Blueprint $table) {
                // This is a fallback for databases that don't support ENUM
                // In production, you should handle this appropriately
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the original ENUM without 'audio'
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE chat_messages MODIFY COLUMN type ENUM('text', 'image', 'file', 'system') DEFAULT 'text'");
        }
    }
};

