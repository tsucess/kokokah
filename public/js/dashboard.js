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
      window.location.href = '/profiles';
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

    // Update first name in dashboard (by class selector)
    const firstNameElements = document.querySelectorAll('.first_name');
    if (firstNameElements.length > 0 && user.first_name) {
      firstNameElements.forEach(el => {
        el.textContent = user.first_name;
      });
    }

    // Update role in dashboard (by class selector)
    const roleElements = document.querySelectorAll('.role');
    if (roleElements.length > 0 && user.role) {
      roleElements.forEach(el => {
        el.textContent = `(${user.role.charAt(0).toUpperCase() + user.role.slice(1)})`;
      });
    }

    // Update user role
    const userRole = document.getElementById('userRole');
    if (userRole && user.role) {
      const roleText = user.role.charAt(0).toUpperCase() + user.role.slice(1);
      userRole.textContent = roleText;
    }

    // Update profile image if available
    const profileImage = document.getElementById('profileImage');
    if (profileImage) {
      if (user.profile_photo) {
        // Use storage URL for profile photos
        profileImage.src = `/storage/${user.profile_photo}`;
      } else {
        // Use default avatar if no profile photo
        profileImage.src = 'images/winner-round.png';
      }
    }
  }
}

export default DashboardModule;

