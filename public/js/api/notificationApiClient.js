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
      const endpoint = queryString ? `/notifications?${queryString}` : '/notifications';
      return await this.get(endpoint);
    } catch (error) {
      console.error('Error fetching notifications:', error);
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
      return await this.put(`/notifications/${notificationId}/read`, {});
    } catch (error) {
      console.error('Error marking notification as read:', error);
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
      return await this.put('/notifications/read-all', {});
    } catch (error) {
      console.error('Error marking all notifications as read:', error);
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
      const params = new URLSearchParams();
      if (filters.status) params.append('status', filters.status);
      if (filters.page) params.append('page', filters.page);
      if (filters.per_page) params.append('per_page', filters.per_page);

      const queryString = params.toString();
      const endpoint = queryString ? `/announcements?${queryString}` : '/announcements';
      const response = await this.get(endpoint);

      // Handle paginated response - extract the data array from pagination object
      if (response.success && response.data) {
        if (response.data.data && Array.isArray(response.data.data)) {
          // Paginated response
          return {
            success: true,
            data: response.data.data,
            message: response.message
          };
        } else if (Array.isArray(response.data)) {
          // Direct array response
          return response;
        }
      }

      return response;
    } catch (error) {
      console.error('Error fetching announcements:', error);
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
      // Messages are fetched from notifications with type 'social' or from a dedicated endpoint
      // For now, we'll return an empty array as there's no dedicated messages endpoint
      // This can be extended later when a messages endpoint is created
      return {
        success: true,
        data: [],
        message: 'No messages available'
      };
    } catch (error) {
      console.error('Error fetching messages:', error);
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
      const response = await this.getNotifications({ ...filters, type: 'system' });

      // Handle wrapped response from NotificationController
      if (response.success && response.data && response.data.notifications) {
        // Extract notifications array from the wrapped response
        const notificationsData = response.data.notifications;
        if (notificationsData.data && Array.isArray(notificationsData.data)) {
          // Paginated response
          return {
            success: true,
            data: notificationsData.data,
            message: response.message
          };
        } else if (Array.isArray(notificationsData)) {
          // Direct array response
          return {
            success: true,
            data: notificationsData,
            message: response.message
          };
        }
      }

      return response;
    } catch (error) {
      console.error('Error fetching system notifications:', error);
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

