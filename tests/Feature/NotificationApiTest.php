<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Notification;
use App\Models\Announcement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Test fetching notifications endpoint
     */
    public function test_can_fetch_notifications()
    {
        // Create test notifications
        Notification::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'type' => 'system'
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/notifications');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'notifications',
                    'summary'
                ]
            ]);

        // Verify we got notifications
        $this->assertTrue($response->json('success'));
        $this->assertIsArray($response->json('data.notifications.data'));
    }

    /**
     * Test fetching announcements endpoint
     */
    public function test_can_fetch_announcements()
    {
        // Create test announcements
        Announcement::factory()->count(3)->create([
            'status' => 'published'
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/announcements');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data'
            ]);

        // Verify we got announcements
        $this->assertEquals(200, $response->json('status'));
        $this->assertIsArray($response->json('data.data'));
    }

    /**
     * Test filtering notifications by type
     */
    public function test_can_filter_notifications_by_type()
    {
        Notification::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'system'
        ]);

        Notification::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'course'
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/notifications?type=system');

        $response->assertStatus(200);
        $notifications = $response->json('data.notifications.data');
        
        foreach ($notifications as $notification) {
            $this->assertEquals('system', $notification['type']);
        }
    }

    /**
     * Test marking notification as read
     */
    public function test_can_mark_notification_as_read()
    {
        $notification = Notification::factory()->create([
            'user_id' => $this->user->id,
            'read_at' => null
        ]);

        $response = $this->actingAs($this->user)
            ->putJson("/api/notifications/{$notification->id}/read");

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'message']);

        $this->assertNotNull($notification->fresh()->read_at);
    }

    /**
     * Test marking all notifications as read
     */
    public function test_can_mark_all_notifications_as_read()
    {
        Notification::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'read_at' => null
        ]);

        $response = $this->actingAs($this->user)
            ->putJson('/api/notifications/read-all');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'message']);

        $unreadCount = Notification::where('user_id', $this->user->id)
            ->whereNull('read_at')
            ->count();

        $this->assertEquals(0, $unreadCount);
    }
}

