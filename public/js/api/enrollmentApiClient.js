/**
 * Enrollment API Client
 * Handles all enrollment-related API calls
 * Extends BaseApiClient for common functionality
 */

import BaseApiClient from './baseApiClient.js';

class EnrollmentApiClient extends BaseApiClient {
  /**
   * Get user's enrollments
   * @param {object} filters - Filter options (page, per_page, status, completed, sort_by, sort_order)
   */
  static async getEnrollments(filters = {}) {
    const params = new URLSearchParams();
    if (filters.page) params.append('page', filters.page);
    if (filters.per_page) params.append('per_page', filters.per_page);
    if (filters.status) params.append('status', filters.status);
    if (filters.completed !== undefined) params.append('completed', filters.completed);
    if (filters.sort_by) params.append('sort_by', filters.sort_by);
    if (filters.sort_order) params.append('sort_order', filters.sort_order);

    const queryString = params.toString();
    const endpoint = queryString ? `/enrollments?${queryString}` : '/enrollments';
    return this.get(endpoint);
  }

  /**
   * Get single enrollment
   * @param {number} enrollmentId - Enrollment ID
   */
  static async getEnrollment(enrollmentId) {
    return this.get(`/enrollments/${enrollmentId}`);
  }

  /**
   * Create enrollment
   * @param {object} enrollmentData - Enrollment data
   */
  static async createEnrollment(enrollmentData) {
    return this.post('/enrollments', enrollmentData);
  }

  /**
   * Update enrollment
   * @param {number} enrollmentId - Enrollment ID
   * @param {object} enrollmentData - Updated enrollment data
   */
  static async updateEnrollment(enrollmentId, enrollmentData) {
    return this.put(`/enrollments/${enrollmentId}`, enrollmentData);
  }

  /**
   * Delete enrollment
   * @param {number} enrollmentId - Enrollment ID
   */
  static async deleteEnrollment(enrollmentId) {
    return this.delete(`/enrollments/${enrollmentId}`);
  }

  /**
   * Get enrollment progress
   * @param {number} enrollmentId - Enrollment ID
   */
  static async getEnrollmentProgress(enrollmentId) {
    return this.get(`/enrollments/${enrollmentId}/progress`);
  }

  /**
   * Complete enrollment
   * @param {number} enrollmentId - Enrollment ID
   */
  static async completeEnrollment(enrollmentId) {
    return this.post(`/enrollments/${enrollmentId}/complete`);
  }

  /**
   * Get enrollment certificates
   */
  static async getEnrollmentCertificates() {
    return this.get('/enrollments/certificates');
  }

  /**
   * Get active enrollments (in progress)
   * @param {object} filters - Filter options
   */
  static async getActiveEnrollments(filters = {}) {
    return this.getEnrollments({
      ...filters,
      status: 'in_progress'
    });
  }

  /**
   * Get completed enrollments
   * @param {object} filters - Filter options
   */
  static async getCompletedEnrollments(filters = {}) {
    return this.getEnrollments({
      ...filters,
      completed: true
    });
  }
}

export default EnrollmentApiClient;

