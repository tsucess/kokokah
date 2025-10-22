<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING SEARCH FILTERS FIX\n";
echo "============================================================\n\n";

// Get student token from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
$studentToken = trim($studentMatches[1]);

echo "Using Student Token: " . substr($studentToken, 0, 30) . "...\n\n";

echo "🔍 Testing Search Filters: /search/filters\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/search/filters');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer ' . $studentToken
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Result: HTTP $httpCode\n";

if ($httpCode === 200) {
    echo "✅ SUCCESS! Search Filters is now working!\n";
    
    $data = json_decode($response, true);
    if (isset($data['data'])) {
        $filters = $data['data'];
        echo "\n📊 Available Filters:\n";
        foreach ($filters as $filterType => $filterData) {
            if (is_array($filterData)) {
                $count = count($filterData);
                echo "   - $filterType: $count items\n";
                if ($filterType === 'categories' && $count > 0) {
                    echo "     Sample: " . json_encode($filterData[0]) . "\n";
                }
            } else {
                echo "   - $filterType: " . gettype($filterData) . "\n";
            }
        }
    }
} elseif ($httpCode === 500) {
    echo "❌ STILL FAILING! HTTP 500\n";
    echo "Error: " . substr($response, 0, 300) . "...\n";
} else {
    echo "❓ UNEXPECTED: HTTP $httpCode\n";
    echo "Response: " . substr($response, 0, 200) . "...\n";
}

echo "\n============================================================\n";
echo "✅ SEARCH FILTERS TEST COMPLETED!\n";
echo "============================================================\n";
