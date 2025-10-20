<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Enrollment;

echo "Enrollment count: " . Enrollment::count() . "\n";
echo "Enrollment ID 1: " . (Enrollment::find(1) ? "EXISTS" : "NOT FOUND") . "\n";
echo "Enrollments for user 130: " . Enrollment::where('user_id', 130)->count() . "\n";

$enrollment = Enrollment::find(1);
if ($enrollment) {
    echo "Enrollment ID 1 details:\n";
    echo "  - User ID: {$enrollment->user_id}\n";
    echo "  - Course ID: {$enrollment->course_id}\n";
    echo "  - Status: {$enrollment->status}\n";
}

