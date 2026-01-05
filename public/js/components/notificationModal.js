/**
 * Notification Modal Component
 * Handles notification modal display and interactions
 */

class NotificationModal {
  constructor() {
    this.modal = null;
    this.announcements = [];
    this.messages = [];
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

    // Load notifications when tabs are clicked
    const announcementsTab = document.querySelector('[href="#announcements"]');
    const messagesTab = document.querySelector('[href="#messages"]');
    const notificationsTab = document.querySelector('[href="#notifications"]');

    if (announcementsTab) {
      announcementsTab.addEventListener('click', () => this.loadAnnouncements());
    }
    if (messagesTab) {
      messagesTab.addEventListener('click', () => this.loadMessages());
    }
    if (notificationsTab) {
      notificationsTab.addEventListener('click', () => this.loadSystemNotifications());
    }
  }

  /**
   * Load all notifications
   */
  async loadNotifications() {
    await Promise.all([
      this.loadAnnouncements(),
      this.loadMessages(),
      this.loadSystemNotifications()
    ]);
  }

  /**
   * Load announcements
   */
  async loadAnnouncements() {
    try {
      const response = await window.NotificationApiClient.getAnnouncements({ per_page: 5 });
      this.announcements = response.success && response.data ? response.data : [];
      this.renderAnnouncements();
    } catch (error) {
      console.error('Error loading announcements:', error);
      this.renderEmpty('announcementsList', 'No announcements');
    }
  }

  /**
   * Load messages
   */
  async loadMessages() {
    try {
      const response = await window.NotificationApiClient.getMessages({ per_page: 5 });
      this.messages = response.success && response.data ? response.data : [];
      this.renderMessages();
    } catch (error) {
      console.error('Error loading messages:', error);
      this.renderEmpty('messagesList', 'No messages');
    }
  }

  /**
   * Load system notifications
   */
  async loadSystemNotifications() {
    try {
      const response = await window.NotificationApiClient.getSystemNotifications({ per_page: 5 });
      this.notifications = response.success && response.data ? response.data : [];
      this.renderNotifications();
    } catch (error) {
      console.error('Error loading notifications:', error);
      this.renderEmpty('notificationsList', 'No notifications');
    }
  }

  /**
   * Render announcements
   */
  renderAnnouncements() {
    const container = document.getElementById('announcementsList');
    if (!container) return;

    if (this.announcements.length === 0) {
      this.renderEmpty('announcementsList', 'No announcements');
      return;
    }

    container.innerHTML = this.announcements.map(item => this.createNotificationItem(
      item,
      '/userannouncement'
    )).join('');
  }

  /**
   * Render messages
   */
  renderMessages() {
    const container = document.getElementById('messagesList');
    if (!container) return;

    if (this.messages.length === 0) {
      this.renderEmpty('messagesList', 'No messages');
      return;
    }

    container.innerHTML = this.messages.map(item => this.createNotificationItem(
      item,
      '/usermessagecenter'
    )).join('');
  }

  /**
   * Render notifications
   */
  renderNotifications() {
    const container = document.getElementById('notificationsList');
    if (!container) return;

    if (this.notifications.length === 0) {
      this.renderEmpty('notificationsList', 'No notifications');
      return;
    }

    container.innerHTML = this.notifications.map(item => this.createNotificationItem(
      item,
      '#'
    )).join('');
  }

  /**
   * Create notification item HTML
   */
  createNotificationItem(item, readMoreLink) {
    const title = item.title || 'Untitled';
    const message = item.message || '';
    const snippet = this.truncateText(message, 100);
    const isUnread = !item.read_at;
    const itemId = item.id || '';

    return `
      <div class="notification-item ${isUnread ? 'unread' : ''}" data-notification-id="${itemId}">
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
      console.error('Error marking all as read:', error);
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

