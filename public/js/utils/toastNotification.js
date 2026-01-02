/**
 * Toast Notification Utility
 * Provides a centralized way to show toast notifications across the application
 * Supports success, error, warning, and info types
 */

class ToastNotification {
  /**
   * Initialize toast container if it doesn't exist
   */
  static initializeContainer() {
    if (!document.getElementById('toastContainer')) {
      const container = document.createElement('div');
      container.id = 'toastContainer';
      container.style.position = 'fixed';
      container.style.top = '20px';
      container.style.right = '20px';
      container.style.zIndex = '9999';
      container.style.pointerEvents = 'none';
      document.body.appendChild(container);
    }
  }

  /**
   * Show a toast notification
   * @param {string} title - Toast title
   * @param {string} message - Toast message
   * @param {string} type - Toast type: 'success', 'error', 'warning', 'info'
   * @param {number} timeout - Auto-hide timeout in milliseconds (0 = no auto-hide)
   */
  static show(title = '', message = '', type = 'info', timeout = 3500) {
    this.initializeContainer();

    const container = document.getElementById('toastContainer');
    const toastId = 'toast-' + Date.now();

    // Determine background color based on type
    const bgColor = this.getBgColor(type);
    const textColor = this.getTextColor(type);

    // Create toast element
    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = 'custom-toast';
    toast.style.minWidth = '300px';
    toast.style.maxWidth = '400px';
    toast.style.padding = '1rem';
    toast.style.marginBottom = '10px';
    toast.style.borderRadius = '6px';
    toast.style.boxShadow = '0 8px 20px rgba(0,0,0,0.15)';
    toast.style.backgroundColor = bgColor;
    toast.style.color = textColor;
    toast.style.fontSize = '0.95rem';
    toast.style.pointerEvents = 'auto';
    toast.style.opacity = '1';
    toast.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    toast.style.animation = 'slideIn 0.3s ease';

    // Create toast content
    const titleEl = document.createElement('strong');
    titleEl.textContent = title;
    titleEl.style.display = 'block';
    titleEl.style.marginBottom = '0.25rem';

    const messageEl = document.createElement('div');
    messageEl.textContent = message;
    messageEl.style.fontSize = '0.9rem';
    messageEl.style.opacity = '0.95';

    // Create close button
    const closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.className = 'btn-close';
    closeBtn.style.position = 'absolute';
    closeBtn.style.top = '0.5rem';
    closeBtn.style.right = '0.5rem';
    closeBtn.style.background = 'none';
    closeBtn.style.border = 'none';
    closeBtn.style.cursor = 'pointer';
    closeBtn.style.fontSize = '1.25rem';
    closeBtn.style.color = textColor;
    closeBtn.style.opacity = '0.7';
    closeBtn.style.padding = '0';
    closeBtn.style.width = '1.5rem';
    closeBtn.style.height = '1.5rem';
    closeBtn.innerHTML = '&times;';
    closeBtn.addEventListener('click', () => this.hide(toastId));

    // Assemble toast
    toast.style.position = 'relative';
    toast.appendChild(titleEl);
    toast.appendChild(messageEl);
    toast.appendChild(closeBtn);

    // Add to container
    container.appendChild(toast);

    // Add animation styles if not already present
    if (!document.getElementById('toastAnimationStyles')) {
      const style = document.createElement('style');
      style.id = 'toastAnimationStyles';
      style.textContent = `
        @keyframes slideIn {
          from {
            transform: translateX(400px);
            opacity: 0;
          }
          to {
            transform: translateX(0);
            opacity: 1;
          }
        }
        @keyframes slideOut {
          from {
            transform: translateX(0);
            opacity: 1;
          }
          to {
            transform: translateX(400px);
            opacity: 0;
          }
        }
      `;
      document.head.appendChild(style);
    }

    // Auto-hide if timeout is set
    if (timeout > 0) {
      setTimeout(() => this.hide(toastId), timeout);
    }

    return toastId;
  }

  /**
   * Hide a toast notification
   */
  static hide(toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
      toast.style.animation = 'slideOut 0.3s ease';
      setTimeout(() => {
        if (toast.parentNode) {
          toast.parentNode.removeChild(toast);
        }
      }, 300);
    }
  }

  /**
   * Show success toast
   */
  static success(title = 'Success', message = '', timeout = 3500) {
    return this.show(title, message, 'success', timeout);
  }

  /**
   * Show error toast
   */
  static error(title = 'Error', message = '', timeout = 5000) {
    return this.show(title, message, 'error', timeout);
  }

  /**
   * Show warning toast
   */
  static warning(title = 'Warning', message = '', timeout = 4000) {
    return this.show(title, message, 'warning', timeout);
  }

  /**
   * Show info toast
   */
  static info(title = 'Info', message = '', timeout = 3500) {
    return this.show(title, message, 'info', timeout);
  }

  /**
   * Get background color for toast type
   */
  static getBgColor(type) {
    const colors = {
      'success': '#198754',
      'error': '#dc3545',
      'warning': '#ffc107',
      'info': '#0d6efd'
    };
    return colors[type] || colors['info'];
  }

  /**
   * Get text color for toast type
   */
  static getTextColor(type) {
    const colors = {
      'success': '#fff',
      'error': '#fff',
      'warning': '#000',
      'info': '#fff'
    };
    return colors[type] || colors['info'];
  }
}

// Make available globally
window.ToastNotification = ToastNotification;