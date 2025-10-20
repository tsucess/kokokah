<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "🔧 CREATE AND VERIFY TOKEN IMMEDIATELY\n";
echo "======================================\n\n";

// Find user ID 2
$student = User::find(2);

if (!$student) {
    echo "❌ User ID 2 not found!\n";
    exit(1);
}

echo "👤 Found student: {$student->first_name} {$student->last_name} (ID: {$student->id})\n\n";

// Delete all existing tokens
$student->tokens()->delete();
echo "🗑️  Deleted existing tokens\n\n";

// Create new token
echo "Creating new token...\n";
$tokenObject = $student->createToken('student-token');
$newToken = $tokenObject->plainTextToken;

echo "🔑 Token generated: $newToken\n\n";

// Extract token ID
$tokenParts = explode('|', $newToken);
$tokenId = $tokenParts[0];

echo "Token ID: $tokenId\n\n";

// Verify immediately
echo "Verifying token in database...\n";
$tokenRecord = \DB::table('personal_access_tokens')
    ->where('id', $tokenId)
    ->first();

if ($tokenRecord) {
    echo "✅ Token found in database!\n";
    echo "   User ID: {$tokenRecord->tokenable_id}\n";
    echo "   Name: {$tokenRecord->name}\n";
    echo "   Created: {$tokenRecord->created_at}\n\n";
    
    // Update auth_tokens.txt
    $tokenContent = file_get_contents('auth_tokens.txt');
    $newTokenContent = preg_replace(
        '/STUDENT_TOKEN=.+/',
        'STUDENT_TOKEN=' . $newToken,
        $tokenContent
    );
    
    file_put_contents('auth_tokens.txt', $newTokenContent);
    echo "📝 Updated auth_tokens.txt\n\n";
    
    // Test the token immediately
    echo "Testing token with /api/user endpoint...\n";
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "http://127.0.0.1:8000/api/user",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $newToken
        ],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        echo "✅ Token works! Status: $httpCode\n";
    } else {
        echo "❌ Token failed! Status: $httpCode\n";
        echo "Response: " . substr($response, 0, 100) . "...\n";
    }
} else {
    echo "❌ Token NOT found in database!\n";
    echo "   This is a critical issue!\n";
}

echo "\n========================================\n";
