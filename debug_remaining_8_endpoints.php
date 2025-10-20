<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 DEBUGGING REMAINING 8 FAILING ENDPOINTS\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
$studentToken = trim($studentMatches[1]);
$adminToken = trim($adminMatches[1]);

echo "Using Student Token: " . substr($studentToken, 0, 30) . "...\n";
echo "Using Admin Token: " . substr($adminToken, 0, 30) . "...\n\n";

$failingEndpoints = [
    'Student Dashboard' => [
        'url' => '/dashboard/student',
        'token' => $studentToken,
        'expected_issue' => 'Database/500 error',
        'fix_type' => 'Controller/Database fix needed'
    ],
    'Learning Analytics (Student Token)' => [
        'url' => '/analytics/learning',
        'token' => $studentToken,
        'expected_issue' => '403 - needs admin token',
        'fix_type' => 'Test configuration - use admin token'
    ],
    'Learning Analytics (Admin Token)' => [
        'url' => '/analytics/learning',
        'token' => $adminToken,
        'expected_issue' => 'Should work',
        'fix_type' => 'Verify working with admin token'
    ],
    'Course Performance Analytics' => [
        'url' => '/analytics/course-performance',
        'token' => $adminToken,
        'expected_issue' => 'Should work',
        'fix_type' => 'Verify working with admin token'
    ],
    'Student Progress Analytics' => [
        'url' => '/analytics/student-progress',
        'token' => $adminToken,
        'expected_issue' => 'Should work',
        'fix_type' => 'Verify working with admin token'
    ],
    'Engagement Analytics' => [
        'url' => '/analytics/engagement',
        'token' => $adminToken,
        'expected_issue' => 'Should work',
        'fix_type' => 'Verify working with admin token'
    ],
    'Content Search (No Params)' => [
        'url' => '/search/content',
        'token' => $studentToken,
        'expected_issue' => '422 - missing parameters',
        'fix_type' => 'Test configuration - add parameters'
    ],
    'Content Search (With Params)' => [
        'url' => '/search/content?q=math&course_id=10',
        'token' => $studentToken,
        'expected_issue' => 'Should work',
        'fix_type' => 'Verify working with parameters'
    ],
    'Search Suggestions (No Params)' => [
        'url' => '/search/suggestions',
        'token' => $studentToken,
        'expected_issue' => '422 - missing parameters',
        'fix_type' => 'Test configuration - add parameters'
    ],
    'Search Suggestions (With Params)' => [
        'url' => '/search/suggestions?q=math&type=courses',
        'token' => $studentToken,
        'expected_issue' => 'Should work',
        'fix_type' => 'Verify working with parameters'
    ],
    'Search Filters' => [
        'url' => '/search/filters',
        'token' => $studentToken,
        'expected_issue' => 'Database/500 error',
        'fix_type' => 'Controller/Database fix needed'
    ]
];

foreach ($failingEndpoints as $name => $config) {
    echo "🔍 Testing $name:\n";
    echo "   URL: {$config['url']}\n";
    echo "   Expected Issue: {$config['expected_issue']}\n";
    echo "   Fix Type: {$config['fix_type']}\n";
    
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
    
    echo "   Result: HTTP $httpCode\n";
    
    if ($httpCode === 200) {
        echo "   ✅ WORKING! This endpoint is actually fine.\n";
    } elseif ($httpCode === 403) {
        echo "   🔒 PERMISSION DENIED - Needs different role/token\n";
    } elseif ($httpCode === 422) {
        echo "   📝 VALIDATION ERROR - Missing required parameters\n";
    } elseif ($httpCode === 500) {
        echo "   ❌ SERVER ERROR - Needs code/database fix\n";
        echo "   Error: " . substr($response, 0, 200) . "...\n";
    } else {
        echo "   ❓ UNEXPECTED: " . substr($response, 0, 150) . "...\n";
    }
    echo "\n";
}

echo "============================================================\n";
echo "✅ DEBUGGING COMPLETED!\n";
echo "============================================================\n";
