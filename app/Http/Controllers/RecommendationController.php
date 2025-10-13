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
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

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

    // Additional helper methods would continue here...
    // Due to length constraints, implementing remaining methods in next chunk
}
