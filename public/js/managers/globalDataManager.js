/**
 * Global Data Manager
 * Centralized state management for user data
 * Handles automatic refresh and broadcasting of data changes
 */

class GlobalDataManager {
  constructor() {
    this.userData = null;
    this.walletData = null;
    this.pointsData = null;
    this.listeners = {};
    this.refreshIntervals = {};
    this.isRefreshing = false;
  }

  /**
   * Initialize the data manager
   */
  async init() {
    console.log('Initializing GlobalDataManager...');
    await this.loadAllData();
    this.setupEventListeners();
  }

  /**
   * Load all user data
   */
  async loadAllData() {
    try {
      this.isRefreshing = true;
      
      // Load user profile
      if (window.AuthApiClient) {
        const userResponse = await AuthApiClient.getCurrentUser();
        if (userResponse.success) {
          this.userData = userResponse.data;
          this.notifyListeners('user', this.userData);
        }
      }

      // Load wallet data
      if (window.WalletApiClient) {
        const walletResponse = await WalletApiClient.getWallet();
        if (walletResponse.success) {
          this.walletData = walletResponse.data;
          this.notifyListeners('wallet', this.walletData);
        }
      }

      // Load points data
      if (window.PointsAndBadgesApiClient) {
        const pointsResponse = await PointsAndBadgesApiClient.getUserPoints();
        if (pointsResponse.success) {
          this.pointsData = pointsResponse.data;
          this.notifyListeners('points', this.pointsData);
        }
      }

      this.isRefreshing = false;
    } catch (error) {
      console.error('Error loading data:', error);
      this.isRefreshing = false;
    }
  }

  /**
   * Refresh specific data type
   */
  async refreshData(type) {
    if (this.isRefreshing) return;

    try {
      this.isRefreshing = true;

      switch (type) {
        case 'user':
          if (window.AuthApiClient) {
            const response = await AuthApiClient.getCurrentUser();
            if (response.success) {
              this.userData = response.data;
              this.notifyListeners('user', this.userData);
            }
          }
          break;

        case 'wallet':
          if (window.WalletApiClient) {
            const response = await WalletApiClient.getWallet();
            if (response.success) {
              this.walletData = response.data;
              this.notifyListeners('wallet', this.walletData);
            }
          }
          break;

        case 'points':
          if (window.PointsAndBadgesApiClient) {
            const response = await PointsAndBadgesApiClient.getUserPoints();
            if (response.success) {
              this.pointsData = response.data;
              this.notifyListeners('points', this.pointsData);
            }
          }
          break;

        case 'all':
          await this.loadAllData();
          break;
      }

      this.isRefreshing = false;
    } catch (error) {
      console.error(`Error refreshing ${type}:`, error);
      this.isRefreshing = false;
    }
  }

  /**
   * Subscribe to data changes
   */
  subscribe(type, callback) {
    if (!this.listeners[type]) {
      this.listeners[type] = [];
    }
    this.listeners[type].push(callback);
    
    // Return unsubscribe function
    return () => {
      this.listeners[type] = this.listeners[type].filter(cb => cb !== callback);
    };
  }

  /**
   * Notify all listeners of data change
   */
  notifyListeners(type, data) {
    if (this.listeners[type]) {
      this.listeners[type].forEach(callback => {
        try {
          callback(data);
        } catch (error) {
          console.error('Error in listener callback:', error);
        }
      });
    }
  }

  /**
   * Setup event listeners
   */
  setupEventListeners() {
    // Listen for custom events
    document.addEventListener('dataRefreshNeeded', (e) => {
      this.refreshData(e.detail.type || 'all');
    });
  }

  /**
   * Get current data
   */
  getData(type) {
    switch (type) {
      case 'user': return this.userData;
      case 'wallet': return this.walletData;
      case 'points': return this.pointsData;
      default: return { user: this.userData, wallet: this.walletData, points: this.pointsData };
    }
  }
}

// Create global instance
window.GlobalDataManager = new GlobalDataManager();

// Initialize when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    window.GlobalDataManager.init();
  });
} else {
  window.GlobalDataManager.init();
}

