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
        // Only run on MySQL - SQLite doesn't support MODIFY
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE quizzes MODIFY COLUMN type ENUM('mcq', 'alternate', 'theory') DEFAULT 'mcq'");
        }
        // SQLite doesn't need this as it doesn't enforce ENUMs
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only run on MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE quizzes MODIFY COLUMN type ENUM('mcq', 'theory') DEFAULT 'mcq'");
        }
    }
};

