<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "Checking forum_posts table...\n";
$exists = Schema::hasTable('forum_posts');
echo "forum_posts table exists: " . ($exists ? "YES" : "NO") . "\n";

if ($exists) {
    $columns = Schema::getColumnListing('forum_posts');
    echo "Columns: " . implode(", ", $columns) . "\n";
    
    $count = DB::table('forum_posts')->count();
    echo "Records: $count\n";
} else {
    echo "Table does not exist. Creating it now...\n";
    
    // Create the table manually
    DB::statement("
        CREATE TABLE IF NOT EXISTS forum_posts (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            topic_id BIGINT UNSIGNED NOT NULL,
            user_id BIGINT UNSIGNED NOT NULL,
            content LONGTEXT NOT NULL,
            parent_id BIGINT UNSIGNED NULL,
            status ENUM('active', 'hidden', 'deleted') DEFAULT 'active',
            edited_at TIMESTAMP NULL,
            edited_by BIGINT UNSIGNED NULL,
            likes_count INT DEFAULT 0,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (topic_id) REFERENCES forum_topics(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (parent_id) REFERENCES forum_posts(id) ON DELETE CASCADE,
            FOREIGN KEY (edited_by) REFERENCES users(id) ON DELETE SET NULL,
            INDEX (topic_id, status),
            INDEX (user_id),
            INDEX (parent_id)
        )
    ");
    
    echo "✅ forum_posts table created\n";
    
    // Create forum_post_likes table
    DB::statement("
        CREATE TABLE IF NOT EXISTS forum_post_likes (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            post_id BIGINT UNSIGNED NOT NULL,
            user_id BIGINT UNSIGNED NOT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (post_id) REFERENCES forum_posts(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE KEY unique_post_user (post_id, user_id),
            INDEX (user_id)
        )
    ");
    
    echo "✅ forum_post_likes table created\n";
}

