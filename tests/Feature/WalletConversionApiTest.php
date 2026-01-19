<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\PointsConversion;

class WalletConversionApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['points' => 100]);
    }

    /**
     * Test convert points endpoint with valid request
     */
    public function test_convert_points_endpoint_success(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/wallet/convert-points', [
                'points' => 100
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Successfully converted 100 points to ₦10'
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'points_converted',
                    'wallet_amount',
                    'remaining_points',
                    'new_wallet_balance',
                    'conversion_id',
                    'converted_at'
                ]
            ]);
    }

    /**
     * Test convert points endpoint without authentication
     */
    public function test_convert_points_endpoint_unauthenticated(): void
    {
        $response = $this->postJson('/api/wallet/convert-points', [
            'points' => 100
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test convert points endpoint with invalid points
     */
    public function test_convert_points_endpoint_invalid_points(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/wallet/convert-points', [
                'points' => 'invalid'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('points');
    }

    /**
     * Test convert points endpoint with insufficient points
     */
    public function test_convert_points_endpoint_insufficient_points(): void
    {
        $this->user->update(['points' => 50]);

        $response = $this->actingAs($this->user)
            ->postJson('/api/wallet/convert-points', [
                'points' => 100
            ]);

        $response->assertStatus(400)
            ->assertJson(['success' => false]);
    }

    /**
     * Test conversion history endpoint
     */
    public function test_conversion_history_endpoint(): void
    {
        // Create some conversions
        $this->actingAs($this->user)
            ->postJson('/api/wallet/convert-points', ['points' => 50]);

        $this->user->update(['points' => 100]);

        $this->actingAs($this->user)
            ->postJson('/api/wallet/convert-points', ['points' => 50]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/wallet/conversion-history');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'conversions',
                    'total_count'
                ]
            ]);

        $this->assertEquals(2, $response->json('data.total_count'));
    }

    /**
     * Test conversion history endpoint without authentication
     */
    public function test_conversion_history_endpoint_unauthenticated(): void
    {
        $response = $this->getJson('/api/wallet/conversion-history');

        $response->assertStatus(401);
    }

    /**
     * Test conversion with limit parameter
     */
    public function test_conversion_history_with_limit(): void
    {
        // Create multiple conversions
        for ($i = 0; $i < 5; $i++) {
            $this->user->update(['points' => 100]);
            $this->actingAs($this->user)
                ->postJson('/api/wallet/convert-points', ['points' => 10]);
        }

        $response = $this->actingAs($this->user)
            ->getJson('/api/wallet/conversion-history?limit=2');

        $response->assertStatus(200);
        $this->assertLessThanOrEqual(2, count($response->json('data.conversions')));
    }

    /**
     * Test conversion creates database record
     */
    public function test_conversion_creates_database_record(): void
    {
        $this->actingAs($this->user)
            ->postJson('/api/wallet/convert-points', [
                'points' => 100
            ]);

        $conversion = PointsConversion::where('user_id', $this->user->id)->first();

        $this->assertNotNull($conversion);
        $this->assertEquals(100, $conversion->points_converted);
        $this->assertEquals(10, $conversion->wallet_amount);
    }

    /**
     * Test conversion updates user points
     */
    public function test_conversion_updates_user_points(): void
    {
        $this->actingAs($this->user)
            ->postJson('/api/wallet/convert-points', [
                'points' => 100
            ]);

        $this->user->refresh();
        $this->assertEquals(0, $this->user->points);
    }

    /**
     * Test conversion updates wallet balance
     */
    public function test_conversion_updates_wallet_balance(): void
    {
        $wallet = $this->user->getOrCreateWallet();
        $initialBalance = $wallet->balance;

        $this->actingAs($this->user)
            ->postJson('/api/wallet/convert-points', [
                'points' => 100
            ]);

        $wallet->refresh();
        $this->assertEquals($initialBalance + 10, $wallet->balance);
    }

    /**
     * Test multiple conversions
     */
    public function test_multiple_conversions(): void
    {
        $this->user->update(['points' => 200]);

        // First conversion
        $response1 = $this->actingAs($this->user)
            ->postJson('/api/wallet/convert-points', ['points' => 100]);

        $response1->assertStatus(200);

        // Second conversion
        $response2 = $this->actingAs($this->user)
            ->postJson('/api/wallet/convert-points', ['points' => 100]);

        $response2->assertStatus(200);

        // Check history
        $history = PointsConversion::where('user_id', $this->user->id)->get();
        $this->assertEquals(2, $history->count());
    }
}
