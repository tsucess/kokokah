/**
 * Notification API Client
 * Handles all notification-related API calls
 */

class NotificationApiClient extends BaseApiClient {
  /**
   * Get notifications with optional filters
   * @param {Object} filters - Filter options (status, type, page, per_page)
   * @returns {Promise<Object>} Response with notifications
   */
  static async getNotifications(filters = {}) {
    try {
      const params = new URLSearchParams();
      if (filters.status) params.append('status', filters.status);
      if (filters.type) params.append('type', filters.type);
      if (filters.page) params.append('page', filters.page);
      if (filters.per_page) params.append('per_page', filters.per_page);

      const queryString = params.toString();
      const endpoint = queryString ? `/users/notifications?${queryString}` : '/users/notifications';
      return await this.get(endpoint);
    } catch (error) {
      return {
        success: false,
        message: 'Failed to fetch notifications',
        data: []
      };
    }
  }

  /**
   * Get count of unread notifications
   * @returns {Promise<number>} Count of unread notifications
   */
  static async getUnreadCount() {
    try {
      const response = await this.getNotifications({ status: 'unread' });
      if (response.success && response.data) {
        return Array.isArray(response.data) ? response.data.length : 0;
      }
      return 0;
    } catch (error) {
      return 0;
    }
  }

  /**
   * Mark a single notification as read
   * @param {number|string} notificationId - ID of notification to mark as read
   * @returns {Promise<Object>} Response from API
   */
  static async markAsRead(notificationId) {
    try {
      return await this.put(`/users/notifications/${notificationId}/read`, {});
    } catch (error) {
      return {
        success: false,
        message: 'Failed to mark notification as read'
      };
    }
  }

  /**
   * Mark all notifications as read
   * @returns {Promise<Object>} Response from API
   */
  static async markAllAsRead() {
    try {
      return await this.put('/users/notifications/read-all', {});
    } catch (error) {
      return {
        success: false,
        message: 'Failed to mark all notifications as read'
      };
    }
  }

  /**
   * Get announcements
   * @param {Object} filters - Filter options
   * @returns {Promise<Object>} Response with announcements
   */
  static async getAnnouncements(filters = {}) {
    try {
      return await this.getNotifications({ ...filters, type: 'announcement' });
    } catch (error) {
      return {
        success: false,
        message: 'Failed to fetch announcements',
        data: []
      };
    }
  }

  /**
   * Get messages
   * @param {Object} filters - Filter options
   * @returns {Promise<Object>} Response with messages
   */
  static async getMessages(filters = {}) {
    try {
      return await this.getNotifications({ ...filters, type: 'message' });
    } catch (error) {
      return {
        success: false,
        message: 'Failed to fetch messages',
        data: []
      };
    }
  }

  /**
   * Get system notifications
   * @param {Object} filters - Filter options
   * @returns {Promise<Object>} Response with notifications
   */
  static async getSystemNotifications(filters = {}) {
    try {
      return await this.getNotifications({ ...filters, type: 'system' });
    } catch (error) {
      return {
        success: false,
        message: 'Failed to fetch system notifications',
        data: []
      };
    }
  }
}

// Make available globally
window.NotificationApiClient = NotificationApiClient;

