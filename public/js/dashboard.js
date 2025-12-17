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
    this.highlightActivePage();
  }

  /**
   * Initialize logout functionality
   */
  static initLogout() {
    const logoutBtn = document.getElementById('logoutBtn');
    if (!logoutBtn) return;

    logoutBtn.addEventListener('click', async (e) => {
      e.preventDefault();

      // Show confirmation modal
      const confirmed = await window.confirmationModal.showLogoutConfirmation();
      if (!confirmed) {
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






    const navigateToProfile = () => {
      // Check if user is authenticated
      const token = localStorage.getItem('auth_token');
      const user = localStorage.getItem('auth_user');

      if (!token || !user) {
        // No token, redirect to login
        console.log('No authentication token found, redirecting to login...');
        window.location.href = '/login';
      } else {
        // Token exists, make API call to get user data with token
        const userData = JSON.parse(user);
        if (userData.role === 'student') {
          window.location.href = '/userprofile';
        } else {
          window.location.href = '/adminprofile';
        }

      }
    };
    // const navigateToProfile = () => {
    //   window.location.href = '/profiles';
    // };



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
        // Check if profile_photo is already a full URL (starts with /)
        if (user.profile_photo.startsWith('/')) {
          profileImage.src = user.profile_photo;
          console.log('Profile photo is a full URL:', user.profile_photo);
        } else {
          // Otherwise, add /storage/ prefix
          profileImage.src = `/storage/${user.profile_photo}`;
          console.log('Profile photo is a relative path, added /storage/ prefix:', profileImage.src);
        }
      } else {
        // Use default avatar if no profile photo
        profileImage.src = 'images/winner-round.png';
        console.log('No profile photo, using default avatar');
      }
    }
  }

  /**
   * Highlight the active page in the sidebar
   */
  static highlightActivePage() {
    // Get current page path
    const currentPath = window.location.pathname;

    // Get all sidebar navigation links
    const navLinks = document.querySelectorAll('.nav-item-link');

    // Remove active class from all links
    navLinks.forEach(link => {
      link.classList.remove('active');
    });

    // Add active class to the matching link
    navLinks.forEach(link => {
      const href = link.getAttribute('href');
      if (href && currentPath === href) {
        link.classList.add('active');
      }
    });
  }
}

export default DashboardModule;



