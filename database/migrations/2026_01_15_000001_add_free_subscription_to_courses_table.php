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
        Schema::table('courses', function (Blueprint $table) {
            // Add free_subscription column to mark courses that should be in free subscription plan
            if (!Schema::hasColumn('courses', 'free_subscription')) {
                $table->boolean('free_subscription')->default(false)->after('free');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'free_subscription')) {
                $table->dropColumn('free_subscription');
            }
        });
    }
};

