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
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Channel preferences
            $table->boolean('email_enabled')->default(true);
            $table->boolean('push_enabled')->default(true);
            $table->boolean('sms_enabled')->default(false);

            // Notification type preferences
            $table->boolean('course_updates')->default(true);
            $table->boolean('assignment_reminders')->default(true);
            $table->boolean('quiz_reminders')->default(true);
            $table->boolean('forum_replies')->default(true);
            $table->boolean('new_courses')->default(true);
            $table->boolean('promotions')->default(false);
            $table->boolean('system_announcements')->default(true);
            $table->boolean('achievement_notifications')->default(true);
            $table->boolean('payment_notifications')->default(true);
            $table->boolean('security_alerts')->default(true);
            $table->boolean('weekly_digest')->default(true);
            $table->boolean('marketing_emails')->default(false);

            // Frequency and timing
            $table->enum('frequency', ['immediate', 'hourly', 'daily', 'weekly'])->default('immediate');
            $table->time('quiet_hours_start')->nullable();
            $table->time('quiet_hours_end')->nullable();
            $table->string('timezone', 50)->default('UTC');

            $table->timestamps();

            // Unique constraint
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
