<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 CHECKING TOKEN IN DATABASE\n";
echo "=============================\n\n";

// Load token
$tokenContent = file_get_contents('auth_tokens.txt');
if (preg_match('/STUDENT_TOKEN=(.+)/', $tokenContent, $match)) {
    $token = trim($match[1]);
    echo "Token from file: $token\n\n";
    
    // Extract token ID and hash
    $tokenParts = explode('|', $token);
    $tokenId = $tokenParts[0];
    $tokenHash = $tokenParts[1];
    
    echo "Token ID: $tokenId\n";
    echo "Token Hash: " . substr($tokenHash, 0, 20) . "...\n\n";
    
    // Check if token exists in database
    $tokenRecord = \DB::table('personal_access_tokens')
        ->where('id', $tokenId)
        ->first();
    
    if ($tokenRecord) {
        echo "✅ Token found in database!\n";
        echo "Token name: {$tokenRecord->name}\n";
        echo "User ID: {$tokenRecord->tokenable_id}\n";
        echo "Created: {$tokenRecord->created_at}\n";
    } else {
        echo "❌ Token NOT found in database\n";
        echo "\nAll tokens in database:\n";
        $allTokens = \DB::table('personal_access_tokens')->get();
        foreach ($allTokens as $t) {
            echo "• ID: {$t->id}, User: {$t->tokenable_id}, Name: {$t->name}, Created: {$t->created_at}\n";
        }
    }
}

echo "\n========================================\n";
