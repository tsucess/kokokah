<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Assignment;
use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    // Note: Middleware is applied at route level in Laravel 12
    // See routes/api.php for auth:sanctum middleware configuration

    /**
     * Global search across all content
     */
    public function globalSearch(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'q' => 'required|string|min:2|max:255',
                'type' => 'nullable|in:all,courses,users,lessons,quizzes,assignments,forums',
                'limit' => 'nullable|integer|min:1|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = $request->q;
            $type = $request->get('type', 'all');
            $limit = $request->get('limit', 20);

            $results = [];

            if ($type === 'all' || $type === 'courses') {
                $results['courses'] = $this->searchCourses($query, $limit);
            }

            if ($type === 'all' || $type === 'users') {
                $results['users'] = $this->searchUsers($query, $limit);
            }

            if ($type === 'all' || $type === 'lessons') {
                $results['lessons'] = $this->searchLessons($query, $limit);
            }

            if ($type === 'all' || $type === 'quizzes') {
                $results['quizzes'] = $this->searchQuizzes($query, $limit);
            }

            if ($type === 'all' || $type === 'assignments') {
                $results['assignments'] = $this->searchAssignments($query, $limit);
            }

            if ($type === 'all' || $type === 'forums') {
                $results['forums'] = $this->searchForums($query, $limit);
            }

            // Log search query for analytics
            $this->logSearchQuery($query, $type);

            return response()->json([
                'success' => true,
                'data' => [
                    'query' => $query,
                    'results' => $results,
                    'total_results' => $this->countTotalResults($results)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Advanced course search with filters
     */
    public function courseSearch(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'q' => 'nullable|string|max:255',
                'category_id' => 'nullable|exists:categories,id',
                'instructor_id' => 'nullable|exists:users,id',
                'difficulty_level' => 'nullable|in:beginner,intermediate,advanced',
                'price_min' => 'nullable|numeric|min:0',
                'price_max' => 'nullable|numeric|min:0',
                'rating_min' => 'nullable|numeric|min:1|max:5',
                'duration_min' => 'nullable|integer|min:1',
                'duration_max' => 'nullable|integer|min:1',
                'sort_by' => 'nullable|in:relevance,price_asc,price_desc,rating,newest,popular',
                'per_page' => 'nullable|integer|min:1|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = Course::where('status', 'published');

            // Text search
            if ($request->q) {
                $searchTerm = $request->q;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%")
                      ->orWhere('tags', 'like', "%{$searchTerm}%");
                });
            }

            // Apply filters
            if ($request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->instructor_id) {
                $query->where('instructor_id', $request->instructor_id);
            }

            if ($request->difficulty_level) {
                $query->where('difficulty_level', $request->difficulty_level);
            }

            if ($request->price_min !== null) {
                $query->where('price', '>=', $request->price_min);
            }

            if ($request->price_max !== null) {
                $query->where('price', '<=', $request->price_max);
            }

            if ($request->rating_min) {
                $query->whereHas('reviews', function($q) use ($request) {
                    $q->havingRaw('AVG(rating) >= ?', [$request->rating_min]);
                });
            }

            if ($request->duration_min) {
                $query->where('duration_hours', '>=', $request->duration_min);
            }

            if ($request->duration_max) {
                $query->where('duration_hours', '<=', $request->duration_max);
            }

            // Apply sorting
            $this->applyCourseSort($query, $request->get('sort_by', 'relevance'));

            $courses = $query->with(['instructor', 'category'])
                           ->withCount(['enrollments', 'reviews'])
                           ->withAvg('reviews', 'rating')
                           ->paginate($request->get('per_page', 20));

            return response()->json([
                'success' => true,
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Course search failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search users (instructors and students)
     */
    public function userSearch(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'q' => 'required|string|min:2|max:255',
                'role' => 'nullable|in:student,instructor,admin',
                'per_page' => 'nullable|integer|min:1|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $searchTerm = $request->q;
            $query = User::where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('contact', 'like', "%{$searchTerm}%")
                  ->orWhere('address', 'like', "%{$searchTerm}%");
            });

            if ($request->role) {
                $query->where('role', $request->role);
            }

            $users = $query->select(['id', 'first_name', 'last_name', 'email', 'role', 'contact', 'gender', 'is_active', 'profile_photo'])
                          ->with('level:id,name,type')
                          ->paginate($request->get('per_page', 20));

            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User search failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search within course content
     */
    public function contentSearch(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'q' => 'required|string|min:2|max:255',
                'course_id' => 'required|exists:courses,id',
                'content_type' => 'nullable|in:all,lessons,quizzes,assignments',
                'per_page' => 'nullable|integer|min:1|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $course = Course::findOrFail($request->course_id);

            // Check if user has access to the course
            if (!$this->hasAccessToCourse($user, $course)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have access to this course'
                ], 403);
            }

            $searchTerm = $request->q;
            $contentType = $request->get('content_type', 'all');
            $results = [];

            if ($contentType === 'all' || $contentType === 'lessons') {
                $results['lessons'] = $this->searchCourseLessons($course->id, $searchTerm);
            }

            if ($contentType === 'all' || $contentType === 'quizzes') {
                $results['quizzes'] = $this->searchCourseQuizzes($course->id, $searchTerm);
            }

            if ($contentType === 'all' || $contentType === 'assignments') {
                $results['assignments'] = $this->searchCourseAssignments($course->id, $searchTerm);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'course' => $course,
                    'query' => $searchTerm,
                    'results' => $results
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Content search failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get search suggestions/autocomplete
     */
    public function getSuggestions(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'q' => 'required|string|min:1|max:100',
                'type' => 'nullable|in:courses,users,topics',
                'limit' => 'nullable|integer|min:1|max:20'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = $request->q;
            $type = $request->get('type', 'courses');
            $limit = $request->get('limit', 10);

            $suggestions = [];

            switch ($type) {
                case 'courses':
                    $suggestions = Course::where('title', 'like', "%{$query}%")
                                        ->where('status', 'published')
                                        ->select('id', 'title')
                                        ->limit($limit)
                                        ->get();
                    break;

                case 'users':
                    $suggestions = User::where(function($q) use ($query) {
                                        $q->where('first_name', 'like', "%{$query}%")
                                          ->orWhere('last_name', 'like', "%{$query}%");
                                    })
                                    ->select('id', 'first_name', 'last_name', 'role')
                                    ->limit($limit)
                                    ->get();
                    break;

                case 'topics':
                    $suggestions = $this->getTopicSuggestions($query, $limit);
                    break;
            }

            return response()->json([
                'success' => true,
                'data' => $suggestions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get suggestions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available search filters
     */
    public function getFilters()
    {
        try {
            $filters = [
                'categories' => DB::table('categories')->select('id', 'title as name')->get(),
                'difficulty_levels' => ['beginner', 'intermediate', 'advanced'],
                'price_ranges' => [
                    ['label' => 'Free', 'min' => 0, 'max' => 0],
                    ['label' => 'Under $50', 'min' => 0, 'max' => 50],
                    ['label' => '$50 - $100', 'min' => 50, 'max' => 100],
                    ['label' => '$100 - $200', 'min' => 100, 'max' => 200],
                    ['label' => 'Over $200', 'min' => 200, 'max' => null]
                ],
                'duration_ranges' => [
                    ['label' => 'Under 5 hours', 'min' => 0, 'max' => 5],
                    ['label' => '5-10 hours', 'min' => 5, 'max' => 10],
                    ['label' => '10-20 hours', 'min' => 10, 'max' => 20],
                    ['label' => 'Over 20 hours', 'min' => 20, 'max' => null]
                ],
                'rating_options' => [
                    ['label' => '4+ stars', 'value' => 4],
                    ['label' => '3+ stars', 'value' => 3],
                    ['label' => '2+ stars', 'value' => 2],
                    ['label' => '1+ stars', 'value' => 1]
                ],
                'sort_options' => [
                    ['value' => 'relevance', 'label' => 'Most Relevant'],
                    ['value' => 'newest', 'label' => 'Newest First'],
                    ['value' => 'popular', 'label' => 'Most Popular'],
                    ['value' => 'rating', 'label' => 'Highest Rated'],
                    ['value' => 'price_asc', 'label' => 'Price: Low to High'],
                    ['value' => 'price_desc', 'label' => 'Price: High to Low']
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $filters
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get filters: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper methods
     */
    private function searchCourses($query, $limit)
    {
        return Course::where('status', 'published')
                    ->where(function($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%")
                          ->orWhere('tags', 'like', "%{$query}%");
                    })
                    ->with(['instructor', 'category'])
                    ->withCount('enrollments')
                    ->withAvg('reviews', 'rating')
                    ->limit($limit)
                    ->get();
    }

    private function searchUsers($query, $limit)
    {
        return User::where(function($q) use ($query) {
                        $q->where('first_name', 'like', "%{$query}%")
                          ->orWhere('last_name', 'like', "%{$query}%")
                          ->orWhere('email', 'like', "%{$query}%");
                    })
                    ->select(['id', 'first_name', 'last_name', 'email', 'role', 'profile_photo'])
                    ->limit($limit)
                    ->get();
    }

    private function searchLessons($query, $limit)
    {
        return Lesson::where(function($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('content', 'like', "%{$query}%");
                    })
                    ->with('course')
                    ->limit($limit)
                    ->get();
    }

    private function searchQuizzes($query, $limit)
    {
        return Quiz::where(function($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%");
                    })
                    ->with('course')
                    ->limit($limit)
                    ->get();
    }

    private function searchAssignments($query, $limit)
    {
        return Assignment::where(function($q) use ($query) {
                            $q->where('title', 'like', "%{$query}%")
                              ->orWhere('description', 'like', "%{$query}%");
                        })
                        ->with('course')
                        ->limit($limit)
                        ->get();
    }

    private function searchForums($query, $limit)
    {
        return Forum::where(function($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%");
                    })
                    ->with(['course'])
                    ->limit($limit)
                    ->get();
    }

    private function searchCourseLessons($courseId, $query)
    {
        return Lesson::where('course_id', $courseId)
                    ->where(function($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('content', 'like', "%{$query}%");
                    })
                    ->get();
    }

    private function searchCourseQuizzes($courseId, $query)
    {
        return Quiz::whereHas('lesson', function($q) use ($courseId) {
                      $q->where('course_id', $courseId);
                  })
                  ->where(function($q) use ($query) {
                      $q->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                  })
                  ->get();
    }

    private function searchCourseAssignments($courseId, $query)
    {
        return Assignment::where('course_id', $courseId)
                        ->where(function($q) use ($query) {
                            $q->where('title', 'like', "%{$query}%")
                              ->orWhere('description', 'like', "%{$query}%");
                        })
                        ->get();
    }

    private function applyCourseSort($query, $sortBy)
    {
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->withCount('enrollments')->orderBy('enrollments_count', 'desc');
                break;
            case 'relevance':
            default:
                // Default relevance sorting (could be enhanced with search scoring)
                $query->orderBy('updated_at', 'desc');
                break;
        }
    }

    private function hasAccessToCourse($user, $course)
    {
        // Check if user is enrolled or is the instructor
        return $user->enrollments()->where('course_id', $course->id)->exists() ||
               $course->instructor_id === $user->id ||
               $user->hasRole('admin');
    }

    private function countTotalResults($results)
    {
        $total = 0;
        foreach ($results as $resultSet) {
            $total += count($resultSet);
        }
        return $total;
    }

    private function getTopicSuggestions($query, $limit)
    {
        // Mock implementation - would get from a topics/tags table
        $topics = [
            'Web Development', 'Mobile Development', 'Data Science', 'Machine Learning',
            'Artificial Intelligence', 'Cybersecurity', 'Cloud Computing', 'DevOps',
            'UI/UX Design', 'Digital Marketing', 'Business Analytics', 'Project Management'
        ];

        return collect($topics)
            ->filter(function($topic) use ($query) {
                return stripos($topic, $query) !== false;
            })
            ->take($limit)
            ->values();
    }

    private function logSearchQuery($query, $type)
    {
        // Mock implementation - would log to search_logs table for analytics
        \Log::info('Search query logged', [
            'query' => $query,
            'type' => $type,
            'user_id' => Auth::id(),
            'timestamp' => now()
        ]);
    }
}
