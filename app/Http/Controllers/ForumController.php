<?php

namespace App\Http\Controllers;

use App\Models\ForumTopic;
use App\Models\ForumPost;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ForumController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get course forum topics
     */
    public function index($courseId, Request $request)
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
                    'message' => 'You must be enrolled in this course to access the forum'
                ], 403);
            }

            $query = ForumTopic::with(['user', 'lastPost.user'])
                              ->where('course_id', $courseId);

            // Filter by category
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
                });
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'updated_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            if ($sortBy === 'activity') {
                $query->orderBy('last_activity_at', $sortOrder);
            } elseif ($sortBy === 'replies') {
                $query->withCount('posts')->orderBy('posts_count', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }

            $topics = $query->paginate($request->get('per_page', 15));

            // Add additional data
            $topics->getCollection()->transform(function ($topic) use ($user) {
                $topicData = $topic->toArray();
                $topicData['posts_count'] = $topic->posts()->count();
                $topicData['is_subscribed'] = $topic->subscribers()->where('user_id', $user->id)->exists();
                $topicData['unread_posts'] = $this->getUnreadPostsCount($topic, $user);
                return $topicData;
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'course' => $course,
                    'topics' => $topics,
                    'categories' => $this->getForumCategories(),
                    'stats' => $this->getForumStats($courseId)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch forum topics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new forum topic
     */
    public function store(Request $request, $courseId)
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
                    'message' => 'You must be enrolled in this course to create topics'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category' => 'required|in:general,questions,announcements,assignments,technical',
                'is_pinned' => 'boolean',
                'is_locked' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Only instructors and admins can pin or lock topics
            $topicData = $request->only(['title', 'content', 'category']);
            $topicData['course_id'] = $courseId;
            $topicData['user_id'] = $user->id;
            $topicData['status'] = 'active';
            $topicData['last_activity_at'] = now();

            if (($isInstructor || $isAdmin) && $request->has('is_pinned')) {
                $topicData['is_pinned'] = $request->boolean('is_pinned');
            }

            if (($isInstructor || $isAdmin) && $request->has('is_locked')) {
                $topicData['is_locked'] = $request->boolean('is_locked');
            }

            $topic = ForumTopic::create($topicData);

            // Auto-subscribe the creator
            $topic->subscribers()->attach($user->id);

            return response()->json([
                'success' => true,
                'message' => 'Forum topic created successfully',
                'data' => $topic->load(['user', 'course'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create forum topic: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get topic details with posts
     */
    public function show($id, Request $request)
    {
        try {
            $topic = ForumTopic::with(['user', 'course'])->findOrFail($id);
            $user = Auth::user();

            // Check if user has access to this course
            $isEnrolled = $topic->course->enrollments()->where('user_id', $user->id)->exists();
            $isInstructor = $topic->course->instructor_id === $user->id;
            $isAdmin = $user->hasRole('admin');

            if (!$isEnrolled && !$isInstructor && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled to view this topic'
                ], 403);
            }

            // Get posts with pagination
            $posts = ForumPost::with(['user', 'parent'])
                            ->where('topic_id', $id)
                            ->orderBy('created_at', 'asc')
                            ->paginate($request->get('per_page', 20));

            // Mark topic as read for this user
            $this->markTopicAsRead($topic, $user);

            $topicData = $topic->toArray();
            $topicData['posts_count'] = $topic->posts()->count();
            $topicData['is_subscribed'] = $topic->subscribers()->where('user_id', $user->id)->exists();
            $topicData['can_moderate'] = $isInstructor || $isAdmin;

            return response()->json([
                'success' => true,
                'data' => [
                    'topic' => $topicData,
                    'posts' => $posts
                ]
            ]);
        } catch (\Exception) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found'
            ], 404);
        }
    }

    /**
     * Update a forum topic
     */
    public function update(Request $request, $id)
    {
        try {
            $topic = ForumTopic::findOrFail($id);
            $user = Auth::user();

            // Check permissions
            $canEdit = $topic->user_id === $user->id || 
                      $topic->course->instructor_id === $user->id || 
                      $user->hasRole('admin');

            if (!$canEdit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this topic'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string|max:255',
                'content' => 'sometimes|string',
                'category' => 'sometimes|in:general,questions,announcements,assignments,technical',
                'is_pinned' => 'boolean',
                'is_locked' => 'boolean',
                'status' => 'sometimes|in:active,closed,archived'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = $request->only(['title', 'content', 'category']);

            // Only instructors and admins can modify these fields
            $isInstructor = $topic->course->instructor_id === $user->id;
            $isAdmin = $user->hasRole('admin');

            if (($isInstructor || $isAdmin) && $request->has(['is_pinned', 'is_locked', 'status'])) {
                $updateData = array_merge($updateData, $request->only(['is_pinned', 'is_locked', 'status']));
            }

            $topic->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Topic updated successfully',
                'data' => $topic
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update topic: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a forum topic
     */
    public function destroy($id)
    {
        try {
            $topic = ForumTopic::findOrFail($id);
            $user = Auth::user();

            // Check permissions
            $canDelete = $topic->user_id === $user->id || 
                        $topic->course->instructor_id === $user->id || 
                        $user->hasRole('admin');

            if (!$canDelete) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this topic'
                ], 403);
            }

            // Check if topic has posts
            if ($topic->posts()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete topic with existing posts. Archive it instead.'
                ], 400);
            }

            $topic->delete();

            return response()->json([
                'success' => true,
                'message' => 'Topic deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete topic: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new forum post (reply)
     */
    public function storePost(Request $request, $topicId)
    {
        try {
            $topic = ForumTopic::findOrFail($topicId);
            $user = Auth::user();

            // Check if user has access to this course
            $isEnrolled = $topic->course->enrollments()->where('user_id', $user->id)->exists();
            $isInstructor = $topic->course->instructor_id === $user->id;
            $isAdmin = $user->hasRole('admin');

            if (!$isEnrolled && !$isInstructor && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled to post in this topic'
                ], 403);
            }

            // Check if topic is locked
            if ($topic->is_locked && !$isInstructor && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'This topic is locked'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'content' => 'required|string',
                'parent_id' => 'nullable|exists:forum_posts,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validate parent post belongs to same topic
            if ($request->parent_id) {
                $parentPost = ForumPost::findOrFail($request->parent_id);
                if ($parentPost->topic_id !== $topic->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid parent post'
                    ], 400);
                }
            }

            $post = ForumPost::create([
                'topic_id' => $topicId,
                'user_id' => $user->id,
                'content' => $request->content,
                'parent_id' => $request->parent_id,
                'status' => 'active'
            ]);

            // Update topic last activity
            $topic->update([
                'last_activity_at' => now(),
                'last_post_id' => $post->id
            ]);

            // Notify topic subscribers (would implement notification system)
            $this->notifyTopicSubscribers($topic, $post);

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully',
                'data' => $post->load(['user', 'parent'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create post: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a forum post
     */
    public function updatePost(Request $request, $postId)
    {
        try {
            $post = ForumPost::findOrFail($postId);
            $user = Auth::user();

            // Check permissions
            $canEdit = $post->user_id === $user->id ||
                      $post->topic->course->instructor_id === $user->id ||
                      $user->hasRole('admin');

            if (!$canEdit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this post'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'content' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $post->update([
                'content' => $request->content,
                'edited_at' => now(),
                'edited_by' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully',
                'data' => $post
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update post: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a forum post
     */
    public function destroyPost($postId)
    {
        try {
            $post = ForumPost::findOrFail($postId);
            $user = Auth::user();

            // Check permissions
            $canDelete = $post->user_id === $user->id ||
                        $post->topic->course->instructor_id === $user->id ||
                        $user->hasRole('admin');

            if (!$canDelete) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this post'
                ], 403);
            }

            // Check if post has replies
            if ($post->replies()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete post with replies. Hide it instead.'
                ], 400);
            }

            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete post: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Subscribe to topic notifications
     */
    public function subscribe($topicId)
    {
        try {
            $topic = ForumTopic::findOrFail($topicId);
            $user = Auth::user();

            // Check if user has access to this course
            $isEnrolled = $topic->course->enrollments()->where('user_id', $user->id)->exists();
            $isInstructor = $topic->course->instructor_id === $user->id;
            $isAdmin = $user->hasRole('admin');

            if (!$isEnrolled && !$isInstructor && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled to subscribe to this topic'
                ], 403);
            }

            // Check if already subscribed
            if ($topic->subscribers()->where('user_id', $user->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Already subscribed to this topic'
                ], 400);
            }

            $topic->subscribers()->attach($user->id);

            return response()->json([
                'success' => true,
                'message' => 'Successfully subscribed to topic'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to subscribe: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unsubscribe from topic notifications
     */
    public function unsubscribe($topicId)
    {
        try {
            $topic = ForumTopic::findOrFail($topicId);
            $user = Auth::user();

            $topic->subscribers()->detach($user->id);

            return response()->json([
                'success' => true,
                'message' => 'Successfully unsubscribed from topic'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unsubscribe: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Like/unlike a post
     */
    public function likePost($postId)
    {
        try {
            $post = ForumPost::findOrFail($postId);
            $user = Auth::user();

            // Check if user has access to this course
            $isEnrolled = $post->topic->course->enrollments()->where('user_id', $user->id)->exists();
            $isInstructor = $post->topic->course->instructor_id === $user->id;
            $isAdmin = $user->hasRole('admin');

            if (!$isEnrolled && !$isInstructor && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled to like posts'
                ], 403);
            }

            // Check if already liked
            $existingLike = \App\Models\ForumPostLike::where('post_id', $postId)
                                                   ->where('user_id', $user->id)
                                                   ->first();

            if ($existingLike) {
                // Remove like
                $existingLike->delete();
                $post->decrement('likes_count');
                $message = 'Post unliked';
                $isLiked = false;
            } else {
                // Add like
                \App\Models\ForumPostLike::create([
                    'post_id' => $postId,
                    'user_id' => $user->id
                ]);
                $post->increment('likes_count');
                $message = 'Post liked';
                $isLiked = true;
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'likes_count' => $post->fresh()->likes_count,
                    'is_liked' => $isLiked
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to like post: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark post as solution (for question topics)
     */
    public function markSolution($postId)
    {
        try {
            $post = ForumPost::findOrFail($postId);
            $user = Auth::user();

            // Check permissions (topic creator, instructor, or admin)
            $canMarkSolution = $post->topic->user_id === $user->id ||
                              $post->topic->course->instructor_id === $user->id ||
                              $user->hasRole('admin');

            if (!$canMarkSolution) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to mark solution'
                ], 403);
            }

            // Remove existing solution if any
            ForumPost::where('topic_id', $post->topic_id)->update(['is_solution' => false]);

            // Mark this post as solution
            $post->update(['is_solution' => true]);

            // Update topic status
            $post->topic->update(['status' => 'solved']);

            return response()->json([
                'success' => true,
                'message' => 'Post marked as solution',
                'data' => $post
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark solution: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get forum analytics (for instructors/admins)
     */
    public function analytics($courseId)
    {
        try {
            $course = Course::findOrFail($courseId);
            $user = Auth::user();

            // Check permissions
            if ($course->instructor_id !== $user->id && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view forum analytics'
                ], 403);
            }

            $analytics = [
                'overview' => [
                    'total_topics' => ForumTopic::where('course_id', $courseId)->count(),
                    'total_posts' => ForumPost::whereHas('topic', function($query) use ($courseId) {
                        $query->where('course_id', $courseId);
                    })->count(),
                    'active_participants' => $this->getActiveParticipants($courseId),
                    'solved_questions' => ForumTopic::where('course_id', $courseId)->where('status', 'solved')->count()
                ],
                'engagement' => [
                    'posts_per_topic' => $this->getAveragePostsPerTopic($courseId),
                    'response_time' => $this->getAverageResponseTime($courseId),
                    'participation_rate' => $this->getParticipationRate($courseId)
                ],
                'trends' => [
                    'monthly_activity' => $this->getMonthlyForumActivity($courseId),
                    'category_distribution' => $this->getCategoryDistribution($courseId)
                ],
                'top_contributors' => $this->getTopContributors($courseId)
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
     * Helper method to get unread posts count
     */
    private function getUnreadPostsCount($topic, $user)
    {
        $lastRead = \App\Models\ForumTopicRead::where('topic_id', $topic->id)
                                            ->where('user_id', $user->id)
                                            ->first();

        if (!$lastRead) {
            return $topic->posts()->count();
        }

        return $topic->posts()->where('created_at', '>', $lastRead->last_read_at)->count();
    }

    /**
     * Helper method to mark topic as read
     */
    private function markTopicAsRead($topic, $user)
    {
        \App\Models\ForumTopicRead::updateOrCreate(
            ['topic_id' => $topic->id, 'user_id' => $user->id],
            ['last_read_at' => now()]
        );
    }

    /**
     * Helper method to notify topic subscribers
     */
    private function notifyTopicSubscribers($topic, $post)
    {
        // This would implement the notification system
        // For now, just a placeholder
    }

    /**
     * Helper method to get forum categories
     */
    private function getForumCategories()
    {
        return [
            'general' => 'General Discussion',
            'questions' => 'Questions & Help',
            'announcements' => 'Announcements',
            'assignments' => 'Assignments',
            'technical' => 'Technical Issues'
        ];
    }

    /**
     * Helper method to get forum statistics
     */
    private function getForumStats($courseId)
    {
        return [
            'total_topics' => ForumTopic::where('course_id', $courseId)->count(),
            'total_posts' => ForumPost::whereHas('topic', function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })->count(),
            'active_topics_today' => ForumTopic::where('course_id', $courseId)
                                             ->where('last_activity_at', '>=', now()->startOfDay())
                                             ->count(),
            'unanswered_questions' => ForumTopic::where('course_id', $courseId)
                                               ->where('category', 'questions')
                                               ->where('status', 'active')
                                               ->whereDoesntHave('posts')
                                               ->count()
        ];
    }

    /**
     * Helper methods for analytics
     */
    private function getActiveParticipants($courseId)
    {
        return ForumPost::whereHas('topic', function($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->distinct('user_id')->count('user_id');
    }

    private function getAveragePostsPerTopic($courseId)
    {
        $topics = ForumTopic::where('course_id', $courseId)->withCount('posts')->get();
        return $topics->count() > 0 ? round($topics->avg('posts_count'), 2) : 0;
    }

    private function getAverageResponseTime($courseId)
    {
        // Mock implementation - would calculate actual response times
        return '2.5 hours';
    }

    private function getParticipationRate($courseId)
    {
        $course = Course::findOrFail($courseId);
        $totalStudents = $course->enrollments()->count();
        $activeParticipants = $this->getActiveParticipants($courseId);

        return $totalStudents > 0 ? round(($activeParticipants / $totalStudents) * 100, 2) : 0;
    }

    private function getMonthlyForumActivity($courseId)
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'topics' => ForumTopic::where('course_id', $courseId)
                                    ->whereYear('created_at', $date->year)
                                    ->whereMonth('created_at', $date->month)
                                    ->count(),
                'posts' => ForumPost::whereHas('topic', function($query) use ($courseId) {
                                $query->where('course_id', $courseId);
                            })
                            ->whereYear('created_at', $date->year)
                            ->whereMonth('created_at', $date->month)
                            ->count()
            ];
        }
        return $months;
    }

    private function getCategoryDistribution($courseId)
    {
        return ForumTopic::where('course_id', $courseId)
                        ->selectRaw('category, COUNT(*) as count')
                        ->groupBy('category')
                        ->pluck('count', 'category')
                        ->toArray();
    }

    private function getTopContributors($courseId)
    {
        return ForumPost::whereHas('topic', function($query) use ($courseId) {
                    $query->where('course_id', $courseId);
                })
                ->with('user')
                ->selectRaw('user_id, COUNT(*) as posts_count')
                ->groupBy('user_id')
                ->orderBy('posts_count', 'desc')
                ->limit(5)
                ->get()
                ->map(function($post) {
                    return [
                        'user' => $post->user,
                        'posts_count' => $post->posts_count
                    ];
                });
    }
}
