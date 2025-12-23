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
        Schema::table('conversation_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('conversation_messages', 'is_helpful')) {
                $table->boolean('is_helpful')->default(false)->after('is_edited');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversation_messages', function (Blueprint $table) {
            if (Schema::hasColumn('conversation_messages', 'is_helpful')) {
                $table->dropColumn('is_helpful');
            }
        });
    }
};

