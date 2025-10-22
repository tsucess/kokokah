<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 VERIFYING TOKEN HASH\n";
echo "=======================\n\n";

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
    echo "Token Hash (plain): " . substr($tokenHash, 0, 30) . "...\n\n";
    
    // Calculate SHA256 hash
    $sha256Hash = hash('sha256', $tokenHash);
    echo "Token Hash (SHA256): " . substr($sha256Hash, 0, 30) . "...\n\n";
    
    // Check what's in the database
    $tokenRecord = \DB::table('personal_access_tokens')
        ->where('id', $tokenId)
        ->first();
    
    if ($tokenRecord) {
        echo "✅ Token found in database!\n";
        echo "Database token hash: " . substr($tokenRecord->token, 0, 30) . "...\n\n";
        
        // Compare hashes
        if ($tokenRecord->token === $sha256Hash) {
            echo "✅ Hash matches!\n";
        } else {
            echo "❌ Hash does NOT match!\n";
            echo "Expected: $sha256Hash\n";
            echo "Got: {$tokenRecord->token}\n";
        }
    } else {
        echo "❌ Token NOT found in database\n";
    }
}

echo "\n========================================\n";
