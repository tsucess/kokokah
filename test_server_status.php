<?php

echo "🔍 CHECKING SERVER STATUS\n";
echo "=========================\n\n";

// Test if server is running
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "http://127.0.0.1:8000/api",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 5,
    CURLOPT_SSL_VERIFYPEER => false
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "❌ Server is NOT running!\n";
    echo "Error: $error\n";
    exit(1);
} else {
    echo "✅ Server is running!\n";
    echo "Status: $httpCode\n";
    echo "Response: " . substr($response, 0, 100) . "...\n";
}

echo "\n========================================\n";
