<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CohortAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'cohort_name',
        'start_date',
        'end_date',
        'student_count',
        'completion_rate',
        'average_score',
        'retention_rate',
        'dropout_count',
        'metadata'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'student_count' => 'integer',
        'completion_rate' => 'float',
        'average_score' => 'float',
        'retention_rate' => 'float',
        'dropout_count' => 'integer',
        'metadata' => 'array'
    ];

    // Relationships
    public function students()
    {
        return $this->belongsToMany(User::class, 'cohort_students')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('end_date', '>=', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('end_date', '<', now());
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate]);
    }

    // Methods
    public function getRetentionPercentage()
    {
        return round($this->retention_rate * 100, 2);
    }

    public function getCompletionPercentage()
    {
        return round($this->completion_rate * 100, 2);
    }

    public function getDropoutPercentage()
    {
        return round((1 - $this->retention_rate) * 100, 2);
    }

    public function getAverageScorePercentage()
    {
        return round($this->average_score * 100, 2);
    }

    public function isHighPerforming()
    {
        return $this->completion_rate > 0.8 && $this->average_score > 0.75;
    }

    public function isAtRisk()
    {
        return $this->completion_rate < 0.5 || $this->retention_rate < 0.6;
    }

    public static function createFromEnrollments($cohortName, $startDate, $endDate)
    {
        $enrollments = Enrollment::whereBetween('created_at', [$startDate, $endDate])
                                 ->get();

        $studentCount = $enrollments->pluck('user_id')->unique()->count();
        $completedCount = $enrollments->where('status', 'completed')->count();
        $completionRate = $studentCount > 0 ? $completedCount / $studentCount : 0;
        $dropoutCount = $enrollments->where('status', 'dropped')->count();
        $retentionRate = $studentCount > 0 ? ($studentCount - $dropoutCount) / $studentCount : 0;

        // Calculate average score
        $averageScore = self::calculateAverageScore($enrollments);

        $cohort = self::create([
            'cohort_name' => $cohortName,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'student_count' => $studentCount,
            'completion_rate' => $completionRate,
            'average_score' => $averageScore,
            'retention_rate' => $retentionRate,
            'dropout_count' => $dropoutCount,
            'metadata' => [
                'created_at_analysis' => now(),
                'enrollment_count' => $enrollments->count()
            ]
        ]);

        // Attach students to cohort
        foreach ($enrollments->pluck('user_id')->unique() as $userId) {
            $cohort->students()->attach($userId);
        }

        return $cohort;
    }

    private static function calculateAverageScore($enrollments)
    {
        $totalScore = 0;
        $count = 0;

        foreach ($enrollments as $enrollment) {
            $quizzes = $enrollment->course->quizzes;
            foreach ($quizzes as $quiz) {
                $attempts = $enrollment->user->quizAttempts()
                    ->where('quiz_id', $quiz->id)
                    ->get();

                foreach ($attempts as $attempt) {
                    $totalScore += $attempt->score ?? 0;
                    $count++;
                }
            }
        }

        return $count > 0 ? $totalScore / $count : 0;
    }

    public function getComparisonMetrics($otherCohort)
    {
        return [
            'completion_rate_diff' => $this->completion_rate - $otherCohort->completion_rate,
            'retention_rate_diff' => $this->retention_rate - $otherCohort->retention_rate,
            'average_score_diff' => $this->average_score - $otherCohort->average_score,
            'student_count_diff' => $this->student_count - $otherCohort->student_count
        ];
    }

    public function getWeeklyProgress()
    {
        $weeks = [];
        $currentDate = $this->start_date->copy();

        while ($currentDate <= $this->end_date) {
            $weekEnd = $currentDate->copy()->addDays(7);
            $weekEnrollments = Enrollment::whereBetween('created_at', [$currentDate, $weekEnd])
                                        ->get();

            $weeks[] = [
                'week' => $currentDate->format('Y-m-d'),
                'enrollments' => $weekEnrollments->count(),
                'completions' => $weekEnrollments->where('status', 'completed')->count(),
                'dropouts' => $weekEnrollments->where('status', 'dropped')->count()
            ];

            $currentDate = $weekEnd;
        }

        return $weeks;
    }
}

