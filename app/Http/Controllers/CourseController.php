<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CurriculumCategory;
use App\Models\CourseCategory;
use App\Models\Level;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CourseController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /*------------------------------------------
    | Helper Methods
    ------------------------------------------*/

    private function success($data = [], $message = 'OK', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data
        ], $code);
    }

    private function error($message, $code = 400, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors
        ], $code);
    }

    private function validateRequest(Request $request, array $rules)
    {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error('Validation failed', 422, $validator->errors());
        }

        return null;
    }

    private function userCanModify(Course $course)
    {
        return $course->instructor_id == Auth::id() || Auth::user()->hasAnyRole(['admin', 'superadmin']);
    }

    private function uploadThumbnail(Request $request, $existing = null)
    {
        if (!$request->hasFile('thumbnail')) return $existing;

        if ($existing) {
            Storage::disk('public')->delete($existing);
        }

        return $request->file('thumbnail')->store('course-thumbnails', 'public');
    }

    /*------------------------------------------
    | List Courses
    ------------------------------------------*/

    // public function index(Request $request)
    // {
    //     $query = Course::with(['courseCategory', 'curriculumCategory', 'instructor', 'level', 'term'])
    //                    ->where('status', 'published');

    //     // Dynamic filtering
    //     $filters = [
    //         'curriculum_category_id',
    //         'course_category_id',
    //         'level_id',
    //         'difficulty'
    //     ];

    //     foreach ($filters as $filter) {
    //         if ($request->filled($filter)) {
    //             $query->where($filter, $request->$filter);
    //         }
    //     }

    //     if ($request->has('price_range')) {
    //         [$min, $max] = explode('-', $request->price_range);
    //         $query->whereBetween('price', [(float)$min, (float)$max]);
    //     }

    //     if ($request->filled('search')) {
    //         $s = $request->search;
    //         $query->where(fn($q) =>
    //             $q->where('title', 'LIKE', "%$s%")
    //               ->orWhere('description', 'LIKE', "%$s%")
    //         );
    //     }

    //     $query->orderBy($request->get('sort_by', 'created_at'), $request->get('sort_order', 'desc'));

    //     return $this->success([
    //         'courses' => $query->paginate($request->get('per_page', 12)),
    //         'filters' => [
    //             'curriculumCategories' => CurriculumCategory::all(),
    //             'courseCategories'     => CourseCategory::all(),
    //             'levels'               => Level::all(),
    //             'difficulties'         => ['beginner', 'intermediate', 'advanced']
    //         ]
    //     ]);
    // }
   public function index(Request $request)
    {
        $user = auth('sanctum')->user(); // Get authenticated user
        $userRole = $user ? $user->role : null;

        $query = Course::with(['courseCategory', 'curriculumCategory', 'instructor', 'level', 'term']);

        // Only show published courses if user is not admin or instructor
        if (!$user || !in_array($userRole, ['admin', 'instructor'])) {
            $query->where('status', 'published');
        }

        // Dynamic filtering
        $filters = [
            'curriculum_category_id',
            'course_category_id',
            'level_id',
            'difficulty'
        ];

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->$filter);
            }
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) =>
                $q->where('title', 'LIKE', "%$s%")
                ->orWhere('description', 'LIKE', "%$s%")
            );
        }

        $query->orderBy($request->get('sort_by', 'created_at'), $request->get('sort_order', 'desc'));

        // Get paginated courses
        $courses = $query->paginate($request->get('per_page', 12));

        // Get total students in system for progress calculation
        $totalStudentsInSystem = User::where('role', 'student')->count();

        // Transform courses to include enrollment count and average rating
        $courses->getCollection()->transform(function ($course) use ($totalStudentsInSystem) {
            $courseData = $course->toArray();
            $enrollmentCount = $course->enrollments()->count();

            // Calculate progress as (enrollments / total_students) * 100
            $progress = $totalStudentsInSystem > 0
                ? round(($enrollmentCount / $totalStudentsInSystem) * 100, 2)
                : 0;

            $courseData['enrollment_count'] = $enrollmentCount;
            $courseData['progress'] = $progress;
            $courseData['average_rating'] = round($course->reviews()->avg('rating') ?? 0, 1);

            return $courseData;
        });

        return $this->success([
            'courses' => $courses,
            'filters' => [
                'curriculumCategories' => CurriculumCategory::all(),
                'courseCategories'     => CourseCategory::all(),
                'levels'               => Level::all(),
                'difficulties'         => ['beginner', 'intermediate', 'advanced']
            ],
            'user_role' => $userRole,
            'total_students_in_system' => $totalStudentsInSystem
        ]);
    }



    /*------------------------------------------
    | Create Course
    ------------------------------------------*/

    public function store(Request $request)
    {
        $validation = $this->validateRequest($request, [
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'term_id'  => 'required|exists:terms,id',
            'course_category_id'     => 'required|exists:course_categories,id',
            'level_id' => 'required|exists:levels,id',
            'curriculum_category_id' => 'sometimes|nullable|exists:curriculum_categories,id',
            'free'     => 'nullable|boolean',
            'free_subscription' => 'nullable|boolean',
            'url'      => 'nullable|string|max:255',
            'duration_hours' => 'nullable|integer|min:1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:5048'
        ]);

        if ($validation) return $validation;

        try {
            // Only include fields that are in the Course model's fillable array
            $fillable = [
                'title', 'slug', 'description', 'curriculum_category_id', 'course_category_id',
                'instructor_id', 'term_id', 'level_id', 'free', 'free_subscription', 'status',
                'thumbnail', 'url', 'duration_hours', 'published_at'
            ];

            $courseData = $request->only($fillable);
            $courseData['status'] = 'draft';
            $courseData['instructor_id'] = Auth::id();
            $courseData['thumbnail'] = $this->uploadThumbnail($request);

            // Set default free to false if not provided
            if (!isset($courseData['free']) || $courseData['free'] === null) {
                $courseData['free'] = false;
            }

            // Generate slug from title if not provided
            if (!isset($courseData['slug']) || empty($courseData['slug'])) {
                $courseData['slug'] = Course::generateSlug($courseData['title']);
            }

            $course = Course::create($courseData);

            return $this->success(
                $course->load(['courseCategory', 'curriculumCategory', 'instructor', 'level', 'term']),
                'Course created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error("Failed to create course: {$e->getMessage()}", 500);
        }
    }

    /*------------------------------------------
    | Show Course Details
    ------------------------------------------*/

    public function show($id)
    {
        try {
            $course = Course::with([
                'curriculumCategory',
                'courseCategory',
                'instructor',
                'level',
                'term',
                'lessons' => fn($q) => $q->orderBy('order'),
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
                $isEnrolled = (bool) $enrollment;
            }

            $data = $course->toArray();
            $data['is_enrolled'] = $isEnrolled;
            $data['enrollment'] = $enrollment;
            $data['total_lessons'] = $course->lessons->count();
            $data['total_duration'] = $course->lessons->sum('duration_minutes');
            $data['average_rating'] = $course->reviews->avg('rating');
            $data['total_reviews'] = $course->reviews->count();
            $data['total_students'] = $course->enrollments()->count();

            return $this->success($data);
        } catch (\Exception $e) {
            \Log::error('Course show error: ' . $e->getMessage());
            return $this->error('Course not found', 404);
        }
    }

    /*------------------------------------------
    | Update Course
    ------------------------------------------*/

    public function update(Request $request, $id)
    {
        try {
            $course = Course::findOrFail($id);

            if (!$this->userCanModify($course)) {
                return $this->error('Unauthorized to update this course', 403);
            }

            // Log incoming request data
            \Log::info('Course update request:', [
                'id' => $id,
                'all_data' => $request->all(),
                'free' => $request->input('free'),
                'free_type' => gettype($request->input('free'))
            ]);

            $validation = $this->validateRequest($request, [
                'title' => 'sometimes|string|max:255',
                'slug' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'course_category_id' => 'sometimes|exists:course_categories,id',
                'curriculum_category_id' => 'sometimes|exists:curriculum_categories,id',
                'free'  => 'sometimes|in:0,1',
                'free_subscription' => 'sometimes|boolean',
                'url' => 'sometimes|nullable|string|max:255',
                'difficulty' => 'sometimes|in:beginner,intermediate,advanced',
                'duration_hours' => 'sometimes|nullable|integer|min:1',
                'max_students' => 'sometimes|nullable|integer|min:1',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:5048'
            ]);

            if ($validation) return $validation;

            // Only include fields that are in the Course model's fillable array
            $fillable = [
                'title', 'slug', 'description', 'curriculum_category_id', 'course_category_id',
                'instructor_id', 'term_id', 'level_id', 'free', 'free_subscription', 'status',
                'thumbnail', 'url', 'duration_hours', 'published_at'
            ];

            $data = $request->only($fillable);
            $data['thumbnail'] = $this->uploadThumbnail($request, $course->thumbnail);

            // Generate slug from title if title is being updated and slug is not provided
            if ($request->has('title') && !$request->has('slug')) {
                $data['slug'] = Course::generateSlug($request->input('title'));
            }

            $course->update($data);

            return $this->success(
                $course->load(['courseCategory', 'instructor', 'level', 'term']),
                'Course updated successfully'
            );

        } catch (\Exception $e) {
            return $this->error("Failed to update course: {$e->getMessage()}", 500);
        }
    }

    /*------------------------------------------
    | Delete Course
    ------------------------------------------*/

    public function destroy($id)
    {
        try {
            $course = Course::findOrFail($id);

            if (!$this->userCanModify($course)) {
                return $this->error('Unauthorized to delete this course', 403);
            }

            if ($course->enrollments()->count() > 0) {
                return $this->error('Cannot delete course with active enrollments', 400);
            }

            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            $course->delete();

            return $this->success([], 'Course deleted successfully');

        } catch (\Exception $e) {
            return $this->error("Failed to delete course: {$e->getMessage()}", 500);
        }
    }

    /*------------------------------------------
    | Search Courses
    ------------------------------------------*/

    public function search(Request $request)
    {
        $validation = $this->validateRequest($request, [
            'q' => 'required|string|min:2',
            'curriculum_category_id' => 'nullable|exists:curriculum_categories,id',
            'course_category_id' => 'nullable|exists:course_categories,id',
            'level_id' => 'nullable|exists:levels,id',
            'difficulty' => 'nullable|in:beginner,intermediate,advanced',
            'sort_by'  => 'nullable|in:title,created_at,rating',
            'sort_order' => 'nullable|in:asc,desc'
        ]);

        if ($validation) return $validation;

        $query = Course::with(['courseCategory', 'instructor', 'level'])
                       ->where('status', 'published');

        $search = $request->q;

        $query->where(fn($q) =>
            $q->where('title', 'LIKE', "%$search%")
              ->orWhere('description', 'LIKE', "%$search%")
        );

        foreach (['curriculum_category_id', 'course_category_id', 'level_id', 'difficulty'] as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->$filter);
            }
        }

        if ($request->sort_by === 'rating') {
            $query->withAvg('reviews', 'rating')
                  ->orderBy('reviews_avg_rating', $request->sort_order ?? 'desc');
        } else {
            $query->orderBy($request->sort_by ?? 'created_at', $request->sort_order ?? 'desc');
        }

        return $this->success([
            'results' => $query->paginate($request->get('per_page', 12)),
            'search_term' => $search
        ]);
    }

    /*------------------------------------------
    | Featured and Popular Courses
    ------------------------------------------*/

    public function featured()
    {
        return $this->success(
            Course::with(['courseCategory', 'instructor', 'level'])
                ->where('status', 'published')
                ->where('is_featured', true)
                ->latest()
                ->limit(8)
                ->get()
        );
    }

    public function popular()
    {
        return $this->success(
            Course::with(['courseCategory', 'instructor', 'level'])
                ->where('status', 'published')
                ->withCount('enrollments')
                ->orderBy('enrollments_count', 'desc')
                ->limit(8)
                ->get()
        );
    }

    /*------------------------------------------
    | My Courses
    ------------------------------------------*/

    public function myCourses()
    {
        try {
            $user = Auth::user();

            if (!$user) return $this->error('User not authenticated', 401);

            $results = [];
            $courseIds = [];

            // 1. Get enrolled courses
            $enrollments = Enrollment::where('user_id', $user->id)
                                     ->latest('enrolled_at')
                                     ->get();

            foreach ($enrollments as $e) {
                $course = Course::with(['courseCategory', 'instructor', 'level'])->find($e->course_id);
                if ($course) {
                    $item = $e->toArray();
                    $item['course'] = $course;
                    $item['access_type'] = 'enrolled'; // Mark as enrolled
                    $results[] = $item;
                    $courseIds[] = $course->id;
                }
            }

            // 2. Get free courses (show to all users)
            $freeSubscriptionPlan = SubscriptionPlan::where('duration_type', 'free')
                                                    ->where('is_active', true)
                                                    ->first();

            \Log::info('Free subscription plan lookup', [
                'found' => $freeSubscriptionPlan ? true : false,
                'plan_id' => $freeSubscriptionPlan?->id,
                'plan_title' => $freeSubscriptionPlan?->title
            ]);

            if ($freeSubscriptionPlan) {
                // Show free courses to all users
                $freeCourses = $freeSubscriptionPlan->courses()
                                                   ->where('courses.status', 'published')
                                                   ->whereNotIn('courses.id', $courseIds)
                                                   ->with(['courseCategory', 'instructor', 'level'])
                                                   ->get();

                \Log::info('Free courses found', [
                    'count' => $freeCourses->count(),
                    'course_ids' => $freeCourses->pluck('id')->toArray()
                ]);

                foreach ($freeCourses as $course) {
                    $results[] = [
                        'id' => null,
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                        'progress' => 0,
                        'status' => 'active',
                        'enrolled_at' => null,
                        'completed_at' => null,
                        'amount_paid' => 0,
                        'course' => $course,
                        'access_type' => 'free_subscription' // Mark as free
                    ];
                    $courseIds[] = $course->id;
                }
            }

            // 3. Get courses from active subscriptions
            $activeSubscriptions = UserSubscription::where('user_id', $user->id)
                                                  ->where('status', 'active')
                                                  ->where(function ($q) {
                                                      $q->whereNull('expires_at')
                                                        ->orWhere('expires_at', '>', Carbon::now());
                                                  })
                                                  ->with('subscriptionPlan.courses')
                                                  ->get();

            foreach ($activeSubscriptions as $subscription) {
                if ($subscription->subscriptionPlan && $subscription->subscriptionPlan->courses) {
                    foreach ($subscription->subscriptionPlan->courses as $course) {
                        // Skip if already in results (enrolled or free)
                        if (in_array($course->id, $courseIds)) {
                            continue;
                        }

                        // Only show published courses
                        if ($course->status !== 'published') {
                            continue;
                        }

                        $results[] = [
                            'id' => null,
                            'user_id' => $user->id,
                            'course_id' => $course->id,
                            'progress' => 0,
                            'status' => 'active',
                            'enrolled_at' => null,
                            'completed_at' => null,
                            'amount_paid' => $subscription->amount_paid,
                            'course' => $course->load(['courseCategory', 'instructor', 'level']),
                            'access_type' => 'subscription', // Mark as subscription
                            'subscription_plan_id' => $subscription->subscription_plan_id
                        ];
                        $courseIds[] = $course->id;
                    }
                }
            }

            return $this->success([
                'courses' => $results,
                'total' => count($results)
            ]);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /*------------------------------------------
    | Enrollment
    ------------------------------------------*/

    public function enroll($id)
    {
        try {
            $user = Auth::user();
            $course = Course::findOrFail($id);

            // Already enrolled?
            if (Enrollment::where('user_id', $user->id)
                          ->where('course_id', $id)
                          ->exists()) {
                return $this->error('Already enrolled in this course');
            }

            if ($course->status !== 'published') {
                return $this->error('Course not available for enrollment');
            }

            if ($course->max_students && $course->enrollments()->count() >= $course->max_students) {
                return $this->error('Course is full');
            }

            $transaction = $this->walletService->purchaseCourse($user, $course);

            return $this->success([
                'enrollment'   => $transaction['enrollment'],
                'transaction'  => $transaction['transaction'],
                'new_balance'  => $user->wallet->balance
            ], 'Successfully enrolled in course');

        } catch (\Exception $e) {
            return $this->error("Enrollment failed: {$e->getMessage()}", 400);
        }
    }

    public function unenroll($id)
    {
        try {
            $user = Auth::user();

            $enrollment = Enrollment::where('user_id', $user->id)
                                    ->where('course_id', $id)
                                    ->first();

            if (!$enrollment) return $this->error('Not enrolled in this course');

            if ($enrollment->progress > 0) {
                return $this->error('Cannot unenroll from a started course');
            }

            $enrollment->delete();

            return $this->success([], 'Successfully unenrolled');

        } catch (\Exception $e) {
            return $this->error("Unenrollment failed: {$e->getMessage()}", 500);
        }
    }

    /*------------------------------------------
    | Students & Analytics
    ------------------------------------------*/

    public function students($id)
    {
        try {
            $course = Course::findOrFail($id);

            if (!$this->userCanModify($course)) {
                return $this->error('Unauthorized', 403);
            }

            $students = Enrollment::with('user')
                                  ->where('course_id', $id)
                                  ->latest('enrolled_at')
                                  ->get();

            return $this->success($students);

        } catch (\Exception $e) {
            return $this->error("Failed to fetch students: {$e->getMessage()}", 500);
        }
    }

    public function analytics($id)
    {
        try {
            $course = Course::findOrFail($id);

            if (!$this->userCanModify($course)) {
                return $this->error('Unauthorized', 403);
            }

            $total = $course->enrollments()->count();
            $completed = $course->enrollments()->where('status', 'completed')->count();

            $analytics = [
                'total_enrollments'  => $total,
                'active_students'    => $course->enrollments()->where('status', 'active')->count(),
                'completed_students' => $completed,
                'average_progress'   => $course->enrollments()->avg('progress'),
                'total_revenue'      => $course->enrollments()->sum('amount_paid'),
                'average_rating'     => $course->reviews()->avg('rating'),
                'total_reviews'      => $course->reviews()->count(),
                'completion_rate'    => $total > 0 ? ($completed / $total) * 100 : 0
            ];

            return $this->success($analytics);

        } catch (\Exception $e) {
            return $this->error("Failed to fetch analytics: {$e->getMessage()}", 500);
        }
    }
}
