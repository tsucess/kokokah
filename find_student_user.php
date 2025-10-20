<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "🔍 FINDING STUDENT USERS\n";
echo "========================\n\n";

$students = User::where('role', 'student')->get();

echo "Total students: " . count($students) . "\n\n";

foreach ($students as $student) {
    echo "ID: {$student->id}\n";
    echo "Name: {$student->first_name} {$student->last_name}\n";
    echo "Email: {$student->email}\n";
    echo "---\n";
}

echo "\n========================================\n";