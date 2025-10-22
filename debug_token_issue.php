<?php

echo "🔍 DEBUGGING TOKEN ISSUE\n";
echo "=======================\n\n";

// Load token
$tokenContent = file_get_contents('auth_tokens.txt');
echo "Raw token content:\n";
var_dump($tokenContent);
echo "\n";

if (preg_match('/STUDENT_TOKEN=(.+)/', $tokenContent, $match)) {
    $token = trim($match[1]);
    echo "Extracted token (with trim):\n";
    var_dump($token);
    echo "\n";
    
    echo "Token length: " . strlen($token) . "\n";
    echo "Token bytes: " . bin2hex($token) . "\n\n";
    
    // Test with curl
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "http://127.0.0.1:8000/api/user",
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
    
    echo "Response status: $httpCode\n";
    echo "Response body: " . substr($response, 0, 100) . "...\n";
}

echo "\n========================================\n";
