<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 ALL TOKENS IN DATABASE\n";
echo "=========================\n\n";

$tokens = \DB::table('personal_access_tokens')->get();

echo "Total tokens: " . count($tokens) . "\n\n";

foreach ($tokens as $token) {
    echo "ID: {$token->id}\n";
    echo "User ID: {$token->tokenable_id}\n";
    echo "Name: {$token->name}\n";
    echo "Created: {$token->created_at}\n";
    echo "---\n";
}

echo "\n========================================\n";
