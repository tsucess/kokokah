<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "🔄 CREATING FRESH STUDENT TOKEN\n";
echo "============================================================\n\n";

// Get current admin token
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
$adminToken = trim($adminMatches[1]);

echo "Using Admin Token: " . substr($adminToken, 0, 30) . "...\n\n";

// First, let's check if we have any student users
$students = User::where('role', 'student')->get();
echo "Found " . $students->count() . " student users in database:\n";

foreach ($students as $student) {
    echo "- {$student->first_name} {$student->last_name} ({$student->email})\n";
}

// Let's create a fresh test student if none exist or use existing one
$testStudent = User::where('email', 'test.student@kokokah.com')->first();

if (!$testStudent) {
    echo "\n🔄 Creating new test student...\n";
    
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
    echo "\n✅ Using existing test student: {$testStudent->email}\n";
}

// Now create a token for this student
echo "\n🔑 Creating token for test student...\n";

$token = $testStudent->createToken('api-token')->plainTextToken;

echo "✅ Generated new student token: " . substr($token, 0, 30) . "...\n";

// Update the tokens file
$tokenContent = "ADMIN_TOKEN=$adminToken\nSTUDENT_TOKEN=$token\n";
file_put_contents('auth_tokens.txt', $tokenContent);

echo "✅ Updated auth_tokens.txt\n";

// Test the new token
echo "\n🧪 Testing new student token...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/user');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer ' . $token
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

echo "\n============================================================\n";
echo "✅ READY TO TEST ALL ENDPOINTS WITH FRESH TOKENS!\n";
echo "============================================================\n";
