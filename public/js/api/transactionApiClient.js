/**
 * Transaction API Client
 * Handles all transaction-related API calls
 * Extends BaseApiClient for common functionality
 */

import BaseApiClient from './baseApiClient.js';

class TransactionApiClient extends BaseApiClient {
  /**
   * Get all transactions (admin endpoint)
   * @param {object} filters - Filter options (page, per_page, status, type, search)
   */
  static async getTransactions(filters = {}) {
    const params = new URLSearchParams();
    if (filters.page) params.append('page', filters.page);
    if (filters.per_page) params.append('per_page', filters.per_page);
    if (filters.status) params.append('status', filters.status);
    if (filters.type) params.append('type', filters.type);
    if (filters.search) params.append('search', filters.search);
    if (filters.start_date) params.append('start_date', filters.start_date);
    if (filters.end_date) params.append('end_date', filters.end_date);

    const queryString = params.toString();
    const endpoint = queryString ? `/admin/transactions?${queryString}` : '/admin/transactions';
    return this.get(endpoint);
  }

  /**
   * Get transaction by ID
   * @param {number} transactionId - Transaction ID
   */
  static async getTransaction(transactionId) {
    return this.get(`/transactions/${transactionId}`);
  }

  /**
   * Create a new transaction
   * @param {object} transactionData - Transaction data
   */
  static async createTransaction(transactionData) {
    return this.post('/transactions', transactionData);
  }

  /**
   * Update transaction
   * @param {number} transactionId - Transaction ID
   * @param {object} transactionData - Updated transaction data
   */
  static async updateTransaction(transactionId, transactionData) {
    return this.put(`/transactions/${transactionId}`, transactionData);
  }

  /**
   * Delete transaction
   * @param {number} transactionId - Transaction ID
   */
  static async deleteTransaction(transactionId) {
    return this.delete(`/transactions/${transactionId}`);
  }

  /**
   * Get user transactions
   * @param {number} userId - User ID
   * @param {object} filters - Filter options
   */
  static async getUserTransactions(userId, filters = {}) {
    const params = new URLSearchParams();
    if (filters.page) params.append('page', filters.page);
    if (filters.per_page) params.append('per_page', filters.per_page);
    if (filters.status) params.append('status', filters.status);

    const queryString = params.toString();
    const endpoint = queryString ? `/users/${userId}/transactions?${queryString}` : `/users/${userId}/transactions`;
    return this.get(endpoint);
  }

  /**
   * Get transaction statistics
   */
  static async getStatistics() {
    return this.get('/transactions/statistics');
  }

  /**
   * Get transaction summary
   * @param {object} filters - Filter options (start_date, end_date)
   */
  static async getSummary(filters = {}) {
    const params = new URLSearchParams();
    if (filters.start_date) params.append('start_date', filters.start_date);
    if (filters.end_date) params.append('end_date', filters.end_date);

    const queryString = params.toString();
    const endpoint = queryString ? `/transactions/summary?${queryString}` : '/transactions/summary';
    return this.get(endpoint);
  }

  /**
   * Export transactions to CSV
   * @param {object} filters - Filter options
   */
  static async exportTransactions(filters = {}) {
    const params = new URLSearchParams();
    if (filters.status) params.append('status', filters.status);
    if (filters.start_date) params.append('start_date', filters.start_date);
    if (filters.end_date) params.append('end_date', filters.end_date);

    const queryString = params.toString();
    const endpoint = queryString ? `/transactions/export?${queryString}` : '/transactions/export';
    return this.get(endpoint, { responseType: 'blob' });
  }

  /**
   * Verify transaction
   * @param {number} transactionId - Transaction ID
   */
  static async verifyTransaction(transactionId) {
    return this.post(`/transactions/${transactionId}/verify`);
  }

  /**
   * Refund transaction
   * @param {number} transactionId - Transaction ID
   * @param {object} refundData - Refund data
   */
  static async refundTransaction(transactionId, refundData = {}) {
    return this.post(`/transactions/${transactionId}/refund`, refundData);
  }

  /**
   * Get transaction receipt
   * @param {number} transactionId - Transaction ID
   */
  static async getReceipt(transactionId) {
    return this.get(`/transactions/${transactionId}/receipt`, { responseType: 'blob' });
  }

  /**
   * Send transaction receipt
   * @param {number} transactionId - Transaction ID
   * @param {string} email - Email address
   */
  static async sendReceipt(transactionId, email) {
    return this.post(`/transactions/${transactionId}/send-receipt`, {
      email: email
    });
  }

  /**
   * Get payment methods
   */
  static async getPaymentMethods() {
    return this.get('/payment-methods');
  }

  /**
   * Get transaction by reference
   * @param {string} reference - Transaction reference
   */
  static async getByReference(reference) {
    return this.get(`/transactions/reference/${reference}`);
  }
}

export default TransactionApiClient;

