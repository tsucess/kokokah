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
            // Rename category_id → curriculum_category_id (only if it exists)
            if (Schema::hasColumn('courses', 'category_id')) {
                $table->renameColumn('category_id', 'curriculum_category_id');
            }

            // Add course_category_id column if not exists
            if (!Schema::hasColumn('courses', 'course_category_id')) {
                $table->unsignedBigInteger('course_category_id')->nullable()->after('curriculum_category_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
             // Rollback rename
            if (Schema::hasColumn('courses', 'curriculum_category_id')) {
                $table->renameColumn('curriculum_category_id', 'category_id');
            }

            // Drop added column
            if (Schema::hasColumn('courses', 'course_category_id')) {
                $table->dropColumn('course_category_id');
            }
        });
    }
};
