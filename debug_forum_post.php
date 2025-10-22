<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ForumPost;
use App\Models\ForumTopic;
use Illuminate\Support\Facades\DB;

echo "🔍 Debugging Forum Post Issue\n";
echo "=============================\n\n";

// Check forum posts table
echo "1. Checking forum_posts table:\n";
$count = DB::table('forum_posts')->count();
echo "   Total forum posts: $count\n";

$post1 = DB::table('forum_posts')->where('id', 1)->first();
if ($post1) {
    echo "   ✅ Forum Post ID 1 exists in database\n";
    echo "   - Topic ID: {$post1->topic_id}\n";
    echo "   - User ID: {$post1->user_id}\n";
    echo "   - Content: {$post1->content}\n";
} else {
    echo "   ❌ Forum Post ID 1 NOT found in database\n";
}
echo "\n";

// Check ForumPost model
echo "2. Checking ForumPost model:\n";
$modelPost = ForumPost::find(1);
if ($modelPost) {
    echo "   ✅ ForumPost::find(1) returned result\n";
    echo "   - ID: {$modelPost->id}\n";
    echo "   - Topic ID: {$modelPost->topic_id}\n";
} else {
    echo "   ❌ ForumPost::find(1) returned null\n";
}
echo "\n";

// Check all forum posts
echo "3. All forum posts:\n";
$allPosts = ForumPost::all();
foreach ($allPosts as $p) {
    echo "   - ID: {$p->id}, Topic: {$p->topic_id}, User: {$p->user_id}\n";
}
if ($allPosts->isEmpty()) {
    echo "   (No forum posts found)\n";
}
echo "\n";

// Check forum topics
echo "4. Forum Topics:\n";
$topics = ForumTopic::all();
foreach ($topics as $t) {
    echo "   - ID: {$t->id}, Title: {$t->title}, User: {$t->user_id}\n";
}
echo "\n";

echo "=============================\n";

