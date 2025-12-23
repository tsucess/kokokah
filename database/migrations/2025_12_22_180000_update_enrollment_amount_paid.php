<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing enrollments with amount_paid = 0 to use course price
        if (DB::getDriverName() === 'mysql') {
            DB::statement('
                UPDATE enrollments e
                JOIN courses c ON e.course_id = c.id
                SET e.amount_paid = c.price
                WHERE e.amount_paid = 0
            ');
        } else {
            // SQLite doesn't support JOIN in UPDATE, so use a subquery
            DB::statement('
                UPDATE enrollments
                SET amount_paid = (
                    SELECT price FROM courses WHERE courses.id = enrollments.course_id
                )
                WHERE amount_paid = 0
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset amount_paid to 0 for all enrollments
        DB::statement('UPDATE enrollments SET amount_paid = 0');
    }
};

