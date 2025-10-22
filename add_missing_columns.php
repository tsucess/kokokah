<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Adding missing columns...\n\n";

// 1. Add category column to forum_topics if it doesn't exist
echo "1️⃣  Checking forum_topics table...\n";
if (!Schema::hasColumn('forum_topics', 'category')) {
    DB::statement('ALTER TABLE forum_topics ADD COLUMN category VARCHAR(50) DEFAULT "general" AFTER content');
    echo "✅ category column added to forum_topics\n";
} else {
    echo "✅ category column already exists\n";
}

// 2. Add status column to forum_topics if it doesn't exist
echo "\n2️⃣  Checking status column...\n";
if (!Schema::hasColumn('forum_topics', 'status')) {
    DB::statement('ALTER TABLE forum_topics ADD COLUMN status VARCHAR(50) DEFAULT "active" AFTER category');
    echo "✅ status column added to forum_topics\n";
} else {
    echo "✅ status column already exists\n";
}

// 3. Add course_id column to forum_topics if it doesn't exist
echo "\n3️⃣  Checking course_id column...\n";
if (!Schema::hasColumn('forum_topics', 'course_id')) {
    DB::statement('ALTER TABLE forum_topics ADD COLUMN course_id BIGINT UNSIGNED AFTER forum_id');
    echo "✅ course_id column added to forum_topics\n";
} else {
    echo "✅ course_id column already exists\n";
}

// 4. Add last_activity column to forum_topics if it doesn't exist
echo "\n4️⃣  Checking last_activity column...\n";
if (!Schema::hasColumn('forum_topics', 'last_activity')) {
    DB::statement('ALTER TABLE forum_topics ADD COLUMN last_activity TIMESTAMP NULL AFTER views');
    echo "✅ last_activity column added to forum_topics\n";
} else {
    echo "✅ last_activity column already exists\n";
}

echo "\n✅ All columns checked/added successfully!\n";

