<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING RECOMMENDATION ENDPOINTS\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
$studentToken = trim($studentMatches[1]);

echo "Using Student Token: " . substr($studentToken, 0, 30) . "...\n\n";

$endpoints = [
    'Learning Paths' => '/recommendations/learning-paths',
    'Instructors' => '/recommendations/instructors',
    'Content' => '/recommendations/content'
];

foreach ($endpoints as $name => $endpoint) {
    echo "🔍 Testing $name endpoint: $endpoint\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api' . $endpoint);
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
    
    if ($httpCode === 200) {
        echo "   ✅ $name endpoint working! HTTP 200\n";
        $data = json_decode($response, true);
        if (isset($data['data'])) {
            $dataKeys = array_keys($data['data']);
            echo "   📊 Data sections: " . implode(', ', $dataKeys) . "\n";
        }
    } else {
        echo "   ❌ $name endpoint failed! HTTP $httpCode\n";
        echo "   Response: " . substr($response, 0, 200) . "...\n";
    }
    echo "\n";
}

echo "============================================================\n";
echo "✅ RECOMMENDATION ENDPOINTS TEST COMPLETED!\n";
echo "============================================================\n";
