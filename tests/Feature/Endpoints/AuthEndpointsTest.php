<?php

namespace Tests\Feature\Endpoints;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthEndpointsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration endpoint
     */
    public function test_register_endpoint()
    {
        $response = $this->postJson('/api/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    /**
     * Test user login endpoint
     */
    public function test_login_endpoint()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message', 'token', 'user']);
    }

    /**
     * Test get current user endpoint
     */
    public function test_get_user_endpoint()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
                        ->getJson('/api/user');

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'data']);
    }

    /**
     * Test logout endpoint
     */
    public function test_logout_endpoint()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
                        ->postJson('/api/logout');

        $response->assertStatus(200);
    }

    /**
     * Test forgot password endpoint
     */
    public function test_forgot_password_endpoint()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->postJson('/api/forgot-password', [
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test reset password endpoint
     */
    public function test_reset_password_endpoint()
    {
        $response = $this->postJson('/api/reset-password', [
            'email' => 'test@example.com',
            'token' => 'test-token',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        // Will fail without valid token, but endpoint should exist
        $response->assertStatus(422);
    }

    /**
     * Test login with invalid credentials
     */
    public function test_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test register with duplicate email
     */
    public function test_register_with_duplicate_email()
    {
        User::factory()->create(['email' => 'duplicate@example.com']);

        $response = $this->postJson('/api/register', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'duplicate@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student'
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test get user without authentication
     */
    public function test_get_user_without_auth()
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }
}

