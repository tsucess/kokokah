<?php

namespace Tests\Feature\Endpoints;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationFileChatEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $admin;
    protected $userToken;
    protected $adminToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'student']);
        $this->admin = User::factory()->create(['role' => 'admin']);

        $this->userToken = $this->user->createToken('api-token')->plainTextToken;
        $this->adminToken = $this->admin->createToken('api-token')->plainTextToken;
    }

    /**
     * Test get notifications endpoint
     */
    public function test_get_notifications()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->getJson('/api/notifications');

        $response->assertStatus(200);
    }

    /**
     * Test mark notification as read endpoint
     */
    public function test_mark_notification_as_read()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->putJson('/api/notifications/1/read');

        $response->assertStatus(404);
    }

    /**
     * Test mark all notifications as read endpoint
     */
    public function test_mark_all_notifications_as_read()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->putJson('/api/notifications/read-all');

        $response->assertStatus(200);
    }

    /**
     * Test delete notification endpoint
     */
    public function test_delete_notification()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->deleteJson('/api/notifications/1');

        $response->assertStatus(404);
    }

    /**
     * Test get notification preferences endpoint
     */
    public function test_get_notification_preferences()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->getJson('/api/notifications/preferences');

        $response->assertStatus(200);
    }

    /**
     * Test update notification preferences endpoint
     */
    public function test_update_notification_preferences()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->putJson('/api/notifications/preferences', [
                            'email_notifications' => true,
                            'push_notifications' => false
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test send notification endpoint
     */
    public function test_send_notification()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->postJson('/api/notifications/send', [
                            'user_id' => $this->user->id,
                            'title' => 'Test',
                            'message' => 'Test message'
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test broadcast notification endpoint
     */
    public function test_broadcast_notification()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->postJson('/api/notifications/broadcast', [
                            'title' => 'Test',
                            'message' => 'Test message'
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test notification analytics endpoint
     */
    public function test_notification_analytics()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->getJson('/api/notifications/analytics');

        $response->assertStatus(200);
    }

    /**
     * Test file upload endpoint
     */
    public function test_file_upload()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->postJson('/api/files/upload', [
                            'file' => 'test-file.txt'
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test file download endpoint
     */
    public function test_file_download()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->getJson('/api/files/download/1');

        $response->assertStatus(404);
    }

    /**
     * Test file delete endpoint
     */
    public function test_file_delete()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->deleteJson('/api/files/1');

        $response->assertStatus(404);
    }

    /**
     * Test list files endpoint
     */
    public function test_list_files()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->getJson('/api/files/list');

        $response->assertStatus(200);
    }

    /**
     * Test file preview endpoint
     */
    public function test_file_preview()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->getJson('/api/files/preview/1');

        $response->assertStatus(404);
    }

    /**
     * Test file share endpoint
     */
    public function test_file_share()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->postJson('/api/files/1/share', [
                            'user_ids' => [1, 2, 3]
                        ]);

        $response->assertStatus(404);
    }

    /**
     * Test file storage stats endpoint
     */
    public function test_file_storage_stats()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->getJson('/api/files/storage/stats');

        $response->assertStatus(200);
    }

    /**
     * Test start chat session endpoint
     */
    public function test_start_chat_session()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->postJson('/api/chat/start', [
                            'topic' => 'Course Help'
                        ]);

        $response->assertStatus(200);
    }

    /**
     * Test send chat message endpoint
     */
    public function test_send_chat_message()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->postJson('/api/chat/sessions/1/message', [
                            'message' => 'Hello'
                        ]);

        $response->assertStatus(404);
    }

    /**
     * Test get chat session history endpoint
     */
    public function test_get_chat_session_history()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->getJson('/api/chat/sessions/1');

        $response->assertStatus(404);
    }

    /**
     * Test get user chat sessions endpoint
     */
    public function test_get_user_chat_sessions()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->getJson('/api/chat/sessions');

        $response->assertStatus(200);
    }

    /**
     * Test end chat session endpoint
     */
    public function test_end_chat_session()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->postJson('/api/chat/sessions/1/end');

        $response->assertStatus(404);
    }

    /**
     * Test rate chat session endpoint
     */
    public function test_rate_chat_session()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->userToken")
                        ->postJson('/api/chat/sessions/1/rate', [
                            'rating' => 5
                        ]);

        $response->assertStatus(404);
    }

    /**
     * Test chat analytics endpoint
     */
    public function test_chat_analytics()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
                        ->getJson('/api/chat/analytics');

        $response->assertStatus(200);
    }
}

