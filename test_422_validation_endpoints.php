<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING 422 VALIDATION ERROR ENDPOINTS\n";
echo "============================================================\n\n";

// Get student token from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
$studentToken = trim($studentMatches[1]);

echo "Using Student Token: " . substr($studentToken, 0, 30) . "...\n\n";

// Test endpoints with missing parameters (should get 422)
echo "🔍 Testing endpoints WITHOUT required parameters (expecting 422):\n\n";

$endpointsWithoutParams = [
    'Content Search (no params)' => [
        'url' => '/search/content',
        'description' => 'Search content without required parameters'
    ],
    'Search Suggestions (no params)' => [
        'url' => '/search/suggestions',
        'description' => 'Get search suggestions without required parameters'
    ]
];

foreach ($endpointsWithoutParams as $name => $config) {
    echo "🔍 Testing $name: {$config['url']}\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api' . $config['url']);
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
    
    echo "   HTTP Code: $httpCode\n";
    if ($httpCode === 422) {
        echo "   ✅ Expected 422 validation error received\n";
    } else {
        echo "   ❌ Unexpected response: " . substr($response, 0, 150) . "...\n";
    }
    echo "\n";
}

// Test endpoints with proper parameters (should get 200)
echo "🔍 Testing endpoints WITH required parameters (expecting 200):\n\n";

$endpointsWithParams = [
    'Content Search (with params)' => [
        'url' => '/search/content?q=mathematics&course_id=10',
        'description' => 'Search content with required parameters'
    ],
    'Search Suggestions (with params)' => [
        'url' => '/search/suggestions?q=math&type=courses',
        'description' => 'Get search suggestions with required parameters'
    ]
];

$successCount = 0;
$totalCount = count($endpointsWithParams);

foreach ($endpointsWithParams as $name => $config) {
    echo "🔍 Testing $name: {$config['url']}\n";
    echo "   Description: {$config['description']}\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api' . $config['url']);
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
        echo "   ✅ SUCCESS! HTTP 200\n";
        $successCount++;
        
        $data = json_decode($response, true);
        if (isset($data['data'])) {
            if (is_array($data['data']) && !empty($data['data'])) {
                $dataInfo = is_array(array_values($data['data'])[0]) ? 
                    count($data['data']) . ' items' : 
                    implode(', ', array_keys($data['data']));
                echo "   📊 Data: $dataInfo\n";
            }
        }
    } else {
        echo "   ❌ FAILED! HTTP $httpCode\n";
        echo "   Response: " . substr($response, 0, 200) . "...\n";
    }
    echo "\n";
}

echo "============================================================\n";
echo "📊 422 VALIDATION ENDPOINTS RESULTS:\n";
echo "   ✅ Working with params: $successCount/$totalCount endpoints\n";
echo "   📈 Success Rate: " . round(($successCount / $totalCount) * 100, 2) . "%\n";

if ($successCount === $totalCount) {
    echo "\n🎉 ALL 422 VALIDATION ERRORS RESOLVED!\n";
    echo "🚀 Search endpoints work when proper parameters are provided!\n";
} else {
    $remaining = $totalCount - $successCount;
    echo "\n⚠️  Still need to fix $remaining more validation endpoints\n";
}

echo "============================================================\n";
