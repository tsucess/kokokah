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
        Schema::table('levels', function (Blueprint $table) {
            $table->unsignedBigInteger('curriculum_category_id')->nullable()->after('name');

            // If you have curriculum_categories table:
            $table->foreign('curriculum_category_id')
                ->references('id')->on('curriculum_categories')
                ->onDelete('cascade');

            // Remove old column
            if (Schema::hasColumn('levels', 'type')) {
                $table->dropColumn('type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->dropForeign(['curriculum_category_id']);
            $table->dropColumn('curriculum_category_id');
        });
    }
};
