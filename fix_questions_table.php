<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING QUESTIONS TABLE\n";
echo "============================================================\n\n";

try {
    // Check if points column exists
    if (!Schema::hasColumn('questions', 'points')) {
        echo "❌ Missing 'points' column in questions table\n";
        
        Schema::table('questions', function (Blueprint $table) {
            $table->integer('points')->default(1)->after('type');
        });
        
        echo "✅ Added 'points' column to questions table\n";
    } else {
        echo "✅ 'points' column already exists in questions table\n";
    }
    
    // Check if explanation column exists
    if (!Schema::hasColumn('questions', 'explanation')) {
        echo "❌ Missing 'explanation' column in questions table\n";
        
        Schema::table('questions', function (Blueprint $table) {
            $table->text('explanation')->nullable()->after('correct_answer');
        });
        
        echo "✅ Added 'explanation' column to questions table\n";
    } else {
        echo "✅ 'explanation' column already exists in questions table\n";
    }
    
    // Check if attempt_number column exists in answers table
    if (!Schema::hasColumn('answers', 'attempt_number')) {
        echo "❌ Missing 'attempt_number' column in answers table\n";
        
        Schema::table('answers', function (Blueprint $table) {
            $table->integer('attempt_number')->default(1)->after('student_id');
        });
        
        echo "✅ Added 'attempt_number' column to answers table\n";
    } else {
        echo "✅ 'attempt_number' column already exists in answers table\n";
    }
    
    // Check if points_earned and points_possible columns exist in answers table
    if (!Schema::hasColumn('answers', 'points_earned')) {
        echo "❌ Missing 'points_earned' column in answers table\n";
        
        Schema::table('answers', function (Blueprint $table) {
            $table->integer('points_earned')->default(0)->after('score');
            $table->integer('points_possible')->default(1)->after('points_earned');
        });
        
        echo "✅ Added 'points_earned' and 'points_possible' columns to answers table\n";
    } else {
        echo "✅ 'points_earned' column already exists in answers table\n";
    }
    
    // Update Question model fillable fields
    echo "\n🔧 Updating Question model...\n";
    $questionModelPath = app_path('Models/Question.php');
    $questionModelContent = file_get_contents($questionModelPath);
    
    // Check if points is in fillable
    if (!str_contains($questionModelContent, "'points'")) {
        $newFillable = str_replace(
            "'correct_answer'",
            "'correct_answer',\n        'points',\n        'explanation'",
            $questionModelContent
        );
        
        file_put_contents($questionModelPath, $newFillable);
        echo "✅ Updated Question model fillable fields\n";
    } else {
        echo "✅ Question model fillable fields already updated\n";
    }
    
    // Update Answer model fillable fields
    echo "\n🔧 Updating Answer model...\n";
    $answerModelPath = app_path('Models/Answer.php');
    $answerModelContent = file_get_contents($answerModelPath);
    
    // Check if new fields are in fillable
    if (!str_contains($answerModelContent, "'attempt_number'")) {
        $newFillable = str_replace(
            "'score'",
            "'score',\n        'attempt_number',\n        'points_earned',\n        'points_possible'",
            $answerModelContent
        );
        
        file_put_contents($answerModelPath, $newFillable);
        echo "✅ Updated Answer model fillable fields\n";
    } else {
        echo "✅ Answer model fillable fields already updated\n";
    }
    
    // Create sample questions for existing quizzes
    echo "\n📝 Creating sample questions...\n";
    $quizzes = \App\Models\Quiz::all();
    
    foreach ($quizzes as $quiz) {
        $existingQuestions = $quiz->questions()->count();
        if ($existingQuestions == 0) {
            echo "Creating questions for quiz: {$quiz->title}\n";
            
            // Create 3 sample questions
            $questions = [
                [
                    'question_text' => 'What is 2 + 2?',
                    'type' => 'multiple_choice',
                    'points' => 10,
                    'options' => ['3', '4', '5', '6'],
                    'correct_answer' => '4',
                    'explanation' => 'Basic addition: 2 + 2 = 4'
                ],
                [
                    'question_text' => 'Is the earth round?',
                    'type' => 'true_false',
                    'points' => 5,
                    'options' => ['True', 'False'],
                    'correct_answer' => 'True',
                    'explanation' => 'The Earth is approximately spherical in shape.'
                ],
                [
                    'question_text' => 'Explain the concept of gravity.',
                    'type' => 'essay',
                    'points' => 15,
                    'options' => null,
                    'correct_answer' => 'Gravity is a fundamental force that attracts objects with mass toward each other.',
                    'explanation' => 'This is an essay question requiring detailed explanation.'
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
    
    echo "\n🎉 Questions table fixes completed!\n";
    echo "✅ Added missing columns to questions and answers tables\n";
    echo "✅ Updated model fillable fields\n";
    echo "✅ Created sample questions for all quizzes\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
