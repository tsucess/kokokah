/**
 * UI Helper Functions
 * Handles common UI operations like alerts, loading states, etc.
 */

export default class UIHelpers {
  /**
   * Show success alert
   */
  static showSuccess(message, containerId = 'alertContainer') {
    this.showAlert(message, 'success', containerId);
  }

  /**
   * Show error alert
   */
  static showError(message, containerId = 'alertContainer') {
    this.showAlert(message, 'danger', containerId);
  }

  /**
   * Show warning alert
   */
  static showWarning(message, containerId = 'alertContainer') {
    this.showAlert(message, 'warning', containerId);
  }

  /**
   * Show info alert
   */
  static showInfo(message, containerId = 'alertContainer') {
    this.showAlert(message, 'info', containerId);
  }

  /**
   * Generic alert display with sanitization
   */
  static showAlert(message, type = 'info', containerId = 'alertContainer') {
    const container = document.getElementById(containerId);
    if (!container) return;

    const alertId = `alert-${Date.now()}`;

    // Create alert element safely
    const alertDiv = document.createElement('div');
    alertDiv.id = alertId;
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';

    // Use textContent to prevent XSS attacks
    alertDiv.textContent = message;
    alertDiv.style.fontSize = '14px';

    // Create close button
    const closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.className = 'btn-close';
    closeBtn.setAttribute('data-bs-dismiss', 'alert');
    closeBtn.setAttribute('aria-label', 'Close');

    alertDiv.appendChild(closeBtn);
    container.innerHTML = ''; // Clear previous alerts
    container.appendChild(alertDiv);

    // Auto-dismiss after 7 seconds (increased from 5)
    setTimeout(() => {
      const alert = document.getElementById(alertId);
      if (alert) {
        alert.remove();
      }
    }, 7000);
  }

  /**
   * Show loading state on button
   */
  static setButtonLoading(buttonId, isLoading = true) {
    const button = document.getElementById(buttonId);
    if (!button) return;

    if (isLoading) {
      button.disabled = true;
      button.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        Loading...
      `;
    } else {
      button.disabled = false;
      button.innerHTML = button.dataset.originalText || 'Submit';
    }
  }

  /**
   * Store original button text
   */
  static storeButtonText(buttonId) {
    const button = document.getElementById(buttonId);
    if (button) {
      button.dataset.originalText = button.innerHTML;
    }
  }

  /**
   * Validate email format
   */
  static isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  /**
   * Validate password strength
   */
  static isValidPassword(password) {
    // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    return passwordRegex.test(password);
  }

  /**
   * Get password strength message
   */
  static getPasswordStrengthMessage(password) {
    if (password.length < 8) return 'Password must be at least 8 characters';
    if (!/[A-Z]/.test(password)) return 'Password must contain uppercase letter';
    if (!/[a-z]/.test(password)) return 'Password must contain lowercase letter';
    if (!/\d/.test(password)) return 'Password must contain number';
    return 'Password is strong';
  }

  /**
   * Sanitize input to prevent XSS attacks
   */
  static sanitizeInput(input) {
    const div = document.createElement('div');
    div.textContent = input;
    return div.innerHTML;
  }

  /**
   * Validate name (letters, spaces, hyphens, apostrophes only)
   */
  static isValidName(name) {
    const nameRegex = /^[a-zA-Z\s\-']{2,50}$/;
    return nameRegex.test(name);
  }

  /**
   * Validate numeric input
   */
  static isNumeric(value) {
    return /^\d+$/.test(value);
  }

  /**
   * Validate code (6 digits)
   */
  static isValidCode(code) {
    return /^\d{6}$/.test(code);
  }

  /**
   * Clear form
   */
  static clearForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
      form.reset();
    }
  }

  /**
   * Get form data as object
   */
  static getFormData(formId) {
    const form = document.getElementById(formId);
    if (!form) return {};

    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => {
      data[key] = value;
    });
    return data;
  }

  /**
   * Disable form inputs
   */
  static disableForm(formId, disabled = true) {
    const form = document.getElementById(formId);
    if (!form) return;

    const inputs = form.querySelectorAll('input, button, select, textarea');
    inputs.forEach(input => {
      input.disabled = disabled;
    });
  }

  /**
   * Show/hide element
   */
  static toggleElement(elementId, show = true) {
    const element = document.getElementById(elementId);
    if (element) {
      element.style.display = show ? 'block' : 'none';
    }
  }

  /**
   * Redirect to URL
   */
  static redirect(url, delay = 1500) {
    setTimeout(() => {
      window.location.href = url;
    }, delay);
  }

  /**
   * Get URL parameter
   */
  static getUrlParameter(name) {
    const url = new URL(window.location);
    return url.searchParams.get(name);
  }

  /**
   * Show loading overlay
   */
  static showLoadingOverlay(show = true) {
    let overlay = document.getElementById('loadingOverlay');

    if (show) {
      if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.style.cssText = `
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(0, 0, 0, 0.5);
          display: flex;
          justify-content: center;
          align-items: center;
          z-index: 9999;
        `;

        const spinner = document.createElement('div');
        spinner.style.cssText = `
          border: 4px solid #f3f3f3;
          border-top: 4px solid #004A53;
          border-radius: 50%;
          width: 40px;
          height: 40px;
          animation: spin 1s linear infinite;
        `;

        overlay.appendChild(spinner);
        document.body.appendChild(overlay);

        // Add animation if not already in stylesheet
        if (!document.getElementById('spinnerStyle')) {
          const style = document.createElement('style');
          style.id = 'spinnerStyle';
          style.textContent = `
            @keyframes spin {
              0% { transform: rotate(0deg); }
              100% { transform: rotate(360deg); }
            }
          `;
          document.head.appendChild(style);
        }
      }
      overlay.style.display = 'flex';
    } else {
      if (overlay) {
        overlay.style.display = 'none';
      }
    }
  }

  /**
   * Copy to clipboard
   */
  static formatDate(dateString) {
            return new Date(dateString).toLocaleDateString("en-US", {
                month: "short",
                day: "numeric",
                year: "numeric"
            });
        }


  /**
   * Copy to clipboard
   */
  static copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
      this.showSuccess('Copied to clipboard!');
    }).catch(() => {
      this.showError('Failed to copy');
    });
  }
}



