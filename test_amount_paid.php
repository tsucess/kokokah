<?php
/**
 * Test script to verify amount_paid is being set correctly in enrollments
 * Run with: php test_amount_paid.php
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;

echo "=== Testing Amount Paid in Enrollments ===\n\n";

// Get recent enrollments
$enrollments = Enrollment::latest()->limit(10)->get();

if ($enrollments->isEmpty()) {
    echo "No enrollments found in database.\n";
    exit;
}

echo "Recent Enrollments:\n";
echo str_pad("ID", 5) . str_pad("User ID", 10) . str_pad("Course ID", 12) . str_pad("Amount Paid", 15) . str_pad("Status", 12) . "\n";
echo str_repeat("-", 60) . "\n";

foreach ($enrollments as $enrollment) {
    echo str_pad($enrollment->id, 5) 
        . str_pad($enrollment->user_id, 10) 
        . str_pad($enrollment->course_id, 12) 
        . str_pad($enrollment->amount_paid, 15) 
        . str_pad($enrollment->status, 12) 
        . "\n";
}

echo "\n=== Summary ===\n";
$totalEnrollments = Enrollment::count();
$enrollmentsWithAmount = Enrollment::where('amount_paid', '>', 0)->count();
$enrollmentsWithZero = Enrollment::where('amount_paid', 0)->count();

echo "Total Enrollments: $totalEnrollments\n";
echo "Enrollments with amount_paid > 0: $enrollmentsWithAmount\n";
echo "Enrollments with amount_paid = 0: $enrollmentsWithZero\n";

echo "\n✅ Test Complete\n";

