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
        Schema::table('quizzes', function (Blueprint $table) {
            // Change enum to only include mcq and alternate
            $table->enum('type', ['mcq', 'alternate'])->default('mcq')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // Revert to include theory
            $table->enum('type', ['mcq', 'alternate', 'theory'])->default('mcq')->change();
        });
    }
};
