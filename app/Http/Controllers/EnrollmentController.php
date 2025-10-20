<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Certificate;
use App\Models\LessonCompletion;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Get user's enrollments
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Enrollment::with(['course.category', 'course.instructor', 'course.level'])
                          ->where('user_id', $user->id);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by progress
        if ($request->has('completed')) {
            if ($request->boolean('completed')) {
                $query->where('status', 'completed');
            } else {
                $query->where('status', '!=', 'completed');
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'enrolled_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $enrollments = $query->paginate($request->get('per_page', 12));

        // Add additional data to each enrollment
        $enrollments->getCollection()->transform(function ($enrollment) {
            $enrollmentData = $enrollment->toArray();
            
            // Add lesson progress
            $totalLessons = $enrollment->course->lessons()->count();
            $completedLessons = LessonCompletion::where('user_id', $enrollment->user_id)
                                              ->whereIn('lesson_id', $enrollment->course->lessons()->pluck('id'))
                                              ->count();
            
            $enrollmentData['lesson_progress'] = [
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedLessons,
                'completion_percentage' => $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0
            ];

            // Add certificate info if completed
            if ($enrollment->status === 'completed') {
                $certificate = Certificate::where('user_id', $enrollment->user_id)
                                         ->where('course_id', $enrollment->course_id)
                                         ->first();
                $enrollmentData['certificate'] = $certificate;
            }

            return $enrollmentData;
        });

        return response()->json([
            'success' => true,
            'data' => $enrollments
        ]);
    }

    /**
     * Enroll in a course
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'coupon_code' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $course = Course::findOrFail($request->course_id);

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

            // Use wallet service to handle payment and enrollment
            $result = $this->walletService->purchaseCourse($user, $course, $request->coupon_code);

            return response()->json([
                'success' => true,
                'message' => 'Successfully enrolled in course',
                'data' => [
                    'enrollment' => $result['enrollment'],
                    'transaction' => $result['transaction'],
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
     * Get enrollment details
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            $enrollment = Enrollment::with(['course.category', 'course.instructor', 'course.level', 'course.lessons'])
                                  ->where('user_id', $user->id)
                                  ->findOrFail($id);

            $enrollmentData = $enrollment->toArray();

            // Add detailed progress information
            $totalLessons = $enrollment->course->lessons()->count();
            $completedLessons = LessonCompletion::where('user_id', $user->id)
                                              ->whereIn('lesson_id', $enrollment->course->lessons()->pluck('id'))
                                              ->get();

            $enrollmentData['detailed_progress'] = [
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedLessons->count(),
                'completion_percentage' => $totalLessons > 0 ? ($completedLessons->count() / $totalLessons) * 100 : 0,
                'total_time_spent' => $completedLessons->sum('time_spent'),
                'lessons_completed' => $completedLessons->map(function ($completion) {
                    return [
                        'lesson_id' => $completion->lesson_id,
                        'completed_at' => $completion->completed_at,
                        'time_spent' => $completion->time_spent
                    ];
                })
            ];

            // Add next lesson to study
            $nextLesson = $enrollment->course->lessons()
                                           ->whereNotIn('id', $completedLessons->pluck('lesson_id'))
                                           ->orderBy('order')
                                           ->first();

            $enrollmentData['next_lesson'] = $nextLesson ? [
                'id' => $nextLesson->id,
                'title' => $nextLesson->title,
                'order' => $nextLesson->order
            ] : null;

            // Add certificate if completed
            if ($enrollment->status === 'completed') {
                $certificate = Certificate::where('user_id', $user->id)
                                         ->where('course_id', $enrollment->course_id)
                                         ->first();
                $enrollmentData['certificate'] = $certificate;
            }

            return response()->json([
                'success' => true,
                'data' => $enrollmentData
            ]);
        } catch (\Exception) {
            return response()->json([
                'success' => false,
                'message' => 'Enrollment not found'
            ], 404);
        }
    }

    /**
     * Update enrollment
     */
    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $enrollment = Enrollment::where('user_id', $user->id)->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'status' => 'sometimes|in:active,paused,cancelled'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Only allow certain status changes
            $allowedUpdates = $request->only(['status']);
            
            // Don't allow changing to completed status manually
            if (isset($allowedUpdates['status']) && $allowedUpdates['status'] === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot manually set status to completed'
                ], 400);
            }

            $enrollment->update($allowedUpdates);

            return response()->json([
                'success' => true,
                'message' => 'Enrollment updated successfully',
                'data' => $enrollment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update enrollment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel enrollment
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            $enrollment = Enrollment::where('user_id', $user->id)->findOrFail($id);

            // Check if enrollment can be cancelled
            if ($enrollment->progress > 25) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel enrollment after 25% progress'
                ], 400);
            }

            // Check if enrollment is recent (within 7 days)
            if ($enrollment->enrolled_at->diffInDays(now()) > 7) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel enrollment after 7 days'
                ], 400);
            }

            $enrollment->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Enrollment cancelled successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel enrollment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get enrollment progress
     */
    public function progress($id)
    {
        try {
            $user = Auth::user();
            $enrollment = Enrollment::where('user_id', $user->id)->findOrFail($id);

            $course = $enrollment->course;
            $totalLessons = $course->lessons()->count();
            $completedLessons = LessonCompletion::where('user_id', $user->id)
                                              ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                                              ->get();

            $progressData = [
                'enrollment_id' => $enrollment->id,
                'course_id' => $course->id,
                'overall_progress' => $enrollment->progress,
                'status' => $enrollment->status,
                'lessons' => [
                    'total' => $totalLessons,
                    'completed' => $completedLessons->count(),
                    'percentage' => $totalLessons > 0 ? ($completedLessons->count() / $totalLessons) * 100 : 0
                ],
                'time_spent' => $completedLessons->sum('time_spent'),
                'enrolled_at' => $enrollment->enrolled_at,
                'completed_at' => $enrollment->completed_at,
                'estimated_completion' => $this->calculateEstimatedCompletion($enrollment, $completedLessons->count(), $totalLessons)
            ];

            return response()->json([
                'success' => true,
                'data' => $progressData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch progress: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete course enrollment
     */
    public function complete($id)
    {
        try {
            $user = Auth::user();
            $enrollment = Enrollment::where('user_id', $user->id)->findOrFail($id);

            // Check if all lessons are completed
            $course = $enrollment->course;
            $totalLessons = $course->lessons()->count();
            $completedLessons = LessonCompletion::where('user_id', $user->id)
                                              ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                                              ->count();

            if ($completedLessons < $totalLessons) {
                return response()->json([
                    'success' => false,
                    'message' => 'All lessons must be completed before completing the course',
                    'data' => [
                        'completed_lessons' => $completedLessons,
                        'total_lessons' => $totalLessons,
                        'remaining_lessons' => $totalLessons - $completedLessons
                    ]
                ], 400);
            }

            // Update enrollment status
            $enrollment->update([
                'status' => 'completed',
                'progress' => 100,
                'completed_at' => now()
            ]);

            // Generate certificate
            $certificate = Certificate::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'certificate_number' => 'CERT-' . strtoupper(uniqid()),
                'certificate_url' => 'certificates/cert-' . $user->id . '-' . $course->id . '.pdf',
                'issued_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course completed successfully! Certificate generated.',
                'data' => [
                    'enrollment' => $enrollment,
                    'certificate' => $certificate
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete course: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's certificates
     */
    public function certificates()
    {
        $user = Auth::user();

        $certificates = Certificate::with(['course.category', 'course.instructor'])
                                 ->where('user_id', $user->id)
                                 ->orderBy('issued_at', 'desc')
                                 ->get();

        return response()->json([
            'success' => true,
            'data' => $certificates
        ]);
    }

    /**
     * Calculate estimated completion date
     */
    private function calculateEstimatedCompletion($enrollment, $completedLessons, $totalLessons)
    {
        if ($completedLessons === 0 || $enrollment->status === 'completed') {
            return null;
        }

        $daysSinceEnrollment = $enrollment->enrolled_at->diffInDays(now());
        $averageLessonsPerDay = $completedLessons / max($daysSinceEnrollment, 1);

        if ($averageLessonsPerDay > 0) {
            $remainingLessons = $totalLessons - $completedLessons;
            $estimatedDaysToComplete = $remainingLessons / $averageLessonsPerDay;

            return now()->addDays(ceil($estimatedDaysToComplete));
        }

        return null;
    }
}
