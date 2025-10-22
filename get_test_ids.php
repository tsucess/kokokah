<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ForumPost;
use App\Models\CourseReview;
use Illuminate\Support\Facades\DB;

echo "Forum Post IDs: " . implode(', ', ForumPost::pluck('id')->toArray()) . "\n";
echo "Review IDs: " . implode(', ', CourseReview::pluck('id')->toArray()) . "\n";
$notificationIds = DB::table('notifications')->pluck('id')->toArray();
echo "Notification IDs: " . implode(', ', $notificationIds) . "\n";
if (!empty($notificationIds)) {
    echo "First Notification ID: " . $notificationIds[0] . "\n";
}

