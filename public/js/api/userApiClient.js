/**
 * User API Client
 * Handles all user profile and account-related API calls
 * Extends BaseApiClient for common functionality
 */

class UserApiClient extends BaseApiClient {
    /**
     * Get current user profile
     * @returns {Promise<Object>} Response with user profile data
     */
    static async getProfile() {
        return this.get('/users/profile');
    }

    /**
     * Update user profile
     * @param {Object} data - Profile data to update
     * @returns {Promise<Object>} Response with updated profile
     */
    static async updateProfile(data) {
        return this.put('/users/profile', data);
    }

    /**
     * Change user password
     * @param {string} currentPassword - Current password
     * @param {string} newPassword - New password
     * @param {string} confirmPassword - Password confirmation
     * @returns {Promise<Object>} Response with status
     */
    static async changePassword(currentPassword, newPassword, confirmPassword) {
        return this.post('/users/change-password', {
            current_password: currentPassword,
            new_password: newPassword,
            new_password_confirmation: confirmPassword
        });
    }

    /**
     * Get user dashboard data
     * @returns {Promise<Object>} Response with dashboard data
     */
    static async getDashboard() {
        return this.get('/users/dashboard');
    }

    /**
     * Get user achievements
     * @returns {Promise<Object>} Response with achievements
     */
    static async getAchievements() {
        return this.get('/users/achievements');
    }

    /**
     * Get user learning statistics
     * @returns {Promise<Object>} Response with learning stats
     */
    static async getLearningStats() {
        return this.get('/users/learning-stats');
    }

    /**
     * Update user preferences
     * @param {Object} preferences - Preference settings
     * @returns {Promise<Object>} Response with updated preferences
     */
    static async updatePreferences(preferences) {
        return this.put('/users/preferences', preferences);
    }

    /**
     * Get user notifications
     * @param {Object} filters - Filter options (page, per_page)
     * @returns {Promise<Object>} Response with notifications
     */
    static async getNotifications(filters = {}) {
        const params = new URLSearchParams();
        if (filters.page) params.append('page', filters.page);
        if (filters.per_page) params.append('per_page', filters.per_page);

        const queryString = params.toString();
        const endpoint = queryString ? `/users/notifications?${queryString}` : '/users/notifications';
        return this.get(endpoint);
    }

    /**
     * Mark notifications as read
     * @param {Array} notificationIds - Array of notification IDs to mark as read
     * @returns {Promise<Object>} Response with status
     */
    static async markNotificationsRead(notificationIds) {
        return this.post('/users/notifications/read', {
            notification_ids: notificationIds
        });
    }

    /**
     * Delete user account
     * @returns {Promise<Object>} Response with status
     */
    static async deleteAccount() {
        return this.delete('/users/account');
    }
}

// Make available globally
window.UserApiClient = UserApiClient;




