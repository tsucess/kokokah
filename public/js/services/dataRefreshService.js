/**
 * Global Data Refresh Service
 * Handles automatic data updates across the application after successful actions
 * Provides centralized event-driven data refresh mechanism
 */

class DataRefreshService {
  // Event types that trigger data refreshes
  static EVENTS = {
    POINTS_CONVERTED: 'points_converted',
    COURSE_COMPLETED: 'course_completed',
    COURSE_ENROLLED: 'course_enrolled',
    WALLET_UPDATED: 'wallet_updated',
    TRANSACTION_CREATED: 'transaction_created',
    ACTIVITY_LOGGED: 'activity_logged',
    BADGE_EARNED: 'badge_earned',
    QUIZ_COMPLETED: 'quiz_completed',
    LESSON_COMPLETED: 'lesson_completed'
  };

  // Listeners registry
  static listeners = {};

  /**
   * Register a listener for a specific event
   * @param {string} eventType - Event type from EVENTS
   * @param {Function} callback - Callback function to execute
   * @param {string} listenerId - Unique identifier for this listener
   */
  static on(eventType, callback, listenerId = null) {
    if (!this.listeners[eventType]) {
      this.listeners[eventType] = [];
    }
    
    const listener = {
      id: listenerId || `listener_${Date.now()}_${Math.random()}`,
      callback: callback
    };
    
    this.listeners[eventType].push(listener);
    return listener.id;
  }

  /**
   * Remove a listener
   * @param {string} eventType - Event type
   * @param {string} listenerId - Listener ID to remove
   */
  static off(eventType, listenerId) {
    if (this.listeners[eventType]) {
      this.listeners[eventType] = this.listeners[eventType].filter(
        l => l.id !== listenerId
      );
    }
  }

  /**
   * Emit an event and trigger all registered listeners
   * @param {string} eventType - Event type
   * @param {object} data - Data to pass to listeners
   */
  static async emit(eventType, data = {}) {
    console.log(`[DataRefreshService] Emitting event: ${eventType}`, data);
    
    if (this.listeners[eventType]) {
      for (const listener of this.listeners[eventType]) {
        try {
          await listener.callback(data);
        } catch (error) {
          console.error(`[DataRefreshService] Error in listener:`, error);
        }
      }
    }
  }

  /**
   * Refresh user points and badges
   */
  static async refreshUserPoints() {
    try {
      const response = await PointsAndBadgesApiClient.getUserPoints();
      if (response.success) {
        // Update all points displays
        const pointsElements = document.querySelectorAll('[data-points]');
        pointsElements.forEach(el => {
          el.textContent = (response.data.points || 0).toLocaleString();
        });
        return response.data;
      }
    } catch (error) {
      console.error('[DataRefreshService] Error refreshing points:', error);
    }
  }

  /**
   * Refresh wallet balance
   */
  static async refreshWalletBalance() {
    try {
      const response = await WalletApiClient.getWallet();
      if (response.success) {
        // Update wallet balance displays
        const balanceElements = document.querySelectorAll('[data-wallet-balance]');
        balanceElements.forEach(el => {
          el.textContent = formatNGN(response.data.balance || 0);
        });
        return response.data;
      }
    } catch (error) {
      console.error('[DataRefreshService] Error refreshing wallet:', error);
    }
  }

  /**
   * Refresh wallet transactions
   */
  static async refreshWalletTransactions() {
    try {
      const response = await WalletApiClient.getTransactions({ per_page: 50 });
      if (response.success) {
        return response.data;
      }
    } catch (error) {
      console.error('[DataRefreshService] Error refreshing transactions:', error);
    }
  }

  /**
   * Refresh user badges
   */
  static async refreshUserBadges() {
    try {
      const response = await PointsAndBadgesApiClient.getUserBadges();
      if (response.success) {
        return response.data;
      }
    } catch (error) {
      console.error('[DataRefreshService] Error refreshing badges:', error);
    }
  }

  /**
   * Refresh user activity
   */
  static async refreshUserActivity() {
    try {
      const response = await AdminApiClient.getUserActivity({ page: 1, per_page: 100 });
      if (response.success) {
        return response.data;
      }
    } catch (error) {
      console.error('[DataRefreshService] Error refreshing activity:', error);
    }
  }

  /**
   * Refresh all user data
   */
  static async refreshAllUserData() {
    try {
      await Promise.all([
        this.refreshUserPoints(),
        this.refreshWalletBalance(),
        this.refreshUserBadges()
      ]);
    } catch (error) {
      console.error('[DataRefreshService] Error refreshing all data:', error);
    }
  }
}

