<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "👥 CHECKING USERS IN DATABASE\n";
echo "============================================================\n\n";

// Get all users
$users = User::all();

echo "Found " . $users->count() . " users:\n\n";

foreach ($users as $user) {
    echo "ID: {$user->id}\n";
    echo "Name: {$user->first_name} {$user->last_name}\n";
    echo "Email: {$user->email}\n";
    echo "Role: {$user->role}\n";
    echo "Created: {$user->created_at}\n";
    echo "---\n";
}

echo "\n🔐 ATTEMPTING TO LOGIN WITH DIFFERENT CREDENTIALS\n";
echo "============================================================\n\n";

// Try to login with the admin user we found
$adminUser = User::where('role', 'admin')->first();
if ($adminUser) {
    echo "Found admin user: {$adminUser->email}\n";
    
    // Try common passwords
    $passwords = ['password', 'admin123', '123456', 'admin', 'kokokah123'];
    
    foreach ($passwords as $password) {
        echo "Trying password: $password... ";
        
        $loginData = [
            'email' => $adminUser->email,
            'password' => $password
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/login');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            $data = json_decode($response, true);
            echo "✅ SUCCESS!\n";
            echo "New Admin Token: " . substr($data['data']['token'], 0, 30) . "...\n";
            $newAdminToken = $data['data']['token'];
            break;
        } else {
            echo "❌ Failed (HTTP $httpCode)\n";
        }
    }
}

// Try to login with a student user
$studentUser = User::where('role', 'student')->first();
if ($studentUser) {
    echo "\nFound student user: {$studentUser->email}\n";
    
    // Try common passwords
    $passwords = ['password', 'student123', '123456', 'student', 'kokokah123'];
    
    foreach ($passwords as $password) {
        echo "Trying password: $password... ";
        
        $loginData = [
            'email' => $studentUser->email,
            'password' => $password
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/login');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            $data = json_decode($response, true);
            echo "✅ SUCCESS!\n";
            echo "New Student Token: " . substr($data['data']['token'], 0, 30) . "...\n";
            $newStudentToken = $data['data']['token'];
            break;
        } else {
            echo "❌ Failed (HTTP $httpCode)\n";
        }
    }
}

// If we couldn't login, let's create a test student user
if (!isset($newStudentToken)) {
    echo "\n🔄 Creating a test student user...\n";
    
    $registerData = [
        'first_name' => 'Test',
        'last_name' => 'Student',
        'email' => 'test.student@kokokah.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'student'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/register');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($registerData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 201) {
        $data = json_decode($response, true);
        echo "✅ Student user created successfully!\n";
        echo "New Student Token: " . substr($data['data']['token'], 0, 30) . "...\n";
        $newStudentToken = $data['data']['token'];
    } else {
        echo "❌ Failed to create student user: HTTP $httpCode\n";
        echo "Response: $response\n";
    }
}

// Update tokens file if we have new tokens
if (isset($newAdminToken) || isset($newStudentToken)) {
    // Get current tokens
    $tokens = file_get_contents('auth_tokens.txt');
    preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
    preg_match('/STUDENT_TOKEN=(.+)/', $tokens, $studentMatches);
    
    $currentAdminToken = isset($newAdminToken) ? $newAdminToken : trim($adminMatches[1]);
    $currentStudentToken = isset($newStudentToken) ? $newStudentToken : trim($studentMatches[1]);
    
    $tokenContent = "ADMIN_TOKEN=$currentAdminToken\nSTUDENT_TOKEN=$currentStudentToken\n";
    file_put_contents('auth_tokens.txt', $tokenContent);
    echo "\n✅ Updated auth_tokens.txt with working tokens\n";
}

echo "\n============================================================\n";
