<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 DEBUGGING AUTHENTICATION ISSUE\n";
echo "==================================\n\n";

// Load authentication tokens
$authTokens = [];
$tokenContent = file_get_contents('auth_tokens.txt');

if (preg_match('/ADMIN_TOKEN=(.+)/', $tokenContent, $adminMatch)) {
    $authTokens['admin'] = trim($adminMatch[1]);
}
if (preg_match('/STUDENT_TOKEN=(.+)/', $tokenContent, $studentMatch)) {
    $authTokens['student'] = trim($studentMatch[1]);
}
if (preg_match('/INSTRUCTOR_TOKEN=(.+)/', $tokenContent, $instructorMatch)) {
    $authTokens['instructor'] = trim($instructorMatch[1]);
}

echo "🔐 Current tokens:\n";
echo "Admin: " . $authTokens['admin'] . "\n";
echo "Student: " . $authTokens['student'] . "\n";
echo "Instructor: " . $authTokens['instructor'] . "\n\n";

function makeRequest($url, $method = 'GET', $token = null, $data = null, $debug = false) {
    $ch = curl_init();
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    if ($debug) {
        echo "🔍 Making request to: http://127.0.0.1:8000/api/$url\n";
        echo "🔍 Method: $method\n";
        echo "🔍 Headers: " . implode(', ', $headers) . "\n";
        if ($data) {
            echo "🔍 Data: " . json_encode($data) . "\n";
        }
        echo "\n";
    }
    
    curl_setopt_array($ch, [
        CURLOPT_URL => "http://127.0.0.1:8000/api/$url",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_VERBOSE => $debug
    ]);
    
    if ($data && ($method === 'POST' || $method === 'PUT')) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['status' => 0, 'body' => $error];
    }
    
    return ['status' => $httpCode, 'body' => $response];
}

// Test each token individually
echo "🧪 TESTING EACH TOKEN:\n";
echo "======================\n\n";

foreach ($authTokens as $role => $token) {
    echo "🔍 Testing $role token: $token\n";
    
    $response = makeRequest('user', 'GET', $token, null, true);
    
    echo "Status: {$response['status']}\n";
    
    if ($response['status'] == 200) {
        $userData = json_decode($response['body'], true);
        if ($userData && isset($userData['data'])) {
            echo "✅ Token VALID - User: {$userData['data']['first_name']} {$userData['data']['last_name']} ({$userData['data']['role']})\n";
        } else {
            echo "✅ Token VALID - Response: " . substr($response['body'], 0, 100) . "...\n";
        }
    } else {
        echo "❌ Token INVALID - Response: " . substr($response['body'], 0, 200) . "...\n";
    }
    
    echo "\n" . str_repeat("-", 50) . "\n\n";
}

// Test a few specific endpoints with detailed debugging
echo "🧪 TESTING SPECIFIC ENDPOINTS WITH DEBUG:\n";
echo "=========================================\n\n";

$testEndpoints = [
    ['url' => 'wallet', 'method' => 'GET', 'token' => 'student', 'description' => 'Get wallet info'],
    ['url' => 'courses/my-courses', 'method' => 'GET', 'token' => 'student', 'description' => 'Get my courses'],
    ['url' => 'dashboard/student', 'method' => 'GET', 'token' => 'student', 'description' => 'Student dashboard'],
];

foreach ($testEndpoints as $endpoint) {
    echo "🔍 Testing: {$endpoint['method']} {$endpoint['url']} ({$endpoint['description']})\n";
    
    $token = $authTokens[$endpoint['token']];
    $response = makeRequest($endpoint['url'], $endpoint['method'], $token, null, true);
    
    echo "Status: {$response['status']}\n";
    echo "Response: " . substr($response['body'], 0, 300) . "...\n";
    
    echo "\n" . str_repeat("-", 50) . "\n\n";
}

// Check if tokens exist in database
echo "🔍 CHECKING TOKENS IN DATABASE:\n";
echo "===============================\n\n";

use App\Models\User;

foreach ($authTokens as $role => $token) {
    echo "🔍 Checking $role token in database: $token\n";
    
    // Extract token ID and hash
    $tokenParts = explode('|', $token);
    if (count($tokenParts) == 2) {
        $tokenId = $tokenParts[0];
        $tokenHash = $tokenParts[1];
        
        echo "Token ID: $tokenId\n";
        echo "Token Hash: " . substr($tokenHash, 0, 20) . "...\n";
        
        // Check if token exists in personal_access_tokens table
        $tokenRecord = \DB::table('personal_access_tokens')
            ->where('id', $tokenId)
            ->first();
            
        if ($tokenRecord) {
            echo "✅ Token found in database\n";
            echo "Token name: {$tokenRecord->name}\n";
            echo "Tokenable ID: {$tokenRecord->tokenable_id}\n";
            echo "Created: {$tokenRecord->created_at}\n";
            echo "Last used: {$tokenRecord->last_used_at}\n";
            
            // Check if token hash matches
            $hashedToken = hash('sha256', $tokenHash);
            if (hash_equals($tokenRecord->token, $hashedToken)) {
                echo "✅ Token hash matches\n";
            } else {
                echo "❌ Token hash does NOT match\n";
                echo "Expected: {$tokenRecord->token}\n";
                echo "Got: $hashedToken\n";
            }
            
            // Get user info
            $user = User::find($tokenRecord->tokenable_id);
            if ($user) {
                echo "✅ User found: {$user->first_name} {$user->last_name} ({$user->role})\n";
            } else {
                echo "❌ User not found for tokenable_id: {$tokenRecord->tokenable_id}\n";
            }
        } else {
            echo "❌ Token NOT found in database\n";
        }
    } else {
        echo "❌ Invalid token format\n";
    }
    
    echo "\n" . str_repeat("-", 50) . "\n\n";
}

// Check Laravel Sanctum configuration
echo "🔍 CHECKING LARAVEL SANCTUM CONFIGURATION:\n";
echo "==========================================\n\n";

// Check if Sanctum middleware is properly configured
echo "Sanctum guard: " . config('auth.guards.sanctum.driver') . "\n";
echo "Sanctum provider: " . config('auth.guards.sanctum.provider') . "\n";
echo "Default guard: " . config('auth.defaults.guard') . "\n";

// Check if personal_access_tokens table exists
$tables = \DB::select('SHOW TABLES');
$tableNames = array_map(function($table) {
    return array_values((array)$table)[0];
}, $tables);

if (in_array('personal_access_tokens', $tableNames)) {
    echo "✅ personal_access_tokens table exists\n";
    
    $tokenCount = \DB::table('personal_access_tokens')->count();
    echo "Total tokens in database: $tokenCount\n";
} else {
    echo "❌ personal_access_tokens table does NOT exist\n";
}

echo "\n========================================\n";
echo "🎯 AUTHENTICATION DIAGNOSIS COMPLETE\n";
echo "========================================\n";
