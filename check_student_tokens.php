<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 CHECKING STUDENT (USER ID 2) TOKENS\n";
echo "======================================\n\n";

$tokens = \DB::table('personal_access_tokens')
    ->where('tokenable_id', 2)
    ->get();

echo "Total tokens for user 2: " . count($tokens) . "\n\n";

foreach ($tokens as $token) {
    echo "ID: {$token->id}\n";
    echo "Name: {$token->name}\n";
    echo "Created: {$token->created_at}\n";
    echo "---\n";
}

echo "\n========================================\n";
