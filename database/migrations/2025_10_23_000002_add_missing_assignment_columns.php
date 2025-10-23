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
        if (Schema::hasTable('assignments')) {
            Schema::table('assignments', function (Blueprint $table) {
                // Add description if it doesn't exist
                if (!Schema::hasColumn('assignments', 'description')) {
                    $table->text('description')->nullable()->after('title');
                }
                
                // Add deadline if it doesn't exist
                if (!Schema::hasColumn('assignments', 'deadline')) {
                    $table->timestamp('deadline')->nullable()->after('description');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('assignments')) {
            Schema::table('assignments', function (Blueprint $table) {
                if (Schema::hasColumn('assignments', 'description')) {
                    $table->dropColumn('description');
                }
                if (Schema::hasColumn('assignments', 'deadline')) {
                    $table->dropColumn('deadline');
                }
            });
        }
    }
};

