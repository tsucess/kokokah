<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Course analytics and statistics
        Schema::create('course_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('views')->default(0);
            $table->integer('enrollments')->default(0);
            $table->integer('completions')->default(0);
            $table->decimal('average_rating', 3, 2)->nullable();
            $table->integer('total_revenue_cents')->default(0); // Store in cents
            $table->timestamps();

            $table->unique(['course_id', 'date']);
            $table->index(['course_id', 'date']);
        });

        // Discussion forums for courses
        Schema::create('forums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['course_id', 'is_active']);
        });

        Schema::create('forum_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->integer('views')->default(0);
            $table->timestamps();

            $table->index(['forum_id', 'is_pinned', 'created_at']);
            $table->index('user_id');
        });

        Schema::create('forum_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('forum_topics')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->foreignId('parent_id')->nullable()->constrained('forum_replies')->onDelete('cascade');
            $table->timestamps();

            $table->index(['topic_id', 'created_at']);
            $table->index('user_id');
            $table->index('parent_id');
        });

        // Coupons and discounts
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed']); // 10% or $10
            $table->decimal('value', 10, 2); // 10.00 for 10% or $10
            $table->decimal('minimum_amount', 10, 2)->nullable(); // Minimum purchase
            $table->integer('usage_limit')->nullable(); // Total usage limit
            $table->integer('usage_limit_per_user')->default(1);
            $table->integer('used_count')->default(0);
            $table->datetime('starts_at');
            $table->datetime('expires_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['code', 'is_active']);
            $table->index(['starts_at', 'expires_at']);
        });

        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('discount_amount', 10, 2);
            $table->timestamps();

            $table->index(['coupon_id', 'user_id']);
            $table->index('user_id');
        });

        // Learning paths (course sequences)
        Schema::create('learning_paths', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_published')->default(false);
            $table->string('difficulty')->default('beginner');
            $table->integer('estimated_hours')->nullable();
            $table->timestamps();

            $table->index(['is_published', 'difficulty']);
            $table->index('created_by');
        });

        Schema::create('learning_path_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_path_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->integer('sort_order');
            $table->boolean('is_required')->default(true);
            $table->timestamps();

            $table->unique(['learning_path_id', 'course_id']);
            $table->index(['learning_path_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_path_courses');
        Schema::dropIfExists('learning_paths');
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('forum_replies');
        Schema::dropIfExists('forum_topics');
        Schema::dropIfExists('forums');
        Schema::dropIfExists('course_analytics');
    }
};
