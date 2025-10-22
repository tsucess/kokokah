<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

echo "🔄 REGENERATING AUTHENTICATION AND TESTING ALL ENDPOINTS\n";
echo "========================================================\n\n";

// Step 1: Create fresh users and tokens
echo "👤 Creating fresh test users...\n";

// Create admin user
$admin = User::updateOrCreate(
    ['email' => 'admin@kokokah.com'],
    [
        'first_name' => 'Test',
        'last_name' => 'Admin',
        'email' => 'admin@kokokah.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
        'is_active' => true,
        'email_verified_at' => now()
    ]
);

// Create student user
$student = User::updateOrCreate(
    ['email' => 'student@kokokah.com'],
    [
        'first_name' => 'Test',
        'last_name' => 'Student',
        'email' => 'student@kokokah.com',
        'password' => Hash::make('password123'),
        'role' => 'student',
        'is_active' => true,
        'email_verified_at' => now()
    ]
);

// Create instructor user
$instructor = User::updateOrCreate(
    ['email' => 'instructor@kokokah.com'],
    [
        'first_name' => 'Test',
        'last_name' => 'Instructor',
        'email' => 'instructor@kokokah.com',
        'password' => Hash::make('password123'),
        'role' => 'instructor',
        'is_active' => true,
        'email_verified_at' => now()
    ]
);

echo "✅ Users created successfully\n";

// Step 2: Generate fresh tokens
echo "🔑 Generating fresh authentication tokens...\n";

// Clear old tokens
$admin->tokens()->delete();
$student->tokens()->delete();
$instructor->tokens()->delete();

// Generate new tokens
$adminToken = $admin->createToken('admin-test-token')->plainTextToken;
$studentToken = $student->createToken('student-test-token')->plainTextToken;
$instructorToken = $instructor->createToken('instructor-test-token')->plainTextToken;

// Save tokens to file
$tokenContent = "ADMIN_TOKEN=$adminToken\n";
$tokenContent .= "STUDENT_TOKEN=$studentToken\n";
$tokenContent .= "INSTRUCTOR_TOKEN=$instructorToken\n";
file_put_contents('auth_tokens.txt', $tokenContent);

echo "✅ Fresh tokens generated and saved\n";
echo "🔐 Admin Token: " . substr($adminToken, 0, 30) . "...\n";
echo "🔐 Student Token: " . substr($studentToken, 0, 30) . "...\n";
echo "🔐 Instructor Token: " . substr($instructorToken, 0, 30) . "...\n\n";

// Step 3: Test critical endpoints with fresh tokens
echo "🧪 Testing critical endpoints with fresh authentication...\n\n";

function makeRequest($url, $method = 'GET', $token = null, $data = []) {
    $ch = curl_init();
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt_array($ch, [
        CURLOPT_URL => "http://127.0.0.1:8000/$url",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true
    ]);
    
    if (!empty($data) && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['status' => 0, 'body' => $error];
    }
    
    return ['status' => $httpCode, 'body' => $response];
}

// Test critical endpoints
$criticalTests = [
    // Admin endpoints
    ['endpoint' => 'api/admin/dashboard', 'token' => $adminToken, 'expected' => 200],
    ['endpoint' => 'api/admin/analytics', 'token' => $adminToken, 'expected' => 200],
    ['endpoint' => 'api/analytics/revenue', 'token' => $adminToken, 'expected' => 200],
    ['endpoint' => 'api/analytics/predictive', 'token' => $adminToken, 'expected' => 200],
    ['endpoint' => 'api/analytics/real-time', 'token' => $adminToken, 'expected' => 200],
    
    // Student endpoints
    ['endpoint' => 'api/user', 'token' => $studentToken, 'expected' => 200],
    ['endpoint' => 'api/dashboard/student', 'token' => $studentToken, 'expected' => 200],
    ['endpoint' => 'api/courses', 'token' => $studentToken, 'expected' => 200],
    ['endpoint' => 'api/progress/overall', 'token' => $studentToken, 'expected' => 200],
    ['endpoint' => 'api/my-badges', 'token' => $studentToken, 'expected' => 200],
    
    // Public endpoints
    ['endpoint' => 'api/courses/featured', 'token' => null, 'expected' => 200],
    ['endpoint' => 'api/courses/popular', 'token' => null, 'expected' => 200],
    ['endpoint' => 'api/category', 'token' => null, 'expected' => 200],
    
    // Previously fixed endpoints
    ['endpoint' => 'api/coupons', 'token' => $adminToken, 'expected' => 200],
    ['endpoint' => 'api/learning-paths', 'token' => $studentToken, 'expected' => 200],
];

$results = ['success' => 0, 'failed' => 0, 'total' => count($criticalTests)];

foreach ($criticalTests as $test) {
    $response = makeRequest($test['endpoint'], 'GET', $test['token']);
    $status = $response['status'];
    
    if ($status == $test['expected']) {
        echo "✅ {$test['endpoint']} - $status\n";
        $results['success']++;
    } else {
        echo "❌ {$test['endpoint']} - $status (expected {$test['expected']})\n";
        $results['failed']++;
        
        // Show error details for debugging
        if ($status >= 400) {
            $body = json_decode($response['body'], true);
            if (isset($body['message'])) {
                echo "   Error: {$body['message']}\n";
            }
        }
    }
}

echo "\n========================================================\n";
echo "📊 CRITICAL ENDPOINT TEST RESULTS\n";
echo "========================================================\n";
echo "Total Tests: {$results['total']}\n";
echo "✅ Successful: {$results['success']}\n";
echo "❌ Failed: {$results['failed']}\n";
echo "📈 Success Rate: " . round(($results['success'] / $results['total']) * 100, 2) . "%\n\n";

if ($results['success'] >= ($results['total'] * 0.8)) {
    echo "🎉 EXCELLENT! 80%+ success rate - System is working well!\n";
} elseif ($results['success'] >= ($results['total'] * 0.6)) {
    echo "👍 GOOD! 60%+ success rate - Minor issues to address.\n";
} else {
    echo "⚠️  NEEDS WORK! <60% success rate - Major fixes required.\n";
}

echo "\n🔑 Fresh authentication tokens saved to auth_tokens.txt\n";
echo "🚀 Ready for comprehensive endpoint testing!\n";
echo "========================================================\n";
