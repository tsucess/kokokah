<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING WALLET ENDPOINTS SPECIFICALLY\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);

$adminToken = trim($adminMatches[1]);
$studentToken = trim($studentMatches[1]);

function testEndpoint($name, $method, $endpoint, $token, $data = null) {
    $url = 'http://localhost:8000/api' . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if ($httpCode === 200) {
        echo "Testing $name... ✅ $httpCode\n";
        if (isset($result['data'])) {
            echo "  Data keys: " . implode(', ', array_keys($result['data'])) . "\n";
        }
    } else {
        echo "Testing $name... ❌ $httpCode";
        if (isset($result['message'])) {
            echo " - " . $result['message'];
        }
        echo "\n";
        
        // Show detailed error for debugging
        if ($httpCode === 500) {
            echo "  Full response: " . substr($response, 0, 200) . "...\n";
        }
    }
    
    return $httpCode === 200;
}

echo "💰 WALLET ENDPOINTS (Detailed Testing):\n";

// Test each wallet endpoint individually
$walletTests = [
    ['Get Wallet Info', 'GET', '/wallet', $studentToken],
    ['Get Wallet Transactions', 'GET', '/wallet/transactions', $studentToken],
    ['Get Wallet Rewards', 'GET', '/wallet/rewards', $studentToken],
];

$passed = 0;
$total = count($walletTests);

foreach ($walletTests as $test) {
    if (testEndpoint($test[0], $test[1], $test[2], $test[3])) {
        $passed++;
    }
}

echo "\n============================================================\n";
echo "📊 WALLET ENDPOINTS TEST RESULTS\n";
echo "============================================================\n";
echo "Total Tests: $total\n";
echo "✅ Passed: $passed\n";
echo "❌ Failed: " . ($total - $passed) . "\n";
echo "📈 Success Rate: " . round(($passed / $total) * 100, 2) . "%\n";

if ($passed < $total) {
    echo "\n🔍 DEBUGGING WALLET ISSUES...\n";
    
    // Check if wallet exists for test user
    try {
        $user = \App\Models\User::where('role', 'student')->first();
        if ($user) {
            echo "Test user ID: {$user->id}\n";
            echo "Test user email: {$user->email}\n";
            
            $wallet = $user->wallet;
            if ($wallet) {
                echo "✅ Wallet exists - Balance: {$wallet->balance} {$wallet->currency}\n";
                echo "✅ Wallet ID: {$wallet->id}\n";
                
                $transactionCount = $wallet->transactions()->count();
                echo "✅ Transactions count: {$transactionCount}\n";
                
                $rewardCount = $user->rewards()->count();
                echo "✅ Rewards count: {$rewardCount}\n";
            } else {
                echo "❌ No wallet found for test user\n";
                
                // Try to create wallet
                $wallet = $user->getOrCreateWallet();
                echo "✅ Created wallet - Balance: {$wallet->balance}\n";
            }
        } else {
            echo "❌ No student user found\n";
        }
    } catch (Exception $e) {
        echo "❌ Error checking wallet: " . $e->getMessage() . "\n";
    }
}
