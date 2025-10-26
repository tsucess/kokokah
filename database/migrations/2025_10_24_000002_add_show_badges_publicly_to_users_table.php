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
        // Add show_badges_publicly column to users table if it doesn't exist
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'show_badges_publicly')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('show_badges_publicly')->default(true)->after('is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'show_badges_publicly')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('show_badges_publicly');
            });
        }
    }
};

