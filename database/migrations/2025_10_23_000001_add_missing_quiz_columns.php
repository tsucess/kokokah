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
        if (Schema::hasTable('quizzes')) {
            Schema::table('quizzes', function (Blueprint $table) {
                // Add description if it doesn't exist
                if (!Schema::hasColumn('quizzes', 'description')) {
                    $table->text('description')->nullable()->after('title');
                }
                
                // Add time_limit if it doesn't exist
                if (!Schema::hasColumn('quizzes', 'time_limit')) {
                    $table->integer('time_limit')->nullable()->after('description');
                }
                
                // Add is_active if it doesn't exist
                if (!Schema::hasColumn('quizzes', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('passing_score');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('quizzes')) {
            Schema::table('quizzes', function (Blueprint $table) {
                if (Schema::hasColumn('quizzes', 'description')) {
                    $table->dropColumn('description');
                }
                if (Schema::hasColumn('quizzes', 'time_limit')) {
                    $table->dropColumn('time_limit');
                }
                if (Schema::hasColumn('quizzes', 'is_active')) {
                    $table->dropColumn('is_active');
                }
            });
        }
    }
};

