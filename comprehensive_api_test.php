<?php

/**
 * Comprehensive API Test Suite for Kokokah.com LMS
 * Tests all endpoints in the system
 */

require_once 'vendor/autoload.php';

class ApiTestSuite
{
    private $baseUrl = 'http://127.0.0.1:8000/api';
    private $tokens = [];
    private $testData = [];
    private $results = [];
    private $totalTests = 0;
    private $passedTests = 0;
    private $failedTests = 0;

    public function __construct()
    {
        echo "🚀 Starting Comprehensive API Test Suite for Kokokah.com LMS\n";
        echo "=" . str_repeat("=", 60) . "\n\n";
    }

    public function run()
    {
        try {
            // Step 1: Setup and Authentication
            $this->setupTestData();
            $this->testAuthentication();
            
            // Step 2: Test Public Endpoints
            $this->testPublicEndpoints();
            
            // Step 3: Test Authenticated Endpoints
            $this->testUserEndpoints();
            $this->testCourseEndpoints();
            $this->testEnrollmentEndpoints();
            $this->testWalletEndpoints();
            $this->testBadgeEndpoints();
            $this->testAdminEndpoints();
            
            // Step 4: Generate Report
            $this->generateReport();
            
        } catch (Exception $e) {
            echo "❌ Test suite failed with error: " . $e->getMessage() . "\n";
        }
    }

    private function setupTestData()
    {
        echo "📋 Setting up test data...\n";
        
        // Get existing users for testing
        $app = require_once 'bootstrap/app.php';
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        
        $admin = \App\Models\User::where('role', 'admin')->first();
        $student = \App\Models\User::where('role', 'student')->first();
        $instructor = \App\Models\User::where('role', 'instructor')->first();
        
        $this->testData = [
            'admin' => $admin ? [
                'email' => $admin->email,
                'password' => 'admin123',
                'id' => $admin->id
            ] : null,
            'student' => $student ? [
                'email' => $student->email,
                'password' => 'student123',
                'id' => $student->id
            ] : null,
            'instructor' => $instructor ? [
                'email' => $instructor->email,
                'password' => 'instructor123',
                'id' => $instructor->id
            ] : null,
        ];
        
        echo "✅ Test data setup complete\n\n";
    }

    private function testAuthentication()
    {
        echo "🔐 Testing Authentication Endpoints...\n";
        
        // Test login for each role
        foreach (['admin', 'student', 'instructor'] as $role) {
            if ($this->testData[$role]) {
                $this->testLogin($role);
            }
        }
        
        // Test registration
        $this->testRegistration();
        
        echo "\n";
    }

    private function testLogin($role)
    {
        $userData = $this->testData[$role];
        $response = $this->makeRequest('POST', '/login', [
            'email' => $userData['email'],
            'password' => $userData['password']
        ]);

        if (isset($response['success']) && $response['success'] && isset($response['data']['token'])) {
            $this->tokens[$role] = $response['data']['token'];
            $this->recordTest("Login ($role)", true, "Successfully logged in");
        } elseif (isset($response['status']) && $response['status'] === 'success' && isset($response['data']['token'])) {
            $this->tokens[$role] = $response['data']['token'];
            $this->recordTest("Login ($role)", true, "Successfully logged in");
        } else {
            $this->recordTest("Login ($role)", false, "Login failed: " . json_encode($response));
        }
    }

    private function testRegistration()
    {
        $testEmail = 'test_' . time() . '@test.com';
        $response = $this->makeRequest('POST', '/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => $testEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student'
        ]);
        
        $success = isset($response['status']) && $response['status'] === 'success';
        $this->recordTest("Registration", $success, $success ? "User registered successfully" : json_encode($response));
    }

    private function testPublicEndpoints()
    {
        echo "🌐 Testing Public Endpoints...\n";
        
        $publicEndpoints = [
            ['GET', '/', 'API Root'],
            ['GET', '/courses', 'Public Courses List'],
            ['GET', '/courses/search', 'Course Search'],
            ['GET', '/courses/featured', 'Featured Courses'],
            ['GET', '/courses/popular', 'Popular Courses'],
            ['GET', '/settings/public', 'Public Settings'],
        ];
        
        foreach ($publicEndpoints as [$method, $endpoint, $name]) {
            $response = $this->makeRequest($method, $endpoint);
            $success = !isset($response['error']);
            $this->recordTest($name, $success, $success ? "OK" : json_encode($response));
        }
        
        echo "\n";
    }

    private function testUserEndpoints()
    {
        echo "👤 Testing User Endpoints...\n";
        
        if (!isset($this->tokens['student'])) {
            echo "⚠️ Skipping user tests - no student token\n\n";
            return;
        }
        
        $token = $this->tokens['student'];
        
        $userEndpoints = [
            ['GET', '/user', 'Get Current User'],
            ['GET', '/users/profile', 'Get Profile'],
            ['GET', '/users/dashboard', 'User Dashboard'],
            ['GET', '/users/achievements', 'User Achievements'],
            ['GET', '/users/learning-stats', 'Learning Stats'],
            ['GET', '/users/notifications', 'User Notifications'],
        ];
        
        foreach ($userEndpoints as [$method, $endpoint, $name]) {
            $response = $this->makeRequest($method, $endpoint, null, $token);
            $success = isset($response['success']) ? $response['success'] : !isset($response['error']);
            $this->recordTest($name, $success, $success ? "OK" : json_encode($response));
        }
        
        echo "\n";
    }

    private function testCourseEndpoints()
    {
        echo "📚 Testing Course Endpoints...\n";
        
        if (!isset($this->tokens['admin'])) {
            echo "⚠️ Skipping course creation tests - no admin token\n\n";
            return;
        }
        
        $adminToken = $this->tokens['admin'];
        
        // Test course creation
        $courseData = [
            'title' => 'Test Course ' . time(),
            'description' => 'A test course for API testing',
            'category_id' => 10,
            'term_id' => 10,
            'level_id' => 10,
            'price' => 99.99,
            'difficulty' => 'beginner',
            'duration_hours' => 40,
            'max_students' => 100
        ];
        
        $response = $this->makeRequest('POST', '/courses', $courseData, $adminToken);
        $courseCreated = isset($response['success']) && $response['success'];
        $this->recordTest("Create Course", $courseCreated, $courseCreated ? "Course created" : json_encode($response));
        
        if ($courseCreated && isset($response['data']['id'])) {
            $courseId = $response['data']['id'];
            $this->testData['course_id'] = $courseId;
            
            // Test course management endpoints
            $courseEndpoints = [
                ['GET', "/courses/{$courseId}", 'Get Course Details'],
                ['PUT', "/courses/{$courseId}", 'Update Course', ['title' => 'Updated Test Course']],
                ['POST', "/courses/{$courseId}/publish", 'Publish Course'],
            ];
            
            foreach ($courseEndpoints as $endpoint) {
                [$method, $url, $name] = $endpoint;
                $data = isset($endpoint[3]) ? $endpoint[3] : null;
                $response = $this->makeRequest($method, $url, $data, $adminToken);
                $success = isset($response['success']) ? $response['success'] : !isset($response['error']);
                $this->recordTest($name, $success, $success ? "OK" : json_encode($response));
            }
        }
        
        echo "\n";
    }

    private function testEnrollmentEndpoints()
    {
        echo "📝 Testing Enrollment Endpoints...\n";
        
        if (!isset($this->tokens['student']) || !isset($this->testData['course_id'])) {
            echo "⚠️ Skipping enrollment tests - missing student token or course\n\n";
            return;
        }
        
        $studentToken = $this->tokens['student'];
        $courseId = $this->testData['course_id'];
        
        // Test enrollment
        $response = $this->makeRequest('POST', "/courses/{$courseId}/enroll", null, $studentToken);
        $enrolled = isset($response['success']) && $response['success'];
        $this->recordTest("Course Enrollment", $enrolled, $enrolled ? "Enrolled successfully" : json_encode($response));
        
        // Test enrollment list
        $response = $this->makeRequest('GET', '/enrollments', null, $studentToken);
        $success = isset($response['success']) && $response['success'];
        $this->recordTest("Get Enrollments", $success, $success ? "OK" : json_encode($response));
        
        echo "\n";
    }

    private function testWalletEndpoints()
    {
        echo "💰 Testing Wallet Endpoints...\n";
        
        if (!isset($this->tokens['student'])) {
            echo "⚠️ Skipping wallet tests - no student token\n\n";
            return;
        }
        
        $token = $this->tokens['student'];
        
        $walletEndpoints = [
            ['GET', '/wallet', 'Get Wallet'],
            ['GET', '/wallet/transactions', 'Get Transactions'],
            ['GET', '/wallet/rewards', 'Get Rewards'],
        ];
        
        foreach ($walletEndpoints as [$method, $endpoint, $name]) {
            $response = $this->makeRequest($method, $endpoint, null, $token);
            $success = isset($response['success']) ? $response['success'] : !isset($response['error']);
            $this->recordTest($name, $success, $success ? "OK" : json_encode($response));
        }
        
        echo "\n";
    }

    private function testBadgeEndpoints()
    {
        echo "🏆 Testing Badge Endpoints...\n";
        
        if (!isset($this->tokens['student'])) {
            echo "⚠️ Skipping badge tests - no student token\n\n";
            return;
        }
        
        $token = $this->tokens['student'];
        
        $badgeEndpoints = [
            ['GET', '/badges', 'Get All Badges'],
            ['GET', '/my-badges', 'Get My Badges'],
            ['GET', '/badges/leaderboard', 'Badge Leaderboard'],
        ];
        
        foreach ($badgeEndpoints as [$method, $endpoint, $name]) {
            $response = $this->makeRequest($method, $endpoint, null, $token);
            $success = isset($response['success']) ? $response['success'] : !isset($response['error']);
            $this->recordTest($name, $success, $success ? "OK" : json_encode($response));
        }
        
        echo "\n";
    }

    private function testAdminEndpoints()
    {
        echo "👑 Testing Admin Endpoints...\n";
        
        if (!isset($this->tokens['admin'])) {
            echo "⚠️ Skipping admin tests - no admin token\n\n";
            return;
        }
        
        $token = $this->tokens['admin'];
        
        $adminEndpoints = [
            ['GET', '/admin/dashboard', 'Admin Dashboard'],
            ['GET', '/admin/users', 'Admin Users List'],
            ['GET', '/admin/courses', 'Admin Courses List'],
            ['GET', '/admin/analytics', 'Admin Analytics'],
        ];
        
        foreach ($adminEndpoints as [$method, $endpoint, $name]) {
            $response = $this->makeRequest($method, $endpoint, null, $token);
            $success = isset($response['success']) ? $response['success'] : !isset($response['error']);
            $this->recordTest($name, $success, $success ? "OK" : json_encode($response));
        }
        
        echo "\n";
    }

    private function makeRequest($method, $endpoint, $data = null, $token = null)
    {
        $url = $this->baseUrl . $endpoint;
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
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $decoded = json_decode($response, true);
        if ($decoded === null) {
            return ['error' => 'Invalid JSON response', 'http_code' => $httpCode, 'raw' => $response];
        }
        
        return $decoded;
    }

    private function recordTest($name, $success, $message)
    {
        $this->totalTests++;
        if ($success) {
            $this->passedTests++;
            echo "  ✅ {$name}: {$message}\n";
        } else {
            $this->failedTests++;
            echo "  ❌ {$name}: {$message}\n";
        }
        
        $this->results[] = [
            'name' => $name,
            'success' => $success,
            'message' => $message
        ];
    }

    private function generateReport()
    {
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "📊 TEST RESULTS SUMMARY\n";
        echo str_repeat("=", 60) . "\n\n";
        
        echo "Total Tests: {$this->totalTests}\n";
        echo "✅ Passed: {$this->passedTests}\n";
        echo "❌ Failed: {$this->failedTests}\n";
        
        $successRate = $this->totalTests > 0 ? round(($this->passedTests / $this->totalTests) * 100, 2) : 0;
        echo "📈 Success Rate: {$successRate}%\n\n";
        
        if ($this->failedTests > 0) {
            echo "❌ FAILED TESTS:\n";
            echo str_repeat("-", 40) . "\n";
            foreach ($this->results as $result) {
                if (!$result['success']) {
                    echo "• {$result['name']}: {$result['message']}\n";
                }
            }
            echo "\n";
        }
        
        echo "🎉 Test suite completed!\n";
        
        // Save detailed report to file
        $this->saveDetailedReport();
    }

    private function saveDetailedReport()
    {
        $report = [
            'timestamp' => date('Y-m-d H:i:s'),
            'summary' => [
                'total' => $this->totalTests,
                'passed' => $this->passedTests,
                'failed' => $this->failedTests,
                'success_rate' => $this->totalTests > 0 ? round(($this->passedTests / $this->totalTests) * 100, 2) : 0
            ],
            'results' => $this->results
        ];
        
        file_put_contents('api_test_report.json', json_encode($report, JSON_PRETTY_PRINT));
        echo "📄 Detailed report saved to: api_test_report.json\n";
    }
}

// Run the test suite
$testSuite = new ApiTestSuite();
$testSuite->run();
