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
        // Add points and explanation columns to questions table if they don't exist
        if (Schema::hasTable('questions')) {
            Schema::table('questions', function (Blueprint $table) {
                if (!Schema::hasColumn('questions', 'points')) {
                    $table->integer('points')->default(1)->after('type');
                }
                
                if (!Schema::hasColumn('questions', 'explanation')) {
                    $table->text('explanation')->nullable()->after('correct_answer');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('questions')) {
            Schema::table('questions', function (Blueprint $table) {
                if (Schema::hasColumn('questions', 'points')) {
                    $table->dropColumn('points');
                }
                
                if (Schema::hasColumn('questions', 'explanation')) {
                    $table->dropColumn('explanation');
                }
            });
        }
    }
};

