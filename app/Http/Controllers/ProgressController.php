<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\Enrollment;
use App\Models\Certificate;
use App\Models\UserBadge;
use App\Models\QuizAttempt;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProgressController extends Controller
{
    // Note: Middleware is applied at route level in Laravel 12
    // See routes/api.php for middleware configuration

    /**
     * Get course progress overview
     */
    public function courseProgress(Request $request)
    {
        $user = Auth::user();
        
        $query = Enrollment::with(['course.category', 'course.instructor'])
                          ->where('user_id', $user->id);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by course category
        if ($request->has('category_id')) {
            $query->whereHas('course', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        $enrollments = $query->orderBy('enrolled_at', 'desc')
                           ->paginate($request->get('per_page', 15));

        // Add detailed progress for each enrollment
        $enrollments->getCollection()->transform(function ($enrollment) use ($user) {
            $enrollmentData = $enrollment->toArray();
            $course = $enrollment->course;

            // Check if course exists
            if (!$course) {
                $enrollmentData['detailed_progress'] = [
                    'lessons' => ['completed' => 0, 'total' => 0, 'percentage' => 0],
                    'quizzes' => ['completed' => 0, 'total' => 0, 'percentage' => 0],
                    'assignments' => ['submitted' => 0, 'total' => 0, 'percentage' => 0]
                ];
                $enrollmentData['study_time_hours'] = 0;
                $enrollmentData['next_lesson'] = null;
                return $enrollmentData;
            }

            // Calculate detailed progress
            $totalLessons = $course->lessons()->count();
            $completedLessons = LessonCompletion::where('user_id', $user->id)
                                              ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                                              ->count();

            // Get quizzes through lessons to avoid ambiguous column issue
            $lessonIds = $course->lessons()->pluck('lessons.id');
            $quizIds = \App\Models\Quiz::whereIn('lesson_id', $lessonIds)->pluck('id');
            $totalQuizzes = $quizIds->count();
            $completedQuizzes = QuizAttempt::where('user_id', $user->id)
                                         ->whereIn('quiz_id', $quizIds)
                                         ->where('status', 'completed')
                                         ->distinct('quiz_id')
                                         ->count();

            $totalAssignments = $course->assignments()->count();
            $submittedAssignments = AssignmentSubmission::where('user_id', $user->id)
                                                       ->whereIn('assignment_id', $course->assignments()->pluck('id'))
                                                       ->count();

            $enrollmentData['detailed_progress'] = [
                'lessons' => [
                    'completed' => $completedLessons,
                    'total' => $totalLessons,
                    'percentage' => $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 2) : 0
                ],
                'quizzes' => [
                    'completed' => $completedQuizzes,
                    'total' => $totalQuizzes,
                    'percentage' => $totalQuizzes > 0 ? round(($completedQuizzes / $totalQuizzes) * 100, 2) : 0
                ],
                'assignments' => [
                    'submitted' => $submittedAssignments,
                    'total' => $totalAssignments,
                    'percentage' => $totalAssignments > 0 ? round(($submittedAssignments / $totalAssignments) * 100, 2) : 0
                ]
            ];

            // Calculate study time
            $studyTime = LessonCompletion::where('user_id', $user->id)
                                       ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                                       ->sum('time_spent');

            $enrollmentData['study_time_hours'] = round($studyTime / 3600, 2);

            // Next lesson to complete
            $nextLesson = $course->lessons()
                               ->whereNotIn('id', function($query) use ($user) {
                                   $query->select('lesson_id')
                                        ->from('lesson_completions')
                                        ->where('user_id', $user->id);
                               })
                               ->orderBy('order')
                               ->first();

            $enrollmentData['next_lesson'] = $nextLesson;

            return $enrollmentData;
        });

        return response()->json([
            'success' => true,
            'data' => $enrollments
        ]);
    }

    /**
     * Get detailed lesson progress
     */
    public function lessonProgress(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $course = Course::findOrFail($request->course_id);

        // Check if user is enrolled
        $enrollment = Enrollment::where('user_id', $user->id)
                               ->where('course_id', $course->id)
                               ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'You are not enrolled in this course'
            ], 403);
        }

        $lessons = $course->lessons()
                         ->with(['completions' => function($query) use ($user) {
                             $query->where('user_id', $user->id);
                         }])
                         ->orderBy('order')
                         ->get();

        $lessonProgress = $lessons->map(function($lesson) use ($user) {
            $completion = $lesson->completions->first();
            
            return [
                'lesson_id' => $lesson->id,
                'title' => $lesson->title,
                'order' => $lesson->order,
                'duration' => $lesson->duration,
                'is_completed' => $completion ? true : false,
                'completed_at' => $completion ? $completion->completed_at : null,
                'time_spent' => $completion ? $completion->time_spent : 0,
                'watch_percentage' => $completion ? $completion->watch_percentage : 0,
                'last_watched_at' => $completion ? $completion->updated_at : null
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'course' => $course,
                'enrollment' => $enrollment,
                'lessons' => $lessonProgress,
                'summary' => [
                    'total_lessons' => $lessons->count(),
                    'completed_lessons' => $lessons->filter(function($lesson) {
                        return $lesson->completions->isNotEmpty();
                    })->count(),
                    'total_study_time' => $lessons->sum(function($lesson) {
                        $completion = $lesson->completions->first();
                        return $completion ? $completion->time_spent : 0;
                    })
                ]
            ]
        ]);
    }

    /**
     * Get overall learning statistics
     */
    public function overallProgress()
    {
        $user = Auth::user();

        $stats = [
            'enrollments' => [
                'total' => $user->enrollments()->count(),
                'active' => $user->enrollments()->where('status', 'active')->count(),
                'completed' => $user->enrollments()->where('status', 'completed')->count(),
                'paused' => $user->enrollments()->where('status', 'paused')->count()
            ],
            'learning_time' => [
                'total_hours' => round(LessonCompletion::where('user_id', $user->id)->sum('time_spent') / 3600, 2),
                'this_week_hours' => round(LessonCompletion::where('user_id', $user->id)
                                                         ->where('created_at', '>=', now()->startOfWeek())
                                                         ->sum('time_spent') / 3600, 2),
                'this_month_hours' => round(LessonCompletion::where('user_id', $user->id)
                                                          ->where('created_at', '>=', now()->startOfMonth())
                                                          ->sum('time_spent') / 3600, 2)
            ],
            'achievements' => [
                'certificates_earned' => Certificate::where('user_id', $user->id)->count(),
                'badges_earned' => UserBadge::where('user_id', $user->id)->whereNull('revoked_at')->count(),
                'total_badge_points' => UserBadge::where('user_id', $user->id)
                                                ->whereNull('revoked_at')
                                                ->join('badges', 'user_badges.badge_id', '=', 'badges.id')
                                                ->sum('badges.points')
            ],
            'assessments' => [
                'quizzes_completed' => QuizAttempt::where('user_id', $user->id)
                                                 ->where('status', 'completed')
                                                 ->distinct('quiz_id')
                                                 ->count(),
                'average_quiz_score' => QuizAttempt::where('user_id', $user->id)
                                                  ->where('status', 'completed')
                                                  ->avg('score'),
                'assignments_submitted' => AssignmentSubmission::where('user_id', $user->id)->count(),
                'average_assignment_grade' => AssignmentSubmission::where('user_id', $user->id)
                                                                 ->whereNotNull('grade')
                                                                 ->avg('grade')
            ],
            'streaks' => [
                'current_streak' => $this->calculateCurrentStreak($user),
                'longest_streak' => $this->calculateLongestStreak($user),
                'last_activity' => $this->getLastActivityDate($user)
            ]
        ];

        // Recent activity
        $recentActivity = $this->getRecentActivity($user);

        // Learning goals progress
        $learningGoals = $this->getLearningGoalsProgress($user);

        return response()->json([
            'success' => true,
            'data' => [
                'statistics' => $stats,
                'recent_activity' => $recentActivity,
                'learning_goals' => $learningGoals
            ]
        ]);
    }

    /**
     * Update progress manually
     */
    public function updateProgress(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:lesson,quiz,assignment',
            'item_id' => 'required|integer',
            'progress_data' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            switch ($request->type) {
                case 'lesson':
                    $this->updateLessonProgress($user, $request->item_id, $request->progress_data);
                    break;
                case 'quiz':
                    $this->updateQuizProgress($user, $request->item_id, $request->progress_data);
                    break;
                case 'assignment':
                    $this->updateAssignmentProgress($user, $request->item_id, $request->progress_data);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Progress updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update progress: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available certificates
     */
    public function availableCertificates()
    {
        $user = Auth::user();

        // Get completed courses that don't have certificates yet
        $completedEnrollments = Enrollment::with(['course.category', 'course.instructor'])
                                        ->where('user_id', $user->id)
                                        ->where('status', 'completed')
                                        ->get();

        $availableCertificates = $completedEnrollments->filter(function($enrollment) use ($user) {
            return !Certificate::where('user_id', $user->id)
                              ->where('course_id', $enrollment->course_id)
                              ->exists();
        });

        // Get existing certificates
        $existingCertificates = Certificate::with(['course.category', 'course.instructor'])
                                         ->where('user_id', $user->id)
                                         ->orderBy('issued_at', 'desc')
                                         ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'available_for_generation' => $availableCertificates,
                'existing_certificates' => $existingCertificates
            ]
        ]);
    }

    /**
     * Generate completion certificate
     */
    public function generateCertificate(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $course = Course::findOrFail($request->course_id);

            // Check if user completed the course
            $enrollment = Enrollment::where('user_id', $user->id)
                                  ->where('course_id', $course->id)
                                  ->where('status', 'completed')
                                  ->first();

            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course not completed or not enrolled'
                ], 400);
            }

            // Check if certificate already exists
            $existingCertificate = Certificate::where('user_id', $user->id)
                                            ->where('course_id', $course->id)
                                            ->first();

            if ($existingCertificate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificate already exists',
                    'data' => $existingCertificate
                ], 400);
            }

            // Generate certificate
            $certificate = Certificate::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'certificate_number' => $this->generateCertificateNumber(),
                'certificate_url' => 'certificates/cert-' . $user->id . '-' . $course->id . '.pdf',
                'issued_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Certificate generated successfully',
                'data' => $certificate->load(['course', 'user'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate certificate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get achievement progress
     */
    public function achievementProgress()
    {
        $user = Auth::user();

        // Get all available badges with progress
        $badges = \App\Models\Badge::where('is_active', true)->get();
        $badgeProgress = $badges->map(function($badge) use ($user) {
            $userBadge = UserBadge::where('user_id', $user->id)
                                 ->where('badge_id', $badge->id)
                                 ->first();

            return [
                'badge' => $badge,
                'earned' => $userBadge ? true : false,
                'earned_at' => $userBadge ? $userBadge->earned_at : null,
                'progress' => $this->calculateBadgeProgress($badge, $user)
            ];
        });

        // Get learning milestones
        $milestones = [
            [
                'name' => 'First Course Completed',
                'description' => 'Complete your first course',
                'achieved' => $user->enrollments()->where('status', 'completed')->count() >= 1,
                'progress' => min(100, $user->enrollments()->where('status', 'completed')->count() * 100)
            ],
            [
                'name' => '10 Hours of Learning',
                'description' => 'Accumulate 10 hours of study time',
                'achieved' => LessonCompletion::where('user_id', $user->id)->sum('time_spent') >= 36000,
                'progress' => min(100, (LessonCompletion::where('user_id', $user->id)->sum('time_spent') / 36000) * 100)
            ],
            [
                'name' => 'Quiz Master',
                'description' => 'Score 90% or higher on 5 quizzes',
                'achieved' => QuizAttempt::where('user_id', $user->id)->where('score', '>=', 90)->count() >= 5,
                'progress' => min(100, (QuizAttempt::where('user_id', $user->id)->where('score', '>=', 90)->count() / 5) * 100)
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'badges' => $badgeProgress,
                'milestones' => $milestones,
                'summary' => [
                    'total_badges' => $badges->count(),
                    'earned_badges' => UserBadge::where('user_id', $user->id)->whereNull('revoked_at')->count(),
                    'total_points' => UserBadge::where('user_id', $user->id)
                                            ->whereNull('revoked_at')
                                            ->join('badges', 'user_badges.badge_id', '=', 'badges.id')
                                            ->sum('badges.points')
                ]
            ]
        ]);
    }

    /**
     * Get learning streak information
     */
    public function streakProgress()
    {
        $user = Auth::user();

        $currentStreak = $this->calculateCurrentStreak($user);
        $longestStreak = $this->calculateLongestStreak($user);
        $streakHistory = $this->getStreakHistory($user);

        return response()->json([
            'success' => true,
            'data' => [
                'current_streak' => $currentStreak,
                'longest_streak' => $longestStreak,
                'streak_history' => $streakHistory,
                'streak_goals' => [
                    [
                        'days' => 7,
                        'achieved' => $currentStreak >= 7,
                        'progress' => min(100, ($currentStreak / 7) * 100)
                    ],
                    [
                        'days' => 30,
                        'achieved' => $currentStreak >= 30,
                        'progress' => min(100, ($currentStreak / 30) * 100)
                    ],
                    [
                        'days' => 100,
                        'achieved' => $currentStreak >= 100,
                        'progress' => min(100, ($currentStreak / 100) * 100)
                    ]
                ]
            ]
        ]);
    }

    /**
     * Helper method to update lesson progress
     */
    private function updateLessonProgress($user, $lessonId, $progressData)
    {
        $lesson = Lesson::findOrFail($lessonId);

        // Check if user has access to this lesson
        $enrollment = Enrollment::where('user_id', $user->id)
                               ->where('course_id', $lesson->course_id)
                               ->first();

        if (!$enrollment) {
            throw new \Exception('Not enrolled in this course');
        }

        LessonCompletion::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lessonId],
            [
                'time_spent' => $progressData['time_spent'] ?? 0,
                'watch_percentage' => $progressData['watch_percentage'] ?? 0,
                'completed_at' => $progressData['completed'] ? now() : null
            ]
        );
    }

    /**
     * Helper method to update quiz progress
     */
    private function updateQuizProgress($user, $quizId, $progressData)
    {
        // This would update quiz attempt progress
        // Implementation depends on quiz structure
    }

    /**
     * Helper method to update assignment progress
     */
    private function updateAssignmentProgress($user, $assignmentId, $progressData)
    {
        // This would update assignment submission progress
        // Implementation depends on assignment structure
    }

    /**
     * Helper method to calculate badge progress
     */
    private function calculateBadgeProgress($badge, $user)
    {
        $criteria = $badge->criteria;
        $progress = ['current' => 0, 'required' => 1, 'percentage' => 0];

        switch ($badge->type) {
            case 'course_completion':
                $required = $criteria['courses_required'] ?? 1;
                $completed = $user->enrollments()->where('status', 'completed')->count();
                $progress = [
                    'current' => min($completed, $required),
                    'required' => $required,
                    'percentage' => min(100, ($completed / $required) * 100)
                ];
                break;

            case 'quiz_mastery':
                $required = $criteria['quizzes_required'] ?? 1;
                $minScore = $criteria['min_score'] ?? 80;
                $completed = QuizAttempt::where('user_id', $user->id)
                                      ->where('score', '>=', $minScore)
                                      ->distinct('quiz_id')
                                      ->count();
                $progress = [
                    'current' => min($completed, $required),
                    'required' => $required,
                    'percentage' => min(100, ($completed / $required) * 100)
                ];
                break;
        }

        return $progress;
    }

    /**
     * Helper methods for streak calculation
     */
    private function calculateCurrentStreak($user)
    {
        // Mock implementation - would calculate actual streak
        return 5;
    }

    private function calculateLongestStreak($user)
    {
        // Mock implementation - would calculate longest streak
        return 15;
    }

    private function getLastActivityDate($user)
    {
        $lastActivity = LessonCompletion::where('user_id', $user->id)
                                      ->latest('created_at')
                                      ->first();

        return $lastActivity ? $lastActivity->created_at : null;
    }

    private function getRecentActivity($user)
    {
        $activities = [];

        // Recent lesson completions
        $recentLessons = LessonCompletion::with(['lesson.course'])
                                       ->where('user_id', $user->id)
                                       ->latest('created_at')
                                       ->limit(5)
                                       ->get();

        foreach ($recentLessons as $completion) {
            $activities[] = [
                'type' => 'lesson_completed',
                'title' => $completion->lesson->title,
                'course' => $completion->lesson->course->title,
                'date' => $completion->created_at
            ];
        }

        // Recent quiz attempts
        $recentQuizzes = QuizAttempt::with(['quiz.course'])
                                  ->where('user_id', $user->id)
                                  ->where('status', 'completed')
                                  ->latest('completed_at')
                                  ->limit(3)
                                  ->get();

        foreach ($recentQuizzes as $attempt) {
            $activities[] = [
                'type' => 'quiz_completed',
                'title' => $attempt->quiz->title,
                'course' => $attempt->quiz->course->title,
                'score' => $attempt->score,
                'date' => $attempt->completed_at
            ];
        }

        // Sort by date
        usort($activities, function($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        return array_slice($activities, 0, 10);
    }

    private function getLearningGoalsProgress($user)
    {
        // Mock learning goals - in real implementation, these would be user-defined
        return [
            [
                'goal' => 'Complete 3 courses this month',
                'current' => $user->enrollments()
                                 ->where('status', 'completed')
                                 ->where('completed_at', '>=', now()->startOfMonth())
                                 ->count(),
                'target' => 3,
                'deadline' => now()->endOfMonth()
            ],
            [
                'goal' => 'Study 20 hours this month',
                'current' => round(LessonCompletion::where('user_id', $user->id)
                                                 ->where('created_at', '>=', now()->startOfMonth())
                                                 ->sum('time_spent') / 3600, 1),
                'target' => 20,
                'deadline' => now()->endOfMonth()
            ]
        ];
    }

    private function getStreakHistory($user)
    {
        // Mock streak history - would calculate actual daily activity
        $history = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $hasActivity = LessonCompletion::where('user_id', $user->id)
                                         ->whereDate('created_at', $date)
                                         ->exists();

            $history[] = [
                'date' => $date->format('Y-m-d'),
                'has_activity' => $hasActivity
            ];
        }

        return $history;
    }

    private function generateCertificateNumber()
    {
        do {
            $number = 'CERT-' . strtoupper(uniqid()) . '-' . date('Y');
        } while (Certificate::where('certificate_number', $number)->exists());

        return $number;
    }
}
