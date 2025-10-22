<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngagementScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'score',
        'lesson_completion_rate',
        'quiz_participation_rate',
        'forum_activity_score',
        'assignment_submission_rate',
        'time_spent_score',
        'last_updated'
    ];

    protected $casts = [
        'score' => 'float',
        'lesson_completion_rate' => 'float',
        'quiz_participation_rate' => 'float',
        'forum_activity_score' => 'float',
        'assignment_submission_rate' => 'float',
        'time_spent_score' => 'float',
        'last_updated' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Scopes
    public function scopeHighEngagement($query)
    {
        return $query->where('score', '>=', 80);
    }

    public function scopeMediumEngagement($query)
    {
        return $query->whereBetween('score', [50, 79]);
    }

    public function scopeLowEngagement($query)
    {
        return $query->where('score', '<', 50);
    }

    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Methods
    public function getEngagementLevel()
    {
        if ($this->score >= 80) {
            return 'high';
        } elseif ($this->score >= 50) {
            return 'medium';
        }
        return 'low';
    }

    public function isHighlyEngaged()
    {
        return $this->score >= 80;
    }

    public function isAtRisk()
    {
        return $this->score < 50;
    }

    public static function calculateForStudent($userId, $courseId)
    {
        $user = User::find($userId);
        $course = Course::find($courseId);

        if (!$user || !$course) {
            return null;
        }

        // Calculate individual components
        $lessonCompletionRate = self::calculateLessonCompletion($user, $course);
        $quizParticipationRate = self::calculateQuizParticipation($user, $course);
        $forumActivityScore = self::calculateForumActivity($user, $course);
        $assignmentSubmissionRate = self::calculateAssignmentSubmission($user, $course);
        $timeSpentScore = self::calculateTimeSpent($user, $course);

        // Calculate overall score (weighted average)
        $overallScore = (
            ($lessonCompletionRate * 0.30) +
            ($quizParticipationRate * 0.25) +
            ($forumActivityScore * 0.20) +
            ($assignmentSubmissionRate * 0.15) +
            ($timeSpentScore * 0.10)
        ) * 100;

        return self::updateOrCreate(
            ['user_id' => $userId, 'course_id' => $courseId],
            [
                'score' => $overallScore,
                'lesson_completion_rate' => $lessonCompletionRate * 100,
                'quiz_participation_rate' => $quizParticipationRate * 100,
                'forum_activity_score' => $forumActivityScore * 100,
                'assignment_submission_rate' => $assignmentSubmissionRate * 100,
                'time_spent_score' => $timeSpentScore * 100,
                'last_updated' => now()
            ]
        );
    }

    private static function calculateLessonCompletion($user, $course)
    {
        $totalLessons = $course->lessons()->count();
        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = $user->lessonCompletions()
            ->whereIn('lesson_id', $course->lessons()->pluck('id'))
            ->count();

        return $completedLessons / $totalLessons;
    }

    private static function calculateQuizParticipation($user, $course)
    {
        $totalQuizzes = $course->quizzes()->count();
        if ($totalQuizzes === 0) {
            return 0;
        }

        $attemptedQuizzes = $user->quizAttempts()
            ->whereIn('quiz_id', $course->quizzes()->pluck('id'))
            ->distinct('quiz_id')
            ->count();

        return $attemptedQuizzes / $totalQuizzes;
    }

    private static function calculateForumActivity($user, $course)
    {
        $forumPosts = $user->forumPosts()
            ->whereIn('forum_id', $course->forums()->pluck('id'))
            ->count();

        $forumReplies = $user->forumReplies()
            ->whereIn('forum_id', $course->forums()->pluck('id'))
            ->count();

        $totalActivity = $forumPosts + $forumReplies;

        // Normalize to 0-1 scale (assuming 10 posts/replies is high engagement)
        return min($totalActivity / 10, 1);
    }

    private static function calculateAssignmentSubmission($user, $course)
    {
        $totalAssignments = $course->assignments()->count();
        if ($totalAssignments === 0) {
            return 0;
        }

        $submittedAssignments = $user->assignmentSubmissions()
            ->whereIn('assignment_id', $course->assignments()->pluck('id'))
            ->count();

        return $submittedAssignments / $totalAssignments;
    }

    private static function calculateTimeSpent($user, $course)
    {
        $totalTimeSpent = $user->lessonCompletions()
            ->whereIn('lesson_id', $course->lessons()->pluck('id'))
            ->sum('time_spent');

        $expectedTimeSpent = $course->duration_hours * 60; // Convert to minutes

        if ($expectedTimeSpent === 0) {
            return 0;
        }

        return min($totalTimeSpent / $expectedTimeSpent, 1);
    }

    public function getScoreBreakdown()
    {
        return [
            'lesson_completion' => round($this->lesson_completion_rate, 2),
            'quiz_participation' => round($this->quiz_participation_rate, 2),
            'forum_activity' => round($this->forum_activity_score, 2),
            'assignment_submission' => round($this->assignment_submission_rate, 2),
            'time_spent' => round($this->time_spent_score, 2),
            'overall' => round($this->score, 2)
        ];
    }
}

