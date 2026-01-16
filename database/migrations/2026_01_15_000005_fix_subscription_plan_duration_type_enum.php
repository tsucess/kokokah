<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Ensures the duration_type enum includes 'free' value.
     * This migration fixes any issues with the initial enum definition.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // Modify the enum to ensure 'free' is included
            DB::statement("ALTER TABLE subscription_plans MODIFY COLUMN duration_type ENUM('free', 'daily', 'weekly', 'monthly', 'yearly') DEFAULT 'monthly'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // Revert to original enum (without 'free')
            DB::statement("ALTER TABLE subscription_plans MODIFY COLUMN duration_type ENUM('daily', 'weekly', 'monthly', 'yearly') DEFAULT 'monthly'");
        }
    }
};

