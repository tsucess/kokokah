<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "🔧 REGENERATING STUDENT TOKEN\n";
echo "=============================\n\n";

// Find student user
$student = User::where('role', 'student')->first();

if (!$student) {
    echo "❌ No student user found!\n";
    exit(1);
}

echo "👤 Found student: {$student->first_name} {$student->last_name} (ID: {$student->id})\n";
echo "📧 Email: {$student->email}\n\n";

// Delete all existing tokens for this student
$deletedCount = $student->tokens()->delete();
echo "🗑️  Deleted $deletedCount existing tokens\n\n";

// Create new token
$newToken = $student->createToken('student-token')->plainTextToken;
echo "🔑 New student token generated: $newToken\n\n";

// Test the new token immediately
function testToken($token) {
    $ch = curl_init();
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $token
    ];
    
    curl_setopt_array($ch, [
        CURLOPT_URL => "http://127.0.0.1:8000/api/user",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['status' => 0, 'body' => $error];
    }
    
    return ['status' => $httpCode, 'body' => $response];
}

echo "🧪 Testing new token...\n";
$testResult = testToken($newToken);

if ($testResult['status'] == 200) {
    echo "✅ New token is WORKING!\n\n";
    
    $userData = json_decode($testResult['body'], true);
    if ($userData && isset($userData['data'])) {
        echo "👤 Authenticated as: {$userData['data']['first_name']} {$userData['data']['last_name']} ({$userData['data']['role']})\n\n";
    }
} else {
    echo "❌ New token is NOT working! Status: {$testResult['status']}\n";
    echo "Response: {$testResult['body']}\n\n";
    exit(1);
}

// Update auth_tokens.txt file
$tokenContent = file_get_contents('auth_tokens.txt');

// Replace the student token
$newTokenContent = preg_replace(
    '/STUDENT_TOKEN=.+/',
    'STUDENT_TOKEN=' . $newToken,
    $tokenContent
);

file_put_contents('auth_tokens.txt', $newTokenContent);

echo "📝 Updated auth_tokens.txt with new student token\n\n";

// Verify the file was updated correctly
$updatedContent = file_get_contents('auth_tokens.txt');
echo "📄 Updated auth_tokens.txt content:\n";
echo $updatedContent . "\n";

echo "✅ STUDENT TOKEN REGENERATION COMPLETE!\n";
echo "🎯 Ready to test all endpoints again!\n";
