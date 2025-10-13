<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get user's certificates
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Certificate::with(['course.category', 'course.instructor'])
                           ->where('user_id', $user->id);

        // Filter by course
        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->where('issued_at', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->where('issued_at', '<=', $request->to_date);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'issued_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $certificates = $query->paginate($request->get('per_page', 12));

        return response()->json([
            'success' => true,
            'data' => $certificates
        ]);
    }

    /**
     * Get a specific certificate
     */
    public function show($id)
    {
        try {
            $certificate = Certificate::with(['course.category', 'course.instructor', 'user'])
                                    ->findOrFail($id);

            $user = Auth::user();

            // Check if user owns this certificate or is admin/instructor
            $canView = $certificate->user_id === $user->id || 
                      $certificate->course->instructor_id === $user->id ||
                      $user->hasRole('admin');

            if (!$canView) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificate not found or access denied'
                ], 404);
            }

            // Add verification URL
            $certificateData = $certificate->toArray();
            $certificateData['verification_url'] = url("/api/certificates/verify/{$certificate->certificate_number}");
            $certificateData['download_url'] = url("/api/certificates/{$certificate->id}/download");

            return response()->json([
                'success' => true,
                'data' => $certificateData
            ]);
        } catch (\Exception) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate not found'
            ], 404);
        }
    }

    /**
     * Generate certificate for a course completion
     */
    public function generate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'course_id' => 'required|exists:courses,id',
                'user_id' => 'required|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $course = Course::findOrFail($request->course_id);
            $student = User::findOrFail($request->user_id);
            $user = Auth::user();

            // Check permissions (instructor or admin)
            if ($course->instructor_id !== $user->id && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to generate certificates for this course'
                ], 403);
            }

            // Check if student completed the course
            $enrollment = $course->enrollments()
                               ->where('user_id', $student->id)
                               ->where('status', 'completed')
                               ->first();

            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student has not completed this course'
                ], 400);
            }

            // Check if certificate already exists
            $existingCertificate = Certificate::where('user_id', $student->id)
                                            ->where('course_id', $course->id)
                                            ->first();

            if ($existingCertificate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificate already exists for this student and course',
                    'data' => $existingCertificate
                ], 400);
            }

            // Generate certificate
            $certificate = Certificate::create([
                'user_id' => $student->id,
                'course_id' => $course->id,
                'certificate_number' => $this->generateCertificateNumber(),
                'issued_at' => now(),
                'issued_by' => $user->id,
                'grade' => $enrollment->final_grade,
                'completion_date' => $enrollment->completed_at,
                'expires_at' => null // Certificates don't expire by default
            ]);

            // Generate PDF certificate
            $pdfPath = $this->generateCertificatePDF($certificate);
            $certificate->update(['file_path' => $pdfPath]);

            return response()->json([
                'success' => true,
                'message' => 'Certificate generated successfully',
                'data' => $certificate->load(['course', 'user'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate certificate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify a certificate by certificate number
     */
    public function verify($certificateNumber)
    {
        try {
            $certificate = Certificate::with(['course.category', 'course.instructor', 'user'])
                                    ->where('certificate_number', $certificateNumber)
                                    ->first();

            if (!$certificate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificate not found',
                    'valid' => false
                ], 404);
            }

            // Check if certificate is still valid
            $isValid = true;
            $validationMessages = [];

            if ($certificate->expires_at && $certificate->expires_at < now()) {
                $isValid = false;
                $validationMessages[] = 'Certificate has expired';
            }

            if ($certificate->revoked_at) {
                $isValid = false;
                $validationMessages[] = 'Certificate has been revoked';
            }

            $verificationData = [
                'valid' => $isValid,
                'certificate_number' => $certificate->certificate_number,
                'student_name' => $certificate->user->first_name . ' ' . $certificate->user->last_name,
                'course_title' => $certificate->course->title,
                'instructor_name' => $certificate->course->instructor->first_name . ' ' . $certificate->course->instructor->last_name,
                'issued_at' => $certificate->issued_at,
                'completion_date' => $certificate->completion_date,
                'grade' => $certificate->grade,
                'expires_at' => $certificate->expires_at,
                'validation_messages' => $validationMessages
            ];

            return response()->json([
                'success' => true,
                'data' => $verificationData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Verification failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download certificate PDF
     */
    public function download($id)
    {
        try {
            $certificate = Certificate::findOrFail($id);
            $user = Auth::user();

            // Check if user owns this certificate or is admin/instructor
            $canDownload = $certificate->user_id === $user->id || 
                          $certificate->course->instructor_id === $user->id ||
                          $user->hasRole('admin');

            if (!$canDownload) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to download this certificate'
                ], 403);
            }

            if (!$certificate->file_path || !Storage::disk('public')->exists($certificate->file_path)) {
                // Generate PDF if it doesn't exist
                $pdfPath = $this->generateCertificatePDF($certificate);
                $certificate->update(['file_path' => $pdfPath]);
            }

            $fileName = "certificate_{$certificate->certificate_number}.pdf";
            
            return Storage::disk('public')->download($certificate->file_path, $fileName);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to download certificate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Revoke a certificate
     */
    public function revoke(Request $request, $id)
    {
        try {
            $certificate = Certificate::findOrFail($id);
            $user = Auth::user();

            // Check permissions (instructor or admin)
            if ($certificate->course->instructor_id !== $user->id && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to revoke this certificate'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'reason' => 'required|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $certificate->update([
                'revoked_at' => now(),
                'revoked_by' => $user->id,
                'revocation_reason' => $request->reason
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Certificate revoked successfully',
                'data' => $certificate
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to revoke certificate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get certificate templates (for admins)
     */
    public function templates()
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $templates = [
            [
                'id' => 'default',
                'name' => 'Default Certificate',
                'description' => 'Standard certificate template',
                'preview_url' => url('/images/certificate-templates/default-preview.png')
            ],
            [
                'id' => 'modern',
                'name' => 'Modern Certificate',
                'description' => 'Modern design with clean layout',
                'preview_url' => url('/images/certificate-templates/modern-preview.png')
            ],
            [
                'id' => 'classic',
                'name' => 'Classic Certificate',
                'description' => 'Traditional certificate design',
                'preview_url' => url('/images/certificate-templates/classic-preview.png')
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }

    /**
     * Get certificate analytics (for instructors/admins)
     */
    public function analytics(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('instructor') && !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $query = Certificate::with(['course', 'user']);

        // If instructor, only show certificates for their courses
        if ($user->hasRole('instructor')) {
            $courseIds = Course::where('instructor_id', $user->id)->pluck('id');
            $query->whereIn('course_id', $courseIds);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->where('issued_at', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->where('issued_at', '<=', $request->to_date);
        }

        $certificates = $query->get();

        $analytics = [
            'overview' => [
                'total_certificates' => $certificates->count(),
                'certificates_this_month' => $certificates->where('issued_at', '>=', now()->startOfMonth())->count(),
                'certificates_this_year' => $certificates->where('issued_at', '>=', now()->startOfYear())->count(),
                'revoked_certificates' => $certificates->whereNotNull('revoked_at')->count()
            ],
            'trends' => [
                'monthly_issuance' => $this->getMonthlyCertificateIssuance($certificates),
                'course_distribution' => $this->getCertificateCourseDistribution($certificates),
                'grade_distribution' => $this->getCertificateGradeDistribution($certificates)
            ],
            'top_courses' => $this->getTopCertificateCourses($certificates),
            'recent_certificates' => $certificates->sortByDesc('issued_at')->take(10)->values()
        ];

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Bulk generate certificates
     */
    public function bulkGenerate(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'course_id' => 'required|exists:courses,id',
                'user_ids' => 'required|array',
                'user_ids.*' => 'exists:users,id'
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
                    'message' => 'Unauthorized to generate certificates for this course'
                ], 403);
            }

            $results = [
                'generated' => [],
                'skipped' => [],
                'errors' => []
            ];

            foreach ($request->user_ids as $userId) {
                try {
                    $student = User::findOrFail($userId);

                    // Check if student completed the course
                    $enrollment = $course->enrollments()
                                       ->where('user_id', $student->id)
                                       ->where('status', 'completed')
                                       ->first();

                    if (!$enrollment) {
                        $results['skipped'][] = [
                            'user_id' => $userId,
                            'reason' => 'Course not completed'
                        ];
                        continue;
                    }

                    // Check if certificate already exists
                    $existingCertificate = Certificate::where('user_id', $student->id)
                                                    ->where('course_id', $course->id)
                                                    ->first();

                    if ($existingCertificate) {
                        $results['skipped'][] = [
                            'user_id' => $userId,
                            'reason' => 'Certificate already exists'
                        ];
                        continue;
                    }

                    // Generate certificate
                    $certificate = Certificate::create([
                        'user_id' => $student->id,
                        'course_id' => $course->id,
                        'certificate_number' => $this->generateCertificateNumber(),
                        'issued_at' => now(),
                        'issued_by' => $user->id,
                        'grade' => $enrollment->final_grade,
                        'completion_date' => $enrollment->completed_at,
                        'expires_at' => null
                    ]);

                    // Generate PDF
                    $pdfPath = $this->generateCertificatePDF($certificate);
                    $certificate->update(['file_path' => $pdfPath]);

                    $results['generated'][] = $certificate;
                } catch (\Exception $e) {
                    $results['errors'][] = [
                        'user_id' => $userId,
                        'error' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Bulk certificate generation completed',
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk generation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to generate unique certificate number
     */
    private function generateCertificateNumber()
    {
        do {
            $number = 'CERT-' . strtoupper(uniqid()) . '-' . date('Y');
        } while (Certificate::where('certificate_number', $number)->exists());

        return $number;
    }

    /**
     * Helper method to generate certificate PDF
     */
    private function generateCertificatePDF($certificate)
    {
        // This would integrate with a PDF generation library like TCPDF or DomPDF
        // For now, return a mock path
        $fileName = "certificate_{$certificate->certificate_number}.pdf";
        $path = "certificates/{$fileName}";

        // Mock PDF generation
        $pdfContent = $this->generateCertificateContent($certificate);
        Storage::disk('public')->put($path, $pdfContent);

        return $path;
    }

    /**
     * Helper method to generate certificate content
     */
    private function generateCertificateContent($certificate)
    {
        // This would generate actual PDF content
        // For now, return mock content
        return "Certificate of Completion\n\n" .
               "This certifies that {$certificate->user->first_name} {$certificate->user->last_name}\n" .
               "has successfully completed the course:\n" .
               "{$certificate->course->title}\n\n" .
               "Issued on: {$certificate->issued_at->format('F j, Y')}\n" .
               "Certificate Number: {$certificate->certificate_number}";
    }

    /**
     * Helper methods for analytics
     */
    private function getMonthlyCertificateIssuance($certificates)
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'count' => $certificates->filter(function($cert) use ($date) {
                    return $cert->issued_at->year === $date->year &&
                           $cert->issued_at->month === $date->month;
                })->count()
            ];
        }
        return $months;
    }

    private function getCertificateCourseDistribution($certificates)
    {
        return $certificates->groupBy('course.title')
                          ->map(function($certs, $courseTitle) {
                              return [
                                  'course' => $courseTitle,
                                  'count' => $certs->count()
                              ];
                          })
                          ->sortByDesc('count')
                          ->values()
                          ->take(10);
    }

    private function getCertificateGradeDistribution($certificates)
    {
        $distribution = [
            'A (90-100)' => 0,
            'B (80-89)' => 0,
            'C (70-79)' => 0,
            'D (60-69)' => 0,
            'F (0-59)' => 0
        ];

        foreach ($certificates as $cert) {
            if ($cert->grade >= 90) $distribution['A (90-100)']++;
            elseif ($cert->grade >= 80) $distribution['B (80-89)']++;
            elseif ($cert->grade >= 70) $distribution['C (70-79)']++;
            elseif ($cert->grade >= 60) $distribution['D (60-69)']++;
            else $distribution['F (0-59)']++;
        }

        return $distribution;
    }

    private function getTopCertificateCourses($certificates)
    {
        return $certificates->groupBy('course_id')
                          ->map(function($certs) {
                              $course = $certs->first()->course;
                              return [
                                  'course_id' => $course->id,
                                  'course_title' => $course->title,
                                  'certificates_count' => $certs->count(),
                                  'average_grade' => round($certs->avg('grade'), 2)
                              ];
                          })
                          ->sortByDesc('certificates_count')
                          ->values()
                          ->take(5);
    }
}
