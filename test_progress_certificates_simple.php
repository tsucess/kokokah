<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING PROGRESS CERTIFICATES ENDPOINT\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
$studentToken = trim($studentMatches[1]);

echo "Using Student Token: " . substr($studentToken, 0, 30) . "...\n\n";

// Test the endpoint
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/progress/certificates');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer ' . $studentToken
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";

if ($httpCode === 200) {
    echo "✅ Progress certificates endpoint working! HTTP 200\n";
    $data = json_decode($response, true);
    if (isset($data['data'])) {
        $available = count($data['data']['available_for_generation'] ?? []);
        $existing = count($data['data']['existing_certificates'] ?? []);
        echo "📊 Available for generation: $available\n";
        echo "📊 Existing certificates: $existing\n";
    }
} else {
    echo "❌ Progress certificates endpoint failed! HTTP $httpCode\n";
    echo "Response: " . substr($response, 0, 500) . "...\n";
}

echo "\n============================================================\n";
echo "✅ PROGRESS CERTIFICATES TEST COMPLETED!\n";
echo "============================================================\n";
