<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔐 TESTING TOKEN VALIDITY\n";
echo "========================\n\n";

// Load authentication tokens
$authTokens = [];
if (file_exists('auth_tokens.txt')) {
    $content = file_get_contents('auth_tokens.txt');
    preg_match('/ADMIN_TOKEN=(.+)/', $content, $adminMatch);
    preg_match('/STUDENT_TOKEN=(.+)/', $content, $studentMatch);
    preg_match('/INSTRUCTOR_TOKEN=(.+)/', $content, $instructorMatch);
    
    if ($adminMatch) $authTokens['admin'] = trim($adminMatch[1]);
    if ($studentMatch) $authTokens['student'] = trim($studentMatch[1]);
    if ($instructorMatch) $authTokens['instructor'] = trim($instructorMatch[1]);
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
        CURLOPT_URL => "http://127.0.0.1:8000/api/$url",
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

// Test each token
foreach ($authTokens as $role => $token) {
    echo "Testing $role token: " . substr($token, 0, 20) . "...\n";
    
    $response = makeRequest('user', 'GET', $token);
    echo "  Status: {$response['status']}\n";
    
    if ($response['status'] == 200) {
        $body = json_decode($response['body'], true);
        if (isset($body['data']['role'])) {
            echo "  ✅ Valid - User role: {$body['data']['role']}\n";
        } else {
            echo "  ✅ Valid - Response received\n";
        }
    } else {
        echo "  ❌ Invalid - Error: " . substr($response['body'], 0, 100) . "\n";
    }
    echo "\n";
}

// Test a few specific endpoints with correct tokens
echo "Testing specific endpoints:\n";
echo "==========================\n";

$testEndpoints = [
    ['url' => 'courses/my-courses', 'token' => 'student', 'description' => 'Student courses'],
    ['url' => 'admin/dashboard', 'token' => 'admin', 'description' => 'Admin dashboard'],
    ['url' => 'instructor/courses', 'token' => 'instructor', 'description' => 'Instructor courses'],
    ['url' => 'wallet', 'token' => 'student', 'description' => 'Student wallet'],
    ['url' => 'notifications', 'token' => 'student', 'description' => 'Student notifications'],
];

foreach ($testEndpoints as $test) {
    $token = $authTokens[$test['token']];
    $response = makeRequest($test['url'], 'GET', $token);
    
    echo "{$test['description']}: ";
    if ($response['status'] == 200) {
        echo "✅ Working ({$response['status']})\n";
    } else {
        echo "❌ Failed ({$response['status']})\n";
        $body = json_decode($response['body'], true);
        if ($body && isset($body['message'])) {
            echo "   Error: " . substr($body['message'], 0, 80) . "\n";
        }
    }
}

echo "\n========================\n";
echo "Token validity test complete.\n";
