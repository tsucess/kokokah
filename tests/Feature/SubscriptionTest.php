<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Models\Wallet;
use App\Models\Course;
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
        // Set wallet balance to sufficient amount
        $this->user->wallet->update(['balance' => 10000]);

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

    /**
     * Test user cannot subscribe with insufficient wallet balance
     */
    public function test_user_cannot_subscribe_with_insufficient_wallet_balance()
    {
        // Set wallet balance to insufficient amount
        $this->user->wallet->update(['balance' => 1000]); // Less than subscription price of 5000

        $response = $this->actingAs($this->user)
                         ->postJson('/api/subscriptions/subscribe', [
                             'subscription_plan_id' => $this->subscriptionPlan->id,
                             'amount_paid' => 5000,
                             'payment_reference' => 'PAY-123456'
                         ]);

        $response->assertStatus(400)
                 ->assertJsonPath('success', false)
                 ->assertJsonPath('message', 'Insufficient wallet balance for this subscription');
    }

    /**
     * Test user can subscribe with sufficient wallet balance
     */
    public function test_user_can_subscribe_with_sufficient_wallet_balance()
    {
        // Set wallet balance to sufficient amount
        $this->user->wallet->update(['balance' => 10000]); // More than subscription price of 5000

        $response = $this->actingAs($this->user)
                         ->postJson('/api/subscriptions/subscribe', [
                             'subscription_plan_id' => $this->subscriptionPlan->id,
                             'amount_paid' => 5000,
                             'payment_reference' => 'PAY-123456'
                         ]);

        $response->assertStatus(201)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('data.status', 'active');
    }

    /**
     * Test user cannot subscribe with zero amount
     */
    public function test_user_cannot_subscribe_with_zero_amount()
    {
        $response = $this->actingAs($this->user)
                         ->postJson('/api/subscriptions/subscribe', [
                             'subscription_plan_id' => $this->subscriptionPlan->id,
                             'amount_paid' => 0,
                             'payment_reference' => 'PAY-123456'
                         ]);

        $response->assertStatus(422)
                 ->assertJsonPath('success', false);
    }

    /**
     * Test user can have multiple active subscriptions to different plans
     */
    public function test_user_can_have_multiple_active_subscriptions()
    {
        // Create second subscription plan
        $secondPlan = SubscriptionPlan::create([
            'title' => 'Premium Plan',
            'description' => 'Premium Description',
            'price' => 10000,
            'duration' => 30,
            'duration_type' => 'monthly',
            'features' => ['Premium Feature 1'],
            'is_active' => true
        ]);

        // Set wallet balance to sufficient amount
        $this->user->wallet->update(['balance' => 20000]);

        // Subscribe to first plan
        $response1 = $this->actingAs($this->user)
                          ->postJson('/api/subscriptions/subscribe', [
                              'subscription_plan_id' => $this->subscriptionPlan->id,
                              'amount_paid' => 5000,
                              'payment_reference' => 'PAY-123456'
                          ]);

        $response1->assertStatus(201);

        // Subscribe to second plan
        $response2 = $this->actingAs($this->user)
                          ->postJson('/api/subscriptions/subscribe', [
                              'subscription_plan_id' => $secondPlan->id,
                              'amount_paid' => 10000,
                              'payment_reference' => 'PAY-789012'
                          ]);

        $response2->assertStatus(201);

        // Verify both subscriptions exist
        $this->assertDatabaseHas('user_subscriptions', [
            'user_id' => $this->user->id,
            'subscription_plan_id' => $this->subscriptionPlan->id,
            'status' => 'active'
        ]);

        $this->assertDatabaseHas('user_subscriptions', [
            'user_id' => $this->user->id,
            'subscription_plan_id' => $secondPlan->id,
            'status' => 'active'
        ]);
    }

    /**
     * Test user can subscribe to same plan for different courses
     */
    public function test_user_can_subscribe_to_same_plan_for_different_courses()
    {
        // Create two courses
        $course1 = Course::create([
            'title' => 'Test Course 1',
            'slug' => 'test-course-1',
            'description' => 'Test Description 1',
            'status' => 'published',
            'instructor_id' => $this->user->id
        ]);

        $course2 = Course::create([
            'title' => 'Test Course 2',
            'slug' => 'test-course-2',
            'description' => 'Test Description 2',
            'status' => 'published',
            'instructor_id' => $this->user->id
        ]);

        // Attach both courses to subscription plan
        $this->subscriptionPlan->courses()->attach([$course1->id, $course2->id]);

        // Set wallet balance to sufficient amount
        $this->user->wallet->update(['balance' => 20000]);

        // Subscribe to plan with first course
        $response1 = $this->actingAs($this->user)
                          ->postJson('/api/subscriptions/subscribe', [
                              'subscription_plan_id' => $this->subscriptionPlan->id,
                              'amount_paid' => 5000,
                              'payment_reference' => 'PAY-123456',
                              'course_ids' => [$course1->id]
                          ]);

        $response1->assertStatus(201);

        // Subscribe to same plan with different course
        $response2 = $this->actingAs($this->user)
                          ->postJson('/api/subscriptions/subscribe', [
                              'subscription_plan_id' => $this->subscriptionPlan->id,
                              'amount_paid' => 5000,
                              'payment_reference' => 'PAY-789012',
                              'course_ids' => [$course2->id]
                          ]);

        $response2->assertStatus(201);

        // Verify both subscriptions exist
        $this->assertDatabaseHas('user_subscriptions', [
            'user_id' => $this->user->id,
            'subscription_plan_id' => $this->subscriptionPlan->id,
            'status' => 'active'
        ]);

        // Verify both enrollments exist
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $this->user->id,
            'course_id' => $course1->id,
            'status' => 'active'
        ]);

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $this->user->id,
            'course_id' => $course2->id,
            'status' => 'active'
        ]);
    }

    /**
     * Test user cannot subscribe to same plan on same course
     */
    public function test_user_cannot_subscribe_to_same_plan_on_same_course()
    {
        // Create a course manually
        $course = Course::create([
            'title' => 'Test Course',
            'slug' => 'test-course',
            'description' => 'Test Description',
            'status' => 'published',
            'instructor_id' => $this->user->id
        ]);

        // Attach course to subscription plan
        $this->subscriptionPlan->courses()->attach($course->id);

        // Set wallet balance to sufficient amount
        $this->user->wallet->update(['balance' => 20000]);

        // First subscription with course enrollment
        $response1 = $this->actingAs($this->user)
                          ->postJson('/api/subscriptions/subscribe', [
                              'subscription_plan_id' => $this->subscriptionPlan->id,
                              'amount_paid' => 5000,
                              'payment_reference' => 'PAY-123456',
                              'course_ids' => [$course->id]
                          ]);

        $response1->assertStatus(201);

        // Verify enrollment was created
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $this->user->id,
            'course_id' => $course->id,
            'status' => 'active'
        ]);

        // Cancel the first subscription to allow another subscription attempt
        $subscription = UserSubscription::where('user_id', $this->user->id)
                                       ->where('subscription_plan_id', $this->subscriptionPlan->id)
                                       ->first();
        $subscription->update(['status' => 'cancelled']);

        // Try to subscribe to same plan with same course
        $response2 = $this->actingAs($this->user)
                          ->postJson('/api/subscriptions/subscribe', [
                              'subscription_plan_id' => $this->subscriptionPlan->id,
                              'amount_paid' => 5000,
                              'payment_reference' => 'PAY-789012',
                              'course_ids' => [$course->id]
                          ]);

        $response2->assertStatus(400)
                  ->assertJsonPath('success', false)
                  ->assertJsonPath('message', 'User is already enrolled in some of the selected courses')
                  ->assertJsonPath('data.duplicate_course_ids.0', $course->id);
    }
}

