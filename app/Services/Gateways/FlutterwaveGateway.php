<?php

namespace App\Services\Gateways;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveGateway implements PaymentGatewayInterface
{
    protected $baseUrl;
    protected $secretKey;
    protected $publicKey;

    public function __construct()
    {
        $this->baseUrl = 'https://api.flutterwave.com/v3';
        $this->secretKey = config('services.flutterwave.secret_key');
        $this->publicKey = config('services.flutterwave.public_key');
    }

    /**
     * Initialize payment with Flutterwave
     */
    public function initializePayment(Payment $payment): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/payments', [
                'tx_ref' => $payment->gateway_reference,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'redirect_url' => route('payment.callback', ['gateway' => 'flutterwave']),
                'customer' => [
                    'email' => $payment->user->email,
                    'name' => $payment->user->full_name,
                ],
                'customizations' => [
                    'title' => config('app.name') . ' Payment',
                    'description' => $payment->getDescription(),
                    'logo' => config('app.url') . '/images/logo.png'
                ],
                'meta' => [
                    'payment_id' => $payment->id,
                    'user_id' => $payment->user_id,
                    'type' => $payment->type,
                    'course_id' => $payment->course_id,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'reference' => $payment->gateway_reference,
                    'authorization_url' => $data['data']['link'],
                    'gateway_response' => $data
                ];
            }

            throw new \Exception('Flutterwave initialization failed: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Flutterwave initialization error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Payment initialization failed',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify payment with Flutterwave
     */
    public function verifyPayment(string $reference): array
    {
        try {
            Log::info('Flutterwave verification started', ['reference' => $reference]);

            // Use verify_by_reference endpoint with tx_ref as query parameter
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get($this->baseUrl . '/transactions/verify_by_reference?tx_ref=' . $reference);

            Log::info('Flutterwave verification response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['data']['status'] === 'successful') {
                    return [
                        'status' => 'success',
                        'reference' => $data['data']['tx_ref'],
                        'amount' => $data['data']['amount'],
                        'currency' => $data['data']['currency'],
                        'gateway_response' => $data,
                        'transaction_date' => $data['data']['created_at']
                    ];
                }

                return [
                    'status' => 'failed',
                    'message' => $data['data']['processor_response'] ?? 'Payment failed',
                    'gateway_response' => $data
                ];
            }

            throw new \Exception('Verification request failed with status: ' . $response->status());

        } catch (\Exception $e) {
            Log::error('Flutterwave verification error: ' . $e->getMessage());

            return [
                'status' => 'failed',
                'message' => 'Payment verification failed',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle webhook from Flutterwave
     */
    public function handleWebhook(array $payload): array
    {
        try {
            // Verify webhook signature
            $signature = request()->header('verif-hash');
            $secretHash = config('services.flutterwave.webhook_secret');
            
            if ($signature !== $secretHash) {
                throw new \Exception('Invalid webhook signature');
            }

            $event = $payload['event'];
            $data = $payload['data'];

            if ($event === 'charge.completed') {
                return $this->verifyPayment($data['tx_ref']);
            }

            return [
                'status' => 'ignored',
                'message' => 'Event not handled: ' . $event
            ];

        } catch (\Exception $e) {
            Log::error('Flutterwave webhook error: ' . $e->getMessage());
            
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
        return ['NGN', 'USD', 'GHS', 'KES', 'UGX', 'ZAR', 'EUR', 'GBP'];
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
            ])->post($this->baseUrl . '/payment-plans', [
                'amount' => $amount,
                'name' => $name,
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
            Log::error('Flutterwave plan creation error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Plan creation failed',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get transaction history from Flutterwave
     */
    public function getTransactions(int $perPage = 50, int $page = 1): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get($this->baseUrl . '/transactions', [
                'per_page' => $perPage,
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
            Log::error('Flutterwave transactions error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to fetch transactions',
                'error' => $e->getMessage()
            ];
        }
    }
}
