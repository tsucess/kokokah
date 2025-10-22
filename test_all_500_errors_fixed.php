<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING ALL PREVIOUSLY FAILING 500 ERROR ENDPOINTS\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
$studentToken = trim($studentMatches[1]);
$adminToken = trim($adminMatches[1]);

echo "Using Student Token: " . substr($studentToken, 0, 30) . "...\n";
echo "Using Admin Token: " . substr($adminToken, 0, 30) . "...\n\n";

// Previously failing 500 error endpoints
$endpoints = [
    'Featured Courses' => [
        'url' => '/courses/featured',
        'token' => $studentToken,
        'description' => 'Get featured courses'
    ],
    'Assignment 1' => [
        'url' => '/assignments/1',
        'token' => $studentToken,
        'description' => 'Get assignment details'
    ],
    'Badge Analytics' => [
        'url' => '/badges/analytics',
        'token' => $adminToken,
        'description' => 'Get badge analytics'
    ],
    'Progress Certificates' => [
        'url' => '/progress/certificates',
        'token' => $studentToken,
        'description' => 'Get available certificates'
    ],
    'Learning Path Recommendations' => [
        'url' => '/recommendations/learning-paths',
        'token' => $studentToken,
        'description' => 'Get learning path recommendations'
    ],
    'Instructor Recommendations' => [
        'url' => '/recommendations/instructors',
        'token' => $studentToken,
        'description' => 'Get instructor recommendations'
    ],
    'Content Recommendations' => [
        'url' => '/recommendations/content',
        'token' => $studentToken,
        'description' => 'Get content recommendations'
    ]
];

$successCount = 0;
$totalCount = count($endpoints);

foreach ($endpoints as $name => $config) {
    echo "🔍 Testing $name: {$config['url']}\n";
    echo "   Description: {$config['description']}\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api' . $config['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $config['token']
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
        echo "   Response: " . substr($response, 0, 150) . "...\n";
    }
    echo "\n";
}

echo "============================================================\n";
echo "📊 FINAL RESULTS:\n";
echo "   ✅ Working: $successCount/$totalCount endpoints\n";
echo "   📈 Success Rate: " . round(($successCount / $totalCount) * 100, 2) . "%\n";

if ($successCount === $totalCount) {
    echo "\n🎉 CONGRATULATIONS! ALL 500 ERRORS HAVE BEEN FIXED!\n";
    echo "🚀 All previously failing server error endpoints are now working!\n";
} else {
    $remaining = $totalCount - $successCount;
    echo "\n⚠️  Still need to fix $remaining more endpoints\n";
}

echo "============================================================\n";
