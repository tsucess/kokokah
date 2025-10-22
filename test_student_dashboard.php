<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING STUDENT DASHBOARD FIX\n";
echo "============================================================\n\n";

// Get student token from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
$studentToken = trim($studentMatches[1]);

echo "Using Student Token: " . substr($studentToken, 0, 30) . "...\n\n";

echo "🔍 Testing Student Dashboard: /dashboard/student\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/dashboard/student');
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
    echo "✅ SUCCESS! Student Dashboard is now working!\n";
    
    $data = json_decode($response, true);
    if (isset($data['data'])) {
        $dashboard = $data['data'];
        echo "\n📊 Dashboard Data Sections:\n";
        foreach ($dashboard as $section => $content) {
            if (is_array($content)) {
                $count = count($content);
                echo "   - $section: $count items\n";
            } else {
                echo "   - $section: " . (is_string($content) ? substr($content, 0, 50) : gettype($content)) . "\n";
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
echo "✅ STUDENT DASHBOARD TEST COMPLETED!\n";
echo "============================================================\n";
