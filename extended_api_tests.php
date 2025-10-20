<?php

/**
 * Extended API Test Suite - Additional endpoint testing
 * Tests more complex scenarios and edge cases
 */

require_once 'vendor/autoload.php';

class ExtendedApiTestSuite
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
        echo "🔬 Starting Extended API Test Suite\n";
        echo "=" . str_repeat("=", 50) . "\n\n";
        $this->setupTokens();
    }

    private function setupTokens()
    {
        // Use existing tokens from previous tests or create new ones
        $this->tokens = [
            'admin' => '4|eZ6bvHbU9VGXI8NOBQUtvCrVl0WcXg3amjuY31to043963bb',
            'student' => '10|DZEuSz0Dgth8VkhdpkA1noL6Mi17vo7HjFwGYVczb039b867'
        ];
    }

    public function run()
    {
        $this->testSearchEndpoints();
        $this->testFileEndpoints();
        $this->testNotificationEndpoints();
        $this->testAnalyticsEndpoints();
        $this->testQuizEndpoints();
        $this->testForumEndpoints();
        $this->testCertificateEndpoints();
        $this->testRecommendationEndpoints();
        $this->testCouponEndpoints();
        $this->testAuditEndpoints();
        $this->generateReport();
    }

    private function testSearchEndpoints()
    {
        echo "🔍 Testing Search Endpoints...\n";
        
        if (!isset($this->tokens['student'])) {
            echo "⚠️ Skipping search tests - no student token\n\n";
            return;
        }
        
        $token = $this->tokens['student'];
        
        $searchEndpoints = [
            ['GET', '/search/global?q=mathematics', 'Global Search'],
            ['GET', '/search/courses?q=math', 'Course Search'],
            ['GET', '/search/users?q=admin', 'User Search'],
            ['GET', '/search/content?q=lesson', 'Content Search'],
            ['GET', '/search/suggestions?q=mat', 'Search Suggestions'],
            ['GET', '/search/filters', 'Search Filters'],
        ];
        
        foreach ($searchEndpoints as [$method, $endpoint, $name]) {
            $response = $this->makeRequest($method, $endpoint, null, $token);
            $success = !isset($response['error']) || (isset($response['success']) && $response['success']);
            $this->recordTest($name, $success, $success ? "OK" : $this->getErrorMessage($response));
        }
        
        echo "\n";
    }

    private function testFileEndpoints()
    {
        echo "📁 Testing File Management Endpoints...\n";
        
        if (!isset($this->tokens['student'])) {
            echo "⚠️ Skipping file tests - no student token\n\n";
            return;
        }
        
        $token = $this->tokens['student'];
        
        $fileEndpoints = [
            ['GET', '/files/list', 'List Files'],
            ['GET', '/files/storage/stats', 'Storage Stats'],
        ];
        
        foreach ($fileEndpoints as [$method, $endpoint, $name]) {
            $response = $this->makeRequest($method, $endpoint, null, $token);
            $success = !isset($response['error']) || (isset($response['success']) && $response['success']);
            $this->recordTest($name, $success, $success ? "OK" : $this->getErrorMessage($response));
        }
        
        echo "\n";
    }

    private function testNotificationEndpoints()
    {
        echo "🔔 Testing Notification Endpoints...\n";
        
        if (!isset($this->tokens['student'])) {
            echo "⚠️ Skipping notification tests - no student token\n\n";
            return;
        }
        
        $token = $this->tokens['student'];
        
        $notificationEndpoints = [
            ['GET', '/notifications', 'Get Notifications'],
            ['GET', '/notifications/preferences', 'Get Notification Preferences'],
            ['PUT', '/notifications/read-all', 'Mark All Read'],
        ];
        
        foreach ($notificationEndpoints as [$method, $endpoint, $name]) {
            $response = $this->makeRequest($method, $endpoint, null, $token);
            $success = !isset($response['error']) || (isset($response['success']) && $response['success']);
            $this->recordTest($name, $success, $success ? "OK" : $this->getErrorMessage($response));
        }
        
        echo "\n";
    }

    private function testAnalyticsEndpoints()
    {
        echo "📊 Testing Analytics Endpoints...\n";
        
        if (!isset($this->tokens['admin'])) {
            echo "⚠️ Skipping analytics tests - no admin token\n\n";
            return;
        }
        
        $token = $this->tokens['admin'];
        
        $analyticsEndpoints = [
            ['GET', '/analytics/learning', 'Learning Analytics'],
            ['GET', '/analytics/course-performance', 'Course Performance'],
            ['GET', '/analytics/student-progress', 'Student Progress'],
            ['GET', '/analytics/engagement', 'Engagement Analytics'],
            ['GET', '/analytics/real-time', 'Real-time Analytics'],
        ];
        
        foreach ($analyticsEndpoints as [$method, $endpoint, $name]) {
            $response = $this->makeRequest($method, $endpoint, null, $token);
            $success = !isset($response['error']) || (isset($response['success']) && $response['success']);
            $this->recordTest($name, $success, $success ? "OK" : $this->getErrorMessage($response));
        }
        
        echo "\n";
    }

    private function testQuizEndpoints()
    {
        echo "📝 Testing Quiz Endpoints...\n";
        
        if (!isset($this->tokens['student'])) {
            echo "⚠️ Skipping quiz tests - no student token\n\n";
            return;
        }
        
        $token = $this->tokens['student'];
        
        // Test quiz endpoints (these might not have data, but we test the endpoints)
        $quizEndpoints = [
            ['GET', '/lessons/1/quizzes', 'Get Lesson Quizzes'],
        ];
        
        foreach ($quizEndpoints as [$method, $endpoint, $name]) {
            $response = $this->makeRequest($method, $endpoint, null, $token);
            $success = !isset($response['error']) || (isset($response['success']) && $response['success']);
            $this->recordTest($name, $success, $success ? "OK" : $this->getErrorMessage($response));
        }
        
        echo "\n";
    }

    private function testForumEndpoints()
    {
        echo "💬 Testing Forum Endpoints...\n";
        
        if (!isset($this->tokens['student'])) {
            echo "⚠️ Skipping forum tests - no student token\n\n";
            return;
        }
        
        $token = $this->tokens['student'];
        
        // Test forum endpoints
        $forumEndpoints = [
            ['GET', '/courses/11/forum', 'Get Course Forum'],
        ];
        
        foreach ($forumEndpoints as [$method, $endpoint, $name]) {
            $response = $this->makeRequest($method, $endpoint, null, $token);
            $success = !isset($response['error']) || (isset($response['success']) && $response['success']);
            $this->recordTest($name, $success, $success ? "OK" : $this->getErrorMessage($response));
        }
        
        echo "\n";
    }

    private function testCertificateEndpoints()
    {
        echo "🏆 Testing Certificate Endpoints...\n";
        
        if (!isset($this->tokens['student'])) {
            echo "⚠️ Skipping certificate tests - no student token\n\n";
            return;
        }
        
        $token = $this->tokens['student'];
        
        $certificateEndpoints = [
            ['GET', '/certificates', 'Get Certificates'],
            ['GET', '/certificates/templates', 'Get Certificate Templates'],
        ];
        
        foreach ($certificateEndpoints as [$method, $endpoint, $name]) {
            $response = $this->makeRequest($method, $endpoint, null, $token);
            $success = !isset($response['error']) || (isset($response['success']) && $response['success']);
            $this->recordTest($name, $success, $success ? "OK" : $this->getErrorMessage($response));
        }
        
        echo "\n";
    }

    private function testRecommendationEndpoints()
    {
        echo "🎯 Testing Recommendation Endpoints...\n";
        
        if (!isset($this->tokens['student'])) {
            echo "⚠️ Skipping recommendation tests - no student token\n\n";
            return;
        }
        
        $token = $this->tokens['student'];
        
        $recommendationEndpoints = [
            ['GET', '/recommendations', 'Get Recommendations'],
            ['GET', '/recommendations/courses/11', 'Course-based Recommendations'],
            ['GET', '/recommendations/learning-paths', 'Learning Path Recommendations'],
            ['GET', '/recommendations/instructors', 'Instructor Recommendations'],
            ['GET', '/recommendations/content', 'Content Recommendations'],
        ];
        
        foreach ($recommendationEndpoints as [$method, $endpoint, $name]) {
            $response = $this->makeRequest($method, $endpoint, null, $token);
            $success = !isset($response['error']) || (isset($response['success']) && $response['success']);
            $this->recordTest($name, $success, $success ? "OK" : $this->getErrorMessage($response));
        }
        
        echo "\n";
    }

    private function testCouponEndpoints()
    {
        echo "🎫 Testing Coupon Endpoints...\n";
        
        if (!isset($this->tokens['student'])) {
            echo "⚠️ Skipping coupon tests - no student token\n\n";
            return;
        }
        
        $token = $this->tokens['student'];
        
        $couponEndpoints = [
            ['GET', '/coupons', 'Get Coupons'],
            ['GET', '/coupons/user/available', 'Get User Available Coupons'],
        ];
        
        foreach ($couponEndpoints as [$method, $endpoint, $name]) {
            $response = $this->makeRequest($method, $endpoint, null, $token);
            $success = !isset($response['error']) || (isset($response['success']) && $response['success']);
            $this->recordTest($name, $success, $success ? "OK" : $this->getErrorMessage($response));
        }
        
        echo "\n";
    }

    private function testAuditEndpoints()
    {
        echo "🔍 Testing Audit Endpoints...\n";
        
        if (!isset($this->tokens['admin'])) {
            echo "⚠️ Skipping audit tests - no admin token\n\n";
            return;
        }
        
        $token = $this->tokens['admin'];
        
        $auditEndpoints = [
            ['GET', '/audit/logs', 'Get Audit Logs'],
            ['GET', '/audit/users/32/activity', 'Get User Activity'],
            ['GET', '/audit/system/events', 'Get System Events'],
            ['GET', '/audit/security/events', 'Get Security Events'],
        ];
        
        foreach ($auditEndpoints as [$method, $endpoint, $name]) {
            $response = $this->makeRequest($method, $endpoint, null, $token);
            $success = !isset($response['error']) || (isset($response['success']) && $response['success']);
            $this->recordTest($name, $success, $success ? "OK" : $this->getErrorMessage($response));
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

    private function getErrorMessage($response)
    {
        if (isset($response['message'])) {
            return $response['message'];
        }
        if (isset($response['error'])) {
            return $response['error'];
        }
        return json_encode($response);
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
        echo "\n" . str_repeat("=", 50) . "\n";
        echo "📊 EXTENDED TEST RESULTS\n";
        echo str_repeat("=", 50) . "\n\n";
        
        echo "Total Extended Tests: {$this->totalTests}\n";
        echo "✅ Passed: {$this->passedTests}\n";
        echo "❌ Failed: {$this->failedTests}\n";
        
        $successRate = $this->totalTests > 0 ? round(($this->passedTests / $this->totalTests) * 100, 2) : 0;
        echo "📈 Success Rate: {$successRate}%\n\n";
        
        if ($this->failedTests > 0) {
            echo "❌ FAILED EXTENDED TESTS:\n";
            echo str_repeat("-", 30) . "\n";
            foreach ($this->results as $result) {
                if (!$result['success']) {
                    echo "• {$result['name']}: {$result['message']}\n";
                }
            }
            echo "\n";
        }
        
        echo "🎉 Extended test suite completed!\n";
    }
}

// Run the extended test suite
$extendedTestSuite = new ExtendedApiTestSuite();
$extendedTestSuite->run();
