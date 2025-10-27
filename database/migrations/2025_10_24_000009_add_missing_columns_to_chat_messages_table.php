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
        if (Schema::hasTable('chat_messages')) {
            $columns = Schema::getColumnListing('chat_messages');
            
            Schema::table('chat_messages', function (Blueprint $table) use ($columns) {
                if (!in_array('sender_type', $columns)) {
                    $table->enum('sender_type', ['user', 'ai', 'bot', 'system'])->default('user')->after('sender');
                }
                
                if (!in_array('sent_at', $columns)) {
                    $table->timestamp('sent_at')->nullable()->after('message');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('chat_messages')) {
            $columns = Schema::getColumnListing('chat_messages');
            
            Schema::table('chat_messages', function (Blueprint $table) use ($columns) {
                if (in_array('sender_type', $columns)) {
                    $table->dropColumn('sender_type');
                }
                
                if (in_array('sent_at', $columns)) {
                    $table->dropColumn('sent_at');
                }
            });
        }
    }
};

