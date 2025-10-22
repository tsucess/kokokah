<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "🔍 DEBUGGING STUDENT USER ISSUE\n";
echo "===============================\n\n";

// Check all users
$users = User::all();
echo "All users in database:\n";
foreach ($users as $user) {
    echo "ID: {$user->id}, Email: {$user->email}, Role: {$user->role}, Active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
}

echo "\n";

// Find student user specifically
$student = User::where('role', 'student')->first();
if (!$student) {
    echo "❌ No student user found! Creating one...\n";
    
    $student = new User();
    $student->first_name = 'Test';
    $student->last_name = 'Student';
    $student->email = 'student1@kokokah.com';
    $student->password = bcrypt('password123');
    $student->role = 'student';
    $student->is_active = true;
    $student->email_verified_at = now();
    $student->save();
    
    echo "✅ Created new student user: {$student->email} (ID: {$student->id})\n";
} else {
    echo "Found student user: {$student->email} (ID: {$student->id})\n";
    echo "Active: " . ($student->is_active ? 'Yes' : 'No') . "\n";
    echo "Email verified: " . ($student->email_verified_at ? 'Yes' : 'No') . "\n";
    
    // Make sure student is active and verified
    if (!$student->is_active) {
        $student->is_active = true;
        $student->save();
        echo "✅ Activated student user\n";
    }
    
    if (!$student->email_verified_at) {
        $student->email_verified_at = now();
        $student->save();
        echo "✅ Verified student email\n";
    }
}

// Check existing tokens
$existingTokens = $student->tokens()->get();
echo "\nExisting tokens for student:\n";
foreach ($existingTokens as $token) {
    echo "Token ID: {$token->id}, Name: {$token->name}, Created: {$token->created_at}\n";
}

// Delete all old tokens
$student->tokens()->delete();
echo "\n✅ Deleted all old tokens\n";

// Create fresh token
$newToken = $student->createToken('student-token')->plainTextToken;
echo "✅ Generated new token: $newToken\n";

// Test the token immediately
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
    curl_close($ch);
    
    return ['status' => $httpCode, 'body' => $response];
}

echo "\n🧪 Testing new token immediately:\n";
$testResult = testToken($newToken);
echo "Status: {$testResult['status']}\n";
if ($testResult['status'] == 200) {
    echo "✅ Token works!\n";
    $body = json_decode($testResult['body'], true);
    if (isset($body['data']['email'])) {
        echo "User: {$body['data']['email']}\n";
    }
} else {
    echo "❌ Token failed!\n";
    echo "Response: " . substr($testResult['body'], 0, 200) . "\n";
}

// Update auth_tokens.txt
$authContent = file_get_contents('auth_tokens.txt');
$authContent = preg_replace('/STUDENT_TOKEN=.+/', "STUDENT_TOKEN=$newToken", $authContent);
file_put_contents('auth_tokens.txt', $authContent);
echo "\n✅ Updated auth_tokens.txt\n";

echo "\n===============================\n";
echo "Student user debugging complete!\n";
echo "New token: $newToken\n";
echo "===============================\n";
