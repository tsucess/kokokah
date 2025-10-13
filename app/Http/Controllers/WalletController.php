<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
        $this->middleware('auth:sanctum');
    }

    /**
     * Get user's wallet information
     */
    public function index()
    {
        $user = Auth::user();
        $stats = $this->walletService->getWalletStats($user);
        $recentTransactions = $this->walletService->getTransactionHistory($user, 10);
        $recentRewards = $this->walletService->getRewardHistory($user, 5);
        $loginStreak = $this->walletService->getLoginStreak($user);

        return response()->json([
            'success' => true,
            'data' => [
                'balance' => $stats['balance'],
                'stats' => $stats,
                'recent_transactions' => $recentTransactions,
                'recent_rewards' => $recentRewards,
                'login_streak' => $loginStreak
            ]
        ]);
    }



    /**
     * Transfer money to another user
     */
    public function transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_email' => 'required|email|exists:users,email',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $sender = Auth::user();
            $recipient = User::where('email', $request->recipient_email)->first();

            // Validate transfer
            $validation = $this->walletService->validateTransfer($sender, $recipient, $request->amount);
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transfer validation failed',
                    'errors' => $validation['errors']
                ], 400);
            }

            $transactions = $this->walletService->transfer(
                $sender,
                $recipient,
                $request->amount,
                $request->description
            );

            return response()->json([
                'success' => true,
                'message' => 'Transfer successful',
                'data' => [
                    'transactions' => $transactions,
                    'new_balance' => $sender->wallet->balance
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Transfer failed: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Purchase a course using wallet balance
     */
    public function purchaseCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'coupon_code' => 'nullable|string|exists:coupons,code'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $course = Course::findOrFail($request->course_id);

            // Check affordability
            $affordability = $this->walletService->canAffordCourse($user, $course, $request->coupon_code);
            if (!$affordability['can_afford']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance',
                    'data' => $affordability
                ], 400);
            }

            $transaction = $this->walletService->purchaseCourse($user, $course, $request->coupon_code);

            return response()->json([
                'success' => true,
                'message' => 'Course purchased successfully',
                'data' => [
                    'transaction' => $transaction,
                    'course' => $course,
                    'new_balance' => $user->wallet->balance
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase failed: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get transaction history
     */
    public function transactions(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 50);
        $type = $request->get('type'); // deposit, transfer, purchase, reward, withdrawal

        $transactions = $this->walletService->getTransactionHistory($user, $limit);

        if ($type) {
            $transactions = $transactions->filter(function ($transaction) use ($type) {
                switch ($type) {
                    case 'deposit':
                        return $transaction->isDeposit();
                    case 'transfer':
                        return $transaction->isTransfer();
                    case 'purchase':
                        return $transaction->isPurchase();
                    case 'reward':
                        return $transaction->isReward();
                    case 'withdrawal':
                        return $transaction->isWithdrawal();
                    default:
                        return true;
                }
            });
        }

        return response()->json([
            'success' => true,
            'data' => $transactions->values()
        ]);
    }

    /**
     * Get reward history
     */
    public function rewards(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 50);

        $rewards = $this->walletService->getRewardHistory($user, $limit);

        return response()->json([
            'success' => true,
            'data' => [
                'rewards' => $rewards,
                'total_earned' => $this->walletService->getTotalRewardEarnings($user),
                'login_streak' => $this->walletService->getLoginStreak($user)
            ]
        ]);
    }

    /**
     * Process daily login reward
     */
    public function claimLoginReward()
    {
        try {
            $user = Auth::user();
            $reward = $this->walletService->processDailyLoginReward($user);

            if (!$reward) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login reward already claimed today'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Login reward claimed successfully',
                'data' => [
                    'reward' => $reward,
                    'new_balance' => $user->wallet->balance
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to claim reward: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Check course affordability
     */
    public function checkAffordability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'coupon_code' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $course = Course::findOrFail($request->course_id);
        $affordability = $this->walletService->canAffordCourse($user, $course, $request->coupon_code);

        return response()->json([
            'success' => true,
            'data' => $affordability
        ]);
    }
}
