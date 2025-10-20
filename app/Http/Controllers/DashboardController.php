<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Quiz;
use App\Models\Answer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    // Note: Middleware is applied at route level in Laravel 12
    // See routes/api.php for middleware configuration

    /**
     * Get student dashboard
     */
    public function studentDashboard()
    {
        $user = Auth::user();

        // Ensure user is a student
        if (!$user->hasRole('student')) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Student role required.'
            ], 403);
        }

        $dashboard = [
            'user' => [
                'id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'wallet_balance' => $user->getOrCreateWallet()->balance
            ],
            'overview' => $this->getStudentOverview($user),
            'current_courses' => $this->getCurrentCourses($user),
            'recent_activity' => $this->getRecentActivity($user),
            'upcoming_deadlines' => $this->getUpcomingDeadlines($user),
            'achievements' => $this->getRecentAchievements($user),
            'learning_streak' => $this->getLearningStreak($user),
            'recommended_courses' => $this->getRecommendedCourses($user),
            'progress_summary' => $this->getProgressSummary($user)
        ];

        return response()->json([
            'success' => true,
            'data' => $dashboard
        ]);
    }

    /**
     * Get instructor dashboard
     */
    public function instructorDashboard()
    {
        $user = Auth::user();

        // Ensure user is an instructor
        if (!$user->hasRole('instructor') && !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Instructor role required.'
            ], 403);
        }

        $dashboard = [
            'user' => [
                'id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'avatar' => $user->avatar
            ],
            'overview' => $this->getInstructorOverview($user),
            'courses' => $this->getInstructorCourses($user),
            'recent_enrollments' => $this->getRecentEnrollments($user),
            'pending_tasks' => $this->getPendingTasks($user),
            'revenue_summary' => $this->getRevenueSummary($user),
            'student_progress' => $this->getStudentProgress($user),
            'course_analytics' => $this->getCourseAnalytics($user),
            'notifications' => $this->getInstructorNotifications($user)
        ];

        return response()->json([
            'success' => true,
            'data' => $dashboard
        ]);
    }

    /**
     * Get admin dashboard
     */
    public function adminDashboard()
    {
        $user = Auth::user();

        // Ensure user is an admin
        if (!$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Admin role required.'
            ], 403);
        }

        $dashboard = [
            'user' => [
                'id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'avatar' => $user->avatar
            ],
            'overview' => $this->getAdminOverview(),
            'system_stats' => $this->getSystemStats(),
            'recent_activities' => $this->getSystemActivities(),
            'user_analytics' => $this->getUserAnalytics(),
            'course_analytics' => $this->getSystemCourseAnalytics(),
            'revenue_analytics' => $this->getSystemRevenueAnalytics(),
            'pending_approvals' => $this->getPendingApprovals(),
            'system_health' => $this->getSystemHealth()
        ];

        return response()->json([
            'success' => true,
            'data' => $dashboard
        ]);
    }

    /**
     * Get dashboard analytics
     */
    public function analytics(Request $request)
    {
        $user = Auth::user();
        $timeframe = $request->get('timeframe', '30'); // days

        $analytics = [
            'timeframe' => $timeframe . ' days',
            'period_start' => now()->subDays($timeframe),
            'period_end' => now()
        ];

        if ($user->hasRole('student')) {
            $analytics['student_analytics'] = $this->getStudentAnalytics($user, $timeframe);
        } elseif ($user->hasRole('instructor')) {
            $analytics['instructor_analytics'] = $this->getInstructorAnalytics($user, $timeframe);
        } elseif ($user->hasRole('admin')) {
            $analytics['admin_analytics'] = $this->getAdminAnalytics($timeframe);
        }

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Get student overview statistics
     */
    private function getStudentOverview($user)
    {
        $enrollments = $user->enrollments();
        
        return [
            'total_courses' => $enrollments->count(),
            'active_courses' => $enrollments->where('status', 'active')->count(),
            'completed_courses' => $enrollments->where('status', 'completed')->count(),
            'certificates_earned' => $user->certificates()->count(),
            'total_study_time' => LessonCompletion::where('user_id', $user->id)->sum('time_spent'),
            'lessons_completed' => LessonCompletion::where('user_id', $user->id)->count(),
            'quizzes_taken' => Answer::where('student_id', $user->id)
                                     ->join('questions', 'answers.question_id', '=', 'questions.id')
                                     ->distinct('questions.quiz_id')
                                     ->count('questions.quiz_id'),
            'assignments_submitted' => AssignmentSubmission::where('user_id', $user->id)->count(),
            'current_streak' => 7, // Mock value - would calculate from login logs
            'wallet_balance' => $user->getOrCreateWallet()->balance
        ];
    }

    /**
     * Get current courses for student
     */
    private function getCurrentCourses($user)
    {
        return $user->enrollments()
                   ->with(['course.category', 'course.instructor'])
                   ->where('status', 'active')
                   ->orderBy('updated_at', 'desc')
                   ->limit(6)
                   ->get()
                   ->map(function ($enrollment) use ($user) {
                       $course = $enrollment->course;

                       // Skip enrollments with null courses
                       if (!$course) {
                           return null;
                       }

                       $totalLessons = $course->lessons()->count();
                       $completedLessons = LessonCompletion::where('user_id', $user->id)
                                                         ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                                                         ->count();

                       return [
                           'enrollment_id' => $enrollment->id,
                           'course' => $course,
                           'progress' => $enrollment->progress,
                           'lessons_completed' => $completedLessons,
                           'total_lessons' => $totalLessons,
                           'next_lesson' => $course->lessons()
                                                 ->whereNotIn('id', LessonCompletion::where('user_id', $user->id)->pluck('lesson_id'))
                                                 ->orderBy('order')
                                                 ->first(),
                           'last_accessed' => $enrollment->updated_at
                       ];
                   })
                   ->filter(); // Remove null values
    }

    /**
     * Get recent activity for student
     */
    private function getRecentActivity($user)
    {
        $activities = [];

        // Recent lesson completions
        $recentCompletions = LessonCompletion::with(['lesson.course'])
                                           ->where('user_id', $user->id)
                                           ->orderBy('completed_at', 'desc')
                                           ->limit(5)
                                           ->get();

        foreach ($recentCompletions as $completion) {
            $activities[] = [
                'type' => 'lesson_completion',
                'title' => 'Completed Lesson',
                'description' => $completion->lesson->title . ' in ' . $completion->lesson->course->title,
                'timestamp' => $completion->completed_at,
                'course_id' => $completion->lesson->course_id
            ];
        }

        // Recent quiz attempts
        $recentQuizzes = Answer::with(['question.quiz.lesson.course'])
                             ->where('student_id', $user->id)
                             ->orderBy('created_at', 'desc')
                             ->limit(3)
                             ->get()
                             ->groupBy('question.quiz_id')
                             ->take(3);

        foreach ($recentQuizzes as $quizId => $answers) {
            $quiz = $answers->first()->question->quiz;
            $activities[] = [
                'type' => 'quiz_attempt',
                'title' => 'Completed Quiz',
                'description' => $quiz->title . ' in ' . $quiz->lesson->course->title,
                'timestamp' => $answers->first()->created_at,
                'course_id' => $quiz->lesson->course_id
            ];
        }

        // Sort by timestamp and return latest 8
        usort($activities, function($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        return array_slice($activities, 0, 8);
    }

    /**
     * Get upcoming deadlines for student
     */
    private function getUpcomingDeadlines($user)
    {
        $enrolledCourseIds = $user->enrollments()->pluck('course_id');
        
        return Assignment::whereIn('course_id', $enrolledCourseIds)
                        ->where('due_date', '>', now())
                        ->whereDoesntHave('submissions', function($query) use ($user) {
                            $query->where('student_id', $user->id);
                        })
                        ->with(['course'])
                        ->orderBy('due_date', 'asc')
                        ->limit(5)
                        ->get()
                        ->map(function ($assignment) {
                            return [
                                'id' => $assignment->id,
                                'title' => $assignment->title,
                                'course' => $assignment->course ? $assignment->course->title : 'Unknown Course',
                                'due_date' => $assignment->due_date,
                                'days_remaining' => now()->diffInDays($assignment->due_date),
                                'max_points' => $assignment->max_score ?? 0
                            ];
                        });
    }

    /**
     * Get recent achievements for student
     */
    private function getRecentAchievements($user)
    {
        $achievements = [];

        // Recent certificates
        $recentCertificates = $user->certificates()
                                  ->with('course')
                                  ->orderBy('created_at', 'desc')
                                  ->limit(3)
                                  ->get();

        foreach ($recentCertificates as $certificate) {
            if ($certificate->course) {
                $achievements[] = [
                    'type' => 'certificate',
                    'title' => 'Course Completed',
                    'description' => 'Earned certificate for ' . $certificate->course->title,
                    'timestamp' => $certificate->created_at,
                    'icon' => 'certificate'
                ];
            }
        }

        // Recent high quiz scores
        $recentQuizzes = Answer::where('student_id', $user->id)
                             ->where('created_at', '>=', now()->subDays(7))
                             ->get()
                             ->groupBy(['question.quiz_id', 'attempt_number'])
                             ->map(function ($attemptAnswers) {
                                 $totalScore = $attemptAnswers->sum('points_earned');
                                 $maxScore = $attemptAnswers->sum('points_possible');
                                 $percentage = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;
                                 
                                 return [
                                     'quiz' => $attemptAnswers->first()->question->quiz,
                                     'percentage' => $percentage,
                                     'timestamp' => $attemptAnswers->first()->created_at
                                 ];
                             })
                             ->filter(function ($result) {
                                 return $result['percentage'] >= 90; // High scores only
                             })
                             ->sortByDesc('timestamp')
                             ->take(2);

        foreach ($recentQuizzes as $result) {
            $achievements[] = [
                'type' => 'high_score',
                'title' => 'Excellent Quiz Score',
                'description' => 'Scored ' . round($result['percentage']) . '% on ' . $result['quiz']->title,
                'timestamp' => $result['timestamp'],
                'icon' => 'trophy'
            ];
        }

        // Sort by timestamp and return latest 5
        usort($achievements, function($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        return array_slice($achievements, 0, 5);
    }

    /**
     * Get learning streak for student
     */
    private function getLearningStreak($user)
    {
        // This would typically calculate from lesson completion logs
        return [
            'current_streak' => 7,
            'longest_streak' => 21,
            'streak_goal' => 30,
            'days_this_week' => 5,
            'weekly_goal' => 7
        ];
    }

    /**
     * Get recommended courses for student
     */
    private function getRecommendedCourses($user)
    {
        $enrolledCategories = $user->enrollments()
                                  ->with('course.category')
                                  ->get()
                                  ->pluck('course.category.id')
                                  ->unique();

        return Course::with(['category', 'instructor'])
                    ->whereIn('category_id', $enrolledCategories)
                    ->where('status', 'published')
                    ->whereNotIn('id', $user->enrollments()->pluck('course_id'))
                    ->withCount('enrollments')
                    ->orderBy('enrollments_count', 'desc')
                    ->limit(4)
                    ->get();
    }

    /**
     * Get progress summary for student
     */
    private function getProgressSummary($user)
    {
        $enrollments = $user->enrollments()->with('course')->get();

        return [
            'overall_progress' => $enrollments->avg('progress'),
            'courses_by_progress' => [
                'not_started' => $enrollments->where('progress', 0)->count(),
                'in_progress' => $enrollments->where('progress', '>', 0)->where('progress', '<', 100)->count(),
                'completed' => $enrollments->where('progress', 100)->count()
            ],
            'monthly_progress' => $this->getMonthlyProgress($user),
            'category_progress' => $this->getCategoryProgress($user)
        ];
    }

    /**
     * Get instructor overview statistics
     */
    private function getInstructorOverview($user)
    {
        $courses = Course::where('instructor_id', $user->id);
        $enrollments = Enrollment::whereIn('course_id', $courses->pluck('id'));

        return [
            'total_courses' => $courses->count(),
            'published_courses' => $courses->where('status', 'published')->count(),
            'draft_courses' => $courses->where('status', 'draft')->count(),
            'total_students' => $enrollments->distinct('user_id')->count(),
            'total_enrollments' => $enrollments->count(),
            'completed_enrollments' => $enrollments->where('status', 'completed')->count(),
            'total_revenue' => $enrollments->sum('amount_paid'),
            'pending_assignments' => $this->getPendingAssignmentGrading($user),
            'course_ratings' => $this->getAverageCourseRating($user),
            'this_month_enrollments' => $enrollments->where('enrolled_at', '>=', now()->startOfMonth())->count()
        ];
    }

    /**
     * Get instructor courses
     */
    private function getInstructorCourses($user)
    {
        return Course::where('instructor_id', $user->id)
                    ->withCount(['enrollments', 'lessons'])
                    ->with('category')
                    ->orderBy('updated_at', 'desc')
                    ->limit(6)
                    ->get()
                    ->map(function ($course) {
                        return [
                            'id' => $course->id,
                            'title' => $course->title,
                            'status' => $course->status,
                            'enrollments_count' => $course->enrollments_count,
                            'lessons_count' => $course->lessons_count,
                            'category' => $course->category,
                            'revenue' => $course->enrollments()->sum('amount_paid'),
                            'average_progress' => $course->enrollments()->avg('progress'),
                            'completion_rate' => $course->enrollments_count > 0
                                ? ($course->enrollments()->where('status', 'completed')->count() / $course->enrollments_count) * 100
                                : 0
                        ];
                    });
    }

    /**
     * Get recent enrollments for instructor
     */
    private function getRecentEnrollments($user)
    {
        $courseIds = Course::where('instructor_id', $user->id)->pluck('id');

        return Enrollment::with(['user', 'course'])
                        ->whereIn('course_id', $courseIds)
                        ->orderBy('enrolled_at', 'desc')
                        ->limit(10)
                        ->get()
                        ->map(function ($enrollment) {
                            return [
                                'student_name' => $enrollment->user->first_name . ' ' . $enrollment->user->last_name,
                                'student_email' => $enrollment->user->email,
                                'course_title' => $enrollment->course->title,
                                'enrolled_at' => $enrollment->enrolled_at,
                                'progress' => $enrollment->progress,
                                'amount_paid' => $enrollment->amount_paid
                            ];
                        });
    }

    /**
     * Get pending tasks for instructor
     */
    private function getPendingTasks($user)
    {
        $courseIds = Course::where('instructor_id', $user->id)->pluck('id');

        $pendingAssignments = AssignmentSubmission::whereIn('assignment_id',
                                Assignment::whereIn('course_id', $courseIds)->pluck('id'))
                            ->whereNull('grade')
                            ->count();

        return [
            'pending_assignment_grading' => $pendingAssignments,
            'draft_courses' => Course::where('instructor_id', $user->id)->where('status', 'draft')->count(),
            'course_reviews_to_respond' => 0, // Would implement when ReviewController is created
            'student_questions' => 0 // Would implement when ForumController is created
        ];
    }

    /**
     * Get revenue summary for instructor
     */
    private function getRevenueSummary($user)
    {
        $courseIds = Course::where('instructor_id', $user->id)->pluck('id');
        $enrollments = Enrollment::whereIn('course_id', $courseIds);

        return [
            'total_revenue' => $enrollments->sum('amount_paid'),
            'this_month_revenue' => $enrollments->where('enrolled_at', '>=', now()->startOfMonth())->sum('amount_paid'),
            'last_month_revenue' => $enrollments->whereBetween('enrolled_at', [
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth()
            ])->sum('amount_paid'),
            'average_course_price' => Course::where('instructor_id', $user->id)->avg('price'),
            'top_earning_course' => Course::where('instructor_id', $user->id)
                                         ->withSum('enrollments', 'amount_paid')
                                         ->orderBy('enrollments_sum_amount_paid', 'desc')
                                         ->first(),
            'monthly_revenue_trend' => $this->getMonthlyRevenueTrend($user)
        ];
    }

    /**
     * Get student progress for instructor
     */
    private function getStudentProgress($user)
    {
        $courseIds = Course::where('instructor_id', $user->id)->pluck('id');
        $enrollments = Enrollment::whereIn('course_id', $courseIds)->with(['user', 'course']);

        return [
            'struggling_students' => $enrollments->where('progress', '<', 25)
                                               ->where('enrolled_at', '<', now()->subWeeks(2))
                                               ->with(['user', 'course'])
                                               ->limit(5)
                                               ->get(),
            'top_performers' => $enrollments->where('progress', '>', 80)
                                          ->orderBy('progress', 'desc')
                                          ->with(['user', 'course'])
                                          ->limit(5)
                                          ->get(),
            'completion_rates' => Course::where('instructor_id', $user->id)
                                       ->withCount(['enrollments', 'enrollments as completed_count' => function($query) {
                                           $query->where('status', 'completed');
                                       }])
                                       ->get()
                                       ->map(function($course) {
                                           return [
                                               'course_title' => $course->title,
                                               'completion_rate' => $course->enrollments_count > 0
                                                   ? ($course->completed_count / $course->enrollments_count) * 100
                                                   : 0
                                           ];
                                       })
        ];
    }

    /**
     * Get course analytics for instructor
     */
    private function getCourseAnalytics($user)
    {
        $courses = Course::where('instructor_id', $user->id)->withCount('enrollments')->get();

        return [
            'most_popular_course' => $courses->sortByDesc('enrollments_count')->first(),
            'engagement_metrics' => $this->getCourseEngagementMetrics($user),
            'completion_trends' => $this->getCompletionTrends($user),
            'student_feedback_summary' => $this->getStudentFeedbackSummary($user)
        ];
    }

    /**
     * Get instructor notifications
     */
    private function getInstructorNotifications($user)
    {
        // This would typically come from a notifications system
        return [
            'unread_count' => 3,
            'recent_notifications' => [
                [
                    'type' => 'new_enrollment',
                    'message' => 'New student enrolled in Laravel Fundamentals',
                    'timestamp' => now()->subHours(2)
                ],
                [
                    'type' => 'assignment_submitted',
                    'message' => '5 new assignment submissions to grade',
                    'timestamp' => now()->subHours(4)
                ],
                [
                    'type' => 'course_review',
                    'message' => 'New 5-star review on your React course',
                    'timestamp' => now()->subHours(6)
                ]
            ]
        ];
    }

    /**
     * Get admin overview statistics
     */
    private function getAdminOverview()
    {
        return [
            'total_users' => User::count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_instructors' => User::where('role', 'instructor')->count(),
            'total_courses' => Course::count(),
            'published_courses' => Course::where('status', 'published')->count(),
            'total_enrollments' => Enrollment::count(),
            'total_revenue' => Enrollment::sum('amount_paid'),
            'active_users_today' => User::where('last_login_at', '>=', now()->startOfDay())->count(),
            'new_users_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'courses_pending_approval' => Course::where('status', 'pending')->count()
        ];
    }

    /**
     * Get system statistics
     */
    private function getSystemStats()
    {
        return [
            'database_stats' => [
                'total_lessons' => \App\Models\Lesson::count(),
                'total_quizzes' => Quiz::count(),
                'total_assignments' => Assignment::count(),
                'total_certificates' => \App\Models\Certificate::count()
            ],
            'engagement_stats' => [
                'lessons_completed_today' => LessonCompletion::whereDate('completed_at', today())->count(),
                'quizzes_taken_today' => Answer::whereDate('created_at', today())->distinct('student_id')->count(),
                'assignments_submitted_today' => AssignmentSubmission::whereDate('submitted_at', today())->count()
            ],
            'growth_metrics' => [
                'user_growth_rate' => $this->calculateGrowthRate('users'),
                'course_growth_rate' => $this->calculateGrowthRate('courses'),
                'enrollment_growth_rate' => $this->calculateGrowthRate('enrollments')
            ]
        ];
    }

    /**
     * Helper methods for calculations
     */
    private function getMonthlyProgress($user)
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'lessons_completed' => LessonCompletion::where('user_id', $user->id)
                                                     ->whereYear('completed_at', $date->year)
                                                     ->whereMonth('completed_at', $date->month)
                                                     ->count()
            ];
        }
        return $months;
    }

    private function getCategoryProgress($user)
    {
        return $user->enrollments()
                   ->with('course.category')
                   ->get()
                   ->groupBy('course.category.title')
                   ->map(function ($enrollments, $category) {
                       return [
                           'category' => $category,
                           'total_courses' => $enrollments->count(),
                           'completed_courses' => $enrollments->where('status', 'completed')->count(),
                           'average_progress' => $enrollments->avg('progress')
                       ];
                   })
                   ->values();
    }

    private function getPendingAssignmentGrading($user)
    {
        $courseIds = Course::where('instructor_id', $user->id)->pluck('id');
        return AssignmentSubmission::whereIn('assignment_id',
                Assignment::whereIn('course_id', $courseIds)->pluck('id'))
            ->whereNull('grade')
            ->count();
    }

    private function getAverageCourseRating($user)
    {
        // This would be implemented when ReviewController is created
        return 4.5; // Mock value
    }

    private function getMonthlyRevenueTrend($user)
    {
        $courseIds = Course::where('instructor_id', $user->id)->pluck('id');
        $months = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'revenue' => Enrollment::whereIn('course_id', $courseIds)
                                     ->whereYear('enrolled_at', $date->year)
                                     ->whereMonth('enrolled_at', $date->month)
                                     ->sum('amount_paid')
            ];
        }
        return $months;
    }

    private function getCourseEngagementMetrics($user)
    {
        // Mock implementation - would calculate real engagement metrics
        return [
            'average_completion_rate' => 75.5,
            'average_time_to_complete' => 45, // days
            'student_satisfaction' => 4.3
        ];
    }

    private function getCompletionTrends($user)
    {
        // Mock implementation - would show completion trends over time
        return [
            'this_month' => 85,
            'last_month' => 78,
            'trend' => 'increasing'
        ];
    }

    private function getStudentFeedbackSummary($user)
    {
        // Mock implementation - would aggregate student feedback
        return [
            'average_rating' => 4.5,
            'total_reviews' => 127,
            'positive_feedback_percentage' => 92
        ];
    }

    private function calculateGrowthRate($type)
    {
        // Mock implementation - would calculate actual growth rates
        return [
            'users' => 15.2,
            'courses' => 8.7,
            'enrollments' => 23.1
        ][$type] ?? 0;
    }

    private function getSystemActivities()
    {
        // Mock implementation - would show recent system activities
        return [
            [
                'type' => 'user_registration',
                'description' => '15 new users registered today',
                'timestamp' => now()->subHours(1)
            ],
            [
                'type' => 'course_published',
                'description' => 'Advanced React course published',
                'timestamp' => now()->subHours(3)
            ],
            [
                'type' => 'payment_processed',
                'description' => '₦50,000 in payments processed',
                'timestamp' => now()->subHours(5)
            ]
        ];
    }

    private function getUserAnalytics()
    {
        return [
            'user_distribution' => [
                'students' => User::where('role', 'student')->count(),
                'instructors' => User::where('role', 'instructor')->count(),
                'admins' => User::where('role', 'admin')->count()
            ],
            'active_users' => [
                'daily' => User::where('last_login_at', '>=', now()->subDay())->count(),
                'weekly' => User::where('last_login_at', '>=', now()->subWeek())->count(),
                'monthly' => User::where('last_login_at', '>=', now()->subMonth())->count()
            ]
        ];
    }

    private function getSystemCourseAnalytics()
    {
        return [
            'course_distribution' => [
                'published' => Course::where('status', 'published')->count(),
                'draft' => Course::where('status', 'draft')->count(),
                'pending' => Course::where('status', 'pending')->count()
            ],
            'popular_categories' => \App\Models\Category::withCount('courses')
                                                       ->orderBy('courses_count', 'desc')
                                                       ->limit(5)
                                                       ->get()
        ];
    }

    private function getSystemRevenueAnalytics()
    {
        return [
            'total_revenue' => Enrollment::sum('amount_paid'),
            'monthly_revenue' => Enrollment::where('enrolled_at', '>=', now()->startOfMonth())->sum('amount_paid'),
            'revenue_by_category' => $this->getRevenueByCategoryData(),
            'top_earning_courses' => Course::withSum('enrollments', 'amount_paid')
                                          ->orderBy('enrollments_sum_amount_paid', 'desc')
                                          ->limit(5)
                                          ->get()
        ];
    }

    private function getPendingApprovals()
    {
        return [
            'courses_pending_approval' => Course::where('status', 'pending')->count(),
            'instructor_applications' => 0, // Would implement when instructor application system is created
            'content_reports' => 0 // Would implement when reporting system is created
        ];
    }

    private function getSystemHealth()
    {
        return [
            'database_status' => 'healthy',
            'storage_usage' => '45%',
            'api_response_time' => '120ms',
            'uptime' => '99.9%'
        ];
    }

    private function getRevenueByCategoryData()
    {
        return \App\Models\Category::with(['courses.enrollments'])
                                  ->get()
                                  ->map(function ($category) {
                                      return [
                                          'category' => $category->title,
                                          'revenue' => $category->courses->sum(function ($course) {
                                              return $course->enrollments->sum('amount_paid');
                                          })
                                      ];
                                  })
                                  ->sortByDesc('revenue')
                                  ->values();
    }

    private function getStudentAnalytics($user, $timeframe)
    {
        return [
            'lessons_completed' => LessonCompletion::where('user_id', $user->id)
                                                 ->where('completed_at', '>=', now()->subDays($timeframe))
                                                 ->count(),
            'study_time' => LessonCompletion::where('user_id', $user->id)
                                          ->where('completed_at', '>=', now()->subDays($timeframe))
                                          ->sum('time_spent'),
            'quiz_performance' => $this->getQuizPerformance($user, $timeframe),
            'progress_trend' => $this->getProgressTrend($user, $timeframe)
        ];
    }

    private function getInstructorAnalytics($user, $timeframe)
    {
        $courseIds = Course::where('instructor_id', $user->id)->pluck('id');

        return [
            'new_enrollments' => Enrollment::whereIn('course_id', $courseIds)
                                         ->where('enrolled_at', '>=', now()->subDays($timeframe))
                                         ->count(),
            'revenue_generated' => Enrollment::whereIn('course_id', $courseIds)
                                           ->where('enrolled_at', '>=', now()->subDays($timeframe))
                                           ->sum('amount_paid'),
            'student_engagement' => $this->getStudentEngagement($courseIds, $timeframe),
            'course_performance' => $this->getCoursePerformance($courseIds, $timeframe)
        ];
    }

    private function getAdminAnalytics($timeframe)
    {
        return [
            'platform_growth' => [
                'new_users' => User::where('created_at', '>=', now()->subDays($timeframe))->count(),
                'new_courses' => Course::where('created_at', '>=', now()->subDays($timeframe))->count(),
                'new_enrollments' => Enrollment::where('enrolled_at', '>=', now()->subDays($timeframe))->count()
            ],
            'revenue_metrics' => [
                'total_revenue' => Enrollment::where('enrolled_at', '>=', now()->subDays($timeframe))->sum('amount_paid'),
                'average_transaction' => Enrollment::where('enrolled_at', '>=', now()->subDays($timeframe))->avg('amount_paid')
            ],
            'engagement_metrics' => [
                'active_users' => User::where('last_login_at', '>=', now()->subDays($timeframe))->count(),
                'content_consumption' => LessonCompletion::where('completed_at', '>=', now()->subDays($timeframe))->count()
            ]
        ];
    }

    private function getQuizPerformance($user, $timeframe)
    {
        // Mock implementation
        return [
            'average_score' => 85.5,
            'quizzes_taken' => 12,
            'improvement_rate' => 15.2
        ];
    }

    private function getProgressTrend($user, $timeframe)
    {
        // Mock implementation
        return [
            'trend' => 'increasing',
            'rate' => 12.5
        ];
    }

    private function getStudentEngagement($courseIds, $timeframe)
    {
        // Mock implementation
        return [
            'average_session_duration' => 45, // minutes
            'completion_rate' => 78.5,
            'active_students' => 156
        ];
    }

    private function getCoursePerformance($courseIds, $timeframe)
    {
        // Mock implementation
        return [
            'top_performing_course' => 'Laravel Fundamentals',
            'average_rating' => 4.5,
            'completion_rate' => 82.3
        ];
    }
}
