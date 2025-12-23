<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Badge;
use App\Models\UserPointsHistory;
use App\Models\BadgeCriteriaLog;
use App\Models\UserLevelHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\PointsAndBadgesService;

class PointsAndBadgesController extends Controller
{
    protected $pointsService;

    public function __construct(PointsAndBadgesService $pointsService)
    {
        $this->pointsService = $pointsService;
    }

    /**
     * Get user's current points and level
     */
    public function getUserPoints()
    {
        try {
            $user = Auth::user();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $user->id,
                    'points' => $user->getPoints(),
                    'level' => $this->calculateUserLevel($user->getPoints()),
                    'next_level_points' => $this->getNextLevelPoints($user->getPoints()),
                    'progress_to_next_level' => $this->getProgressToNextLevel($user->getPoints())
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch points: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's points history
     */
    public function getPointsHistory(Request $request)
    {
        try {
            $user = Auth::user();
            $limit = $request->get('limit', 50);
            
            $history = UserPointsHistory::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate($limit);
            
            return response()->json([
                'success' => true,
                'data' => $history
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch points history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's badges with progress
     */
    public function getUserBadges(Request $request)
    {
        try {
            $user = Auth::user();
            $category = $request->get('category');
            
            $query = $user->badges()
                ->with('pivot')
                ->orderBy('user_badges.earned_at', 'desc');
            
            if ($category) {
                $query->where('category', $category);
            }
            
            $badges = $query->paginate($request->get('per_page', 20));
            
            return response()->json([
                'success' => true,
                'data' => $badges
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch badges: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get badge details with user progress
     */
    public function getBadgeDetails($badgeId)
    {
        try {
            $user = Auth::user();
            $badge = Badge::findOrFail($badgeId);
            
            $userHasBadge = $user->badges()->where('badge_id', $badgeId)->exists();
            $earnedAt = null;
            
            if ($userHasBadge) {
                $earnedAt = $user->badges()
                    ->where('badge_id', $badgeId)
                    ->first()
                    ->pivot
                    ->earned_at;
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'badge' => $badge,
                    'earned' => $userHasBadge,
                    'earned_at' => $earnedAt,
                    'progress' => $this->getBadgeProgress($user, $badge)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch badge details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get leaderboard with points and badges
     */
    public function getLeaderboard(Request $request)
    {
        try {
            $period = $request->get('period', 'all_time');
            $limit = $request->get('limit', 50);
            
            $query = User::select('id', 'first_name', 'last_name', 'email', 'profile_photo', 'points')
                ->where('points', '>', 0)
                ->orderBy('points', 'desc');
            
            $leaderboard = $query->paginate($limit);
            
            // Add rank and level
            $leaderboard->getCollection()->transform(function($user, $key) {
                return [
                    'rank' => $key + 1,
                    'user_id' => $user->id,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'email' => $user->email,
                    'profile_photo' => $user->profile_photo ? '/storage/' . $user->profile_photo : null,
                    'points' => $user->points,
                    'level' => $this->calculateUserLevel($user->points),
                    'badges_count' => $user->badges()->count()
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $leaderboard
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch leaderboard: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get badge statistics
     */
    public function getBadgeStats()
    {
        try {
            $user = Auth::user();
            
            $stats = [
                'total_badges' => $user->badges()->count(),
                'badges_by_category' => $user->badges()
                    ->groupBy('category')
                    ->selectRaw('category, count(*) as count')
                    ->get(),
                'total_badge_points' => $user->badges()
                    ->sum('points'),
                'recent_badges' => $user->badges()
                    ->orderBy('user_badges.earned_at', 'desc')
                    ->limit(5)
                    ->get()
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch badge stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper: Calculate user level
     */
    private function calculateUserLevel($points)
    {
        if ($points >= 1000) return 'Expert';
        if ($points >= 500) return 'Advanced';
        if ($points >= 100) return 'Intermediate';
        return 'Amateur';
    }

    /**
     * Helper: Get next level points
     */
    private function getNextLevelPoints($points)
    {
        if ($points >= 1000) return null;
        if ($points >= 500) return 1000;
        if ($points >= 100) return 500;
        return 100;
    }

    /**
     * Helper: Get progress to next level
     */
    private function getProgressToNextLevel($points)
    {
        $nextLevel = $this->getNextLevelPoints($points);
        if (!$nextLevel) return 100;
        
        $currentThreshold = 0;
        if ($points >= 500) $currentThreshold = 500;
        elseif ($points >= 100) $currentThreshold = 100;
        
        $progress = (($points - $currentThreshold) / ($nextLevel - $currentThreshold)) * 100;
        return min(100, max(0, $progress));
    }

    /**
     * Helper: Get badge progress
     */
    private function getBadgeProgress($user, $badge)
    {
        // This would be implemented based on badge criteria
        return 0;
    }
}

