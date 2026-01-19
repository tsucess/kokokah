<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Course;
use App\Models\UserReward;
use App\Models\Coupon;
use App\Models\UserPointsHistory;
use App\Models\PointsConversion;
use Illuminate\Support\Facades\DB;

class WalletService
{
    const POINTS_TO_WALLET_RATIO = 10; // 10 points = 1 wallet unit

    /**
     * Convert user points to wallet balance
     * 10 points = 1 wallet unit (₦1.00)
     */
    public function convertPointsToWallet(User $user, int $points): array
    {
        // Validation
        $validation = $this->validatePointsConversion($user, $points);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => $validation['errors'][0],
                'errors' => $validation['errors']
            ];
        }

        try {
            return DB::transaction(function () use ($user, $points) {
                // Get or create wallet
                $wallet = $user->getOrCreateWallet();

                // Calculate wallet amount
                $walletAmount = $points / self::POINTS_TO_WALLET_RATIO;

                // Deduct points from user
                $pointsBefore = $user->points;
                $user->deductPoints($points);
                $pointsAfter = $user->points;

                // Add to wallet
                $transaction = $wallet->deposit(
                    $walletAmount,
                    'PTS-' . uniqid(),
                    "Points conversion: {$points} points to ₦{$walletAmount}",
                    ['points_converted' => $points, 'conversion_ratio' => self::POINTS_TO_WALLET_RATIO],
                    'Points'
                );

                // Log conversion in points history
                UserPointsHistory::create([
                    'user_id' => $user->id,
                    'points_change' => -$points,
                    'points_before' => $pointsBefore,
                    'points_after' => $pointsAfter,
                    'reason' => 'Converted to wallet',
                    'action_type' => 'conversion',
                    'metadata' => [
                        'wallet_amount' => $walletAmount,
                        'conversion_ratio' => self::POINTS_TO_WALLET_RATIO,
                        'transaction_id' => $transaction->id
                    ]
                ]);

                // Log conversion in points_conversions table
                $conversion = PointsConversion::create([
                    'user_id' => $user->id,
                    'points_converted' => $points,
                    'wallet_amount' => $walletAmount,
                    'conversion_ratio' => self::POINTS_TO_WALLET_RATIO,
                    'reference' => 'CONV-' . uniqid(),
                    'metadata' => [
                        'transaction_id' => $transaction->id,
                        'points_before' => $pointsBefore,
                        'points_after' => $pointsAfter,
                        'wallet_balance_after' => $wallet->balance
                    ]
                ]);

                return [
                    'success' => true,
                    'message' => "Successfully converted {$points} points to ₦{$walletAmount}",
                    'data' => [
                        'points_converted' => $points,
                        'wallet_amount' => $walletAmount,
                        'remaining_points' => $pointsAfter,
                        'new_wallet_balance' => $wallet->balance,
                        'conversion_id' => $conversion->reference,
                        'converted_at' => $conversion->created_at
                    ]
                ];
            });
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to convert points: ' . $e->getMessage(),
                'errors' => [$e->getMessage()]
            ];
        }
    }

    /**
     * Validate points conversion
     */
    public function validatePointsConversion(User $user, int $points): array
    {
        $errors = [];

        // Check if points is positive
        if ($points <= 0) {
            $errors[] = 'Points must be greater than zero';
        }

        // Check minimum points
        if ($points < self::POINTS_TO_WALLET_RATIO) {
            $errors[] = "Minimum " . self::POINTS_TO_WALLET_RATIO . " points required for conversion";
        }

        // Check if points is multiple of 10
        if ($points % self::POINTS_TO_WALLET_RATIO !== 0) {
            $errors[] = 'Points must be a multiple of ' . self::POINTS_TO_WALLET_RATIO;
        }

        // Check if user has enough points
        if ($user->points < $points) {
            $errors[] = "Insufficient points. You have {$user->points} points but requested {$points}";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Get conversion history for user
     */
    public function getConversionHistory(User $user, int $limit = 50)
    {
        return PointsConversion::forUser($user->id)
                              ->orderBy('created_at', 'desc')
                              ->limit($limit)
                              ->get();
    }

    /**
     * Process a deposit to user's wallet
     */
    public function deposit(User $user, float $amount, string $paymentMethod = null, array $metadata = [])
    {
        $wallet = $user->getOrCreateWallet();

        return DB::transaction(function () use ($wallet, $amount, $paymentMethod, $metadata) {
            return $wallet->deposit(
                $amount,
                null,
                "Wallet deposit via {$paymentMethod}",
                array_merge($metadata, ['payment_method' => $paymentMethod]),
                $paymentMethod
            );
        });
    }

    /**
     * Transfer money between users
     */
    public function transfer(User $sender, User $recipient, float $amount, string $description = null)
    {
        $senderWallet = $sender->getOrCreateWallet();
        $recipientWallet = $recipient->getOrCreateWallet();

        if ($senderWallet->balance < $amount) {
            throw new \Exception('Insufficient balance for transfer');
        }

        return DB::transaction(function () use ($senderWallet, $recipientWallet, $amount, $description) {
            return $senderWallet->transferTo($recipientWallet, $amount, $description);
        });
    }

    /**
     * Purchase a course using wallet balance
     */
    public function purchaseCourse(User $user, Course $course, string $couponCode = null)
    {
        $wallet = $user->getOrCreateWallet();

        // Check if user is already enrolled
        if ($user->isEnrolledIn($course)) {
            throw new \Exception('User is already enrolled in this course');
        }

        return DB::transaction(function () use ($wallet, $course, $couponCode) {
            $transaction = $wallet->purchaseCourse($course, $couponCode);
            
            // Record analytics
            if (class_exists('App\Models\CourseAnalytic')) {
                \App\Models\CourseAnalytic::recordEnrollment($course->id);
            }

            return $transaction;
        });
    }

    /**
     * Process daily login reward
     */
    public function processDailyLoginReward(User $user)
    {
        return UserReward::giveLoginReward($user);
    }

    /**
     * Process study time reward
     */
    public function processStudyTimeReward(User $user, int $studyMinutes)
    {
        return UserReward::giveStudyReward($user, $studyMinutes);
    }

    /**
     * Process course completion reward
     */
    public function processCourseCompletionReward(User $user, Course $course)
    {
        return UserReward::giveCourseCompletionReward($user, $course);
    }

    /**
     * Get user's wallet balance
     */
    public function getBalance(User $user): float
    {
        $wallet = $user->wallet;
        return $wallet ? $wallet->balance : 0;
    }

    /**
     * Get user's transaction history
     */
    public function getTransactionHistory(User $user, int $limit = 50)
    {
        $wallet = $user->wallet;
        if (!$wallet) {
            return collect();
        }

        return $wallet->transactions()
                     ->with(['relatedUser', 'course'])
                     ->orderBy('created_at', 'desc')
                     ->limit($limit)
                     ->get();
    }

    /**
     * Get user's reward history
     */
    public function getRewardHistory(User $user, int $limit = 50)
    {
        return $user->rewards()
                   ->orderBy('created_at', 'desc')
                   ->limit($limit)
                   ->get();
    }

    /**
     * Calculate user's current login streak
     */
    public function getLoginStreak(User $user): int
    {
        $latestReward = $user->rewards()
                           ->where('reward_type', 'daily_login')
                           ->orderBy('date', 'desc')
                           ->first();

        return $latestReward ? $latestReward->streak_count : 0;
    }

    /**
     * Get user's total earnings from rewards
     */
    public function getTotalRewardEarnings(User $user): float
    {
        return $user->rewards()->sum('amount');
    }

    /**
     * Get user's spending on courses
     */
    public function getTotalCourseSpending(User $user): float
    {
        $wallet = $user->wallet;
        if (!$wallet) {
            return 0;
        }

        return $wallet->transactions()
                     ->where('type', 'debit')
                     ->whereNotNull('course_id')
                     ->sum('amount');
    }

    /**
     * Check if user can afford a course
     */
    public function canAffordCourse(User $user, Course $course, string $couponCode = null): array
    {
        $wallet = $user->getOrCreateWallet();
        $price = $course->price;
        $discount = 0;

        // Calculate discount if coupon provided
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->active()->first();
            if ($coupon && $coupon->canBeUsedBy($user, $price)) {
                $discount = $coupon->calculateDiscount($price);
            }
        }

        $finalPrice = $price - $discount;
        $canAfford = $wallet->balance >= $finalPrice;

        return [
            'can_afford' => $canAfford,
            'balance' => $wallet->balance,
            'original_price' => $price,
            'discount' => $discount,
            'final_price' => $finalPrice,
            'shortfall' => $canAfford ? 0 : $finalPrice - $wallet->balance
        ];
    }

    /**
     * Get wallet statistics for user
     */
    public function getWalletStats(User $user): array
    {
        $wallet = $user->wallet;
        if (!$wallet) {
            return [
                'balance' => 0,
                'total_deposits' => 0,
                'total_spending' => 0,
                'total_rewards' => 0,
                'total_transfers_sent' => 0,
                'total_transfers_received' => 0,
                'transaction_count' => 0
            ];
        }

        $transactions = $wallet->transactions();

        return [
            'balance' => $wallet->balance,
            // Deposits: credit transactions with no related_user_id, course_id, or reward_type
            'total_deposits' => $transactions->clone()
                ->where('type', 'credit')
                ->whereNull('related_user_id')
                ->whereNull('course_id')
                ->whereNull('reward_type')
                ->sum('amount'),
            // Spending: debit transactions with course_id
            'total_spending' => $transactions->clone()
                ->where('type', 'debit')
                ->whereNotNull('course_id')
                ->sum('amount'),
            // Rewards: credit transactions with reward_type
            'total_rewards' => $transactions->clone()
                ->where('type', 'credit')
                ->whereNotNull('reward_type')
                ->sum('amount'),
            // Transfers sent: debit transactions with related_user_id
            'total_transfers_sent' => $transactions->clone()
                ->where('type', 'debit')
                ->whereNotNull('related_user_id')
                ->sum('amount'),
            // Transfers received: credit transactions with related_user_id
            'total_transfers_received' => $transactions->clone()
                ->where('type', 'credit')
                ->whereNotNull('related_user_id')
                ->sum('amount'),
            'transaction_count' => $transactions->count()
        ];
    }

    /**
     * Validate transfer between users
     */
    public function validateTransfer(User $sender, User $recipient, float $amount): array
    {
        $errors = [];

        if ($sender->id === $recipient->id) {
            $errors[] = 'Cannot transfer to yourself';
        }

        if ($amount <= 0) {
            $errors[] = 'Transfer amount must be greater than zero';
        }

        $senderWallet = $sender->wallet;
        if (!$senderWallet || $senderWallet->balance < $amount) {
            $errors[] = 'Insufficient balance for transfer';
        }

        if (!$recipient->is_active) {
            $errors[] = 'Recipient account is not active';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}
