/**
 * Admin API Client
 * Handles all admin-related API calls
 * Extends BaseApiClient for common functionality
 */

import BaseApiClient from './baseApiClient.js';

class AdminApiClient extends BaseApiClient {
  /**
   * Get admin dashboard statistics
   */
  static async getDashboardStats() {
    return this.get('/admin/dashboard');
  }

  /**
   * Get recent users
   * @param {number} page - Page number for pagination
   * @param {number} perPage - Items per page
   */
  static async getRecentUsers(page = 1, perPage = 10) {
    return this.get(`/admin/users/recent?page=${page}&per_page=${perPage}`);
  }

  /**
   * Get all users with optional filtering
   * @param {object} filters - Filter options (page, per_page, role, search)
   */
  static async getUsers(filters = {}) {
    const params = new URLSearchParams();
    if (filters.page) params.append('page', filters.page);
    if (filters.per_page) params.append('per_page', filters.per_page);
    if (filters.role) params.append('role', filters.role);
    if (filters.search) params.append('search', filters.search);

    const queryString = params.toString();
    const endpoint = queryString ? `/admin/users?${queryString}` : '/admin/users';
    return this.get(endpoint);
  }

  /**
   * Get user by ID
   * @param {number} userId - User ID
   */
  static async getUser(userId) {
    return this.get(`/admin/users/${userId}`);
  }

  /**
   * Create a new user
   * @param {object} userData - User data
   */
  static async createUser(userData) {
    return this.post('/admin/users', userData);
  }

  /**
   * Update user
   * @param {number} userId - User ID
   * @param {object} userData - Updated user data
   */
  static async updateUser(userId, userData) {
    return this.put(`/admin/users/${userId}`, userData);
  }

  /**
   * Delete user
   * @param {number} userId - User ID
   */
  static async deleteUser(userId) {
    return this.delete(`/admin/users/${userId}`);
  }

  /**
   * Get all transactions
   * @param {object} filters - Filter options (page, per_page, status)
   */
  static async getTransactions(filters = {}) {
    const params = new URLSearchParams();
    if (filters.page) params.append('page', filters.page);
    if (filters.per_page) params.append('per_page', filters.per_page);
    if (filters.status) params.append('status', filters.status);

    const queryString = params.toString();
    const endpoint = queryString ? `/admin/transactions?${queryString}` : '/admin/transactions';
    return this.get(endpoint);
  }

  /**
   * Get transaction by ID
   * @param {number} transactionId - Transaction ID
   */
  static async getTransaction(transactionId) {
    return this.get(`/admin/transactions/${transactionId}`);
  }

  /**
   * Get user activity (all activities from admin dashboard)
   * @param {object} filters - Filter options (page, per_page)
   */
  static async getUserActivity(filters = {}) {
    const params = new URLSearchParams();
    if (filters.page) params.append('page', filters.page);
    if (filters.per_page) params.append('per_page', filters.per_page);

    const queryString = params.toString();
    const endpoint = queryString ? `/admin/dashboard?${queryString}` : '/admin/dashboard';
    return this.get(endpoint);
  }

  /**
   * Get system statistics
   */
  static async getSystemStats() {
    return this.get('/admin/stats');
  }

  /**
   * Get user statistics
   */
  static async getUserStats() {
    return this.get('/admin/stats/users');
  }

  /**
   * Get course statistics
   */
  static async getCourseStats() {
    return this.get('/admin/stats/courses');
  }

  /**
   * Get revenue statistics
   */
  static async getRevenueStats() {
    return this.get('/admin/stats/revenue');
  }

  /**
   * Export users to CSV
   */
  static async exportUsers() {
    return this.get('/admin/users/export', { responseType: 'blob' });
  }

  /**
   * Export transactions to CSV
   */
  static async exportTransactions() {
    return this.get('/admin/transactions/export', { responseType: 'blob' });
  }

  /**
   * Get admin logs
   * @param {object} filters - Filter options
   */
  static async getLogs(filters = {}) {
    const params = new URLSearchParams();
    if (filters.page) params.append('page', filters.page);
    if (filters.per_page) params.append('per_page', filters.per_page);
    if (filters.action) params.append('action', filters.action);

    const queryString = params.toString();
    const endpoint = queryString ? `/admin/logs?${queryString}` : '/admin/logs';
    return this.get(endpoint);
  }
}

export default AdminApiClient;