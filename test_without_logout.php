<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING ENDPOINTS WITHOUT LOGOUT FIRST\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
$studentToken = trim($studentMatches[1]);

echo "Using Student Token: " . substr($studentToken, 0, 30) . "...\n\n";

function testEndpoint($name, $method, $endpoint, $token = null, $data = null) {
    $url = 'http://localhost:8000/api' . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $success = in_array($httpCode, [200, 201, 204]);
    $status = $success ? '✅' : '❌';
    
    printf("%-50s %s %d\n", $name, $status, $httpCode);
    
    if (!$success && $httpCode !== 404) {
        echo "   Response: " . substr($response, 0, 100) . "...\n";
    }
    
    return $success;
}

$totalTests = 0;
$passedTests = 0;

// Test endpoints in order without logout
$tests = [
    // Start with working endpoints
    ['Get Current User', 'GET', '/user', $studentToken],
    
    // Test the failing ones
    ['Get My Courses', 'GET', '/courses/my-courses', $studentToken],
    ['Get User Profile', 'GET', '/users/profile', $studentToken],
    ['Get User Dashboard', 'GET', '/users/dashboard', $studentToken],
    ['Get Course Lessons', 'GET', '/courses/11/lessons', $studentToken],
    ['Get Single Lesson', 'GET', '/lessons/1', $studentToken],
    ['Get Wallet Info', 'GET', '/wallet', $studentToken],
    ['Get Payment Gateways', 'GET', '/payments/gateways', $studentToken],
    ['Get All Badges', 'GET', '/badges', $studentToken],
    ['Get My Badges', 'GET', '/my-badges', $studentToken],
    ['Get Notifications', 'GET', '/notifications', $studentToken],
    ['Global Search', 'GET', '/search/global?q=test', $studentToken],
    
    // Test again to see if token is still valid
    ['Get Current User (Again)', 'GET', '/user', $studentToken],
];

echo "🎯 TESTING " . count($tests) . " ENDPOINTS:\n\n";

foreach ($tests as $test) {
    $totalTests++;
    if (testEndpoint($test[0], $test[1], $test[2], $test[3] ?? null, $test[4] ?? null)) {
        $passedTests++;
    }
}

$successRate = round(($passedTests / $totalTests) * 100, 2);

echo "\n============================================================\n";
echo "📊 TEST RESULTS WITHOUT LOGOUT\n";
echo "============================================================\n";
echo "Total Tests: $totalTests\n";
echo "✅ Passed: $passedTests\n";
echo "❌ Failed: " . ($totalTests - $passedTests) . "\n";
echo "📈 Success Rate: $successRate%\n";
echo "============================================================\n";

// Now test if the token is still valid after all these calls
echo "\n🔍 Final token validation:\n";
testEndpoint("Final User Check", "GET", "/user", $studentToken);
