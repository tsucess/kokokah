<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Course;
use App\Models\UserReward;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;

class WalletService
{
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
                     ->where('type', 'purchase')
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
            'total_deposits' => $transactions->clone()->where('type', 'deposit')->sum('amount'),
            'total_spending' => $transactions->clone()->where('type', 'purchase')->sum('amount'),
            'total_rewards' => $transactions->clone()->where('type', 'reward')->sum('amount'),
            'total_transfers_sent' => $transactions->clone()->where('type', 'transfer')->where('amount', '<', 0)->sum('amount'),
            'total_transfers_received' => $transactions->clone()->where('type', 'transfer')->where('amount', '>', 0)->sum('amount'),
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
