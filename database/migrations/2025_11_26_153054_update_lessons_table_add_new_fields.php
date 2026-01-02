<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            // Add new columns
            $table->unsignedBigInteger('topic_id')->nullable()->after('course_id');
            $table->string('video_type')->nullable()->after('video_url');
            $table->string('lesson_type')->nullable()->after('video_type');

            // Modify existing column: attachment → longtext
            $table->longText('attachment')->nullable()->change();

            // Add new attachment fields
            $table->string('attachment_type')->nullable()->after('attachment');
            $table->longText('summary')->nullable()->after('attachment_type');

            // Mobile app fields
            $table->string('video_type_for_mobile_application')->nullable()->after('summary');
            $table->string('video_url_for_mobile_application')->nullable()->after('video_type_for_mobile_application');
            $table->string('duration_for_mobile_application')->nullable()->after('video_url_for_mobile_application');
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            // Reverse the added columns
            $table->dropColumn('topic_id');
            $table->dropColumn('video_type');
            $table->dropColumn('lesson_type');
            $table->dropColumn('attachment_type');
            $table->dropColumn('summary');
            $table->dropColumn('video_type_for_mobile_application');
            $table->dropColumn('video_url_for_mobile_application');
            $table->dropColumn('duration_for_mobile_application');

            // Revert attachment back to varchar(255)
            $table->string('attachment', 255)->nullable()->change();
        });
    }
};
