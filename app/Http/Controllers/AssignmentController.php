<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    // Note: Middleware is applied at route level in Laravel 12
    // See routes/api.php for middleware configuration

    /**
     * Get course assignments
     */
    public function index($courseId)
    {
        try {
            $course = Course::findOrFail($courseId);
            $user = Auth::user();

            // Check if user has access to this course
            $isEnrolled = $course->enrollments()->where('user_id', $user->id)->exists();
            $isInstructor = $course->instructor_id === $user->id;
            $isAdmin = $user->hasRole('admin');

            if (!$isEnrolled && !$isInstructor && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled in this course to view assignments'
                ], 403);
            }

            $assignments = $course->assignments()
                                ->with(['submissions' => function($query) use ($user, $isInstructor, $isAdmin) {
                                    if (!$isInstructor && !$isAdmin) {
                                        $query->where('student_id', $user->id);
                                    }
                                }])
                                ->orderBy('due_date', 'asc')
                                ->get()
                                ->map(function ($assignment) use ($user, $isInstructor, $isAdmin) {
                                    $assignmentData = $assignment->toArray();
                                    
                                    if ($isInstructor || $isAdmin) {
                                        // Add submission statistics for instructors
                                        $totalSubmissions = $assignment->submissions()->count();
                                        $gradedSubmissions = $assignment->submissions()->whereNotNull('grade')->count();
                                        $averageGrade = $assignment->submissions()->whereNotNull('grade')->avg('grade');
                                        
                                        $assignmentData['statistics'] = [
                                            'total_submissions' => $totalSubmissions,
                                            'graded_submissions' => $gradedSubmissions,
                                            'pending_grading' => $totalSubmissions - $gradedSubmissions,
                                            'average_grade' => $averageGrade ? round($averageGrade, 2) : null
                                        ];
                                    } else {
                                        // Add student's submission status
                                        $userSubmission = $assignment->submissions()->where('student_id', $user->id)->first();
                                        $assignmentData['user_submission'] = $userSubmission ? [
                                            'id' => $userSubmission->id,
                                            'submitted_at' => $userSubmission->submitted_at,
                                            'grade' => $userSubmission->grade,
                                            'feedback' => $userSubmission->feedback,
                                            'status' => $userSubmission->status
                                        ] : null;
                                        
                                        $assignmentData['can_submit'] = !$userSubmission && now() <= $assignment->due_date;
                                        $assignmentData['is_overdue'] = now() > $assignment->due_date;
                                    }

                                    return $assignmentData;
                                });

            return response()->json([
                'success' => true,
                'data' => $assignments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch assignments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new assignment
     */
    public function store(Request $request, $courseId)
    {
        try {
            $course = Course::findOrFail($courseId);

            // Check if user is instructor or admin
            if ($course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to create assignments for this course'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'instructions' => 'nullable|string',
                'due_date' => 'required|date|after:now',
                'max_points' => 'required|integer|min:1',
                'submission_type' => 'required|in:text,file,both',
                'allowed_file_types' => 'nullable|string',
                'max_file_size_mb' => 'nullable|integer|min:1|max:100',
                'late_submission_penalty' => 'nullable|integer|min:0|max:100',
                'allow_late_submissions' => 'boolean',
                'attachments' => 'nullable|array',
                'attachments.*' => 'file|max:10240' // 10MB max per file
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            try {
                // Create assignment
                $assignmentData = $request->except(['attachments']);
                $assignmentData['course_id'] = $course->id;
                $assignmentData['allow_late_submissions'] = $request->boolean('allow_late_submissions', false);

                $assignment = Assignment::create($assignmentData);

                // Handle file attachments
                if ($request->hasFile('attachments')) {
                    $attachments = [];
                    foreach ($request->file('attachments') as $file) {
                        $path = $file->store('assignments/' . $assignment->id, 'public');
                        $attachments[] = [
                            'name' => $file->getClientOriginalName(),
                            'path' => $path,
                            'size' => $file->getSize(),
                            'type' => $file->getMimeType()
                        ];
                    }
                    $assignment->update(['attachments' => $attachments]);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Assignment created successfully',
                    'data' => $assignment
                ], 201);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get assignment details
     */
    public function show($id)
    {
        try {
            $assignment = Assignment::with(['course', 'submissions.student'])->findOrFail($id);
            $user = Auth::user();

            // Check if course exists
            if (!$assignment->course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment course not found'
                ], 404);
            }

            // Check access
            $isEnrolled = $assignment->course->enrollments()->where('user_id', $user->id)->exists();
            $isInstructor = $assignment->course->instructor_id === $user->id;
            $isAdmin = $user->hasRole('admin');

            if (!$isEnrolled && !$isInstructor && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled to view this assignment'
                ], 403);
            }

            $assignmentData = $assignment->toArray();

            if ($isInstructor || $isAdmin) {
                // Include all submissions for instructors
                $assignmentData['submissions'] = $assignment->submissions()
                                                          ->with('student')
                                                          ->orderBy('submitted_at', 'desc')
                                                          ->get();
            } else {
                // Include only user's submission for students
                $userSubmission = $assignment->submissions()->where('student_id', $user->id)->first();
                $assignmentData['user_submission'] = $userSubmission;
                $assignmentData['can_submit'] = !$userSubmission && now() <= $assignment->due_date;
                $assignmentData['is_overdue'] = now() > $assignment->due_date;

                // Remove submissions array for students
                unset($assignmentData['submissions']);
            }

            return response()->json([
                'success' => true,
                'data' => $assignmentData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update assignment
     */
    public function update(Request $request, $id)
    {
        try {
            $assignment = Assignment::findOrFail($id);

            // Check if user is instructor or admin
            if ($assignment->course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this assignment'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'instructions' => 'nullable|string',
                'due_date' => 'sometimes|date|after:now',
                'max_points' => 'sometimes|integer|min:1',
                'submission_type' => 'sometimes|in:text,file,both',
                'allowed_file_types' => 'nullable|string',
                'max_file_size_mb' => 'nullable|integer|min:1|max:100',
                'late_submission_penalty' => 'nullable|integer|min:0|max:100',
                'allow_late_submissions' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if assignment has submissions
            if ($assignment->submissions()->exists() && $request->has(['max_points', 'due_date'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot modify points or due date after submissions have been made'
                ], 400);
            }

            $updateData = $request->all();
            if ($request->has('allow_late_submissions')) {
                $updateData['allow_late_submissions'] = $request->boolean('allow_late_submissions');
            }

            $assignment->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Assignment updated successfully',
                'data' => $assignment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete assignment
     */
    public function destroy($id)
    {
        try {
            $assignment = Assignment::findOrFail($id);

            // Check if user is instructor or admin
            if ($assignment->course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this assignment'
                ], 403);
            }

            // Check if assignment has submissions
            if ($assignment->submissions()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete assignment that has submissions'
                ], 400);
            }

            // Delete associated files
            if ($assignment->attachments) {
                foreach ($assignment->attachments as $attachment) {
                    Storage::disk('public')->delete($attachment['path']);
                }
            }

            $assignment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Assignment deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit assignment
     */
    public function submit(Request $request, $id)
    {
        try {
            $assignment = Assignment::findOrFail($id);
            $user = Auth::user();

            // Check if user is enrolled
            $isEnrolled = $assignment->course->enrollments()->where('user_id', $user->id)->exists();
            if (!$isEnrolled) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled to submit this assignment'
                ], 403);
            }

            // Check if already submitted
            $existingSubmission = $assignment->submissions()->where('student_id', $user->id)->first();
            if ($existingSubmission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment already submitted'
                ], 400);
            }

            // Check deadline
            $isLate = now() > $assignment->due_date;
            if ($isLate && !$assignment->allow_late_submissions) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment deadline has passed and late submissions are not allowed'
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'submission_text' => 'required_if:submission_type,text,both|nullable|string',
                'files' => 'required_if:submission_type,file,both|nullable|array',
                'files.*' => 'file|max:' . ($assignment->max_file_size_mb * 1024) // Convert MB to KB
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            try {
                // Handle file uploads
                $uploadedFiles = [];
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        // Validate file type if specified
                        if ($assignment->allowed_file_types) {
                            $allowedTypes = explode(',', $assignment->allowed_file_types);
                            $fileExtension = $file->getClientOriginalExtension();
                            if (!in_array($fileExtension, $allowedTypes)) {
                                throw new \Exception("File type {$fileExtension} is not allowed");
                            }
                        }

                        $path = $file->store('submissions/' . $assignment->id, 'public');
                        $uploadedFiles[] = [
                            'name' => $file->getClientOriginalName(),
                            'path' => $path,
                            'size' => $file->getSize(),
                            'type' => $file->getMimeType()
                        ];
                    }
                }

                // Create submission
                $submission = AssignmentSubmission::create([
                    'assignment_id' => $assignment->id,
                    'student_id' => $user->id,
                    'submission_text' => $request->submission_text,
                    'files' => $uploadedFiles,
                    'submitted_at' => now(),
                    'is_late' => $isLate,
                    'status' => 'submitted'
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Assignment submitted successfully',
                    'data' => $submission
                ], 201);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get assignment submissions (for instructors)
     */
    public function submissions($id)
    {
        try {
            $assignment = Assignment::findOrFail($id);

            // Check if user is instructor or admin
            if ($assignment->course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view submissions'
                ], 403);
            }

            $submissions = $assignment->submissions()
                                    ->with('student')
                                    ->orderBy('submitted_at', 'desc')
                                    ->get()
                                    ->map(function ($submission) use ($assignment) {
                                        $submissionData = $submission->toArray();

                                        // Calculate late penalty if applicable
                                        if ($submission->is_late && $assignment->late_submission_penalty) {
                                            $submissionData['penalty_applied'] = $assignment->late_submission_penalty;
                                        }

                                        return $submissionData;
                                    });

            return response()->json([
                'success' => true,
                'data' => [
                    'assignment' => $assignment,
                    'submissions' => $submissions,
                    'statistics' => [
                        'total_submissions' => $submissions->count(),
                        'graded_submissions' => $submissions->whereNotNull('grade')->count(),
                        'pending_grading' => $submissions->whereNull('grade')->count(),
                        'late_submissions' => $submissions->where('is_late', true)->count(),
                        'average_grade' => $submissions->whereNotNull('grade')->avg('grade')
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch submissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Grade a submission
     */
    public function gradeSubmission(Request $request, $submissionId)
    {
        try {
            $submission = AssignmentSubmission::with(['assignment.course', 'student'])->findOrFail($submissionId);

            // Check if user is instructor or admin
            if ($submission->assignment->course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to grade this submission'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'grade' => 'required|numeric|min:0|max:' . $submission->assignment->max_points,
                'feedback' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $grade = $request->grade;

            // Apply late penalty if applicable
            if ($submission->is_late && $submission->assignment->late_submission_penalty) {
                $penalty = ($submission->assignment->late_submission_penalty / 100) * $grade;
                $grade = max(0, $grade - $penalty);
            }

            $submission->update([
                'grade' => $grade,
                'original_grade' => $request->grade,
                'feedback' => $request->feedback,
                'graded_at' => now(),
                'graded_by' => Auth::id(),
                'status' => 'graded'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Submission graded successfully',
                'data' => $submission->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to grade submission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get assignment grades (for instructors)
     */
    public function grades($id)
    {
        try {
            $assignment = Assignment::findOrFail($id);

            // Check if user is instructor or admin
            if ($assignment->course->instructor_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view grades'
                ], 403);
            }

            $grades = $assignment->submissions()
                                ->with('student')
                                ->whereNotNull('grade')
                                ->orderBy('grade', 'desc')
                                ->get()
                                ->map(function ($submission) {
                                    return [
                                        'student_id' => $submission->student_id,
                                        'student_name' => $submission->student->first_name . ' ' . $submission->student->last_name,
                                        'student_email' => $submission->student->email,
                                        'grade' => $submission->grade,
                                        'original_grade' => $submission->original_grade,
                                        'max_points' => $submission->assignment->max_points,
                                        'percentage' => round(($submission->grade / $submission->assignment->max_points) * 100, 2),
                                        'is_late' => $submission->is_late,
                                        'submitted_at' => $submission->submitted_at,
                                        'graded_at' => $submission->graded_at,
                                        'feedback' => $submission->feedback
                                    ];
                                });

            $statistics = [
                'total_graded' => $grades->count(),
                'average_grade' => $grades->avg('grade'),
                'highest_grade' => $grades->max('grade'),
                'lowest_grade' => $grades->min('grade'),
                'grade_distribution' => $this->getGradeDistribution($grades, $assignment->max_points)
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'assignment' => $assignment,
                    'grades' => $grades,
                    'statistics' => $statistics
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch grades: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get grade distribution for analytics
     */
    private function getGradeDistribution($grades, $maxPoints)
    {
        $distribution = [
            'A (90-100%)' => 0,
            'B (80-89%)' => 0,
            'C (70-79%)' => 0,
            'D (60-69%)' => 0,
            'F (0-59%)' => 0
        ];

        foreach ($grades as $grade) {
            $percentage = ($grade['grade'] / $maxPoints) * 100;

            if ($percentage >= 90) $distribution['A (90-100%)']++;
            elseif ($percentage >= 80) $distribution['B (80-89%)']++;
            elseif ($percentage >= 70) $distribution['C (70-79%)']++;
            elseif ($percentage >= 60) $distribution['D (60-69%)']++;
            else $distribution['F (0-59%)']++;
        }

        return $distribution;
    }
}
