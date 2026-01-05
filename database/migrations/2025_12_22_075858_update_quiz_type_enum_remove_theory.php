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
        if (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support changing enum columns
            // The column will be created with the correct enum in the initial migration
        } else {
            Schema::table('quizzes', function (Blueprint $table) {
                // Change enum to only include mcq and alternate
                $table->enum('type', ['mcq', 'alternate'])->default('mcq')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support changing enum columns
        } else {
            Schema::table('quizzes', function (Blueprint $table) {
                // Revert to include theory
                $table->enum('type', ['mcq', 'alternate', 'theory'])->default('mcq')->change();
            });
        }
    }
};
