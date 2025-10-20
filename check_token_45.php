<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 CHECKING TOKEN ID 45\n";
echo "======================\n\n";

$tokenRecord = \DB::table('personal_access_tokens')
    ->where('id', 45)
    ->first();

if ($tokenRecord) {
    echo "✅ Token found!\n";
    echo "ID: {$tokenRecord->id}\n";
    echo "User ID: {$tokenRecord->tokenable_id}\n";
    echo "Name: {$tokenRecord->name}\n";
    echo "Created: {$tokenRecord->created_at}\n";
} else {
    echo "❌ Token not found!\n";
}

echo "\n========================================\n";
