<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 CHECKING ADMIN USER ROLE\n";
echo "============================================================\n\n";

// Get tokens from file
$tokens = file_get_contents('auth_tokens.txt');
preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);

$adminToken = trim($adminMatches[1]);
$studentToken = trim($studentMatches[1]);

echo "Admin Token: " . substr($adminToken, 0, 20) . "...\n";
echo "Student Token: " . substr($studentToken, 0, 20) . "...\n\n";

// Get admin user from token
$adminTokenRecord = \Laravel\Sanctum\PersonalAccessToken::findToken($adminToken);
if ($adminTokenRecord) {
    $adminUser = $adminTokenRecord->tokenable;
    echo "Admin User Found:\n";
    echo "  ID: " . $adminUser->id . "\n";
    echo "  Name: " . $adminUser->first_name . " " . $adminUser->last_name . "\n";
    echo "  Email: " . $adminUser->email . "\n";
    echo "  Role: " . $adminUser->role . "\n";
    echo "  Is Admin: " . ($adminUser->isAdmin() ? 'YES' : 'NO') . "\n";
    echo "  Has Admin Role: " . ($adminUser->hasRole('admin') ? 'YES' : 'NO') . "\n\n";
} else {
    echo "❌ Admin token not found!\n\n";
}

// Get student user from token
$studentTokenRecord = \Laravel\Sanctum\PersonalAccessToken::findToken($studentToken);
if ($studentTokenRecord) {
    $studentUser = $studentTokenRecord->tokenable;
    echo "Student User Found:\n";
    echo "  ID: " . $studentUser->id . "\n";
    echo "  Name: " . $studentUser->first_name . " " . $studentUser->last_name . "\n";
    echo "  Email: " . $studentUser->email . "\n";
    echo "  Role: " . $studentUser->role . "\n";
    echo "  Is Student: " . ($studentUser->isStudent() ? 'YES' : 'NO') . "\n";
    echo "  Has Student Role: " . ($studentUser->hasRole('student') ? 'YES' : 'NO') . "\n\n";
} else {
    echo "❌ Student token not found!\n\n";
}

// Test the certificate templates endpoint directly
echo "🧪 Testing Certificate Templates Endpoint:\n";

function testCertificateTemplates($token, $userType) {
    $url = 'http://localhost:8000/api/certificates/templates';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "  $userType Token Test:\n";
    echo "    Status: $httpCode\n";
    echo "    Response: " . substr($response, 0, 200) . "\n\n";
    
    return $httpCode === 200;
}

testCertificateTemplates($adminToken, 'Admin');
testCertificateTemplates($studentToken, 'Student');

echo "============================================================\n";
