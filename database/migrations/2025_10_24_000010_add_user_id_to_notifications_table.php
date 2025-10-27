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
        if (Schema::hasTable('notifications')) {
            $columns = Schema::getColumnListing('notifications');

            Schema::table('notifications', function (Blueprint $table) use ($columns) {
                // Make notifiable_type and notifiable_id nullable if they exist
                if (in_array('notifiable_type', $columns)) {
                    $table->string('notifiable_type')->nullable()->change();
                }

                if (in_array('notifiable_id', $columns)) {
                    $table->unsignedBigInteger('notifiable_id')->nullable()->change();
                }

                // Add user_id column if it doesn't exist
                if (!in_array('user_id', $columns)) {
                    $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
                }

                // Add other missing columns
                if (!in_array('title', $columns)) {
                    $table->string('title')->nullable()->after('type');
                }

                if (!in_array('message', $columns)) {
                    $table->text('message')->nullable()->after('title');
                }

                if (!in_array('action_url', $columns)) {
                    $table->string('action_url')->nullable()->after('message');
                }

                if (!in_array('action_text', $columns)) {
                    $table->string('action_text')->nullable()->after('action_url');
                }

                if (!in_array('priority', $columns)) {
                    $table->enum('priority', ['low', 'normal', 'high'])->default('normal')->after('action_text');
                }

                if (!in_array('category', $columns)) {
                    $table->string('category')->nullable()->after('priority');
                }

                if (!in_array('expires_at', $columns)) {
                    $table->timestamp('expires_at')->nullable()->after('category');
                }

                if (!in_array('sender_id', $columns)) {
                    $table->foreignId('sender_id')->nullable()->after('expires_at')->constrained('users')->onDelete('set null');
                }

                if (!in_array('sent_by', $columns)) {
                    $table->foreignId('sent_by')->nullable()->after('sender_id')->constrained('users')->onDelete('set null');
                }

                if (!in_array('related_model_type', $columns)) {
                    $table->string('related_model_type')->nullable()->after('sent_by');
                }

                if (!in_array('related_model_id', $columns)) {
                    $table->unsignedBigInteger('related_model_id')->nullable()->after('related_model_type');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                $columns = Schema::getColumnListing('notifications');

                if (in_array('user_id', $columns)) {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }

                if (in_array('title', $columns)) {
                    $table->dropColumn('title');
                }

                if (in_array('message', $columns)) {
                    $table->dropColumn('message');
                }

                if (in_array('action_url', $columns)) {
                    $table->dropColumn('action_url');
                }

                if (in_array('action_text', $columns)) {
                    $table->dropColumn('action_text');
                }

                if (in_array('priority', $columns)) {
                    $table->dropColumn('priority');
                }

                if (in_array('category', $columns)) {
                    $table->dropColumn('category');
                }

                if (in_array('expires_at', $columns)) {
                    $table->dropColumn('expires_at');
                }

                if (in_array('sender_id', $columns)) {
                    $table->dropForeign(['sender_id']);
                    $table->dropColumn('sender_id');
                }

                if (in_array('sent_by', $columns)) {
                    $table->dropForeign(['sent_by']);
                    $table->dropColumn('sent_by');
                }

                if (in_array('related_model_type', $columns)) {
                    $table->dropColumn('related_model_type');
                }

                if (in_array('related_model_id', $columns)) {
                    $table->dropColumn('related_model_id');
                }
            });
        }
    }
};

