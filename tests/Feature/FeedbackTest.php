<?php

namespace Tests\Feature;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedbackTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test submitting feedback without authentication
     */
    public function test_can_submit_feedback_without_auth()
    {
        $response = $this->postJson('/api/feedback/submit', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'feedback_type' => 'bug',
            'rating' => 4,
            'subject' => 'Test Subject',
            'message' => 'This is a test feedback message with sufficient length.',
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true])
                 ->assertJsonPath('message', 'Thank you for your feedback! We appreciate your input.');

        $this->assertDatabaseHas('feedback', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'feedback_type' => 'bug',
            'rating' => 4,
        ]);
    }

    /**
     * Test feedback validation
     */
    public function test_feedback_validation_fails_with_invalid_data()
    {
        $response = $this->postJson('/api/feedback/submit', [
            'first_name' => '',
            'last_name' => '',
            'feedback_type' => 'invalid_type',
            'message' => 'short',
        ]);

        $response->assertStatus(422)
                 ->assertJson(['success' => false]);
    }

    /**
     * Test getting user feedback history
     */
    public function test_authenticated_user_can_get_feedback_history()
    {
        $user = User::factory()->create();
        
        Feedback::create([
            'user_id' => $user->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'feedback_type' => 'general',
            'message' => 'Test feedback message',
        ]);

        $response = $this->actingAs($user)
                         ->getJson('/api/feedback/my-feedback');

        $response->assertStatus(200)
                 ->assertJson(['success' => true])
                 ->assertJsonCount(1, 'data');
    }

    /**
     * Test admin can view all feedback
     */
    public function test_admin_can_view_all_feedback()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        Feedback::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'feedback_type' => 'bug',
            'message' => 'Test feedback',
        ]);

        $response = $this->actingAs($admin)
                         ->getJson('/api/feedback');

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);
    }

    /**
     * Test non-admin cannot view all feedback
     */
    public function test_non_admin_cannot_view_all_feedback()
    {
        $user = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($user)
                         ->getJson('/api/feedback');

        $response->assertStatus(403)
                 ->assertJson(['success' => false]);
    }

    /**
     * Test feedback with all optional fields
     */
    public function test_feedback_with_all_fields()
    {
        $response = $this->postJson('/api/feedback/submit', [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'feedback_type' => 'feature_request',
            'rating' => 5,
            'subject' => 'Amazing Feature Idea',
            'message' => 'I would love to see this feature implemented in the platform.',
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('feedback', [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'feedback_type' => 'feature_request',
            'rating' => 5,
            'subject' => 'Amazing Feature Idea',
        ]);
    }
}

