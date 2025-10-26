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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('original_name');
            $table->string('file_name');
            $table->string('file_path');
            $table->bigInteger('file_size'); // in bytes
            $table->string('mime_type');
            $table->string('extension', 10);
            $table->string('folder')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('lesson_id')->nullable()->constrained()->onDelete('set null');
            $table->string('upload_ip')->nullable();
            $table->integer('download_count')->default(0);
            $table->timestamp('last_downloaded_at')->nullable();
            $table->string('share_token')->nullable()->unique();
            $table->enum('share_type', ['public', 'private', 'course'])->default('private');
            $table->timestamp('share_expires_at')->nullable();
            $table->string('share_password')->nullable();
            $table->integer('allowed_downloads')->nullable();
            $table->boolean('is_shared')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['user_id', 'created_at']);
            $table->index(['course_id', 'lesson_id']);
            $table->index(['extension', 'mime_type']);
            $table->index(['is_public', 'is_shared']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
