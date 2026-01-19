<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Wallet;
use App\Models\PointsConversion;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WalletConversionTest extends TestCase
{
    use RefreshDatabase;

    protected WalletService $walletService;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->walletService = app(WalletService::class);
        $this->user = User::factory()->create(['points' => 100]);
    }

    /**
     * Test successful points conversion
     */
    public function test_convert_points_successfully(): void
    {
        $result = $this->walletService->convertPointsToWallet($this->user, 100);

        $this->assertTrue($result['success']);
        $this->assertEquals('Successfully converted 100 points to ₦10', $result['message']);
        $this->assertEquals(100, $result['data']['points_converted']);
        $this->assertEquals(10, $result['data']['wallet_amount']);
        $this->assertEquals(0, $result['data']['remaining_points']);
    }

    /**
     * Test partial points conversion
     */
    public function test_convert_partial_points(): void
    {
        $this->user->update(['points' => 150]);

        $result = $this->walletService->convertPointsToWallet($this->user, 100);

        $this->assertTrue($result['success']);
        $this->assertEquals(100, $result['data']['points_converted']);
        $this->assertEquals(10, $result['data']['wallet_amount']);
        $this->assertEquals(50, $result['data']['remaining_points']);
    }

    /**
     * Test conversion with insufficient points
     */
    public function test_convert_with_insufficient_points(): void
    {
        $this->user->update(['points' => 50]);

        $result = $this->walletService->convertPointsToWallet($this->user, 100);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Insufficient points', $result['message']);
    }

    /**
     * Test conversion with points less than minimum
     */
    public function test_convert_with_points_less_than_minimum(): void
    {
        $result = $this->walletService->convertPointsToWallet($this->user, 5);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Minimum 10 points required', $result['message']);
    }

    /**
     * Test conversion with non-multiple of 10
     */
    public function test_convert_with_non_multiple_of_10(): void
    {
        $result = $this->walletService->convertPointsToWallet($this->user, 15);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('multiple of 10', $result['message']);
    }

    /**
     * Test conversion with zero points
     */
    public function test_convert_with_zero_points(): void
    {
        $result = $this->walletService->convertPointsToWallet($this->user, 0);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('greater than zero', $result['message']);
    }

    /**
     * Test conversion with negative points
     */
    public function test_convert_with_negative_points(): void
    {
        $result = $this->walletService->convertPointsToWallet($this->user, -10);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('greater than zero', $result['message']);
    }

    /**
     * Test wallet balance is updated after conversion
     */
    public function test_wallet_balance_updated_after_conversion(): void
    {
        $wallet = $this->user->getOrCreateWallet();
        $initialBalance = $wallet->balance;

        $this->walletService->convertPointsToWallet($this->user, 100);

        $wallet->refresh();
        $this->assertEquals($initialBalance + 10, $wallet->balance);
    }

    /**
     * Test points are deducted after conversion
     */
    public function test_points_deducted_after_conversion(): void
    {
        $this->user->update(['points' => 150]);

        $this->walletService->convertPointsToWallet($this->user, 100);

        $this->user->refresh();
        $this->assertEquals(50, $this->user->points);
    }

    /**
     * Test conversion record is created
     */
    public function test_conversion_record_created(): void
    {
        $this->walletService->convertPointsToWallet($this->user, 100);

        $conversion = PointsConversion::where('user_id', $this->user->id)->first();

        $this->assertNotNull($conversion);
        $this->assertEquals(100, $conversion->points_converted);
        $this->assertEquals(10, $conversion->wallet_amount);
        $this->assertEquals(10, $conversion->conversion_ratio);
    }

    /**
     * Test get conversion history
     */
    public function test_get_conversion_history(): void
    {
        $this->walletService->convertPointsToWallet($this->user, 50);
        $this->walletService->convertPointsToWallet($this->user, 50);

        $history = $this->walletService->getConversionHistory($this->user);

        $this->assertEquals(2, $history->count());
    }

    /**
     * Test validate points conversion
     */
    public function test_validate_points_conversion(): void
    {
        $validation = $this->walletService->validatePointsConversion($this->user, 100);

        $this->assertTrue($validation['valid']);
        $this->assertEmpty($validation['errors']);
    }

    /**
     * Test validate with multiple errors
     */
    public function test_validate_with_multiple_errors(): void
    {
        $this->user->update(['points' => 5]);

        $validation = $this->walletService->validatePointsConversion($this->user, 15);

        $this->assertFalse($validation['valid']);
        $this->assertCount(2, $validation['errors']);
    }
}
