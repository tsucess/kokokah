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
        Schema::table('topics', function (Blueprint $table) {
            // Add term_id to organize topics by term
            if (!Schema::hasColumn('topics', 'term_id')) {
                $table->unsignedBigInteger('term_id')->nullable()->after('course_id');

                // Add foreign key constraint
                $table->foreign('term_id')
                      ->references('id')->on('terms')
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            // Drop foreign key and column
            if (Schema::hasColumn('topics', 'term_id')) {
                $table->dropForeign(['term_id']);
                $table->dropColumn('term_id');
            }
        });
    }
};
