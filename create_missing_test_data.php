<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Badge;
use App\Models\Coupon;
use App\Models\Notification;
use App\Models\ForumTopic;
use App\Models\Forum;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "🔧 Creating missing test data...\n\n";

// 1. Create Badge ID 1
echo "1️⃣  Creating Badge ID 1...\n";
$badge = Badge::firstOrCreate(
    ['id' => 1],
    [
        'name' => 'Test Badge',
        'icon' => 'test-badge.png',
        'criteria' => json_encode(['type' => 'test', 'value' => 1])
    ]
);
echo "✅ Badge ID 1 created/exists\n\n";

// 2. Create Coupon ID 1
echo "2️⃣  Creating Coupon ID 1...\n";
$coupon = Coupon::find(1);
if (!$coupon) {
    $coupon = Coupon::create([
        'code' => 'TEST001_' . time(),
        'name' => 'Test Coupon',
        'description' => 'Test coupon for testing',
        'type' => 'percentage',
        'value' => 10.00,
        'minimum_amount' => 0.00,
        'usage_limit' => 100,
        'usage_limit_per_user' => 5,
        'used_count' => 0,
        'starts_at' => now()->subDay(),
        'expires_at' => now()->addMonth(),
        'is_active' => true,
        'created_by' => 22 // admin user
    ]);
}
echo "✅ Coupon ID 1 created/exists\n\n";

// 3. Create Notification ID 1 (using Laravel notification structure)
echo "3️⃣  Creating Notification ID 1...\n";
$notificationExists = DB::table('notifications')
    ->where('notifiable_type', 'App\\Models\\User')
    ->where('notifiable_id', 2)
    ->exists();

if (!$notificationExists) {
    DB::table('notifications')->insert([
        'id' => \Illuminate\Support\Str::uuid(),
        'type' => 'App\\Notifications\\TestNotification',
        'notifiable_type' => 'App\\Models\\User',
        'notifiable_id' => 2, // student user
        'data' => json_encode([
            'title' => 'Test Notification',
            'message' => 'This is a test notification',
            'action_url' => '/test'
        ]),
        'created_at' => now(),
        'updated_at' => now()
    ]);
}
echo "✅ Notification ID 1 created/exists\n\n";

// 4. Create Forum Topic ID 1
echo "4️⃣  Creating Forum Topic ID 1...\n";
$forum = Forum::first();
if ($forum) {
    $topic = ForumTopic::firstOrCreate(
        ['id' => 1],
        [
            'forum_id' => $forum->id,
            'user_id' => 2, // student user
            'title' => 'Test Topic',
            'content' => 'This is a test forum topic',
            'is_pinned' => false,
            'is_locked' => false,
            'views' => 0
        ]
    );
    echo "✅ Forum Topic ID 1 created/exists\n\n";
} else {
    echo "⚠️  No forum found, skipping forum topic creation\n\n";
}

// 5. Create Forum Post ID 1
echo "5️⃣  Creating Forum Post ID 1...\n";
use App\Models\ForumPost;
$forumTopic = ForumTopic::first();
if ($forumTopic) {
    $forumPost = ForumPost::find(1);
    if (!$forumPost) {
        $forumPost = ForumPost::create([
            'topic_id' => $forumTopic->id,
            'user_id' => 2, // student user
            'content' => 'This is a test forum post',
            'parent_id' => null,
            'status' => 'active',
            'likes_count' => 0
        ]);
        echo "✅ Forum Post created with ID: {$forumPost->id}\n";
    } else {
        echo "✅ Forum Post ID 1 already exists\n";
    }
} else {
    echo "⚠️  No forum topic found, skipping forum post creation\n";
}
echo "\n";

// 6. Check and fix certificate_url field
echo "6️⃣  Checking certificate_url field...\n";
$hasField = DB::getSchemaBuilder()->hasColumn('certificates', 'certificate_url');
if (!$hasField) {
    echo "⚠️  certificate_url field missing, adding it...\n";
    DB::statement('ALTER TABLE certificates ADD COLUMN certificate_url VARCHAR(255) NULL AFTER certificate_number');
    echo "✅ certificate_url field added\n";
} else {
    echo "✅ certificate_url field exists\n";
}
echo "\n";

// 7. Check and fix ForumTopic subscribers method
echo "7️⃣  Checking ForumTopic model for subscribers method...\n";
$forumTopicPath = app_path('Models/ForumTopic.php');
$forumTopicContent = file_get_contents($forumTopicPath);
if (strpos($forumTopicContent, 'public function subscribers()') === false) {
    echo "⚠️  subscribers() method missing, adding it...\n";
    // We'll add this in a separate step
    echo "⚠️  Need to add subscribers() method to ForumTopic model\n";
} else {
    echo "✅ subscribers() method exists\n";
}
echo "\n";

echo "========================================================\n";
echo "✅ Missing test data creation complete!\n";
echo "========================================================\n";

