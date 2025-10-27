<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSuccessPrediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'success_probability',
        'risk_factors',
        'predicted_completion_date',
        'confidence_score',
        'last_updated'
    ];

    protected $casts = [
        'success_probability' => 'float',
        'risk_factors' => 'array',
        'predicted_completion_date' => 'datetime',
        'confidence_score' => 'float',
        'last_updated' => 'datetime'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Scopes
    public function scopeHighRisk($query)
    {
        return $query->where('success_probability', '<', 0.3);
    }

    public function scopeMediumRisk($query)
    {
        return $query->whereBetween('success_probability', [0.3, 0.7]);
    }

    public function scopeLowRisk($query)
    {
        return $query->where('success_probability', '>', 0.7);
    }

    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    // Methods
    public function getRiskLevel()
    {
        if ($this->success_probability < 0.3) {
            return 'high';
        } elseif ($this->success_probability < 0.7) {
            return 'medium';
        }
        return 'low';
    }

    public function getMainRiskFactors()
    {
        return $this->risk_factors ?? [];
    }

    public function hasHighRisk()
    {
        return $this->success_probability < 0.3;
    }

    public function isConfident()
    {
        return $this->confidence_score > 0.8;
    }

    public static function predictForStudent($studentId, $courseId)
    {
        $student = User::find($studentId);
        $course = Course::find($courseId);

        if (!$student || !$course) {
            return null;
        }

        // Calculate success probability based on various factors
        $probability = self::calculateSuccessProbability($student, $course);
        $riskFactors = self::identifyRiskFactors($student, $course);
        $confidenceScore = self::calculateConfidenceScore($student, $course);

        return self::updateOrCreate(
            ['student_id' => $studentId, 'course_id' => $courseId],
            [
                'success_probability' => $probability,
                'risk_factors' => $riskFactors,
                'confidence_score' => $confidenceScore,
                'predicted_completion_date' => now()->addDays(30),
                'last_updated' => now()
            ]
        );
    }

    private static function calculateSuccessProbability($student, $course)
    {
        $score = 0.5; // Base score

        // Factor 1: Previous course completion rate (30%)
        $completionRate = $student->enrollments()
            ->where('status', 'completed')
            ->count() / max($student->enrollments()->count(), 1);
        $score += ($completionRate * 0.3);

        // Factor 2: Course difficulty vs student level (20%)
        $difficultyMatch = self::calculateDifficultyMatch($student, $course);
        $score += ($difficultyMatch * 0.2);

        // Factor 3: Time spent on similar courses (20%)
        $timeSpent = self::calculateAverageTimeSpent($student, $course);
        $score += (min($timeSpent / 100, 1) * 0.2);

        // Factor 4: Quiz performance (20%)
        $quizPerformance = self::calculateQuizPerformance($student);
        $score += ($quizPerformance * 0.2);

        // Factor 5: Engagement level (10%)
        $engagement = self::calculateEngagementLevel($student);
        $score += ($engagement * 0.1);

        return min(max($score, 0), 1);
    }

    private static function identifyRiskFactors($student, $course)
    {
        $factors = [];

        // Check completion rate
        $completionRate = $student->enrollments()
            ->where('status', 'completed')
            ->count() / max($student->enrollments()->count(), 1);
        if ($completionRate < 0.5) {
            $factors[] = 'Low historical completion rate';
        }

        // Check course difficulty
        if ($course->difficulty === 'advanced' && $student->enrollments()->count() < 3) {
            $factors[] = 'Advanced course with limited experience';
        }

        // Check engagement
        $engagement = self::calculateEngagementLevel($student);
        if ($engagement < 0.3) {
            $factors[] = 'Low engagement level';
        }

        return $factors;
    }

    private static function calculateConfidenceScore($student, $course)
    {
        // Confidence based on data availability and consistency
        $enrollmentCount = $student->enrollments()->count();
        $confidence = min($enrollmentCount / 10, 1);

        return $confidence;
    }

    private static function calculateDifficultyMatch($student, $course)
    {
        $studentLevel = $student->enrollments()
            ->with('course')
            ->get()
            ->avg(function ($enrollment) {
                return match($enrollment->course->difficulty) {
                    'beginner' => 1,
                    'intermediate' => 2,
                    'advanced' => 3,
                    default => 2
                };
            });

        $courseDifficulty = match($course->difficulty) {
            'beginner' => 1,
            'intermediate' => 2,
            'advanced' => 3,
            default => 2
        };

        $diff = abs($studentLevel - $courseDifficulty);
        return max(1 - ($diff * 0.2), 0);
    }

    private static function calculateAverageTimeSpent($student, $course)
    {
        return $student->enrollments()
            ->with('course')
            ->get()
            ->avg(function ($enrollment) {
                return $enrollment->course->duration_hours ?? 0;
            });
    }

    private static function calculateQuizPerformance($student)
    {
        $quizzes = $student->answers()
            ->with('question.quiz')
            ->get();

        if ($quizzes->isEmpty()) {
            return 0.5;
        }

        $correctCount = $quizzes->filter(function ($answer) {
            return $answer->is_correct;
        })->count();

        return $correctCount / $quizzes->count();
    }

    private static function calculateEngagementLevel($student)
    {
        $recentActivity = $student->activityLogs()
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        return min($recentActivity / 20, 1);
    }
}

