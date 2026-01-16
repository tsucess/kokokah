<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Course;
use App\Models\LessonCompletion;
use App\Services\PointsAndBadgesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    // Note: Middleware is applied at route level in Laravel 12
    // See routes/api.php for middleware configuration

    /**
     * Get course lessons
     */
    public function index($courseId)
    {
        try {
            $course = Course::findOrFail($courseId);
            $user = Auth::user();

            // Check if user is enrolled or is instructor/admin/superadmin
            $isEnrolled = $course->enrollments()->where('user_id', $user->id)->exists();
            $isInstructor = $course->instructor_id === $user->id;
            $isAdmin = $user->hasAnyRole(['admin', 'superadmin']);

            if (!$isEnrolled && !$isInstructor && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled in this course to view lessons'
                ], 403);
            }

            $lessons = $course->lessons()
                            ->orderBy('order')
                            ->get()
                            ->map(function ($lesson) use ($user) {
                                $lessonData = $lesson->toArray();
                                
                                // Add completion status for the user
                                $completion = LessonCompletion::where('user_id', $user->id)
                                                            ->where('lesson_id', $lesson->id)
                                                            ->first();
                                
                                $lessonData['is_completed'] = $completion !== null;
                                $lessonData['completed_at'] = $completion?->completed_at;
                                $lessonData['time_spent'] = $completion?->time_spent ?? 0;
                                
                                return $lessonData;
                            });

            return response()->json([
                'success' => true,
                'data' => $lessons
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch lessons: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new lesson
     */
    public function store(Request $request, $courseId)
    {
        try {
            $course = Course::findOrFail($courseId);

            // Check if user is instructor or admin
            if ($course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to create lessons for this course'
                ], 403);
            }

            // Get lesson type from request
            $lessonType = $request->input('lesson_type', 'content');

            // Build validation rules based on lesson type
            $rules = [
                'title' => 'required|string|max:255',
                'topic_id' => 'required|integer|exists:topics,id',
                'lesson_type' => 'required|in:content,youtube,document,image,audio,video',
                'duration_minutes' => 'nullable|integer|min:1',
            ];

            // Add type-specific validation rules
            switch ($lessonType) {
                case 'youtube':
                    $rules['video_url'] = 'required|url';
                    break;
                case 'content':
                    $rules['content'] = 'required|string';
                    break;
                case 'document':
                    $rules['attachment'] = 'required|file|mimes:pdf|max:10240';
                    break;
                case 'image':
                    $rules['attachment'] = 'required|file|mimes:jpg,jpeg,png,gif,webp,svg,bmp|max:10240';
                    break;
                case 'audio':
                    $rules['attachment'] = 'required|file|mimes:mp3,wav,ogg,aac,flac,m4a|max:10240';
                    break;
                case 'video':
                    $rules['attachment'] = 'required|file|mimes:mp4,webm,mov,avi,mkv,flv,wmv,m4v|max:10485760'; // 10GB max for videos
                    break;
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get next order number
            $nextOrder = $course->lessons()->max('order') + 1;

            $lessonData = $request->except(['attachment']);
            $lessonData['course_id'] = $course->id;
            $lessonData['order'] = $nextOrder;

            // Handle file attachment based on lesson type
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachmentPath = $file->store('lesson-attachments', 'public');
                $lessonData['attachment'] = $attachmentPath;

                // Set attachment type to the actual file extension
                $lessonData['attachment_type'] = $file->getClientOriginalExtension();
            }

            $lesson = Lesson::create($lessonData);

            return response()->json([
                'success' => true,
                'message' => 'Lesson created successfully',
                'data' => $lesson
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create lesson: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get lesson details
     */
    public function show($id)
    {
        try {
            $lesson = Lesson::with(['course'])->findOrFail($id);
            $user = Auth::user();

            // Check if user has access to this lesson
            $isEnrolled = $lesson->course->enrollments()->where('user_id', $user->id)->exists();
            $isInstructor = $lesson->course->instructor_id === $user->id;
            $isAdmin = $user->hasRole('admin');

            if (!$isEnrolled && !$isInstructor && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled in this course to view this lesson'
                ], 403);
            }

            $lessonData = $lesson->toArray();

            // Add completion status for the user
            $completion = LessonCompletion::where('user_id', $user->id)
                                        ->where('lesson_id', $lesson->id)
                                        ->first();

            $lessonData['is_completed'] = $completion !== null;
            $lessonData['completed_at'] = $completion?->completed_at;
            $lessonData['time_spent'] = $completion?->time_spent ?? 0;

            // Get next and previous lessons
            $nextLesson = Lesson::where('course_id', $lesson->course_id)
                               ->where('order', '>', $lesson->order)
                               ->orderBy('order')
                               ->first();

            $previousLesson = Lesson::where('course_id', $lesson->course_id)
                                   ->where('order', '<', $lesson->order)
                                   ->orderBy('order', 'desc')
                                   ->first();

            $lessonData['next_lesson'] = $nextLesson ? ['id' => $nextLesson->id, 'title' => $nextLesson->title] : null;
            $lessonData['previous_lesson'] = $previousLesson ? ['id' => $previousLesson->id, 'title' => $previousLesson->title] : null;

            return response()->json([
                'success' => true,
                'data' => $lessonData
            ]);
        } catch (\Exception) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson not found'
            ], 404);
        }
    }

    /**
     * Update lesson
     */
    public function update(Request $request, $id)
    {
        try {
            $lesson = Lesson::findOrFail($id);

            // Check if user is instructor or admin
            if ($lesson->course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this lesson'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string|max:255',
                'content' => 'sometimes|string',
                'video_url' => 'nullable|url',
                'lesson_type' => 'sometimes|in:content,youtube,document,image,audio,video',
                'duration_minutes' => 'nullable|integer|min:1',
                'order' => 'sometimes|integer|min:1',
                'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif,webp,svg,bmp,mp3,wav,ogg,aac,flac,m4a,mp4,webm,mov,avi,mkv,flv,wmv,m4v|max:10485760' // 10GB max for videos
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = $request->except(['attachment']);

            // Handle file attachment
            if ($request->hasFile('attachment')) {
                // Delete old attachment
                if ($lesson->attachment) {
                    Storage::disk('public')->delete($lesson->attachment);
                }
                $file = $request->file('attachment');
                $attachmentPath = $file->store('lesson-attachments', 'public');
                $updateData['attachment'] = $attachmentPath;

                // Set attachment type to the actual file extension
                $updateData['attachment_type'] = $file->getClientOriginalExtension();
            }

            $lesson->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Lesson updated successfully',
                'data' => $lesson
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update lesson: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete lesson
     */
    public function destroy($id)
    {
        try {
            $lesson = Lesson::findOrFail($id);

            // Check if user is instructor or admin
            if ($lesson->course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this lesson'
                ], 403);
            }

            // Delete attachment file
            if ($lesson->attachment) {
                Storage::disk('public')->delete($lesson->attachment);
            }

            $lesson->delete();

            return response()->json([
                'success' => true,
                'message' => 'Lesson deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete lesson: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark lesson as complete
     */
    public function complete($id)
    {
        try {
            $lesson = Lesson::findOrFail($id);
            $user = Auth::user();

            // Check if user is enrolled in the course
            $isEnrolled = $lesson->course->enrollments()->where('user_id', $user->id)->exists();
            if (!$isEnrolled) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled in this course to complete lessons'
                ], 403);
            }

            // Check if already completed
            $existingCompletion = LessonCompletion::where('user_id', $user->id)
                                                ->where('lesson_id', $lesson->id)
                                                ->first();

            if ($existingCompletion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lesson already completed'
                ], 400);
            }

            // Create completion record
            $completion = LessonCompletion::create([
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
                'completed_at' => now(),
                'time_spent' => 0 // Will be updated via watch time tracking
            ]);

            // Update course progress
            $this->updateCourseProgress($user, $lesson->course);

            // Award points for completing the lesson/topic
            $pointsService = new PointsAndBadgesService();
            $topicId = $lesson->topic_id;
            if ($topicId) {
                $pointsService->awardPointsForTopicCompletion($user, $topicId);
            }

            // Refresh user to get updated points
            $user->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Lesson marked as complete',
                'data' => $completion,
                'user_points' => $user->points,
                'points_awarded' => 5
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete lesson: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get lesson progress
     */
    public function progress($id)
    {
        try {
            $lesson = Lesson::findOrFail($id);
            $user = Auth::user();

            $completion = LessonCompletion::where('user_id', $user->id)
                                        ->where('lesson_id', $lesson->id)
                                        ->first();

            $progressData = [
                'lesson_id' => $lesson->id,
                'is_completed' => $completion !== null,
                'completed_at' => $completion?->completed_at,
                'time_spent' => $completion?->time_spent ?? 0,
                'lesson_duration' => $lesson->duration_minutes * 60, // Convert to seconds
                'progress_percentage' => $completion ? 100 : 0
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
     * Track watch time
     */
    public function trackWatchTime(Request $request, $id)
    {
        try {
            $lesson = Lesson::findOrFail($id);
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'time_spent' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if user is enrolled
            $isEnrolled = $lesson->course->enrollments()->where('user_id', $user->id)->exists();
            if (!$isEnrolled) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled in this course'
                ], 403);
            }

            // Update or create completion record
            $completion = LessonCompletion::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'lesson_id' => $lesson->id
                ],
                [
                    'time_spent' => $request->time_spent,
                    'completed_at' => now()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Watch time tracked successfully',
                'data' => $completion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to track watch time: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get lesson attachments
     */
    public function attachments($id)
    {
        try {
            $lesson = Lesson::findOrFail($id);
            $user = Auth::user();

            // Check access
            $isEnrolled = $lesson->course->enrollments()->where('user_id', $user->id)->exists();
            $isInstructor = $lesson->course->instructor_id === $user->id;
            $isAdmin = $user->hasRole('admin');

            if (!$isEnrolled && !$isInstructor && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled to access attachments'
                ], 403);
            }

            $attachments = [];
            if ($lesson->attachment) {
                try {
                    $fileSize = Storage::disk('public')->size($lesson->attachment);
                } catch (\Exception $e) {
                    $fileSize = 0;
                }

                $attachments[] = [
                    'file_name' => basename($lesson->attachment),
                    'file_path' => url('/storage/' . $lesson->attachment),
                    'size' => $fileSize
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $attachments
            ]);
        } catch (\Exception $e) {
            \Log::error('Lesson attachments error: ' . $e->getMessage(), [
                'lesson_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch attachments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update course progress based on lesson completion
     */
    private function updateCourseProgress($user, $course)
    {
        $totalLessons = $course->lessons()->count();
        $completedLessons = LessonCompletion::where('user_id', $user->id)
                                          ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                                          ->count();

        $progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

        // Update enrollment progress
        $enrollment = $course->enrollments()->where('user_id', $user->id)->first();
        if ($enrollment) {
            $enrollment->update([
                'progress' => $progress,
                'completed_at' => $progress >= 100 ? now() : null,
                'status' => $progress >= 100 ? 'completed' : 'active'
            ]);
        }
    }
}
