/**
 * Activity Refresh Manager
 * Automatically refreshes user activity when events occur
 */

class ActivityRefreshManager {
  static initialized = false;

  /**
   * Initialize activity refresh listeners
   */
  static init() {
    if (this.initialized) return;
    this.initialized = true;

    // Listen for all activity-related events
    const events = [
      DataRefreshService.EVENTS.POINTS_CONVERTED,
      DataRefreshService.EVENTS.COURSE_COMPLETED,
      DataRefreshService.EVENTS.COURSE_ENROLLED,
      DataRefreshService.EVENTS.WALLET_UPDATED,
      DataRefreshService.EVENTS.TRANSACTION_CREATED,
      DataRefreshService.EVENTS.BADGE_EARNED,
      DataRefreshService.EVENTS.QUIZ_COMPLETED,
      DataRefreshService.EVENTS.LESSON_COMPLETED
    ];

    events.forEach(event => {
      DataRefreshService.on(
        event,
        () => this.onActivityEvent(event),
        `activity_${event}`
      );
    });

    console.log('[ActivityRefreshManager] Initialized');
  }

  /**
   * Handle any activity event
   */
  static async onActivityEvent(eventType) {
    console.log(`[ActivityRefreshManager] Activity event: ${eventType}, refreshing...`);
    await this.refreshUserActivity();
  }

  /**
   * Refresh user activity
   */
  static async refreshUserActivity() {
    try {
      const response = await DataRefreshService.refreshUserActivity();
      
      // Trigger custom event for activity page
      window.dispatchEvent(new CustomEvent('userActivityUpdated', {
        detail: response
      }));
    } catch (error) {
      console.error('[ActivityRefreshManager] Error refreshing activity:', error);
    }
  }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  ActivityRefreshManager.init();
});

