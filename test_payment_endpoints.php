<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTING PAYMENT ENDPOINTS SPECIFICALLY\n";
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
            if (is_array($result['data'])) {
                echo "  Data count: " . count($result['data']) . "\n";
            } else {
                echo "  Data keys: " . implode(', ', array_keys($result['data'])) . "\n";
            }
        }
    } else {
        echo "Testing $name... ❌ $httpCode";
        if (isset($result['message'])) {
            echo " - " . $result['message'];
        }
        echo "\n";
        
        // Show detailed error for debugging
        if ($httpCode === 500) {
            echo "  Full response: " . substr($response, 0, 300) . "...\n";
        }
    }
    
    return $httpCode === 200;
}

echo "💳 PAYMENT ENDPOINTS (Detailed Testing):\n";

// Test each payment endpoint individually
$paymentTests = [
    ['Get Payment Gateways', 'GET', '/payments/gateways', $studentToken],
    ['Get Payment History', 'GET', '/payments/history', $studentToken],
];

$passed = 0;
$total = count($paymentTests);

foreach ($paymentTests as $test) {
    if (testEndpoint($test[0], $test[1], $test[2], $test[3])) {
        $passed++;
    }
}

echo "\n============================================================\n";
echo "📊 PAYMENT ENDPOINTS TEST RESULTS\n";
echo "============================================================\n";
echo "Total Tests: $total\n";
echo "✅ Passed: $passed\n";
echo "❌ Failed: " . ($total - $passed) . "\n";
echo "📈 Success Rate: " . round(($passed / $total) * 100, 2) . "%\n";

if ($passed < $total) {
    echo "\n🔍 DEBUGGING PAYMENT ISSUES...\n";
    
    // Check if payment gateways exist
    try {
        $gatewayCount = \App\Models\PaymentGateway::count();
        echo "Payment gateways in database: {$gatewayCount}\n";
        
        if ($gatewayCount == 0) {
            echo "❌ No payment gateways found, creating sample gateways...\n";
            
            // Create sample payment gateways
            \App\Models\PaymentGateway::create([
                'name' => 'Paystack',
                'slug' => 'paystack',
                'is_active' => true,
                'config' => [
                    'public_key' => 'pk_test_sample',
                    'secret_key' => 'sk_test_sample',
                    'webhook_url' => config('app.url') . '/api/payments/webhook/paystack'
                ]
            ]);
            
            \App\Models\PaymentGateway::create([
                'name' => 'Flutterwave',
                'slug' => 'flutterwave',
                'is_active' => true,
                'config' => [
                    'public_key' => 'FLWPUBK_TEST-sample',
                    'secret_key' => 'FLWSECK_TEST-sample',
                    'webhook_url' => config('app.url') . '/api/payments/webhook/flutterwave'
                ]
            ]);
            
            echo "✅ Created sample payment gateways\n";
        }
        
        $paymentCount = \App\Models\Payment::count();
        echo "Payments in database: {$paymentCount}\n";
        
        if ($paymentCount == 0) {
            echo "❌ No payments found, creating sample payment...\n";
            
            $user = \App\Models\User::where('role', 'student')->first();
            $course = \App\Models\Course::first();
            
            if ($user && $course) {
                \App\Models\Payment::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'amount' => 5000.00,
                    'currency' => 'NGN',
                    'gateway' => 'paystack',
                    'gateway_reference' => 'PAY_' . strtoupper(uniqid()),
                    'type' => 'course_purchase',
                    'status' => 'completed',
                    'metadata' => [
                        'user_email' => $user->email,
                        'course_title' => $course->title
                    ],
                    'completed_at' => now()
                ]);
                
                echo "✅ Created sample payment\n";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Error checking payments: " . $e->getMessage() . "\n";
    }
}
