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
            // Add title column if it doesn't exist
            if (!Schema::hasColumn('course_reviews', 'title')) {
                $table->string('title')->nullable()->after('rating');
            }

            // Add comment column if it doesn't exist
            if (!Schema::hasColumn('course_reviews', 'comment')) {
                $table->text('comment')->nullable()->after('title');
            }

            // Add pros column if it doesn't exist
            if (!Schema::hasColumn('course_reviews', 'pros')) {
                $table->json('pros')->nullable()->after('comment');
            }

            // Add cons column if it doesn't exist
            if (!Schema::hasColumn('course_reviews', 'cons')) {
                $table->json('cons')->nullable()->after('pros');
            }

            // Add status column if it doesn't exist
            if (!Schema::hasColumn('course_reviews', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])
                      ->default('approved')->after('cons');
            }

            // Add helpful_count column if it doesn't exist
            if (!Schema::hasColumn('course_reviews', 'helpful_count')) {
                $table->integer('helpful_count')->default(0)->after('status');
            }

            // Add moderated_by column if it doesn't exist
            if (!Schema::hasColumn('course_reviews', 'moderated_by')) {
                $table->unsignedBigInteger('moderated_by')->nullable()->after('helpful_count');
            }

            // Add moderated_at column if it doesn't exist
            if (!Schema::hasColumn('course_reviews', 'moderated_at')) {
                $table->timestamp('moderated_at')->nullable()->after('moderated_by');
            }

            // Add rejection_reason column if it doesn't exist
            if (!Schema::hasColumn('course_reviews', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('moderated_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_reviews', function (Blueprint $table) {
            // Drop foreign key first if it exists
            if (Schema::hasColumn('course_reviews', 'moderated_by')) {
                try {
                    $table->dropForeign(['moderated_by']);
                } catch (\Exception $e) {
                    // Foreign key might not exist
                }
            }

            $columns = ['title', 'comment', 'pros', 'cons', 'status',
                       'helpful_count', 'moderated_by', 'moderated_at', 'rejection_reason'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('course_reviews', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
