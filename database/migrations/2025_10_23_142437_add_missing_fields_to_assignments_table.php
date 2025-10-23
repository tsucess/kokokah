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
        Schema::table('assignments', function (Blueprint $table) {
            // Add submission_type if it doesn't exist
            if (!Schema::hasColumn('assignments', 'submission_type')) {
                $table->enum('submission_type', ['text', 'file', 'both'])->default('file')->after('max_file_size_mb');
            }

            // Add max_points if it doesn't exist
            if (!Schema::hasColumn('assignments', 'max_points')) {
                $table->integer('max_points')->default(100)->after('submission_type');
            }

            // Add allow_late_submissions if it doesn't exist
            if (!Schema::hasColumn('assignments', 'allow_late_submissions')) {
                $table->boolean('allow_late_submissions')->default(false)->after('max_points');
            }

            // Add late_submission_penalty if it doesn't exist
            if (!Schema::hasColumn('assignments', 'late_submission_penalty')) {
                $table->integer('late_submission_penalty')->default(0)->after('allow_late_submissions');
            }

            // Add attachments if it doesn't exist
            if (!Schema::hasColumn('assignments', 'attachments')) {
                $table->json('attachments')->nullable()->after('late_submission_penalty');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (Schema::hasColumn('assignments', 'submission_type')) {
                $table->dropColumn('submission_type');
            }
            if (Schema::hasColumn('assignments', 'max_points')) {
                $table->dropColumn('max_points');
            }
            if (Schema::hasColumn('assignments', 'allow_late_submissions')) {
                $table->dropColumn('allow_late_submissions');
            }
            if (Schema::hasColumn('assignments', 'late_submission_penalty')) {
                $table->dropColumn('late_submission_penalty');
            }
            if (Schema::hasColumn('assignments', 'attachments')) {
                $table->dropColumn('attachments');
            }
        });
    }
};
