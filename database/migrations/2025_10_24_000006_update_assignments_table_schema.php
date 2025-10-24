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
        // Update assignments table to match controller expectations
        if (Schema::hasTable('assignments')) {
            // Check which columns exist before adding
            $columns = Schema::getColumnListing('assignments');

            Schema::table('assignments', function (Blueprint $table) use ($columns) {
                // Make instructions nullable if it exists
                if (in_array('instructions', $columns)) {
                    $table->text('instructions')->nullable()->change();
                }

                // Add description if it doesn't exist
                if (!in_array('description', $columns)) {
                    $table->text('description')->nullable()->after('title');
                }

                // Add due_date if it doesn't exist (rename from deadline if needed)
                if (!in_array('due_date', $columns) && in_array('deadline', $columns)) {
                    $table->renameColumn('deadline', 'due_date');
                } elseif (!in_array('due_date', $columns)) {
                    $table->timestamp('due_date')->nullable()->after('instructions');
                }

                // Add max_points if it doesn't exist
                if (!in_array('max_points', $columns)) {
                    $table->integer('max_points')->default(100)->nullable();
                }

                // Add submission_type if it doesn't exist
                if (!in_array('submission_type', $columns)) {
                    $table->enum('submission_type', ['text', 'file', 'both'])->default('both')->nullable();
                }

                // Add late_submission_penalty if it doesn't exist
                if (!in_array('late_submission_penalty', $columns)) {
                    $table->integer('late_submission_penalty')->default(0)->nullable();
                }

                // Add allow_late_submissions if it doesn't exist
                if (!in_array('allow_late_submissions', $columns)) {
                    $table->boolean('allow_late_submissions')->default(false)->nullable();
                }

                // Add attachments if it doesn't exist
                if (!in_array('attachments', $columns)) {
                    $table->json('attachments')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('assignments')) {
            Schema::table('assignments', function (Blueprint $table) {
                // Drop new columns
                $columnsToCheck = ['description', 'max_points', 'submission_type', 'late_submission_penalty', 'allow_late_submissions', 'attachments'];
                foreach ($columnsToCheck as $column) {
                    if (Schema::hasColumn('assignments', $column)) {
                        $table->dropColumn($column);
                    }
                }
                
                // Rename due_date back to deadline if needed
                if (Schema::hasColumn('assignments', 'due_date') && !Schema::hasColumn('assignments', 'deadline')) {
                    $table->renameColumn('due_date', 'deadline');
                }
            });
        }
    }
};

