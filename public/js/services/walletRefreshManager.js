/**
 * Wallet Refresh Manager
 * Automatically refreshes wallet data when transactions occur
 */

class WalletRefreshManager {
  static initialized = false;
  static refreshInterval = null;

  /**
   * Initialize wallet refresh listeners
   */
  static init() {
    if (this.initialized) return;
    this.initialized = true;

    // Listen for transaction created
    DataRefreshService.on(
      DataRefreshService.EVENTS.TRANSACTION_CREATED,
      () => this.onTransactionCreated(),
      'wallet_transaction_created'
    );

    // Listen for wallet updated
    DataRefreshService.on(
      DataRefreshService.EVENTS.WALLET_UPDATED,
      () => this.onWalletUpdated(),
      'wallet_wallet_updated'
    );

    // Listen for points converted
    DataRefreshService.on(
      DataRefreshService.EVENTS.POINTS_CONVERTED,
      () => this.onPointsConverted(),
      'wallet_points_converted'
    );

    console.log('[WalletRefreshManager] Initialized');
  }

  /**
   * Handle transaction created event
   */
  static async onTransactionCreated() {
    console.log('[WalletRefreshManager] Transaction created, refreshing...');
    await DataRefreshService.refreshWalletBalance();
    await DataRefreshService.refreshWalletTransactions();
    
    // Trigger custom event for wallet page
    window.dispatchEvent(new CustomEvent('walletTransactionsUpdated'));
  }

  /**
   * Handle wallet updated event
   */
  static async onWalletUpdated() {
    console.log('[WalletRefreshManager] Wallet updated, refreshing...');
    await DataRefreshService.refreshWalletBalance();
    await DataRefreshService.refreshWalletTransactions();
    
    // Trigger custom event for wallet page
    window.dispatchEvent(new CustomEvent('walletUpdated'));
  }

  /**
   * Handle points converted event
   */
  static async onPointsConverted() {
    console.log('[WalletRefreshManager] Points converted, refreshing...');
    await DataRefreshService.refreshWalletBalance();
    await DataRefreshService.refreshWalletTransactions();
    
    // Trigger custom event for wallet page
    window.dispatchEvent(new CustomEvent('walletUpdated'));
  }

  /**
   * Refresh wallet data on demand
   */
  static async refreshWalletData() {
    await DataRefreshService.refreshWalletBalance();
    await DataRefreshService.refreshWalletTransactions();
  }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  WalletRefreshManager.init();
});

