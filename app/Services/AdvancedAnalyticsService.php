<?php

namespace App\Services;

use App\Models\StudentSuccessPrediction;
use App\Models\CohortAnalysis;
use App\Models\EngagementScore;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Cache;

class AdvancedAnalyticsService
{
    /**
     * Get predictive analytics for a student
     */
    public function getStudentPredictions($studentId)
    {
        $student = User::find($studentId);
        if (!$student) {
            return null;
        }

        $predictions = StudentSuccessPrediction::where('student_id', $studentId)->get();

        return [
            'student_id' => $studentId,
            'total_predictions' => $predictions->count(),
            'high_risk_count' => $predictions->highRisk()->count(),
            'medium_risk_count' => $predictions->mediumRisk()->count(),
            'low_risk_count' => $predictions->lowRisk()->count(),
            'predictions' => $predictions->map(function ($prediction) {
                return [
                    'course_id' => $prediction->course_id,
                    'course_title' => $prediction->course->title,
                    'success_probability' => round($prediction->success_probability * 100, 2),
                    'risk_level' => $prediction->getRiskLevel(),
                    'risk_factors' => $prediction->getMainRiskFactors(),
                    'predicted_completion_date' => $prediction->predicted_completion_date,
                    'confidence_score' => round($prediction->confidence_score * 100, 2)
                ];
            })
        ];
    }

    /**
     * Get cohort analysis
     */
    public function getCohortAnalysis($cohortId)
    {
        $cohort = CohortAnalysis::find($cohortId);
        if (!$cohort) {
            return null;
        }

        return [
            'cohort_id' => $cohort->id,
            'cohort_name' => $cohort->cohort_name,
            'period' => [
                'start' => $cohort->start_date,
                'end' => $cohort->end_date
            ],
            'metrics' => [
                'student_count' => $cohort->student_count,
                'completion_rate' => round($cohort->getCompletionPercentage(), 2),
                'retention_rate' => round($cohort->getRetentionPercentage(), 2),
                'dropout_rate' => round($cohort->getDropoutPercentage(), 2),
                'average_score' => round($cohort->getAverageScorePercentage(), 2),
                'dropout_count' => $cohort->dropout_count
            ],
            'status' => $cohort->isHighPerforming() ? 'high_performing' : ($cohort->isAtRisk() ? 'at_risk' : 'normal'),
            'weekly_progress' => $cohort->getWeeklyProgress()
        ];
    }

    /**
     * Compare two cohorts
     */
    public function compareCohorts($cohortId1, $cohortId2)
    {
        $cohort1 = CohortAnalysis::find($cohortId1);
        $cohort2 = CohortAnalysis::find($cohortId2);

        if (!$cohort1 || !$cohort2) {
            return null;
        }

        $comparison = $cohort1->getComparisonMetrics($cohort2);

        return [
            'cohort_1' => [
                'name' => $cohort1->cohort_name,
                'completion_rate' => round($cohort1->getCompletionPercentage(), 2),
                'retention_rate' => round($cohort1->getRetentionPercentage(), 2),
                'average_score' => round($cohort1->getAverageScorePercentage(), 2)
            ],
            'cohort_2' => [
                'name' => $cohort2->cohort_name,
                'completion_rate' => round($cohort2->getCompletionPercentage(), 2),
                'retention_rate' => round($cohort2->getRetentionPercentage(), 2),
                'average_score' => round($cohort2->getAverageScorePercentage(), 2)
            ],
            'differences' => [
                'completion_rate_diff' => round($comparison['completion_rate_diff'] * 100, 2),
                'retention_rate_diff' => round($comparison['retention_rate_diff'] * 100, 2),
                'average_score_diff' => round($comparison['average_score_diff'] * 100, 2),
                'student_count_diff' => $comparison['student_count_diff']
            ]
        ];
    }

    /**
     * Get engagement scores for a course
     */
    public function getCourseEngagementScores($courseId)
    {
        $scores = EngagementScore::where('course_id', $courseId)->get();

        return [
            'course_id' => $courseId,
            'total_students' => $scores->count(),
            'high_engagement' => $scores->highEngagement()->count(),
            'medium_engagement' => $scores->mediumEngagement()->count(),
            'low_engagement' => $scores->lowEngagement()->count(),
            'average_score' => round($scores->avg('score'), 2),
            'scores' => $scores->map(function ($score) {
                return [
                    'user_id' => $score->user_id,
                    'user_name' => $score->user->name,
                    'engagement_level' => $score->getEngagementLevel(),
                    'score' => round($score->score, 2),
                    'breakdown' => $score->getScoreBreakdown(),
                    'at_risk' => $score->isAtRisk()
                ];
            })
        ];
    }

    /**
     * Get student engagement score
     */
    public function getStudentEngagementScore($userId, $courseId)
    {
        $score = EngagementScore::where('user_id', $userId)
                               ->where('course_id', $courseId)
                               ->first();

        if (!$score) {
            return null;
        }

        return [
            'user_id' => $userId,
            'course_id' => $courseId,
            'engagement_level' => $score->getEngagementLevel(),
            'overall_score' => round($score->score, 2),
            'breakdown' => $score->getScoreBreakdown(),
            'is_highly_engaged' => $score->isHighlyEngaged(),
            'is_at_risk' => $score->isAtRisk(),
            'last_updated' => $score->last_updated
        ];
    }

    /**
     * Generate cohort from date range
     */
    public function generateCohort($cohortName, $startDate, $endDate)
    {
        return CohortAnalysis::createFromEnrollments($cohortName, $startDate, $endDate);
    }

    /**
     * Predict student success for all enrolled courses
     */
    public function predictAllStudentSuccess($studentId)
    {
        $student = User::find($studentId);
        if (!$student) {
            return null;
        }

        $enrollments = $student->enrollments()->with('course')->get();
        $predictions = [];

        foreach ($enrollments as $enrollment) {
            $prediction = StudentSuccessPrediction::predictForStudent($studentId, $enrollment->course_id);
            if ($prediction) {
                $predictions[] = $prediction;
            }
        }

        return $predictions;
    }

    /**
     * Calculate engagement for all students in a course
     */
    public function calculateCourseEngagement($courseId)
    {
        $course = Course::find($courseId);
        if (!$course) {
            return null;
        }

        $students = $course->students()->get();
        $scores = [];

        foreach ($students as $student) {
            $score = EngagementScore::calculateForStudent($student->id, $courseId);
            if ($score) {
                $scores[] = $score;
            }
        }

        return $scores;
    }

    /**
     * Get at-risk students for a course
     */
    public function getAtRiskStudents($courseId)
    {
        $predictions = StudentSuccessPrediction::where('course_id', $courseId)
                                              ->highRisk()
                                              ->with('student')
                                              ->get();

        return $predictions->map(function ($prediction) {
            return [
                'student_id' => $prediction->student_id,
                'student_name' => $prediction->student->name,
                'student_email' => $prediction->student->email,
                'success_probability' => round($prediction->success_probability * 100, 2),
                'risk_factors' => $prediction->getMainRiskFactors(),
                'confidence_score' => round($prediction->confidence_score * 100, 2)
            ];
        });
    }

    /**
     * Get high-performing students for a course
     */
    public function getHighPerformingStudents($courseId)
    {
        $predictions = StudentSuccessPrediction::where('course_id', $courseId)
                                              ->lowRisk()
                                              ->with('student')
                                              ->get();

        return $predictions->map(function ($prediction) {
            return [
                'student_id' => $prediction->student_id,
                'student_name' => $prediction->student->name,
                'student_email' => $prediction->student->email,
                'success_probability' => round($prediction->success_probability * 100, 2),
                'confidence_score' => round($prediction->confidence_score * 100, 2)
            ];
        });
    }

    /**
     * Get analytics dashboard data
     */
    public function getDashboardData()
    {
        return Cache::remember('analytics_dashboard', 3600, function () {
            return [
                'total_predictions' => StudentSuccessPrediction::count(),
                'total_cohorts' => CohortAnalysis::count(),
                'total_engagement_scores' => EngagementScore::count(),
                'high_risk_students' => StudentSuccessPrediction::highRisk()->count(),
                'at_risk_courses' => CohortAnalysis::where('completion_rate', '<', 0.5)->count(),
                'average_engagement' => round(EngagementScore::avg('score'), 2)
            ];
        });
    }
}

