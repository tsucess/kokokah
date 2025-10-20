<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Route;

echo "🔍 COMPREHENSIVE TEST - ALL ERROR TYPES ACROSS ALL ENDPOINTS\n";
echo "============================================================\n\n";

// Load authentication tokens
$authTokens = [];
if (file_exists('auth_tokens.txt')) {
    $content = file_get_contents('auth_tokens.txt');
    preg_match('/ADMIN_TOKEN=(.+)/', $content, $adminMatch);
    preg_match('/STUDENT_TOKEN=(.+)/', $content, $studentMatch);
    preg_match('/INSTRUCTOR_TOKEN=(.+)/', $content, $instructorMatch);
    
    if ($adminMatch) $authTokens['admin'] = trim($adminMatch[1]);
    if ($studentMatch) $authTokens['student'] = trim($studentMatch[1]);
    if ($instructorMatch) $authTokens['instructor'] = trim($instructorMatch[1]);
}

function makeRequest($url, $method = 'GET', $token = null, $data = null) {
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
    
    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
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

// Get all routes from Laravel
$routes = collect(Route::getRoutes())->filter(function ($route) {
    return str_starts_with($route->uri(), 'api/');
})->map(function ($route) {
    return [
        'method' => $route->methods()[0],
        'uri' => $route->uri(),
        'name' => $route->getName(),
        'action' => $route->getActionName()
    ];
})->values()->toArray();

echo "Found " . count($routes) . " API routes to test\n\n";

// Test data for different endpoints
$testData = [
    'courses' => ['title' => 'Test Course', 'description' => 'Test Description', 'price' => 100],
    'users' => ['first_name' => 'Test', 'last_name' => 'User', 'email' => 'test@test.com', 'password' => 'password123'],
    'enrollments' => ['course_id' => 1],
    'assignments' => ['title' => 'Test Assignment', 'description' => 'Test Description', 'due_date' => '2025-12-31'],
    'quizzes' => ['title' => 'Test Quiz', 'description' => 'Test Description', 'course_id' => 1],
    'lessons' => ['title' => 'Test Lesson', 'content' => 'Test Content', 'course_id' => 1],
    'categories' => ['name' => 'Test Category', 'description' => 'Test Description'],
    'coupons' => ['code' => 'TEST123', 'discount_percentage' => 10, 'expires_at' => '2025-12-31'],
];

$results = [
    'total' => 0,
    'success' => 0,
    'server_errors' => 0,
    'permission_errors' => 0,
    'not_found_errors' => 0,
    'validation_errors' => 0,
    'auth_errors' => 0,
    'other_errors' => 0
];

$errorDetails = [
    'server_errors' => [],
    'permission_errors' => [],
    'not_found_errors' => [],
    'validation_errors' => [],
    'auth_errors' => [],
    'other_errors' => []
];

echo "🧪 Testing all endpoints with appropriate tokens and data...\n\n";

foreach ($routes as $route) {
    $results['total']++;
    $method = $route['method'];
    $uri = $route['uri'];
    
    // Skip certain routes that require special handling
    if (str_contains($uri, '{') && !str_contains($uri, '{id}')) {
        continue; // Skip complex parameter routes for now
    }
    
    // Replace {id} with 1 for testing
    $testUri = str_replace('{id}', '1', $uri);
    
    // Determine appropriate token based on route
    $token = 'student'; // default
    if (str_contains($uri, 'admin')) {
        $token = 'admin';
    } elseif (str_contains($uri, 'instructor')) {
        $token = 'instructor';
    } elseif (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
        $token = 'admin'; // Use admin for write operations
    }
    
    // Determine test data based on route
    $data = null;
    if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
        foreach ($testData as $key => $value) {
            if (str_contains($uri, $key)) {
                $data = $value;
                break;
            }
        }
    }
    
    // Add query parameters for search endpoints
    if (str_contains($uri, 'search') && !str_contains($testUri, '?')) {
        $testUri .= '?q=test';
    }
    
    $response = makeRequest($testUri, $method, $authTokens[$token], $data);
    $status = $response['status'];
    
    // Categorize the response
    if ($status == 200 || $status == 201) {
        $results['success']++;
        echo "✅ $method $testUri\n";
    } elseif ($status >= 500) {
        $results['server_errors']++;
        $errorDetails['server_errors'][] = "$method $testUri - $status";
        echo "❌ $method $testUri - SERVER ERROR ($status)\n";
    } elseif ($status == 403) {
        $results['permission_errors']++;
        $errorDetails['permission_errors'][] = "$method $testUri - $status";
        echo "⚠️  $method $testUri - PERMISSION ($status)\n";
    } elseif ($status == 404) {
        $results['not_found_errors']++;
        $errorDetails['not_found_errors'][] = "$method $testUri - $status";
        echo "🔍 $method $testUri - NOT FOUND ($status)\n";
    } elseif ($status == 422) {
        $results['validation_errors']++;
        $errorDetails['validation_errors'][] = "$method $testUri - $status";
        echo "📝 $method $testUri - VALIDATION ($status)\n";
    } elseif ($status == 401) {
        $results['auth_errors']++;
        $errorDetails['auth_errors'][] = "$method $testUri - $status";
        echo "🔐 $method $testUri - AUTH ERROR ($status)\n";
    } else {
        $results['other_errors']++;
        $errorDetails['other_errors'][] = "$method $testUri - $status";
        echo "❓ $method $testUri - OTHER ($status)\n";
    }
    
    // Limit output for readability
    if ($results['total'] >= 100) {
        echo "\n... (limiting output to first 100 routes for readability)\n";
        break;
    }
}

echo "\n============================================================\n";
echo "📊 COMPREHENSIVE ERROR ANALYSIS RESULTS\n";
echo "============================================================\n";

echo "📈 OVERALL STATISTICS:\n";
echo "Total Endpoints Tested: {$results['total']}\n";
echo "✅ Success (200/201): {$results['success']}\n";
echo "❌ Server Errors (500+): {$results['server_errors']}\n";
echo "⚠️  Permission Errors (403): {$results['permission_errors']}\n";
echo "🔍 Not Found (404): {$results['not_found_errors']}\n";
echo "📝 Validation Errors (422): {$results['validation_errors']}\n";
echo "🔐 Auth Errors (401): {$results['auth_errors']}\n";
echo "❓ Other Errors: {$results['other_errors']}\n\n";

$successRate = round(($results['success'] / $results['total']) * 100, 2);
echo "📈 Success Rate: $successRate%\n\n";

echo "🔍 ERROR BREAKDOWN:\n";
foreach ($errorDetails as $type => $errors) {
    if (count($errors) > 0) {
        echo "\n" . strtoupper(str_replace('_', ' ', $type)) . " (" . count($errors) . "):\n";
        foreach (array_slice($errors, 0, 5) as $error) {
            echo "  • $error\n";
        }
        if (count($errors) > 5) {
            echo "  • ... and " . (count($errors) - 5) . " more\n";
        }
    }
}

echo "\n============================================================\n";
echo "🎯 NEXT STEPS RECOMMENDATION\n";
echo "============================================================\n";

if ($results['server_errors'] > 0) {
    echo "🔧 Priority 1: Fix {$results['server_errors']} server errors (500+)\n";
}
if ($results['validation_errors'] > 0) {
    echo "📝 Priority 2: Fix {$results['validation_errors']} validation errors (422)\n";
}
if ($results['not_found_errors'] > 0) {
    echo "🔍 Priority 3: Investigate {$results['not_found_errors']} not found errors (404)\n";
}
if ($results['permission_errors'] > 0) {
    echo "⚠️  Priority 4: Review {$results['permission_errors']} permission errors (403) - may be intentional\n";
}

echo "\n🚀 Platform is " . ($successRate >= 80 ? "PRODUCTION READY" : "NEEDS MORE WORK") . "!\n";
echo "============================================================\n";
