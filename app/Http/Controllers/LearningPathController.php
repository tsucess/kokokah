<?php

namespace App\Http\Controllers;

use App\Models\LearningPath;
use App\Models\Course;
use App\Models\User;
use App\Models\LearningPathEnrollment;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class LearningPathController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:instructor,admin')->except(['index', 'show', 'enroll', 'unenroll', 'myPaths', 'pathProgress']);
    }

    /**
     * Get all learning paths (public)
     */
    public function index(Request $request)
    {
        try {
            $query = LearningPath::with(['courses', 'creator'])
                                ->where('status', 'published');

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filter by category
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            // Filter by difficulty
            if ($request->has('difficulty')) {
                $query->where('difficulty_level', $request->difficulty);
            }

            // Filter by duration
            if ($request->has('duration')) {
                switch ($request->duration) {
                    case 'short':
                        $query->where('estimated_duration', '<=', 30);
                        break;
                    case 'medium':
                        $query->whereBetween('estimated_duration', [31, 90]);
                        break;
                    case 'long':
                        $query->where('estimated_duration', '>', 90);
                        break;
                }
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            if ($sortBy === 'popularity') {
                $query->withCount('enrollments')->orderBy('enrollments_count', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }

            $paths = $query->paginate($request->get('per_page', 12));

            // Add enrollment counts and ratings
            $paths->getCollection()->transform(function ($path) {
                $pathData = $path->toArray();
                $pathData['enrollment_count'] = $path->enrollments()->count();
                $pathData['completion_count'] = $path->enrollments()->where('status', 'completed')->count();
                $pathData['average_rating'] = $path->reviews()->avg('rating');
                return $pathData;
            });

            return response()->json([
                'success' => true,
                'data' => $paths
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch learning paths: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new learning path
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|string|max:100',
                'difficulty_level' => 'required|in:beginner,intermediate,advanced',
                'estimated_duration' => 'required|integer|min:1',
                'course_ids' => 'required|array|min:2',
                'course_ids.*' => 'exists:courses,id',
                'prerequisites' => 'nullable|string',
                'learning_objectives' => 'required|array',
                'learning_objectives.*' => 'string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if user owns all courses or is admin
            $courses = Course::whereIn('id', $request->course_ids)->get();
            foreach ($courses as $course) {
                if ($course->instructor_id !== $user->id && !$user->hasRole('admin')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You can only create paths with your own courses'
                    ], 403);
                }
            }

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('learning-paths', 'public');
            }

            // Create learning path
            $path = LearningPath::create([
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'difficulty_level' => $request->difficulty_level,
                'estimated_duration' => $request->estimated_duration,
                'prerequisites' => $request->prerequisites,
                'learning_objectives' => $request->learning_objectives,
                'image_path' => $imagePath,
                'creator_id' => $user->id,
                'status' => 'draft'
            ]);

            // Attach courses with order
            foreach ($request->course_ids as $index => $courseId) {
                $path->courses()->attach($courseId, ['order' => $index + 1]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Learning path created successfully',
                'data' => $path->load(['courses', 'creator'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create learning path: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific learning path
     */
    public function show($id)
    {
        try {
            $path = LearningPath::with(['courses.instructor', 'creator', 'reviews.user'])
                              ->findOrFail($id);

            $user = Auth::user();
            $pathData = $path->toArray();

            // Add enrollment and progress data if user is authenticated
            if ($user) {
                $enrollment = LearningPathEnrollment::where('user_id', $user->id)
                                                  ->where('learning_path_id', $path->id)
                                                  ->first();

                $pathData['user_enrollment'] = $enrollment;
                $pathData['user_progress'] = $enrollment ? $this->calculatePathProgress($user, $path) : null;
            }

            // Add statistics
            $pathData['statistics'] = [
                'total_enrollments' => $path->enrollments()->count(),
                'completion_count' => $path->enrollments()->where('status', 'completed')->count(),
                'average_rating' => $path->reviews()->avg('rating'),
                'total_reviews' => $path->reviews()->count()
            ];

            // Add course progress for enrolled users
            if ($user && isset($pathData['user_enrollment'])) {
                $pathData['course_progress'] = $this->getCourseProgressInPath($user, $path);
            }

            return response()->json([
                'success' => true,
                'data' => $pathData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch learning path: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update learning path
     */
    public function update(Request $request, $id)
    {
        try {
            $path = LearningPath::findOrFail($id);
            $user = Auth::user();

            // Check permissions
            if ($path->creator_id !== $user->id && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this learning path'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'category' => 'sometimes|string|max:100',
                'difficulty_level' => 'sometimes|in:beginner,intermediate,advanced',
                'estimated_duration' => 'sometimes|integer|min:1',
                'course_ids' => 'sometimes|array|min:2',
                'course_ids.*' => 'exists:courses,id',
                'prerequisites' => 'nullable|string',
                'learning_objectives' => 'sometimes|array',
                'learning_objectives.*' => 'string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'sometimes|in:draft,published,archived'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($path->image_path) {
                    Storage::disk('public')->delete($path->image_path);
                }
                $imagePath = $request->file('image')->store('learning-paths', 'public');
                $path->image_path = $imagePath;
            }

            // Update path
            $path->update($request->only([
                'title', 'description', 'category', 'difficulty_level',
                'estimated_duration', 'prerequisites', 'learning_objectives', 'status'
            ]));

            // Update courses if provided
            if ($request->has('course_ids')) {
                $path->courses()->detach();
                foreach ($request->course_ids as $index => $courseId) {
                    $path->courses()->attach($courseId, ['order' => $index + 1]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Learning path updated successfully',
                'data' => $path->load(['courses', 'creator'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update learning path: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete learning path
     */
    public function destroy($id)
    {
        try {
            $path = LearningPath::findOrFail($id);
            $user = Auth::user();

            // Check permissions
            if ($path->creator_id !== $user->id && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this learning path'
                ], 403);
            }

            // Check if path has enrollments
            if ($path->enrollments()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete learning path with active enrollments'
                ], 400);
            }

            // Delete image
            if ($path->image_path) {
                Storage::disk('public')->delete($path->image_path);
            }

            $path->delete();

            return response()->json([
                'success' => true,
                'message' => 'Learning path deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete learning path: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enroll in learning path
     */
    public function enroll(Request $request, $id)
    {
        try {
            $path = LearningPath::findOrFail($id);
            $user = Auth::user();

            // Check if path is published
            if ($path->status !== 'published') {
                return response()->json([
                    'success' => false,
                    'message' => 'Learning path is not available for enrollment'
                ], 400);
            }

            // Check if already enrolled
            $existingEnrollment = LearningPathEnrollment::where('user_id', $user->id)
                                                       ->where('learning_path_id', $path->id)
                                                       ->first();

            if ($existingEnrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Already enrolled in this learning path'
                ], 400);
            }

            // Create enrollment
            $enrollment = LearningPathEnrollment::create([
                'user_id' => $user->id,
                'learning_path_id' => $path->id,
                'enrolled_at' => now(),
                'status' => 'active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Successfully enrolled in learning path',
                'data' => $enrollment
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to enroll in learning path: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unenroll from learning path
     */
    public function unenroll($id)
    {
        try {
            $path = LearningPath::findOrFail($id);
            $user = Auth::user();

            $enrollment = LearningPathEnrollment::where('user_id', $user->id)
                                               ->where('learning_path_id', $path->id)
                                               ->first();

            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enrolled in this learning path'
                ], 400);
            }

            $enrollment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Successfully unenrolled from learning path'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unenroll from learning path: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's enrolled learning paths
     */
    public function myPaths()
    {
        try {
            $user = Auth::user();

            $enrollments = LearningPathEnrollment::with(['learningPath.courses', 'learningPath.creator'])
                                                ->where('user_id', $user->id)
                                                ->get();

            $pathsData = $enrollments->map(function($enrollment) use ($user) {
                $path = $enrollment->learningPath;
                $pathData = $path->toArray();
                $pathData['enrollment'] = $enrollment->toArray();
                $pathData['progress'] = $this->calculatePathProgress($user, $path);
                $pathData['next_course'] = $this->getNextCourseInPath($user, $path);
                return $pathData;
            });

            return response()->json([
                'success' => true,
                'data' => $pathsData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch enrolled paths: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get learning path progress
     */
    public function pathProgress($id)
    {
        try {
            $path = LearningPath::with('courses')->findOrFail($id);
            $user = Auth::user();

            // Check if enrolled
            $enrollment = LearningPathEnrollment::where('user_id', $user->id)
                                               ->where('learning_path_id', $path->id)
                                               ->first();

            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enrolled in this learning path'
                ], 400);
            }

            $progress = $this->calculateDetailedPathProgress($user, $path);

            return response()->json([
                'success' => true,
                'data' => [
                    'learning_path' => $path,
                    'enrollment' => $enrollment,
                    'progress' => $progress
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch path progress: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get learning path analytics (for creators)
     */
    public function analytics($id)
    {
        try {
            $path = LearningPath::findOrFail($id);
            $user = Auth::user();

            // Check permissions
            if ($path->creator_id !== $user->id && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view analytics for this learning path'
                ], 403);
            }

            $enrollments = $path->enrollments()->with('user')->get();

            $analytics = [
                'overview' => [
                    'total_enrollments' => $enrollments->count(),
                    'active_enrollments' => $enrollments->where('status', 'active')->count(),
                    'completed_enrollments' => $enrollments->where('status', 'completed')->count(),
                    'completion_rate' => $this->calculatePathCompletionRate($path),
                    'average_completion_time' => $this->calculateAveragePathCompletionTime($enrollments)
                ],
                'engagement' => [
                    'course_completion_rates' => $this->getCourseCompletionRatesInPath($path),
                    'drop_off_points' => $this->getPathDropOffPoints($path),
                    'most_challenging_courses' => $this->getMostChallengingCourses($path)
                ],
                'learner_demographics' => [
                    'enrollment_trend' => $this->getPathEnrollmentTrend($path),
                    'completion_trend' => $this->getPathCompletionTrend($path),
                    'learner_distribution' => $this->getLearnerDistribution($enrollments)
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch path analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Publish learning path
     */
    public function publish($id)
    {
        try {
            $path = LearningPath::findOrFail($id);
            $user = Auth::user();

            // Check permissions
            if ($path->creator_id !== $user->id && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to publish this learning path'
                ], 403);
            }

            // Validate path is ready for publishing
            if ($path->courses()->count() < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Learning path must have at least 2 courses to be published'
                ], 400);
            }

            $path->update(['status' => 'published']);

            return response()->json([
                'success' => true,
                'message' => 'Learning path published successfully',
                'data' => $path
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to publish learning path: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unpublish learning path
     */
    public function unpublish($id)
    {
        try {
            $path = LearningPath::findOrFail($id);
            $user = Auth::user();

            // Check permissions
            if ($path->creator_id !== $user->id && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to unpublish this learning path'
                ], 403);
            }

            $path->update(['status' => 'draft']);

            return response()->json([
                'success' => true,
                'message' => 'Learning path unpublished successfully',
                'data' => $path
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unpublish learning path: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper methods
     */
    private function calculatePathProgress($user, $path)
    {
        $courses = $path->courses;
        $totalCourses = $courses->count();
        $completedCourses = 0;

        foreach ($courses as $course) {
            $enrollment = Enrollment::where('user_id', $user->id)
                                  ->where('course_id', $course->id)
                                  ->where('status', 'completed')
                                  ->first();
            if ($enrollment) {
                $completedCourses++;
            }
        }

        return [
            'total_courses' => $totalCourses,
            'completed_courses' => $completedCourses,
            'percentage' => $totalCourses > 0 ? round(($completedCourses / $totalCourses) * 100, 2) : 0
        ];
    }

    private function getCourseProgressInPath($user, $path)
    {
        return $path->courses->map(function($course) use ($user) {
            $enrollment = Enrollment::where('user_id', $user->id)
                                  ->where('course_id', $course->id)
                                  ->first();

            return [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'enrollment_status' => $enrollment ? $enrollment->status : 'not_enrolled',
                'progress_percentage' => $enrollment ? $enrollment->progress_percentage : 0,
                'completed_at' => $enrollment ? $enrollment->completed_at : null
            ];
        });
    }

    private function getNextCourseInPath($user, $path)
    {
        $courses = $path->courses()->orderBy('pivot.order')->get();

        foreach ($courses as $course) {
            $enrollment = Enrollment::where('user_id', $user->id)
                                  ->where('course_id', $course->id)
                                  ->first();

            if (!$enrollment || $enrollment->status !== 'completed') {
                return [
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'order' => $course->pivot->order
                ];
            }
        }

        return null; // All courses completed
    }

    private function calculateDetailedPathProgress($user, $path)
    {
        $courses = $path->courses()->orderBy('pivot.order')->get();
        $courseProgress = [];
        $totalLessons = 0;
        $completedLessons = 0;

        foreach ($courses as $course) {
            $enrollment = Enrollment::where('user_id', $user->id)
                                  ->where('course_id', $course->id)
                                  ->first();

            $courseLessons = $course->lessons()->count();
            $courseCompletedLessons = 0;

            if ($enrollment) {
                $courseCompletedLessons = \App\Models\LessonCompletion::where('user_id', $user->id)
                                                                    ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                                                                    ->count();
            }

            $totalLessons += $courseLessons;
            $completedLessons += $courseCompletedLessons;

            $courseProgress[] = [
                'course' => $course,
                'enrollment' => $enrollment,
                'lessons_total' => $courseLessons,
                'lessons_completed' => $courseCompletedLessons,
                'progress_percentage' => $courseLessons > 0 ? round(($courseCompletedLessons / $courseLessons) * 100, 2) : 0
            ];
        }

        return [
            'overall_progress' => $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 2) : 0,
            'total_lessons' => $totalLessons,
            'completed_lessons' => $completedLessons,
            'course_progress' => $courseProgress,
            'estimated_time_remaining' => $this->calculateEstimatedTimeRemaining($user, $path)
        ];
    }

    private function calculatePathCompletionRate($path)
    {
        $totalEnrollments = $path->enrollments()->count();
        $completedEnrollments = $path->enrollments()->where('status', 'completed')->count();

        return $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 2) : 0;
    }

    private function calculateAveragePathCompletionTime($enrollments)
    {
        $completed = $enrollments->where('status', 'completed')->whereNotNull('completed_at');

        if ($completed->count() === 0) return null;

        $totalDays = $completed->sum(function($enrollment) {
            return $enrollment->enrolled_at->diffInDays($enrollment->completed_at);
        });

        return round($totalDays / $completed->count(), 1) . ' days';
    }

    private function getCourseCompletionRatesInPath($path)
    {
        return $path->courses->map(function($course) {
            $totalEnrollments = Enrollment::where('course_id', $course->id)->count();
            $completedEnrollments = Enrollment::where('course_id', $course->id)
                                            ->where('status', 'completed')
                                            ->count();

            return [
                'course_title' => $course->title,
                'completion_rate' => $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 2) : 0
            ];
        });
    }

    private function getPathDropOffPoints($path)
    {
        // Mock implementation - would analyze actual drop-off patterns
        return $path->courses->map(function($course, $index) {
            return [
                'course_order' => $index + 1,
                'course_title' => $course->title,
                'drop_off_rate' => rand(5, 25) // Mock data
            ];
        });
    }

    private function getMostChallengingCourses($path)
    {
        // Mock implementation - would analyze completion times and ratings
        return $path->courses->map(function($course) {
            return [
                'course_title' => $course->title,
                'difficulty_score' => rand(1, 10),
                'average_completion_time' => rand(10, 30) . ' days'
            ];
        })->sortByDesc('difficulty_score')->take(3)->values();
    }

    private function getPathEnrollmentTrend($path)
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $data[] = [
                'month' => $date->format('M Y'),
                'enrollments' => $path->enrollments()
                                    ->whereYear('enrolled_at', $date->year)
                                    ->whereMonth('enrolled_at', $date->month)
                                    ->count()
            ];
        }
        return $data;
    }

    private function getPathCompletionTrend($path)
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $data[] = [
                'month' => $date->format('M Y'),
                'completions' => $path->enrollments()
                                    ->where('status', 'completed')
                                    ->whereYear('completed_at', $date->year)
                                    ->whereMonth('completed_at', $date->month)
                                    ->count()
            ];
        }
        return $data;
    }

    private function getLearnerDistribution($enrollments)
    {
        return [
            'by_status' => [
                'active' => $enrollments->where('status', 'active')->count(),
                'completed' => $enrollments->where('status', 'completed')->count(),
                'paused' => $enrollments->where('status', 'paused')->count()
            ],
            'by_progress' => [
                'just_started' => $enrollments->filter(function($e) { return $this->getEnrollmentProgress($e) < 25; })->count(),
                'in_progress' => $enrollments->filter(function($e) { return $this->getEnrollmentProgress($e) >= 25 && $this->getEnrollmentProgress($e) < 75; })->count(),
                'nearly_complete' => $enrollments->filter(function($e) { return $this->getEnrollmentProgress($e) >= 75 && $this->getEnrollmentProgress($e) < 100; })->count(),
                'completed' => $enrollments->filter(function($e) { return $this->getEnrollmentProgress($e) == 100; })->count()
            ]
        ];
    }

    private function getEnrollmentProgress($enrollment)
    {
        // Mock implementation - would calculate actual progress
        return rand(0, 100);
    }

    private function calculateEstimatedTimeRemaining($user, $path)
    {
        // Mock implementation - would calculate based on remaining content and user's pace
        return rand(5, 30) . ' days';
    }
}
