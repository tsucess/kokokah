<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Enrollment;
use App\Models\User;

echo "Checking enrollments for user 130...\n";
$enrollments = Enrollment::where('user_id', 130)->get();
echo "Total enrollments for user 130: " . $enrollments->count() . "\n";
foreach ($enrollments as $enrollment) {
    echo "  - Enrollment ID: " . $enrollment->id . ", Course ID: " . $enrollment->course_id . "\n";
}

echo "\nAll enrollments in database:\n";
$allEnrollments = Enrollment::all();
echo "Total enrollments: " . $allEnrollments->count() . "\n";
foreach ($allEnrollments->take(10) as $enrollment) {
    echo "  - Enrollment ID: " . $enrollment->id . ", User ID: " . $enrollment->user_id . ", Course ID: " . $enrollment->course_id . "\n";
}

echo "\nCreating enrollment for user 130, course 1...\n";
$enrollment = Enrollment::create([
    'user_id' => 130,
    'course_id' => 1,
    'status' => 'active',
    'progress' => 0,
    'enrolled_at' => now(),
]);
echo "Created enrollment ID: " . $enrollment->id . "\n";

