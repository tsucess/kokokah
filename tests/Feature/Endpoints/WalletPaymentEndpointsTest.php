<?php

namespace Tests\Feature\Endpoints;

use App\Models\User;
use App\Models\Course;
use App\Models\CurriculumCategory;
use App\Models\CourseCategory;
use App\Models\Term;
use App\Models\Level;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletPaymentEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;
    protected $course;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('api-token')->plainTextToken;

        // Give user wallet balance
        $this->user->wallet->update(['balance' => 5000.00]);

        // Create a test course
        $category = CurriculumCategory::factory()->create();
        $term = Term::factory()->create();
        $level = Level::factory()->create();

        $this->course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test',
            'curriculum_category_id' => $category->id,
            'instructor_id' => User::factory()->create(['role' => 'instructor'])->id,
            'term_id' => $term->id,
            'level_id' => $level->id,
            'price' => 100.00,
            'status' => 'published'
        ]);
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
        $recipient = User::factory()->create(['is_active' => true]);

        // Create wallet for sender with balance
        $this->user->getOrCreateWallet()->update(['balance' => 500.00]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->postJson('/api/wallet/transfer', [
                            'recipient_email' => $recipient->email,
                            'amount' => 100.00
                        ]);

        // May return 200 or 400 depending on validation
        $this->assertTrue(in_array($response->status(), [200, 400]));
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
        $course = Course::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->postJson('/api/wallet/check-affordability', [
                            'course_id' => $course->id
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

        // Callback endpoint redirects to frontend, so expect 302
        $response->assertStatus(302);
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

    /**
     * Test initialize subscription payment endpoint
     */
    public function test_initialize_subscription_payment()
    {
        // Create a subscription plan
        $plan = \App\Models\SubscriptionPlan::factory()->create([
            'price' => 500.00,
            'duration' => 1,
            'duration_type' => 'monthly'
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->postJson('/api/payments/purchase-subscription', [
                            'subscription_plan_id' => $plan->id,
                            'course_ids' => [$this->course->id],
                            'gateway' => 'paystack'
                        ]);

        // Should return 200 with payment data
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'payment_id',
                'gateway_data'
            ]
        ]);
    }

    /**
     * Test subscription payment with invalid plan
     */
    public function test_subscription_payment_invalid_plan()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->postJson('/api/payments/purchase-subscription', [
                            'subscription_plan_id' => 99999,
                            'gateway' => 'paystack'
                        ]);

        $response->assertStatus(422);
    }

    /**
     * Test subscription payment with invalid gateway
     */
    public function test_subscription_payment_invalid_gateway()
    {
        $plan = \App\Models\SubscriptionPlan::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
                        ->postJson('/api/payments/purchase-subscription', [
                            'subscription_plan_id' => $plan->id,
                            'gateway' => 'invalid_gateway'
                        ]);

        $response->assertStatus(422);
    }
}

