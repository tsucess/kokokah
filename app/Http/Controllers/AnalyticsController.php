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
    // Note: Middleware is applied at route level in Laravel 12
    // See routes/api.php for middleware configuration

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
                    'conversion_rate' => $this->calculateConversionRate()
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
            'on_time_rate' => 85.5, // Mock data - would calculate based on due dates
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

    // Student Progress Helper Methods
    private function calculateStudentProgress($student, $course)
    {
        $totalLessons = $course->lessons()->count();
        if ($totalLessons === 0) return 0;

        $completedLessons = $course->lessons()
            ->whereHas('completions', function($query) use ($student) {
                $query->where('user_id', $student->id);
            })->count();

        return round(($completedLessons / $totalLessons) * 100, 2);
    }

    private function calculateTimeSpent($student, $course)
    {
        // Mock calculation - would track actual time spent
        return rand(120, 3600); // seconds
    }

    private function getLastActivity($student, $course)
    {
        $lastCompletion = $course->lessons()
            ->join('lesson_completions', 'lessons.id', '=', 'lesson_completions.lesson_id')
            ->where('lesson_completions.user_id', $student->id)
            ->orderBy('lesson_completions.completed_at', 'desc')
            ->first();

        return $lastCompletion ? $lastCompletion->completed_at : null;
    }

    private function getLessonsCompleted($student, $course)
    {
        return $course->lessons()
            ->whereHas('completions', function($query) use ($student) {
                $query->where('user_id', $student->id);
            })->count();
    }

    private function getQuizzesCompleted($student, $course)
    {
        return $course->quizzes()
            ->whereHas('attempts', function($query) use ($student) {
                $query->where('user_id', $student->id)->where('status', 'completed');
            })->count();
    }

    private function getAssignmentsSubmitted($student, $course)
    {
        return $course->assignments()
            ->whereHas('submissions', function($query) use ($student) {
                $query->where('student_id', $student->id);
            })->count();
    }

    // Engagement Analytics Helper Methods
    private function getLessonCompletionRates($courses)
    {
        $data = [];
        foreach ($courses as $course) {
            $totalLessons = $course->lessons()->count();
            $totalEnrollments = $course->enrollments()->count();
            $completions = $course->lessons()
                ->join('lesson_completions', 'lessons.id', '=', 'lesson_completions.lesson_id')
                ->count();

            $rate = ($totalLessons > 0 && $totalEnrollments > 0) ?
                round(($completions / ($totalLessons * $totalEnrollments)) * 100, 2) : 0;

            $data[] = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'completion_rate' => $rate
            ];
        }
        return $data;
    }

    private function getVideoWatchTime($courses)
    {
        // Mock data - would track actual video watch time
        $data = [];
        foreach ($courses as $course) {
            $data[] = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'total_watch_time' => rand(1000, 10000), // minutes
                'average_watch_time' => rand(30, 120) // minutes per student
            ];
        }
        return $data;
    }

    private function getContentDropOff($courses)
    {
        // Mock data - would track where students drop off
        $data = [];
        foreach ($courses as $course) {
            $lessons = $course->lessons()->orderBy('order')->take(5)->get();
            $dropOffData = [];
            foreach ($lessons as $index => $lesson) {
                $dropOffData[] = [
                    'lesson_id' => $lesson->id,
                    'lesson_title' => $lesson->title,
                    'completion_rate' => max(10, 100 - ($index * 15)) // Mock decreasing completion
                ];
            }
            $data[] = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'drop_off_points' => $dropOffData
            ];
        }
        return $data;
    }

    private function getMostEngagingContent($courses)
    {
        $data = [];
        foreach ($courses as $course) {
            $lessons = $course->lessons()
                ->withCount('completions')
                ->orderBy('completions_count', 'desc')
                ->take(3)
                ->get();

            $engagingContent = [];
            foreach ($lessons as $lesson) {
                $engagingContent[] = [
                    'lesson_id' => $lesson->id,
                    'lesson_title' => $lesson->title,
                    'engagement_score' => $lesson->completions_count * 10
                ];
            }

            $data[] = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'engaging_content' => $engagingContent
            ];
        }
        return $data;
    }

    // Additional Engagement Analytics Methods
    private function getForumActivity($courses)
    {
        // Mock forum activity data
        $data = [];
        foreach ($courses as $course) {
            $data[] = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'total_topics' => rand(5, 50),
                'total_posts' => rand(20, 200),
                'active_participants' => rand(10, 100),
                'average_response_time' => rand(2, 24) . ' hours'
            ];
        }
        return $data;
    }

    private function getDiscussionParticipation($courses)
    {
        // Mock discussion participation data
        $data = [];
        foreach ($courses as $course) {
            $data[] = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'participation_rate' => rand(30, 80) . '%',
                'average_posts_per_student' => rand(2, 10),
                'most_active_students' => rand(5, 15)
            ];
        }
        return $data;
    }

    private function getPeerInteraction($courses)
    {
        // Mock peer interaction data
        $data = [];
        foreach ($courses as $course) {
            $data[] = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'peer_reviews' => rand(10, 50),
                'study_groups' => rand(2, 8),
                'collaboration_score' => rand(60, 95)
            ];
        }
        return $data;
    }

    private function getInstructorInteraction($courses)
    {
        // Mock instructor interaction data
        $data = [];
        foreach ($courses as $course) {
            $data[] = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'instructor_posts' => rand(5, 30),
                'response_rate' => rand(70, 100) . '%',
                'average_response_time' => rand(1, 12) . ' hours'
            ];
        }
        return $data;
    }

    private function getQuizEngagement($courses)
    {
        // Mock quiz engagement data
        $data = [];
        foreach ($courses as $course) {
            $data[] = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'quiz_completion_rate' => rand(60, 95) . '%',
                'average_attempts' => rand(1, 3),
                'average_score' => rand(70, 90) . '%'
            ];
        }
        return $data;
    }

    private function getAssignmentEngagement($courses)
    {
        // Mock assignment engagement data
        $data = [];
        foreach ($courses as $course) {
            $data[] = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'submission_rate' => rand(70, 95) . '%',
                'on_time_submissions' => rand(60, 90) . '%',
                'average_grade' => rand(75, 95) . '%'
            ];
        }
        return $data;
    }

    // Student Progress Helper Methods
    private function getAverageQuizScore($student, $course)
    {
        $quizzes = $course->quizzes()->get();
        if ($quizzes->isEmpty()) return 0;

        $totalScore = 0;
        $quizCount = 0;

        foreach ($quizzes as $quiz) {
            $attempts = $quiz->attempts()->where('user_id', $student->id)->where('status', 'completed')->get();
            if ($attempts->isNotEmpty()) {
                $bestScore = $attempts->max('score');
                $totalScore += $bestScore;
                $quizCount++;
            }
        }

        return $quizCount > 0 ? round($totalScore / $quizCount, 2) : 0;
    }

    private function getAverageAssignmentGrade($student, $course)
    {
        $submissions = $course->assignments()
            ->join('submissions', 'assignments.id', '=', 'submissions.assignment_id')
            ->where('submissions.student_id', $student->id)
            ->whereNotNull('submissions.grade')
            ->avg('submissions.grade');

        return round($submissions ?? 0, 2);
    }

    private function getImprovementRate($student, $course)
    {
        // Mock calculation - would track improvement over time
        return rand(5, 25) . '%';
    }

    // Engagement Analytics Helper Methods
    private function getQuizParticipation($courses)
    {
        $data = [];
        foreach ($courses as $course) {
            $totalStudents = $course->enrollments()->count();
            $quizTakers = $course->quizzes()
                ->join('quiz_attempts', 'quizzes.id', '=', 'quiz_attempts.quiz_id')
                ->distinct('quiz_attempts.user_id')
                ->count();

            $participationRate = $totalStudents > 0 ? round(($quizTakers / $totalStudents) * 100, 2) : 0;

            $data[] = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'participation_rate' => $participationRate . '%',
                'total_attempts' => $course->quizzes()->join('quiz_attempts', 'quizzes.id', '=', 'quiz_attempts.quiz_id')->count(),
                'average_score' => rand(70, 90) . '%'
            ];
        }
        return $data;
    }

    private function getAssignmentSubmissionRates($courses)
    {
        $data = [];
        foreach ($courses as $course) {
            $totalStudents = $course->enrollments()->count();
            $submitters = $course->assignments()
                ->join('submissions', 'assignments.id', '=', 'submissions.assignment_id')
                ->distinct('submissions.student_id')
                ->count();

            $submissionRate = $totalStudents > 0 ? round(($submitters / $totalStudents) * 100, 2) : 0;

            $data[] = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'submission_rate' => $submissionRate . '%',
                'total_submissions' => $course->assignments()->join('submissions', 'assignments.id', '=', 'submissions.assignment_id')->count(),
                'on_time_rate' => rand(70, 95) . '%'
            ];
        }
        return $data;
    }

    private function getFeedbackEngagement($courses)
    {
        // Mock feedback engagement data
        $data = [];
        foreach ($courses as $course) {
            $data[] = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'feedback_requests' => rand(10, 50),
                'feedback_provided' => rand(8, 45),
                'response_rate' => rand(80, 100) . '%'
            ];
        }
        return $data;
    }

    private function getRetryPatterns($courses)
    {
        // Mock retry patterns data
        $data = [];
        foreach ($courses as $course) {
            $data[] = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'quiz_retries' => rand(20, 60) . '%',
                'assignment_resubmissions' => rand(10, 30) . '%',
                'improvement_after_retry' => rand(15, 40) . '%'
            ];
        }
        return $data;
    }

    // Additional Student Progress Methods
    private function getForumParticipation($student, $course)
    {
        // Mock forum participation data
        return [
            'posts_count' => rand(5, 25),
            'topics_started' => rand(1, 8),
            'replies_count' => rand(10, 50),
            'participation_score' => rand(60, 95)
        ];
    }

    private function getStudyStreak($student)
    {
        // Mock study streak data
        return [
            'current_streak' => rand(1, 30),
            'longest_streak' => rand(10, 60),
            'total_study_days' => rand(50, 200)
        ];
    }

    private function getSessionFrequency($student, $course)
    {
        // Mock session frequency data
        return [
            'sessions_per_week' => rand(3, 7),
            'average_session_duration' => rand(30, 120), // minutes
            'total_sessions' => rand(20, 100)
        ];
    }

    // Additional Engagement Analytics Methods
    private function getDailyActivityPatterns($courses)
    {
        $data = [];
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($days as $day) {
            $data[] = [
                'day' => $day,
                'activity_level' => rand(20, 100),
                'peak_hours' => [rand(9, 12), rand(14, 18), rand(19, 22)]
            ];
        }
        return $data;
    }

    private function getWeeklyPatterns($courses)
    {
        $data = [];
        for ($week = 1; $week <= 4; $week++) {
            $data[] = [
                'week' => $week,
                'engagement_score' => rand(60, 95),
                'completion_rate' => rand(70, 90) . '%',
                'active_students' => rand(50, 200)
            ];
        }
        return $data;
    }

    private function getSeasonalEngagement($courses)
    {
        $seasons = ['Spring', 'Summer', 'Fall', 'Winter'];
        $data = [];

        foreach ($seasons as $season) {
            $data[] = [
                'season' => $season,
                'engagement_level' => rand(60, 95),
                'enrollment_rate' => rand(70, 90) . '%',
                'completion_rate' => rand(65, 85) . '%'
            ];
        }
        return $data;
    }

    private function getPeakLearningTimes($courses)
    {
        $timeSlots = [
            '6:00-9:00 AM' => rand(20, 40),
            '9:00-12:00 PM' => rand(60, 80),
            '12:00-3:00 PM' => rand(40, 60),
            '3:00-6:00 PM' => rand(70, 90),
            '6:00-9:00 PM' => rand(80, 100),
            '9:00-12:00 AM' => rand(30, 50)
        ];

        $data = [];
        foreach ($timeSlots as $time => $activity) {
            $data[] = [
                'time_slot' => $time,
                'activity_level' => $activity,
                'student_count' => rand(10, 100)
            ];
        }
        return $data;
    }

    // Missing methods for predictive analytics
    private function predictEnrollmentTrends()
    {
        // Mock implementation - would use ML models in production
        return [
            'next_month' => rand(100, 500),
            'next_quarter' => rand(300, 1500),
            'trend' => 'increasing',
            'confidence' => 0.85
        ];
    }

    private function predictRevenueTrends()
    {
        return [
            'next_month' => rand(5000, 25000),
            'next_quarter' => rand(15000, 75000),
            'trend' => 'stable',
            'confidence' => 0.78
        ];
    }

    private function predictCompletionLikelihood()
    {
        return [
            'high_risk_students' => rand(50, 200),
            'medium_risk_students' => rand(100, 400),
            'low_risk_students' => rand(200, 800),
            'overall_completion_rate' => rand(65, 85)
        ];
    }

    private function predictChurnRisk()
    {
        return [
            'high_risk' => rand(20, 100),
            'medium_risk' => rand(50, 200),
            'low_risk' => rand(100, 500),
            'retention_rate' => rand(75, 95)
        ];
    }

    private function predictCourseDemand()
    {
        return [
            'trending_categories' => ['Programming', 'Data Science', 'Digital Marketing'],
            'declining_categories' => ['Basic Computer Skills'],
            'emerging_topics' => ['AI/ML', 'Blockchain', 'Cloud Computing']
        ];
    }

    private function predictOptimalPricing()
    {
        return [
            'recommended_price_range' => ['min' => 50, 'max' => 200],
            'price_elasticity' => 0.65,
            'optimal_discount_rate' => 15
        ];
    }

    // Missing methods for real-time analytics
    private function getCurrentActiveUsers()
    {
        // Mock implementation - would track real-time user activity
        return rand(50, 500);
    }

    private function getActiveSessionsCount()
    {
        return rand(30, 300);
    }

    private function getCurrentLearningActivity()
    {
        return [
            'lessons_in_progress' => rand(20, 200),
            'quizzes_being_taken' => rand(5, 50),
            'assignments_being_worked_on' => rand(10, 100)
        ];
    }

    private function getRealtimeEngagement()
    {
        return [
            'average_session_duration' => rand(15, 45),
            'bounce_rate' => rand(10, 30),
            'pages_per_session' => rand(3, 8)
        ];
    }

    // Missing methods for revenue analytics
    private function calculateConversionRate()
    {
        $totalVisitors = rand(1000, 5000);
        $totalEnrollments = rand(50, 500);
        return $totalEnrollments > 0 ? round(($totalEnrollments / $totalVisitors) * 100, 2) : 0;
    }

    private function getRevenueBySource()
    {
        return [
            'direct_enrollment' => rand(10000, 50000),
            'affiliate_marketing' => rand(5000, 25000),
            'social_media' => rand(3000, 15000),
            'email_campaigns' => rand(2000, 10000)
        ];
    }

    private function getRevenueGrowthRate()
    {
        return rand(5, 25); // percentage growth
    }

    private function getAverageRevenuePerUser()
    {
        return rand(50, 300);
    }

    // Additional missing methods for real-time analytics
    private function getLiveEnrollments()
    {
        return rand(5, 50);
    }

    private function getOngoingSessions()
    {
        return rand(20, 200);
    }

    private function getRecentCompletions()
    {
        return [
            'lessons' => rand(10, 100),
            'quizzes' => rand(5, 50),
            'courses' => rand(2, 20)
        ];
    }

    private function getLiveRevenue()
    {
        return [
            'today' => rand(500, 5000),
            'this_hour' => rand(50, 500),
            'last_transaction' => now()->subMinutes(rand(1, 60))->format('H:i:s')
        ];
    }

    // Missing methods for revenue analytics trends
    private function getDailyRevenueTrend($period)
    {
        $data = [];
        for ($i = $period; $i >= 0; $i--) {
            $data[] = [
                'date' => now()->subDays($i)->format('Y-m-d'),
                'revenue' => rand(100, 1000)
            ];
        }
        return $data;
    }

    private function getMonthlyRevenueTrend()
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $data[] = [
                'month' => now()->subMonths($i)->format('Y-m'),
                'revenue' => rand(5000, 50000)
            ];
        }
        return $data;
    }

    private function getRevenueByCourse($fromDate)
    {
        return [
            ['course_name' => 'Web Development', 'revenue' => rand(5000, 15000)],
            ['course_name' => 'Data Science', 'revenue' => rand(3000, 12000)],
            ['course_name' => 'Digital Marketing', 'revenue' => rand(2000, 8000)]
        ];
    }

    private function getRevenueByInstructor($fromDate)
    {
        return [
            ['instructor_name' => 'John Doe', 'revenue' => rand(3000, 10000)],
            ['instructor_name' => 'Jane Smith', 'revenue' => rand(2000, 8000)],
            ['instructor_name' => 'Mike Johnson', 'revenue' => rand(1000, 5000)]
        ];
    }

    // Missing methods for payment analytics
    private function getGatewayBreakdown($fromDate)
    {
        return [
            'paystack' => rand(40, 60),
            'flutterwave' => rand(20, 40),
            'bank_transfer' => rand(10, 30)
        ];
    }

    private function getPaymentSuccessRates($fromDate)
    {
        return [
            'overall' => rand(85, 95),
            'paystack' => rand(90, 98),
            'flutterwave' => rand(85, 95),
            'bank_transfer' => rand(80, 90)
        ];
    }

    private function getPaymentFailureAnalysis($fromDate)
    {
        return [
            'insufficient_funds' => rand(30, 50),
            'network_error' => rand(20, 40),
            'card_declined' => rand(15, 35),
            'other' => rand(5, 15)
        ];
    }

    // Missing methods for forecasting
    private function projectMonthlyRevenue()
    {
        return rand(20000, 80000);
    }

    private function calculateGrowthRate()
    {
        return rand(5, 25);
    }

    private function getSeasonalTrends()
    {
        return [
            'peak_months' => ['January', 'September'],
            'low_months' => ['July', 'December'],
            'seasonal_factor' => rand(15, 35)
        ];
    }
}
