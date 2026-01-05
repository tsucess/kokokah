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
        // Modify the type column to include 'alternate' in the ENUM
        if (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support MODIFY, so we skip this for SQLite
            // The column will be created with the correct enum in the initial migration
        } else {
            DB::statement("ALTER TABLE quizzes MODIFY COLUMN type ENUM('mcq', 'alternate', 'theory') DEFAULT 'mcq'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original ENUM without 'alternate'
        if (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support MODIFY, so we skip this for SQLite
        } else {
            DB::statement("ALTER TABLE quizzes MODIFY COLUMN type ENUM('mcq', 'theory') DEFAULT 'mcq'");
        }
    }
};

