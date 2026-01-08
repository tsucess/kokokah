<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\PaymentMethod;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
        // Middleware applied at route level in Laravel 12
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
     * For admins: returns all transactions in the system
     * For regular users: returns only their own transactions
     */
    public function transactions(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 50);
        $type = $request->get('type'); // deposit, transfer, purchase, reward, withdrawal

        // Check if user is admin
        $isAdmin = $user && in_array($user->role, ['admin', 'super_admin', 'superadmin']);

        \Log::info('WalletController::transactions', [
            'user_id' => $user?->id,
            'user_role' => $user?->role,
            'is_admin' => $isAdmin,
            'type' => $type,
            'limit' => $limit
        ]);

        if ($isAdmin) {
            // For admins, get all transactions
            $transactions = \App\Models\WalletTransaction::with(['wallet.user', 'course'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
            \Log::info('Admin transactions fetched', ['count' => count($transactions)]);
        } else {
            // For regular users, get only their transactions
            $transactions = $this->walletService->getTransactionHistory($user, $limit);
            \Log::info('User transactions fetched', ['count' => count($transactions)]);
        }

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

    /**
     * Get user's saved payment methods
     */
    public function getPaymentMethods()
    {
        $user = Auth::user();
        $paymentMethods = $user->paymentMethods()
            ->where('is_saved', true)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($method) {
                return [
                    'id' => $method->id,
                    'card_holder_name' => $method->card_holder_name,
                    'card_last_four' => $method->card_last_four,
                    'expiry_date' => $method->expiry_date,
                    'card_type' => $method->card_type,
                    'is_default' => $method->is_default,
                    'masked_card' => $method->getMaskedCardNumber(),
                    'last_used_at' => $method->last_used_at
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $paymentMethods
        ]);
    }

    /**
     * Add a new payment method
     */
    public function addPaymentMethod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_holder_name' => 'required|string|max:255',
            'card_number' => 'required|string|regex:/^\d{13,19}$/',
            'expiry_date' => 'required|string|regex:/^\d{2}\/\d{2}$/',
            'cvv' => 'required|string|regex:/^\d{3,4}$/',
            'is_default' => 'sometimes|boolean'
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

            // Extract last 4 digits
            $cardNumber = $request->card_number;
            $lastFour = substr($cardNumber, -4);

            // Detect card type
            $cardType = $this->detectCardType($cardNumber);

            // If this is set as default, unset other defaults
            if ($request->is_default) {
                $user->paymentMethods()->update(['is_default' => false]);
            }

            // Create payment method with encrypted data
            $paymentMethod = $user->paymentMethods()->create([
                'card_holder_name' => $request->card_holder_name,
                'card_number' => Crypt::encryptString($cardNumber),
                'card_last_four' => $lastFour,
                'expiry_date' => $request->expiry_date,
                'cvv' => Crypt::encryptString($request->cvv),
                'card_type' => $cardType,
                'is_default' => $request->is_default ?? false,
                'is_saved' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment method saved successfully',
                'data' => [
                    'id' => $paymentMethod->id,
                    'card_holder_name' => $paymentMethod->card_holder_name,
                    'card_last_four' => $paymentMethod->card_last_four,
                    'expiry_date' => $paymentMethod->expiry_date,
                    'card_type' => $paymentMethod->card_type,
                    'is_default' => $paymentMethod->is_default,
                    'masked_card' => $paymentMethod->getMaskedCardNumber()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save payment method: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Delete a payment method
     */
    public function deletePaymentMethod($methodId)
    {
        try {
            $user = Auth::user();
            $paymentMethod = $user->paymentMethods()->findOrFail($methodId);

            // If this was the default, set another as default
            if ($paymentMethod->is_default) {
                $nextDefault = $user->paymentMethods()
                    ->where('id', '!=', $methodId)
                    ->first();

                if ($nextDefault) {
                    $nextDefault->update(['is_default' => true]);
                }
            }

            $paymentMethod->delete();

            return response()->json([
                'success' => true,
                'message' => 'Payment method deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete payment method: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Set a payment method as default
     */
    public function setDefaultPaymentMethod($methodId)
    {
        try {
            $user = Auth::user();
            $paymentMethod = $user->paymentMethods()->findOrFail($methodId);

            // Unset all other defaults
            $user->paymentMethods()->update(['is_default' => false]);

            // Set this as default
            $paymentMethod->update(['is_default' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Default payment method updated successfully',
                'data' => [
                    'id' => $paymentMethod->id,
                    'is_default' => $paymentMethod->is_default
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set default payment method: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Detect card type from card number
     */
    private function detectCardType($cardNumber)
    {
        $patterns = [
            'visa' => '/^4[0-9]{12}(?:[0-9]{3})?$/',
            'mastercard' => '/^5[1-5][0-9]{14}$/',
            'amex' => '/^3[47][0-9]{13}$/',
            'discover' => '/^6(?:011|5[0-9]{2})[0-9]{12}$/'
        ];

        foreach ($patterns as $type => $pattern) {
            if (preg_match($pattern, $cardNumber)) {
                return $type;
            }
        }

        return 'unknown';
    }
}
