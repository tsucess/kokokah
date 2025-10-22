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
        // Video Streams Table
        Schema::create('video_streams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->text('original_url');
            $table->text('hls_manifest_url')->nullable();
            $table->text('dash_manifest_url')->nullable();
            $table->text('cdn_url')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->bigInteger('file_size_bytes')->default(0);
            $table->enum('status', ['pending', 'processing', 'processed', 'failed'])->default('pending');
            $table->float('processing_progress')->default(0);
            $table->text('error_message')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('lesson_id');
            $table->index('status');
        });

        // Video Qualities Table
        Schema::create('video_qualities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_stream_id')->constrained()->onDelete('cascade');
            $table->string('quality_label'); // 360p, 480p, 720p, 1080p
            $table->string('resolution'); // 640x360, 854x480, etc.
            $table->integer('bitrate'); // in bps
            $table->bigInteger('file_size_bytes')->default(0);
            $table->text('url')->nullable();
            $table->enum('status', ['pending', 'processing', 'ready', 'failed'])->default('pending');
            $table->string('codec_video')->default('h264');
            $table->string('codec_audio')->default('aac');
            $table->float('frame_rate')->default(30);
            $table->timestamps();

            $table->index('video_stream_id');
            $table->index('status');
            $table->unique(['video_stream_id', 'quality_label']);
        });

        // Video Analytics Table
        Schema::create('video_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_stream_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('view_count')->default(0);
            $table->integer('watch_time_seconds')->default(0);
            $table->string('quality_watched')->nullable();
            $table->string('device_type')->nullable(); // mobile, tablet, desktop
            $table->string('browser')->nullable();
            $table->string('country')->nullable();
            $table->string('ip_address')->nullable();
            $table->dateTime('last_watched_at')->nullable();
            $table->timestamps();

            $table->index('video_stream_id');
            $table->index('user_id');
            $table->index('device_type');
            $table->index('country');
            $table->unique(['video_stream_id', 'user_id']);
        });

        // Video Downloads Table
        Schema::create('video_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_stream_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('quality_label');
            $table->bigInteger('file_size_bytes')->default(0);
            $table->text('download_url')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'failed'])->default('pending');
            $table->float('progress_percentage')->default(0);
            $table->dateTime('expires_at')->nullable();
            $table->dateTime('downloaded_at')->nullable();
            $table->timestamps();

            $table->index('video_stream_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_downloads');
        Schema::dropIfExists('video_analytics');
        Schema::dropIfExists('video_qualities');
        Schema::dropIfExists('video_streams');
    }
};

