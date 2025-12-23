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
        // Add missing columns to badges table if they don't exist
        if (Schema::hasTable('badges')) {
            Schema::table('badges', function (Blueprint $table) {
                // Add description if it doesn't exist
                if (!Schema::hasColumn('badges', 'description')) {
                    $table->text('description')->nullable()->after('name');
                }
                
                // Add points if it doesn't exist
                if (!Schema::hasColumn('badges', 'points')) {
                    $table->integer('points')->default(0)->after('icon');
                }
                
                // Add category if it doesn't exist
                if (!Schema::hasColumn('badges', 'category')) {
                    $table->enum('category', ['learning', 'achievement', 'social', 'special'])->default('learning')->after('criteria');
                }
                
                // Add type if it doesn't exist
                if (!Schema::hasColumn('badges', 'type')) {
                    $table->enum('type', ['lesson_completion', 'topic_completion', 'course_completion', 'course_enrollment', 'quiz_mastery', 'points', 'speed', 'time', 'streak', 'participation', 'instructor', 'milestone'])->default('lesson_completion')->after('category');
                }
                
                // Add is_active if it doesn't exist
                if (!Schema::hasColumn('badges', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('type');
                }
                
                // Add created_by if it doesn't exist
                if (!Schema::hasColumn('badges', 'created_by')) {
                    $table->unsignedBigInteger('created_by')->nullable()->after('is_active');
                }
            });
            
            // Add indexes if they don't exist (MySQL only)
            if (Schema::hasTable('badges') && DB::getDriverName() === 'mysql') {
                Schema::table('badges', function (Blueprint $table) {
                    // Check and add indexes
                    $indexExists = DB::select("SHOW INDEX FROM badges WHERE Key_name = 'badges_category_index'");
                    if (empty($indexExists)) {
                        $table->index('category');
                    }

                    $indexExists = DB::select("SHOW INDEX FROM badges WHERE Key_name = 'badges_type_index'");
                    if (empty($indexExists)) {
                        $table->index('type');
                    }

                    $indexExists = DB::select("SHOW INDEX FROM badges WHERE Key_name = 'badges_is_active_index'");
                    if (empty($indexExists)) {
                        $table->index('is_active');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('badges')) {
            Schema::table('badges', function (Blueprint $table) {
                if (Schema::hasColumn('badges', 'description')) {
                    $table->dropColumn('description');
                }
                if (Schema::hasColumn('badges', 'points')) {
                    $table->dropColumn('points');
                }
                if (Schema::hasColumn('badges', 'category')) {
                    $table->dropColumn('category');
                }
                if (Schema::hasColumn('badges', 'type')) {
                    $table->dropColumn('type');
                }
                if (Schema::hasColumn('badges', 'is_active')) {
                    $table->dropColumn('is_active');
                }
                if (Schema::hasColumn('badges', 'created_by')) {
                    $table->dropColumn('created_by');
                }
            });
        }
    }
};

