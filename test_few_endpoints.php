<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING FEW ENDPOINTS WITH FRESH TOKEN\n";
echo "==========================================\n\n";

// Load token
$tokenContent = file_get_contents('auth_tokens.txt');
if (preg_match('/STUDENT_TOKEN=(.+)/', $tokenContent, $match)) {
    $token = trim($match[1]);
    echo "Using token: " . substr($token, 0, 30) . "...\n\n";
    
    // Test endpoints
    $endpoints = [
        ['url' => 'user', 'method' => 'GET'],
        ['url' => 'wallet', 'method' => 'GET'],
        ['url' => 'wallet/transactions', 'method' => 'GET'],
        ['url' => 'courses/my-courses', 'method' => 'GET'],
        ['url' => 'dashboard/student', 'method' => 'GET'],
    ];
    
    foreach ($endpoints as $endpoint) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "http://127.0.0.1:8000/api/{$endpoint['url']}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $token
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $status = ($httpCode >= 200 && $httpCode < 300) ? '✅' : '❌';
        echo "$status {$endpoint['method']} {$endpoint['url']} - $httpCode\n";
    }
}

echo "\n========================================\n";
