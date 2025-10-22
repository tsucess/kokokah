<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 TESTING FIXED ENDPOINTS\n";
echo "============================================================\n\n";

// Get tokens
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
$adminToken = trim($adminMatches[1]);
$studentToken = trim($studentMatches[1]);

echo "🔐 Using Authentication Tokens:\n";
echo "Admin Token: " . substr($adminToken, 0, 30) . "...\n";
echo "Student Token: " . substr($studentToken, 0, 30) . "...\n\n";

// Test the 3 previously failing endpoints
$fixedTests = [
    [
        'name' => '/user endpoint (Fixed response format)',
        'url' => '/user',
        'token' => $studentToken,
        'expected' => 'success: true, data: user object'
    ],
    [
        'name' => '/search endpoint (Added general search route)',
        'url' => '/search?q=course',
        'token' => $studentToken,
        'expected' => 'global search results'
    ],
    [
        'name' => '/admin/stats endpoint (Added missing route)',
        'url' => '/admin/stats',
        'token' => $adminToken,
        'expected' => 'database statistics'
    ]
];

$passedTests = 0;
$totalTests = count($fixedTests);

foreach ($fixedTests as $test) {
    echo "🧪 Testing: {$test['name']}\n";
    echo "   URL: {$test['url']}\n";
    echo "   Expected: {$test['expected']}\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api' . $test['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $test['token']
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "   Result: HTTP $httpCode\n";
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        
        if (isset($data['success']) && $data['success'] === true) {
            echo "   ✅ SUCCESS! Proper response format\n";
            $passedTests++;
            
            // Show sample data structure
            if (isset($data['data'])) {
                if (is_array($data['data'])) {
                    $keys = array_keys($data['data']);
                    echo "   📊 Data keys: " . implode(', ', array_slice($keys, 0, 5)) . "\n";
                } else {
                    echo "   📊 Data type: " . gettype($data['data']) . "\n";
                }
            }
        } else {
            echo "   ⚠️  HTTP 200 but unexpected response format\n";
            echo "   Response: " . substr($response, 0, 100) . "...\n";
        }
    } else {
        echo "   ❌ FAILED - HTTP $httpCode\n";
        if ($response) {
            $errorData = json_decode($response, true);
            if (isset($errorData['message'])) {
                echo "   Error: " . substr($errorData['message'], 0, 100) . "\n";
            } else {
                echo "   Raw error: " . substr($response, 0, 100) . "\n";
            }
        }
    }
    
    echo "\n";
}

echo "============================================================\n";
echo "📊 FIXED ENDPOINTS TEST RESULTS\n";
echo "============================================================\n";
echo "Total Fixed Tests: $totalTests\n";
echo "✅ Passed: $passedTests\n";
echo "❌ Failed: " . ($totalTests - $passedTests) . "\n";
echo "📈 Success Rate: " . round(($passedTests / $totalTests) * 100, 2) . "%\n";
echo "============================================================\n";

if ($passedTests === $totalTests) {
    echo "🎉 EXCELLENT! All previously failing endpoints are now fixed!\n";
    echo "🚀 Ready to run comprehensive test for 100% success rate!\n";
} else {
    echo "⚠️  Some endpoints still need attention.\n";
}

echo "============================================================\n";
