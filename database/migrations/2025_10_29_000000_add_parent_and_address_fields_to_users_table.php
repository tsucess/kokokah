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
        // Add missing columns to users table
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                // Add state column if it doesn't exist
                if (!Schema::hasColumn('users', 'state')) {
                    $table->string('state')->nullable()->after('address');
                }

                // Add zipcode column if it doesn't exist
                if (!Schema::hasColumn('users', 'zipcode')) {
                    $table->string('zipcode')->nullable()->after('state');
                }

                // Add parent_first_name column if it doesn't exist
                if (!Schema::hasColumn('users', 'parent_first_name')) {
                    $table->string('parent_first_name')->nullable()->after('zipcode');
                }

                // Add parent_last_name column if it doesn't exist
                if (!Schema::hasColumn('users', 'parent_last_name')) {
                    $table->string('parent_last_name')->nullable()->after('parent_first_name');
                }

                // Add parent_email column if it doesn't exist
                if (!Schema::hasColumn('users', 'parent_email')) {
                    $table->string('parent_email')->nullable()->after('parent_last_name');
                }

                // Add parent_phone column if it doesn't exist
                if (!Schema::hasColumn('users', 'parent_phone')) {
                    $table->string('parent_phone')->nullable()->after('parent_email');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $columns = ['state', 'zipcode', 'parent_first_name', 'parent_last_name', 'parent_email', 'parent_phone'];
                
                foreach ($columns as $column) {
                    if (Schema::hasColumn('users', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};

