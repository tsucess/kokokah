/**
 * Badge Award Service
 * Handles badge award API calls with modal display and real-time updates
 */

class BadgeAwardService {
  /**
   * Award a badge to a user and show congratulation modal
   * @param {number} userId - User ID to award badge to
   * @param {number} badgeId - Badge ID to award
   * @returns {Promise}
   */
  static async awardBadgeWithModal(userId, badgeId) {
    try {
      const response = await fetch('/api/badges/award', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
          'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
        },
        body: JSON.stringify({
          user_id: userId,
          badge_id: badgeId
        })
      });

      const data = await response.json();

      if (data.success && data.data && data.data.badge) {
        // Show congratulation modal
        const badge = data.data.badge;
        if (window.BadgeCongratulationModal) {
          window.BadgeCongratulationModal.show({
            name: badge.name,
            description: badge.description,
            icon: badge.icon,
            icon_path: badge.icon_path,
            points: badge.points
          });
        }

        // Refresh user points and badges after a short delay
        setTimeout(() => {
          this.refreshUserPointsAndBadges();
        }, 500);
      }

      return data;
    } catch (error) {
      console.error('Error awarding badge:', error);
      throw error;
    }
  }

  /**
   * Refresh user points and badges in the UI
   */
  static async refreshUserPointsAndBadges() {
    try {
      // Refresh points
      if (window.PointsAndBadgesApiClient) {
        const pointsResponse = await window.PointsAndBadgesApiClient.getUserPoints();
        if (pointsResponse.success && pointsResponse.data) {
          const points = pointsResponse.data.points || 0;
          const pointsElements = document.querySelectorAll('[data-points]');
          pointsElements.forEach(el => {
            el.textContent = points.toLocaleString();
          });
        }

        // Refresh badges
        const badgesResponse = await window.PointsAndBadgesApiClient.getUserBadges();
        if (badgesResponse.success && badgesResponse.data) {
          const badgeCount = Array.isArray(badgesResponse.data)
            ? badgesResponse.data.length
            : (badgesResponse.data.data ? badgesResponse.data.data.length : 0);

          const badgeElements = document.querySelectorAll('[data-badges]');
          badgeElements.forEach(el => {
            el.textContent = badgeCount;
          });
        }
      }
    } catch (error) {
      console.error('Error refreshing points and badges:', error);
    }
  }

  /**
   * Check and award automatic badges for current user
   * @param {number} userId - User ID
   * @returns {Promise}
   */
  static async checkAndAwardAutomaticBadges(userId) {
    try {
      const response = await fetch(`/api/badges/check-automatic/${userId}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
          'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
        }
      });

      const data = await response.json();

      if (data.success && data.data && Array.isArray(data.data)) {
        // Show modal for each newly awarded badge with delay
        for (const badge of data.data) {
          if (window.BadgeCongratulationModal) {
            window.BadgeCongratulationModal.show({
              name: badge.name,
              description: badge.description,
              icon: badge.icon,
              icon_path: badge.icon_path,
              points: badge.points
            });
          }
          // Wait 4 seconds between badges
          await new Promise(resolve => setTimeout(resolve, 4000));
        }

        // Refresh UI after all badges shown
        this.refreshUserPointsAndBadges();
      }

      return data;
    } catch (error) {
      console.error('Error checking automatic badges:', error);
      throw error;
    }
  }
}

// Make available globally
window.BadgeAwardService = BadgeAwardService;

