<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

echo "Creating notification for student user...\n\n";

// Get the student user
$student = User::where('email', 'test.student@kokokah.com')->first();
if (!$student) {
    echo "❌ Student user not found\n";
    exit;
}

echo "Student: {$student->name} (ID: {$student->id})\n\n";

// Create a notification for this student
$notificationId = Str::uuid();
DB::table('notifications')->insert([
    'id' => $notificationId,
    'type' => 'App\\Notifications\\TestNotification',
    'notifiable_type' => 'App\\Models\\User',
    'notifiable_id' => $student->id,
    'data' => json_encode(['title' => 'Test Notification', 'message' => 'This is a test notification']),
    'read_at' => null,
    'created_at' => now(),
    'updated_at' => now()
]);

echo "✅ Notification created with ID: $notificationId\n";

// Verify it was created
$notification = DB::table('notifications')->where('id', $notificationId)->first();
if ($notification) {
    echo "✅ Notification verified in database\n";
    echo "   Notifiable ID: {$notification->notifiable_id}\n";
} else {
    echo "❌ Notification NOT found in database\n";
}

