<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class WalletTransferRoleTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected User $instructor;
    protected User $admin;
    protected User $superadmin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create users with different roles
        $this->student = User::factory()->create(['role' => 'student', 'is_active' => true]);
        $this->instructor = User::factory()->create(['role' => 'instructor', 'is_active' => true]);
        $this->admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $this->superadmin = User::factory()->create(['role' => 'superadmin', 'is_active' => true]);

        // Give them wallet balances
        $this->student->getOrCreateWallet()->update(['balance' => 1000]);
        $this->instructor->getOrCreateWallet()->update(['balance' => 1000]);
        $this->admin->getOrCreateWallet()->update(['balance' => 1000]);
        $this->superadmin->getOrCreateWallet()->update(['balance' => 1000]);
    }

    /**
     * Test student can transfer to another student
     */
    public function test_student_can_transfer_to_student(): void
    {
        $recipient = User::factory()->create(['role' => 'student', 'is_active' => true]);
        $recipient->getOrCreateWallet()->update(['balance' => 0]);

        $response = $this->actingAs($this->student)
            ->postJson('/api/wallet/transfer', [
                'recipient_email' => $recipient->email,
                'amount' => 100,
                'description' => 'Test transfer'
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * Test student can transfer to instructor
     */
    public function test_student_can_transfer_to_instructor(): void
    {
        $response = $this->actingAs($this->student)
            ->postJson('/api/wallet/transfer', [
                'recipient_email' => $this->instructor->email,
                'amount' => 100,
                'description' => 'Test transfer'
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * Test instructor can transfer to student
     */
    public function test_instructor_can_transfer_to_student(): void
    {
        $response = $this->actingAs($this->instructor)
            ->postJson('/api/wallet/transfer', [
                'recipient_email' => $this->student->email,
                'amount' => 100,
                'description' => 'Test transfer'
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * Test instructor can transfer to another instructor
     */
    public function test_instructor_can_transfer_to_instructor(): void
    {
        $recipient = User::factory()->create(['role' => 'instructor', 'is_active' => true]);
        $recipient->getOrCreateWallet()->update(['balance' => 0]);

        $response = $this->actingAs($this->instructor)
            ->postJson('/api/wallet/transfer', [
                'recipient_email' => $recipient->email,
                'amount' => 100,
                'description' => 'Test transfer'
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * Test admin cannot transfer money
     */
    public function test_admin_cannot_transfer_money(): void
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/api/wallet/transfer', [
                'recipient_email' => $this->student->email,
                'amount' => 100,
                'description' => 'Test transfer'
            ]);

        $response->assertStatus(400)
            ->assertJson(['success' => false])
            ->assertJsonPath('errors.0', 'Only students and instructors can transfer money');
    }

    /**
     * Test superadmin cannot transfer money
     */
    public function test_superadmin_cannot_transfer_money(): void
    {
        $response = $this->actingAs($this->superadmin)
            ->postJson('/api/wallet/transfer', [
                'recipient_email' => $this->student->email,
                'amount' => 100,
                'description' => 'Test transfer'
            ]);

        $response->assertStatus(400)
            ->assertJson(['success' => false])
            ->assertJsonPath('errors.0', 'Only students and instructors can transfer money');
    }

    /**
     * Test student cannot transfer to admin
     */
    public function test_student_cannot_transfer_to_admin(): void
    {
        $response = $this->actingAs($this->student)
            ->postJson('/api/wallet/transfer', [
                'recipient_email' => $this->admin->email,
                'amount' => 100,
                'description' => 'Test transfer'
            ]);

        $response->assertStatus(400)
            ->assertJson(['success' => false])
            ->assertJsonPath('errors.0', 'Money can only be transferred to students or instructors');
    }

    /**
     * Test student cannot transfer to superadmin
     */
    public function test_student_cannot_transfer_to_superadmin(): void
    {
        $response = $this->actingAs($this->student)
            ->postJson('/api/wallet/transfer', [
                'recipient_email' => $this->superadmin->email,
                'amount' => 100,
                'description' => 'Test transfer'
            ]);

        $response->assertStatus(400)
            ->assertJson(['success' => false])
            ->assertJsonPath('errors.0', 'Money can only be transferred to students or instructors');
    }

    /**
     * Test validateRecipient endpoint rejects admin recipient
     */
    public function test_validate_recipient_rejects_admin(): void
    {
        $response = $this->actingAs($this->student)
            ->postJson('/api/wallet/validate-recipient', [
                'email' => $this->admin->email
            ]);

        $response->assertStatus(400)
            ->assertJson(['success' => false])
            ->assertJsonPath('message', 'Money can only be transferred to students or instructors');
    }

    /**
     * Test validateRecipient endpoint rejects superadmin recipient
     */
    public function test_validate_recipient_rejects_superadmin(): void
    {
        $response = $this->actingAs($this->student)
            ->postJson('/api/wallet/validate-recipient', [
                'email' => $this->superadmin->email
            ]);

        $response->assertStatus(400)
            ->assertJson(['success' => false])
            ->assertJsonPath('message', 'Money can only be transferred to students or instructors');
    }

    /**
     * Test validateRecipient endpoint accepts student recipient
     */
    public function test_validate_recipient_accepts_student(): void
    {
        $response = $this->actingAs($this->student)
            ->postJson('/api/wallet/validate-recipient', [
                'email' => $this->instructor->email
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}

