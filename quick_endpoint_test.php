<?php

/**
 * Quick Endpoint Test - Tests core functionality
 */

$baseUrl = 'http://127.0.0.1:8000/api';
$adminToken = '4|eZ6bvHbU9VGXI8NOBQUtvCrVl0WcXg3amjuY31to043963bb';
$studentToken = '10|DZEuSz0Dgth8VkhdpkA1noL6Mi17vo7HjFwGYVczb039b867';

function makeRequest($method, $endpoint, $data = null, $token = null) {
    global $baseUrl;
    
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
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'response' => json_decode($response, true),
        'http_code' => $httpCode,
        'raw' => $response
    ];
}

function testEndpoint($name, $method, $endpoint, $token = null, $data = null) {
    echo "Testing {$name}... ";
    
    $result = makeRequest($method, $endpoint, $data, $token);
    $response = $result['response'];
    $httpCode = $result['http_code'];
    
    if ($httpCode >= 200 && $httpCode < 300) {
        echo "✅ {$httpCode}\n";
        return true;
    } else {
        echo "❌ {$httpCode} - ";
        if (isset($response['message'])) {
            echo $response['message'];
        } elseif (isset($response['error'])) {
            echo $response['error'];
        } else {
            echo "Unknown error";
        }
        echo "\n";
        return false;
    }
}

echo "🧪 QUICK ENDPOINT TEST FOR KOKOKAH.COM LMS\n";
echo str_repeat("=", 60) . "\n\n";

$totalTests = 0;
$passedTests = 0;

// Test public endpoints
echo "🌐 PUBLIC ENDPOINTS:\n";
$tests = [
    ['API Root', 'GET', '/'],
    ['Public Courses', 'GET', '/courses'],
    ['Course Search', 'GET', '/courses/search?q=math'],
    ['Featured Courses', 'GET', '/courses/featured'],
    ['Popular Courses', 'GET', '/courses/popular'],
];

foreach ($tests as [$name, $method, $endpoint]) {
    $totalTests++;
    if (testEndpoint($name, $method, $endpoint)) {
        $passedTests++;
    }
}

echo "\n👤 USER ENDPOINTS (Student Token):\n";
$tests = [
    ['Get Current User', 'GET', '/user', $studentToken],
    ['User Profile', 'GET', '/users/profile', $studentToken],
    ['User Dashboard', 'GET', '/users/dashboard', $studentToken],
    ['User Achievements', 'GET', '/users/achievements', $studentToken],
    ['User Notifications', 'GET', '/users/notifications', $studentToken],
];

foreach ($tests as [$name, $method, $endpoint, $token]) {
    $totalTests++;
    if (testEndpoint($name, $method, $endpoint, $token)) {
        $passedTests++;
    }
}

echo "\n💰 WALLET ENDPOINTS (Student Token):\n";
$tests = [
    ['Get Wallet', 'GET', '/wallet', $studentToken],
    ['Wallet Transactions', 'GET', '/wallet/transactions', $studentToken],
    ['Wallet Rewards', 'GET', '/wallet/rewards', $studentToken],
];

foreach ($tests as [$name, $method, $endpoint, $token]) {
    $totalTests++;
    if (testEndpoint($name, $method, $endpoint, $token)) {
        $passedTests++;
    }
}

echo "\n🏆 BADGE ENDPOINTS (Student Token):\n";
$tests = [
    ['Get All Badges', 'GET', '/badges', $studentToken],
    ['Get My Badges', 'GET', '/my-badges', $studentToken],
    ['Badge Leaderboard', 'GET', '/badges/leaderboard', $studentToken],
];

foreach ($tests as [$name, $method, $endpoint, $token]) {
    $totalTests++;
    if (testEndpoint($name, $method, $endpoint, $token)) {
        $passedTests++;
    }
}

echo "\n📚 COURSE ENDPOINTS (Student Token):\n";
$tests = [
    ['Get Course 11', 'GET', '/courses/11', $studentToken],
    ['Get Course 12', 'GET', '/courses/12', $studentToken],
];

foreach ($tests as [$name, $method, $endpoint, $token]) {
    $totalTests++;
    if (testEndpoint($name, $method, $endpoint, $token)) {
        $passedTests++;
    }
}

echo "\n📝 ENROLLMENT ENDPOINTS (Student Token):\n";
$tests = [
    ['Get Enrollments', 'GET', '/enrollments', $studentToken],
];

foreach ($tests as [$name, $method, $endpoint, $token]) {
    $totalTests++;
    if (testEndpoint($name, $method, $endpoint, $token)) {
        $passedTests++;
    }
}

echo "\n👑 ADMIN ENDPOINTS (Admin Token):\n";
$tests = [
    ['Admin Dashboard', 'GET', '/admin/dashboard', $adminToken],
    ['Admin Users', 'GET', '/admin/users', $adminToken],
    ['Admin Courses', 'GET', '/admin/courses', $adminToken],
    ['Admin Analytics', 'GET', '/admin/analytics', $adminToken],
];

foreach ($tests as [$name, $method, $endpoint, $token]) {
    $totalTests++;
    if (testEndpoint($name, $method, $endpoint, $token)) {
        $passedTests++;
    }
}

echo "\n🔍 SEARCH ENDPOINTS (Student Token):\n";
$tests = [
    ['Global Search', 'GET', '/search/global?q=mathematics', $studentToken],
    ['Course Search', 'GET', '/search/courses?q=math', $studentToken],
    ['User Search', 'GET', '/search/users?q=admin', $studentToken],
];

foreach ($tests as [$name, $method, $endpoint, $token]) {
    $totalTests++;
    if (testEndpoint($name, $method, $endpoint, $token)) {
        $passedTests++;
    }
}

echo "\n📊 ANALYTICS ENDPOINTS (Admin Token):\n";
$tests = [
    ['Learning Analytics', 'GET', '/analytics/learning', $adminToken],
    ['Course Performance', 'GET', '/analytics/course-performance', $adminToken],
    ['Student Progress', 'GET', '/analytics/student-progress', $adminToken],
];

foreach ($tests as [$name, $method, $endpoint, $token]) {
    $totalTests++;
    if (testEndpoint($name, $method, $endpoint, $token)) {
        $passedTests++;
    }
}

echo "\n🔍 AUDIT ENDPOINTS (Admin Token):\n";
$tests = [
    ['Audit Logs', 'GET', '/audit/logs', $adminToken],
    ['User Activity', 'GET', '/audit/users/32/activity', $adminToken],
];

foreach ($tests as [$name, $method, $endpoint, $token]) {
    $totalTests++;
    if (testEndpoint($name, $method, $endpoint, $token)) {
        $passedTests++;
    }
}

echo "\n📁 FILE ENDPOINTS (Student Token):\n";
$tests = [
    ['List Files', 'GET', '/files/list', $studentToken],
    ['Storage Stats', 'GET', '/files/storage/stats', $studentToken],
];

foreach ($tests as [$name, $method, $endpoint, $token]) {
    $totalTests++;
    if (testEndpoint($name, $method, $endpoint, $token)) {
        $passedTests++;
    }
}

echo "\n🔔 NOTIFICATION ENDPOINTS (Student Token):\n";
$tests = [
    ['Get Notifications', 'GET', '/notifications', $studentToken],
    ['Notification Preferences', 'GET', '/notifications/preferences', $studentToken],
];

foreach ($tests as [$name, $method, $endpoint, $token]) {
    $totalTests++;
    if (testEndpoint($name, $method, $endpoint, $token)) {
        $passedTests++;
    }
}

echo "\n🏆 CERTIFICATE ENDPOINTS (Student Token):\n";
$tests = [
    ['Get Certificates', 'GET', '/certificates', $studentToken],
    ['Certificate Templates', 'GET', '/certificates/templates', $studentToken],
];

foreach ($tests as [$name, $method, $endpoint, $token]) {
    $totalTests++;
    if (testEndpoint($name, $method, $endpoint, $token)) {
        $passedTests++;
    }
}

echo "\n🎯 RECOMMENDATION ENDPOINTS (Student Token):\n";
$tests = [
    ['Get Recommendations', 'GET', '/recommendations', $studentToken],
    ['Course Recommendations', 'GET', '/recommendations/courses/11', $studentToken],
];

foreach ($tests as [$name, $method, $endpoint, $token]) {
    $totalTests++;
    if (testEndpoint($name, $method, $endpoint, $token)) {
        $passedTests++;
    }
}

echo "\n🎫 COUPON ENDPOINTS (Student Token):\n";
$tests = [
    ['Get Coupons', 'GET', '/coupons', $studentToken],
    ['Available Coupons', 'GET', '/coupons/user/available', $studentToken],
];

foreach ($tests as [$name, $method, $endpoint, $token]) {
    $totalTests++;
    if (testEndpoint($name, $method, $endpoint, $token)) {
        $passedTests++;
    }
}

// Final Summary
$failedTests = $totalTests - $passedTests;
$successRate = round(($passedTests / $totalTests) * 100, 2);

echo "\n" . str_repeat("=", 60) . "\n";
echo "📊 FINAL RESULTS\n";
echo str_repeat("=", 60) . "\n";
echo "Total Tests: {$totalTests}\n";
echo "✅ Passed: {$passedTests}\n";
echo "❌ Failed: {$failedTests}\n";
echo "📈 Success Rate: {$successRate}%\n";

if ($successRate >= 90) {
    echo "\n🟢 EXCELLENT: System is performing exceptionally well!\n";
} elseif ($successRate >= 75) {
    echo "\n🟡 GOOD: System is performing well with minor issues\n";
} elseif ($successRate >= 50) {
    echo "\n🟠 FAIR: System has moderate issues that need attention\n";
} else {
    echo "\n🔴 POOR: System has significant issues requiring immediate attention\n";
}

echo "\n🎉 Quick endpoint test completed!\n";
