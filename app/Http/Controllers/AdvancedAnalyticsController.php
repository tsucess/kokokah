<?php

namespace App\Http\Controllers;

use App\Services\AdvancedAnalyticsService;
use App\Models\StudentSuccessPrediction;
use App\Models\CohortAnalysis;
use App\Models\EngagementScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvancedAnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AdvancedAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Get student predictions
     */
    public function getStudentPredictions($studentId)
    {
        try {
            $user = Auth::user();

            // Check authorization
            if ($user->id !== (int)$studentId && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $predictions = $this->analyticsService->getStudentPredictions($studentId);

            return response()->json([
                'success' => true,
                'data' => $predictions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch predictions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate student predictions
     */
    public function calculateStudentPredictions($studentId)
    {
        try {
            $predictions = $this->analyticsService->predictAllStudentSuccess($studentId);

            return response()->json([
                'success' => true,
                'message' => 'Predictions calculated successfully',
                'data' => $predictions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate predictions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * List all cohorts
     */
    public function listCohorts()
    {
        try {
            $cohorts = CohortAnalysis::all();

            return response()->json([
                'success' => true,
                'data' => $cohorts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cohorts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new cohort
     */
    public function createCohort(Request $request)
    {
        try {
            $validated = $request->validate([
                'cohort_name' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date'
            ]);

            $cohort = $this->analyticsService->generateCohort(
                $validated['cohort_name'],
                $validated['start_date'],
                $validated['end_date']
            );

            return response()->json([
                'success' => true,
                'message' => 'Cohort created successfully',
                'data' => $cohort
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create cohort: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cohort analysis
     */
    public function getCohortAnalysis($cohortId)
    {
        try {
            $analysis = $this->analyticsService->getCohortAnalysis($cohortId);

            if (!$analysis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cohort not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $analysis
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cohort analysis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Compare two cohorts
     */
    public function compareCohorts($cohortId1, $cohortId2)
    {
        try {
            $comparison = $this->analyticsService->compareCohorts($cohortId1, $cohortId2);

            if (!$comparison) {
                return response()->json([
                    'success' => false,
                    'message' => 'One or both cohorts not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $comparison
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to compare cohorts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course engagement scores
     */
    public function getCourseEngagement($courseId)
    {
        try {
            $engagement = $this->analyticsService->getCourseEngagementScores($courseId);

            return response()->json([
                'success' => true,
                'data' => $engagement
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch engagement scores: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get student engagement score
     */
    public function getStudentEngagement($studentId, $courseId)
    {
        try {
            $engagement = $this->analyticsService->getStudentEngagementScore($studentId, $courseId);

            if (!$engagement) {
                return response()->json([
                    'success' => false,
                    'message' => 'Engagement score not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $engagement
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch engagement score: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate course engagement
     */
    public function calculateCourseEngagement($courseId)
    {
        try {
            $scores = $this->analyticsService->calculateCourseEngagement($courseId);

            return response()->json([
                'success' => true,
                'message' => 'Engagement scores calculated successfully',
                'data' => $scores
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate engagement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get at-risk students
     */
    public function getAtRiskStudents($courseId)
    {
        try {
            $students = $this->analyticsService->getAtRiskStudents($courseId);

            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch at-risk students: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get high-performing students
     */
    public function getHighPerformingStudents($courseId)
    {
        try {
            $students = $this->analyticsService->getHighPerformingStudents($courseId);

            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch high-performing students: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get analytics dashboard
     */
    public function getDashboard()
    {
        try {
            $dashboard = $this->analyticsService->getDashboardData();

            return response()->json([
                'success' => true,
                'data' => $dashboard
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard: ' . $e->getMessage()
            ], 500);
        }
    }
}

