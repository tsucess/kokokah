/**
 * Subscription API Client
 * Handles all subscription-related API calls
 * Extends BaseApiClient for common functionality
 */

class SubscriptionApiClient extends BaseApiClient {
  /**
   * Get all subscription plans
   * @param {object} filters - Filter options (active, duration_type, page, per_page)
   */
  static async getPlans(filters = {}) {
    const params = new URLSearchParams();
    if (filters.active !== undefined) params.append('active', filters.active);
    if (filters.duration_type) params.append('duration_type', filters.duration_type);
    if (filters.page) params.append('page', filters.page);
    if (filters.per_page) params.append('per_page', filters.per_page);

    const queryString = params.toString();
    const endpoint = queryString ? `/subscriptions/plans?${queryString}` : '/subscriptions/plans';
    return this.get(endpoint);
  }

  /**
   * Get specific subscription plan
   * @param {number} planId - Plan ID
   */
  static async getPlan(planId) {
    return this.get(`/subscriptions/plans/${planId}`);
  }

  /**
   * Get user's subscriptions
   * @param {object} filters - Filter options (status, page, per_page)
   */
  static async getMySubscriptions(filters = {}) {
    const params = new URLSearchParams();
    if (filters.status) params.append('status', filters.status);
    if (filters.page) params.append('page', filters.page);
    if (filters.per_page) params.append('per_page', filters.per_page);

    const queryString = params.toString();
    const endpoint = queryString ? `/subscriptions/my-subscriptions?${queryString}` : '/subscriptions/my-subscriptions';
    return this.get(endpoint);
  }

  /**
   * Subscribe to a plan
   * @param {object} subscriptionData - Subscription data
   */
  static async subscribe(subscriptionData) {
    return this.post('/subscriptions/subscribe', subscriptionData);
  }

  /**
   * Cancel subscription
   * @param {number} subscriptionId - Subscription ID
   */
  static async cancelSubscription(subscriptionId) {
    return this.post(`/subscriptions/${subscriptionId}/cancel`);
  }

  /**
   * Pause subscription
   * @param {number} subscriptionId - Subscription ID
   */
  static async pauseSubscription(subscriptionId) {
    return this.post(`/subscriptions/${subscriptionId}/pause`);
  }

  /**
   * Resume subscription
   * @param {number} subscriptionId - Subscription ID
   */
  static async resumeSubscription(subscriptionId) {
    return this.post(`/subscriptions/${subscriptionId}/resume`);
  }
}

// Make available globally
window.SubscriptionApiClient = SubscriptionApiClient;

