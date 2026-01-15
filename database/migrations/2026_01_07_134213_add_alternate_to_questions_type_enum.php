<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the type column in questions table to include 'alternate' in the ENUM
        if (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support MODIFY, so we skip this for SQLite
            // The column will be created with the correct enum in the initial migration
        } else {
            DB::statement("ALTER TABLE questions MODIFY COLUMN type ENUM('mcq', 'alternate', 'theory') DEFAULT 'mcq'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support MODIFY, so we skip this for SQLite
        } else {
            // First, update any 'alternate' type questions to 'mcq' before removing the enum value
            DB::statement("UPDATE questions SET type = 'mcq' WHERE type = 'alternate'");
            DB::statement("ALTER TABLE questions MODIFY COLUMN type ENUM('mcq', 'theory') DEFAULT 'mcq'");
        }
    }
};
