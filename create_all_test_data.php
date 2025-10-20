<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Enrollment;
use App\Models\Coupon;
use App\Models\ForumPost;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

echo "Creating all necessary test data...\n\n";

// 1. Create Enrollment ID 1
echo "1. Creating Enrollment ID 1...\n";
DB::table('enrollments')->where('id', 1)->delete();
DB::insert('INSERT INTO enrollments (id, user_id, course_id, status, progress, completed_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [
    1,
    130,
    1,
    'active',
    0,
    null,
    now(),
    now()
]);
echo "   ✅ Enrollment ID 1 created\n";

// 2. Create Coupon ID 9 (if not exists)
echo "2. Creating Coupon ID 9...\n";
$coupon = Coupon::find(9);
if (!$coupon) {
    Coupon::create([
        'code' => 'TEST10',
        'name' => 'Test Coupon 10% Off',
        'description' => 'Test coupon for 10% discount',
        'type' => 'percentage',
        'value' => 10,
        'usage_limit' => 100,
        'used_count' => 0,
        'starts_at' => now(),
        'expires_at' => now()->addMonths(1),
        'is_active' => true
    ]);
    echo "   ✅ Coupon ID 9 created\n";
} else {
    echo "   ✅ Coupon ID 9 already exists\n";
}

// 3. Create ForumPost ID 1 (if not exists)
echo "3. Creating ForumPost ID 1...\n";
$post = ForumPost::find(1);
if (!$post) {
    DB::table('forum_posts')->where('id', 1)->delete();
    DB::insert('INSERT INTO forum_posts (id, topic_id, user_id, content, parent_id, status, likes_count, is_solution, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
        1,
        1,
        2,
        'This is a test forum post',
        null,
        'active',
        0,
        false,
        now(),
        now()
    ]);
    echo "   ✅ ForumPost ID 1 created\n";
} else {
    echo "   ✅ ForumPost ID 1 already exists\n";
}

// 4. Create Notification for user 130
echo "4. Creating Notification for user 130...\n";
$notification = DB::table('notifications')->where('notifiable_id', 130)->first();
if (!$notification) {
    $notificationId = Str::uuid();
    DB::table('notifications')->insert([
        'id' => $notificationId,
        'type' => 'App\\Notifications\\TestNotification',
        'notifiable_type' => 'App\\Models\\User',
        'notifiable_id' => 130,
        'data' => json_encode(['title' => 'Test Notification', 'message' => 'This is a test notification']),
        'read_at' => null,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "   ✅ Notification created with ID: $notificationId\n";
} else {
    echo "   ✅ Notification already exists for user 130\n";
}

echo "\n✅ All test data created successfully!\n";

