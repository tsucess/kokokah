<?php

namespace App\Services\Gateways;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StripeGateway implements PaymentGatewayInterface
{
    protected $baseUrl;
    protected $secretKey;
    protected $publicKey;

    public function __construct()
    {
        $this->baseUrl = 'https://api.stripe.com/v1';
        $this->secretKey = config('services.stripe.secret');
        $this->publicKey = config('services.stripe.key');
    }

    /**
     * Initialize payment with Stripe
     */
    public function initializePayment(Payment $payment): array
    {
        try {
            // Create Stripe Checkout Session
            $response = Http::withBasicAuth($this->secretKey, '')
                ->asForm()
                ->post($this->baseUrl . '/checkout/sessions', [
                    'payment_method_types[]' => 'card',
                    'line_items[0][price_data][currency]' => strtolower($payment->currency),
                    'line_items[0][price_data][product_data][name]' => $payment->getDescription(),
                    'line_items[0][price_data][unit_amount]' => $payment->amount * 100, // Convert to cents
                    'line_items[0][quantity]' => 1,
                    'mode' => 'payment',
                    'success_url' => route('payment.success', ['gateway' => 'stripe']) . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('payment.cancel', ['gateway' => 'stripe']),
                    'client_reference_id' => $payment->gateway_reference,
                    'customer_email' => $payment->user->email,
                    'metadata[payment_id]' => $payment->id,
                    'metadata[user_id]' => $payment->user_id,
                    'metadata[type]' => $payment->type,
                    'metadata[course_id]' => $payment->course_id,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'reference' => $payment->gateway_reference,
                    'authorization_url' => $data['url'],
                    'session_id' => $data['id'],
                    'gateway_response' => $data
                ];
            }

            throw new \Exception('Stripe initialization failed: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Stripe initialization error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Payment initialization failed',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify payment with Stripe
     */
    public function verifyPayment(string $reference): array
    {
        try {
            // For Stripe, reference could be session_id or payment_intent_id
            $response = Http::withBasicAuth($this->secretKey, '')
                ->get($this->baseUrl . '/checkout/sessions/' . $reference);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['payment_status'] === 'paid') {
                    return [
                        'status' => 'success',
                        'reference' => $data['client_reference_id'],
                        'amount' => $data['amount_total'] / 100, // Convert from cents
                        'currency' => strtoupper($data['currency']),
                        'gateway_response' => $data,
                        'transaction_date' => now()->toISOString()
                    ];
                }

                return [
                    'status' => 'failed',
                    'message' => 'Payment not completed',
                    'gateway_response' => $data
                ];
            }

            throw new \Exception('Verification request failed');

        } catch (\Exception $e) {
            Log::error('Stripe verification error: ' . $e->getMessage());
            
            return [
                'status' => 'failed',
                'message' => 'Payment verification failed',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle webhook from Stripe
     */
    public function handleWebhook(array $payload): array
    {
        try {
            // Verify webhook signature
            $signature = request()->header('stripe-signature');
            $endpointSecret = config('services.stripe.webhook_secret');
            
            // Stripe signature verification would go here
            // For now, we'll process the webhook
            
            $event = $payload['type'];
            $data = $payload['data']['object'];

            if ($event === 'checkout.session.completed') {
                return $this->verifyPayment($data['id']);
            }

            return [
                'status' => 'ignored',
                'message' => 'Event not handled: ' . $event
            ];

        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            
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
        return ['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'JPY', 'CHF', 'SEK', 'NOK', 'DKK'];
    }

    /**
     * Get gateway configuration for frontend
     */
    public function getClientConfig(): array
    {
        return [
            'public_key' => $this->publicKey,
            'currency' => config('app.currency', 'USD'),
            'supported_currencies' => $this->getSupportedCurrencies()
        ];
    }

    /**
     * Create payment plan for subscriptions (future use)
     */
    public function createPlan(string $name, int $amount, string $interval = 'month'): array
    {
        try {
            // Create product first
            $productResponse = Http::withBasicAuth($this->secretKey, '')
                ->asForm()
                ->post($this->baseUrl . '/products', [
                    'name' => $name,
                ]);

            if (!$productResponse->successful()) {
                throw new \Exception('Product creation failed');
            }

            $product = $productResponse->json();

            // Create price
            $priceResponse = Http::withBasicAuth($this->secretKey, '')
                ->asForm()
                ->post($this->baseUrl . '/prices', [
                    'unit_amount' => $amount * 100,
                    'currency' => strtolower(config('app.currency', 'USD')),
                    'recurring[interval]' => $interval,
                    'product' => $product['id'],
                ]);

            if ($priceResponse->successful()) {
                return [
                    'success' => true,
                    'plan' => $priceResponse->json()
                ];
            }

            throw new \Exception('Price creation failed');

        } catch (\Exception $e) {
            Log::error('Stripe plan creation error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Plan creation failed',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get transaction history from Stripe
     */
    public function getTransactions(int $limit = 50): array
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->get($this->baseUrl . '/payment_intents', [
                    'limit' => $limit
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'transactions' => $response->json()['data']
                ];
            }

            throw new \Exception('Failed to fetch transactions');

        } catch (\Exception $e) {
            Log::error('Stripe transactions error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to fetch transactions',
                'error' => $e->getMessage()
            ];
        }
    }
}
