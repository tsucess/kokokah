<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 DEBUGGING ASSIGNMENT 1 COURSE RELATIONSHIP\n";
echo "============================================================\n\n";

use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

try {
    echo "📝 Loading assignment 1 with course relationship...\n";
    
    $assignment = Assignment::with(['course'])->find(1);
    
    if (!$assignment) {
        echo "❌ Assignment 1 not found!\n";
        exit;
    }
    
    echo "Assignment details:\n";
    echo "   ID: {$assignment->id}\n";
    echo "   Course ID: {$assignment->course_id}\n";
    echo "   Title: {$assignment->title}\n";
    echo "   Course relationship: " . ($assignment->course ? 'EXISTS' : 'NULL') . "\n\n";
    
    if ($assignment->course) {
        echo "Course details:\n";
        echo "   ID: {$assignment->course->id}\n";
        echo "   Title: {$assignment->course->title}\n";
        echo "   Status: {$assignment->course->status}\n";
    } else {
        echo "❌ Course relationship is NULL!\n";
        
        // Check if course exists directly
        $course = Course::find($assignment->course_id);
        if ($course) {
            echo "✅ Course exists when queried directly:\n";
            echo "   ID: {$course->id}\n";
            echo "   Title: {$course->title}\n";
            echo "   Status: {$course->status}\n";
            echo "   Deleted at: " . ($course->deleted_at ?? 'NULL') . "\n";
        } else {
            echo "❌ Course doesn't exist even when queried directly!\n";
        }
        
        // Check raw database
        $rawCourse = DB::table('courses')->where('id', $assignment->course_id)->first();
        if ($rawCourse) {
            echo "✅ Course exists in raw database:\n";
            echo "   ID: {$rawCourse->id}\n";
            echo "   Title: {$rawCourse->title}\n";
            echo "   Status: {$rawCourse->status}\n";
            echo "   Deleted at: " . ($rawCourse->deleted_at ?? 'NULL') . "\n";
        } else {
            echo "❌ Course doesn't exist in raw database!\n";
        }
    }
    
    // Check Assignment model relationships
    echo "\n📝 Checking Assignment model course relationship...\n";
    $courseFromRelation = $assignment->course();
    echo "Course relation query: " . $courseFromRelation->toSql() . "\n";
    
    $courseResult = $courseFromRelation->first();
    if ($courseResult) {
        echo "✅ Course found via relationship: {$courseResult->title}\n";
    } else {
        echo "❌ Course NOT found via relationship\n";
        
        // Try without soft delete scope
        $courseWithTrashed = $courseFromRelation->withTrashed()->first();
        if ($courseWithTrashed) {
            echo "✅ Course found with trashed: {$courseWithTrashed->title} (deleted: {$courseWithTrashed->deleted_at})\n";
        }
    }
    
    echo "\n============================================================\n";
    echo "✅ ASSIGNMENT COURSE DEBUG COMPLETED!\n";
    echo "============================================================\n";
    
} catch (\Exception $e) {
    echo "❌ Error debugging assignment: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
