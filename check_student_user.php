<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$token = file_get_contents('auth_tokens.txt');
if (preg_match('/STUDENT_TOKEN=(.+)/', $token, $m)) {
    $t = trim($m[1]);
    $pt = DB::table('personal_access_tokens')->where('token', hash('sha256', $t))->first();
    echo 'Student Token User ID: ' . ($pt ? $pt->tokenable_id : 'NOT FOUND') . "\n";
    
    // Get notifications for this user
    $userId = $pt->tokenable_id;
    $notifications = DB::table('notifications')->where('notifiable_id', $userId)->get();
    echo "Notifications for user $userId: " . count($notifications) . "\n";
    foreach ($notifications as $n) {
        echo "  - ID: {$n->id}\n";
    }
}

