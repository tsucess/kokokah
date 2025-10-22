<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 TESTING SPECIFIC FAILING ENDPOINTS\n";
echo "=====================================\n\n";

// Load authentication tokens
$authTokens = [];
if (file_exists('auth_tokens.txt')) {
    $content = file_get_contents('auth_tokens.txt');
    preg_match('/ADMIN_TOKEN=(.+)/', $content, $adminMatch);
    preg_match('/STUDENT_TOKEN=(.+)/', $content, $studentMatch);
    
    if ($adminMatch) $authTokens['admin'] = trim($adminMatch[1]);
    if ($studentMatch) $authTokens['student'] = trim($studentMatch[1]);
}

if (empty($authTokens)) {
    echo "❌ No authentication tokens found.\n";
    exit(1);
}

function makeRequest($url, $method = 'GET', $token = null, $data = []) {
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
    
    if (!empty($data) && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['status' => 0, 'body' => $error];
    }
    
    return ['status' => $httpCode, 'body' => $response];
}

echo "🧪 Testing specific failing endpoints:\n\n";

// Test the 404 error
echo "1. Testing api/enrollments/certificates (404 error):\n";
$response = makeRequest('api/enrollments/certificates', 'GET', $authTokens['student']);
echo "   Status: {$response['status']}\n";
if ($response['status'] != 200) {
    $body = json_decode($response['body'], true);
    echo "   Response: " . json_encode($body, JSON_PRETTY_PRINT) . "\n";
}
echo "\n";

// Test the 422 validation errors
echo "2. Testing api/progress/lessons (422 validation error):\n";
$response = makeRequest('api/progress/lessons', 'GET', $authTokens['student']);
echo "   Status: {$response['status']}\n";
if ($response['status'] != 200) {
    $body = json_decode($response['body'], true);
    echo "   Response: " . json_encode($body, JSON_PRETTY_PRINT) . "\n";
}
echo "\n";

echo "3. Testing api/search/content (422 validation error):\n";
$response = makeRequest('api/search/content', 'GET', $authTokens['student']);
echo "   Status: {$response['status']}\n";
if ($response['status'] != 200) {
    $body = json_decode($response['body'], true);
    echo "   Response: " . json_encode($body, JSON_PRETTY_PRINT) . "\n";
}
echo "\n";

// Test some permission errors to understand the role system
echo "4. Testing role-based access:\n";

echo "   Testing api/audit/logs with admin token:\n";
$response = makeRequest('api/audit/logs', 'GET', $authTokens['admin']);
echo "   Status: {$response['status']}\n";
if ($response['status'] != 200) {
    $body = json_decode($response['body'], true);
    echo "   Response: " . json_encode($body, JSON_PRETTY_PRINT) . "\n";
}
echo "\n";

echo "   Testing api/dashboard/instructor with student token:\n";
$response = makeRequest('api/dashboard/instructor', 'GET', $authTokens['student']);
echo "   Status: {$response['status']}\n";
if ($response['status'] != 200) {
    $body = json_decode($response['body'], true);
    echo "   Response: " . json_encode($body, JSON_PRETTY_PRINT) . "\n";
}
echo "\n";

echo "=====================================\n";
echo "🎯 Analysis complete!\n";
