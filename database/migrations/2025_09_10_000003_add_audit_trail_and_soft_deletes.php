<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add soft deletes to important tables
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
            $table->timestamp('last_login_at')->nullable()->after('email_verified_at');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Create audit log table for tracking changes
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('auditable_type'); // Model class name
            $table->unsignedBigInteger('auditable_id'); // Model ID
            $table->string('event'); // created, updated, deleted, etc.
            $table->json('old_values')->nullable(); // Previous values
            $table->json('new_values')->nullable(); // New values
            $table->unsignedBigInteger('user_id')->nullable(); // Who made the change
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['auditable_type', 'auditable_id']);
            $table->index('user_id');
            $table->index('created_at');
        });

        // Create notifications table for system notifications (only if it doesn't exist)
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable'); // This already creates the index
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        // Create activity log for user actions
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // login, logout, course_enrolled, lesson_completed, etc.
            $table->string('description')->nullable();
            $table->json('properties')->nullable(); // Additional data
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('action');
            $table->index('created_at');
        });

        // Create system settings table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false); // Can be accessed by frontend
            $table->timestamps();

            $table->index('key');
            $table->index('is_public');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['last_login_at', 'last_login_ip']);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::dropIfExists('settings');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('audit_logs');
    }
};
