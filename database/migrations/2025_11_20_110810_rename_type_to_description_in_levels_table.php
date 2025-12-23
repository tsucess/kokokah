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
        // Only run on MySQL - SQLite doesn't support CHANGE
        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE `levels` CHANGE `type` `description` VARCHAR(191) COLLATE 'utf8mb4_unicode_ci' NOT NULL DEFAULT 'secondary'"
            );
        } elseif (DB::getDriverName() === 'sqlite') {
            // For SQLite, we need to recreate the table
            Schema::table('levels', function (Blueprint $table) {
                if (Schema::hasColumn('levels', 'type')) {
                    // SQLite doesn't support renaming columns directly, so we'll add description and drop type
                    $table->string('description')->default('secondary')->after('name');
                }
            });

            // Copy data from type to description
            DB::statement("UPDATE levels SET description = type WHERE type IS NOT NULL");

            // Drop the old column
            Schema::table('levels', function (Blueprint $table) {
                if (Schema::hasColumn('levels', 'type')) {
                    $table->dropColumn('type');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE `levels` CHANGE `description` `type` ENUM('secondary','university','grade') COLLATE 'utf8mb4_unicode_ci' NOT NULL DEFAULT 'secondary'"
            );
        } elseif (DB::getDriverName() === 'sqlite') {
            Schema::table('levels', function (Blueprint $table) {
                if (!Schema::hasColumn('levels', 'type')) {
                    $table->enum('type', ['secondary', 'university', 'grade'])->default('secondary')->after('name');
                }
            });

            // Copy data back
            DB::statement("UPDATE levels SET type = description WHERE description IS NOT NULL");

            // Drop description
            Schema::table('levels', function (Blueprint $table) {
                if (Schema::hasColumn('levels', 'description')) {
                    $table->dropColumn('description');
                }
            });
        }
    }
};
