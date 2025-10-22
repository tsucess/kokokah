<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ForumPost;
use App\Models\ForumTopic;
use Illuminate\Support\Facades\DB;

echo "Creating ForumPost ID 1...\n\n";

// First, delete any existing post with ID 1
DB::table('forum_posts')->where('id', 1)->delete();

// Get a forum topic
$topic = ForumTopic::first();
if (!$topic) {
    echo "❌ No forum topic found\n";
    exit;
}

echo "Using Forum Topic ID: {$topic->id}\n\n";

// Create ForumPost with ID 1 using raw SQL
DB::insert('INSERT INTO forum_posts (id, topic_id, user_id, content, parent_id, status, likes_count, is_solution, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
    1,
    $topic->id,
    2,
    'This is a test forum post',
    null,
    'active',
    0,
    false,
    now(),
    now()
]);

echo "✅ ForumPost created with ID: 1\n";

// Verify it was created
$verify = ForumPost::find(1);
if ($verify) {
    echo "✅ ForumPost ID 1 verified in database\n";
} else {
    echo "❌ ForumPost ID 1 NOT found in database\n";
}

