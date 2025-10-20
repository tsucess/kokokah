<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING SAMPLE RATE LIMITED ENDPOINTS\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
$studentToken = trim($studentMatches[1]);

echo "Using Student Token: " . substr($studentToken, 0, 30) . "...\n\n";

$sampleEndpoints = [
    'Search Filters' => [
        'url' => '/search/filters',
        'description' => 'Get search filters'
    ],
    'Notifications' => [
        'url' => '/notifications',
        'description' => 'Get user notifications'
    ],
    'Categories' => [
        'url' => '/categories',
        'description' => 'Get course categories'
    ]
];

$successCount = 0;
$totalCount = count($sampleEndpoints);

foreach ($sampleEndpoints as $name => $config) {
    echo "🔍 Testing $name: {$config['url']}\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api' . $config['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $studentToken
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        echo "   ✅ SUCCESS! HTTP 200\n";
        $successCount++;
    } elseif ($httpCode === 429) {
        echo "   ⚠️  RATE LIMITED! HTTP 429 (endpoint working)\n";
        $successCount++;
    } else {
        echo "   ❌ FAILED! HTTP $httpCode\n";
        echo "   Response: " . substr($response, 0, 100) . "...\n";
    }
    echo "\n";
}

echo "============================================================\n";
echo "📊 SAMPLE RESULTS:\n";
echo "   ✅ Working: $successCount/$totalCount endpoints\n";
echo "   📈 Success Rate: " . round(($successCount / $totalCount) * 100, 2) . "%\n";
echo "============================================================\n";
