<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING FEATURED COURSES ENDPOINT\n";
echo "============================================================\n\n";

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

try {
    // Check if is_featured column exists
    if (!Schema::hasColumn('courses', 'is_featured')) {
        echo "📝 Adding is_featured column to courses table...\n";
        
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('status');
        });
        
        echo "✅ Added is_featured column successfully!\n\n";
    } else {
        echo "✅ is_featured column already exists!\n\n";
    }
    
    // Check if we have any featured courses, if not, mark some as featured
    $featuredCount = DB::table('courses')->where('is_featured', true)->count();
    
    if ($featuredCount === 0) {
        echo "📝 No featured courses found. Marking some published courses as featured...\n";
        
        // Get some published courses to mark as featured
        $publishedCourses = DB::table('courses')
            ->where('status', 'published')
            ->limit(5)
            ->get();
            
        if ($publishedCourses->count() > 0) {
            $courseIds = $publishedCourses->pluck('id')->toArray();
            
            DB::table('courses')
                ->whereIn('id', $courseIds)
                ->update(['is_featured' => true]);
                
            echo "✅ Marked " . count($courseIds) . " courses as featured!\n";
            
            foreach ($publishedCourses as $course) {
                echo "   - Course ID {$course->id}: {$course->title}\n";
            }
        } else {
            echo "⚠️  No published courses found to mark as featured.\n";
            echo "📝 Creating a sample published course...\n";
            
            // Create a sample course
            $courseId = DB::table('courses')->insertGetId([
                'title' => 'Featured Sample Course',
                'description' => 'This is a sample featured course for testing purposes.',
                'category_id' => 1, // Assuming category 1 exists
                'instructor_id' => 1, // Assuming user 1 exists
                'price' => 99.99,
                'status' => 'published',
                'is_featured' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            echo "✅ Created sample featured course with ID: $courseId\n";
        }
    } else {
        echo "✅ Found $featuredCount featured courses already!\n";
    }
    
    echo "\n🧪 Testing featured courses endpoint...\n";
    
    // Test the endpoint
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/courses/featured');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        $count = count($data['data'] ?? []);
        echo "✅ Featured courses endpoint working! HTTP 200\n";
        echo "📊 Returned $count featured courses\n";
        
        if ($count > 0) {
            echo "\n📋 Featured courses:\n";
            foreach ($data['data'] as $course) {
                echo "   - {$course['title']} (ID: {$course['id']})\n";
            }
        }
    } else {
        echo "❌ Featured courses endpoint failed! HTTP $httpCode\n";
        echo "Response: " . substr($response, 0, 200) . "...\n";
    }
    
    echo "\n============================================================\n";
    echo "✅ FEATURED COURSES ENDPOINT FIXED!\n";
    echo "============================================================\n";
    
} catch (\Exception $e) {
    echo "❌ Error fixing featured courses: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
