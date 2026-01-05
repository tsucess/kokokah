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
        Schema::table('course_reviews', function (Blueprint $table) {
            // Add review content fields if they don't exist
            if (!Schema::hasColumn('course_reviews', 'title')) {
                $table->string('title')->nullable()->after('rating');
            }
            
            if (!Schema::hasColumn('course_reviews', 'comment')) {
                $table->text('comment')->nullable()->after('title');
            }
            
            if (!Schema::hasColumn('course_reviews', 'pros')) {
                $table->json('pros')->nullable()->after('comment');
            }
            
            if (!Schema::hasColumn('course_reviews', 'cons')) {
                $table->json('cons')->nullable()->after('pros');
            }
            
            // Add moderation fields
            if (!Schema::hasColumn('course_reviews', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])
                      ->default('approved')->after('cons');
            }
            
            if (!Schema::hasColumn('course_reviews', 'helpful_count')) {
                $table->integer('helpful_count')->default(0)->after('status');
            }
            
            if (!Schema::hasColumn('course_reviews', 'moderated_by')) {
                $table->unsignedBigInteger('moderated_by')->nullable()->after('helpful_count');
            }
            
            if (!Schema::hasColumn('course_reviews', 'moderated_at')) {
                $table->timestamp('moderated_at')->nullable()->after('moderated_by');
            }
            
            if (!Schema::hasColumn('course_reviews', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('moderated_at');
            }
            
            // Add indexes
            if (!Schema::hasIndex('course_reviews', 'idx_status')) {
                $table->index('status', 'idx_status');
            }
            
            if (!Schema::hasIndex('course_reviews', 'idx_course_status')) {
                $table->index(['course_id', 'status'], 'idx_course_status');
            }
            
            // Add foreign key for moderated_by
            if (!Schema::hasColumn('course_reviews', 'moderated_by_fk')) {
                try {
                    $table->foreign('moderated_by')
                          ->references('id')->on('users')
                          ->onDelete('set null');
                } catch (\Exception $e) {
                    // Foreign key might already exist
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_reviews', function (Blueprint $table) {
            // Drop foreign key if exists
            try {
                $table->dropForeign(['moderated_by']);
            } catch (\Exception $e) {
                // Foreign key doesn't exist
            }
            
            // Drop columns if they exist
            $columns = ['title', 'comment', 'pros', 'cons', 'status', 
                       'helpful_count', 'moderated_by', 'moderated_at', 'rejection_reason'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('course_reviews', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Drop indexes
            if (Schema::hasIndex('course_reviews', 'idx_status')) {
                $table->dropIndex('idx_status');
            }
            
            if (Schema::hasIndex('course_reviews', 'idx_course_status')) {
                $table->dropIndex('idx_course_status');
            }
        });
    }
};

