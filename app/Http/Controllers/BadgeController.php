<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BadgeController extends Controller
{
    // Note: Middleware is applied at route level in Laravel 12
    // See routes/api.php for middleware configuration

    /**
     * Get all available badges
     */
    public function index(Request $request)
    {
        $query = Badge::query();

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $badges = $query->paginate($request->get('per_page', 20));

        // Add user progress for each badge
        $user = Auth::user();
        $badges->getCollection()->transform(function ($badge) use ($user) {
            $badgeData = $badge->toArray();
            $userBadge = $user->badges()->where('badge_id', $badge->id)->first();

            $badgeData['earned'] = $userBadge ? true : false;
            $badgeData['earned_at'] = $userBadge ? $userBadge->pivot->earned_at : null;
            $badgeData['progress'] = $this->calculateBadgeProgress($badge, $user);

            return $badgeData;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'badges' => $badges,
                'categories' => $this->getBadgeCategories(),
                'types' => $this->getBadgeTypes()
            ]
        ]);
    }

    /**
     * Get a specific badge
     */
    public function show($id)
    {
        try {
            $badge = Badge::findOrFail($id);
            $user = Auth::user();

            $badgeData = $badge->toArray();
            $userBadge = $user->badges()->where('badge_id', $badge->id)->first();

            $badgeData['earned'] = $userBadge ? true : false;
            $badgeData['earned_at'] = $userBadge ? $userBadge->pivot->earned_at : null;
            $badgeData['progress'] = $this->calculateBadgeProgress($badge, $user);
            $badgeData['criteria_details'] = $this->getBadgeCriteriaDetails($badge, $user);
            $badgeData['recent_earners'] = $this->getRecentBadgeEarners($badge);

            return response()->json([
                'success' => true,
                'data' => $badgeData
            ]);
        } catch (\Exception) {
            return response()->json([
                'success' => false,
                'message' => 'Badge not found'
            ], 404);
        }
    }

    /**
     * Create a new badge (admin only)
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:badges',
            'description' => 'required|string',
            'category' => 'required|in:learning,achievement,social,special',
            'type' => 'required|in:course_completion,quiz_mastery,streak,participation,milestone',
            'criteria' => 'required|array',
            'points' => 'required|integer|min:1',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $badgeData = $request->only(['name', 'description', 'category', 'type', 'criteria', 'points']);
            $badgeData['is_active'] = $request->boolean('is_active', true);
            $badgeData['created_by'] = $user->id;

            // Handle icon upload
            if ($request->hasFile('icon')) {
                $iconPath = $request->file('icon')->store('badges/icons', 'public');
                $badgeData['icon_path'] = $iconPath;
            }

            $badge = Badge::create($badgeData);

            return response()->json([
                'success' => true,
                'message' => 'Badge created successfully',
                'data' => $badge
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create badge: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a badge (admin only)
     */
    public function update(Request $request, $id)
    {
        try {
            $badge = Badge::findOrFail($id);
            $user = Auth::user();

            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255|unique:badges,name,' . $id,
                'description' => 'sometimes|string',
                'category' => 'sometimes|in:learning,achievement,social,special',
                'type' => 'sometimes|in:course_completion,quiz_mastery,streak,participation,milestone',
                'criteria' => 'sometimes|array',
                'points' => 'sometimes|integer|min:1',
                'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = $request->only(['name', 'description', 'category', 'type', 'criteria', 'points', 'is_active']);

            // Handle icon upload
            if ($request->hasFile('icon')) {
                // Delete old icon
                if ($badge->icon_path && Storage::disk('public')->exists($badge->icon_path)) {
                    Storage::disk('public')->delete($badge->icon_path);
                }

                $iconPath = $request->file('icon')->store('badges/icons', 'public');
                $updateData['icon_path'] = $iconPath;
            }

            $badge->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Badge updated successfully',
                'data' => $badge
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update badge: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a badge (admin only)
     */
    public function destroy($id)
    {
        try {
            $badge = Badge::findOrFail($id);
            $user = Auth::user();

            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            // Check if badge has been earned by users
            if ($badge->users()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete badge that has been earned by users'
                ], 400);
            }

            // Delete icon file
            if ($badge->icon_path && Storage::disk('public')->exists($badge->icon_path)) {
                Storage::disk('public')->delete($badge->icon_path);
            }

            $badge->delete();

            return response()->json([
                'success' => true,
                'message' => 'Badge deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete badge: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's earned badges
     */
    public function userBadges(Request $request, $userId = null)
    {
        $targetUserId = $userId ?? Auth::id();
        $user = Auth::user();

        // Check if user can view these badges
        if ($targetUserId !== $user->id && $user->role !== 'admin') {
            // Check if target user allows public badge viewing
            $targetUser = User::findOrFail($targetUserId);
            if (!$targetUser->show_badges_publicly) {
                return response()->json([
                    'success' => false,
                    'message' => 'User badges are private'
                ], 403);
            }
        }

        $targetUser = User::findOrFail($targetUserId);
        $query = $targetUser->badges();

        // Filter by name (since category doesn't exist)
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'earned_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'earned_at') {
            $query->orderBy('user_badges.earned_at', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $userBadges = $query->paginate($request->get('per_page', 20));

        // Calculate badge statistics
        $totalBadges = $targetUser->badges()->count();

        $stats = [
            'total_badges' => $totalBadges,
            'badge_names' => $targetUser->badges()
                                     ->selectRaw('name, COUNT(*) as count')
                                     ->groupBy('name')
                                     ->pluck('count', 'name'),
            'recent_badges' => $targetUser->badges()
                                        ->orderBy('user_badges.earned_at', 'desc')
                                        ->limit(5)
                                        ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'badges' => $userBadges,
                'stats' => $stats
            ]
        ]);
    }

    /**
     * Award a badge to a user (admin/instructor only)
     */
    public function awardBadge(Request $request)
    {
        try {
            $user = Auth::user();

            if ($user->role !== 'admin' && $user->role !== 'instructor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'badge_id' => 'required|exists:badges,id',
                'user_id' => 'required|exists:users,id',
                'reason' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $badge = Badge::findOrFail($request->badge_id);
            $targetUser = User::findOrFail($request->user_id);

            // Check if user already has this badge
            if ($targetUser->badges()->where('badge_id', $badge->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User already has this badge'
                ], 400);
            }

            // Award the badge using the pivot table
            $targetUser->badges()->attach($badge->id, [
                'earned_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Get the badge with pivot data for response
            $userBadge = $targetUser->badges()->where('badge_id', $badge->id)->first();

            // Update user's total badge points
            $this->updateUserBadgePoints($targetUser);

            return response()->json([
                'success' => true,
                'message' => 'Badge awarded successfully',
                'data' => [
                    'badge' => $badge,
                    'user' => $targetUser,
                    'earned_at' => $userBadge->pivot->earned_at
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to award badge: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Revoke a badge from a user (admin only)
     */
    public function revokeBadge(Request $request, $userId, $badgeId)
    {
        try {
            $user = Auth::user();

            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $targetUser = User::findOrFail($userId);
            $badge = Badge::findOrFail($badgeId);

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

            // Check if user has this badge
            if (!$targetUser->badges()->where('badge_id', $badgeId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User does not have this badge'
                ], 404);
            }

            // Remove the badge from the user
            $targetUser->badges()->detach($badgeId);

            return response()->json([
                'success' => true,
                'message' => 'Badge revoked successfully',
                'data' => [
                    'user_id' => $userId,
                    'badge_id' => $badgeId,
                    'revoked_at' => now(),
                    'reason' => $request->reason
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to revoke badge: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check and award automatic badges for a user
     */
    public function checkAutomaticBadges($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $awardedBadges = [];

            // Get all active badges that can be automatically awarded
            $badges = Badge::where('is_active', true)->get();

            foreach ($badges as $badge) {
                // Skip if user already has this badge
                if ($user->badges()->where('badge_id', $badge->id)->exists()) {
                    continue;
                }

                // Check if user meets the criteria
                if ($this->checkBadgeCriteria($badge, $user)) {
                    // Award the badge using the pivot table
                    $user->badges()->attach($badge->id, [
                        'earned_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $awardedBadges[] = $badge;
                }
            }

            return response()->json([
                'success' => true,
                'message' => count($awardedBadges) . ' badges awarded',
                'data' => $awardedBadges
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check badges: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get badge leaderboard
     */
    public function leaderboard(Request $request)
    {
        $period = $request->get('period', 'all_time'); // all_time, this_month, this_year
        $category = $request->get('category'); // optional filter by badge category

        $query = User::select('users.id', 'users.first_name', 'users.last_name', 'users.email')
                    ->selectRaw('COUNT(user_badges.id) as badges_count')
                    ->selectRaw('COALESCE(SUM(badges.points), 0) as total_points')
                    ->leftJoin('user_badges', function($join) use ($period) {
                        $join->on('users.id', '=', 'user_badges.user_id')
                             ->whereNull('user_badges.revoked_at');

                        if ($period === 'this_month') {
                            $join->where('user_badges.earned_at', '>=', now()->startOfMonth());
                        } elseif ($period === 'this_year') {
                            $join->where('user_badges.earned_at', '>=', now()->startOfYear());
                        }
                    })
                    ->leftJoin('badges', 'user_badges.badge_id', '=', 'badges.id');

        if ($category) {
            $query->where('badges.type', $category); // Use 'type' instead of 'category'
        }

        $leaderboard = $query->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.email')
                           ->orderBy('total_points', 'desc')
                           ->orderBy('badges_count', 'desc')
                           ->limit(50)
                           ->get();

        // Add rank to each user
        $leaderboard = $leaderboard->map(function($user, $index) {
            $userData = $user->toArray();
            $userData['rank'] = $index + 1;
            return $userData;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'leaderboard' => $leaderboard,
                'period' => $period,
                'category' => $category
            ]
        ]);
    }

    /**
     * Get badge analytics (admin only)
     */
    public function analytics()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $analytics = [
            'overview' => [
                'total_badges' => Badge::count(),
                'active_badges' => Badge::where('is_active', true)->count(),
                'total_awards' => DB::table('user_badges')->count(),
                'unique_badge_holders' => DB::table('user_badges')->distinct('user_id')->count()
            ],
            'popular_badges' => $this->getPopularBadges(),
            'category_distribution' => $this->getBadgeCategoryDistribution(),
            'award_trends' => $this->getBadgeAwardTrends(),
            'top_badge_earners' => $this->getTopBadgeEarners()
        ];

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Helper method to calculate badge progress
     */
    private function calculateBadgeProgress($badge, $user)
    {
        $criteria = $badge->criteria;
        $progress = [];

        switch ($badge->type) {
            case 'course_completion':
                $required = $criteria['courses_required'] ?? 1;
                $completed = $user->enrollments()->where('status', 'completed')->count();
                $progress = [
                    'current' => min($completed, $required),
                    'required' => $required,
                    'percentage' => min(100, ($completed / $required) * 100)
                ];
                break;

            case 'quiz_mastery':
                $required = $criteria['quizzes_required'] ?? 1;
                $minScore = $criteria['min_score'] ?? 80;
                $completed = \App\Models\QuizAttempt::where('user_id', $user->id)
                                                  ->where('score', '>=', $minScore)
                                                  ->distinct('quiz_id')
                                                  ->count();
                $progress = [
                    'current' => min($completed, $required),
                    'required' => $required,
                    'percentage' => min(100, ($completed / $required) * 100)
                ];
                break;

            case 'streak':
                $required = $criteria['days_required'] ?? 7;
                $currentStreak = $this->getUserCurrentStreak($user);
                $progress = [
                    'current' => min($currentStreak, $required),
                    'required' => $required,
                    'percentage' => min(100, ($currentStreak / $required) * 100)
                ];
                break;

            default:
                $progress = ['current' => 0, 'required' => 1, 'percentage' => 0];
        }

        return $progress;
    }

    /**
     * Helper method to get badge criteria details
     */
    private function getBadgeCriteriaDetails($badge, $user)
    {
        $criteria = $badge->criteria;
        $details = [];

        switch ($badge->type) {
            case 'course_completion':
                $details[] = [
                    'description' => 'Complete ' . ($criteria['courses_required'] ?? 1) . ' courses',
                    'completed' => $user->enrollments()->where('status', 'completed')->count() >= ($criteria['courses_required'] ?? 1)
                ];
                break;

            case 'quiz_mastery':
                $details[] = [
                    'description' => 'Score ' . ($criteria['min_score'] ?? 80) . '% or higher on ' . ($criteria['quizzes_required'] ?? 1) . ' quizzes',
                    'completed' => \App\Models\QuizAttempt::where('user_id', $user->id)
                                                         ->where('score', '>=', $criteria['min_score'] ?? 80)
                                                         ->distinct('quiz_id')
                                                         ->count() >= ($criteria['quizzes_required'] ?? 1)
                ];
                break;

            case 'streak':
                $details[] = [
                    'description' => 'Maintain a ' . ($criteria['days_required'] ?? 7) . '-day learning streak',
                    'completed' => $this->getUserCurrentStreak($user) >= ($criteria['days_required'] ?? 7)
                ];
                break;
        }

        return $details;
    }

    /**
     * Helper method to get recent badge earners
     */
    private function getRecentBadgeEarners($badge)
    {
        return $badge->users()
                    ->orderBy('user_badges.earned_at', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function($user) {
                        return [
                            'user' => $user,
                            'earned_at' => $user->pivot->earned_at
                        ];
                    });
    }

    /**
     * Helper method to check if user meets badge criteria
     */
    private function checkBadgeCriteria($badge, $user)
    {
        $criteria = $badge->criteria;

        switch ($badge->type) {
            case 'course_completion':
                $required = $criteria['courses_required'] ?? 1;
                $completed = $user->enrollments()->where('status', 'completed')->count();
                return $completed >= $required;

            case 'quiz_mastery':
                $required = $criteria['quizzes_required'] ?? 1;
                $minScore = $criteria['min_score'] ?? 80;
                $completed = \App\Models\QuizAttempt::where('user_id', $user->id)
                                                  ->where('score', '>=', $minScore)
                                                  ->distinct('quiz_id')
                                                  ->count();
                return $completed >= $required;

            case 'streak':
                $required = $criteria['days_required'] ?? 7;
                $currentStreak = $this->getUserCurrentStreak($user);
                return $currentStreak >= $required;

            case 'participation':
                $required = $criteria['forum_posts_required'] ?? 10;
                $posts = \App\Models\ForumPost::where('user_id', $user->id)->count();
                return $posts >= $required;

            case 'milestone':
                // Custom milestone logic based on criteria
                return $this->checkMilestoneCriteria($criteria, $user);

            default:
                return false;
        }
    }

    /**
     * Helper method to update user's total badge points
     */
    private function updateUserBadgePoints($user)
    {
        // Since badges table doesn't have points column, we'll count total badges instead
        $totalBadges = $user->badges()->count();
        // Update user's badge count (assuming there's a badge_count field, or skip this)
        // $user->update(['badge_count' => $totalBadges]);
    }

    /**
     * Helper method to get user's current learning streak
     */
    private function getUserCurrentStreak($user)
    {
        // This would calculate the user's current learning streak
        // For now, return a mock value
        return 5; // Mock streak of 5 days
    }

    /**
     * Helper method to check milestone criteria
     */
    private function checkMilestoneCriteria($criteria, $user)
    {
        // Custom milestone logic
        if (isset($criteria['total_study_hours'])) {
            $totalHours = \App\Models\LessonCompletion::where('user_id', $user->id)
                                                    ->sum('time_spent') / 3600;
            return $totalHours >= $criteria['total_study_hours'];
        }

        if (isset($criteria['certificates_earned'])) {
            $certificates = \App\Models\Certificate::where('user_id', $user->id)->count();
            return $certificates >= $criteria['certificates_earned'];
        }

        return false;
    }

    /**
     * Helper method to get badge categories
     */
    private function getBadgeCategories()
    {
        return [
            'learning' => 'Learning Achievements',
            'achievement' => 'Performance Achievements',
            'social' => 'Social Engagement',
            'special' => 'Special Recognition'
        ];
    }

    /**
     * Helper method to get badge types
     */
    private function getBadgeTypes()
    {
        return [
            'course_completion' => 'Course Completion',
            'quiz_mastery' => 'Quiz Mastery',
            'streak' => 'Learning Streak',
            'participation' => 'Community Participation',
            'milestone' => 'Achievement Milestone'
        ];
    }

    /**
     * Helper methods for analytics
     */
    private function getPopularBadges()
    {
        return Badge::withCount('users')
                    ->orderBy('users_count', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function($badge) {
                        return [
                            'id' => $badge->id,
                            'name' => $badge->name,
                            'icon' => $badge->icon,
                            'awards_count' => $badge->users_count
                        ];
                    });
    }

    private function getBadgeCategoryDistribution()
    {
        return Badge::selectRaw('category, COUNT(*) as count')
                   ->groupBy('category')
                   ->pluck('count', 'category')
                   ->toArray();
    }

    private function getBadgeAwardTrends()
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'awards' => DB::table('user_badges')
                                   ->whereYear('earned_at', $date->year)
                                   ->whereMonth('earned_at', $date->month)
                                   ->count()
            ];
        }
        return $months;
    }

    private function getTopBadgeEarners()
    {
        return User::select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.profile_photo')
                  ->selectRaw('COUNT(user_badges.id) as badges_count')
                  ->selectRaw('COALESCE(SUM(badges.points), 0) as total_points')
                  ->leftJoin('user_badges', function($join) {
                      $join->on('users.id', '=', 'user_badges.user_id')
                           ->whereNull('user_badges.revoked_at');
                  })
                  ->leftJoin('badges', 'user_badges.badge_id', '=', 'badges.id')
                  ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.profile_photo')
                  ->orderBy('total_points', 'desc')
                  ->limit(10)
                  ->get()
                  ->map(function($user, $index) {
                      return [
                          'rank' => $index + 1,
                          'user' => [
                              'id' => $user->id,
                              'first_name' => $user->first_name,
                              'last_name' => $user->last_name,
                              'email' => $user->email,
                              'profile_photo' => $user->profile_photo
                          ],
                          'badges_count' => $user->badges_count,
                          'total_points' => $user->total_points
                      ];
                  });
    }
}
