<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

echo "🔧 FIXING STUDENT TOKEN ISSUE\n";
echo "============================================================\n\n";

// Get current admin token
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
$adminToken = trim($adminMatches[1]);

echo "Using Admin Token: " . substr($adminToken, 0, 30) . "...\n\n";

// Find our test student
$testStudent = User::where('email', 'test.student@kokokah.com')->first();

if (!$testStudent) {
    echo "❌ Test student not found. Creating new one...\n";
    $testStudent = User::create([
        'first_name' => 'Test',
        'last_name' => 'Student',
        'email' => 'test.student@kokokah.com',
        'password' => Hash::make('password123'),
        'role' => 'student',
        'identifier' => 'KOKOKAH-' . str_pad(User::count() + 1, 4, '0', STR_PAD_LEFT)
    ]);
    echo "✅ Created test student: {$testStudent->email}\n";
} else {
    echo "✅ Found test student: {$testStudent->email} (ID: {$testStudent->id})\n";
}

// Delete any existing tokens for this user
echo "\n🗑️ Cleaning up old tokens...\n";
$testStudent->tokens()->delete();
echo "✅ Deleted old tokens\n";

// Create a new token properly
echo "\n🔑 Creating new token...\n";
$token = $testStudent->createToken('student-api-token');
$plainTextToken = $token->plainTextToken;

echo "✅ Generated new student token: " . substr($plainTextToken, 0, 30) . "...\n";

// Verify the token was saved to database
$tokenId = explode('|', $plainTextToken)[0];
$tokenRecord = PersonalAccessToken::find($tokenId);

if ($tokenRecord) {
    echo "✅ Token verified in database:\n";
    echo "  - Token ID: {$tokenRecord->id}\n";
    echo "  - User ID: {$tokenRecord->tokenable_id}\n";
    echo "  - Name: {$tokenRecord->name}\n";
    echo "  - Created: {$tokenRecord->created_at}\n";
} else {
    echo "❌ Token NOT found in database!\n";
}

// Update the tokens file
$tokenContent = "ADMIN_TOKEN=$adminToken\nSTUDENT_TOKEN=$plainTextToken\n";
file_put_contents('auth_tokens.txt', $tokenContent);
echo "\n✅ Updated auth_tokens.txt\n";

// Test the new token
echo "\n🧪 Testing new student token...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/user');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer ' . $plainTextToken
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "✅ Student token is working! HTTP $httpCode\n";
    $data = json_decode($response, true);
    echo "User: {$data['first_name']} {$data['last_name']} (ID: {$data['id']})\n";
} else {
    echo "❌ Student token test failed: HTTP $httpCode\n";
    echo "Response: $response\n";
}

// Also test a simple authenticated endpoint
echo "\n🧪 Testing authenticated endpoint (/courses/my-courses)...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/courses/my-courses');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer ' . $plainTextToken
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "✅ My Courses endpoint working! HTTP $httpCode\n";
} else {
    echo "❌ My Courses endpoint failed: HTTP $httpCode\n";
    echo "Response: " . substr($response, 0, 200) . "...\n";
}

echo "\n============================================================\n";
echo "✅ STUDENT TOKEN FIXED AND READY FOR TESTING!\n";
echo "============================================================\n";
