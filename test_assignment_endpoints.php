<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING ASSIGNMENT ENDPOINTS\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
$studentToken = trim($studentMatches[1]);

echo "Using Student Token: " . substr($studentToken, 0, 30) . "...\n\n";

function testEndpoint($name, $endpoint, $token) {
    echo "🔍 Testing: $name\n";
    echo "Endpoint: $endpoint\n";
    
    $url = 'http://localhost:8000/api' . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
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
    
    echo "HTTP Code: $httpCode\n";
    
    if ($httpCode === 200) {
        echo "✅ SUCCESS!\n";
        $data = json_decode($response, true);
        if (isset($data['data']) && is_array($data['data'])) {
            echo "📊 Returned " . count($data['data']) . " items\n";
        }
    } else {
        echo "❌ FAILED!\n";
        echo "Response: " . substr($response, 0, 200) . "...\n";
    }
    echo "---\n\n";
    
    return $httpCode === 200;
}

// Test assignment endpoints
$tests = [
    ['Course 10 Assignments', '/courses/10/assignments'],
    ['Course 11 Assignments', '/courses/11/assignments'],
    ['Course 12 Assignments', '/courses/12/assignments'],
    ['Assignment 1', '/assignments/1'],
    ['Assignment 2', '/assignments/2'],
    ['Assignment 3', '/assignments/3'],
];

$passed = 0;
$total = count($tests);

foreach ($tests as $test) {
    if (testEndpoint($test[0], $test[1], $studentToken)) {
        $passed++;
    }
}

echo "============================================================\n";
echo "📊 ASSIGNMENT ENDPOINT TEST RESULTS\n";
echo "============================================================\n";
echo "Total Tests: $total\n";
echo "✅ Passed: $passed\n";
echo "❌ Failed: " . ($total - $passed) . "\n";
echo "📈 Success Rate: " . round(($passed / $total) * 100, 2) . "%\n";
echo "============================================================\n";
