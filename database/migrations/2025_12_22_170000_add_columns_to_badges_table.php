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
            
            // Add indexes if they don't exist
            if (Schema::hasTable('badges')) {
                Schema::table('badges', function (Blueprint $table) {
                    // Check and add indexes using Schema::hasIndex
                    if (!Schema::hasIndex('badges', 'badges_category_index')) {
                        $table->index('category', 'badges_category_index');
                    }

                    if (!Schema::hasIndex('badges', 'badges_type_index')) {
                        $table->index('type', 'badges_type_index');
                    }

                    if (!Schema::hasIndex('badges', 'badges_is_active_index')) {
                        $table->index('is_active', 'badges_is_active_index');
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

