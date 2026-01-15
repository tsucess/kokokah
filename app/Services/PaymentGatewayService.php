<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\Payment;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentGatewayService
{
    protected $gateways = [
        'paystack' => 'PaystackGateway',
        'flutterwave' => 'FlutterwaveGateway', 
        'stripe' => 'StripeGateway',
        'paypal' => 'PaypalGateway'
    ];

    /**
     * Initialize payment for wallet deposit
     */
    public function initializeWalletDeposit(User $user, float $amount, string $gateway, array $metadata = [])
    {
        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'currency' => config('app.currency', 'NGN'),
            'gateway' => $gateway,
            'type' => 'wallet_deposit',
            'status' => 'pending',
            'metadata' => array_merge($metadata, [
                'user_email' => $user->email,
                'user_name' => $user->full_name
            ])
        ]);

        try {
            $gatewayService = $this->getGatewayService($gateway);
            $response = $gatewayService->initializePayment($payment);

            // Check if initialization was successful
            if (!isset($response['success']) || !$response['success']) {
                // Mark payment as failed if gateway initialization failed
                $payment->update([
                    'status' => 'failed',
                    'gateway_response' => $response,
                    'failed_at' => now()
                ]);

                throw new \Exception($response['message'] ?? 'Payment initialization failed');
            }

            // Update payment with gateway reference and response
            $payment->update([
                'gateway_reference' => $response['reference'] ?? null,
                'gateway_response' => $response
            ]);

            return [
                'success' => true,
                'payment_id' => $payment->id,
                'gateway_data' => $response,
                'payment' => $payment
            ];
        } catch (\Exception $e) {
            Log::error('Wallet deposit initialization failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'amount' => $amount,
                'gateway' => $gateway
            ]);

            throw $e;
        }
    }

    /**
     * Initialize payment for subscription purchase
     */
    public function initializeSubscriptionPayment(User $user, SubscriptionPlan $plan, string $gateway, array $courseIds = [])
    {
        $amount = $plan->price;

        $payment = Payment::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'amount' => $amount,
            'currency' => config('app.currency', 'NGN'),
            'gateway' => $gateway,
            'type' => 'subscription_purchase',
            'status' => 'pending',
            'metadata' => [
                'user_email' => $user->email,
                'user_name' => $user->full_name,
                'plan_title' => $plan->title,
                'course_ids' => $courseIds
            ]
        ]);

        $gatewayService = $this->getGatewayService($gateway);
        $response = $gatewayService->initializePayment($payment);

        $payment->update([
            'gateway_reference' => $response['reference'] ?? null,
            'gateway_response' => $response
        ]);

        return [
            'payment_id' => $payment->id,
            'gateway_data' => $response,
            'payment' => $payment
        ];
    }

    /**
     * Initialize payment for direct course purchase
     */
    public function initializeCoursePayment(User $user, Course $course, string $gateway, string $couponCode = null)
    {
        $amount = $course->price;
        $discount = 0;

        // Apply coupon if provided
        if ($couponCode) {
            $coupon = \App\Models\Coupon::where('code', $couponCode)->active()->first();
            if ($coupon && $coupon->canBeUsedBy($user, $amount)) {
                $discount = $coupon->calculateDiscount($amount);
                $amount -= $discount;
            }
        }

        $payment = Payment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'amount' => $amount,
            'currency' => config('app.currency', 'NGN'),
            'gateway' => $gateway,
            'type' => 'course_purchase',
            'status' => 'pending',
            'metadata' => [
                'user_email' => $user->email,
                'user_name' => $user->full_name,
                'course_title' => $course->title,
                'original_price' => $course->price,
                'discount_amount' => $discount,
                'coupon_code' => $couponCode
            ]
        ]);

        $gatewayService = $this->getGatewayService($gateway);
        $response = $gatewayService->initializePayment($payment);

        $payment->update([
            'gateway_reference' => $response['reference'] ?? null,
            'gateway_response' => $response
        ]);

        return [
            'payment_id' => $payment->id,
            'gateway_data' => $response,
            'payment' => $payment
        ];
    }

    /**
     * Verify payment from gateway webhook/callback
     */
    public function verifyPayment(string $gateway, string $reference)
    {
        $gatewayService = $this->getGatewayService($gateway);
        $verification = $gatewayService->verifyPayment($reference);

        $payment = Payment::where('gateway_reference', $reference)->first();
        
        if (!$payment) {
            throw new \Exception('Payment not found');
        }

        if ($verification['status'] === 'success') {
            return $this->processSuccessfulPayment($payment, $verification);
        } else {
            return $this->processFailedPayment($payment, $verification);
        }
    }

    /**
     * Process successful payment
     */
    protected function processSuccessfulPayment(Payment $payment, array $verification)
    {
        $payment->update([
            'status' => 'completed',
            'gateway_response' => $verification,
            'completed_at' => now()
        ]);

        if ($payment->type === 'wallet_deposit') {
            // Add money to wallet
            $wallet = $payment->user->getOrCreateWallet();
            $transaction = $wallet->deposit(
                $payment->amount,
                $payment->gateway_reference,
                "Wallet deposit via {$payment->gateway}",
                ['payment_id' => $payment->id],
                $payment->gateway
            );

            return [
                'success' => true,
                'type' => 'wallet_deposit',
                'payment' => $payment,
                'transaction' => $transaction,
                'new_balance' => $wallet->balance
            ];
        }
        
        if ($payment->type === 'course_purchase') {
            // Enroll user in course
            $enrollment = $payment->user->enrollments()->create([
                'course_id' => $payment->course_id,
                'status' => 'active',
                'enrolled_at' => now(),
                'amount_paid' => $payment->amount
            ]);

            // Record transaction for tracking
            $wallet = $payment->user->getOrCreateWallet();
            $transaction = $wallet->transactions()->create([
                'amount' => $payment->amount,
                'type' => 'debit',
                'reference' => $payment->gateway_reference,
                'status' => 'success',
                'description' => "Course purchase: {$payment->course->title}",
                'course_id' => $payment->course_id,
                'payment_method' => $payment->gateway,
                'metadata' => ['payment_id' => $payment->id]
            ]);

            return [
                'success' => true,
                'type' => 'course_purchase',
                'payment' => $payment,
                'enrollment' => $enrollment,
                'transaction' => $transaction,
                'course' => $payment->course
            ];
        }

        if ($payment->type === 'subscription_purchase') {
            // Create subscription and enroll in courses
            return $this->processSubscriptionPayment($payment);
        }

        return ['success' => true, 'payment' => $payment];
    }

    /**
     * Process subscription payment
     */
    protected function processSubscriptionPayment(Payment $payment)
    {
        return DB::transaction(function () use ($payment) {
            $user = $payment->user;
            $plan = $payment->subscriptionPlan;
            $courseIds = $payment->metadata['course_ids'] ?? [];

            // Calculate expiration date based on duration type
            $expiresAt = Carbon::now();
            switch ($plan->duration_type) {
                case 'daily':
                    $expiresAt->addDays($plan->duration);
                    break;
                case 'weekly':
                    $expiresAt->addWeeks($plan->duration);
                    break;
                case 'monthly':
                    $expiresAt->addMonths($plan->duration);
                    break;
                case 'yearly':
                    $expiresAt->addYears($plan->duration);
                    break;
            }

            // Create subscription
            $subscription = UserSubscription::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'started_at' => Carbon::now(),
                'expires_at' => $expiresAt,
                'status' => 'active',
                'amount_paid' => $payment->amount,
                'payment_reference' => $payment->gateway_reference
            ]);

            // Enroll user in selected courses
            $enrollments = [];
            if (is_array($courseIds) && count($courseIds) > 0) {
                foreach ($courseIds as $courseId) {
                    // Check if already enrolled
                    $existingEnrollment = Enrollment::where('user_id', $user->id)
                                                   ->where('course_id', $courseId)
                                                   ->first();

                    if (!$existingEnrollment) {
                        $enrollment = Enrollment::create([
                            'user_id' => $user->id,
                            'course_id' => $courseId,
                            'status' => 'active',
                            'enrolled_at' => Carbon::now(),
                            'amount_paid' => $payment->amount
                        ]);
                        $enrollments[] = $enrollment;
                    }
                }
            }

            return [
                'success' => true,
                'type' => 'subscription_purchase',
                'payment' => $payment,
                'subscription' => $subscription,
                'enrollments' => $enrollments,
                'plan' => $plan
            ];
        });
    }

    /**
     * Process failed payment
     */
    protected function processFailedPayment(Payment $payment, array $verification)
    {
        $payment->update([
            'status' => 'failed',
            'gateway_response' => $verification,
            'failed_at' => now()
        ]);

        return [
            'success' => false,
            'payment' => $payment,
            'message' => $verification['message'] ?? 'Payment failed'
        ];
    }

    /**
     * Get gateway service instance
     */
    protected function getGatewayService(string $gateway)
    {
        $className = "App\\Services\\Gateways\\{$this->gateways[$gateway]}";
        
        if (!class_exists($className)) {
            throw new \Exception("Gateway service not found: {$gateway}");
        }

        return new $className();
    }

    /**
     * Get available payment gateways
     */
    public function getAvailableGateways(): array
    {
        return [
            'paystack' => [
                'name' => 'Paystack',
                'currencies' => ['NGN', 'USD', 'GHS', 'ZAR'],
                'logo' => '/images/gateways/paystack.png'
            ],
            'flutterwave' => [
                'name' => 'Flutterwave',
                'currencies' => ['NGN', 'USD', 'GHS', 'KES', 'UGX'],
                'logo' => '/images/gateways/flutterwave.png'
            ],
            'stripe' => [
                'name' => 'Stripe',
                'currencies' => ['USD', 'EUR', 'GBP', 'CAD'],
                'logo' => '/images/gateways/stripe.png'
            ],
            'paypal' => [
                'name' => 'PayPal',
                'currencies' => ['USD', 'EUR', 'GBP', 'CAD'],
                'logo' => '/images/gateways/paypal.png'
            ]
        ];
    }

    /**
     * Get recommended gateway based on user location/currency
     */
    public function getRecommendedGateway(string $currency = 'NGN'): string
    {
        return match($currency) {
            'NGN' => 'paystack',
            'GHS', 'KES', 'UGX' => 'flutterwave',
            'USD', 'EUR', 'GBP', 'CAD' => 'stripe',
            default => 'paystack'
        };
    }
}
