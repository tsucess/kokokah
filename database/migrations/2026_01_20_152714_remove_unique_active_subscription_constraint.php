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
        Schema::table('user_subscriptions', function (Blueprint $table) {
            // Drop the unique constraint that prevented multiple active subscriptions to the same plan
            // Users should be able to subscribe to the same plan for different courses
            if (Schema::hasTable('user_subscriptions')) {
                try {
                    $table->dropUnique('unique_active_subscription');
                } catch (\Exception $e) {
                    // Constraint might not exist, continue
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            // Restore the unique constraint
            $table->unique(['user_id', 'subscription_plan_id', 'status'], 'unique_active_subscription');
        });
    }
};
