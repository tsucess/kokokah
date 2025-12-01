<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CurriculumCategory;
use App\Models\CourseCategory;
use App\Models\Level;
use App\Models\Enrollment;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
        // Middleware is applied at route level in routes/api.php
    }

    /**
     * Display a listing of courses
     */
    public function index(Request $request)
    {
        $query = Course::with(['category', 'instructor', 'level', 'term'])
                      ->where('status', 'published');

        // Apply filters
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        if ($request->has('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->has('price_range')) {
            $range = explode('-', $request->price_range);
            if (count($range) == 2) {
                $query->whereBetween('price', [$range[0], $range[1]]);
            }
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $courses = $query->paginate($request->get('per_page', 12));

        return response()->json([
            'success' => true,
            'data' => $courses,
            'filters' => [
                'categories' => Category::all(),
                'levels' => Level::all(),
                'difficulties' => ['beginner', 'intermediate', 'advanced']
            ]
        ]);
    }

    /**
     * Store a newly created course
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'level_id' => 'nullable|exists:levels,id',
            'term_id' => 'nullable|exists:terms,id',
            'price' => 'required|numeric|min:0',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'duration_hours' => 'nullable|integer|min:1',
            'max_students' => 'nullable|integer|min:1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:5048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $courseData = $request->except(['thumbnail']);
            $courseData['instructor_id'] = Auth::id();
            $courseData['status'] = 'draft';

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('course-thumbnails', 'public');
                $courseData['thumbnail'] = $thumbnailPath;
            }

            $course = Course::create($courseData);

            return response()->json([
                'success' => true,
                'message' => 'Course created successfully',
                'data' => $course->load(['category', 'instructor', 'level', 'term'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create course: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified course
     */
    public function show($id)
    {
        try {
            $course = Course::with([
                'category', 
                'instructor', 
                'level', 
                'term', 
                'lessons' => function($query) {
                    $query->orderBy('order');
                },
                'reviews.user',
                'tags'
            ])->findOrFail($id);

            $user = Auth::user();
            $isEnrolled = false;
            $enrollment = null;

            if ($user) {
                $enrollment = Enrollment::where('user_id', $user->id)
                                      ->where('course_id', $course->id)
                                      ->first();
                $isEnrolled = $enrollment !== null;
            }

            $courseData = $course->toArray();
            $courseData['is_enrolled'] = $isEnrolled;
            $courseData['enrollment'] = $enrollment;
            $courseData['total_lessons'] = $course->lessons->count();
            $courseData['total_duration'] = $course->lessons->sum('duration_minutes');
            $courseData['average_rating'] = $course->reviews->avg('rating');
            $courseData['total_reviews'] = $course->reviews->count();
            $courseData['total_students'] = $course->enrollments()->count();

            return response()->json([
                'success' => true,
                'data' => $courseData
            ]);
        } catch (\Exception) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }
    }

    /**
     * Update the specified course
     */
    public function update(Request $request, $id)
    {
        try {
            $course = Course::findOrFail($id);

            // Check if user owns the course or is admin
            if ($course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this course'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'category_id' => 'sometimes|exists:categories,id',
                'level_id' => 'nullable|exists:levels,id',
                'term_id' => 'nullable|exists:terms,id',
                'price' => 'sometimes|numeric|min:0',
                'difficulty' => 'sometimes|in:beginner,intermediate,advanced',
                'duration_hours' => 'nullable|integer|min:1',
                'max_students' => 'nullable|integer|min:1',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:5048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = $request->except(['thumbnail']);

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail
                if ($course->thumbnail) {
                    Storage::disk('public')->delete($course->thumbnail);
                }
                $thumbnailPath = $request->file('thumbnail')->store('course-thumbnails', 'public');
                $updateData['thumbnail'] = $thumbnailPath;
            }

            $course->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Course updated successfully',
                'data' => $course->load(['category', 'instructor', 'level', 'term'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update course: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified course
     */
    public function destroy($id)
    {
        try {
            $course = Course::findOrFail($id);

            // Check if user owns the course or is admin
            if ($course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this course'
                ], 403);
            }

            // Check if course has enrollments
            if ($course->enrollments()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete course with active enrollments'
                ], 400);
            }

            // Delete thumbnail
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            $course->delete();

            return response()->json([
                'success' => true,
                'message' => 'Course deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete course: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search courses
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|min:2',
            'category_id' => 'nullable|exists:categories,id',
            'level_id' => 'nullable|exists:levels,id',
            'difficulty' => 'nullable|in:beginner,intermediate,advanced',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'sort_by' => 'nullable|in:title,price,created_at,rating',
            'sort_order' => 'nullable|in:asc,desc'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Course::with(['category', 'instructor', 'level'])
                      ->where('status', 'published');

        // Search in title and description
        $searchTerm = $request->q;
        $query->where(function($q) use ($searchTerm) {
            $q->where('title', 'LIKE', "%{$searchTerm}%")
              ->orWhere('description', 'LIKE', "%{$searchTerm}%");
        });

        // Apply filters
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        if ($request->has('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'rating') {
            $query->withAvg('reviews', 'rating')
                  ->orderBy('reviews_avg_rating', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $courses = $query->paginate($request->get('per_page', 12));

        return response()->json([
            'success' => true,
            'data' => $courses,
            'search_term' => $searchTerm
        ]);
    }

    /**
     * Get featured courses
     */
    public function featured()
    {
        $courses = Course::with(['category', 'instructor', 'level'])
                        ->where('status', 'published')
                        ->where('is_featured', true)
                        ->orderBy('created_at', 'desc')
                        ->limit(8)
                        ->get();

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    /**
     * Get popular courses
     */
    public function popular()
    {
        $courses = Course::with(['category', 'instructor', 'level'])
                        ->where('status', 'published')
                        ->withCount('enrollments')
                        ->orderBy('enrollments_count', 'desc')
                        ->limit(8)
                        ->get();

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    /**
     * Get user's enrolled courses
     */
    public function myCourses()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Get enrollments and manually check for valid courses
            $enrollments = Enrollment::where('user_id', $user->id)
                                    ->orderBy('enrolled_at', 'desc')
                                    ->get();

            $validEnrollments = [];

            foreach ($enrollments as $enrollment) {
                // Check if course exists before loading relationships
                $course = \App\Models\Course::with(['category', 'instructor', 'level'])
                                          ->find($enrollment->course_id);

                if ($course) {
                    $enrollmentData = $enrollment->toArray();
                    $enrollmentData['course'] = $course->toArray();
                    $validEnrollments[] = $enrollmentData;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $validEnrollments,
                'total' => count($validEnrollments)
            ]);
        } catch (\Exception $e) {
            \Log::error('My Courses Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch courses',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Enroll in a course
     */
    public function enroll($id)
    {
        try {
            $user = Auth::user();
            $course = Course::findOrFail($id);

            // Check if already enrolled
            $existingEnrollment = Enrollment::where('user_id', $user->id)
                                          ->where('course_id', $course->id)
                                          ->first();

            if ($existingEnrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Already enrolled in this course'
                ], 400);
            }

            // Check if course is published
            if ($course->status !== 'published') {
                return response()->json([
                    'success' => false,
                    'message' => 'Course is not available for enrollment'
                ], 400);
            }

            // Check max students limit
            if ($course->max_students && $course->enrollments()->count() >= $course->max_students) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course is full'
                ], 400);
            }

            // Use wallet to purchase course
            $transaction = $this->walletService->purchaseCourse($user, $course);

            return response()->json([
                'success' => true,
                'message' => 'Successfully enrolled in course',
                'data' => [
                    'enrollment' => $transaction['enrollment'],
                    'transaction' => $transaction['transaction'],
                    'new_balance' => $user->wallet->balance
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Enrollment failed: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Unenroll from a course
     */
    public function unenroll($id)
    {
        try {
            $user = Auth::user();
            $enrollment = Enrollment::where('user_id', $user->id)
                                  ->where('course_id', $id)
                                  ->first();

            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enrolled in this course'
                ], 400);
            }

            // Check if course has been started (has progress)
            if ($enrollment->progress > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot unenroll from a course that has been started'
                ], 400);
            }

            $enrollment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Successfully unenrolled from course'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unenrollment failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course students (instructor only)
     */
    public function students($id)
    {
        try {
            $course = Course::findOrFail($id);

            // Check if user owns the course or is admin
            if ($course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view course students'
                ], 403);
            }

            $enrollments = Enrollment::with(['user'])
                                   ->where('course_id', $course->id)
                                   ->orderBy('enrolled_at', 'desc')
                                   ->get();

            return response()->json([
                'success' => true,
                'data' => $enrollments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch students: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course analytics (instructor only)
     */
    public function analytics($id)
    {
        try {
            $course = Course::findOrFail($id);

            // Check if user owns the course or is admin
            if ($course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view course analytics'
                ], 403);
            }

            $analytics = [
                'total_enrollments' => $course->enrollments()->count(),
                'active_students' => $course->enrollments()->where('status', 'active')->count(),
                'completed_students' => $course->enrollments()->where('status', 'completed')->count(),
                'average_progress' => $course->enrollments()->avg('progress'),
                'total_revenue' => $course->enrollments()->sum('amount_paid'),
                'average_rating' => $course->reviews()->avg('rating'),
                'total_reviews' => $course->reviews()->count(),
                'completion_rate' => $course->enrollments()->count() > 0
                    ? ($course->enrollments()->where('status', 'completed')->count() / $course->enrollments()->count()) * 100
                    : 0
            ];

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Publish a course
     */
    public function publish($id)
    {
        try {
            $course = Course::findOrFail($id);

            // Check if user owns the course or is admin
            if ($course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to publish this course'
                ], 403);
            }

            // Validate course has required content
            if ($course->lessons()->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course must have at least one lesson to be published'
                ], 400);
            }

            $course->update([
                'status' => 'published',
                'published_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course published successfully',
                'data' => $course
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to publish course: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unpublish a course
     */
    public function unpublish($id)
    {
        try {
            $course = Course::findOrFail($id);

            // Check if user owns the course or is admin
            if ($course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to unpublish this course'
                ], 403);
            }

            $course->update(['status' => 'draft']);

            return response()->json([
                'success' => true,
                'message' => 'Course unpublished successfully',
                'data' => $course
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unpublish course: ' . $e->getMessage()
            ], 500);
        }
    }
}
