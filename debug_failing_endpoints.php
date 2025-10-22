<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 DEBUGGING FAILING ENDPOINTS\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
$studentToken = trim($studentMatches[1]);

echo "Using Student Token: " . substr($studentToken, 0, 30) . "...\n\n";

function debugEndpoint($name, $endpoint, $token) {
    echo "🔍 Testing: $name\n";
    echo "Endpoint: $endpoint\n";
    
    $url = 'http://localhost:8000/api' . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $token
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "HTTP Code: $httpCode\n";
    echo "Response: " . substr($response, 0, 300) . "...\n";
    echo "---\n\n";
    
    return $httpCode;
}

// Test the endpoints that are failing
$failingEndpoints = [
    ['Get My Courses', '/courses/my-courses'],
    ['Enroll in Course', '/courses/11/enroll'],
    ['Get Course Lessons', '/courses/11/lessons'],
    ['Get Single Lesson', '/lessons/1'],
    ['Get User Profile', '/users/profile'],
    ['Get User Dashboard', '/users/dashboard'],
    ['Student Dashboard', '/dashboard/student'],
    ['Get Wallet Info', '/wallet'],
    ['Get Payment Gateways', '/payments/gateways'],
    ['Get All Badges', '/badges'],
    ['Get My Badges', '/my-badges'],
    ['Get Notifications', '/notifications'],
];

echo "Testing failing endpoints:\n\n";

foreach ($failingEndpoints as $endpoint) {
    $httpCode = debugEndpoint($endpoint[0], $endpoint[1], $studentToken);
    
    if ($httpCode === 401) {
        echo "❌ Authentication failed for {$endpoint[0]}\n";
    } elseif ($httpCode === 403) {
        echo "⚠️  Authorization failed for {$endpoint[0]}\n";
    } elseif ($httpCode === 404) {
        echo "🔍 Endpoint not found for {$endpoint[0]}\n";
    } elseif ($httpCode === 500) {
        echo "💥 Server error for {$endpoint[0]}\n";
    } else {
        echo "✅ Unexpected success for {$endpoint[0]}\n";
    }
    echo "\n";
}

// Let's also check what routes actually exist for these endpoints
echo "🔍 Checking route definitions:\n\n";

$routes = app('router')->getRoutes();
$apiRoutes = [];

foreach ($routes as $route) {
    if (str_starts_with($route->uri(), 'api/')) {
        $uri = str_replace('api/', '', $route->uri());
        $methods = implode('|', $route->methods());
        $middleware = implode(', ', $route->middleware());
        $apiRoutes[] = [
            'uri' => $uri,
            'methods' => $methods,
            'middleware' => $middleware,
            'action' => $route->getActionName()
        ];
    }
}

// Check specific failing routes
$checkRoutes = [
    'courses/my-courses',
    'courses/{id}/enroll',
    'courses/{courseId}/lessons',
    'lessons/{id}',
    'users/profile',
    'users/dashboard',
    'dashboard/student',
    'wallet',
    'payments/gateways',
    'badges',
    'my-badges',
    'notifications'
];

foreach ($checkRoutes as $checkRoute) {
    echo "Route: $checkRoute\n";
    $found = false;
    
    foreach ($apiRoutes as $route) {
        if ($route['uri'] === $checkRoute || 
            (str_contains($route['uri'], '{') && preg_match('#^' . str_replace(['{', '}'], ['[^/]+', ''], $route['uri']) . '$#', $checkRoute))) {
            echo "  ✅ Found: {$route['methods']} - Middleware: {$route['middleware']}\n";
            echo "  Action: {$route['action']}\n";
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        echo "  ❌ Route not found!\n";
    }
    echo "\n";
}

echo "============================================================\n";
