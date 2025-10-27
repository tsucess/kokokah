<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reward_type',
        'amount',
        'date',
        'streak_count',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'streak_count' => 'integer',
        'metadata' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('reward_type', $type);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year);
    }

    // Static methods for reward management
    public static function giveLoginReward(User $user)
    {
        // Check if user already got login reward today
        $todayReward = static::where('user_id', $user->id)
                           ->where('reward_type', 'daily_login')
                           ->whereDate('date', today())
                           ->first();

        if ($todayReward) {
            return null; // Already rewarded today
        }

        // Calculate streak
        $streak = static::calculateLoginStreak($user);
        
        // Base reward amount
        $baseAmount = 10; // 10 units for daily login
        
        // Bonus for streak
        $streakBonus = min($streak * 2, 50); // Max 50 bonus
        $totalAmount = $baseAmount + $streakBonus;

        // Create reward record
        $reward = static::create([
            'user_id' => $user->id,
            'reward_type' => 'daily_login',
            'amount' => $totalAmount,
            'date' => today(),
            'streak_count' => $streak,
            'metadata' => [
                'base_amount' => $baseAmount,
                'streak_bonus' => $streakBonus,
                'streak_days' => $streak
            ]
        ]);

        // Add to wallet
        $wallet = $user->getOrCreateWallet();
        $transaction = $wallet->addReward(
            $totalAmount,
            'daily_login',
            "Daily login reward (Day {$streak})",
            ['reward_id' => $reward->id]
        );

        return $reward;
    }

    public static function giveStudyReward(User $user, $studyMinutes)
    {
        // Check if user already got study reward today
        $todayReward = static::where('user_id', $user->id)
                           ->where('reward_type', 'study_time')
                           ->whereDate('date', today())
                           ->first();

        if ($todayReward) {
            return null; // Already rewarded today
        }

        // Minimum 30 minutes to get reward
        if ($studyMinutes < 30) {
            return null;
        }

        // Calculate reward based on study time
        $baseAmount = 5; // 5 units base
        $timeBonus = min(floor($studyMinutes / 30) * 5, 25); // 5 units per 30 min, max 25
        $totalAmount = $baseAmount + $timeBonus;

        // Create reward record
        $reward = static::create([
            'user_id' => $user->id,
            'reward_type' => 'study_time',
            'amount' => $totalAmount,
            'date' => today(),
            'metadata' => [
                'study_minutes' => $studyMinutes,
                'base_amount' => $baseAmount,
                'time_bonus' => $timeBonus
            ]
        ]);

        // Add to wallet
        $wallet = $user->getOrCreateWallet();
        $transaction = $wallet->addReward(
            $totalAmount,
            'study_time',
            "Study time reward ({$studyMinutes} minutes)",
            ['reward_id' => $reward->id]
        );

        return $reward;
    }

    public static function giveCourseCompletionReward(User $user, Course $course)
    {
        // Check if user already got completion reward for this course
        $existingReward = static::where('user_id', $user->id)
                              ->where('reward_type', 'course_completion')
                              ->where('metadata->course_id', $course->id)
                              ->first();

        if ($existingReward) {
            return null; // Already rewarded for this course
        }

        // Calculate reward based on course price/difficulty
        $baseAmount = 50; // 50 units base
        $difficultyBonus = match($course->difficulty) {
            'beginner' => 0,
            'intermediate' => 25,
            'advanced' => 50,
            default => 0
        };
        $totalAmount = $baseAmount + $difficultyBonus;

        // Create reward record
        $reward = static::create([
            'user_id' => $user->id,
            'reward_type' => 'course_completion',
            'amount' => $totalAmount,
            'date' => today(),
            'metadata' => [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'difficulty' => $course->difficulty,
                'base_amount' => $baseAmount,
                'difficulty_bonus' => $difficultyBonus
            ]
        ]);

        // Add to wallet
        $wallet = $user->getOrCreateWallet();
        $transaction = $wallet->addReward(
            $totalAmount,
            'course_completion',
            "Course completion: {$course->title}",
            ['reward_id' => $reward->id]
        );

        return $reward;
    }

    private static function calculateLoginStreak(User $user)
    {
        $streak = 1; // Today counts as 1
        $currentDate = today()->subDay();

        while (true) {
            $hasLogin = static::where('user_id', $user->id)
                           ->where('reward_type', 'daily_login')
                           ->whereDate('date', $currentDate)
                           ->exists();

            if (!$hasLogin) {
                break;
            }

            $streak++;
            $currentDate = $currentDate->subDay();

            // Limit streak calculation to prevent infinite loops
            if ($streak > 365) {
                break;
            }
        }

        return $streak;
    }

    // Helper methods
    public function getFormattedAmount()
    {
        return number_format($this->amount, 2);
    }

    public function getRewardDescription()
    {
        return match($this->reward_type) {
            'daily_login' => "Daily Login Reward (Day {$this->streak_count})",
            'study_time' => "Study Time Reward ({$this->metadata['study_minutes']} minutes)",
            'course_completion' => "Course Completion: {$this->metadata['course_title']}",
            'quiz_perfect' => 'Perfect Quiz Score Bonus',
            'streak_bonus' => 'Study Streak Bonus',
            'referral' => 'Referral Bonus',
            default => 'Reward'
        };
    }
}
