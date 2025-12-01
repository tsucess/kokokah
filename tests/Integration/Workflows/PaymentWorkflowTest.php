<?php

namespace Tests\Integration\Workflows;

use App\Models\User;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Wallet;
use App\Models\Enrollment;
use App\Models\CurriculumCategory;
use App\Models\CourseCategory;
use App\Models\Term;
use App\Models\Level;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $student;
    protected $course;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->student = User::factory()->create(['role' => 'student']);
        $this->token = $this->student->createToken('api-token')->plainTextToken;

        // Update wallet balance (wallet is auto-created by User model)
        $this->student->wallet->update([
            'balance' => 5000.00,
            'currency' => 'NGN'
        ]);

        $instructor = User::factory()->create(['role' => 'instructor']);
        $category = Category::factory()->create();
        $term = Term::factory()->create();
        $level = Level::factory()->create();

        $this->course = Course::create([
            'title' => 'Premium Course',
            'description' => 'A premium course',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'term_id' => $term->id,
            'level_id' => $level->id,
            'price' => 1000.00,
            'status' => 'published'
        ]);
    }

    public function test_complete_payment_workflow()
    {
        // Note: Payment endpoints not fully tested yet
        $this->markTestSkipped('Payment workflow endpoints need implementation');

        // Step 1: Initialize payment
        $paymentResponse = $this->withHeader('Authorization', "Bearer $this->token")
                                ->postJson('/api/payments/initialize', [
                                    'course_id' => $this->course->id,
                                    'gateway' => 'paystack',
                                    'amount' => $this->course->price
                                ]);

        $paymentResponse->assertStatus(200);

        // Step 2: Verify payment record created
        $payment = Payment::where('user_id', $this->student->id)
                         ->where('course_id', $this->course->id)
                         ->first();
        $this->assertNotNull($payment);
        $this->assertEquals('pending', $payment->status);

        // Step 3: Simulate payment completion
        $payment->update(['status' => 'completed']);

        // Step 4: Verify enrollment created
        $enrollment = Enrollment::where('user_id', $this->student->id)
                               ->where('course_id', $this->course->id)
                               ->first();
        $this->assertNotNull($enrollment);
    }

    public function test_wallet_deposit_workflow()
    {
        $this->markTestSkipped('Payment workflow endpoints need implementation');
    }

    public function test_payment_with_wallet_balance()
    {
        $this->markTestSkipped('Payment workflow endpoints need implementation');
    }

    public function test_payment_fails_with_insufficient_balance()
    {
        $this->markTestSkipped('Payment workflow endpoints need implementation');
    }

    public function test_payment_creates_transaction_record()
    {
        $this->markTestSkipped('Payment workflow endpoints need implementation');
    }

    public function test_multiple_payments_tracked()
    {
        $this->markTestSkipped('Payment workflow endpoints need implementation');
    }

    public function test_payment_reference_is_unique()
    {
        $this->markTestSkipped('Payment workflow endpoints need implementation');
    }
}

