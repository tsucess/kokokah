<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $admin;
    protected $subscriptionPlan;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->user = User::factory()->create(['role' => 'student']);
        $this->admin = User::factory()->create(['role' => 'superadmin']);

        // Create test subscription plan
        $this->subscriptionPlan = SubscriptionPlan::create([
            'title' => 'Test Plan',
            'description' => 'Test Description',
            'price' => 5000,
            'duration' => 30,
            'duration_type' => 'monthly',
            'features' => ['Feature 1', 'Feature 2'],
            'is_active' => true
        ]);
    }

    /**
     * Test getting all subscription plans
     */
    public function test_get_all_subscription_plans()
    {
        $response = $this->getJson('/api/subscriptions/plans');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'data' => [
                             '*' => [
                                 'id',
                                 'title',
                                 'price',
                                 'duration_type'
                             ]
                         ]
                     ]
                 ]);
    }

    /**
     * Test getting a specific subscription plan
     */
    public function test_get_specific_subscription_plan()
    {
        $response = $this->getJson("/api/subscriptions/plans/{$this->subscriptionPlan->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('data.id', $this->subscriptionPlan->id)
                 ->assertJsonPath('data.title', 'Test Plan');
    }

    /**
     * Test user can subscribe to a plan
     */
    public function test_user_can_subscribe_to_plan()
    {
        $response = $this->actingAs($this->user)
                         ->postJson('/api/subscriptions/subscribe', [
                             'subscription_plan_id' => $this->subscriptionPlan->id,
                             'amount_paid' => 5000,
                             'payment_reference' => 'PAY-123456'
                         ]);

        $response->assertStatus(201)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('data.status', 'active');

        $this->assertDatabaseHas('user_subscriptions', [
            'user_id' => $this->user->id,
            'subscription_plan_id' => $this->subscriptionPlan->id,
            'status' => 'active'
        ]);
    }

    /**
     * Test user can get their subscriptions
     */
    public function test_user_can_get_their_subscriptions()
    {
        UserSubscription::create([
            'user_id' => $this->user->id,
            'subscription_plan_id' => $this->subscriptionPlan->id,
            'started_at' => now(),
            'expires_at' => now()->addMonth(),
            'status' => 'active',
            'amount_paid' => 5000
        ]);

        $response = $this->actingAs($this->user)
                         ->getJson('/api/subscriptions/my-subscriptions');

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('data.data.0.status', 'active');
    }

    /**
     * Test user can cancel subscription
     */
    public function test_user_can_cancel_subscription()
    {
        $subscription = UserSubscription::create([
            'user_id' => $this->user->id,
            'subscription_plan_id' => $this->subscriptionPlan->id,
            'started_at' => now(),
            'expires_at' => now()->addMonth(),
            'status' => 'active',
            'amount_paid' => 5000
        ]);

        $response = $this->actingAs($this->user)
                         ->postJson("/api/subscriptions/{$subscription->id}/cancel");

        $response->assertStatus(200)
                 ->assertJsonPath('data.status', 'cancelled');
    }

    /**
     * Test admin can create subscription plan
     */
    public function test_admin_can_create_subscription_plan()
    {
        $response = $this->actingAs($this->admin)
                         ->postJson('/api/subscriptions/plans', [
                             'title' => 'Premium Plan',
                             'description' => 'Premium Description',
                             'price' => 10000,
                             'duration' => 365,
                             'duration_type' => 'yearly',
                             'features' => ['Premium Feature 1', 'Premium Feature 2'],
                             'is_active' => true
                         ]);

        if ($response->status() !== 201) {
            \Log::error('Create plan failed', ['response' => $response->json()]);
        }

        $response->assertStatus(201)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('data.title', 'Premium Plan');
    }

    /**
     * Test admin can update subscription plan
     */
    public function test_admin_can_update_subscription_plan()
    {
        $response = $this->actingAs($this->admin)
                         ->putJson("/api/subscriptions/plans/{$this->subscriptionPlan->id}", [
                             'price' => 6000,
                             'title' => 'Updated Test Plan'
                         ]);

        $response->assertStatus(200)
                 ->assertJsonPath('data.title', 'Updated Test Plan')
                 ->assertJsonPath('success', true);
    }

    /**
     * Test admin can delete subscription plan
     */
    public function test_admin_can_delete_subscription_plan()
    {
        $response = $this->actingAs($this->admin)
                         ->deleteJson("/api/subscriptions/plans/{$this->subscriptionPlan->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('success', true);
    }
}

