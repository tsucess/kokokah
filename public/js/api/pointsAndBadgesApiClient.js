/**
 * Points and Badges API Client
 * Handles all points and badges related API calls
 */

class PointsAndBadgesApiClient extends BaseApiClient {
  /**
   * Get user's current points and level
   * @returns {Promise}
   */
  static async getUserPoints() {
    try {
      return await this.get('/points-badges/points');
    } catch (error) {
      return {
        success: false,
        message: 'Failed to fetch user points',
        data: {
          points: 0,
          level: 'Amateur',
          next_level_points: 100,
          progress_to_next_level: 0
        }
      };
    }
  }

  /**
   * Get user's points history
   * @param {number} page - Page number for pagination
   * @param {number} perPage - Items per page
   * @returns {Promise}
   */
  static async getPointsHistory(page = 1, perPage = 10) {
    try {
      return await this.get(`/points-badges/points/history?page=${page}&per_page=${perPage}`);
    } catch (error) {
      return {
        success: false,
        message: 'Failed to fetch points history',
        data: []
      };
    }
  }

  /**
   * Get user's badges
   * @param {string} category - Optional category filter
   * @returns {Promise}
   */
  static async getUserBadges(category = null) {
    try {
      let endpoint = '/points-badges/badges';
      if (category) {
        endpoint += `?category=${category}`;
      }
      return await this.get(endpoint);
    } catch (error) {
      return {
        success: false,
        message: 'Failed to fetch user badges',
        data: []
      };
    }
  }

  /**
   * Get specific badge details
   * @param {number} badgeId - Badge ID
   * @returns {Promise}
   */
  static async getBadgeDetails(badgeId) {
    try {
      return await this.get(`/points-badges/badges/${badgeId}`);
    } catch (error) {
      return {
        success: false,
        message: 'Failed to fetch badge details',
        data: null
      };
    }
  }

  /**
   * Get badge statistics
   * @returns {Promise}
   */
  static async getBadgeStats() {
    try {
      return await this.get('/points-badges/badges/stats');
    } catch (error) {
      return {
        success: false,
        message: 'Failed to fetch badge stats',
        data: {
          total_badges: 0,
          badges_by_category: {},
          total_badge_points: 0,
          recent_badges: []
        }
      };
    }
  }

  /**
   * Get global leaderboard
   * @param {number} limit - Number of top users to fetch
   * @returns {Promise}
   */
  static async getLeaderboard(limit = 10) {
    try {
      return await this.get(`/points-badges/leaderboard?limit=${limit}`);
    } catch (error) {
      return {
        success: false,
        message: 'Failed to fetch leaderboard',
        data: []
      };
    }
  }
}

// Make available globally
window.PointsAndBadgesApiClient = PointsAndBadgesApiClient;
