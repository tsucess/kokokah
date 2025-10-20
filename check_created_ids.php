<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Models\Badge;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;

echo "Checking created IDs...\n\n";

// Check Badge
$badge = Badge::first();
echo "First Badge: ID=" . ($badge?->id ?? 'NONE') . "\n";
echo "Badge ID 1 exists: " . (Badge::find(1) ? 'YES' : 'NO') . "\n";
echo "Total Badges: " . Badge::count() . "\n\n";

// Check Coupon
$coupon = DB::table('coupons')->first();
echo "First Coupon: ID=" . ($coupon?->id ?? 'NONE') . "\n";
echo "Coupon ID 1 exists: " . (DB::table('coupons')->where('id', 1)->exists() ? 'YES' : 'NO') . "\n";
echo "Total Coupons: " . DB::table('coupons')->count() . "\n\n";

// Check Notification
$notification = DB::table('notifications')->first();
echo "First Notification: ID=" . ($notification?->id ?? 'NONE') . "\n";
echo "Total Notifications: " . DB::table('notifications')->count() . "\n\n";

// Check Forum Topic
$forumTopic = DB::table('forum_topics')->first();
echo "First Forum Topic: ID=" . ($forumTopic?->id ?? 'NONE') . "\n";
echo "Forum Topic ID 1 exists: " . (DB::table('forum_topics')->where('id', 1)->exists() ? 'YES' : 'NO') . "\n";
echo "Total Forum Topics: " . DB::table('forum_topics')->count() . "\n";

