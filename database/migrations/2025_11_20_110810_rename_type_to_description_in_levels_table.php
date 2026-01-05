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
        // Rename column and convert enum to VARCHAR to allow more flexible values.
        // We use VARCHAR(191) to be compatible with indexed columns in older MySQL setups.

        if (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support CHANGE, so we need to recreate the table
            Schema::table('levels', function (Blueprint $table) {
                $table->string('description')->nullable()->after('name');
            });

            // Copy data from type to description if type exists
            if (Schema::hasColumn('levels', 'type')) {
                DB::statement('UPDATE levels SET description = type WHERE description IS NULL');
                $table = Schema::getConnection()->getSchemaBuilder();
                Schema::table('levels', function (Blueprint $table) {
                    $table->dropColumn('type');
                });
            }
        } else {
            // MySQL and other databases support CHANGE
            DB::statement(
                "ALTER TABLE `levels` CHANGE `type` `description` VARCHAR(191) COLLATE 'utf8mb4_unicode_ci' NOT NULL DEFAULT 'secondary'"
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to enum in the down migration to restore original schema.
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('levels', function (Blueprint $table) {
                $table->enum('type', ['secondary', 'university', 'grade'])->default('secondary')->after('name');
            });

            // Copy data back
            if (Schema::hasColumn('levels', 'description')) {
                DB::statement('UPDATE levels SET type = description');
                Schema::table('levels', function (Blueprint $table) {
                    $table->dropColumn('description');
                });
            }
        } else {
            DB::statement(
                "ALTER TABLE `levels` CHANGE `description` `type` ENUM('secondary','university','grade') COLLATE 'utf8mb4_unicode_ci' NOT NULL DEFAULT 'secondary'"
            );
        }
    }
};
