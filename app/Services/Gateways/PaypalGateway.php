<?php

namespace App\Services\Gateways;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaypalGateway implements PaymentGatewayInterface
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $accessToken;

    public function __construct()
    {
        $this->baseUrl = config('services.paypal.mode') === 'live' 
            ? 'https://api.paypal.com' 
            : 'https://api.sandbox.paypal.com';
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
        $this->getAccessToken();
    }

    /**
     * Get PayPal access token
     */
    protected function getAccessToken()
    {
        try {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->asForm()
                ->post($this->baseUrl . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials'
                ]);

            if ($response->successful()) {
                $this->accessToken = $response->json()['access_token'];
            }
        } catch (\Exception $e) {
            Log::error('PayPal access token error: ' . $e->getMessage());
        }
    }

    /**
     * Initialize payment with PayPal
     */
    public function initializePayment(Payment $payment): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $payment->gateway_reference,
                        'amount' => [
                            'currency_code' => $payment->currency,
                            'value' => number_format($payment->amount, 2, '.', '')
                        ],
                        'description' => $payment->getDescription(),
                        'custom_id' => $payment->id
                    ]
                ],
                'application_context' => [
                    'return_url' => route('payment.success', ['gateway' => 'paypal']),
                    'cancel_url' => route('payment.cancel', ['gateway' => 'paypal']),
                    'brand_name' => config('app.name'),
                    'landing_page' => 'BILLING',
                    'user_action' => 'PAY_NOW'
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $approvalUrl = collect($data['links'])->firstWhere('rel', 'approve')['href'];
                
                return [
                    'success' => true,
                    'reference' => $payment->gateway_reference,
                    'authorization_url' => $approvalUrl,
                    'order_id' => $data['id'],
                    'gateway_response' => $data
                ];
            }

            throw new \Exception('PayPal initialization failed: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('PayPal initialization error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Payment initialization failed',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify payment with PayPal
     */
    public function verifyPayment(string $reference): array
    {
        try {
            // For PayPal, reference is the order ID
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->get($this->baseUrl . '/v2/checkout/orders/' . $reference);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'COMPLETED') {
                    $purchaseUnit = $data['purchase_units'][0];
                    $capture = $purchaseUnit['payments']['captures'][0];
                    
                    return [
                        'status' => 'success',
                        'reference' => $purchaseUnit['reference_id'],
                        'amount' => $capture['amount']['value'],
                        'currency' => $capture['amount']['currency_code'],
                        'gateway_response' => $data,
                        'transaction_date' => $capture['create_time']
                    ];
                }

                return [
                    'status' => 'failed',
                    'message' => 'Payment not completed. Status: ' . $data['status'],
                    'gateway_response' => $data
                ];
            }

            throw new \Exception('Verification request failed');

        } catch (\Exception $e) {
            Log::error('PayPal verification error: ' . $e->getMessage());
            
            return [
                'status' => 'failed',
                'message' => 'Payment verification failed',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Capture PayPal order
     */
    public function captureOrder(string $orderId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/v2/checkout/orders/' . $orderId . '/capture');

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            throw new \Exception('Order capture failed');

        } catch (\Exception $e) {
            Log::error('PayPal capture error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Order capture failed',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle webhook from PayPal
     */
    public function handleWebhook(array $payload): array
    {
        try {
            // PayPal webhook verification would go here
            $event = $payload['event_type'];
            $resource = $payload['resource'];

            if ($event === 'CHECKOUT.ORDER.COMPLETED') {
                return $this->verifyPayment($resource['id']);
            }

            return [
                'status' => 'ignored',
                'message' => 'Event not handled: ' . $event
            ];

        } catch (\Exception $e) {
            Log::error('PayPal webhook error: ' . $e->getMessage());
            
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
        return ['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'JPY', 'CHF', 'SEK', 'NOK', 'DKK', 'PLN', 'CZK', 'HUF'];
    }

    /**
     * Get gateway configuration for frontend
     */
    public function getClientConfig(): array
    {
        return [
            'client_id' => $this->clientId,
            'currency' => config('app.currency', 'USD'),
            'supported_currencies' => $this->getSupportedCurrencies(),
            'mode' => config('services.paypal.mode', 'sandbox')
        ];
    }

    /**
     * Create payment plan for subscriptions (future use)
     */
    public function createPlan(string $name, int $amount, string $interval = 'MONTH'): array
    {
        try {
            // Create product first
            $productResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/v1/catalogs/products', [
                'name' => $name,
                'type' => 'SERVICE'
            ]);

            if (!$productResponse->successful()) {
                throw new \Exception('Product creation failed');
            }

            $product = $productResponse->json();

            // Create plan
            $planResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/v1/billing/plans', [
                'product_id' => $product['id'],
                'name' => $name,
                'billing_cycles' => [
                    [
                        'frequency' => [
                            'interval_unit' => $interval,
                            'interval_count' => 1
                        ],
                        'tenure_type' => 'REGULAR',
                        'sequence' => 1,
                        'total_cycles' => 0,
                        'pricing_scheme' => [
                            'fixed_price' => [
                                'value' => $amount,
                                'currency_code' => config('app.currency', 'USD')
                            ]
                        ]
                    ]
                ],
                'payment_preferences' => [
                    'auto_bill_outstanding' => true,
                    'setup_fee_failure_action' => 'CONTINUE'
                ]
            ]);

            if ($planResponse->successful()) {
                return [
                    'success' => true,
                    'plan' => $planResponse->json()
                ];
            }

            throw new \Exception('Plan creation failed');

        } catch (\Exception $e) {
            Log::error('PayPal plan creation error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Plan creation failed',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get transaction history from PayPal
     */
    public function getTransactions(int $pageSize = 50, int $page = 1): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->get($this->baseUrl . '/v1/reporting/transactions', [
                'page_size' => $pageSize,
                'page' => $page,
                'start_date' => now()->subDays(30)->toISOString(),
                'end_date' => now()->toISOString()
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'transactions' => $response->json()['transaction_details']
                ];
            }

            throw new \Exception('Failed to fetch transactions');

        } catch (\Exception $e) {
            Log::error('PayPal transactions error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to fetch transactions',
                'error' => $e->getMessage()
            ];
        }
    }
}
