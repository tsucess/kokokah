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
        // Fix coupons table - add missing user_limit column only
        Schema::table('coupons', function (Blueprint $table) {
            // Add user_limit column (per-user usage limit)
            $table->integer('user_limit')->nullable()->after('usage_limit_per_user');
        });

        // Fix coupon_usages table - add used_at column
        Schema::table('coupon_usages', function (Blueprint $table) {
            $table->timestamp('used_at')->default(now())->after('discount_amount');
        });

        // Fix learning_paths table - add status column
        Schema::table('learning_paths', function (Blueprint $table) {
            // Add status column that maps to is_published
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->after('is_published');
        });

        // Update existing data to match new schema
        DB::statement("UPDATE learning_paths SET status = CASE WHEN is_published = 1 THEN 'published' ELSE 'draft' END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('user_limit');
        });

        Schema::table('coupon_usages', function (Blueprint $table) {
            $table->dropColumn('used_at');
        });

        Schema::table('learning_paths', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
