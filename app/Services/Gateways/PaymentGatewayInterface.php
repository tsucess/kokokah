<?php

namespace App\Services\Gateways;

use App\Models\Payment;

interface PaymentGatewayInterface
{
    /**
     * Initialize payment with the gateway
     */
    public function initializePayment(Payment $payment): array;

    /**
     * Verify payment with the gateway
     */
    public function verifyPayment(string $reference): array;

    /**
     * Handle webhook from the gateway
     */
    public function handleWebhook(array $payload): array;

    /**
     * Get supported currencies
     */
    public function getSupportedCurrencies(): array;

    /**
     * Get gateway configuration for frontend
     */
    public function getClientConfig(): array;
}
