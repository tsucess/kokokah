/**
 * Global Notification Helper
 * Provides standardized functions for showing toasts and confirmations
 * Available globally as window.NotificationHelper
 */

class NotificationHelper {
  /**
   * Show success toast
   * @param {string} message - Success message
   * @param {string} title - Optional title (default: 'Success')
   * @param {number} timeout - Auto-hide timeout in ms (default: 3500)
   */
  static success(message, title = 'Success', timeout = 3500) {
    if (window.ToastNotification) {
      window.ToastNotification.success(title, message, timeout);
    } else {
      console.warn('ToastNotification not available');
      alert(`${title}: ${message}`);
    }
  }

  /**
   * Show error toast
   * @param {string} message - Error message
   * @param {string} title - Optional title (default: 'Error')
   * @param {number} timeout - Auto-hide timeout in ms (default: 5000)
   */
  static error(message, title = 'Error', timeout = 5000) {
    if (window.ToastNotification) {
      window.ToastNotification.error(title, message, timeout);
    } else {
      console.warn('ToastNotification not available');
      alert(`${title}: ${message}`);
    }
  }

  /**
   * Show warning toast
   * @param {string} message - Warning message
   * @param {string} title - Optional title (default: 'Warning')
   * @param {number} timeout - Auto-hide timeout in ms (default: 4000)
   */
  static warning(message, title = 'Warning', timeout = 4000) {
    if (window.ToastNotification) {
      window.ToastNotification.warning(title, message, timeout);
    } else {
      console.warn('ToastNotification not available');
      alert(`${title}: ${message}`);
    }
  }

  /**
   * Show info toast
   * @param {string} message - Info message
   * @param {string} title - Optional title (default: 'Info')
   * @param {number} timeout - Auto-hide timeout in ms (default: 3500)
   */
  static info(message, title = 'Info', timeout = 3500) {
    if (window.ToastNotification) {
      window.ToastNotification.info(title, message, timeout);
    } else {
      console.warn('ToastNotification not available');
      alert(`${title}: ${message}`);
    }
  }

  /**
   * Show delete confirmation modal
   * @param {string} itemName - Name of item being deleted
   * @returns {Promise<boolean>} - True if confirmed, false if cancelled
   */
  static async confirmDelete(itemName = 'this item') {
    if (window.confirmationModal) {
      return await window.confirmationModal.showDeleteConfirmation(itemName);
    } else {
      console.warn('confirmationModal not available');
      return confirm(`Are you sure you want to delete ${itemName}?`);
    }
  }

  /**
   * Show logout confirmation modal
   * @returns {Promise<boolean>} - True if confirmed, false if cancelled
   */
  static async confirmLogout() {
    if (window.confirmationModal) {
      return await window.confirmationModal.showLogoutConfirmation();
    } else {
      console.warn('confirmationModal not available');
      return confirm('Are you sure you want to logout?');
    }
  }

  /**
   * Show account deletion confirmation modal
   * @returns {Promise<boolean>} - True if confirmed, false if cancelled
   */
  static async confirmAccountDeletion() {
    if (window.confirmationModal) {
      return await window.confirmationModal.showAccountDeletionConfirmation();
    } else {
      console.warn('confirmationModal not available');
      return confirm('Are you sure you want to delete your account? This action cannot be undone.');
    }
  }

  /**
   * Show custom confirmation modal
   * @param {string} title - Modal title
   * @param {string} message - Confirmation message
   * @param {string} confirmText - Confirm button text
   * @param {string} cancelText - Cancel button text
   * @returns {Promise<boolean>} - True if confirmed, false if cancelled
   */
  static async confirm(title, message, confirmText = 'Confirm', cancelText = 'Cancel') {
    if (window.confirmationModal) {
      return await window.confirmationModal.show(title, message, confirmText, cancelText);
    } else {
      console.warn('confirmationModal not available');
      return confirm(message);
    }
  }

  /**
   * Show success toast and redirect after delay
   * @param {string} message - Success message
   * @param {string} redirectUrl - URL to redirect to
   * @param {number} delay - Delay before redirect in ms (default: 1500)
   */
  static successAndRedirect(message, redirectUrl, delay = 1500) {
    this.success(message);
    setTimeout(() => {
      window.location.href = redirectUrl;
    }, delay);
  }

  /**
   * Show error toast and redirect after delay
   * @param {string} message - Error message
   * @param {string} redirectUrl - URL to redirect to
   * @param {number} delay - Delay before redirect in ms (default: 2000)
   */
  static errorAndRedirect(message, redirectUrl, delay = 2000) {
    this.error(message);
    setTimeout(() => {
      window.location.href = redirectUrl;
    }, delay);
  }
}

// Make available globally
window.NotificationHelper = NotificationHelper;

