<?php

namespace App\Http\Controllers;

use App\Models\CourseReview;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    // Note: Middleware is applied at route level in Laravel 12
    // See routes/api.php for middleware configuration

    /**
     * Get course reviews
     */
    public function index($courseId, Request $request)
    {
        try {
            $course = Course::findOrFail($courseId);
            
            $query = CourseReview::with(['user'])
                               ->where('course_id', $courseId)
                               ->where('status', 'approved');

            // Filter by rating
            if ($request->has('rating')) {
                $query->where('rating', $request->rating);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            if ($sortBy === 'helpful') {
                $query->orderBy('helpful_count', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }

            $reviews = $query->paginate($request->get('per_page', 10));

            // Add review statistics
            $stats = [
                'total_reviews' => CourseReview::where('course_id', $courseId)->where('status', 'approved')->count(),
                'average_rating' => CourseReview::where('course_id', $courseId)->where('status', 'approved')->avg('rating'),
                'rating_distribution' => $this->getRatingDistribution($courseId)
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'course' => $course,
                    'reviews' => $reviews,
                    'statistics' => $stats
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reviews: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new review
     */
    public function store(Request $request, $courseId)
    {
        try {
            $course = Course::findOrFail($courseId);
            $user = Auth::user();

            // Check if user is enrolled in the course
            $isEnrolled = $course->enrollments()->where('user_id', $user->id)->exists();
            if (!$isEnrolled) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled in this course to leave a review'
                ], 403);
            }

            // Check if user has already reviewed this course
            $existingReview = CourseReview::where('course_id', $courseId)
                                        ->where('user_id', $user->id)
                                        ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reviewed this course'
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'rating' => 'required|integer|min:1|max:5',
                'title' => 'required|string|max:255',
                'comment' => 'required|string|max:1000',
                'pros' => 'nullable|array',
                'cons' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $review = CourseReview::create([
                'course_id' => $courseId,
                'user_id' => $user->id,
                'rating' => $request->rating,
                'title' => $request->title,
                'comment' => $request->comment,
                'pros' => $request->pros,
                'cons' => $request->cons,
                'status' => 'pending' // Reviews need approval
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully and is pending approval',
                'data' => $review
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific review
     */
    public function show($id)
    {
        try {
            $review = CourseReview::with(['user', 'course'])->findOrFail($id);

            // Check if user can view this review
            $user = Auth::user();
            $canView = $review->status === 'approved' || 
                      $review->user_id === $user->id || 
                      $review->course->instructor_id === $user->id ||
                      $user->hasRole('admin');

            if (!$canView) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found or not accessible'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $review
            ]);
        } catch (\Exception) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }
    }

    /**
     * Update a review
     */
    public function update(Request $request, $id)
    {
        try {
            $review = CourseReview::findOrFail($id);
            $user = Auth::user();

            // Check if user owns this review
            if ($review->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this review'
                ], 403);
            }

            // Check if review can be updated (only pending or approved reviews)
            if ($review->status === 'rejected') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update a rejected review'
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'rating' => 'sometimes|integer|min:1|max:5',
                'title' => 'sometimes|string|max:255',
                'comment' => 'sometimes|string|max:1000',
                'pros' => 'nullable|array',
                'cons' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = $request->only(['rating', 'title', 'comment', 'pros', 'cons']);
            
            // If review was approved and is being updated, set back to pending
            if ($review->status === 'approved') {
                $updateData['status'] = 'pending';
            }

            $review->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully',
                'data' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a review
     */
    public function destroy($id)
    {
        try {
            $review = CourseReview::findOrFail($id);
            $user = Auth::user();

            // Check if user owns this review or is admin
            if ($review->user_id !== $user->id && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this review'
                ], 403);
            }

            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark review as helpful
     */
    public function markHelpful($id)
    {
        try {
            $review = CourseReview::findOrFail($id);
            $user = Auth::user();

            // Check if review is approved
            if ($review->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot mark unapproved review as helpful'
                ], 400);
            }

            // Check if user has already marked this review as helpful
            $existingHelpful = \App\Models\ReviewHelpful::where('review_id', $id)
                                                       ->where('user_id', $user->id)
                                                       ->first();

            if ($existingHelpful) {
                // Remove helpful mark
                $existingHelpful->delete();
                $review->decrement('helpful_count');
                $message = 'Review helpful mark removed';
            } else {
                // Add helpful mark
                \App\Models\ReviewHelpful::create([
                    'review_id' => $id,
                    'user_id' => $user->id
                ]);
                $review->increment('helpful_count');
                $message = 'Review marked as helpful';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'helpful_count' => $review->fresh()->helpful_count,
                    'is_helpful' => !$existingHelpful
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark review as helpful: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reviews for moderation (admin/superadmin/instructor)
     */
    public function moderate(Request $request)
    {
        $user = Auth::user();

        // Check if user is admin, superadmin or instructor
        if (!$user->hasAnyRole(['admin', 'superadmin', 'instructor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $query = CourseReview::with(['user', 'course']);

        // If instructor, only show reviews for their courses
        if ($user->hasRole('instructor')) {
            $courseIds = Course::where('instructor_id', $user->id)->pluck('id');
            $query->whereIn('course_id', $courseIds);
        }

        // Filter by status
        $status = $request->get('status', 'pending');
        $query->where('status', $status);

        $reviews = $query->orderBy('created_at', 'desc')
                        ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    /**
     * Approve a review
     */
    public function approve($id)
    {
        try {
            $review = CourseReview::findOrFail($id);
            $user = Auth::user();

            // Check permissions
            $canApprove = $user->hasRole('admin') ||
                         ($user->hasRole('instructor') && $review->course->instructor_id === $user->id);

            if (!$canApprove) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to approve this review'
                ], 403);
            }

            $review->update([
                'status' => 'approved',
                'moderated_by' => $user->id,
                'moderated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review approved successfully',
                'data' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a review
     */
    public function reject(Request $request, $id)
    {
        try {
            $review = CourseReview::findOrFail($id);
            $user = Auth::user();

            // Check permissions
            $canReject = $user->hasRole('admin') ||
                        ($user->hasRole('instructor') && $review->course->instructor_id === $user->id);

            if (!$canReject) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to reject this review'
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

            $review->update([
                'status' => 'rejected',
                'rejection_reason' => $request->reason,
                'moderated_by' => $user->id,
                'moderated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review rejected successfully',
                'data' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get review analytics
     */
    public function analytics($courseId)
    {
        try {
            $course = Course::findOrFail($courseId);
            $user = Auth::user();

            // Check if user is instructor of this course or admin
            if ($course->instructor_id !== $user->id && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view analytics for this course'
                ], 403);
            }

            $reviews = CourseReview::where('course_id', $courseId)->where('status', 'approved');

            $analytics = [
                'overview' => [
                    'total_reviews' => $reviews->count(),
                    'average_rating' => round($reviews->avg('rating'), 2),
                    'rating_distribution' => $this->getRatingDistribution($courseId),
                    'response_rate' => $this->getResponseRate($courseId)
                ],
                'sentiment_analysis' => [
                    'positive_reviews' => $reviews->where('rating', '>=', 4)->count(),
                    'neutral_reviews' => $reviews->where('rating', 3)->count(),
                    'negative_reviews' => $reviews->where('rating', '<=', 2)->count()
                ],
                'trends' => [
                    'monthly_reviews' => $this->getMonthlyReviewTrend($courseId),
                    'rating_trend' => $this->getRatingTrend($courseId)
                ],
                'top_keywords' => $this->getTopKeywords($courseId),
                'helpful_reviews' => $reviews->orderBy('helpful_count', 'desc')->limit(5)->get(),
                'recent_reviews' => $reviews->orderBy('created_at', 'desc')->limit(5)->get()
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
     * Get user's reviews
     */
    public function userReviews()
    {
        $user = Auth::user();

        $reviews = CourseReview::with(['course.category'])
                             ->where('user_id', $user->id)
                             ->orderBy('created_at', 'desc')
                             ->get();

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    /**
     * Helper method to get rating distribution
     */
    private function getRatingDistribution($courseId)
    {
        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = CourseReview::where('course_id', $courseId)
                                          ->where('status', 'approved')
                                          ->where('rating', $i)
                                          ->count();
        }
        return $distribution;
    }

    /**
     * Helper method to get response rate
     */
    private function getResponseRate($courseId)
    {
        $totalEnrollments = \App\Models\Enrollment::where('course_id', $courseId)->count();
        $totalReviews = CourseReview::where('course_id', $courseId)->where('status', 'approved')->count();

        return $totalEnrollments > 0 ? round(($totalReviews / $totalEnrollments) * 100, 2) : 0;
    }

    /**
     * Helper method to get monthly review trend
     */
    private function getMonthlyReviewTrend($courseId)
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'count' => CourseReview::where('course_id', $courseId)
                                     ->where('status', 'approved')
                                     ->whereYear('created_at', $date->year)
                                     ->whereMonth('created_at', $date->month)
                                     ->count(),
                'average_rating' => CourseReview::where('course_id', $courseId)
                                               ->where('status', 'approved')
                                               ->whereYear('created_at', $date->year)
                                               ->whereMonth('created_at', $date->month)
                                               ->avg('rating')
            ];
        }
        return $months;
    }

    /**
     * Helper method to get rating trend
     */
    private function getRatingTrend($courseId)
    {
        $currentMonth = CourseReview::where('course_id', $courseId)
                                  ->where('status', 'approved')
                                  ->whereMonth('created_at', now()->month)
                                  ->avg('rating');

        $lastMonth = CourseReview::where('course_id', $courseId)
                                ->where('status', 'approved')
                                ->whereMonth('created_at', now()->subMonth()->month)
                                ->avg('rating');

        return [
            'current_month' => round($currentMonth, 2),
            'last_month' => round($lastMonth, 2),
            'trend' => $currentMonth > $lastMonth ? 'increasing' : ($currentMonth < $lastMonth ? 'decreasing' : 'stable')
        ];
    }

    /**
     * Helper method to get top keywords from reviews
     */
    private function getTopKeywords($courseId)
    {
        $reviews = CourseReview::where('course_id', $courseId)
                             ->where('status', 'approved')
                             ->pluck('comment');

        // Simple keyword extraction (in real implementation, use NLP library)
        $keywords = [];
        $commonWords = ['the', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'is', 'was', 'are', 'were', 'this', 'that', 'course', 'very', 'good', 'great'];

        foreach ($reviews as $comment) {
            $words = str_word_count(strtolower($comment), 1);
            foreach ($words as $word) {
                if (strlen($word) > 3 && !in_array($word, $commonWords)) {
                    $keywords[$word] = ($keywords[$word] ?? 0) + 1;
                }
            }
        }

        arsort($keywords);
        return array_slice($keywords, 0, 10, true);
    }
}
