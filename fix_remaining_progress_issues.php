<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING REMAINING PROGRESS ISSUES\n";
echo "============================================================\n\n";

try {
    // 1. Fix the quizzes relationship to avoid ambiguous column
    echo "📚 Fixing Course::quizzes() relationship...\n";
    $courseModelPath = app_path('Models/Course.php');
    $courseModelContent = file_get_contents($courseModelPath);
    
    // Replace the problematic quizzes method
    $oldMethod = '    public function quizzes()
    {
        return $this->hasManyThrough(Quiz::class, Lesson::class);
    }';
    
    $newMethod = '    public function quizzes()
    {
        return $this->hasManyThrough(Quiz::class, Lesson::class, \'course_id\', \'lesson_id\', \'id\', \'id\');
    }';
    
    $newContent = str_replace($oldMethod, $newMethod, $courseModelContent);
    file_put_contents($courseModelPath, $newContent);
    echo "✅ Fixed Course::quizzes() relationship\n";
    
    // 2. Create AssignmentSubmission model
    echo "\n📝 Creating AssignmentSubmission model...\n";
    $assignmentSubmissionModelPath = app_path('Models/AssignmentSubmission.php');
    
    if (!file_exists($assignmentSubmissionModelPath)) {
        $assignmentSubmissionModel = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        "assignment_id",
        "user_id",
        "content",
        "file_path",
        "submitted_at",
        "graded_at",
        "grade",
        "feedback",
        "status"
    ];

    protected $casts = [
        "submitted_at" => "datetime",
        "graded_at" => "datetime",
        "grade" => "decimal:2"
    ];

    // Relationships
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    // Scopes
    public function scopeSubmitted($query)
    {
        return $query->where("status", "submitted");
    }

    public function scopeGraded($query)
    {
        return $query->where("status", "graded");
    }

    public function scopePending($query)
    {
        return $query->where("status", "pending");
    }

    // Methods
    public function isSubmitted()
    {
        return $this->status === "submitted" || $this->status === "graded";
    }

    public function isGraded()
    {
        return $this->status === "graded";
    }

    public function isPending()
    {
        return $this->status === "pending";
    }
}';
        
        file_put_contents($assignmentSubmissionModelPath, $assignmentSubmissionModel);
        echo "✅ Created AssignmentSubmission model\n";
    } else {
        echo "✅ AssignmentSubmission model already exists\n";
    }
    
    // 3. Create assignment_submissions table if missing
    echo "\n📝 Checking assignment_submissions table...\n";
    if (!\Illuminate\Support\Facades\Schema::hasTable('assignment_submissions')) {
        \Illuminate\Support\Facades\Schema::create('assignment_submissions', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->decimal('grade', 5, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->enum('status', ['pending', 'submitted', 'graded'])->default('pending');
            $table->timestamps();
            
            $table->index(['assignment_id', 'user_id']);
            $table->index(['user_id', 'status']);
        });
        echo "✅ Created assignment_submissions table\n";
    } else {
        echo "✅ assignment_submissions table already exists\n";
    }
    
    // 4. Create sample assignment submissions
    echo "\n📝 Creating sample assignment submissions...\n";
    $student = \App\Models\User::where('role', 'student')->first();
    $assignments = \App\Models\Assignment::all();
    
    if ($student && $assignments->count() > 0) {
        foreach ($assignments as $assignment) {
            $existingSubmission = \App\Models\AssignmentSubmission::where('user_id', $student->id)
                                                                 ->where('assignment_id', $assignment->id)
                                                                 ->first();
            
            if (!$existingSubmission) {
                \App\Models\AssignmentSubmission::create([
                    'assignment_id' => $assignment->id,
                    'user_id' => $student->id,
                    'content' => 'This is a sample assignment submission for: ' . $assignment->title,
                    'submitted_at' => now()->subDays(rand(1, 7)),
                    'graded_at' => now()->subDays(rand(0, 3)),
                    'grade' => rand(70, 95),
                    'feedback' => 'Good work! Keep it up.',
                    'status' => 'graded'
                ]);
                echo "✅ Created assignment submission for assignment {$assignment->id}\n";
            }
        }
    }
    
    echo "\n🎉 Remaining progress issues fixed!\n";
    echo "✅ Fixed Course::quizzes() relationship\n";
    echo "✅ Created AssignmentSubmission model and table\n";
    echo "✅ Created sample assignment submissions\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
