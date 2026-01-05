<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentMethodApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Test getting payment methods for authenticated user
     */
    public function test_get_payment_methods_authenticated(): void
    {
        // Create some payment methods
        PaymentMethod::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/wallet/payment-methods');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'card_holder_name',
                        'card_last_four',
                        'expiry_date',
                        'card_type',
                        'is_default',
                        'masked_card',
                        'last_used_at'
                    ]
                ]
            ]);
    }

    /**
     * Test getting payment methods without authentication
     */
    public function test_get_payment_methods_unauthenticated(): void
    {
        $response = $this->getJson('/api/wallet/payment-methods');

        $response->assertStatus(401);
    }

    /**
     * Test adding a valid payment method
     */
    public function test_add_payment_method_valid(): void
    {
        $cardData = [
            'card_holder_name' => 'John Doe',
            'card_number' => '4532015112830366',
            'expiry_date' => '12/25',
            'cvv' => '123',
            'is_default' => true
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/wallet/payment-methods', $cardData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'card_holder_name',
                    'card_last_four',
                    'expiry_date',
                    'card_type',
                    'is_default',
                    'masked_card'
                ]
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'card_holder_name' => 'John Doe',
                    'card_last_four' => '0366',
                    'expiry_date' => '12/25',
                    'card_type' => 'visa',
                    'is_default' => true
                ]
            ]);

        // Verify card was saved in database
        $this->assertDatabaseHas('payment_methods', [
            'user_id' => $this->user->id,
            'card_holder_name' => 'John Doe',
            'card_last_four' => '0366'
        ]);
    }

    /**
     * Test adding payment method with invalid card number
     */
    public function test_add_payment_method_invalid_card_number(): void
    {
        $cardData = [
            'card_holder_name' => 'John Doe',
            'card_number' => '1234',  // Too short
            'expiry_date' => '12/25',
            'cvv' => '123'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/wallet/payment-methods', $cardData);

        $response->assertStatus(422)
            ->assertJsonStructure(['success', 'message', 'errors']);
    }

    /**
     * Test adding payment method with invalid expiry date
     */
    public function test_add_payment_method_invalid_expiry(): void
    {
        $cardData = [
            'card_holder_name' => 'John Doe',
            'card_number' => '4532015112830366',
            'expiry_date' => '13/25',  // Invalid month
            'cvv' => '123'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/wallet/payment-methods', $cardData);

        $response->assertStatus(422);
    }

    /**
     * Test adding payment method with invalid CVV
     */
    public function test_add_payment_method_invalid_cvv(): void
    {
        $cardData = [
            'card_holder_name' => 'John Doe',
            'card_number' => '4532015112830366',
            'expiry_date' => '12/25',
            'cvv' => '12'  // Too short
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/wallet/payment-methods', $cardData);

        $response->assertStatus(422);
    }

    /**
     * Test deleting a payment method
     */
    public function test_delete_payment_method(): void
    {
        $paymentMethod = PaymentMethod::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/wallet/payment-methods/{$paymentMethod->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('payment_methods', ['id' => $paymentMethod->id]);
    }

    /**
     * Test setting default payment method
     */
    public function test_set_default_payment_method(): void
    {
        $method1 = PaymentMethod::factory()->create(['user_id' => $this->user->id, 'is_default' => true]);
        $method2 = PaymentMethod::factory()->create(['user_id' => $this->user->id, 'is_default' => false]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/wallet/payment-methods/{$method2->id}/set-default");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        // Verify method2 is now default
        $this->assertTrue($method2->fresh()->is_default);
        // Verify method1 is no longer default
        $this->assertFalse($method1->fresh()->is_default);
    }

    /**
     * Test card type detection for Visa
     */
    public function test_card_type_detection_visa(): void
    {
        $cardData = [
            'card_holder_name' => 'John Doe',
            'card_number' => '4532015112830366',  // Visa
            'expiry_date' => '12/25',
            'cvv' => '123'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/wallet/payment-methods', $cardData);

        $response->assertJson(['data' => ['card_type' => 'visa']]);
    }

    /**
     * Test card type detection for Mastercard
     */
    public function test_card_type_detection_mastercard(): void
    {
        $cardData = [
            'card_holder_name' => 'John Doe',
            'card_number' => '5425233010103442',  // Mastercard
            'expiry_date' => '12/25',
            'cvv' => '123'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/wallet/payment-methods', $cardData);

        $response->assertJson(['data' => ['card_type' => 'mastercard']]);
    }

    /**
     * Test masked card number format
     */
    public function test_masked_card_number_format(): void
    {
        $paymentMethod = PaymentMethod::factory()->create([
            'user_id' => $this->user->id,
            'card_last_four' => '0366'
        ]);

        $this->assertEquals('**** **** **** 0366', $paymentMethod->getMaskedCardNumber());
    }

    /**
     * Test payment method relationships
     */
    public function test_payment_method_user_relationship(): void
    {
        $paymentMethod = PaymentMethod::factory()->create(['user_id' => $this->user->id]);

        $this->assertTrue($paymentMethod->user->is($this->user));
        $this->assertContains($paymentMethod->id, $this->user->paymentMethods->pluck('id'));
    }
}
