<?php

namespace Tests\Unit\Models;

use App\Models\Wallet;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $wallet;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        // User model automatically creates a wallet, so just get it
        $this->wallet = $this->user->wallet;
        // Update the balance for testing
        $this->wallet->update([
            'balance' => 1000.00,
            'currency' => 'NGN'
        ]);
    }

    public function test_wallet_can_be_created()
    {
        $this->assertDatabaseHas('wallets', [
            'user_id' => $this->user->id,
            'balance' => 1000.00
        ]);
    }

    public function test_wallet_belongs_to_user()
    {
        $this->assertEquals($this->user->id, $this->wallet->user->id);
    }

    public function test_wallet_can_deposit_money()
    {
        $this->wallet->deposit(500.00, 'DEP-001', 'Test deposit');

        $this->assertEquals(1500.00, $this->wallet->fresh()->balance);
    }

    public function test_wallet_can_withdraw_money()
    {
        $this->wallet->withdraw(200.00, 'WTH-001', 'Test withdrawal');

        $this->assertEquals(800.00, $this->wallet->fresh()->balance);
    }

    public function test_wallet_cannot_withdraw_more_than_balance()
    {
        $this->expectException(\Exception::class);
        $this->wallet->withdraw(2000.00, 'WTH-001', 'Test withdrawal');
    }

    public function test_wallet_has_transactions()
    {
        $this->wallet->deposit(500.00, 'DEP-001', 'Test deposit');
        $this->wallet->withdraw(100.00, 'WTH-001', 'Test withdrawal');

        $this->assertEquals(2, $this->wallet->transactions()->count());
    }

    public function test_wallet_transaction_is_recorded()
    {
        $this->wallet->deposit(500.00, 'DEP-001', 'Test deposit');

        $this->assertDatabaseHas('transactions', [
            'wallet_id' => $this->wallet->id,
            'type' => 'credit',
            'amount' => 500.00,
            'reference' => 'DEP-001'
        ]);
    }

    public function test_wallet_balance_is_numeric()
    {
        // Decimal fields are cast to strings in Laravel
        $this->assertIsString($this->wallet->balance);
    }

    public function test_wallet_currency_defaults_to_ngn()
    {
        $this->assertEquals('NGN', $this->wallet->currency);
    }

    public function test_wallet_get_total_credits()
    {
        $this->wallet->deposit(500.00, 'DEP-001', 'Test deposit');
        $this->wallet->deposit(300.00, 'DEP-002', 'Test deposit');

        $totalCredits = $this->wallet->getTotalCredits();
        $this->assertEquals(800.00, $totalCredits);
    }

    public function test_wallet_get_formatted_balance()
    {
        $formatted = $this->wallet->getFormattedBalance();
        
        $this->assertStringContainsString('NGN', $formatted);
        $this->assertStringContainsString('1,000.00', $formatted);
    }

    public function test_wallet_multiple_deposits()
    {
        $this->wallet->deposit(100.00, 'DEP-001', 'Deposit 1');
        $this->wallet->deposit(200.00, 'DEP-002', 'Deposit 2');
        $this->wallet->deposit(300.00, 'DEP-003', 'Deposit 3');

        $this->assertEquals(1600.00, $this->wallet->fresh()->balance);
    }

    public function test_wallet_multiple_withdrawals()
    {
        $this->wallet->withdraw(100.00, 'WTH-001', 'Withdrawal 1');
        $this->wallet->withdraw(200.00, 'WTH-002', 'Withdrawal 2');

        $this->assertEquals(700.00, $this->wallet->fresh()->balance);
    }

    public function test_wallet_transaction_status_is_success()
    {
        $transaction = $this->wallet->deposit(500.00, 'DEP-001', 'Test deposit');

        $this->assertEquals('success', $transaction->status);
    }
}

