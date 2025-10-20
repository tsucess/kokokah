<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🎯 FINAL TARGETED TEST - FIXING ALL REMAINING ISSUES\n";
echo "====================================================\n\n";

// Load authentication tokens
$authTokens = [];
if (file_exists('auth_tokens.txt')) {
    $content = file_get_contents('auth_tokens.txt');
    preg_match('/ADMIN_TOKEN=(.+)/', $content, $adminMatch);
    preg_match('/STUDENT_TOKEN=(.+)/', $content, $studentMatch);
    preg_match('/INSTRUCTOR_TOKEN=(.+)/', $content, $instructorMatch);
    
    if ($adminMatch) $authTokens['admin'] = trim($adminMatch[1]);
    if ($studentMatch) $authTokens['student'] = trim($studentMatch[1]);
    if ($instructorMatch) $authTokens['instructor'] = trim($instructorMatch[1]);
}

function makeRequest($url, $method = 'GET', $token = null) {
    $ch = curl_init();
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt_array($ch, [
        CURLOPT_URL => "http://127.0.0.1:8000/$url",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['status' => 0, 'body' => $error];
    }
    
    return ['status' => $httpCode, 'body' => $response];
}

// Test endpoints that were previously failing with proper IDs and parameters
$testEndpoints = [
    // Validation errors - add proper parameters
    ['url' => 'api/courses/search?q=test', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/search?q=test', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/search/global?q=test', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/search/users?q=test', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/search/suggestions?q=test', 'token' => 'student', 'expected' => 200],
    
    // Server errors with proper IDs
    ['url' => 'api/courses/1/lessons', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/lessons/1/progress', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/lessons/1/attachments', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/enrollments/1/progress', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/lessons/1/quizzes', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/quizzes/1/results', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/quizzes/1/analytics', 'token' => 'admin', 'expected' => 200],
    ['url' => 'api/courses/1/assignments', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/assignments/1', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/assignments/1/submissions', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/assignments/1/grades', 'token' => 'admin', 'expected' => 200],
    ['url' => 'api/courses/1/reviews', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/courses/1/reviews/analytics', 'token' => 'admin', 'expected' => 200],
    ['url' => 'api/courses/1/forum', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/courses/1/forum/analytics', 'token' => 'admin', 'expected' => 200],
    ['url' => 'api/certificates/1/download', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/grading/gradebook/1', 'token' => 'admin', 'expected' => 200],
    ['url' => 'api/grading/courses/1', 'token' => 'admin', 'expected' => 200],
    ['url' => 'api/grading/students/1', 'token' => 'admin', 'expected' => 200],
    ['url' => 'api/grading/grade-history/1/1', 'token' => 'admin', 'expected' => 200],
    ['url' => 'api/grading/reports/1', 'token' => 'admin', 'expected' => 200],
    ['url' => 'api/learning-paths/1', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/learning-paths/1/progress', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/learning-paths/1/analytics', 'token' => 'admin', 'expected' => 200],
    ['url' => 'api/chat/sessions/1', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/recommendations/courses/1', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/coupons/1', 'token' => 'admin', 'expected' => 200],
    ['url' => 'api/files/download/1', 'token' => 'student', 'expected' => 200],
    ['url' => 'api/files/preview/1', 'token' => 'student', 'expected' => 200],
    
    // Permission-restricted endpoints (should return 403 for non-admin)
    ['url' => 'api/analytics/learning', 'token' => 'admin', 'expected' => 200],
    ['url' => 'api/analytics/course-performance', 'token' => 'admin', 'expected' => 200],
    ['url' => 'api/analytics/student-progress', 'token' => 'admin', 'expected' => 200],
    ['url' => 'api/analytics/revenue', 'token' => 'admin', 'expected' => 200],
    ['url' => 'api/analytics/engagement', 'token' => 'admin', 'expected' => 200],
    ['url' => 'api/analytics/real-time', 'token' => 'admin', 'expected' => 200],
    ['url' => 'api/analytics/predictive', 'token' => 'admin', 'expected' => 200],
];

echo "🧪 Testing " . count($testEndpoints) . " previously failing endpoints:\n\n";

$results = [
    'total' => 0,
    'fixed' => 0,
    'still_failing' => 0
];

foreach ($testEndpoints as $test) {
    $results['total']++;
    $token = $authTokens[$test['token']];
    $response = makeRequest($test['url'], 'GET', $token);
    $status = $response['status'];
    
    if ($status == $test['expected']) {
        echo "✅ {$test['url']} - Fixed! ($status)\n";
        $results['fixed']++;
    } else {
        echo "❌ {$test['url']} - Still failing ($status, expected {$test['expected']})\n";
        $results['still_failing']++;
        
        // Show error details for debugging
        if ($status >= 500) {
            $body = json_decode($response['body'], true);
            if ($body && isset($body['message'])) {
                echo "   Error: " . substr($body['message'], 0, 100) . "...\n";
            }
        }
    }
}

echo "\n====================================================\n";
echo "📊 TARGETED TEST RESULTS\n";
echo "====================================================\n";
echo "Total Tested: {$results['total']}\n";
echo "✅ Fixed: {$results['fixed']}\n";
echo "❌ Still Failing: {$results['still_failing']}\n";

$fixRate = round(($results['fixed'] / $results['total']) * 100, 2);
echo "📈 Fix Rate: $fixRate%\n\n";

if ($fixRate >= 90) {
    echo "🎉 EXCELLENT! 90%+ fix rate - Almost all issues resolved!\n";
} elseif ($fixRate >= 80) {
    echo "✅ VERY GOOD! 80%+ fix rate - Most issues resolved!\n";
} elseif ($fixRate >= 70) {
    echo "👍 GOOD! 70%+ fix rate - Significant progress made!\n";
} else {
    echo "⚠️  MORE WORK NEEDED! <70% fix rate - Additional fixes required.\n";
}

echo "\n🎯 NEXT: Run comprehensive test to see overall improvement\n";
echo "====================================================\n";
