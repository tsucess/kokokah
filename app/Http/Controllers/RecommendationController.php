<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\CourseReview;
use App\Models\LessonCompletion;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{
    // Note: Middleware is applied at route level in Laravel 12
    // See routes/api.php for middleware configuration

    /**
     * Get personalized course recommendations
     */
    public function getRecommendations(Request $request)
    {
        try {
            $user = Auth::user();
            $limit = $request->get('limit', 10);

            $recommendations = [
                'personalized' => $this->getPersonalizedRecommendations($user, $limit),
                'trending' => $this->getTrendingCourses($limit),
                'similar_learners' => $this->getSimilarLearnersRecommendations($user, $limit),
                'category_based' => $this->getCategoryBasedRecommendations($user, $limit),
                'skill_gap' => $this->getSkillGapRecommendations($user, $limit),
                'instructor_based' => $this->getInstructorBasedRecommendations($user, $limit)
            ];

            return response()->json([
                'success' => true,
                'data' => $recommendations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch recommendations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course recommendations based on specific course
     */
    public function getCourseBasedRecommendations($courseId, Request $request)
    {
        try {
            $course = Course::findOrFail($courseId);
            $limit = $request->get('limit', 6);

            $recommendations = [
                'similar_courses' => $this->getSimilarCourses($course, $limit),
                'same_instructor' => $this->getSameInstructorCourses($course, $limit),
                'same_category' => $this->getSameCategoryCourses($course, $limit),
                'frequently_bought_together' => $this->getFrequentlyBoughtTogether($course, $limit),
                'next_level' => $this->getNextLevelCourses($course, $limit)
            ];

            return response()->json([
                'success' => true,
                'data' => $recommendations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch course recommendations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get learning path recommendations
     */
    public function getLearningPathRecommendations(Request $request)
    {
        try {
            $user = Auth::user();
            $limit = $request->get('limit', 5);

            $recommendations = [
                'beginner_paths' => $this->getBeginnerPaths($user, $limit),
                'skill_building' => $this->getSkillBuildingPaths($user, $limit),
                'career_focused' => $this->getCareerFocusedPaths($user, $limit),
                'completion_based' => $this->getCompletionBasedPaths($user, $limit)
            ];

            return response()->json([
                'success' => true,
                'data' => $recommendations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch learning path recommendations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get instructor recommendations
     */
    public function getInstructorRecommendations(Request $request)
    {
        try {
            $user = Auth::user();
            $limit = $request->get('limit', 8);

            $recommendations = [
                'top_rated' => $this->getTopRatedInstructors($limit),
                'similar_interests' => $this->getSimilarInterestInstructors($user, $limit),
                'trending' => $this->getTrendingInstructors($limit),
                'new_instructors' => $this->getNewInstructors($limit)
            ];

            return response()->json([
                'success' => true,
                'data' => $recommendations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch instructor recommendations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get content recommendations (lessons, quizzes, etc.)
     */
    public function getContentRecommendations(Request $request)
    {
        try {
            $user = Auth::user();
            $courseId = $request->get('course_id');
            $limit = $request->get('limit', 10);

            $recommendations = [];

            if ($courseId) {
                $course = Course::findOrFail($courseId);
                $recommendations = [
                    'next_lessons' => $this->getNextLessons($user, $course, $limit),
                    'review_content' => $this->getReviewContent($user, $course, $limit),
                    'practice_quizzes' => $this->getPracticeQuizzes($user, $course, $limit),
                    'supplementary' => $this->getSupplementaryContent($user, $course, $limit)
                ];
            } else {
                $recommendations = [
                    'continue_learning' => $this->getContinueLearning($user, $limit),
                    'quick_reviews' => $this->getQuickReviews($user, $limit),
                    'new_content' => $this->getNewContent($user, $limit)
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $recommendations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch content recommendations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user preferences for better recommendations
     */
    public function updatePreferences(Request $request)
    {
        try {
            $user = Auth::user();

            $preferences = [
                'preferred_categories' => $request->get('preferred_categories', []),
                'learning_goals' => $request->get('learning_goals', []),
                'difficulty_preference' => $request->get('difficulty_preference', 'mixed'),
                'time_commitment' => $request->get('time_commitment', 'flexible'),
                'learning_style' => $request->get('learning_style', 'mixed'),
                'interests' => $request->get('interests', [])
            ];

            $user->update(['recommendation_preferences' => $preferences]);

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
     * Get recommendation analytics (for admins)
     */
    public function getAnalytics(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $analytics = [
                'recommendation_performance' => $this->getRecommendationPerformance(),
                'user_engagement' => $this->getUserEngagementMetrics(),
                'algorithm_effectiveness' => $this->getAlgorithmEffectiveness(),
                'popular_recommendations' => $this->getPopularRecommendations(),
                'conversion_rates' => $this->getConversionRates()
            ];

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch recommendation analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper methods for generating recommendations
     */
    private function getPersonalizedRecommendations($user, $limit)
    {
        // Get user's enrolled courses and preferences
        $enrolledCourses = $user->enrollments()->pluck('course_id');
        $completedCourses = $user->enrollments()->where('status', 'completed')->pluck('course_id');
        
        // Get courses based on user's learning history and preferences
        $query = Course::where('status', 'published')
                      ->whereNotIn('id', $enrolledCourses);

        // If user has completed courses, find similar ones
        if ($completedCourses->count() > 0) {
            $categories = Course::whereIn('id', $completedCourses)->pluck('category_id')->unique();
            $query->whereIn('category_id', $categories);
        }

        return $query->withCount('enrollments')
                    ->withAvg('reviews', 'rating')
                    ->orderBy('enrollments_count', 'desc')
                    ->orderBy('reviews_avg_rating', 'desc')
                    ->limit($limit)
                    ->get();
    }

    private function getTrendingCourses($limit)
    {
        return Course::where('status', 'published')
                    ->where('created_at', '>=', now()->subDays(30))
                    ->withCount(['enrollments' => function($query) {
                        $query->where('created_at', '>=', now()->subDays(7));
                    }])
                    ->withAvg('reviews', 'rating')
                    ->orderBy('enrollments_count', 'desc')
                    ->limit($limit)
                    ->get();
    }

    private function getSimilarLearnersRecommendations($user, $limit)
    {
        // Find users with similar enrollment patterns
        $userCourses = $user->enrollments()->pluck('course_id');
        
        if ($userCourses->count() === 0) {
            return collect();
        }

        $similarUsers = User::whereHas('enrollments', function($query) use ($userCourses) {
            $query->whereIn('course_id', $userCourses);
        })->where('id', '!=', $user->id)->limit(50)->get();

        $recommendedCourseIds = collect();
        foreach ($similarUsers as $similarUser) {
            $theirCourses = $similarUser->enrollments()->pluck('course_id');
            $recommendedCourseIds = $recommendedCourseIds->merge($theirCourses->diff($userCourses));
        }

        return Course::whereIn('id', $recommendedCourseIds->unique())
                    ->where('status', 'published')
                    ->withCount('enrollments')
                    ->withAvg('reviews', 'rating')
                    ->orderBy('enrollments_count', 'desc')
                    ->limit($limit)
                    ->get();
    }

    private function getCategoryBasedRecommendations($user, $limit)
    {
        $enrolledCategories = $user->enrollments()
                                 ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                                 ->pluck('courses.category_id')
                                 ->unique();

        if ($enrolledCategories->count() === 0) {
            return collect();
        }

        $enrolledCourses = $user->enrollments()->pluck('course_id');

        return Course::whereIn('category_id', $enrolledCategories)
                    ->whereNotIn('id', $enrolledCourses)
                    ->where('status', 'published')
                    ->withCount('enrollments')
                    ->withAvg('reviews', 'rating')
                    ->orderBy('reviews_avg_rating', 'desc')
                    ->limit($limit)
                    ->get();
    }

    private function getSkillGapRecommendations($user, $limit)
    {
        // Analyze user's completed courses and suggest advanced/complementary courses
        $completedCourses = $user->enrollments()
                               ->where('status', 'completed')
                               ->with('course')
                               ->get()
                               ->pluck('course');

        if ($completedCourses->count() === 0) {
            return collect();
        }

        $categories = $completedCourses->pluck('category_id')->unique();
        $enrolledCourses = $user->enrollments()->pluck('course_id');

        return Course::whereIn('category_id', $categories)
                    ->whereNotIn('id', $enrolledCourses)
                    ->where('difficulty_level', 'advanced')
                    ->where('status', 'published')
                    ->withCount('enrollments')
                    ->withAvg('reviews', 'rating')
                    ->orderBy('reviews_avg_rating', 'desc')
                    ->limit($limit)
                    ->get();
    }

    private function getInstructorBasedRecommendations($user, $limit)
    {
        $favoriteInstructors = $user->enrollments()
                                  ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                                  ->where('enrollments.status', 'completed')
                                  ->pluck('courses.instructor_id')
                                  ->unique();

        if ($favoriteInstructors->count() === 0) {
            return collect();
        }

        $enrolledCourses = $user->enrollments()->pluck('course_id');

        return Course::whereIn('instructor_id', $favoriteInstructors)
                    ->whereNotIn('id', $enrolledCourses)
                    ->where('status', 'published')
                    ->withCount('enrollments')
                    ->withAvg('reviews', 'rating')
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
    }

    private function getSimilarCourses($course, $limit)
    {
        return Course::where('category_id', $course->category_id)
                    ->where('id', '!=', $course->id)
                    ->where('status', 'published')
                    ->withCount('enrollments')
                    ->withAvg('reviews', 'rating')
                    ->orderBy('reviews_avg_rating', 'desc')
                    ->limit($limit)
                    ->get();
    }

    private function getSameInstructorCourses($course, $limit)
    {
        return Course::where('instructor_id', $course->instructor_id)
                    ->where('id', '!=', $course->id)
                    ->where('status', 'published')
                    ->withCount('enrollments')
                    ->withAvg('reviews', 'rating')
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
    }

    private function getSameCategoryCourses($course, $limit)
    {
        return Course::where('category_id', $course->category_id)
                    ->where('id', '!=', $course->id)
                    ->where('status', 'published')
                    ->withCount('enrollments')
                    ->withAvg('reviews', 'rating')
                    ->orderBy('enrollments_count', 'desc')
                    ->limit($limit)
                    ->get();
    }

    private function getFrequentlyBoughtTogether($course, $limit)
    {
        // Find courses that users often enroll in together with this course
        $usersEnrolledInThisCourse = Enrollment::where('course_id', $course->id)->pluck('user_id');
        
        $frequentCourses = Enrollment::whereIn('user_id', $usersEnrolledInThisCourse)
                                   ->where('course_id', '!=', $course->id)
                                   ->groupBy('course_id')
                                   ->selectRaw('course_id, COUNT(*) as frequency')
                                   ->orderBy('frequency', 'desc')
                                   ->limit($limit)
                                   ->pluck('course_id');

        return Course::whereIn('id', $frequentCourses)
                    ->where('status', 'published')
                    ->withCount('enrollments')
                    ->withAvg('reviews', 'rating')
                    ->get();
    }

    private function getNextLevelCourses($course, $limit)
    {
        $nextLevel = $this->getNextDifficultyLevel($course->difficulty_level);
        
        return Course::where('category_id', $course->category_id)
                    ->where('difficulty_level', $nextLevel)
                    ->where('id', '!=', $course->id)
                    ->where('status', 'published')
                    ->withCount('enrollments')
                    ->withAvg('reviews', 'rating')
                    ->orderBy('reviews_avg_rating', 'desc')
                    ->limit($limit)
                    ->get();
    }

    private function getNextDifficultyLevel($currentLevel)
    {
        $levels = ['beginner' => 'intermediate', 'intermediate' => 'advanced', 'advanced' => 'advanced'];
        return $levels[$currentLevel] ?? 'intermediate';
    }

    // Learning Path Recommendation Methods
    private function getBeginnerPaths($user, $limit)
    {
        return collect([
            [
                'id' => 1,
                'title' => 'Web Development Fundamentals',
                'description' => 'Start your journey in web development',
                'courses_count' => 5,
                'estimated_duration' => '8 weeks',
                'difficulty' => 'beginner'
            ],
            [
                'id' => 2,
                'title' => 'Data Science Basics',
                'description' => 'Introduction to data science and analytics',
                'courses_count' => 4,
                'estimated_duration' => '6 weeks',
                'difficulty' => 'beginner'
            ]
        ])->take($limit);
    }

    private function getSkillBuildingPaths($user, $limit)
    {
        return collect([
            [
                'id' => 3,
                'title' => 'Advanced Programming',
                'description' => 'Build advanced programming skills',
                'courses_count' => 6,
                'estimated_duration' => '10 weeks',
                'difficulty' => 'intermediate'
            ]
        ])->take($limit);
    }

    private function getCareerFocusedPaths($user, $limit)
    {
        return collect([
            [
                'id' => 4,
                'title' => 'Full Stack Developer',
                'description' => 'Complete path to become a full stack developer',
                'courses_count' => 8,
                'estimated_duration' => '12 weeks',
                'difficulty' => 'advanced'
            ]
        ])->take($limit);
    }

    private function getCompletionBasedPaths($user, $limit)
    {
        $completedCourses = $user->enrollments()->where('status', 'completed')->count();

        if ($completedCourses >= 3) {
            return collect([
                [
                    'id' => 5,
                    'title' => 'Advanced Specialization',
                    'description' => 'Specialize in your area of expertise',
                    'courses_count' => 4,
                    'estimated_duration' => '6 weeks',
                    'difficulty' => 'advanced'
                ]
            ])->take($limit);
        }

        return collect();
    }

    // Instructor Recommendation Methods
    private function getTopRatedInstructors($limit)
    {
        return User::where('role', 'instructor')
                  ->withCount('instructedCourses')
                  ->limit($limit)
                  ->get()
                  ->map(function($instructor) {
                      // Calculate average rating manually
                      $avgRating = $instructor->instructedCourses()
                                            ->join('course_reviews', 'courses.id', '=', 'course_reviews.course_id')
                                            ->avg('course_reviews.rating') ?? 0;

                      return [
                          'id' => $instructor->id,
                          'name' => $instructor->first_name . ' ' . $instructor->last_name,
                          'email' => $instructor->email,
                          'courses_count' => $instructor->instructed_courses_count,
                          'average_rating' => round($avgRating, 2),
                          'specialization' => 'Technology & Programming'
                      ];
                  })
                  ->sortByDesc('average_rating')
                  ->values();
    }

    private function getSimilarInterestInstructors($user, $limit)
    {
        $userCategories = $user->enrollments()
                             ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                             ->pluck('courses.category_id')
                             ->unique();

        if ($userCategories->isEmpty()) {
            return collect();
        }

        return User::where('role', 'instructor')
                  ->whereHas('instructedCourses', function($query) use ($userCategories) {
                      $query->whereIn('category_id', $userCategories);
                  })
                  ->withCount('instructedCourses')
                  ->limit($limit)
                  ->get()
                  ->map(function($instructor) {
                      return [
                          'id' => $instructor->id,
                          'name' => $instructor->first_name . ' ' . $instructor->last_name,
                          'email' => $instructor->email,
                          'courses_count' => $instructor->instructed_courses_count,
                          'specialization' => 'Similar Interests'
                      ];
                  });
    }

    private function getTrendingInstructors($limit)
    {
        return User::where('role', 'instructor')
                  ->whereHas('instructedCourses', function($query) {
                      $query->where('created_at', '>=', now()->subMonths(3));
                  })
                  ->withCount(['instructedCourses' => function($query) {
                      $query->where('created_at', '>=', now()->subMonths(3));
                  }])
                  ->orderBy('instructed_courses_count', 'desc')
                  ->limit($limit)
                  ->get()
                  ->map(function($instructor) {
                      return [
                          'id' => $instructor->id,
                          'name' => $instructor->first_name . ' ' . $instructor->last_name,
                          'email' => $instructor->email,
                          'recent_courses' => $instructor->instructed_courses_count,
                          'specialization' => 'Trending'
                      ];
                  });
    }

    private function getNewInstructors($limit)
    {
        return User::where('role', 'instructor')
                  ->where('created_at', '>=', now()->subMonths(6))
                  ->withCount('instructedCourses')
                  ->orderBy('created_at', 'desc')
                  ->limit($limit)
                  ->get()
                  ->map(function($instructor) {
                      return [
                          'id' => $instructor->id,
                          'name' => $instructor->first_name . ' ' . $instructor->last_name,
                          'email' => $instructor->email,
                          'courses_count' => $instructor->instructed_courses_count,
                          'joined_at' => $instructor->created_at->format('M Y'),
                          'specialization' => 'New Instructor'
                      ];
                  });
    }

    // Content Recommendation Methods
    private function getNextLessons($user, $course, $limit)
    {
        return collect([
            [
                'id' => 1,
                'title' => 'Next Lesson in Course',
                'type' => 'lesson',
                'course_title' => $course->title,
                'estimated_time' => '15 minutes'
            ]
        ])->take($limit);
    }

    private function getReviewContent($user, $course, $limit)
    {
        return collect([
            [
                'id' => 2,
                'title' => 'Review Previous Concepts',
                'type' => 'review',
                'course_title' => $course->title,
                'estimated_time' => '10 minutes'
            ]
        ])->take($limit);
    }

    private function getPracticeQuizzes($user, $course, $limit)
    {
        return collect([
            [
                'id' => 3,
                'title' => 'Practice Quiz',
                'type' => 'quiz',
                'course_title' => $course->title,
                'estimated_time' => '20 minutes'
            ]
        ])->take($limit);
    }

    private function getSupplementaryContent($user, $course, $limit)
    {
        return collect([
            [
                'id' => 4,
                'title' => 'Additional Resources',
                'type' => 'resource',
                'course_title' => $course->title,
                'estimated_time' => '5 minutes'
            ]
        ])->take($limit);
    }

    private function getContinueLearning($user, $limit)
    {
        $activeEnrollments = $user->enrollments()
                                 ->where('status', 'active')
                                 ->with('course')
                                 ->limit($limit)
                                 ->get();

        return $activeEnrollments->map(function($enrollment) {
            return [
                'id' => $enrollment->course->id,
                'title' => 'Continue: ' . $enrollment->course->title,
                'type' => 'continue',
                'progress' => $enrollment->progress,
                'estimated_time' => '30 minutes'
            ];
        });
    }

    private function getQuickReviews($user, $limit)
    {
        return collect([
            [
                'id' => 5,
                'title' => 'Quick Review Session',
                'type' => 'review',
                'estimated_time' => '10 minutes'
            ]
        ])->take($limit);
    }

    private function getNewContent($user, $limit)
    {
        return Course::where('status', 'published')
                    ->where('created_at', '>=', now()->subWeeks(2))
                    ->whereNotIn('id', $user->enrollments()->pluck('course_id'))
                    ->limit($limit)
                    ->get()
                    ->map(function($course) {
                        return [
                            'id' => $course->id,
                            'title' => $course->title,
                            'type' => 'new_course',
                            'estimated_time' => '2 hours'
                        ];
                    });
    }

    // Analytics Methods (simplified for now)
    private function getRecommendationPerformance()
    {
        return [
            'total_recommendations' => 1250,
            'clicked_recommendations' => 340,
            'enrolled_from_recommendations' => 85,
            'click_through_rate' => 27.2,
            'conversion_rate' => 6.8
        ];
    }

    private function getUserEngagementMetrics()
    {
        return [
            'active_users' => 450,
            'recommendations_per_user' => 8.5,
            'average_session_time' => '12 minutes',
            'return_rate' => 68.5
        ];
    }

    private function getAlgorithmEffectiveness()
    {
        return [
            'personalized_accuracy' => 78.5,
            'trending_accuracy' => 65.2,
            'category_based_accuracy' => 72.1,
            'overall_satisfaction' => 4.2
        ];
    }

    private function getPopularRecommendations()
    {
        return [
            ['type' => 'personalized', 'count' => 450],
            ['type' => 'trending', 'count' => 320],
            ['type' => 'category_based', 'count' => 280],
            ['type' => 'instructor_based', 'count' => 200]
        ];
    }

    private function getConversionRates()
    {
        return [
            'personalized' => 8.5,
            'trending' => 6.2,
            'category_based' => 7.1,
            'instructor_based' => 5.8,
            'similar_learners' => 9.2
        ];
    }
}
