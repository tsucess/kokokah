<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Link to class_levels
            $table->foreignId('level_id')->nullable()
                  ->constrained('levels')
                  ->onDelete('set null');

            // Additional profile info
            $table->date('date_of_birth')->nullable();
            $table->string('address')->nullable();
            $table->string('profile_photo')->nullable(); // store file path

            // Rename name → first_name + last_name if not yet applied
            // (Already in your current table, so no need here)
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['level_id']);
            $table->dropColumn(['level_id', 'date_of_birth', 'address', 'profile_photo']);
        });
    }
};
