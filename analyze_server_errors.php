<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 ANALYZING SERVER ERRORS (500) IN DETAIL\n";
echo "==========================================\n\n";

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

// List of endpoints that returned 500 errors
$serverErrorEndpoints = [
    'api/courses/1/lessons',
    'api/lessons/1/progress',
    'api/lessons/1/attachments',
    'api/enrollments/1/progress',
    'api/lessons/1/quizzes',
    'api/quizzes/1/results',
    'api/quizzes/1/analytics',
    'api/courses/1/assignments',
    'api/assignments/1',
    'api/assignments/1/submissions',
    'api/assignments/1/grades',
    'api/courses/1/reviews',
    'api/courses/1/reviews/analytics',
    'api/courses/1/forum',
    'api/courses/1/forum/analytics',
    'api/certificates/1/download',
    'api/grading/gradebook/1',
    'api/grading/courses/1',
    'api/grading/students/1',
    'api/grading/grade-history/1/1',
    'api/grading/reports/1',
    'api/learning-paths/1',
    'api/learning-paths/1/progress',
    'api/learning-paths/1/analytics',
    'api/chat/sessions/1',
    'api/recommendations/courses/1',
    'api/coupons/1',
    'api/settings',
    'api/settings/email/config',
    'api/settings/payment/config',
    'api/settings/features/toggles',
    'api/files/download/1',
    'api/files/preview/1'
];

echo "🧪 Testing server error endpoints to get detailed error messages:\n\n";

foreach ($serverErrorEndpoints as $endpoint) {
    echo "Testing: $endpoint\n";
    $response = makeRequest($endpoint, 'GET', $authTokens['admin']);
    
    if ($response['status'] >= 500) {
        $body = json_decode($response['body'], true);
        if ($body && isset($body['message'])) {
            echo "   ❌ Error: {$body['message']}\n";
            if (isset($body['exception'])) {
                echo "   📍 Exception: {$body['exception']}\n";
            }
            if (isset($body['file'])) {
                echo "   📁 File: {$body['file']}\n";
            }
            if (isset($body['line'])) {
                echo "   📍 Line: {$body['line']}\n";
            }
        } else {
            echo "   ❌ Raw error: " . substr($response['body'], 0, 200) . "...\n";
        }
    } else {
        echo "   ✅ Status: {$response['status']}\n";
    }
    echo "\n";
}

echo "==========================================\n";
echo "Analysis complete. Check errors above for patterns.\n";
