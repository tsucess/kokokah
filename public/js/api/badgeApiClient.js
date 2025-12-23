/**
 * Badge API Client
 * Handles all badge and leaderboard related API calls
 */

import BaseApiClient from './baseApiClient.js';

class BadgeApiClient extends BaseApiClient {
  /**
   * Get badge leaderboard
   * @param {string} period - 'all_time', 'this_month', 'this_year'
   * @param {string} category - Optional badge category filter
   * @returns {Promise}
   */
  static async getLeaderboard(period = 'all_time', category = null) {
    try {
      let endpoint = `/badges/leaderboard?period=${period}`;
      if (category) {
        endpoint += `&category=${category}`;
      }
      return await this.get(endpoint);
    } catch (error) {
      console.error('Error fetching leaderboard:', error);
      return {
        success: false,
        message: 'Failed to fetch leaderboard',
        data: []
      };
    }
  }

  /**
   * Get top 3 leaderboard winners
   * @returns {Promise}
   */
  static async getTopWinners() {
    try {
      return await this.get('/badges/leaderboard?period=all_time');
    } catch (error) {
      console.error('Error fetching top winners:', error);
      return {
        success: false,
        message: 'Failed to fetch top winners',
        data: []
      };
    }
  }

  /**
   * Get user badges
   * @param {number} userId - User ID
   * @returns {Promise}
   */
  static async getUserBadges(userId) {
    try {
      return await this.get(`/users/${userId}/badges`);
    } catch (error) {
      console.error('Error fetching user badges:', error);
      return {
        success: false,
        message: 'Failed to fetch user badges',
        data: []
      };
    }
  }

  /**
   * Get all badges
   * @returns {Promise}
   */
  static async getAllBadges() {
    try {
      return await this.get('/badges');
    } catch (error) {
      console.error('Error fetching badges:', error);
      return {
        success: false,
        message: 'Failed to fetch badges',
        data: []
      };
    }
  }

  /**
   * Get badge details
   * @param {number} badgeId - Badge ID
   * @returns {Promise}
   */
  static async getBadgeDetails(badgeId) {
    try {
      return await this.get(`/badges/${badgeId}`);
    } catch (error) {
      console.error('Error fetching badge details:', error);
      return {
        success: false,
        message: 'Failed to fetch badge details',
        data: null
      };
    }
  }
}

export default BadgeApiClient;

