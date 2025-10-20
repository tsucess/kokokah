<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "🔍 DEBUGGING STUDENT AUTHENTICATION ISSUE\n";
echo "========================================\n\n";

// Create fresh student token
$student = User::find(2);
if (!$student) {
    echo "❌ Student not found!\n";
    exit(1);
}

$student->tokens()->delete();
$tokenObject = $student->createToken('student-token');
$newToken = $tokenObject->plainTextToken;

echo "✅ Created token: $newToken\n";
echo "   User ID: {$student->id}\n";
echo "   User Role: {$student->role}\n\n";

// Test different endpoints
$endpoints = [
    'user' => 'GET',
    'wallet' => 'GET',
    'courses/my-courses' => 'GET',
    'dashboard/student' => 'GET',
];

echo "Testing endpoints with student token:\n";
echo "====================================\n\n";

foreach ($endpoints as $endpoint => $method) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "http://127.0.0.1:8000/api/$endpoint",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $newToken
        ],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $status = ($httpCode == 200) ? '✅' : '❌';
    echo "$status $method $endpoint - $httpCode\n";
    
    if ($httpCode != 200) {
        $responseData = json_decode($response, true);
        if (isset($responseData['message'])) {
            echo "   Message: {$responseData['message']}\n";
        }
    }
}

echo "\n========================================\n";
