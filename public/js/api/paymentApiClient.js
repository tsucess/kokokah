/**
 * Payment API Client
 * Handles all payment-related API calls for multiple gateways
 * Supports: Kudikah Wallet, Paystack, Flutterwave, Stripe, PayPal
 * Extends BaseApiClient for common functionality
 */

class PaymentApiClient extends BaseApiClient {
  /**
   * Get available payment gateways
   */
  static async getGateways() {
    return this.get('/payments/gateways');
  }

  /**
   * Initialize course payment
   * @param {object} paymentData - Payment data
   * @param {array} paymentData.course_ids - Array of course IDs to purchase
   * @param {string} paymentData.gateway - Payment gateway (kudikah, paystack, flutterwave, stripe, paypal)
   * @param {string} paymentData.coupon_code - Optional coupon code
   */
  static async initializeCoursePayment(paymentData) {
    return this.post('/payments/purchase-course', paymentData);
  }

  /**
   * Initialize wallet deposit
   * @param {object} depositData - Deposit data
   * @param {number} depositData.amount - Amount to deposit
   * @param {string} depositData.gateway - Payment gateway
   * @param {string} depositData.currency - Currency code (default: NGN)
   */
  static async initializeWalletDeposit(depositData) {
    return this.post('/payments/deposit', depositData);
  }

  /**
   * Get payment history
   * @param {object} filters - Filter options
   * @param {number} filters.page - Page number
   * @param {number} filters.per_page - Items per page
   * @param {string} filters.type - Payment type (wallet_deposit, course_purchase)
   * @param {string} filters.status - Payment status (pending, completed, failed)
   */
  static async getHistory(filters = {}) {
    const params = new URLSearchParams();
    if (filters.page) params.append('page', filters.page);
    if (filters.per_page) params.append('per_page', filters.per_page);
    if (filters.type) params.append('type', filters.type);
    if (filters.status) params.append('status', filters.status);

    const queryString = params.toString();
    const endpoint = queryString ? `/payments/history?${queryString}` : '/payments/history';
    return this.get(endpoint);
  }

  /**
   * Get payment details by ID
   * @param {number} paymentId - Payment ID
   */
  static async getPayment(paymentId) {
    return this.get(`/payments/${paymentId}`);
  }

  /**
   * Verify payment with gateway
   * @param {string} gateway - Payment gateway name
   * @param {string} reference - Payment reference from gateway
   */
  static async verifyPayment(gateway, reference) {
    return this.post(`/payments/verify/${gateway}`, {
      reference: reference
    });
  }

  /**
   * Handle payment callback
   * @param {string} gateway - Payment gateway name
   * @param {object} callbackData - Callback data from gateway
   */
  static async handleCallback(gateway, callbackData) {
    return this.post(`/payments/callback/${gateway}`, callbackData);
  }

  /**
   * Get payment status
   * @param {string} reference - Payment reference
   */
  static async getPaymentStatus(reference) {
    return this.get(`/payments/status/${reference}`);
  }

  /**
   * Cancel payment
   * @param {number} paymentId - Payment ID
   */
  static async cancelPayment(paymentId) {
    return this.post(`/payments/${paymentId}/cancel`);
  }

  /**
   * Retry failed payment
   * @param {number} paymentId - Payment ID
   */
  static async retryPayment(paymentId) {
    return this.post(`/payments/${paymentId}/retry`);
  }

  /**
   * Get payment receipt
   * @param {number} paymentId - Payment ID
   */
  static async getReceipt(paymentId) {
    return this.get(`/payments/${paymentId}/receipt`);
  }

  /**
   * Download payment invoice
   * @param {number} paymentId - Payment ID
   */
  static async downloadInvoice(paymentId) {
    return this.get(`/payments/${paymentId}/invoice`, { responseType: 'blob' });
  }
}

// Make available globally
window.PaymentApiClient = PaymentApiClient;
