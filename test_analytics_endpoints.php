<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING ANALYTICS ENDPOINTS (ADMIN TOKEN)\n";
echo "============================================================\n\n";

// Get admin token from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
$adminToken = trim($adminMatches[1]);

echo "Using Admin Token: " . substr($adminToken, 0, 30) . "...\n\n";

$analyticsEndpoints = [
    'Learning Analytics' => [
        'url' => '/analytics/learning',
        'description' => 'Get comprehensive learning analytics'
    ],
    'Course Performance Analytics' => [
        'url' => '/analytics/course-performance',
        'description' => 'Get course performance analytics'
    ],
    'Student Progress Analytics' => [
        'url' => '/analytics/student-progress',
        'description' => 'Get student progress analytics'
    ],
    'Engagement Analytics' => [
        'url' => '/analytics/engagement',
        'description' => 'Get engagement analytics'
    ]
];

$successCount = 0;
$totalCount = count($analyticsEndpoints);

foreach ($analyticsEndpoints as $name => $config) {
    echo "🔍 Testing $name: {$config['url']}\n";
    echo "   Description: {$config['description']}\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api' . $config['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $adminToken
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
                $sections = array_keys($data['data']);
                echo "   📊 Data sections: " . implode(', ', $sections) . "\n";
            }
        }
    } else {
        echo "   ❌ FAILED! HTTP $httpCode\n";
        echo "   Response: " . substr($response, 0, 200) . "...\n";
    }
    echo "\n";
}

echo "============================================================\n";
echo "📊 ANALYTICS ENDPOINTS RESULTS:\n";
echo "   ✅ Working: $successCount/$totalCount endpoints\n";
echo "   📈 Success Rate: " . round(($successCount / $totalCount) * 100, 2) . "%\n";

if ($successCount === $totalCount) {
    echo "\n🎉 ALL ANALYTICS ENDPOINTS WORKING!\n";
    echo "🚀 403 permission errors have been resolved!\n";
} else {
    $remaining = $totalCount - $successCount;
    echo "\n⚠️  Still need to fix $remaining more analytics endpoints\n";
}

echo "============================================================\n";
