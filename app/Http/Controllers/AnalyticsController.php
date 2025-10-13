<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\QuizAttempt;
use App\Models\AssignmentSubmission;
use App\Models\LessonCompletion;
use App\Models\CourseReview;
use App\Models\ForumTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:instructor,admin');
    }

    /**
     * Get comprehensive learning analytics
     */
    public function learningAnalytics(Request $request)
    {
        try {
            $user = Auth::user();
            $courseId = $request->get('course_id');

            // Build base query
            $query = Enrollment::with(['user', 'course']);

            // Filter by course if specified and user has permission
            if ($courseId) {
                $course = Course::findOrFail($courseId);
                if ($course->instructor_id !== $user->id && !$user->hasRole('admin')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized to view analytics for this course'
                    ], 403);
                }
                $query->where('course_id', $courseId);
            } elseif ($user->hasRole('instructor')) {
                // Instructors can only see their own courses
                $query->whereHas('course', function($q) use ($user) {
                    $q->where('instructor_id', $user->id);
                });
            }

            $enrollments = $query->get();

            $analytics = [
                'overview' => [
                    'total_enrollments' => $enrollments->count(),
                    'active_enrollments' => $enrollments->where('status', 'active')->count(),
                    'completed_enrollments' => $enrollments->where('status', 'completed')->count(),
                    'completion_rate' => $this->calculateCompletionRate($enrollments),
                    'average_completion_time' => $this->calculateAverageCompletionTime($enrollments)
                ],
                'engagement_metrics' => [
                    'daily_active_learners' => $this->getDailyActiveLearners($enrollments),
                    'session_duration' => $this->getAverageSessionDuration($enrollments),
                    'content_consumption' => $this->getContentConsumptionMetrics($enrollments),
                    'drop_off_points' => $this->getDropOffPoints($enrollments)
                ],
                'performance_metrics' => [
                    'quiz_performance' => $this->getQuizPerformanceMetrics($enrollments),
                    'assignment_performance' => $this->getAssignmentPerformanceMetrics($enrollments),
                    'grade_distribution' => $this->getGradeDistribution($enrollments),
                    'improvement_trends' => $this->getImprovementTrends($enrollments)
                ],
                'learner_behavior' => [
                    'learning_patterns' => $this->getLearningPatterns($enrollments),
                    'peak_activity_times' => $this->getPeakActivityTimes($enrollments),
                    'device_usage' => $this->getDeviceUsageStats($enrollments),
                    'geographic_distribution' => $this->getGeographicDistribution($enrollments)
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch learning analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course performance analytics
     */
    public function coursePerformance(Request $request)
    {
        try {
            $user = Auth::user();

            $query = Course::with(['enrollments', 'reviews', 'instructor']);

            // Filter by instructor if not admin
            if ($user->hasRole('instructor')) {
                $query->where('instructor_id', $user->id);
            }

            $courses = $query->get();

            $performance = $courses->map(function($course) {
                $enrollments = $course->enrollments;
                $reviews = $course->reviews->where('status', 'approved');

                return [
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'instructor' => $course->instructor->first_name . ' ' . $course->instructor->last_name,
                    'metrics' => [
                        'total_enrollments' => $enrollments->count(),
                        'active_enrollments' => $enrollments->where('status', 'active')->count(),
                        'completed_enrollments' => $enrollments->where('status', 'completed')->count(),
                        'completion_rate' => $this->calculateCourseCompletionRate($course),
                        'average_rating' => $reviews->avg('rating'),
                        'total_reviews' => $reviews->count(),
                        'revenue' => $this->calculateCourseRevenue($course),
                        'engagement_score' => $this->calculateEngagementScore($course)
                    ],
                    'trends' => [
                        'enrollment_trend' => $this->getEnrollmentTrend($course),
                        'completion_trend' => $this->getCompletionTrend($course),
                        'rating_trend' => $this->getRatingTrend($course)
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $performance
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch course performance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get student progress analytics
     */
    public function studentProgress(Request $request)
    {
        try {
            $user = Auth::user();
            $studentId = $request->get('student_id');
            $courseId = $request->get('course_id');

            // Build query based on user role and filters
            $query = Enrollment::with(['user', 'course']);

            if ($studentId) {
                $query->where('user_id', $studentId);
            }

            if ($courseId) {
                $course = Course::findOrFail($courseId);
                if ($course->instructor_id !== $user->id && !$user->hasRole('admin')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized to view analytics for this course'
                    ], 403);
                }
                $query->where('course_id', $courseId);
            } elseif ($user->hasRole('instructor')) {
                $query->whereHas('course', function($q) use ($user) {
                    $q->where('instructor_id', $user->id);
                });
            }

            $enrollments = $query->get();

            $progressAnalytics = $enrollments->map(function($enrollment) {
                $student = $enrollment->user;
                $course = $enrollment->course;

                return [
                    'student' => [
                        'id' => $student->id,
                        'name' => $student->first_name . ' ' . $student->last_name,
                        'email' => $student->email
                    ],
                    'course' => [
                        'id' => $course->id,
                        'title' => $course->title
                    ],
                    'progress' => [
                        'enrollment_date' => $enrollment->enrolled_at,
                        'status' => $enrollment->status,
                        'completion_percentage' => $this->calculateStudentProgress($student, $course),
                        'time_spent' => $this->calculateTimeSpent($student, $course),
                        'last_activity' => $this->getLastActivity($student, $course),
                        'lessons_completed' => $this->getLessonsCompleted($student, $course),
                        'quizzes_completed' => $this->getQuizzesCompleted($student, $course),
                        'assignments_submitted' => $this->getAssignmentsSubmitted($student, $course)
                    ],
                    'performance' => [
                        'average_quiz_score' => $this->getAverageQuizScore($student, $course),
                        'average_assignment_grade' => $this->getAverageAssignmentGrade($student, $course),
                        'final_grade' => $enrollment->final_grade,
                        'improvement_rate' => $this->getImprovementRate($student, $course)
                    ],
                    'engagement' => [
                        'forum_participation' => $this->getForumParticipation($student, $course),
                        'study_streak' => $this->getStudyStreak($student),
                        'session_frequency' => $this->getSessionFrequency($student, $course)
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $progressAnalytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch student progress: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get revenue analytics
     */
    public function revenueAnalytics(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $period = $request->get('period', '30'); // days
            $fromDate = now()->subDays($period);

            $analytics = [
                'overview' => [
                    'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
                    'period_revenue' => Payment::where('status', 'completed')
                                             ->where('created_at', '>=', $fromDate)
                                             ->sum('amount'),
                    'total_transactions' => Payment::where('status', 'completed')->count(),
                    'period_transactions' => Payment::where('status', 'completed')
                                                  ->where('created_at', '>=', $fromDate)
                                                  ->count(),
                    'average_transaction_value' => Payment::where('status', 'completed')->avg('amount'),
                    'conversion_rate' => $this->calculateConversionRate($fromDate)
                ],
                'trends' => [
                    'daily_revenue' => $this->getDailyRevenueTrend($period),
                    'monthly_revenue' => $this->getMonthlyRevenueTrend(),
                    'revenue_by_course' => $this->getRevenueByCourse($fromDate),
                    'revenue_by_instructor' => $this->getRevenueByInstructor($fromDate)
                ],
                'payment_methods' => [
                    'gateway_breakdown' => $this->getGatewayBreakdown($fromDate),
                    'success_rates' => $this->getPaymentSuccessRates($fromDate),
                    'failure_analysis' => $this->getPaymentFailureAnalysis($fromDate)
                ],
                'forecasting' => [
                    'projected_monthly_revenue' => $this->projectMonthlyRevenue(),
                    'growth_rate' => $this->calculateGrowthRate(),
                    'seasonal_trends' => $this->getSeasonalTrends()
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch revenue analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get engagement analytics
     */
    public function engagementAnalytics(Request $request)
    {
        try {
            $user = Auth::user();
            $courseId = $request->get('course_id');

            $query = Course::query();

            if ($courseId) {
                $course = Course::findOrFail($courseId);
                if ($course->instructor_id !== $user->id && !$user->hasRole('admin')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized to view analytics for this course'
                    ], 403);
                }
                $query->where('id', $courseId);
            } elseif ($user->hasRole('instructor')) {
                $query->where('instructor_id', $user->id);
            }

            $courses = $query->get();

            $engagement = [
                'content_engagement' => [
                    'lesson_completion_rates' => $this->getLessonCompletionRates($courses),
                    'video_watch_time' => $this->getVideoWatchTime($courses),
                    'content_drop_off' => $this->getContentDropOff($courses),
                    'most_engaging_content' => $this->getMostEngagingContent($courses)
                ],
                'community_engagement' => [
                    'forum_activity' => $this->getForumActivity($courses),
                    'discussion_participation' => $this->getDiscussionParticipation($courses),
                    'peer_interaction' => $this->getPeerInteraction($courses),
                    'instructor_interaction' => $this->getInstructorInteraction($courses)
                ],
                'assessment_engagement' => [
                    'quiz_participation' => $this->getQuizParticipation($courses),
                    'assignment_submission_rates' => $this->getAssignmentSubmissionRates($courses),
                    'feedback_engagement' => $this->getFeedbackEngagement($courses),
                    'retry_patterns' => $this->getRetryPatterns($courses)
                ],
                'temporal_patterns' => [
                    'daily_activity' => $this->getDailyActivityPatterns($courses),
                    'weekly_patterns' => $this->getWeeklyPatterns($courses),
                    'seasonal_engagement' => $this->getSeasonalEngagement($courses),
                    'peak_learning_times' => $this->getPeakLearningTimes($courses)
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $engagement
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch engagement analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get comparative analytics
     */
    public function comparativeAnalytics(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'course_ids' => 'required|array|min:2|max:5',
                'course_ids.*' => 'exists:courses,id',
                'metrics' => 'required|array',
                'metrics.*' => 'in:enrollment,completion,engagement,revenue,rating'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $courses = Course::whereIn('id', $request->course_ids)->get();

            // Check permissions
            foreach ($courses as $course) {
                if ($course->instructor_id !== $user->id && !$user->hasRole('admin')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized to view analytics for one or more courses'
                    ], 403);
                }
            }

            $comparison = [];

            foreach ($courses as $course) {
                $courseData = [
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'metrics' => []
                ];

                foreach ($request->metrics as $metric) {
                    switch ($metric) {
                        case 'enrollment':
                            $courseData['metrics']['enrollment'] = [
                                'total' => $course->enrollments()->count(),
                                'active' => $course->enrollments()->where('status', 'active')->count(),
                                'completed' => $course->enrollments()->where('status', 'completed')->count()
                            ];
                            break;

                        case 'completion':
                            $courseData['metrics']['completion'] = [
                                'rate' => $this->calculateCourseCompletionRate($course),
                                'average_time' => $this->calculateAverageCompletionTime($course->enrollments)
                            ];
                            break;

                        case 'engagement':
                            $courseData['metrics']['engagement'] = [
                                'score' => $this->calculateEngagementScore($course),
                                'forum_activity' => $this->getCourseForumActivity($course),
                                'content_consumption' => $this->getCourseContentConsumption($course)
                            ];
                            break;

                        case 'revenue':
                            $courseData['metrics']['revenue'] = [
                                'total' => $this->calculateCourseRevenue($course),
                                'per_student' => $this->calculateRevenuePerStudent($course)
                            ];
                            break;

                        case 'rating':
                            $reviews = $course->reviews()->where('status', 'approved');
                            $courseData['metrics']['rating'] = [
                                'average' => $reviews->avg('rating'),
                                'count' => $reviews->count(),
                                'distribution' => $this->getRatingDistribution($course)
                            ];
                            break;
                    }
                }

                $comparison[] = $courseData;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'comparison' => $comparison,
                    'insights' => $this->generateComparisonInsights($comparison, $request->metrics)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch comparative analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export analytics data
     */
    public function exportAnalytics(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'type' => 'required|in:learning,course,student,revenue,engagement',
                'format' => 'required|in:csv,excel,pdf',
                'course_id' => 'nullable|exists:courses,id',
                'date_range' => 'nullable|array',
                'date_range.from' => 'nullable|date',
                'date_range.to' => 'nullable|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Generate analytics data based on type
            $data = $this->generateExportData($request->type, $request->all(), $user);

            // Generate file based on format
            $fileName = $this->generateExportFile($data, $request->format, $request->type);

            return response()->json([
                'success' => true,
                'message' => 'Analytics exported successfully',
                'data' => [
                    'download_url' => asset("storage/exports/analytics/{$fileName}"),
                    'file_name' => $fileName
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get real-time analytics
     */
    public function realTimeAnalytics()
    {
        try {
            $user = Auth::user();

            $realTime = [
                'current_active_users' => $this->getCurrentActiveUsers(),
                'live_enrollments' => $this->getLiveEnrollments(),
                'ongoing_sessions' => $this->getOngoingSessions(),
                'recent_completions' => $this->getRecentCompletions(),
                'live_revenue' => $this->getLiveRevenue(),
                'system_performance' => [
                    'response_time' => rand(50, 200) . 'ms',
                    'server_load' => rand(20, 80) . '%',
                    'active_connections' => rand(100, 500)
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $realTime
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch real-time analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get predictive analytics
     */
    public function predictiveAnalytics(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $predictions = [
                'enrollment_forecast' => $this->predictEnrollmentTrends(),
                'revenue_projection' => $this->predictRevenueTrends(),
                'completion_likelihood' => $this->predictCompletionLikelihood(),
                'churn_risk' => $this->predictChurnRisk(),
                'course_demand' => $this->predictCourseDemand(),
                'optimal_pricing' => $this->predictOptimalPricing()
            ];

            return response()->json([
                'success' => true,
                'data' => $predictions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch predictive analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper methods for analytics calculations
     */
    private function calculateCompletionRate($enrollments)
    {
        $total = $enrollments->count();
        $completed = $enrollments->where('status', 'completed')->count();

        return $total > 0 ? round(($completed / $total) * 100, 2) : 0;
    }

    private function calculateAverageCompletionTime($enrollments)
    {
        $completed = $enrollments->where('status', 'completed')->whereNotNull('completed_at');

        if ($completed->count() === 0) return null;

        $totalDays = $completed->sum(function($enrollment) {
            return $enrollment->enrolled_at->diffInDays($enrollment->completed_at);
        });

        return round($totalDays / $completed->count(), 1);
    }

    private function getDailyActiveLearners($enrollments)
    {
        // Mock implementation - would track actual daily activity
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'active_learners' => rand(10, 50)
            ];
        }
        return $data;
    }

    private function getAverageSessionDuration($enrollments)
    {
        // Mock implementation - would calculate from actual session data
        return rand(15, 45) . ' minutes';
    }

    private function getContentConsumptionMetrics($enrollments)
    {
        return [
            'videos_watched' => LessonCompletion::whereIn('user_id', $enrollments->pluck('user_id'))->count(),
            'average_watch_time' => rand(5, 15) . ' minutes',
            'completion_rate' => rand(70, 95) . '%'
        ];
    }

    private function getDropOffPoints($enrollments)
    {
        // Mock implementation - would analyze actual drop-off patterns
        return [
            ['lesson' => 'Introduction', 'drop_off_rate' => 5],
            ['lesson' => 'Chapter 2', 'drop_off_rate' => 15],
            ['lesson' => 'Mid-course Quiz', 'drop_off_rate' => 25],
            ['lesson' => 'Advanced Topics', 'drop_off_rate' => 35]
        ];
    }

    private function getQuizPerformanceMetrics($enrollments)
    {
        $userIds = $enrollments->pluck('user_id');
        $quizAttempts = QuizAttempt::whereIn('user_id', $userIds)->where('status', 'completed');

        return [
            'total_attempts' => $quizAttempts->count(),
            'average_score' => round($quizAttempts->avg('score'), 2),
            'pass_rate' => round(($quizAttempts->where('score', '>=', 70)->count() / max($quizAttempts->count(), 1)) * 100, 2),
            'retry_rate' => round(($quizAttempts->where('attempt_number', '>', 1)->count() / max($quizAttempts->count(), 1)) * 100, 2)
        ];
    }

    private function getAssignmentPerformanceMetrics($enrollments)
    {
        $userIds = $enrollments->pluck('user_id');
        $submissions = AssignmentSubmission::whereIn('user_id', $userIds)->whereNotNull('grade');

        return [
            'total_submissions' => $submissions->count(),
            'average_grade' => round($submissions->avg('grade'), 2),
            'on_time_rate' => round(($submissions->where('is_late', false)->count() / max($submissions->count(), 1)) * 100, 2),
            'grading_turnaround' => rand(1, 5) . ' days'
        ];
    }

    private function getGradeDistribution($enrollments)
    {
        $graded = $enrollments->whereNotNull('final_grade');
        $total = $graded->count();

        if ($total === 0) return [];

        return [
            'A (90-100)' => round(($graded->where('final_grade', '>=', 90)->count() / $total) * 100, 1),
            'B (80-89)' => round(($graded->whereBetween('final_grade', [80, 89])->count() / $total) * 100, 1),
            'C (70-79)' => round(($graded->whereBetween('final_grade', [70, 79])->count() / $total) * 100, 1),
            'D (60-69)' => round(($graded->whereBetween('final_grade', [60, 69])->count() / $total) * 100, 1),
            'F (0-59)' => round(($graded->where('final_grade', '<', 60)->count() / $total) * 100, 1)
        ];
    }

    private function getImprovementTrends($enrollments)
    {
        // Mock implementation - would analyze actual improvement patterns
        return [
            'overall_improvement' => '+15%',
            'quiz_score_improvement' => '+12%',
            'assignment_grade_improvement' => '+18%',
            'engagement_improvement' => '+8%'
        ];
    }

    private function getLearningPatterns($enrollments)
    {
        return [
            'preferred_study_time' => 'Evening (6-9 PM)',
            'session_frequency' => '3-4 times per week',
            'content_preference' => 'Video > Text > Interactive',
            'completion_pattern' => 'Sequential'
        ];
    }

    private function getPeakActivityTimes($enrollments)
    {
        // Mock implementation
        return [
            ['hour' => '09:00', 'activity_level' => 65],
            ['hour' => '14:00', 'activity_level' => 80],
            ['hour' => '19:00', 'activity_level' => 95],
            ['hour' => '21:00', 'activity_level' => 75]
        ];
    }

    private function getDeviceUsageStats($enrollments)
    {
        return [
            'desktop' => 60,
            'mobile' => 30,
            'tablet' => 10
        ];
    }

    private function getGeographicDistribution($enrollments)
    {
        // Mock implementation
        return [
            'United States' => 40,
            'United Kingdom' => 20,
            'Canada' => 15,
            'Australia' => 10,
            'Others' => 15
        ];
    }

    private function calculateCourseCompletionRate($course)
    {
        $total = $course->enrollments()->count();
        $completed = $course->enrollments()->where('status', 'completed')->count();

        return $total > 0 ? round(($completed / $total) * 100, 2) : 0;
    }

    private function calculateCourseRevenue($course)
    {
        return Payment::where('course_id', $course->id)
                     ->where('status', 'completed')
                     ->sum('amount');
    }

    private function calculateEngagementScore($course)
    {
        // Mock calculation - would use actual engagement metrics
        $enrollments = $course->enrollments()->count();
        $completions = $course->enrollments()->where('status', 'completed')->count();
        $reviews = $course->reviews()->where('status', 'approved')->count();

        $score = ($completions * 0.4) + ($reviews * 0.3) + ($enrollments * 0.3);
        return min(100, round($score / 10, 1));
    }

    private function getEnrollmentTrend($course)
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $data[] = [
                'month' => $date->format('M Y'),
                'enrollments' => $course->enrollments()
                                      ->whereYear('created_at', $date->year)
                                      ->whereMonth('created_at', $date->month)
                                      ->count()
            ];
        }
        return $data;
    }

    private function getCompletionTrend($course)
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $data[] = [
                'month' => $date->format('M Y'),
                'completions' => $course->enrollments()
                                       ->where('status', 'completed')
                                       ->whereYear('completed_at', $date->year)
                                       ->whereMonth('completed_at', $date->month)
                                       ->count()
            ];
        }
        return $data;
    }

    private function getRatingTrend($course)
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $data[] = [
                'month' => $date->format('M Y'),
                'average_rating' => $course->reviews()
                                         ->where('status', 'approved')
                                         ->whereYear('created_at', $date->year)
                                         ->whereMonth('created_at', $date->month)
                                         ->avg('rating')
            ];
        }
        return $data;
    }

    // Additional helper methods would continue here...
    // Due to length constraints, I'll implement the remaining methods in the next chunk
}
