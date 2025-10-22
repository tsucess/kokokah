<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 DETAILED VERIFICATION TEST - DATA QUALITY CHECK\n";
echo "============================================================\n\n";

// Get tokens
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
$adminToken = trim($adminMatches[1]);
$studentToken = trim($studentMatches[1]);

echo "🔐 Using Authentication Tokens:\n";
echo "Admin Token: " . substr($adminToken, 0, 30) . "...\n";
echo "Student Token: " . substr($studentToken, 0, 30) . "...\n\n";

// Test critical endpoints with data validation
$criticalTests = [
    [
        'name' => 'Student Dashboard',
        'url' => '/dashboard/student',
        'token' => $studentToken,
        'expected_sections' => ['user', 'overview', 'current_courses', 'upcoming_deadlines']
    ],
    [
        'name' => 'Featured Courses',
        'url' => '/courses/featured',
        'token' => $studentToken,
        'expected_data' => 'courses array'
    ],
    [
        'name' => 'Learning Analytics',
        'url' => '/analytics/learning',
        'token' => $adminToken,
        'expected_sections' => ['total_students', 'active_courses', 'completion_rate']
    ],
    [
        'name' => 'Search Filters',
        'url' => '/search/filters',
        'token' => $studentToken,
        'expected_sections' => ['categories', 'difficulty_levels', 'price_ranges']
    ],
    [
        'name' => 'Badge Analytics',
        'url' => '/badges/analytics',
        'token' => $adminToken,
        'expected_data' => 'analytics data'
    ],
    [
        'name' => 'Assignment Details',
        'url' => '/assignments/1',
        'token' => $studentToken,
        'expected_sections' => ['id', 'title', 'course']
    ]
];

$passedTests = 0;
$totalTests = count($criticalTests);

foreach ($criticalTests as $test) {
    echo "🧪 Testing: {$test['name']}\n";
    echo "   URL: {$test['url']}\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api' . $test['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $test['token']
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        
        if (isset($data['success']) && $data['success'] === true) {
            echo "   ✅ HTTP 200 - Success response format\n";
            
            // Check data structure
            if (isset($test['expected_sections'])) {
                $responseData = $data['data'] ?? [];
                $missingsections = [];
                
                foreach ($test['expected_sections'] as $section) {
                    if (!isset($responseData[$section])) {
                        $missingsections[] = $section;
                    }
                }
                
                if (empty($missingsections)) {
                    echo "   ✅ All expected data sections present\n";
                    $passedTests++;
                } else {
                    echo "   ⚠️  Missing sections: " . implode(', ', $missingsections) . "\n";
                }
            } else {
                echo "   ✅ Response data structure valid\n";
                $passedTests++;
            }
            
            // Show sample data
            if (isset($data['data'])) {
                $sampleData = is_array($data['data']) ? array_keys($data['data']) : ['data_present'];
                echo "   📊 Data keys: " . implode(', ', array_slice($sampleData, 0, 5)) . "\n";
            }
            
        } else {
            echo "   ❌ Invalid response format\n";
        }
    } else {
        echo "   ❌ HTTP $httpCode - Failed\n";
        if ($response) {
            $errorData = json_decode($response, true);
            if (isset($errorData['message'])) {
                echo "   Error: " . substr($errorData['message'], 0, 100) . "\n";
            }
        }
    }
    
    echo "\n";
}

echo "============================================================\n";
echo "📊 DETAILED VERIFICATION RESULTS\n";
echo "============================================================\n";
echo "Total Critical Tests: $totalTests\n";
echo "✅ Passed: $passedTests\n";
echo "❌ Failed: " . ($totalTests - $passedTests) . "\n";
echo "📈 Success Rate: " . round(($passedTests / $totalTests) * 100, 2) . "%\n";
echo "============================================================\n";

if ($passedTests === $totalTests) {
    echo "🎉 EXCELLENT! All critical endpoints are working with proper data structure!\n";
} else {
    echo "⚠️  Some endpoints need attention for data structure improvements.\n";
}

echo "============================================================\n";
