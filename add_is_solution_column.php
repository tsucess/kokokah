<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "Adding is_solution column to forum_posts table...\n";

if (Schema::hasTable('forum_posts')) {
    if (!Schema::hasColumn('forum_posts', 'is_solution')) {
        DB::statement('ALTER TABLE forum_posts ADD COLUMN is_solution BOOLEAN DEFAULT FALSE AFTER likes_count');
        echo "✅ is_solution column added to forum_posts table\n";
    } else {
        echo "✅ is_solution column already exists\n";
    }
} else {
    echo "❌ forum_posts table does not exist\n";
}

// Also add last_activity column to forum_topics if missing
echo "\nAdding last_activity column to forum_topics table...\n";

if (Schema::hasTable('forum_topics')) {
    if (!Schema::hasColumn('forum_topics', 'last_activity')) {
        DB::statement('ALTER TABLE forum_topics ADD COLUMN last_activity TIMESTAMP NULL AFTER views');
        echo "✅ last_activity column added to forum_topics table\n";
    } else {
        echo "✅ last_activity column already exists\n";
    }
} else {
    echo "❌ forum_topics table does not exist\n";
}

echo "\n✅ All columns added successfully!\n";

