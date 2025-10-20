<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 CHECKING USER ID 139\n";
echo "======================\n\n";

$user = \DB::table('users')->where('id', 139)->first();

if ($user) {
    echo "✅ User found!\n";
    echo "ID: {$user->id}\n";
    echo "Name: {$user->first_name} {$user->last_name}\n";
    echo "Email: {$user->email}\n";
    echo "Role: {$user->role}\n";
} else {
    echo "❌ User not found!\n";
}

echo "\n========================================\n";
