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
        if (Schema::hasTable('enrollments') && !Schema::hasColumn('enrollments', 'amount_paid')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->decimal('amount_paid', 10, 2)->default(0)->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('enrollments') && Schema::hasColumn('enrollments', 'amount_paid')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->dropColumn('amount_paid');
            });
        }
    }
};

