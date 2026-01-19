<?php

namespace App\Services;

use App\Models\User;
use App\Models\Badge;
use App\Models\LessonCompletion;
use App\Models\QuizAttempt;

class PointsAndBadgesService
{
    const POINTS_PER_TOPIC_COMPLETION = 5;
    const POINTS_PER_QUIZ_PASS = 10;
    const POINTS_PER_COURSE_COMPLETION = 50;

    /**
     * Award badge for user signup
     */
    public function awardSignupBadge(User $user)
    {
        $badge = Badge::where('criteria', 'signup:1')->first();
        if ($badge && !$user->badges()->where('badge_id', $badge->id)->exists()) {
            $user->badges()->attach($badge->id, ['earned_at' => now()]);
        }
        return $user;
    }

    /**
     * Award badge for profile completion
     */
    public function awardProfileCompletionBadge(User $user)
    {
        if ($this->isProfileComplete($user)) {
            $badge = Badge::where('criteria', 'profile_complete:1')->first();
            if ($badge && !$user->badges()->where('badge_id', $badge->id)->exists()) {
                $user->badges()->attach($badge->id, ['earned_at' => now()]);
            }
        }
        return $user;
    }

    /**
     * Award points for completing a topic/lesson
     */
    public function awardPointsForTopicCompletion(User $user, $topicId)
    {
        $user->addPoints(self::POINTS_PER_TOPIC_COMPLETION);

        // Check if user qualifies for any badges
        $this->checkAndAwardBadges($user);

        return $user;
    }

    /**
     * Award points for passing a quiz
     */
    public function awardPointsForQuizPass(User $user, QuizAttempt $attempt)
    {
        if ($attempt->passed) {
            $user->addPoints(self::POINTS_PER_QUIZ_PASS);
            
            // Check if user qualifies for any badges
            $this->checkAndAwardBadges($user);
        }
        
        return $user;
    }

    /**
     * Award points for completing a course
     */
    public function awardPointsForCourseCompletion(User $user, $courseId)
    {
        $user->addPoints(self::POINTS_PER_COURSE_COMPLETION);

        // Check if user qualifies for any badges
        $this->checkAndAwardBadges($user);

        return $user;
    }

    /**
     * Award badges for money transfer
     */
    public function awardMoneyTransferBadges(User $user)
    {
        // Check and award money transfer badges
        $this->checkAndAwardBadges($user);
        return $user;
    }

    /**
     * Award badges for chat activity
     */
    public function awardChatActivityBadges(User $user)
    {
        // Check and award chat-related badges
        $this->checkAndAwardBadges($user);
        return $user;
    }

    /**
     * Award badges for subscription
     */
    public function awardSubscriptionBadges(User $user)
    {
        // Check and award subscription-related badges
        $this->checkAndAwardBadges($user);
        return $user;
    }

    /**
     * Award badges for enrollment
     */
    public function awardEnrollmentBadges(User $user)
    {
        // Check and award enrollment-related badges
        $this->checkAndAwardBadges($user);
        return $user;
    }

    /**
     * Check and award badges based on user's progress
     */
    public function checkAndAwardBadges(User $user)
    {
        $badges = Badge::all();
        
        foreach ($badges as $badge) {
            // Skip if user already has this badge
            if ($user->badges()->where('badge_id', $badge->id)->exists()) {
                continue;
            }
            
            // Check if user qualifies for this badge
            if ($this->userQualifiesForBadge($user, $badge)) {
                $user->badges()->attach($badge->id, ['earned_at' => now()]);
            }
        }
        
        return $user;
    }

    /**
     * Check if user qualifies for a specific badge
     */
    private function userQualifiesForBadge(User $user, Badge $badge)
    {
        // Parse criteria from string format (e.g., "lesson_completion:10")
        $criteria = $badge->criteria;

        if (!$criteria) {
            return false;
        }

        // Parse criteria string
        list($criteriaType, $criteriaValue) = explode(':', $criteria . ':') + [null, null];

        // Check based on badge criteria type
        switch ($criteriaType) {
            // LESSON BADGES
            case 'lesson_completion':
                $completed = LessonCompletion::where('user_id', $user->id)->count();
                return $completed >= (int)$criteriaValue;

            // TOPIC BADGES
            case 'topic_completion':
                $completed = \App\Models\Topic::whereHas('lessons', function($q) use ($user) {
                    $q->whereHas('completions', function($q2) use ($user) {
                        $q2->where('user_id', $user->id);
                    });
                })->distinct()->count();
                return $completed >= (int)$criteriaValue;

            // COURSE BADGES
            case 'course_completion':
                $completed = $user->enrollments()
                    ->where('status', 'completed')
                    ->count();
                return $completed >= (int)$criteriaValue;

            case 'enrollment':
                $enrolled = $user->enrollments()->count();
                return $enrolled >= (int)$criteriaValue;

            // QUIZ BADGES
            case 'quiz_pass':
                $passed = QuizAttempt::where('user_id', $user->id)
                    ->where('passed', true)
                    ->count();
                return $passed >= (int)$criteriaValue;

            case 'quiz_perfect':
                $perfect = QuizAttempt::where('user_id', $user->id)
                    ->where('score', 100)
                    ->count();
                return $perfect >= (int)$criteriaValue;

            // POINTS BADGES
            case 'points':
                return $user->points >= (int)$criteriaValue;

            // SPEED BADGES
            case 'course_speed':
                $fast = $user->enrollments()
                    ->where('status', 'completed')
                    ->whereRaw('DATEDIFF(completed_at, enrolled_at) <= ?', [(int)$criteriaValue])
                    ->count();
                return $fast >= 1;

            // TIME BADGES
            case 'early_bird':
                $early = LessonCompletion::where('user_id', $user->id)
                    ->whereRaw('HOUR(completed_at) < 8')
                    ->count();
                return $early >= (int)$criteriaValue;

            case 'night_owl':
                $night = LessonCompletion::where('user_id', $user->id)
                    ->whereRaw('HOUR(completed_at) >= 22')
                    ->count();
                return $night >= (int)$criteriaValue;

            // STREAK BADGES
            case 'consecutive_days':
                return $this->checkConsecutiveDays($user, (int)$criteriaValue);

            // CHATROOM BADGES
            case 'chatroom_posts':
                $posts = \App\Models\ChatMessage::where('user_id', $user->id)->count();
                return $posts >= (int)$criteriaValue;

            case 'helpful_posts':
                $helpful = \App\Models\ChatMessage::where('user_id', $user->id)
                    ->where('is_helpful', true)
                    ->count();
                return $helpful >= (int)$criteriaValue;

            // ENROLLMENT BADGES
            case 'active_enrollments':
                $active = $user->enrollments()
                    ->where('status', 'active')
                    ->count();
                return $active >= (int)$criteriaValue;

            case 'total_enrollments':
                $total = $user->enrollments()->count();
                return $total >= (int)$criteriaValue;

            // INSTRUCTOR BADGES
            case 'instructor':
                $courses = \App\Models\Course::where('instructor_id', $user->id)->count();
                return $courses >= (int)$criteriaValue;

            // LEVEL BADGES
            case 'level':
                $level = $this->calculateUserLevel($user->points);
                return strtolower($level) === strtolower($criteriaValue);

            // SIGNUP & PROFILE BADGES
            case 'signup':
                return true; // User exists, so they've signed up

            case 'profile_complete':
                return $this->isProfileComplete($user);

            // MONEY TRANSFER BADGES
            case 'money_transfer':
                $transfers = \App\Models\Transaction::where('sender_id', $user->id)
                    ->where('type', 'transfer')
                    ->count();
                return $transfers >= (int)$criteriaValue;

            case 'transfer_champion':
                // Check if user has the most transfers
                $userTransfers = \App\Models\Transaction::where('sender_id', $user->id)
                    ->where('type', 'transfer')
                    ->count();
                $maxTransfers = \App\Models\Transaction::where('type', 'transfer')
                    ->selectRaw('sender_id, COUNT(*) as transfer_count')
                    ->groupBy('sender_id')
                    ->orderByDesc('transfer_count')
                    ->first();
                return $maxTransfers && $maxTransfers->sender_id === $user->id && $userTransfers > 0;

            // CHATROOM CHAMPION BADGE
            case 'chatroom_champion':
                // Check if user has the most chatroom posts
                $userPosts = \App\Models\ChatMessage::where('user_id', $user->id)->count();
                $maxPosts = \App\Models\ChatMessage::selectRaw('user_id, COUNT(*) as post_count')
                    ->groupBy('user_id')
                    ->orderByDesc('post_count')
                    ->first();
                return $maxPosts && $maxPosts->user_id === $user->id && $userPosts > 0;

            // SUBSCRIPTION BADGES
            case 'subscription':
                return $this->checkSubscriptionMonths($user, (int)$criteriaValue);

            case 'resubscription':
                return $this->checkResubscription($user);

            default:
                return false;
        }
    }

    /**
     * Check if user has studied for consecutive days
     */
    private function checkConsecutiveDays(User $user, int $requiredDays): bool
    {
        $completions = LessonCompletion::where('user_id', $user->id)
            ->orderBy('completed_at', 'desc')
            ->get()
            ->groupBy(function($item) {
                return $item->completed_at->format('Y-m-d');
            });

        if ($completions->count() < $requiredDays) {
            return false;
        }

        $dates = $completions->keys()->map(function($date) {
            return \Carbon\Carbon::parse($date);
        })->sort()->values();

        $consecutive = 1;
        for ($i = 1; $i < count($dates); $i++) {
            if ($dates[$i]->diffInDays($dates[$i - 1]) === 1) {
                $consecutive++;
                if ($consecutive >= $requiredDays) {
                    return true;
                }
            } else {
                $consecutive = 1;
            }
        }

        return false;
    }

    /**
     * Calculate user level based on points
     */
    private function calculateUserLevel($points)
    {
        if ($points >= 1000) return 'Expert';
        if ($points >= 500) return 'Advanced';
        if ($points >= 100) return 'Intermediate';
        return 'Amateur';
    }

    /**
     * Check if user profile is complete
     */
    private function isProfileComplete(User $user)
    {
        // Check if all required profile fields are filled
        return !empty($user->first_name) &&
               !empty($user->last_name) &&
               !empty($user->email) &&
               !empty($user->profile_photo);
    }

    /**
     * Check subscription months
     */
    private function checkSubscriptionMonths(User $user, $months)
    {
        // Check if user has an active subscription for at least the specified months
        $subscription = \App\Models\Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            return false;
        }

        $subscriptionMonths = $subscription->created_at->diffInMonths(now());
        return $subscriptionMonths >= $months;
    }

    /**
     * Check if user has resubscribed
     */
    private function checkResubscription(User $user)
    {
        // Check if user has more than one subscription record
        $subscriptionCount = \App\Models\Subscription::where('user_id', $user->id)->count();
        return $subscriptionCount > 1;
    }

    /**
     * Use points to enroll in a course
     */
    public function enrollWithPoints(User $user, $courseId, $coursePrice)
    {
        if (!$user->hasEnoughPoints($coursePrice)) {
            return [
                'success' => false,
                'message' => 'Insufficient points. Required: ' . $coursePrice . ', Available: ' . $user->points
            ];
        }

        // Deduct points
        $user->deductPoints($coursePrice);

        // Create enrollment
        $enrollment = $user->enrollments()->create([
            'course_id' => $courseId,
            'status' => 'active',
            'enrolled_at' => now(),
            'amount_paid' => 0 // Points-based enrollment
        ]);

        return [
            'success' => true,
            'message' => 'Successfully enrolled using points',
            'enrollment' => $enrollment,
            'remaining_points' => $user->points
        ];
    }
}

