<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING BADGE ANALYTICS ENDPOINT\n";
echo "============================================================\n\n";

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

try {
    echo "📝 Checking badges table schema...\n";
    
    // Get current columns
    $columns = Schema::getColumnListing('badges');
    echo "Current columns: " . implode(', ', $columns) . "\n\n";
    
    // Check if we need to add missing columns
    $needsUpdate = false;
    
    if (!in_array('description', $columns)) {
        echo "📝 Adding description column...\n";
        Schema::table('badges', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
        });
        $needsUpdate = true;
    }
    
    if (!in_array('category', $columns)) {
        echo "📝 Adding category column...\n";
        Schema::table('badges', function (Blueprint $table) {
            $table->string('category')->default('achievement')->after('description');
        });
        $needsUpdate = true;
    }
    
    if (!in_array('type', $columns)) {
        echo "📝 Adding type column...\n";
        Schema::table('badges', function (Blueprint $table) {
            $table->string('type')->default('milestone')->after('category');
        });
        $needsUpdate = true;
    }
    
    if (!in_array('points', $columns)) {
        echo "📝 Adding points column...\n";
        Schema::table('badges', function (Blueprint $table) {
            $table->integer('points')->default(10)->after('type');
        });
        $needsUpdate = true;
    }
    
    if (!in_array('is_active', $columns)) {
        echo "📝 Adding is_active column...\n";
        Schema::table('badges', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('points');
        });
        $needsUpdate = true;
    }
    
    if ($needsUpdate) {
        echo "✅ Updated badges table schema!\n\n";
    } else {
        echo "✅ Badges table schema is correct!\n\n";
    }
    
    // Check if we have any badges
    $badgeCount = DB::table('badges')->count();
    
    if ($badgeCount === 0) {
        echo "📝 No badges found. Creating sample badges...\n";
        
        $badges = [
            [
                'name' => 'First Course Completed',
                'description' => 'Complete your first course',
                'category' => 'achievement',
                'type' => 'course_completion',
                'points' => 50,
                'icon' => 'trophy',
                'criteria' => json_encode(['courses_completed' => 1]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Quiz Master',
                'description' => 'Score 100% on 5 quizzes',
                'category' => 'learning',
                'type' => 'quiz_mastery',
                'points' => 75,
                'icon' => 'star',
                'criteria' => json_encode(['perfect_quizzes' => 5]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Learning Streak',
                'description' => 'Study for 7 consecutive days',
                'category' => 'social',
                'type' => 'streak',
                'points' => 30,
                'icon' => 'fire',
                'criteria' => json_encode(['consecutive_days' => 7]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        
        foreach ($badges as $badge) {
            $badgeId = DB::table('badges')->insertGetId($badge);
            echo "   ✅ Created badge: {$badge['name']} (ID: $badgeId)\n";
        }
    } else {
        echo "✅ Found $badgeCount badges in database!\n";
    }
    
    // Check if user_badges table exists
    if (!Schema::hasTable('user_badges')) {
        echo "📝 Creating user_badges table...\n";
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('badge_id')->constrained()->onDelete('cascade');
            $table->timestamp('earned_at')->default(now());
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'badge_id']);
        });
        echo "✅ Created user_badges table!\n";
    }
    
    echo "\n🧪 Testing badge analytics endpoint...\n";
    
    // Get tokens from file
    $tokens = file_get_contents('auth_tokens.txt');
    preg_match('/ADMIN_TOKEN=(.+)/', $tokens, $adminMatches);
    $adminToken = trim($adminMatches[1]);
    
    // Test the endpoint
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/badges/analytics');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $adminToken
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        echo "✅ Badge analytics endpoint working! HTTP 200\n";
        $data = json_decode($response, true);
        if (isset($data['data']['overview'])) {
            $overview = $data['data']['overview'];
            echo "📊 Analytics Overview:\n";
            echo "   - Total badges: " . ($overview['total_badges'] ?? 0) . "\n";
            echo "   - Active badges: " . ($overview['active_badges'] ?? 0) . "\n";
            echo "   - Total awards: " . ($overview['total_awards'] ?? 0) . "\n";
            echo "   - Unique badge holders: " . ($overview['unique_badge_holders'] ?? 0) . "\n";
        }
    } else {
        echo "❌ Badge analytics endpoint failed! HTTP $httpCode\n";
        echo "Response: " . substr($response, 0, 300) . "...\n";
    }
    
    echo "\n============================================================\n";
    echo "✅ BADGE ANALYTICS ENDPOINT FIXED!\n";
    echo "============================================================\n";
    
} catch (\Exception $e) {
    echo "❌ Error fixing badge analytics: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
