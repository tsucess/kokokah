<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING USER ENDPOINTS WITH CORRECT ROUTES\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
$studentToken = trim($studentMatches[1]);

function testEndpointDetailed($name, $method, $endpoint, $token) {
    $url = 'http://localhost:8000/api' . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    echo "🔍 Testing: $name\n";
    echo "   URL: $url\n";
    echo "   Status: $httpCode\n";
    
    if ($httpCode === 200) {
        echo "   Result: ✅ SUCCESS\n";
    } else {
        echo "   Result: ❌ FAILED\n";
        if (isset($result['message'])) {
            echo "   Message: " . $result['message'] . "\n";
        }
        if (isset($result['error'])) {
            echo "   Error: " . substr($result['error'], 0, 150) . "...\n";
        }
    }
    
    echo "\n" . str_repeat("-", 60) . "\n\n";
    
    return $httpCode === 200;
}

// Test the correct user endpoints
$userTests = [
    ['User Profile (Correct Route)', 'GET', '/users/profile', $studentToken],
    ['User Dashboard (Correct Route)', 'GET', '/users/dashboard', $studentToken],
];

$passed = 0;
$total = count($userTests);

foreach ($userTests as $test) {
    if (testEndpointDetailed($test[0], $test[1], $test[2], $test[3])) {
        $passed++;
    }
}

echo "============================================================\n";
echo "📊 USER ENDPOINTS TEST RESULTS\n";
echo "============================================================\n";
echo "Total Tests: $total\n";
echo "✅ Passed: $passed\n";
echo "❌ Failed: " . ($total - $passed) . "\n";
echo "📈 Success Rate: " . round(($passed / $total) * 100, 2) . "%\n";
echo "============================================================\n";
