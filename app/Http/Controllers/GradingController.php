<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\QuizAttempt;
use App\Models\AssignmentSubmission;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class GradingController extends Controller
{
    // Note: Middleware is applied at route level in Laravel 12
    // See routes/api.php for middleware configuration

    /**
     * Get complete gradebook for a course
     */
    public function gradebook($courseId, Request $request)
    {
        try {
            $course = Course::findOrFail($courseId);
            $user = Auth::user();

            // Check permissions
            if ($course->instructor_id !== $user->id && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view this gradebook'
                ], 403);
            }

            // Get enrolled students
            $students = User::whereHas('enrollments', function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })->with(['enrollments' => function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            }])->get();

            // Get course assessments
            $quizzes = $course->quizzes()->orderBy('created_at')->get();
            $assignments = $course->assignments()->orderBy('created_at')->get();

            // Build gradebook data
            $gradebook = $students->map(function($student) use ($course, $quizzes, $assignments) {
                $enrollment = $student->enrollments->first();
                
                $studentData = [
                    'student_id' => $student->id,
                    'student_name' => $student->first_name . ' ' . $student->last_name,
                    'student_email' => $student->email,
                    'enrollment_date' => $enrollment->enrolled_at,
                    'enrollment_status' => $enrollment->status,
                    'final_grade' => $enrollment->final_grade,
                    'quiz_grades' => [],
                    'assignment_grades' => [],
                    'overall_stats' => []
                ];

                // Get quiz grades
                foreach ($quizzes as $quiz) {
                    $attempt = QuizAttempt::where('user_id', $student->id)
                                        ->where('quiz_id', $quiz->id)
                                        ->where('status', 'completed')
                                        ->orderBy('score', 'desc')
                                        ->first();

                    $studentData['quiz_grades'][] = [
                        'quiz_id' => $quiz->id,
                        'quiz_title' => $quiz->title,
                        'score' => $attempt ? $attempt->score : null,
                        'max_score' => $quiz->total_points,
                        'attempts' => QuizAttempt::where('user_id', $student->id)
                                               ->where('quiz_id', $quiz->id)
                                               ->count(),
                        'completed_at' => $attempt ? $attempt->completed_at : null
                    ];
                }

                // Get assignment grades
                foreach ($assignments as $assignment) {
                    $submission = AssignmentSubmission::where('user_id', $student->id)
                                                    ->where('assignment_id', $assignment->id)
                                                    ->first();

                    $studentData['assignment_grades'][] = [
                        'assignment_id' => $assignment->id,
                        'assignment_title' => $assignment->title,
                        'grade' => $submission ? $submission->grade : null,
                        'max_points' => $assignment->max_points,
                        'submitted_at' => $submission ? $submission->submitted_at : null,
                        'graded_at' => $submission ? $submission->graded_at : null,
                        'is_late' => $submission ? $submission->is_late : false
                    ];
                }

                // Calculate overall statistics
                $quizAverage = collect($studentData['quiz_grades'])
                             ->where('score', '!=', null)
                             ->avg('score');

                $assignmentAverage = collect($studentData['assignment_grades'])
                                   ->where('grade', '!=', null)
                                   ->avg('grade');

                $studentData['overall_stats'] = [
                    'quiz_average' => $quizAverage ? round($quizAverage, 2) : null,
                    'assignment_average' => $assignmentAverage ? round($assignmentAverage, 2) : null,
                    'overall_average' => $this->calculateOverallGrade($studentData, $course),
                    'completion_percentage' => $this->calculateCompletionPercentage($student, $course)
                ];

                return $studentData;
            });

            // Course statistics
            $courseStats = [
                'total_students' => $students->count(),
                'average_grade' => $gradebook->avg('overall_stats.overall_average'),
                'completion_rate' => $gradebook->where('enrollment_status', 'completed')->count() / max($students->count(), 1) * 100,
                'quiz_completion_rate' => $this->calculateQuizCompletionRate($students, $quizzes),
                'assignment_submission_rate' => $this->calculateAssignmentSubmissionRate($students, $assignments)
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'course' => $course,
                    'gradebook' => $gradebook,
                    'course_stats' => $courseStats,
                    'assessments' => [
                        'quizzes' => $quizzes,
                        'assignments' => $assignments
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch gradebook: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course grade overview
     */
    public function courseGrades($courseId)
    {
        try {
            $course = Course::findOrFail($courseId);
            $user = Auth::user();

            // Check permissions
            if ($course->instructor_id !== $user->id && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view course grades'
                ], 403);
            }

            $enrollments = Enrollment::with('user')
                                   ->where('course_id', $courseId)
                                   ->get();

            $gradeDistribution = [
                'A (90-100)' => 0,
                'B (80-89)' => 0,
                'C (70-79)' => 0,
                'D (60-69)' => 0,
                'F (0-59)' => 0,
                'No Grade' => 0
            ];

            $totalGraded = 0;
            $gradeSum = 0;

            foreach ($enrollments as $enrollment) {
                if ($enrollment->final_grade !== null) {
                    $grade = $enrollment->final_grade;
                    $gradeSum += $grade;
                    $totalGraded++;

                    if ($grade >= 90) $gradeDistribution['A (90-100)']++;
                    elseif ($grade >= 80) $gradeDistribution['B (80-89)']++;
                    elseif ($grade >= 70) $gradeDistribution['C (70-79)']++;
                    elseif ($grade >= 60) $gradeDistribution['D (60-69)']++;
                    else $gradeDistribution['F (0-59)']++;
                } else {
                    $gradeDistribution['No Grade']++;
                }
            }

            $analytics = [
                'total_students' => $enrollments->count(),
                'graded_students' => $totalGraded,
                'average_grade' => $totalGraded > 0 ? round($gradeSum / $totalGraded, 2) : null,
                'grade_distribution' => $gradeDistribution,
                'pass_rate' => $totalGraded > 0 ? round(($enrollments->where('final_grade', '>=', 60)->count() / $totalGraded) * 100, 2) : 0
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'course' => $course,
                    'analytics' => $analytics,
                    'recent_grades' => $enrollments->whereNotNull('final_grade')
                                                 ->sortByDesc('updated_at')
                                                 ->take(10)
                                                 ->values()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch course grades: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get student grade profile
     */
    public function studentGrades($studentId, Request $request)
    {
        try {
            $student = User::findOrFail($studentId);
            $user = Auth::user();

            // Check permissions (student can view own grades, instructors/admins can view all)
            if ($student->id !== $user->id && !$user->hasRole('instructor') && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view these grades'
                ], 403);
            }

            $query = Enrollment::with(['course.category', 'course.instructor'])
                             ->where('user_id', $studentId);

            // If instructor, only show their courses
            if ($user->hasRole('instructor') && $student->id !== $user->id) {
                $query->whereHas('course', function($q) use ($user) {
                    $q->where('instructor_id', $user->id);
                });
            }

            $enrollments = $query->get();

            $gradeData = $enrollments->map(function($enrollment) use ($studentId) {
                $course = $enrollment->course;
                
                // Get quiz grades
                $quizGrades = QuizAttempt::with('quiz')
                                       ->where('user_id', $studentId)
                                       ->whereHas('quiz', function($q) use ($course) {
                                           $q->where('course_id', $course->id);
                                       })
                                       ->where('status', 'completed')
                                       ->get()
                                       ->groupBy('quiz_id')
                                       ->map(function($attempts) {
                                           return $attempts->sortByDesc('score')->first();
                                       });

                // Get assignment grades
                $assignmentGrades = AssignmentSubmission::with('assignment')
                                                      ->where('user_id', $studentId)
                                                      ->whereHas('assignment', function($q) use ($course) {
                                                          $q->where('course_id', $course->id);
                                                      })
                                                      ->get();

                return [
                    'course' => $course,
                    'enrollment' => $enrollment,
                    'quiz_grades' => $quizGrades->values(),
                    'assignment_grades' => $assignmentGrades,
                    'grade_breakdown' => $this->calculateGradeBreakdown($enrollment, $quizGrades, $assignmentGrades)
                ];
            });

            // Overall statistics
            $overallStats = [
                'total_courses' => $enrollments->count(),
                'completed_courses' => $enrollments->where('status', 'completed')->count(),
                'average_grade' => $enrollments->whereNotNull('final_grade')->avg('final_grade'),
                'highest_grade' => $enrollments->whereNotNull('final_grade')->max('final_grade'),
                'lowest_grade' => $enrollments->whereNotNull('final_grade')->min('final_grade')
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'student' => $student,
                    'grades' => $gradeData,
                    'overall_stats' => $overallStats
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch student grades: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk grading operations
     */
    public function bulkGrade(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'type' => 'required|in:quiz,assignment,final',
                'course_id' => 'required|exists:courses,id',
                'grades' => 'required|array',
                'grades.*.student_id' => 'required|exists:users,id',
                'grades.*.grade' => 'required|numeric|min:0|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $course = Course::findOrFail($request->course_id);

            // Check permissions
            if ($course->instructor_id !== $user->id && !$user->hasAnyRole(['admin', 'superadmin'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to grade this course'
                ], 403);
            }

            $results = [
                'updated' => 0,
                'errors' => []
            ];

            foreach ($request->grades as $gradeData) {
                try {
                    switch ($request->type) {
                        case 'final':
                            $enrollment = Enrollment::where('user_id', $gradeData['student_id'])
                                                  ->where('course_id', $course->id)
                                                  ->first();
                            if ($enrollment) {
                                $enrollment->update(['final_grade' => $gradeData['grade']]);
                                $results['updated']++;
                            }
                            break;

                        case 'assignment':
                            if (isset($gradeData['assignment_id'])) {
                                $submission = AssignmentSubmission::where('user_id', $gradeData['student_id'])
                                                                ->where('assignment_id', $gradeData['assignment_id'])
                                                                ->first();
                                if ($submission) {
                                    $submission->update([
                                        'grade' => $gradeData['grade'],
                                        'graded_at' => now(),
                                        'status' => 'graded'
                                    ]);
                                    $results['updated']++;
                                }
                            }
                            break;
                    }
                } catch (\Exception $e) {
                    $results['errors'][] = [
                        'student_id' => $gradeData['student_id'],
                        'error' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Bulk grading completed. {$results['updated']} grades updated.",
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk grading failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get grading analytics
     */
    public function analytics(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user->hasRole('instructor') && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $query = Course::query();

            // If instructor, only their courses
            if ($user->hasRole('instructor')) {
                $query->where('instructor_id', $user->id);
            }

            $courses = $query->with(['enrollments'])->get();

            $analytics = [
                'overview' => [
                    'total_courses' => $courses->count(),
                    'total_students' => $courses->sum(function($course) {
                        return $course->enrollments->count();
                    }),
                    'graded_students' => $courses->sum(function($course) {
                        return $course->enrollments->whereNotNull('final_grade')->count();
                    }),
                    'average_grade_across_courses' => $this->calculateOverallAverageGrade($courses)
                ],
                'grade_distribution' => $this->getOverallGradeDistribution($courses),
                'course_performance' => $courses->map(function($course) {
                    $enrollments = $course->enrollments;
                    $gradedEnrollments = $enrollments->whereNotNull('final_grade');

                    return [
                        'course_id' => $course->id,
                        'course_title' => $course->title,
                        'total_students' => $enrollments->count(),
                        'graded_students' => $gradedEnrollments->count(),
                        'average_grade' => $gradedEnrollments->avg('final_grade'),
                        'pass_rate' => $gradedEnrollments->count() > 0 ?
                                     ($gradedEnrollments->where('final_grade', '>=', 60)->count() / $gradedEnrollments->count()) * 100 : 0
                    ];
                }),
                'trends' => $this->getGradingTrends($courses)
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
     * Export grades to CSV/Excel
     */
    public function exportGrades(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'course_id' => 'required|exists:courses,id',
                'format' => 'required|in:csv,excel'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $course = Course::findOrFail($request->course_id);

            // Check permissions
            if ($course->instructor_id !== $user->id && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to export grades for this course'
                ], 403);
            }

            // Get gradebook data
            $gradebookResponse = $this->gradebook($course->id, $request);
            $gradebookData = json_decode($gradebookResponse->getContent(), true);

            if (!$gradebookData['success']) {
                throw new \Exception('Failed to fetch gradebook data');
            }

            $gradebook = $gradebookData['data']['gradebook'];

            // Generate CSV content
            $csvContent = $this->generateGradesCsv($gradebook, $course);

            // Save to storage
            $fileName = "grades_{$course->slug}_" . now()->format('Y-m-d_H-i-s') . '.csv';
            $filePath = "exports/grades/{$fileName}";

            Storage::disk('public')->put($filePath, $csvContent);

            return response()->json([
                'success' => true,
                'message' => 'Grades exported successfully',
                'data' => [
                    'download_url' => Storage::disk('public')->url($filePath),
                    'file_name' => $fileName
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get grade change history
     */
    public function gradeHistory($studentId, $courseId)
    {
        try {
            $user = Auth::user();
            $student = User::findOrFail($studentId);
            $course = Course::findOrFail($courseId);

            // Check permissions
            $canView = $student->id === $user->id ||
                      $course->instructor_id === $user->id ||
                      $user->hasRole('admin');

            if (!$canView) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view grade history'
                ], 403);
            }

            // Get grade history from various sources
            $history = [];

            // Quiz attempt history
            $quizAttempts = QuizAttempt::with(['quiz'])
                                     ->where('user_id', $studentId)
                                     ->whereHas('quiz', function($q) use ($courseId) {
                                         $q->where('course_id', $courseId);
                                     })
                                     ->orderBy('completed_at', 'desc')
                                     ->get();

            foreach ($quizAttempts as $attempt) {
                $history[] = [
                    'type' => 'quiz',
                    'item_title' => $attempt->quiz->title,
                    'grade' => $attempt->score,
                    'max_points' => $attempt->quiz->total_points,
                    'date' => $attempt->completed_at,
                    'attempt_number' => $attempt->attempt_number
                ];
            }

            // Assignment submission history
            $submissions = AssignmentSubmission::with(['assignment'])
                                             ->where('user_id', $studentId)
                                             ->whereHas('assignment', function($q) use ($courseId) {
                                                 $q->where('course_id', $courseId);
                                             })
                                             ->orderBy('graded_at', 'desc')
                                             ->get();

            foreach ($submissions as $submission) {
                if ($submission->grade !== null) {
                    $history[] = [
                        'type' => 'assignment',
                        'item_title' => $submission->assignment->title,
                        'grade' => $submission->grade,
                        'max_points' => $submission->assignment->max_points,
                        'date' => $submission->graded_at,
                        'is_late' => $submission->is_late
                    ];
                }
            }

            // Sort by date
            usort($history, function($a, $b) {
                return $b['date'] <=> $a['date'];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'student' => $student,
                    'course' => $course,
                    'grade_history' => $history
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch grade history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update grade weights for a course
     */
    public function updateGradeWeights(Request $request, $courseId)
    {
        try {
            $course = Course::findOrFail($courseId);
            $user = Auth::user();

            // Check permissions
            if ($course->instructor_id !== $user->id && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update grade weights'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'weights' => 'required|array',
                'weights.quizzes' => 'required|numeric|min:0|max:100',
                'weights.assignments' => 'required|numeric|min:0|max:100',
                'weights.participation' => 'nullable|numeric|min:0|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validate weights sum to 100
            $totalWeight = $request->weights['quizzes'] + $request->weights['assignments'] + ($request->weights['participation'] ?? 0);

            if ($totalWeight !== 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Grade weights must sum to 100%'
                ], 400);
            }

            $course->update([
                'grade_weights' => $request->weights
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Grade weights updated successfully',
                'data' => $course
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update grade weights: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add grading comments
     */
    public function addGradingComments(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'type' => 'required|in:quiz,assignment,course',
                'item_id' => 'required|integer',
                'student_id' => 'required|exists:users,id',
                'comment' => 'required|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Add comment based on type
            switch ($request->type) {
                case 'assignment':
                    $submission = AssignmentSubmission::where('assignment_id', $request->item_id)
                                                    ->where('user_id', $request->student_id)
                                                    ->first();
                    if ($submission) {
                        $submission->update(['feedback' => $request->comment]);
                    }
                    break;

                case 'course':
                    $enrollment = Enrollment::where('course_id', $request->item_id)
                                          ->where('user_id', $request->student_id)
                                          ->first();
                    if ($enrollment) {
                        $enrollment->update(['instructor_comments' => $request->comment]);
                    }
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get grading reports
     */
    public function reports($courseId)
    {
        try {
            $course = Course::findOrFail($courseId);
            $user = Auth::user();

            // Check permissions
            if ($course->instructor_id !== $user->id && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view reports'
                ], 403);
            }

            $enrollments = Enrollment::with('user')->where('course_id', $courseId)->get();

            $reports = [
                'grade_summary' => [
                    'total_students' => $enrollments->count(),
                    'graded_students' => $enrollments->whereNotNull('final_grade')->count(),
                    'average_grade' => $enrollments->whereNotNull('final_grade')->avg('final_grade'),
                    'median_grade' => $this->calculateMedianGrade($enrollments),
                    'standard_deviation' => $this->calculateStandardDeviation($enrollments)
                ],
                'performance_bands' => $this->getPerformanceBands($enrollments),
                'at_risk_students' => $this->getAtRiskStudents($enrollments),
                'top_performers' => $this->getTopPerformers($enrollments),
                'completion_timeline' => $this->getCompletionTimeline($enrollments)
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'course' => $course,
                    'reports' => $reports
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate reports: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper methods
     */
    private function calculateOverallGrade($studentData, $course)
    {
        $weights = $course->grade_weights ?? ['quizzes' => 50, 'assignments' => 50];

        $quizAverage = collect($studentData['quiz_grades'])->where('score', '!=', null)->avg('score');
        $assignmentAverage = collect($studentData['assignment_grades'])->where('grade', '!=', null)->avg('grade');

        if ($quizAverage === null && $assignmentAverage === null) {
            return null;
        }

        $overallGrade = 0;
        $totalWeight = 0;

        if ($quizAverage !== null) {
            $overallGrade += $quizAverage * ($weights['quizzes'] / 100);
            $totalWeight += $weights['quizzes'];
        }

        if ($assignmentAverage !== null) {
            $overallGrade += $assignmentAverage * ($weights['assignments'] / 100);
            $totalWeight += $weights['assignments'];
        }

        return $totalWeight > 0 ? round($overallGrade * (100 / $totalWeight), 2) : null;
    }

    private function calculateCompletionPercentage($student, $course)
    {
        $totalLessons = $course->lessons()->count();
        $completedLessons = \App\Models\LessonCompletion::where('user_id', $student->id)
                                                       ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                                                       ->count();

        return $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 2) : 0;
    }

    private function calculateQuizCompletionRate($students, $quizzes)
    {
        if ($students->count() === 0 || $quizzes->count() === 0) {
            return 0;
        }

        $totalPossibleAttempts = $students->count() * $quizzes->count();
        $completedAttempts = QuizAttempt::whereIn('quiz_id', $quizzes->pluck('id'))
                                      ->whereIn('user_id', $students->pluck('id'))
                                      ->where('status', 'completed')
                                      ->distinct(['user_id', 'quiz_id'])
                                      ->count();

        return round(($completedAttempts / $totalPossibleAttempts) * 100, 2);
    }

    private function calculateAssignmentSubmissionRate($students, $assignments)
    {
        if ($students->count() === 0 || $assignments->count() === 0) {
            return 0;
        }

        $totalPossibleSubmissions = $students->count() * $assignments->count();
        $actualSubmissions = AssignmentSubmission::whereIn('assignment_id', $assignments->pluck('id'))
                                                ->whereIn('user_id', $students->pluck('id'))
                                                ->count();

        return round(($actualSubmissions / $totalPossibleSubmissions) * 100, 2);
    }

    private function calculateGradeBreakdown($enrollment, $quizGrades, $assignmentGrades)
    {
        $quizAverage = $quizGrades->avg('score');
        $assignmentAverage = $assignmentGrades->avg('grade');

        return [
            'quiz_average' => $quizAverage ? round($quizAverage, 2) : null,
            'assignment_average' => $assignmentAverage ? round($assignmentAverage, 2) : null,
            'final_grade' => $enrollment->final_grade,
            'quiz_count' => $quizGrades->count(),
            'assignment_count' => $assignmentGrades->count()
        ];
    }

    private function calculateOverallAverageGrade($courses)
    {
        $allGrades = $courses->flatMap(function($course) {
            return $course->enrollments->whereNotNull('final_grade')->pluck('final_grade');
        });

        return $allGrades->count() > 0 ? round($allGrades->avg(), 2) : null;
    }

    private function getOverallGradeDistribution($courses)
    {
        $distribution = [
            'A (90-100)' => 0,
            'B (80-89)' => 0,
            'C (70-79)' => 0,
            'D (60-69)' => 0,
            'F (0-59)' => 0
        ];

        $courses->each(function($course) use (&$distribution) {
            $course->enrollments->whereNotNull('final_grade')->each(function($enrollment) use (&$distribution) {
                $grade = $enrollment->final_grade;
                if ($grade >= 90) $distribution['A (90-100)']++;
                elseif ($grade >= 80) $distribution['B (80-89)']++;
                elseif ($grade >= 70) $distribution['C (70-79)']++;
                elseif ($grade >= 60) $distribution['D (60-69)']++;
                else $distribution['F (0-59)']++;
            });
        });

        return $distribution;
    }

    private function getGradingTrends($courses)
    {
        // Mock implementation - would calculate actual trends
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'average_grade' => rand(75, 85) + (rand(0, 100) / 100)
            ];
        }
        return $months;
    }

    private function generateGradesCsv($gradebook, $course)
    {
        $csv = "Student Name,Email,Final Grade,Quiz Average,Assignment Average,Enrollment Status\n";

        foreach ($gradebook as $student) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s\n",
                $student['student_name'],
                $student['student_email'],
                $student['final_grade'] ?? 'N/A',
                $student['overall_stats']['quiz_average'] ?? 'N/A',
                $student['overall_stats']['assignment_average'] ?? 'N/A',
                $student['enrollment_status']
            );
        }

        return $csv;
    }

    private function calculateMedianGrade($enrollments)
    {
        $grades = $enrollments->whereNotNull('final_grade')->pluck('final_grade')->sort()->values();
        $count = $grades->count();

        if ($count === 0) return null;

        if ($count % 2 === 0) {
            return ($grades[$count / 2 - 1] + $grades[$count / 2]) / 2;
        } else {
            return $grades[floor($count / 2)];
        }
    }

    private function calculateStandardDeviation($enrollments)
    {
        $grades = $enrollments->whereNotNull('final_grade')->pluck('final_grade');
        $count = $grades->count();

        if ($count < 2) return null;

        $mean = $grades->avg();
        $variance = $grades->sum(function($grade) use ($mean) {
            return pow($grade - $mean, 2);
        }) / ($count - 1);

        return round(sqrt($variance), 2);
    }

    private function getPerformanceBands($enrollments)
    {
        $graded = $enrollments->whereNotNull('final_grade');
        $total = $graded->count();

        if ($total === 0) return [];

        return [
            'Excellent (90-100)' => round(($graded->where('final_grade', '>=', 90)->count() / $total) * 100, 1),
            'Good (80-89)' => round(($graded->whereBetween('final_grade', [80, 89])->count() / $total) * 100, 1),
            'Satisfactory (70-79)' => round(($graded->whereBetween('final_grade', [70, 79])->count() / $total) * 100, 1),
            'Needs Improvement (60-69)' => round(($graded->whereBetween('final_grade', [60, 69])->count() / $total) * 100, 1),
            'Failing (0-59)' => round(($graded->where('final_grade', '<', 60)->count() / $total) * 100, 1)
        ];
    }

    private function getAtRiskStudents($enrollments)
    {
        return $enrollments->filter(function($enrollment) {
            return $enrollment->final_grade !== null && $enrollment->final_grade < 70;
        })->map(function($enrollment) {
            return [
                'student' => $enrollment->user,
                'grade' => $enrollment->final_grade,
                'status' => $enrollment->status
            ];
        })->values();
    }

    private function getTopPerformers($enrollments)
    {
        return $enrollments->filter(function($enrollment) {
            return $enrollment->final_grade !== null && $enrollment->final_grade >= 90;
        })->sortByDesc('final_grade')->map(function($enrollment) {
            return [
                'student' => $enrollment->user,
                'grade' => $enrollment->final_grade,
                'status' => $enrollment->status
            ];
        })->values()->take(10);
    }

    private function getCompletionTimeline($enrollments)
    {
        return $enrollments->where('status', 'completed')
                         ->whereNotNull('completed_at')
                         ->groupBy(function($enrollment) {
                             return $enrollment->completed_at->format('Y-m');
                         })
                         ->map(function($group, $month) {
                             return [
                                 'month' => $month,
                                 'completions' => $group->count(),
                                 'average_grade' => round($group->avg('final_grade'), 2)
                             ];
                         })
                         ->values();
    }
}
