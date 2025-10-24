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
        if (Schema::hasTable('chat_sessions')) {
            $columns = Schema::getColumnListing('chat_sessions');
            
            Schema::table('chat_sessions', function (Blueprint $table) use ($columns) {
                if (!in_array('course_id', $columns)) {
                    $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('set null')->after('user_id');
                }
                
                if (!in_array('session_type', $columns)) {
                    $table->enum('session_type', ['general', 'course_help', 'assignment_help', 'quiz_help'])->default('general')->after('course_id');
                }
                
                if (!in_array('context', $columns)) {
                    $table->json('context')->nullable()->after('session_type');
                }
                
                if (!in_array('status', $columns)) {
                    $table->enum('status', ['active', 'ended', 'paused'])->default('active')->after('context');
                }
                
                if (!in_array('started_at', $columns)) {
                    $table->timestamp('started_at')->nullable()->after('status');
                }
                
                if (!in_array('ended_at', $columns)) {
                    $table->timestamp('ended_at')->nullable()->after('started_at');
                }
                
                if (!in_array('last_activity_at', $columns)) {
                    $table->timestamp('last_activity_at')->nullable()->after('ended_at');
                }
                
                if (!in_array('rating', $columns)) {
                    $table->tinyInteger('rating')->nullable()->after('last_activity_at');
                }
                
                if (!in_array('feedback', $columns)) {
                    $table->text('feedback')->nullable()->after('rating');
                }
                
                if (!in_array('rated_at', $columns)) {
                    $table->timestamp('rated_at')->nullable()->after('feedback');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('chat_sessions')) {
            $columns = Schema::getColumnListing('chat_sessions');
            
            Schema::table('chat_sessions', function (Blueprint $table) use ($columns) {
                $columnsToRemove = ['course_id', 'session_type', 'context', 'status', 'started_at', 'ended_at', 'last_activity_at', 'rating', 'feedback', 'rated_at'];
                
                foreach ($columnsToRemove as $column) {
                    if (in_array($column, $columns)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};

