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
        DB::statement(
            "ALTER TABLE `levels` CHANGE `type` `description` VARCHAR(191) COLLATE 'utf8mb4_unicode_ci' NOT NULL DEFAULT 'secondary'"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to enum in the down migration to restore original schema.
        DB::statement(
            "ALTER TABLE `levels` CHANGE `description` `type` ENUM('secondary','university','grade') COLLATE 'utf8mb4_unicode_ci' NOT NULL DEFAULT 'secondary'"
        );
    }
};
