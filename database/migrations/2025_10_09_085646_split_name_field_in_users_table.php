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
        Schema::table('users', function (Blueprint $table) {
            // Add first_name and last_name columns
            $table->string('first_name')->nullable()->after('identifier');
            $table->string('last_name')->nullable()->after('first_name');
        });

        // Migrate existing data from name to first_name and last_name
        DB::statement("UPDATE users SET first_name = SUBSTRING_INDEX(name, ' ', 1), last_name = SUBSTRING_INDEX(name, ' ', -1) WHERE name IS NOT NULL");

        Schema::table('users', function (Blueprint $table) {
            // Make first_name and last_name required
            $table->string('first_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();

            // Drop the old name column
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if name column already exists
        if (!Schema::hasColumn('users', 'name')) {
            Schema::table('users', function (Blueprint $table) {
                // Add back the name column
                $table->string('name')->after('id');
            });

            // Migrate data back from first_name and last_name to name
            DB::statement("UPDATE users SET name = CONCAT(first_name, ' ', last_name) WHERE first_name IS NOT NULL AND last_name IS NOT NULL");
        }

        Schema::table('users', function (Blueprint $table) {
            // Drop the split columns if they exist
            if (Schema::hasColumn('users', 'first_name')) {
                $table->dropColumn('first_name');
            }
            if (Schema::hasColumn('users', 'last_name')) {
                $table->dropColumn('last_name');
            }
        });
    }
};
