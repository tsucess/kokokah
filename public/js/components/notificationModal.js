/**
 * Notification Modal Component
 * Handles notification modal display and interactions
 */

class NotificationModal {
  constructor() {
    this.modal = null;
    this.notifications = [];
  }

  /**
   * Initialize the notification modal
   */
  async init() {
    this.modal = new bootstrap.Modal(document.getElementById('notificationModal'));
    this.setupEventListeners();
    await this.loadNotifications();
  }

  /**
   * Setup event listeners for modal
   */
  setupEventListeners() {
    const markAllReadBtn = document.getElementById('markAllReadBtn');
    if (markAllReadBtn) {
      markAllReadBtn.addEventListener('click', () => this.markAllAsRead());
    }
  }

  /**
   * Load all notifications (consolidated from announcements, messages, and system notifications)
   */
  async loadNotifications() {
    try {
      const [announcementsResponse, messagesResponse, notificationsResponse] = await Promise.all([
        window.NotificationApiClient.getAnnouncements({ per_page: 5 }),
        window.NotificationApiClient.getMessages({ per_page: 5 }),
        window.NotificationApiClient.getSystemNotifications({ per_page: 5 })
      ]);

      // Consolidate all notifications into a single array
      const announcements = this.ensureArray(announcementsResponse.success && announcementsResponse.data ? announcementsResponse.data : []);
      const messages = this.ensureArray(messagesResponse.success && messagesResponse.data ? messagesResponse.data : []);
      const systemNotifications = this.ensureArray(notificationsResponse.success && notificationsResponse.data ? notificationsResponse.data : []);

      // Combine all notifications with type information
      this.notifications = [
        ...announcements.map(item => ({ ...item, type: 'announcement', readMoreLink: '/userannouncement' })),
        ...messages.map(item => ({ ...item, type: 'message', readMoreLink: '/usermessagecenter' })),
        ...systemNotifications.map(item => ({ ...item, type: 'notification', readMoreLink: '#' }))
      ];

      // Sort by date (most recent first) if available
      this.notifications.sort((a, b) => {
        const dateA = new Date(a.created_at || 0);
        const dateB = new Date(b.created_at || 0);
        return dateB - dateA;
      });

      this.renderNotifications();
    } catch (error) {
      console.error('Error loading notifications:', error);
      this.notifications = [];
      this.renderEmpty('notificationsList', 'No notifications');
    }
  }

  /**
   * Render all notifications (consolidated)
   */
  renderNotifications() {
    const container = document.getElementById('notificationsList');
    if (!container) return;

    if (this.notifications.length === 0) {
      this.renderEmpty('notificationsList', 'No notifications');
      return;
    }

    container.innerHTML = this.notifications.map(item => this.createNotificationItem(item)).join('');
  }

  /**
   * Create notification item HTML
   */
  createNotificationItem(item) {
    const title = item.title || 'Untitled';
    const message = item.message || '';
    const snippet = this.truncateText(message, 100);
    const isUnread = !item.read_at;
    const itemId = item.id || '';
    const readMoreLink = item.readMoreLink || '#';
    const type = item.type || 'notification';

    return `
      <div class="notification-item ${isUnread ? 'unread' : ''}" data-notification-id="${itemId}" data-notification-type="${type}">
        <div class="notification-title">${this.escapeHtml(title)}</div>
        <div class="notification-snippet">${this.escapeHtml(snippet)}</div>
        <button class="btn-read-more" onclick="window.location.href='${readMoreLink}'">
          Read More
        </button>
      </div>
    `;
  }

  /**
   * Render empty state
   */
  renderEmpty(containerId, message) {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = `
      <div class="notification-empty">
        <i class="fa-regular fa-bell"></i>
        <p>${message}</p>
      </div>
    `;
  }

  /**
   * Truncate text to specified length
   */
  truncateText(text, length) {
    if (!text) return '';
    if (text.length <= length) return text;
    return text.substring(0, length) + '...';
  }

  /**
   * Escape HTML special characters
   */
  escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  /**
   * Ensure value is an array
   */
  ensureArray(value) {
    if (Array.isArray(value)) {
      return value;
    }
    return [];
  }

  /**
   * Mark all notifications as read
   */
  async markAllAsRead() {
    try {
      const response = await window.NotificationApiClient.markAllAsRead();
      if (response.success) {
        // Reload notifications
        await this.loadNotifications();
        // Update badge
        if (window.DashboardModule) {
          await window.DashboardModule.loadNotifications();
        }
      }
    } catch (error) {
    }
  }

  /**
   * Show the modal
   */
  show() {
    if (this.modal) {
      this.modal.show();
    }
  }

  /**
   * Hide the modal
   */
  hide() {
    if (this.modal) {
      this.modal.hide();
    }
  }
}

// Create global instance
window.NotificationModalComponent = new NotificationModal();

