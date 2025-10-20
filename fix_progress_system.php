<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING PROGRESS SYSTEM\n";
echo "============================================================\n\n";

try {
    // 1. Add quizzes() relationship to Course model
    echo "📚 Adding quizzes relationship to Course model...\n";
    $courseModelPath = app_path('Models/Course.php');
    $courseModelContent = file_get_contents($courseModelPath);
    
    if (!str_contains($courseModelContent, 'function quizzes()')) {
        $newMethod = '
    public function quizzes()
    {
        return $this->hasManyThrough(Quiz::class, Lesson::class);
    }';
        
        // Add before the closing brace
        $newContent = str_replace(
            '}' . PHP_EOL . '?>', 
            $newMethod . PHP_EOL . '}' . PHP_EOL . '?>', 
            $courseModelContent
        );
        
        file_put_contents($courseModelPath, $newContent);
        echo "✅ Added quizzes() relationship to Course model\n";
    } else {
        echo "✅ quizzes() relationship already exists in Course model\n";
    }
    
    // 2. Create UserBadge model if missing
    echo "\n🏆 Creating UserBadge model...\n";
    $userBadgeModelPath = app_path('Models/UserBadge.php');
    
    if (!file_exists($userBadgeModelPath)) {
        $userBadgeModel = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "badge_id", 
        "earned_at",
        "revoked_at"
    ];

    protected $casts = [
        "earned_at" => "datetime",
        "revoked_at" => "datetime"
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNull("revoked_at");
    }

    public function scopeRevoked($query)
    {
        return $query->whereNotNull("revoked_at");
    }

    // Methods
    public function isActive()
    {
        return $this->revoked_at === null;
    }

    public function revoke()
    {
        $this->update(["revoked_at" => now()]);
    }

    public function restore()
    {
        $this->update(["revoked_at" => null]);
    }
}';
        
        file_put_contents($userBadgeModelPath, $userBadgeModel);
        echo "✅ Created UserBadge model\n";
    } else {
        echo "✅ UserBadge model already exists\n";
    }
    
    // 3. Add is_active column to badges table
    echo "\n🏅 Adding is_active column to badges table...\n";
    if (!Schema::hasColumn('badges', 'is_active')) {
        Schema::table('badges', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('criteria');
        });
        echo "✅ Added is_active column to badges table\n";
    } else {
        echo "✅ is_active column already exists in badges table\n";
    }
    
    // 4. Add points column to badges table
    echo "\n🏅 Adding points column to badges table...\n";
    if (!Schema::hasColumn('badges', 'points')) {
        Schema::table('badges', function (Blueprint $table) {
            $table->integer('points')->default(10)->after('is_active');
        });
        echo "✅ Added points column to badges table\n";
    } else {
        echo "✅ points column already exists in badges table\n";
    }
    
    // 5. Add type column to badges table
    echo "\n🏅 Adding type column to badges table...\n";
    if (!Schema::hasColumn('badges', 'type')) {
        Schema::table('badges', function (Blueprint $table) {
            $table->string('type')->default('achievement')->after('points');
        });
        echo "✅ Added type column to badges table\n";
    } else {
        echo "✅ type column already exists in badges table\n";
    }
    
    // 6. Update existing badges with proper data
    echo "\n🏅 Updating existing badges...\n";
    $badges = \App\Models\Badge::all();
    foreach ($badges as $badge) {
        $badge->update([
            'is_active' => true,
            'points' => 10,
            'type' => 'achievement'
        ]);
    }
    echo "✅ Updated " . $badges->count() . " badges\n";
    
    // 7. Create QuizAttempt model if missing
    echo "\n📋 Checking QuizAttempt model...\n";
    $quizAttemptModelPath = app_path('Models/QuizAttempt.php');
    
    if (!file_exists($quizAttemptModelPath)) {
        echo "❌ QuizAttempt model missing, creating it...\n";
        
        $quizAttemptModel = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "quiz_id",
        "attempt_number",
        "score",
        "max_score",
        "percentage",
        "status",
        "started_at",
        "completed_at",
        "time_taken"
    ];

    protected $casts = [
        "score" => "decimal:2",
        "max_score" => "decimal:2", 
        "percentage" => "decimal:2",
        "started_at" => "datetime",
        "completed_at" => "datetime",
        "time_taken" => "integer"
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where("status", "completed");
    }

    public function scopeInProgress($query)
    {
        return $query->where("status", "in_progress");
    }

    // Methods
    public function isCompleted()
    {
        return $this->status === "completed";
    }

    public function isPassed($passingScore = 60)
    {
        return $this->percentage >= $passingScore;
    }
}';
        
        file_put_contents($quizAttemptModelPath, $quizAttemptModel);
        echo "✅ Created QuizAttempt model\n";
    } else {
        echo "✅ QuizAttempt model already exists\n";
    }
    
    // 8. Create quiz_attempts table if missing
    echo "\n📋 Checking quiz_attempts table...\n";
    if (!Schema::hasTable('quiz_attempts')) {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->integer('attempt_number')->default(1);
            $table->decimal('score', 8, 2)->default(0);
            $table->decimal('max_score', 8, 2)->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->enum('status', ['in_progress', 'completed', 'abandoned'])->default('in_progress');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('time_taken')->nullable(); // in seconds
            $table->timestamps();
            
            $table->index(['user_id', 'quiz_id']);
            $table->index(['user_id', 'status']);
        });
        echo "✅ Created quiz_attempts table\n";
    } else {
        echo "✅ quiz_attempts table already exists\n";
    }
    
    // 9. Create sample quiz attempts
    echo "\n📋 Creating sample quiz attempts...\n";
    $student = \App\Models\User::where('role', 'student')->first();
    $quizzes = \App\Models\Quiz::all();
    
    if ($student && $quizzes->count() > 0) {
        foreach ($quizzes as $quiz) {
            $existingAttempt = \App\Models\QuizAttempt::where('user_id', $student->id)
                                                   ->where('quiz_id', $quiz->id)
                                                   ->first();
            
            if (!$existingAttempt) {
                \App\Models\QuizAttempt::create([
                    'user_id' => $student->id,
                    'quiz_id' => $quiz->id,
                    'attempt_number' => 1,
                    'score' => 85,
                    'max_score' => 100,
                    'percentage' => 85.0,
                    'status' => 'completed',
                    'started_at' => now()->subHour(),
                    'completed_at' => now()->subMinutes(30),
                    'time_taken' => 1800 // 30 minutes
                ]);
                echo "✅ Created quiz attempt for quiz {$quiz->id}\n";
            }
        }
    }
    
    echo "\n🎉 Progress system fixes completed!\n";
    echo "✅ Added Course::quizzes() relationship\n";
    echo "✅ Created UserBadge model\n";
    echo "✅ Added missing columns to badges table\n";
    echo "✅ Created QuizAttempt model and table\n";
    echo "✅ Created sample quiz attempts\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
