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
        // Add subscription_plan_id column to payments table
        if (Schema::hasTable('payments') && !Schema::hasColumn('payments', 'subscription_plan_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->foreignId('subscription_plan_id')->nullable()->after('course_id')->constrained('subscription_plans')->onDelete('set null');
            });
        }

        // Update the enum type to include 'subscription_purchase'
        // For MySQL, we need to modify the column
        if (Schema::hasTable('payments') && DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE payments MODIFY COLUMN type ENUM('wallet_deposit', 'course_purchase', 'subscription_purchase')");
        }
        // For SQLite, we skip this as it doesn't support MODIFY
        // The column will be created with the correct enum in the initial migration
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('payments')) {
            // Revert enum back to original (only for MySQL)
            if (DB::getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE payments MODIFY COLUMN type ENUM('wallet_deposit', 'course_purchase')");
            }

            // Drop the subscription_plan_id column
            if (Schema::hasColumn('payments', 'subscription_plan_id')) {
                Schema::table('payments', function (Blueprint $table) {
                    $table->dropForeign(['subscription_plan_id']);
                    $table->dropColumn('subscription_plan_id');
                });
            }
        }
    }
};

