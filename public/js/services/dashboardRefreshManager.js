/**
 * Dashboard Refresh Manager
 * Automatically refreshes dashboard data when events occur
 */

class DashboardRefreshManager {
  static initialized = false;

  /**
   * Initialize dashboard refresh listeners
   */
  static init() {
    if (this.initialized) return;
    this.initialized = true;

    // Listen for points conversion
    DataRefreshService.on(
      DataRefreshService.EVENTS.POINTS_CONVERTED,
      () => this.onPointsConverted(),
      'dashboard_points_converted'
    );

    // Listen for course completion
    DataRefreshService.on(
      DataRefreshService.EVENTS.COURSE_COMPLETED,
      () => this.onCourseCompleted(),
      'dashboard_course_completed'
    );

    // Listen for wallet updates
    DataRefreshService.on(
      DataRefreshService.EVENTS.WALLET_UPDATED,
      () => this.onWalletUpdated(),
      'dashboard_wallet_updated'
    );

    // Listen for badge earned
    DataRefreshService.on(
      DataRefreshService.EVENTS.BADGE_EARNED,
      () => this.onBadgeEarned(),
      'dashboard_badge_earned'
    );

    console.log('[DashboardRefreshManager] Initialized');
  }

  /**
   * Handle points converted event
   */
  static async onPointsConverted() {
    console.log('[DashboardRefreshManager] Points converted, refreshing...');
    await DataRefreshService.refreshUserPoints();
    await DataRefreshService.refreshWalletBalance();
  }

  /**
   * Handle course completed event
   */
  static async onCourseCompleted() {
    console.log('[DashboardRefreshManager] Course completed, refreshing...');
    await DataRefreshService.refreshUserPoints();
    await DataRefreshService.refreshUserBadges();
  }

  /**
   * Handle wallet updated event
   */
  static async onWalletUpdated() {
    console.log('[DashboardRefreshManager] Wallet updated, refreshing...');
    await DataRefreshService.refreshWalletBalance();
    await DataRefreshService.refreshWalletTransactions();
  }

  /**
   * Handle badge earned event
   */
  static async onBadgeEarned() {
    console.log('[DashboardRefreshManager] Badge earned, refreshing...');
    await DataRefreshService.refreshUserBadges();
  }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  DashboardRefreshManager.init();
});

