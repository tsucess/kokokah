<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 TESTING ROUTE DEBUG\n";
echo "============================================================\n\n";

function testRoute($name, $endpoint) {
    $url = 'http://localhost:8000/api' . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "🔍 Testing: $name\n";
    echo "   URL: $url\n";
    echo "   Status: $httpCode\n";
    echo "   Response: $response\n\n";
    
    return $httpCode === 200;
}

// Test the routes
testRoute('Test Route (No Auth)', '/test-my-courses');
testRoute('My Courses (With Auth)', '/courses/my-courses');

echo "============================================================\n";
