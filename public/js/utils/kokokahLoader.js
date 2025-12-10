/**
 * Kokokah Loader
 * Displays an animated loader during page navigation and actions
 * Matches the style from editsubject.blade.php
 */

class KokokahLoader {
  constructor() {
    this.loaderElement = null;
    this.isVisible = false;
    this.hideTimeout = null;
    this.init();
  }

  /**
   * Initialize the loader
   */
  init() {
    this.createLoaderHTML();
    this.setupEventListeners();
  }

  /**
   * Create the loader HTML structure
   */
  createLoaderHTML() {
    // Check if loader already exists
    if (document.getElementById('kokokahLoader')) {
      this.loaderElement = document.getElementById('kokokahLoader');
      return;
    }

    const loaderHTML = `
      <div class="kokokah-loader-overlay hidden" id="kokokahLoader">
        <div class="kokokah-loader-container">
          <div class="kokokah-spinner"></div>
          <div class="kokokah-loader-text">
            Loading<span class="kokokah-loader-dots"></span>
          </div>
        </div>
      </div>
    `;

    document.body.insertAdjacentHTML('afterbegin', loaderHTML);
    this.loaderElement = document.getElementById('kokokahLoader');
  }

  /**
   * Setup event listeners for page navigation
   */
  setupEventListeners() {
    // Show loader on link clicks
    document.addEventListener('click', (e) => {
      const link = e.target.closest('a');
      if (link && !link.hasAttribute('data-no-loader')) {
        const href = link.getAttribute('href');
        // Only show loader for internal navigation
        if (href && !href.startsWith('http') && !href.startsWith('mailto:') && !href.startsWith('tel:')) {
          this.show();
        }
      }
    });

    // Show loader on form submissions
    document.addEventListener('submit', (e) => {
      const form = e.target;
      if (!form.hasAttribute('data-no-loader')) {
        this.show();
      }
    });

    // Hide loader when page is fully loaded
    window.addEventListener('load', () => {
      this.hide();
    });

    // Hide loader on popstate (back/forward navigation)
    window.addEventListener('popstate', () => {
      this.hide();
    });
  }

  /**
   * Show the loader
   */
  show() {
    if (this.isVisible) return;

    this.isVisible = true;

    if (this.loaderElement) {
      this.loaderElement.classList.remove('hidden');
    }

    // Clear any pending hide timeout
    if (this.hideTimeout) {
      clearTimeout(this.hideTimeout);
      this.hideTimeout = null;
    }
  }

  /**
   * Hide the loader
   */
  hide() {
    if (!this.isVisible) return;

    // Clear any pending timeout
    if (this.hideTimeout) {
      clearTimeout(this.hideTimeout);
    }

    // Hide with transition
    this.hideTimeout = setTimeout(() => {
      if (this.loaderElement) {
        this.loaderElement.classList.add('hidden');
        this.isVisible = false;
      }
    }, 300);
  }

  /**
   * Force hide the loader immediately
   */
  forceHide() {
    if (this.hideTimeout) {
      clearTimeout(this.hideTimeout);
      this.hideTimeout = null;
    }

    if (this.loaderElement) {
      this.loaderElement.classList.add('hidden');
      this.isVisible = false;
    }
  }

  /**
   * Show loader for a specific action
   * @param {number} duration - Duration to show loader in milliseconds
   */
  showForAction(duration = 1000) {
    this.show();
    setTimeout(() => {
      this.hide();
    }, duration);
  }
}

// Initialize loader when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    window.kokokahLoader = new KokokahLoader();
  });
} else {
  window.kokokahLoader = new KokokahLoader();
}

