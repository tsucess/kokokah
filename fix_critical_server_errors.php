<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING CRITICAL SERVER ERRORS (500) - PRIORITY FIXES\n";
echo "========================================================\n\n";

// Get tokens
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
$adminToken = trim($adminMatches[1]);
$studentToken = trim($studentMatches[1]);

echo "🔐 Using Admin Token: " . substr($adminToken, 0, 30) . "...\n\n";

// List of endpoints that returned 500 errors
$serverErrorEndpoints = [
    'api/analytics/predictive',
    'api/analytics/real-time', 
    'api/analytics/revenue',
    'api/coupons',
    'api/coupons/admin/analytics',
    'api/coupons/user/available',
    'api/learning-paths'
];

echo "🎯 Testing " . count($serverErrorEndpoints) . " endpoints that had server errors:\n\n";

$fixedCount = 0;
$stillBrokenCount = 0;

foreach ($serverErrorEndpoints as $uri) {
    echo "🧪 Testing: " . str_pad($uri, 40) . " ";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/' . $uri);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $adminToken
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        echo "✅ FIXED! ($httpCode)\n";
        $fixedCount++;
    } else {
        echo "❌ Still broken ($httpCode)\n";
        $stillBrokenCount++;
        
        // Get detailed error information
        if ($response) {
            $errorData = json_decode($response, true);
            if (isset($errorData['message'])) {
                echo "   Error: " . $errorData['message'] . "\n";
            }
        }
    }
}

echo "\n========================================================\n";
echo "📊 CRITICAL ERROR FIX RESULTS:\n";
echo "========================================================\n";
echo "✅ Fixed: $fixedCount endpoints\n";
echo "❌ Still broken: $stillBrokenCount endpoints\n";

if ($stillBrokenCount > 0) {
    echo "\n🔧 ENDPOINTS THAT STILL NEED FIXING:\n";
    
    foreach ($serverErrorEndpoints as $uri) {
        echo "\n🧪 Re-testing: $uri\n";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/' . $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $adminToken
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            echo "❌ Status: $httpCode\n";
            if ($response) {
                $errorData = json_decode($response, true);
                echo "📝 Response: " . substr($response, 0, 200) . "...\n";
            }
        }
    }
}

echo "\n========================================================\n";
echo "🎯 NEXT ACTIONS:\n";
echo "========================================================\n";

if ($fixedCount === count($serverErrorEndpoints)) {
    echo "🎉 ALL CRITICAL ERRORS FIXED!\n";
    echo "✅ Ready to proceed with comprehensive testing\n";
} else {
    echo "⚠️  $stillBrokenCount endpoints still need attention\n";
    echo "🔧 Focus on these controllers:\n";
    echo "   • AnalyticsController (predictive, real-time, revenue)\n";
    echo "   • CouponController (index, analytics, getUserCoupons)\n";
    echo "   • LearningPathController (index)\n";
}

echo "========================================================\n";
