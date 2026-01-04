<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Payment;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentGatewayService $paymentService)
    {
        $this->paymentService = $paymentService;
        // Middleware applied at route level in Laravel 12
    }

    /**
     * Get available payment gateways
     */
    public function gateways()
    {
        return response()->json([
            'success' => true,
            'data' => $this->paymentService->getAvailableGateways()
        ]);
    }

    /**
     * Initialize wallet deposit payment
     */
    public function initializeWalletDeposit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1|max:1000000',
            'gateway' => 'required|string|in:paystack,flutterwave,stripe,paypal',
            'currency' => 'nullable|string|size:3'
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
            $currency = $request->currency ?: config('app.currency', 'NGN');
            
            $result = $this->paymentService->initializeWalletDeposit(
                $user,
                $request->amount,
                $request->gateway,
                ['currency' => $currency]
            );

            return response()->json([
                'success' => true,
                'message' => 'Payment initialized successfully',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Wallet deposit initialization failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Payment initialization failed: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Initialize direct course purchase payment
     */
    public function initializeCoursePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'gateway' => 'required|string|in:paystack,flutterwave,stripe,paypal',
            'coupon_code' => 'nullable|string|exists:coupons,code',
            'currency' => 'nullable|string|size:3'
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

            // Check if user is already enrolled
            if ($user->isEnrolledIn($course)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already enrolled in this course'
                ], 400);
            }

            $result = $this->paymentService->initializeCoursePayment(
                $user,
                $course,
                $request->gateway,
                $request->coupon_code
            );

            return response()->json([
                'success' => true,
                'message' => 'Payment initialized successfully',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Course payment initialization failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Payment initialization failed: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Handle payment webhook from gateways
     */
    public function webhook(Request $request, string $gateway)
    {
        try {
            $payload = $request->all();
            Log::info("Payment webhook received from {$gateway}", $payload);

            $reference = $this->extractReference($gateway, $payload);

            if (!$reference) {
                Log::warning("No reference found in webhook payload for {$gateway}");
                return response()->json(['status' => 'success']); // Return success to prevent retry
            }

            $result = $this->paymentService->verifyPayment($gateway, $reference);

            if ($result['success']) {
                Log::info("Payment verified successfully", $result);
                return response()->json(['status' => 'success']);
            }

            Log::warning("Payment verification failed", $result);
            return response()->json(['status' => 'success']); // Return success to prevent retry

        } catch (\Exception $e) {
            Log::error("Webhook processing failed for {$gateway}: " . $e->getMessage());
            return response()->json(['status' => 'success']); // Return success to prevent retry
        }
    }

    /**
     * Handle payment callback/redirect from gateways
     */
    public function callback(Request $request, string $gateway)
    {
        try {
            $reference = $this->extractCallbackReference($gateway, $request);

            if (!$reference) {
                return redirect()->to(config('app.frontend_url') . '/payment/failed?error=missing_reference');
            }

            $result = $this->paymentService->verifyPayment($gateway, $reference);

            if ($result['success']) {
                // Redirect based on payment type
                if ($result['type'] === 'wallet_deposit') {
                    // Redirect to wallet page for wallet deposits
                    $redirectUrl = config('app.frontend_url') . '/userkudikah?payment_success=true&reference=' . $reference;
                } else {
                    // Redirect to subject page for course purchases
                    $redirectUrl = config('app.frontend_url') . '/usersubject?payment_success=true&reference=' . $reference;
                }
                return redirect()->to($redirectUrl);
            }

            $redirectUrl = config('app.frontend_url') . '/payment/failed?reference=' . $reference;
            return redirect()->to($redirectUrl);

        } catch (\Exception $e) {
            Log::error("Callback processing failed for {$gateway}: " . $e->getMessage());
            $redirectUrl = config('app.frontend_url') . '/payment/failed?error=processing_failed';
            return redirect()->to($redirectUrl);
        }
    }

    /**
     * Payment success page
     */
    public function success(Request $request, string $gateway)
    {
        $reference = $request->get('reference') ?: $request->get('session_id');
        
        return response()->json([
            'success' => true,
            'message' => 'Payment completed successfully',
            'reference' => $reference,
            'gateway' => $gateway
        ]);
    }

    /**
     * Payment cancel page
     */
    public function cancel(Request $request, string $gateway)
    {
        return response()->json([
            'success' => false,
            'message' => 'Payment was cancelled',
            'gateway' => $gateway
        ]);
    }

    /**
     * Get user's payment history
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 50);
        $type = $request->get('type'); // wallet_deposit, course_purchase
        $status = $request->get('status'); // pending, completed, failed

        $query = Payment::where('user_id', $user->id)
                       ->with(['course'])
                       ->orderBy('created_at', 'desc');

        if ($type) {
            $query->where('type', $type);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $payments = $query->limit($limit)->get();

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    /**
     * Get payment details
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $payment = Payment::where('user_id', $user->id)
                         ->with(['course'])
                         ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $payment
        ]);
    }

    /**
     * Extract reference from webhook payload
     */
    private function extractReference(string $gateway, array $payload): string
    {
        return match($gateway) {
            'paystack' => $payload['data']['reference'] ?? '',
            'flutterwave' => $payload['data']['tx_ref'] ?? '',
            'stripe' => $payload['data']['object']['client_reference_id'] ?? '',
            'paypal' => $payload['resource']['purchase_units'][0]['reference_id'] ?? '',
            default => ''
        };
    }

    /**
     * Extract reference from callback request
     */
    private function extractCallbackReference(string $gateway, Request $request): ?string
    {
        return match($gateway) {
            'paystack' => $request->get('reference'),
            'flutterwave' => $request->get('tx_ref'),
            'stripe' => $request->get('session_id'),
            'paypal' => $request->get('token'),
            default => null
        };
    }
}
