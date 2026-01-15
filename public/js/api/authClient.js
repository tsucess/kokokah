/**
 * Authentication API Client
 * Handles all authentication-related API calls
 * Extends BaseApiClient for common functionality
 */

class AuthApiClient extends BaseApiClient {
  /**
   * Register a new user
   */
  static async register(firstName, lastName, email, password, role = 'student') {
    const response = await this.post('/register', {
      first_name: firstName,
      last_name: lastName,
      email: email,
      password: password,
      password_confirmation: password,
      role: role
    });

    if (response.success) {
      const token = response.data.token || response.data.data?.token;
      const user = response.data.user || response.data.data?.user;
      this.setToken(token);
      this.setUser(user);
      return { success: true, data: { token, user } };
    }
    return response;
  }

  /**
   * Login user
   */
  static async login(email, password) {
    const response = await this.post('/login', {
      email: email,
      password: password
    });

    if (response.success) {
      // Handle different response structures
      // API returns: { success: true, user: {...}, token: "..." }
      // BaseApiClient wraps it as: { success: true, data: {...}, ... }
      const data = response.data || response;
      const token = data.token || response.token;
      const user = data.user || response.user;

      console.log('Login response:', { response, data, token, user });

      if (token && user) {
        this.setToken(token);
        this.setUser(user);
        return { success: true, data: { token, user } };
      } else {
        console.error('Missing token or user in login response', { token, user });
        return { success: false, message: 'Invalid login response' };
      }
    }
    return response;
  }

  /**
   * Send verification code to email
   */
  static async sendVerificationCode(email) {
    return this.post('/email/send-verification-code', {
      email: email
    });
  }

  /**
   * Verify email with code
   */
  static async verifyEmailWithCode(email, code) {
    return this.post('/email/verify-with-code', {
      email: email,
      code: code
    });
  }

  /**
   * Resend verification code
   */
  static async resendVerificationCode(email) {
    return this.post('/email/resend-verification-code', {
      email: email
    });
  }

  /**
   * Send password reset link
   */
  static async sendPasswordResetLink(email) {
    return this.post('/forgot-password', {
      email: email
    });
  }

  /**
   * Reset password with token
   */
  static async resetPassword(email, token, password, passwordConfirmation) {
    return this.post('/reset-password', {
      email: email,
      token: token,
      password: password,
      password_confirmation: passwordConfirmation
    });
  }

  /**
   * Get current user
   */
  static async getCurrentUser() {
    const response = await this.get('/users/profile');
    if (response.success) {
      this.setUser(response.data);
    }
    return response;
  }

  /**
   * Logout user
   */
  static async logout() {
    try {
      await this.post('/logout');
    } catch (error) {
      console.error('Logout error:', error);
    }

    // Clear stored data regardless of API response
    this.clearToken();
    this.clearUser();
    return { success: true, message: 'Logged out successfully' };
  }

  /**
   * Update user profile
   */
  static async updateProfile(userData) {
    const response = await this.put('/users/profile', userData);
    if (response.success) {
      this.setUser(response.data);
    }
    return response;
  }

  /**
   * Change password
   */
  static async changePassword(currentPassword, newPassword, newPasswordConfirmation) {
    return this.post('/users/change-password', {
      current_password: currentPassword,
      password: newPassword,
      password_confirmation: newPasswordConfirmation
    });
  }

  /**
   * Verify password
   */
  static async verifyPassword(password) {
    return this.post('/user/verify-password', {
      password: password
    });
  }

  /**
   * Error Handler with improved network error detection
   */
  static handleError(error) {
    let message = 'An error occurred';

    if (error.response) {
      // Server responded with error status
      message = error.response.data?.message || error.response.statusText;

      // Handle specific HTTP status codes
      if (error.response.status === 401) {
        message = 'Unauthorized - please check your credentials';
      } else if (error.response.status === 422) {
        // Handle validation errors - show detailed field errors
        const errors = error.response.data?.errors;
        if (errors && typeof errors === 'object') {
          // Get first error message from validation errors
          const firstError = Object.values(errors)[0];
          message = Array.isArray(firstError) ? firstError[0] : firstError;
        } else {
          message = error.response.data?.message || 'Validation error - please check your input';
        }
      } else if (error.response.status === 429) {
        message = 'Too many requests - please try again later';
      } else if (error.response.status >= 500) {
        message = 'Server error - please try again later';
      }
    } else if (error.request) {
      // Request made but no response
      if (error.code === 'ECONNABORTED') {
        message = 'Request timeout - please check your internet connection and try again';
      } else if (error.message === 'Network Error') {
        message = 'Network error - please check your internet connection';
      } else {
        message = 'No response from server - please check your internet connection';
      }
    } else {
      // Error in request setup
      message = error.message || 'An error occurred while processing your request';
    }

    return { success: false, message: message, error: error };
  }
}

// Make available globally
window.AuthApiClient = AuthApiClient;