<?php

/**
 * Fix Authentication Tokens
 * Create fresh authentication tokens for testing
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "🔐 FIXING AUTHENTICATION TOKENS\n";
echo str_repeat("=", 50) . "\n\n";

try {
    // Get admin user
    $admin = User::where('role', 'admin')->first();
    if (!$admin) {
        echo "❌ No admin user found\n";
        exit(1);
    }

    // Get student user
    $student = User::where('role', 'student')->first();
    if (!$student) {
        echo "❌ No student user found\n";
        exit(1);
    }

    // Delete old tokens
    $admin->tokens()->delete();
    $student->tokens()->delete();

    // Create fresh tokens
    $adminToken = $admin->createToken('admin-token')->plainTextToken;
    $studentToken = $student->createToken('student-token')->plainTextToken;

    echo "✅ Created fresh authentication tokens:\n\n";
    echo "Admin User: {$admin->email} (ID: {$admin->id})\n";
    echo "Admin Token: {$adminToken}\n\n";
    echo "Student User: {$student->email} (ID: {$student->id})\n";
    echo "Student Token: {$studentToken}\n\n";

    // Test the tokens
    echo "🧪 Testing tokens...\n";
    
    // Test admin token
    $response = makeRequest('GET', '/user', null, $adminToken);
    if ($response['http_code'] === 200) {
        echo "✅ Admin token working\n";
    } else {
        echo "❌ Admin token failed: {$response['http_code']}\n";
    }

    // Test student token
    $response = makeRequest('GET', '/user', null, $studentToken);
    if ($response['http_code'] === 200) {
        echo "✅ Student token working\n";
    } else {
        echo "❌ Student token failed: {$response['http_code']}\n";
    }

    // Save tokens to file for other scripts
    file_put_contents('auth_tokens.txt', "ADMIN_TOKEN={$adminToken}\nSTUDENT_TOKEN={$studentToken}\n");
    echo "\n📄 Tokens saved to auth_tokens.txt\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

function makeRequest($method, $endpoint, $data = null, $token = null) {
    $baseUrl = 'http://127.0.0.1:8000/api';
    $url = $baseUrl . $endpoint;
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'response' => json_decode($response, true),
        'http_code' => $httpCode
    ];
}
