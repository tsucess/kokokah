<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "🔍 TESTING WALLET ENDPOINT DIRECTLY\n";
echo "===================================\n\n";

// Create fresh student token
$student = User::find(2);
if (!$student) {
    echo "❌ Student not found!\n";
    exit(1);
}

$student->tokens()->delete();
$tokenObject = $student->createToken('student-token');
$newToken = $tokenObject->plainTextToken;

echo "✅ Created token: $newToken\n\n";

// Test wallet endpoint
echo "Testing GET /api/wallet with curl:\n";
echo "==================================\n\n";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "http://127.0.0.1:8000/api/wallet",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $newToken
    ],
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_VERBOSE => true
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status: $httpCode\n";
echo "Response: " . substr($response, 0, 200) . "...\n\n";

if ($error) {
    echo "Error: $error\n";
}

echo "========================================\n";
