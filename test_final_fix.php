<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING FINAL FIX FOR ENROLLMENTS/CERTIFICATES\n";
echo "=================================================\n\n";

// Load authentication tokens
$authTokens = [];
if (file_exists('auth_tokens.txt')) {
    $content = file_get_contents('auth_tokens.txt');
    preg_match('/STUDENT_TOKEN=(.+)/', $content, $studentMatch);
    
    if ($studentMatch) $authTokens['student'] = trim($studentMatch[1]);
}

function makeRequest($url, $method = 'GET', $token = null) {
    $ch = curl_init();
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt_array($ch, [
        CURLOPT_URL => "http://127.0.0.1:8000/$url",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => $method,
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

echo "Testing api/enrollments/certificates:\n";
$response = makeRequest('api/enrollments/certificates', 'GET', $authTokens['student']);
echo "Status: {$response['status']}\n";

if ($response['status'] == 200) {
    echo "✅ SUCCESS! Endpoint is now working!\n";
    $body = json_decode($response['body'], true);
    echo "Response: " . json_encode($body, JSON_PRETTY_PRINT) . "\n";
} else {
    echo "❌ Still failing\n";
    $body = json_decode($response['body'], true);
    echo "Error: " . json_encode($body, JSON_PRETTY_PRINT) . "\n";
}

echo "\n=================================================\n";
