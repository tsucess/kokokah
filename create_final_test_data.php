<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ForumPost;
use App\Models\ForumTopic;
use App\Models\CourseReview;
use App\Models\Course;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "🔧 Creating final test data...\n\n";

// 1. Create Forum Post ID 1
echo "1️⃣  Creating Forum Post ID 1...\n";
$forumTopic = ForumTopic::first();
if ($forumTopic) {
    $forumPost = ForumPost::find(1);
    if (!$forumPost) {
        $forumPost = ForumPost::create([
            'id' => 1,
            'topic_id' => $forumTopic->id,
            'user_id' => 2, // student user
            'content' => 'This is a test forum post',
            'parent_id' => null,
            'status' => 'active',
            'likes_count' => 0,
            'is_solution' => false
        ]);
        echo "✅ Forum Post created with ID: {$forumPost->id}\n";
    } else {
        echo "✅ Forum Post ID 1 already exists\n";
    }
} else {
    echo "❌ No forum topic found\n";
}

// 2. Create Review ID 1
echo "\n2️⃣  Creating Review ID 1...\n";
$course = Course::first();
if ($course) {
    $review = CourseReview::find(1);
    if (!$review) {
        $review = CourseReview::create([
            'id' => 1,
            'course_id' => $course->id,
            'user_id' => 2, // student user
            'rating' => 5,
            'title' => 'Excellent Course',
            'comment' => 'This is a great course with excellent content',
            'status' => 'approved',
            'helpful_count' => 0
        ]);
        echo "✅ Review created with ID: {$review->id}\n";
    } else {
        echo "✅ Review ID 1 already exists\n";
    }
} else {
    echo "❌ No course found\n";
}

// 3. Create Notification
echo "\n3️⃣  Creating Notification...\n";
$user = User::find(2);
if ($user) {
    // Use Laravel's notification system
    DB::table('notifications')->insert([
        'id' => \Illuminate\Support\Str::uuid(),
        'type' => 'App\\Notifications\\TestNotification',
        'notifiable_type' => 'App\\Models\\User',
        'notifiable_id' => $user->id,
        'data' => json_encode(['title' => 'Test Notification', 'message' => 'This is a test notification']),
        'read_at' => null,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Notification created\n";
} else {
    echo "❌ User not found\n";
}

echo "\n✅ All test data created successfully!\n";

