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
        // Update all existing quizzes to have max_attempts = 3
        DB::table('quizzes')->update(['max_attempts' => 3]);

        // Update the default value for new quizzes
        Schema::table('quizzes', function (Blueprint $table) {
            $table->integer('max_attempts')->default(3)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to default of 1
        DB::table('quizzes')->update(['max_attempts' => 1]);

        Schema::table('quizzes', function (Blueprint $table) {
            $table->integer('max_attempts')->default(1)->change();
        });
    }
};
