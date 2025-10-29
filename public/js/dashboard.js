/**
 * Dashboard Module
 * Handles dashboard interactions like logout and profile navigation
 */

import AuthApiClient from './api/authClient.js';
import UIHelpers from './utils/uiHelpers.js';

class DashboardModule {
  /**
   * Initialize dashboard functionality
   */
  static init() {
    this.initLogout();
    this.initProfileNavigation();
    this.initTooltips();
    this.loadUserProfile();
  }

  /**
   * Initialize logout functionality
   */
  static initLogout() {
    const logoutBtn = document.getElementById('logoutBtn');
    if (!logoutBtn) return;

    logoutBtn.addEventListener('click', async (e) => {
      e.preventDefault();

      // Show confirmation dialog
      if (!confirm('Are you sure you want to logout?')) {
        return;
      }

      // Show loading state
      UIHelpers.showLoadingOverlay(true);

      // Call logout API
      const result = await AuthApiClient.logout();

      UIHelpers.showLoadingOverlay(false);

      if (result.success) {
        UIHelpers.showSuccess('Logged out successfully! Redirecting...');
        // Redirect to login page after 1.5 seconds
        UIHelpers.redirect('/login', 1500);
      } else {
        UIHelpers.showError('Logout failed. Please try again.');
      }
    });
  }

  /**
   * Initialize profile navigation
   */
  static initProfileNavigation() {
    const profileSection = document.getElementById('profileSection');
    const profileImage = document.getElementById('profileImage');
    const profileInfo = document.getElementById('profileInfo');

    if (!profileSection) return;

    // Make profile section clickable
    const navigateToProfile = () => {
      window.location.href = '/profile';
    };

    if (profileSection) {
      profileSection.addEventListener('click', (e) => {
        // Don't navigate if clicking logout button
        if (e.target.closest('.logout')) {
          return;
        }
        navigateToProfile();
      });
    }

    if (profileImage) {
      profileImage.addEventListener('click', (e) => {
        e.stopPropagation();
        navigateToProfile();
      });
    }

    if (profileInfo) {
      profileInfo.addEventListener('click', (e) => {
        e.stopPropagation();
        navigateToProfile();
      });
    }
  }

  /**
   * Initialize Bootstrap tooltips
   */
  static initTooltips() {
    const profileInfo = document.getElementById('profileInfo');
    const profileImage = document.getElementById('profileSection');
    if (!profileInfo) return;
    if (!profileImage) return;

    // Initialize Bootstrap tooltip
    const tooltip = new bootstrap.Tooltip(profileImage, {
      trigger: 'hover'
    });
    const tooltips = new bootstrap.Tooltip(profileInfo, {
      trigger: 'hover'
    });
  }

  /**
   * Load and display user profile information
   */
  static loadUserProfile() {
    const user = AuthApiClient.getUser();

    if (!user) return;

    // Update user name
    const userName = document.getElementById('userName');
    if (userName && user.first_name && user.last_name) {
      userName.textContent = `${user.first_name} ${user.last_name}`;
    }

    // Update user role
    const userRole = document.getElementById('userRole');
    if (userRole && user.role) {
      const roleText = user.role.charAt(0).toUpperCase() + user.role.slice(1);
      userRole.textContent = roleText;
    }

    // Update profile image if available
    const profileImage = document.getElementById('profileImage');
    if (profileImage && user.profile_photo) {
      profileImage.src = user.profile_photo;
    }
  }
}

export default DashboardModule;

