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
        // For SQLite, we need to recreate the table since it doesn't support modifying enum columns
        if (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support modifying enum, so we'll just add the new values
            // by recreating the constraint through a raw query
            DB::statement("
                CREATE TABLE enrollments_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id BIGINT UNSIGNED NOT NULL,
                    course_id BIGINT UNSIGNED NOT NULL,
                    progress INTEGER DEFAULT 0,
                    status VARCHAR(255) DEFAULT 'active' CHECK(status IN ('active', 'completed', 'dropped', 'paused', 'cancelled')),
                    enrolled_at TIMESTAMP NULL,
                    completed_at TIMESTAMP NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    UNIQUE(user_id, course_id),
                    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
                    FOREIGN KEY(course_id) REFERENCES courses(id) ON DELETE CASCADE
                )
            ");

            DB::statement("
                INSERT INTO enrollments_new 
                SELECT * FROM enrollments
            ");

            DB::statement("DROP TABLE enrollments");
            DB::statement("ALTER TABLE enrollments_new RENAME TO enrollments");
        } else {
            // For MySQL and other databases
            Schema::table('enrollments', function (Blueprint $table) {
                $table->string('status')->change();
            });

            // Add check constraint for MySQL
            DB::statement("ALTER TABLE enrollments MODIFY status ENUM('active', 'completed', 'dropped', 'paused', 'cancelled') DEFAULT 'active'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement("
                CREATE TABLE enrollments_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id BIGINT UNSIGNED NOT NULL,
                    course_id BIGINT UNSIGNED NOT NULL,
                    progress INTEGER DEFAULT 0,
                    status VARCHAR(255) DEFAULT 'active' CHECK(status IN ('active', 'completed', 'dropped')),
                    enrolled_at TIMESTAMP NULL,
                    completed_at TIMESTAMP NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    UNIQUE(user_id, course_id),
                    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
                    FOREIGN KEY(course_id) REFERENCES courses(id) ON DELETE CASCADE
                )
            ");

            DB::statement("
                INSERT INTO enrollments_new 
                SELECT * FROM enrollments WHERE status IN ('active', 'completed', 'dropped')
            ");

            DB::statement("DROP TABLE enrollments");
            DB::statement("ALTER TABLE enrollments_new RENAME TO enrollments");
        } else {
            DB::statement("ALTER TABLE enrollments MODIFY status ENUM('active', 'completed', 'dropped') DEFAULT 'active'");
        }
    }
};

