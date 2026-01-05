<?php

namespace App\Http\Controllers;

use App\Models\LessonCompletion;
use App\Models\Answer;
use App\Models\Enrollment;
use App\Services\PointsAndBadgesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Note: Middleware is applied at route level in Laravel 12
    // See routes/api.php for auth:sanctum middleware configuration

    /**
     * Get user profile
     */
    public function profile()
    {
        $user = Auth::user();

        $profileData = $user->toArray();

        // Convert profile_photo to full URL
        if ($profileData['profile_photo']) {
            $profileData['profile_photo'] = '/storage/' . $profileData['profile_photo'];
        }

        // Add additional profile information
        $profileData['stats'] = [
            'total_enrollments' => $user->enrollments()->count(),
            'completed_courses' => $user->enrollments()->where('status', 'completed')->count(),
            'certificates_earned' => $user->certificates()->count(),
            'total_rewards' => $user->rewards()->sum('amount'),
            'current_streak' => $this->calculateLoginStreak($user),
            'total_study_time' => LessonCompletion::where('user_id', $user->id)->sum('time_spent'),
            'points' => $user->getPoints(),
            'level' => $this->calculateUserLevel($user->getPoints())
        ];

        // Add wallet information
        $wallet = $user->getOrCreateWallet();
        $profileData['wallet'] = [
            'balance' => $wallet->balance,
            'total_spent' => $wallet->transactions()->where('type', 'debit')->sum('amount'),
            'total_earned' => $wallet->transactions()->where('type', 'credit')->sum('amount')
        ];

        // Add recent activity
        $profileData['recent_activity'] = $this->getRecentActivity($user);

        return response()->json([
            'success' => true,
            'data' => $profileData
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'timezone' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:10',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'parent_first_name' => 'nullable|string|max:255',
            'parent_last_name' => 'nullable|string|max:255',
            'parent_email' => 'nullable|email',
            'parent_phone' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            \Log::error('Profile update validation failed', [
                'errors' => $validator->errors()->toArray(),
                'request_data' => $request->except(['avatar', 'password'])
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = $request->except(['avatar']);

            // Handle avatar upload - save to profile_photo column
            if ($request->hasFile('avatar')) {
                // Delete old profile photo
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                $profilePhotoPath = $request->file('avatar')->store('profile_photos', 'public');
                $updateData['profile_photo'] = $profilePhotoPath;
            }

            $user->update($updateData);

            // Return user with full profile photo URL
            $userData = $user->fresh()->toArray();
            if ($userData['profile_photo']) {
                $userData['profile_photo'] = '/storage/' . $userData['profile_photo'];
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $userData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user dashboard data
     */
    public function dashboard()
    {
        $user = Auth::user();

        $dashboardData = [
            'user' => $user,
            'stats' => [
                'total_enrollments' => $user->enrollments()->count(),
                'active_enrollments' => $user->enrollments()->where('status', 'active')->count(),
                'completed_courses' => $user->enrollments()->where('status', 'completed')->count(),
                'certificates_earned' => $user->certificates()->count(),
                'total_study_time' => LessonCompletion::where('user_id', $user->id)->sum('time_spent'),
                'current_streak' => $this->calculateLoginStreak($user),
                'wallet_balance' => $user->getOrCreateWallet()->balance,
                'points' => $user->getPoints(),
                'level' => $this->calculateUserLevel($user->getPoints())
            ],
            'recent_enrollments' => $user->enrollments()
                                       ->with(['course.category', 'course.instructor'])
                                       ->orderBy('enrolled_at', 'desc')
                                       ->limit(5)
                                       ->get(),
            'continue_learning' => $this->getContinueLearning($user),
            'achievements' => $this->getRecentAchievements($user),
            'upcoming_deadlines' => $this->getUpcomingDeadlines($user),
            'recommended_courses' => $this->getRecommendedCourses($user)
        ];

        return response()->json([
            'success' => true,
            'data' => $dashboardData
        ]);
    }

    /**
     * Get user achievements
     */
    public function achievements()
    {
        try {
            $user = Auth::user();

            $achievements = [
                'badges' => $user->badges()->get() ?? [],
                'certificates' => $user->certificates()->with(['course.category'])->get() ?? [],
                'rewards' => $user->rewards()->orderBy('created_at', 'desc')->limit(10)->get() ?? [],
                'milestones' => $this->calculateMilestones($user)
            ];

            return response()->json([
                'success' => true,
                'data' => $achievements
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch achievements: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get learning statistics
     */
    public function learningStats()
    {
        $user = Auth::user();

        $stats = [
            'overview' => [
                'total_courses_enrolled' => $user->enrollments()->count(),
                'courses_completed' => $user->enrollments()->where('status', 'completed')->count(),
                'courses_in_progress' => $user->enrollments()->where('status', 'active')->count(),
                'total_lessons_completed' => LessonCompletion::where('user_id', $user->id)->count(),
                'total_study_time_hours' => round(LessonCompletion::where('user_id', $user->id)->sum('time_spent') / 3600, 2),
                'average_course_completion' => $user->enrollments()->avg('progress'),
                'certificates_earned' => $user->certificates()->count()
            ],
            'monthly_progress' => $this->getMonthlyProgress($user),
            'category_breakdown' => $this->getCategoryBreakdown($user),
            'study_streak' => [
                'current_streak' => $this->calculateLoginStreak($user),
                'longest_streak' => $this->calculateLongestStreak($user),
                'total_study_days' => $this->calculateTotalStudyDays($user)
            ],
            'performance_metrics' => [
                'average_quiz_score' => $this->getAverageQuizScore($user),
                'assignment_completion_rate' => $this->getAssignmentCompletionRate($user),
                'course_completion_rate' => $this->getCourseCompletionRate($user)
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Update user preferences
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'marketing_emails' => 'boolean',
            'course_reminders' => 'boolean',
            'achievement_notifications' => 'boolean',
            'theme' => 'nullable|in:light,dark,auto',
            'language' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:50',
            'study_reminder_time' => 'nullable|date_format:H:i',
            'weekly_goal_hours' => 'nullable|integer|min:1|max:168'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Store preferences in user's metadata or separate preferences table
            $preferences = $request->all();
            $user->update(['preferences' => $preferences]);

            return response()->json([
                'success' => true,
                'message' => 'Preferences updated successfully',
                'data' => $preferences
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update preferences: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user notifications
     */
    public function notifications()
    {
        // This would typically come from a notifications table
        // For now, we'll return a mock structure
        $notifications = [
            'unread_count' => 0,
            'notifications' => []
        ];

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Get user's current points
     */
    public function getPoints()
    {
        try {
            $user = Auth::user();

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $user->id,
                    'points' => $user->getPoints(),
                    'level' => $this->calculateUserLevel($user->getPoints())
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch points: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enroll in a course using points
     */
    public function enrollWithPoints(Request $request)
    {
        try {
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

            // Check if already enrolled
            $existingEnrollment = Enrollment::where('user_id', $user->id)
                                           ->where('course_id', $request->course_id)
                                           ->first();

            if ($existingEnrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already enrolled in this course'
                ], 400);
            }

            // Get course price
            $course = \App\Models\Course::findOrFail($request->course_id);
            $coursePrice = $course->price ?? 0;

            // Use points service to enroll
            $pointsService = new PointsAndBadgesService();
            $result = $pointsService->enrollWithPoints($user, $request->course_id, $coursePrice);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'enrollment' => $result['enrollment'],
                    'remaining_points' => $result['remaining_points']
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to enroll with points: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate user level based on points
     */
    private function calculateUserLevel($points)
    {
        if ($points >= 1000) return 'Expert';
        if ($points >= 500) return 'Advanced';
        if ($points >= 100) return 'Intermediate';
        return 'Amateur';
    }

    /**
     * Get all quiz answers for the current user
     */
    public function quizAnswers()
    {
        try {
            $user = Auth::user();

            // Get all answers for the current user with question and quiz information
            $answers = Answer::with(['question.quiz'])
                            ->where('student_id', $user->id)
                            ->get()
                            ->map(function ($answer) {
                                return [
                                    'id' => $answer->id,
                                    'question_id' => $answer->question_id,
                                    'quiz_id' => $answer->question->quiz_id,
                                    'answer_text' => $answer->answer_text,
                                    'attempt_number' => $answer->attempt_number,
                                    'points_earned' => $answer->points_earned,
                                    'points_possible' => $answer->points_possible,
                                    'is_correct' => $answer->is_correct,
                                    'created_at' => $answer->created_at
                                ];
                            });

            return response()->json([
                'success' => true,
                'data' => $answers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch quiz answers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all quiz results for the current user grouped by course
     */
    public function allQuizResults()
    {
        try {
            $user = Auth::user();

            // Get all answers grouped by quiz
            $answers = Answer::with(['question.quiz'])
                            ->where('student_id', $user->id)
                            ->get()
                            ->groupBy(function($answer) {
                                return $answer->question->quiz_id;
                            });

            // Build results for each quiz
            $quizzesByQuiz = [];
            foreach ($answers as $quizId => $quizAnswers) {
                $quiz = \App\Models\Quiz::with(['lesson.course', 'topic.course', 'questions'])->find($quizId);

                if (!$quiz) continue;

                // Get the course
                $course = null;
                if ($quiz->lesson && $quiz->lesson->course) {
                    $course = $quiz->lesson->course;
                } elseif ($quiz->topic && $quiz->topic->course) {
                    $course = $quiz->topic->course;
                }

                if (!$course) continue;

                // Group answers by attempt
                $attemptGroups = $quizAnswers->groupBy('attempt_number');

                $attempts = $attemptGroups->map(function($attemptAnswers) use ($quiz) {
                    $totalScore = $attemptAnswers->sum('points_earned');
                    $maxScore = $attemptAnswers->sum('points_possible');
                    $percentage = $maxScore > 0 ? round(($totalScore / $maxScore) * 100, 2) : 0;

                    return [
                        'attempt_number' => $attemptAnswers->first()->attempt_number,
                        'score' => $totalScore,
                        'max_score' => $maxScore,
                        'percentage' => $percentage,
                        'passed' => $quiz->passing_score ? $percentage >= $quiz->passing_score : true,
                        'submitted_at' => $attemptAnswers->first()->created_at,
                        'answers' => $attemptAnswers->map(function($answer) {
                            return [
                                'question_id' => $answer->question_id,
                                'question_text' => $answer->question->question_text,
                                'user_answer' => $answer->answer_text,
                                'correct_answer' => $answer->question->correct_answer,
                                'points_earned' => $answer->points_earned,
                                'points_possible' => $answer->points_possible,
                                'is_correct' => $answer->is_correct,
                                'explanation' => $answer->question->explanation
                            ];
                        })->toArray()
                    ];
                })->values();

                $quizzesByQuiz[$quizId] = [
                    'quiz' => [
                        'id' => $quiz->id,
                        'title' => $quiz->title,
                        'type' => $quiz->type,
                        'passing_score' => $quiz->passing_score
                    ],
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'topic' => $quiz->topic ? [
                        'id' => $quiz->topic->id,
                        'title' => $quiz->topic->title
                    ] : null,
                    'lesson' => $quiz->lesson ? [
                        'id' => $quiz->lesson->id,
                        'title' => $quiz->lesson->title
                    ] : null,
                    'results' => $attempts
                ];
            }

            // Group by course and aggregate results
            $courseResults = [];
            foreach ($quizzesByQuiz as $quizData) {
                $courseId = $quizData['course_id'];
                $courseTitle = $quizData['course_title'];

                if (!isset($courseResults[$courseId])) {
                    $courseResults[$courseId] = [
                        'course' => [
                            'id' => $courseId,
                            'title' => $courseTitle
                        ],
                        'total_points_earned' => 0,
                        'total_points_possible' => 0,
                        'quizzes' => []
                    ];
                }

                // Get the latest attempt for this quiz
                if (!empty($quizData['results'])) {
                    $latestAttempt = $quizData['results'][count($quizData['results']) - 1];
                    $courseResults[$courseId]['total_points_earned'] += $latestAttempt['score'];
                    $courseResults[$courseId]['total_points_possible'] += $latestAttempt['max_score'];
                }

                $courseResults[$courseId]['quizzes'][] = $quizData;
            }

            // Calculate percentage for each course
            $finalResults = [];
            foreach ($courseResults as $courseData) {
                $totalEarned = $courseData['total_points_earned'];
                $totalPossible = $courseData['total_points_possible'];
                $percentage = $totalPossible > 0 ? round(($totalEarned / $totalPossible) * 100, 2) : 0;

                $finalResults[] = [
                    'course' => $courseData['course'],
                    'total_points_earned' => $totalEarned,
                    'total_points_possible' => $totalPossible,
                    'percentage' => $percentage,
                    'passed' => $percentage >= 70,
                    'quizzes' => $courseData['quizzes']
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $finalResults
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch quiz results: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Debug endpoint to check quiz data
     */
    public function debugQuizData()
    {
        try {
            $user = Auth::user();

            // Get all enrollments
            $enrollments = \App\Models\Enrollment::where('user_id', $user->id)->get();

            // Get all answers for this user
            $answers = Answer::where('student_id', $user->id)->count();

            // Get all courses with topics and quizzes
            $courses = \App\Models\Course::with(['topics.quizzes', 'lessons.quizzes'])
                            ->whereHas('enrollments', function($q) use ($user) {
                                $q->where('user_id', $user->id);
                            })
                            ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $user->id,
                    'enrollments_count' => $enrollments->count(),
                    'answers_count' => $answers,
                    'courses' => $courses->map(function($course) {
                        return [
                            'id' => $course->id,
                            'title' => $course->title,
                            'topics_count' => $course->topics->count(),
                            'lessons_count' => $course->lessons->count(),
                            'topic_quizzes_count' => $course->topics->sum(fn($t) => $t->quizzes->count()),
                            'lesson_quizzes_count' => $course->lessons->sum(fn($l) => $l->quizzes->count()),
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Debug error: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Mark notifications as read
     */
    public function markNotificationsRead(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'notification_ids' => 'nullable|array',
            'notification_ids.*' => 'integer',
            'mark_all' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Implementation would mark specific notifications or all as read
        // For now, return success

        return response()->json([
            'success' => true,
            'message' => 'Notifications marked as read'
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 400);
        }

        try {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to change password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete user account
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        try {
            // Delete profile photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Delete all user tokens (logout from all devices)
            $user->tokens()->delete();

            // Delete the user
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete account: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to calculate login streak
     */
    private function calculateLoginStreak($user)
    {
        // This would typically check login logs
        // For now, return a mock value
        return 7;
    }

    /**
     * Helper method to get recent activity
     */
    private function getRecentActivity($user)
    {
        $activities = [];

        // Recent enrollments
        $recentEnrollments = $user->enrollments()
                                 ->with('course')
                                 ->orderBy('enrolled_at', 'desc')
                                 ->limit(3)
                                 ->get();

        foreach ($recentEnrollments as $enrollment) {
            if ($enrollment->course) {
                $activities[] = [
                    'type' => 'enrollment',
                    'message' => 'Enrolled in ' . $enrollment->course->title,
                    'date' => $enrollment->enrolled_at,
                    'course_id' => $enrollment->course_id
                ];
            }
        }

        // Recent lesson completions
        $recentCompletions = LessonCompletion::with(['lesson.course'])
                                           ->where('user_id', $user->id)
                                           ->orderBy('completed_at', 'desc')
                                           ->limit(3)
                                           ->get();

        foreach ($recentCompletions as $completion) {
            $activities[] = [
                'type' => 'lesson_completion',
                'message' => 'Completed lesson: ' . $completion->lesson->title,
                'date' => $completion->completed_at,
                'course_id' => $completion->lesson->course_id
            ];
        }

        // Sort by date and return latest 5
        usort($activities, function($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        return array_slice($activities, 0, 5);
    }

    /**
     * Helper method to get continue learning courses
     */
    private function getContinueLearning($user)
    {
        return $user->enrollments()
                   ->with(['course.lessons'])
                   ->where('status', 'active')
                   ->where('progress', '>', 0)
                   ->where('progress', '<', 100)
                   ->orderBy('updated_at', 'desc')
                   ->limit(3)
                   ->get()
                   ->map(function ($enrollment) use ($user) {
                       $course = $enrollment->course;
                       $completedLessons = LessonCompletion::where('user_id', $user->id)
                                                         ->whereIn('lesson_id', $course->lessons->pluck('id'))
                                                         ->pluck('lesson_id');

                       $nextLesson = $course->lessons()
                                          ->whereNotIn('id', $completedLessons)
                                          ->orderBy('order')
                                          ->first();

                       return [
                           'enrollment' => $enrollment,
                           'next_lesson' => $nextLesson
                       ];
                   });
    }

    /**
     * Helper method to get recent achievements
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
                    'date' => $certificate->created_at,
                    'icon' => 'certificate'
                ];
            }
        }

        // Recent rewards
        $recentRewards = $user->rewards()
                             ->orderBy('created_at', 'desc')
                             ->limit(3)
                             ->get();

        foreach ($recentRewards as $reward) {
            $achievements[] = [
                'type' => 'reward',
                'title' => ucfirst($reward->type) . ' Reward',
                'description' => 'Earned ₦' . number_format($reward->amount, 2),
                'date' => $reward->created_at,
                'icon' => 'reward'
            ];
        }

        // Sort by date and return latest 5
        usort($achievements, function($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        return array_slice($achievements, 0, 5);
    }

    /**
     * Helper method to get upcoming deadlines
     */
    private function getUpcomingDeadlines($user)
    {
        // This would typically check assignment deadlines, quiz deadlines, etc.
        // For now, return empty array
        return [];
    }

    /**
     * Helper method to get recommended courses
     */
    private function getRecommendedCourses($user)
    {
        // This would use AI recommendations or collaborative filtering
        // For now, return popular courses in user's categories
        $enrolledCategories = $user->enrollments()
                                  ->with('course.category')
                                  ->get()
                                  ->pluck('course.category.id')
                                  ->unique();

        if ($enrolledCategories->isEmpty()) {
            return [];
        }

        return \App\Models\Course::with(['category', 'instructor'])
                                ->whereIn('category_id', $enrolledCategories)
                                ->where('status', 'published')
                                ->whereNotIn('id', $user->enrollments()->pluck('course_id'))
                                ->withCount('enrollments')
                                ->orderBy('enrollments_count', 'desc')
                                ->limit(4)
                                ->get();
    }

    /**
     * Helper method to calculate milestones
     */
    private function calculateMilestones($user)
    {
        $completedCourses = $user->enrollments()->where('status', 'completed')->count();
        $totalStudyTime = LessonCompletion::where('user_id', $user->id)->sum('time_spent');
        $certificates = $user->certificates()->count();

        return [
            'courses_completed' => [
                'current' => $completedCourses,
                'next_milestone' => $this->getNextMilestone($completedCourses, [1, 5, 10, 25, 50, 100]),
                'progress' => $this->getMilestoneProgress($completedCourses, [1, 5, 10, 25, 50, 100])
            ],
            'study_hours' => [
                'current' => round($totalStudyTime / 3600, 1),
                'next_milestone' => $this->getNextMilestone(round($totalStudyTime / 3600), [10, 50, 100, 250, 500, 1000]),
                'progress' => $this->getMilestoneProgress(round($totalStudyTime / 3600), [10, 50, 100, 250, 500, 1000])
            ],
            'certificates' => [
                'current' => $certificates,
                'next_milestone' => $this->getNextMilestone($certificates, [1, 3, 5, 10, 20, 50]),
                'progress' => $this->getMilestoneProgress($certificates, [1, 3, 5, 10, 20, 50])
            ]
        ];
    }

    /**
     * Helper method to get next milestone
     */
    private function getNextMilestone($current, $milestones)
    {
        foreach ($milestones as $milestone) {
            if ($current < $milestone) {
                return $milestone;
            }
        }
        return end($milestones);
    }

    /**
     * Helper method to get milestone progress
     */
    private function getMilestoneProgress($current, $milestones)
    {
        $nextMilestone = $this->getNextMilestone($current, $milestones);
        $previousMilestone = 0;

        foreach ($milestones as $milestone) {
            if ($milestone >= $nextMilestone) {
                break;
            }
            $previousMilestone = $milestone;
        }

        if ($nextMilestone === $previousMilestone) {
            return 100;
        }

        return round((($current - $previousMilestone) / ($nextMilestone - $previousMilestone)) * 100, 1);
    }

    /**
     * Helper methods for learning stats
     */
    private function getMonthlyProgress($user)
    {
        // Return last 12 months of progress data
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'lessons_completed' => LessonCompletion::where('user_id', $user->id)
                                                     ->whereYear('completed_at', $date->year)
                                                     ->whereMonth('completed_at', $date->month)
                                                     ->count(),
                'study_hours' => round(LessonCompletion::where('user_id', $user->id)
                                                     ->whereYear('completed_at', $date->year)
                                                     ->whereMonth('completed_at', $date->month)
                                                     ->sum('time_spent') / 3600, 1)
            ];
        }
        return $months;
    }

    private function getCategoryBreakdown($user)
    {
        return $user->enrollments()
                   ->with('course.courseCategory')
                   ->get()
                   ->groupBy('course.courseCategory.title')
                   ->map(function ($enrollments, $category) {
                       return [
                           'category' => $category,
                           'total_courses' => $enrollments->count(),
                           'completed_courses' => $enrollments->where('status', 'completed')->count(),
                           'completion_rate' => $enrollments->count() > 0
                               ? round(($enrollments->where('status', 'completed')->count() / $enrollments->count()) * 100, 1)
                               : 0
                       ];
                   })
                   ->values();
    }

    private function calculateLongestStreak($user)
    {
        // This would calculate from login logs
        return 14; // Mock value
    }

    private function calculateTotalStudyDays($user)
    {
        return LessonCompletion::where('user_id', $user->id)
                             ->selectRaw('DATE(completed_at) as study_date')
                             ->distinct()
                             ->count();
    }

    private function getAverageQuizScore($user)
    {
        // This would calculate from quiz results
        return 85.5; // Mock value
    }

    private function getAssignmentCompletionRate($user)
    {
        // This would calculate from assignment submissions
        return 92.3; // Mock value
    }

    private function getCourseCompletionRate($user)
    {
        $totalEnrollments = $user->enrollments()->count();
        $completedEnrollments = $user->enrollments()->where('status', 'completed')->count();

        return $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 1) : 0;
    }

    /**
     * Get subscription history from enrollments only
     */
    public function subscriptionHistory(Request $request)
    {
        try {
            $user = Auth::user();
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);

            // Get user's enrollments with course details
            $enrollments = $user->enrollments()
                ->with('course')
                ->orderBy('enrolled_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            // Format subscription history from enrollments only
            $subscriptionHistory = [];

            foreach ($enrollments->items() as $enrollment) {
                $subscriptionHistory[] = [
                    'id' => 'enrollment_' . $enrollment->id,
                    'type' => 'enrollment',
                    'course' => [
                        'id' => $enrollment->course->id,
                        'title' => $enrollment->course->title,
                        'price' => $enrollment->course->price
                    ],
                    'amount' => $enrollment->amount_paid ?? $enrollment->course->price,
                    'status' => $enrollment->status,
                    'date' => $enrollment->enrolled_at,
                    'completed_at' => $enrollment->completed_at,
                    'progress' => $enrollment->progress
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $subscriptionHistory,
                'pagination' => [
                    'current_page' => $enrollments->currentPage(),
                    'per_page' => $enrollments->perPage(),
                    'total' => $enrollments->total(),
                    'last_page' => $enrollments->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch subscription history: ' . $e->getMessage()
            ], 500);
        }
    }
}
