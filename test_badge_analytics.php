<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING BADGE ANALYTICS ENDPOINT\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
$adminToken = trim($adminMatches[1]);

echo "Using Admin Token: " . substr($adminToken, 0, 30) . "...\n\n";

// Test the endpoint
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/badges/analytics');
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

echo "HTTP Code: $httpCode\n";

if ($httpCode === 200) {
    echo "✅ Badge analytics endpoint working! HTTP 200\n";
    $data = json_decode($response, true);
    if (isset($data['data']['overview'])) {
        $overview = $data['data']['overview'];
        echo "\n📊 Analytics Overview:\n";
        echo "   - Total badges: " . ($overview['total_badges'] ?? 0) . "\n";
        echo "   - Active badges: " . ($overview['active_badges'] ?? 0) . "\n";
        echo "   - Total awards: " . ($overview['total_awards'] ?? 0) . "\n";
        echo "   - Unique badge holders: " . ($overview['unique_badge_holders'] ?? 0) . "\n";
    }
    
    if (isset($data['data']['popular_badges'])) {
        echo "\n🏆 Popular Badges:\n";
        foreach ($data['data']['popular_badges'] as $badge) {
            echo "   - {$badge['name']}: {$badge['awards_count']} awards\n";
        }
    }
    
    if (isset($data['data']['top_badge_earners'])) {
        echo "\n👑 Top Badge Earners:\n";
        foreach ($data['data']['top_badge_earners'] as $earner) {
            $user = $earner['user'];
            echo "   - Rank {$earner['rank']}: {$user['first_name']} {$user['last_name']} ({$earner['badges_count']} badges, {$earner['total_points']} points)\n";
        }
    }
} else {
    echo "❌ Badge analytics endpoint failed! HTTP $httpCode\n";
    echo "Response: " . substr($response, 0, 500) . "...\n";
}

echo "\n============================================================\n";
echo "✅ BADGE ANALYTICS TEST COMPLETED!\n";
echo "============================================================\n";
