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
    this.visibilityStartTime = null; // Track when loader became visible
    this.safetyCheckInterval = null;
    this.isInitialPageLoad = true; // Track if this is the initial page load
    this.init();
  }

  /**
   * Initialize the loader
   */
  init() {
    this.createLoaderHTML();
    this.setupEventListeners();
    // Hide loader after initial page load completes
    this.hideInitialLoader();
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
      <div class="kokokah-loader-overlay" id="kokokahLoader" style="opacity: 1; visibility: visible; pointer-events: auto;">
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
    this.isVisible = true; // Loader starts visible
  }

  /**
   * Hide the initial page load loader
   */
  hideInitialLoader() {
    // Wait for page to be fully loaded (both DOM and resources)
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => {
        this.hideInitialLoaderAfterDelay();
      });
    } else if (document.readyState === 'interactive') {
      // If DOM is interactive but not complete, wait for load event
      window.addEventListener('load', () => {
        this.hideInitialLoaderAfterDelay();
      });
    } else {
      // Page is already fully loaded
      this.hideInitialLoaderAfterDelay();
    }
  }

  /**
   * Hide initial loader after a small delay to ensure page is rendered
   */
  hideInitialLoaderAfterDelay() {
    // Add a delay to ensure all content is rendered and CSS is applied
    // Use a longer delay (800ms) to ensure page is fully visible
    setTimeout(() => {
      if (this.isInitialPageLoad && this.loaderElement) {
        this.loaderElement.classList.add('hidden');
        this.loaderElement.style.opacity = '0';
        this.loaderElement.style.visibility = 'hidden';
        this.loaderElement.style.pointerEvents = 'none';
        this.isVisible = false;
        this.isInitialPageLoad = false;
      }
    }, 800);
  }

  /**
   * Setup event listeners for page navigation
   */
  setupEventListeners() {
    // Show loader on link clicks
    document.addEventListener('click', (e) => {
      // Don't process clicks from within iframes or elements with data-no-loader
      if (e.target.closest('[data-no-loader]')) {
        return;
      }

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
  }

  /**
   * Show the loader
   */
  show() {
    // If already visible, don't show again (prevents flashing)
    if (this.isVisible) return;

    this.isVisible = true;
    this.visibilityStartTime = Date.now(); // Track when loader became visible
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

    // Start safety check interval when loader is shown
    this.startSafetyCheck();
  }

  /**
   * Start the safety check interval
   */
  startSafetyCheck() {
    // Clear any existing interval first
    if (this.safetyCheckInterval) {
      clearInterval(this.safetyCheckInterval);
    }

    // Start new interval to check if loader is stuck
    this.safetyCheckInterval = setInterval(() => {
      if (this.isVisible && this.visibilityStartTime) {
        const elapsedTime = Date.now() - this.visibilityStartTime;
        // If loader has been visible for more than 10 seconds, force hide it
        if (elapsedTime > 10000) {
          this.forceHide();
          this.stopSafetyCheck();
        }
      }
    }, 5000);
  }

  /**
   * Stop the safety check interval
   */
  stopSafetyCheck() {
    if (this.safetyCheckInterval) {
      clearInterval(this.safetyCheckInterval);
      this.safetyCheckInterval = null;
    }
  }

  /**
   * Hide the loader
   */
  hide() {
    if (!this.isVisible) return;

    // Stop the safety check when hiding
    this.stopSafetyCheck();

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
        this.visibilityStartTime = null; // Reset visibility start time
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
    // Stop the safety check
    this.stopSafetyCheck();

    if (this.hideTimeout) {
      clearTimeout(this.hideTimeout);
      this.hideTimeout = null;
    }

    if (this.loaderElement) {
      this.loaderElement.classList.add('hidden');
      this.loaderElement.style.opacity = '0';
      this.loaderElement.style.visibility = 'hidden';
      this.loaderElement.style.pointerEvents = 'none';
      this.isVisible = false;
      this.visibilityStartTime = null; // Reset visibility start time
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

