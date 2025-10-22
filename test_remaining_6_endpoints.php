<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING REMAINING 6 FAILING ENDPOINTS\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);

$adminToken = trim($adminMatches[1]);
$studentToken = trim($studentMatches[1]);

function testEndpointDetailed($name, $method, $endpoint, $token, $data = null) {
    $url = 'http://localhost:8000/api' . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    echo "🔍 Testing: $name\n";
    echo "   URL: $url\n";
    echo "   Method: $method\n";
    echo "   Status: $httpCode\n";
    
    if ($httpCode === 200) {
        echo "   Result: ✅ SUCCESS\n";
        if (isset($result['data'])) {
            if (is_array($result['data'])) {
                echo "   Data: " . count($result['data']) . " items\n";
            } else {
                echo "   Data: " . implode(', ', array_keys($result['data'])) . "\n";
            }
        }
    } else {
        echo "   Result: ❌ FAILED\n";
        if (isset($result['message'])) {
            echo "   Message: " . $result['message'] . "\n";
        }
        if (isset($result['error'])) {
            echo "   Error: " . substr($result['error'], 0, 100) . "...\n";
        }
        echo "   Full Response: " . substr($response, 0, 200) . "...\n";
    }
    
    echo "\n" . str_repeat("-", 60) . "\n\n";
    
    return $httpCode === 200;
}

echo "🎯 TESTING THE 6 REMAINING FAILING ENDPOINTS:\n\n";

// Test each failing endpoint with detailed output
$failingTests = [
    ['User Profile', 'GET', '/user/profile', $studentToken],
    ['User Dashboard', 'GET', '/user/dashboard', $studentToken],
    ['Badge Leaderboard', 'GET', '/badges/leaderboard', $studentToken],
    ['My Courses', 'GET', '/courses/my-courses', $studentToken],
    ['Certificate Templates (Admin)', 'GET', '/certificates/templates', $adminToken],
    ['Student Dashboard', 'GET', '/dashboard/student', $studentToken],
];

$passed = 0;
$total = count($failingTests);

foreach ($failingTests as $test) {
    if (testEndpointDetailed($test[0], $test[1], $test[2], $test[3])) {
        $passed++;
    }
}

echo "============================================================\n";
echo "📊 REMAINING ENDPOINTS TEST RESULTS\n";
echo "============================================================\n";
echo "Total Tests: $total\n";
echo "✅ Passed: $passed\n";
echo "❌ Failed: " . ($total - $passed) . "\n";
echo "📈 Success Rate: " . round(($passed / $total) * 100, 2) . "%\n";
echo "============================================================\n";
