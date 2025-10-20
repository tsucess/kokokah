<?php

echo "🎯 COMPREHENSIVE ENDPOINT TEST - FRESH TOKENS\n";
echo "=============================================\n\n";

// Load tokens fresh each time
function getTokens() {
    $tokens = [];
    $tokenContent = file_get_contents('auth_tokens.txt');
    
    if (preg_match('/ADMIN_TOKEN=(.+)/', $tokenContent, $match)) {
        $tokens['admin'] = trim($match[1]);
    }
    if (preg_match('/STUDENT_TOKEN=(.+)/', $tokenContent, $match)) {
        $tokens['student'] = trim($match[1]);
    }
    if (preg_match('/INSTRUCTOR_TOKEN=(.+)/', $tokenContent, $match)) {
        $tokens['instructor'] = trim($match[1]);
    }
    
    return $tokens;
}

$tokens = getTokens();

echo "🔐 Loaded tokens:\n";
echo "Admin: " . substr($tokens['admin'], 0, 20) . "...\n";
echo "Student: " . substr($tokens['student'], 0, 20) . "...\n";
echo "Instructor: " . substr($tokens['instructor'], 0, 20) . "...\n\n";

// Test function
function testEndpoint($url, $method = 'GET', $token = null) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "http://127.0.0.1:8000/api/$url",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            $token ? 'Authorization: Bearer ' . $token : ''
        ],
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode;
}

// Test key endpoints
$testEndpoints = [
    ['url' => 'user', 'method' => 'GET', 'token' => 'student', 'name' => 'Get user'],
    ['url' => 'wallet', 'method' => 'GET', 'token' => 'student', 'name' => 'Get wallet'],
    ['url' => 'wallet/transactions', 'method' => 'GET', 'token' => 'student', 'name' => 'Get wallet transactions'],
    ['url' => 'courses/my-courses', 'method' => 'GET', 'token' => 'student', 'name' => 'Get my courses'],
    ['url' => 'dashboard/student', 'method' => 'GET', 'token' => 'student', 'name' => 'Get student dashboard'],
    ['url' => 'admin/dashboard', 'method' => 'GET', 'token' => 'admin', 'name' => 'Get admin dashboard'],
    ['url' => 'admin/users', 'method' => 'GET', 'token' => 'admin', 'name' => 'Get admin users'],
    ['url' => 'instructor/courses', 'method' => 'GET', 'token' => 'instructor', 'name' => 'Get instructor courses'],
];

$success = 0;
$failed = 0;

echo "🧪 TESTING KEY ENDPOINTS:\n";
echo "=========================\n";

foreach ($testEndpoints as $endpoint) {
    $token = $endpoint['token'] ? $tokens[$endpoint['token']] : null;
    $status = testEndpoint($endpoint['url'], $endpoint['method'], $token);
    
    if ($status >= 200 && $status < 300) {
        echo "✅ {$endpoint['name']} - $status\n";
        $success++;
    } else {
        echo "❌ {$endpoint['name']} - $status\n";
        $failed++;
    }
}

echo "\n========================================\n";
echo "Success: $success / " . ($success + $failed) . "\n";
echo "Success Rate: " . round(($success / ($success + $failed)) * 100, 2) . "%\n";
echo "========================================\n";
