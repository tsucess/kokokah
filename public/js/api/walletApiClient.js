/**
 * Wallet API Client
 * Handles all wallet-related API calls
 * Extends BaseApiClient for common functionality
 */

import BaseApiClient from './baseApiClient.js';

class WalletApiClient extends BaseApiClient {
  /**
   * Get wallet balance
   */
  static async getBalance() {
    return this.get('/wallet/balance');
  }

  /**
   * Get wallet details
   */
  static async getWallet() {
    return this.get('/wallet');
  }

  /**
   * Get wallet transactions
   * @param {object} filters - Filter options (page, per_page, type, status)
   */
  static async getTransactions(filters = {}) {
    const params = new URLSearchParams();
    if (filters.page) params.append('page', filters.page);
    if (filters.per_page) params.append('per_page', filters.per_page);
    if (filters.type) params.append('type', filters.type);
    if (filters.status) params.append('status', filters.status);

    const queryString = params.toString();
    const endpoint = queryString ? `/wallet/transactions?${queryString}` : '/wallet/transactions';
    return this.get(endpoint);
  }

  /**
   * Add funds to wallet
   * @param {number} amount - Amount to add
   * @param {string} paymentMethod - Payment method (card, bank_transfer, etc.)
   * @param {object} metadata - Additional metadata
   */
  static async addFunds(amount, paymentMethod, metadata = {}) {
    return this.post('/wallet/add-funds', {
      amount: amount,
      payment_method: paymentMethod,
      ...metadata
    });
  }

  /**
   * Withdraw funds from wallet
   * @param {number} amount - Amount to withdraw
   * @param {string} bankAccount - Bank account details
   */
  static async withdrawFunds(amount, bankAccount) {
    return this.post('/wallet/withdraw', {
      amount: amount,
      bank_account: bankAccount
    });
  }

  /**
   * Transfer funds to another user
   * @param {number} recipientId - Recipient user ID
   * @param {number} amount - Amount to transfer
   * @param {string} description - Transfer description
   */
  static async transferFunds(recipientId, amount, description = '') {
    return this.post('/wallet/transfer', {
      recipient_id: recipientId,
      amount: amount,
      description: description
    });
  }

  /**
   * Get wallet transaction by ID
   * @param {number} transactionId - Transaction ID
   */
  static async getTransaction(transactionId) {
    return this.get(`/wallet/transactions/${transactionId}`);
  }

  /**
   * Get wallet statistics
   */
  static async getStatistics() {
    return this.get('/wallet/statistics');
  }

  /**
   * Get wallet history
   * @param {object} filters - Filter options
   */
  static async getHistory(filters = {}) {
    const params = new URLSearchParams();
    if (filters.start_date) params.append('start_date', filters.start_date);
    if (filters.end_date) params.append('end_date', filters.end_date);
    if (filters.type) params.append('type', filters.type);

    const queryString = params.toString();
    const endpoint = queryString ? `/wallet/history?${queryString}` : '/wallet/history';
    return this.get(endpoint);
  }

  /**
   * Export wallet transactions
   * @param {object} filters - Filter options
   */
  static async exportTransactions(filters = {}) {
    const params = new URLSearchParams();
    if (filters.start_date) params.append('start_date', filters.start_date);
    if (filters.end_date) params.append('end_date', filters.end_date);

    const queryString = params.toString();
    const endpoint = queryString ? `/wallet/export?${queryString}` : '/wallet/export';
    return this.get(endpoint, { responseType: 'blob' });
  }

  /**
   * Get saved payment methods
   */
  static async getPaymentMethods() {
    return this.get('/wallet/payment-methods');
  }

  /**
   * Add payment method
   * @param {object} paymentMethodData - Payment method data
   */
  static async addPaymentMethod(paymentMethodData) {
    return this.post('/wallet/payment-methods', paymentMethodData);
  }

  /**
   * Delete payment method
   * @param {number} methodId - Payment method ID
   */
  static async deletePaymentMethod(methodId) {
    return this.delete(`/wallet/payment-methods/${methodId}`);
  }

  /**
   * Set default payment method
   * @param {number} methodId - Payment method ID
   */
  static async setDefaultPaymentMethod(methodId) {
    return this.post(`/wallet/payment-methods/${methodId}/set-default`);
  }

  /**
   * Get wallet limits
   */
  static async getLimits() {
    return this.get('/wallet/limits');
  }

  /**
   * Request wallet verification
   */
  static async requestVerification() {
    return this.post('/wallet/request-verification');
  }

  /**
   * Verify wallet with code
   * @param {string} code - Verification code
   */
  static async verifyWithCode(code) {
    return this.post('/wallet/verify', {
      code: code
    });
  }
}

export default WalletApiClient;

