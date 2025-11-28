<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            // Convert INT -> BIGINT UNSIGNED + make it primary key
            $table->unsignedBigInteger('id')->autoIncrement()->change();

            // Ensure course_id matches lessons table type if needed (optional)
            $table->unsignedBigInteger('course_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Optional rollback
    }
};
