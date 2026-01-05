<?php
/**
 * Email Verification Flow Test Script
 * Tests the complete email verification process
 */

require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Hash;

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Email Verification Flow Test ===\n\n";

// Test 1: Create a test user
echo "Test 1: Creating test user...\n";
$testEmail = 'test_verification_' . time() . '@example.com';
$user = User::create([
    'first_name' => 'Test',
    'last_name' => 'User',
    'email' => $testEmail,
    'password' => Hash::make('password123'),
    'role' => 'student'
]);
echo "✓ User created: {$user->email} (ID: {$user->id})\n\n";

// Test 2: Send verification code
echo "Test 2: Sending verification code...\n";
$verificationCode = VerificationCode::createForUser($user, 'email', 15);
echo "✓ Verification code created: {$verificationCode->code}\n";
echo "  - Expires at: {$verificationCode->expires_at}\n";
echo "  - Max attempts: {$verificationCode->max_attempts}\n\n";

// Test 3: Verify the code
echo "Test 3: Verifying code...\n";
$verified = VerificationCode::verify($user->id, $verificationCode->code, 'email');
if ($verified) {
    echo "✓ Code verified successfully\n";
    echo "  - Code is valid: " . ($verified->isValid() ? 'Yes' : 'No') . "\n";
    echo "  - Code is expired: " . ($verified->isExpired() ? 'Yes' : 'No') . "\n";
    echo "  - Attempts exceeded: " . ($verified->hasExceededAttempts() ? 'Yes' : 'No') . "\n\n";
} else {
    echo "✗ Code verification failed\n\n";
}

// Test 4: Mark email as verified
echo "Test 4: Marking email as verified...\n";
$user->markEmailAsVerified();
echo "✓ Email marked as verified\n";
echo "  - Email verified at: {$user->email_verified_at}\n";
echo "  - Has verified email: " . ($user->hasVerifiedEmail() ? 'Yes' : 'No') . "\n\n";

// Test 5: Mark code as used
echo "Test 5: Marking code as used...\n";
$verified->markAsUsed();
echo "✓ Code marked as used\n";
echo "  - Used at: {$verified->used_at}\n";
echo "  - Is used: " . ($verified->isUsed() ? 'Yes' : 'No') . "\n\n";

// Test 6: Verify code cannot be reused
echo "Test 6: Testing code reuse prevention...\n";
$reused = VerificationCode::verify($user->id, $verificationCode->code, 'email');
if (!$reused) {
    echo "✓ Code cannot be reused (as expected)\n\n";
} else {
    echo "✗ Code was reused (unexpected)\n\n";
}

// Test 7: Test invalid code
echo "Test 7: Testing invalid code...\n";
$invalid = VerificationCode::verify($user->id, 'INVALID', 'email');
if (!$invalid) {
    echo "✓ Invalid code rejected (as expected)\n\n";
} else {
    echo "✗ Invalid code was accepted (unexpected)\n\n";
}

// Cleanup
echo "Cleanup: Deleting test user...\n";
$user->delete();
echo "✓ Test user deleted\n\n";

echo "=== All Tests Completed ===\n";

