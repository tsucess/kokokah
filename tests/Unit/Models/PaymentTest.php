<?php

namespace Tests\Unit\Models;

use App\Models\Payment;
use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\Term;
use App\Models\Level;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $course;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $instructor = User::factory()->create(['role' => 'instructor']);
        $category = Category::factory()->create();
        $term = Term::factory()->create();
        $level = Level::factory()->create();
        
        $this->course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'term_id' => $term->id,
            'level_id' => $level->id,
            'price' => 100.00,
            'status' => 'published'
        ]);
    }

    public function test_payment_can_be_created()
    {
        $payment = Payment::create([
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'amount' => 100.00,
            'currency' => 'NGN',
            'gateway' => 'paystack',
            'type' => 'course_purchase',
            'status' => 'pending'
        ]);

        $this->assertDatabaseHas('payments', [
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'status' => 'pending'
        ]);
    }

    public function test_payment_belongs_to_user()
    {
        $payment = Payment::create([
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'amount' => 100.00,
            'currency' => 'NGN',
            'gateway' => 'paystack',
            'type' => 'course_purchase',
            'status' => 'pending'
        ]);

        $this->assertEquals($this->user->id, $payment->user->id);
    }

    public function test_payment_status_can_be_pending()
    {
        $payment = Payment::create([
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'amount' => 100.00,
            'currency' => 'NGN',
            'gateway' => 'paystack',
            'type' => 'course_purchase',
            'status' => 'pending'
        ]);

        $this->assertEquals('pending', $payment->status);
    }

    public function test_payment_status_can_be_completed()
    {
        $payment = Payment::create([
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'amount' => 100.00,
            'currency' => 'NGN',
            'gateway' => 'paystack',
            'type' => 'course_purchase',
            'status' => 'completed'
        ]);

        $this->assertEquals('completed', $payment->status);
    }

    public function test_payment_status_can_be_failed()
    {
        $payment = Payment::create([
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'amount' => 100.00,
            'currency' => 'NGN',
            'gateway' => 'paystack',
            'type' => 'course_purchase',
            'status' => 'failed'
        ]);

        $this->assertEquals('failed', $payment->status);
    }

    public function test_payment_supports_multiple_gateways()
    {
        $gateways = ['paystack', 'flutterwave', 'stripe', 'paypal'];

        foreach ($gateways as $gateway) {
            $payment = Payment::create([
                'user_id' => $this->user->id,
                'course_id' => $this->course->id,
                'amount' => 100.00,
                'currency' => 'NGN',
                'gateway' => $gateway,
                'type' => 'course_purchase',
                'status' => 'pending'
            ]);

            $this->assertEquals($gateway, $payment->gateway);
        }
    }

    public function test_payment_type_can_be_course_purchase()
    {
        $payment = Payment::create([
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'amount' => 100.00,
            'currency' => 'NGN',
            'gateway' => 'paystack',
            'type' => 'course_purchase',
            'status' => 'pending'
        ]);

        $this->assertEquals('course_purchase', $payment->type);
    }

    public function test_payment_type_can_be_wallet_deposit()
    {
        $payment = Payment::create([
            'user_id' => $this->user->id,
            'amount' => 100.00,
            'currency' => 'NGN',
            'gateway' => 'paystack',
            'type' => 'wallet_deposit',
            'status' => 'pending'
        ]);

        $this->assertEquals('wallet_deposit', $payment->type);
    }

    public function test_payment_amount_is_numeric()
    {
        $payment = Payment::create([
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'amount' => 99.99,
            'currency' => 'NGN',
            'gateway' => 'paystack',
            'gateway_reference' => 'PAY_TEST123',
            'type' => 'course_purchase',
            'status' => 'pending'
        ]);

        // Decimal fields are cast to strings in Laravel
        $this->assertIsString($payment->amount);
        $this->assertEquals('99.99', $payment->amount);
    }

    public function test_payment_has_reference()
    {
        $payment = Payment::create([
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'amount' => 100.00,
            'currency' => 'NGN',
            'gateway' => 'paystack',
            'gateway_reference' => 'PAY-12345',
            'type' => 'course_purchase',
            'status' => 'pending'
        ]);

        $this->assertEquals('PAY-12345', $payment->gateway_reference);
    }

    public function test_payment_is_completed_method()
    {
        $payment = Payment::create([
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'amount' => 100.00,
            'currency' => 'NGN',
            'gateway' => 'paystack',
            'type' => 'course_purchase',
            'status' => 'completed'
        ]);

        $this->assertTrue($payment->isCompleted());
    }

    public function test_payment_is_failed_method()
    {
        $payment = Payment::create([
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'amount' => 100.00,
            'currency' => 'NGN',
            'gateway' => 'paystack',
            'type' => 'course_purchase',
            'status' => 'failed'
        ]);

        $this->assertTrue($payment->isFailed());
    }
}

