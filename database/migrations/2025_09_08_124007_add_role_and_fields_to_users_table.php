<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student')->after('email'); // student, instructor, admin, superadmin
            $table->boolean('is_active')->default(true)->after('role');
            $table->string('identifier')->nullable()->unique()->after('id');
            // add other fields you need like contact, gender
            $table->string('contact')->nullable()->after('identifier');
            $table->string('gender')->nullable()->after('contact');
        });
    }




    /**
     * Reverse the migrations.
     */
    // public function down(): void
    // {
    //     Schema::table('users', function (Blueprint $table) {
    //         //
    //     });
    // }
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active', 'identifier', 'contact', 'gender']);
        });
    }
};
