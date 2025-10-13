<?php

namespace App\Services\Gateways;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackGateway implements PaymentGatewayInterface
{
    protected $baseUrl;
    protected $secretKey;
    protected $publicKey;

    public function __construct()
    {
        $this->baseUrl = 'https://api.paystack.co';
        $this->secretKey = config('services.paystack.secret_key');
        $this->publicKey = config('services.paystack.public_key');
    }

    /**
     * Initialize payment with Paystack
     */
    public function initializePayment(Payment $payment): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/transaction/initialize', [
                'email' => $payment->user->email,
                'amount' => $payment->amount * 100, // Convert to kobo
                'currency' => $payment->currency,
                'reference' => $payment->gateway_reference,
                'callback_url' => route('payment.callback', ['gateway' => 'paystack']),
                'metadata' => [
                    'payment_id' => $payment->id,
                    'user_id' => $payment->user_id,
                    'type' => $payment->type,
                    'course_id' => $payment->course_id,
                    'custom_fields' => [
                        [
                            'display_name' => 'Payment Type',
                            'variable_name' => 'payment_type',
                            'value' => $payment->getTypeDescription()
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'reference' => $payment->gateway_reference,
                    'authorization_url' => $data['data']['authorization_url'],
                    'access_code' => $data['data']['access_code'],
                    'gateway_response' => $data
                ];
            }

            throw new \Exception('Paystack initialization failed: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Paystack initialization error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Payment initialization failed',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify payment with Paystack
     */
    public function verifyPayment(string $reference): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get($this->baseUrl . '/transaction/verify/' . $reference);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['data']['status'] === 'success') {
                    return [
                        'status' => 'success',
                        'reference' => $data['data']['reference'],
                        'amount' => $data['data']['amount'] / 100, // Convert from kobo
                        'currency' => $data['data']['currency'],
                        'gateway_response' => $data,
                        'transaction_date' => $data['data']['transaction_date']
                    ];
                }

                return [
                    'status' => 'failed',
                    'message' => $data['data']['gateway_response'] ?? 'Payment failed',
                    'gateway_response' => $data
                ];
            }

            throw new \Exception('Verification request failed');

        } catch (\Exception $e) {
            Log::error('Paystack verification error: ' . $e->getMessage());
            
            return [
                'status' => 'failed',
                'message' => 'Payment verification failed',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle webhook from Paystack
     */
    public function handleWebhook(array $payload): array
    {
        try {
            // Verify webhook signature
            $signature = request()->header('x-paystack-signature');
            $computedSignature = hash_hmac('sha512', request()->getContent(), $this->secretKey);
            
            if (!hash_equals($signature, $computedSignature)) {
                throw new \Exception('Invalid webhook signature');
            }

            $event = $payload['event'];
            $data = $payload['data'];

            if ($event === 'charge.success') {
                return $this->verifyPayment($data['reference']);
            }

            return [
                'status' => 'ignored',
                'message' => 'Event not handled: ' . $event
            ];

        } catch (\Exception $e) {
            Log::error('Paystack webhook error: ' . $e->getMessage());
            
            return [
                'status' => 'failed',
                'message' => 'Webhook processing failed',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get supported currencies
     */
    public function getSupportedCurrencies(): array
    {
        return ['NGN', 'USD', 'GHS', 'ZAR'];
    }

    /**
     * Get gateway configuration for frontend
     */
    public function getClientConfig(): array
    {
        return [
            'public_key' => $this->publicKey,
            'currency' => config('app.currency', 'NGN'),
            'supported_currencies' => $this->getSupportedCurrencies()
        ];
    }

    /**
     * Create payment plan for subscriptions (future use)
     */
    public function createPlan(string $name, int $amount, string $interval = 'monthly'): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/plan', [
                'name' => $name,
                'amount' => $amount * 100,
                'interval' => $interval,
                'currency' => config('app.currency', 'NGN')
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'plan' => $response->json()['data']
                ];
            }

            throw new \Exception('Plan creation failed');

        } catch (\Exception $e) {
            Log::error('Paystack plan creation error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Plan creation failed',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get transaction history from Paystack
     */
    public function getTransactions(int $perPage = 50, int $page = 1): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get($this->baseUrl . '/transaction', [
                'perPage' => $perPage,
                'page' => $page
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'transactions' => $response->json()['data']
                ];
            }

            throw new \Exception('Failed to fetch transactions');

        } catch (\Exception $e) {
            Log::error('Paystack transactions error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to fetch transactions',
                'error' => $e->getMessage()
            ];
        }
    }
}
