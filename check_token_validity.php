<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔐 CHECKING TOKEN VALIDITY\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);

$adminToken = trim($adminMatches[1]);
$studentToken = trim($studentMatches[1]);

echo "Current tokens:\n";
echo "Admin Token: " . substr($adminToken, 0, 30) . "...\n";
echo "Student Token: " . substr($studentToken, 0, 30) . "...\n\n";

function testToken($tokenName, $token) {
    $url = 'http://localhost:8000/api/user';
    
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
    
    echo "$tokenName: ";
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        if (isset($data['data'])) {
            echo "✅ VALID - User: " . $data['data']['first_name'] . " " . $data['data']['last_name'] . " (ID: " . $data['data']['id'] . ")\n";
        } else {
            echo "✅ VALID - Response: " . substr($response, 0, 100) . "...\n";
        }
        return true;
    } else {
        echo "❌ INVALID - HTTP $httpCode\n";
        echo "Response: " . substr($response, 0, 200) . "...\n";
        return false;
    }
}

echo "Testing token validity:\n";
$adminValid = testToken("Admin Token", $adminToken);
$studentValid = testToken("Student Token", $studentToken);

if (!$adminValid || !$studentValid) {
    echo "\n🔄 Generating fresh tokens...\n\n";
    
    // Login as admin
    $adminLoginData = [
        'email' => 'admin@kokokah.com',
        'password' => 'password'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/login');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($adminLoginData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        $newAdminToken = $data['data']['token'];
        echo "✅ New Admin Token Generated: " . substr($newAdminToken, 0, 30) . "...\n";
    } else {
        echo "❌ Failed to generate admin token: HTTP $httpCode\n";
        echo "Response: $response\n";
    }
    
    // Login as student
    $studentLoginData = [
        'email' => 'student@kokokah.com',
        'password' => 'password'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/login');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($studentLoginData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        $newStudentToken = $data['data']['token'];
        echo "✅ New Student Token Generated: " . substr($newStudentToken, 0, 30) . "...\n";
    } else {
        echo "❌ Failed to generate student token: HTTP $httpCode\n";
        echo "Response: $response\n";
    }
    
    // Update tokens file if we got new tokens
    if (isset($newAdminToken) && isset($newStudentToken)) {
        $tokenContent = "ADMIN_TOKEN=$newAdminToken\nSTUDENT_TOKEN=$newStudentToken\n";
        file_put_contents('auth_tokens.txt', $tokenContent);
        echo "\n✅ Updated auth_tokens.txt with fresh tokens\n";
        
        // Test new tokens
        echo "\nTesting new tokens:\n";
        testToken("New Admin Token", $newAdminToken);
        testToken("New Student Token", $newStudentToken);
    }
} else {
    echo "\n✅ All tokens are valid!\n";
}

echo "\n============================================================\n";
