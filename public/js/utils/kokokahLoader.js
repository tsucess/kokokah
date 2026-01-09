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
    this.pageLoadStartTime = Date.now();
    this.safetyCheckInterval = null;
    this.init();
  }

  /**
   * Initialize the loader
   */
  init() {
    this.createLoaderHTML();
    this.setupEventListeners();
    // Don't show loader on initial page load - only on navigation/actions
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
    this.isVisible = false;
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
        // Only show loader for internal navigation (exclude hash links, external links, and special protocols)
        if (href && !href.startsWith('http') && !href.startsWith('mailto:') && !href.startsWith('tel:') && !href.startsWith('#') && href !== 'javascript:void(0)') {
          this.show();
        }
      }
    });

    // Show loader on form submissions ONLY for traditional forms (not AJAX)
    document.addEventListener('submit', (e) => {
      const form = e.target;
      // Skip if form has data-no-loader or data-ajax attribute
      if (!form.hasAttribute('data-no-loader') && !form.hasAttribute('data-ajax')) {
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

    // Auto-hide loader after 10 seconds if it's still visible (safety mechanism)
    this.safetyCheckInterval = setInterval(() => {
      if (this.isVisible) {
        const elapsedTime = Date.now() - this.pageLoadStartTime;
        // If loader has been visible for more than 10 seconds, force hide it
        if (elapsedTime > 10000) {
          console.warn('Loader visible for too long, force hiding');
          this.forceHide();
        }
      }
    }, 5000);
  }

  /**
   * Show the loader
   */
  show() {
    // If already visible, don't show again (prevents flashing)
    if (this.isVisible) return;

    this.isVisible = true;
    this.pageLoadStartTime = Date.now();

    if (this.loaderElement) {
      // Remove hidden class to show the loader
      this.loaderElement.classList.remove('hidden');
      // Ensure visibility is set
      this.loaderElement.style.opacity = '1';
      this.loaderElement.style.visibility = 'visible';
      this.loaderElement.style.pointerEvents = 'auto';
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

    // Ensure minimum display time of 500ms to prevent flashing
    const elapsedTime = Date.now() - this.pageLoadStartTime;
    const minDisplayTime = 500;
    const delayBeforeHide = Math.max(0, minDisplayTime - elapsedTime);

    // Hide with transition
    this.hideTimeout = setTimeout(() => {
      if (this.loaderElement) {
        this.loaderElement.classList.add('hidden');
        this.loaderElement.style.opacity = '0';
        this.loaderElement.style.visibility = 'hidden';
        this.loaderElement.style.pointerEvents = 'none';
        this.isVisible = false;
      }
    }, delayBeforeHide);
  }

  /**
   * Destroy the loader and clean up resources
   */
  destroy() {
    // Clear all timeouts and intervals
    if (this.hideTimeout) {
      clearTimeout(this.hideTimeout);
      this.hideTimeout = null;
    }
    if (this.safetyCheckInterval) {
      clearInterval(this.safetyCheckInterval);
      this.safetyCheckInterval = null;
    }
    this.isVisible = false;
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

// Initialize loader when DOM is ready - only if not already initialized
if (!window.kokokahLoader) {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      if (!window.kokokahLoader) {
        window.kokokahLoader = new KokokahLoader();
      }
    });
  } else {
    window.kokokahLoader = new KokokahLoader();
  }
}

// Clean up loader on page unload to prevent memory leaks
window.addEventListener('beforeunload', () => {
  if (window.kokokahLoader && window.kokokahLoader.destroy) {
    window.kokokahLoader.destroy();
  }
});

