<?php

namespace App\Http\Controllers;

use App\Models\CourseReview;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Show ratings overview page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get courses based on user role
        if ($user->hasRole('admin')) {
            $courses = Course::with(['reviews' => function ($query) {
                $query->where('status', 'approved');
            }])->get();
        } else {
            // Instructors see only their courses
            $courses = Course::where('instructor_id', $user->id)
                            ->with(['reviews' => function ($query) {
                                $query->where('status', 'approved');
                            }])->get();
        }

        // Calculate statistics for each course
        $coursesWithStats = $courses->map(function ($course) {
            $reviews = CourseReview::where('course_id', $course->id)
                                  ->where('status', 'approved')
                                  ->get();
            
            return [
                'id' => $course->id,
                'title' => $course->title,
                'average_rating' => $reviews->avg('rating') ?? 0,
                'total_reviews' => $reviews->count(),
                'rating_distribution' => $this->getRatingDistribution($course->id),
            ];
        });

        return view('admin.rating', [
            'courses' => $coursesWithStats
        ]);
    }

    /**
     * Show detailed ratings for a specific course
     */
    public function show($courseId, Request $request)
    {
        $user = Auth::user();
        $course = Course::findOrFail($courseId);

        // Check authorization
        if (!$user->hasRole('admin') && $course->instructor_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Get reviews with filters
        $query = CourseReview::where('course_id', $courseId)
                            ->with(['user', 'moderator']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'approved');
        }

        // Filter by rating
        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'helpful') {
            $query->orderBy('helpful_count', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $reviews = $query->paginate(10);

        // Get statistics
        $allReviews = CourseReview::where('course_id', $courseId)->get();
        $stats = [
            'total_reviews' => $allReviews->count(),
            'approved_reviews' => $allReviews->where('status', 'approved')->count(),
            'pending_reviews' => $allReviews->where('status', 'pending')->count(),
            'rejected_reviews' => $allReviews->where('status', 'rejected')->count(),
            'average_rating' => $allReviews->where('status', 'approved')->avg('rating') ?? 0,
            'rating_distribution' => $this->getRatingDistribution($courseId),
        ];

        return view('admin.ratingdetails', [
            'course' => $course,
            'reviews' => $reviews,
            'statistics' => $stats,
            'currentFilter' => $request->get('status', 'approved')
        ]);
    }

    /**
     * Get rating distribution for a course
     */
    private function getRatingDistribution($courseId)
    {
        $distribution = [];
        
        for ($i = 5; $i >= 1; $i--) {
            $count = CourseReview::where('course_id', $courseId)
                                ->where('rating', $i)
                                ->where('status', 'approved')
                                ->count();
            $distribution[$i] = $count;
        }
        
        return $distribution;
    }
}

