<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING QUESTION TYPES\n";
echo "============================================================\n\n";

try {
    // Update the questions table enum to support more types
    echo "🔄 Updating question type enum...\n";
    
    DB::statement("ALTER TABLE questions MODIFY COLUMN type ENUM('mcq', 'theory', 'multiple_choice', 'true_false', 'short_answer', 'essay') DEFAULT 'mcq'");
    
    echo "✅ Updated question type enum to support more types\n";
    
    // Create sample questions for existing quizzes with correct types
    echo "\n📝 Creating sample questions with correct types...\n";
    $quizzes = \App\Models\Quiz::all();
    
    foreach ($quizzes as $quiz) {
        $existingQuestions = $quiz->questions()->count();
        if ($existingQuestions == 0) {
            echo "Creating questions for quiz: {$quiz->title}\n";
            
            // Create 3 sample questions with correct enum values
            $questions = [
                [
                    'question_text' => 'What is 2 + 2?',
                    'type' => 'mcq',  // Using correct enum value
                    'points' => 10,
                    'options' => ['3', '4', '5', '6'],
                    'correct_answer' => '4',
                    'explanation' => 'Basic addition: 2 + 2 = 4'
                ],
                [
                    'question_text' => 'Is the earth round?',
                    'type' => 'mcq',  // Using correct enum value
                    'points' => 5,
                    'options' => ['True', 'False'],
                    'correct_answer' => 'True',
                    'explanation' => 'The Earth is approximately spherical in shape.'
                ],
                [
                    'question_text' => 'Explain the concept of gravity.',
                    'type' => 'theory',  // Using correct enum value
                    'points' => 15,
                    'options' => null,
                    'correct_answer' => 'Gravity is a fundamental force that attracts objects with mass toward each other.',
                    'explanation' => 'This is a theory question requiring detailed explanation.'
                ]
            ];
            
            foreach ($questions as $questionData) {
                \App\Models\Question::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $questionData['question_text'],
                    'type' => $questionData['type'],
                    'points' => $questionData['points'],
                    'options' => $questionData['options'],
                    'correct_answer' => $questionData['correct_answer'],
                    'explanation' => $questionData['explanation']
                ]);
            }
            
            echo "✅ Created 3 questions for quiz {$quiz->id}\n";
        } else {
            echo "✅ Quiz {$quiz->id} already has {$existingQuestions} questions\n";
        }
    }
    
    // Verify questions were created
    echo "\n📊 Question Summary:\n";
    $totalQuestions = \App\Models\Question::count();
    echo "Total questions in database: {$totalQuestions}\n";
    
    $questionsByType = \App\Models\Question::select('type', DB::raw('count(*) as count'))
        ->groupBy('type')
        ->get();
    
    foreach ($questionsByType as $typeCount) {
        echo "  - {$typeCount->type}: {$typeCount->count} questions\n";
    }
    
    echo "\n🎉 Question types fixed successfully!\n";
    echo "✅ Updated enum to support more question types\n";
    echo "✅ Created sample questions for all quizzes\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
