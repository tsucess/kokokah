<?php

namespace Tests\Feature\Endpoints;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletPaymentEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('api-token')->plainTextToken;
        
        // Give user wallet balance
        $this->user->wallet->update(['balance' => 5000.00]);
    }

    /**
     * Test get wallet endpoint
     */
    public function test_get_wallet()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->getJson('/api/wallet');

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'data']);
    }

    /**
     * Test wallet transfer endpoint
     */
    public function test_wallet_transfer()
    {
        $recipient = User::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->postJson('/api/wallet/transfer', [
                            'recipient_id' => $recipient->id,
                            'amount' => 100.00
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test wallet transactions endpoint
     */
    public function test_wallet_transactions()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->getJson('/api/wallet/transactions');

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'data']);
    }

    /**
     * Test wallet rewards endpoint
     */
    public function test_wallet_rewards()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->getJson('/api/wallet/rewards');

        $response->assertStatus(200);
    }

    /**
     * Test claim login reward endpoint
     */
    public function test_claim_login_reward()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->postJson('/api/wallet/claim-login-reward');

        $response->assertStatus(200);
    }

    /**
     * Test check affordability endpoint
     */
    public function test_check_affordability()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->postJson('/api/wallet/check-affordability', [
                            'amount' => 100.00
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test get payment gateways endpoint
     */
    public function test_get_payment_gateways()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->getJson('/api/payments/gateways');

        $response->assertStatus(200);
    }

    /**
     * Test payment history endpoint
     */
    public function test_payment_history()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->getJson('/api/payments/history');

        $response->assertStatus(200);
    }

    /**
     * Test get single payment endpoint
     */
    public function test_get_single_payment()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->getJson('/api/payments/1');

        // Will return 404 if payment doesn't exist, but endpoint should exist
        $response->assertStatus(404);
    }

    /**
     * Test payment webhook endpoint (public)
     */
    public function test_payment_webhook()
    {
        $response = $this->postJson('/api/payments/webhook/paystack', [
            'reference' => 'test-ref',
            'status' => 'success'
        ]);

        // Should handle webhook
        $response->assertStatus(200);
    }

    /**
     * Test payment callback endpoint (public)
     */
    public function test_payment_callback()
    {
        $response = $this->getJson('/api/payments/callback/paystack?reference=test-ref');

        $response->assertStatus(200);
    }

    /**
     * Test payment success endpoint (public)
     */
    public function test_payment_success()
    {
        $response = $this->getJson('/api/payments/success/paystack?reference=test-ref');

        $response->assertStatus(200);
    }

    /**
     * Test payment cancel endpoint (public)
     */
    public function test_payment_cancel()
    {
        $response = $this->getJson('/api/payments/cancel/paystack?reference=test-ref');

        $response->assertStatus(200);
    }

    /**
     * Test wallet without authentication
     */
    public function test_wallet_without_auth()
    {
        $response = $this->getJson('/api/wallet');

        $response->assertStatus(401);
    }
}

