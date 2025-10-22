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
        // Add localization columns to users table if they don't exist
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'language_preference')) {
                $table->string('language_preference')->default('en')->after('role');
            }
            if (!Schema::hasColumn('users', 'currency_preference')) {
                $table->string('currency_preference')->default('NGN')->after('language_preference');
            }
            if (!Schema::hasColumn('users', 'timezone_preference')) {
                $table->string('timezone_preference')->default('Africa/Lagos')->after('currency_preference');
            }
        });

        // Create content translations table if it doesn't exist
        if (!Schema::hasTable('content_translations')) {
            Schema::create('content_translations', function (Blueprint $table) {
                $table->id();
                $table->morphs('translatable');
                $table->string('language_code', 10);
                $table->string('field_name');
                $table->longText('field_value');
                $table->timestamps();

                $table->unique(['translatable_type', 'translatable_id', 'language_code', 'field_name'], 'ct_trans_lang_field_unique');
                $table->index('language_code');
                $table->index('field_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_translations');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['language_preference', 'currency_preference', 'timezone_preference']);
        });
    }
};

