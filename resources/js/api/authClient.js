/**
 * Authentication API Client
 * Handles all authentication-related API calls
 */

const API_BASE_URL = '/api';
const TOKEN_KEY = 'auth_token';
const USER_KEY = 'auth_user';
const REQUEST_TIMEOUT = 30000; // 30 seconds

// Configure axios with timeout
axios.defaults.timeout = REQUEST_TIMEOUT;

class AuthApiClient {
  /**
   * Register a new user
   */
  static async register(firstName, lastName, email, password, role = 'student') {
    try {
      const response = await axios.post(`${API_BASE_URL}/register`, {
        first_name: firstName,
        last_name: lastName,
        email: email,
        password: password,
        password_confirmation: password,
        role: role
      });

      if (response.data.status === 'success' || response.data.success) {
        // Store token and user data
        const token = response.data.token || (response.data.data && response.data.data.token);
        const user = response.data.user || (response.data.data && response.data.data.user);
        this.setToken(token);
        this.setUser(user);
        return { success: true, data: { token, user } };
      }
      return { success: false, message: response.data.message };
    } catch (error) {
      return this.handleError(error);
    }
  }

  /**
   * Login user
   */
  static async login(email, password) {
    try {
      const response = await axios.post(`${API_BASE_URL}/login`, {
        email: email,
        password: password
      });

      if (response.data.success) {
        // Store token and user data
        this.setToken(response.data.data.token);
        this.setUser(response.data.data.user);
        return { success: true, data: response.data.data };
      }
      return { success: false, message: response.data.message };
    } catch (error) {
      return this.handleError(error);
    }
  }

  /**
   * Send verification code to email
   */
  static async sendVerificationCode(email) {
    try {
      const response = await axios.post(`${API_BASE_URL}/email/send-verification-code`, {
        email: email
      });

      if (response.data.success) {
        return { success: true, message: response.data.message };
      }
      return { success: false, message: response.data.message };
    } catch (error) {
      return this.handleError(error);
    }
  }

  /**
   * Verify email with code
   */
  static async verifyEmailWithCode(email, code) {
    try {
      const response = await axios.post(`${API_BASE_URL}/email/verify-with-code`, {
        email: email,
        code: code
      });

      if (response.data.success) {
        return { success: true, message: response.data.message };
      }
      return { success: false, message: response.data.message };
    } catch (error) {
      return this.handleError(error);
    }
  }

  /**
   * Resend verification code
   */
  static async resendVerificationCode(email) {
    try {
      const response = await axios.post(`${API_BASE_URL}/email/resend-verification-code`, {
        email: email
      });

      if (response.data.success) {
        return { success: true, message: response.data.message };
      }
      return { success: false, message: response.data.message };
    } catch (error) {
      return this.handleError(error);
    }
  }

  /**
   * Send password reset link
   */
  static async sendPasswordResetLink(email) {
    try {
      const response = await axios.post(`${API_BASE_URL}/forgot-password`, {
        email: email
      });

      if (response.data.success) {
        return { success: true, message: response.data.message };
      }
      return { success: false, message: response.data.message };
    } catch (error) {
      return this.handleError(error);
    }
  }

  /**
   * Reset password with token
   */
  static async resetPassword(email, token, password, passwordConfirmation) {
    try {
      const response = await axios.post(`${API_BASE_URL}/reset-password`, {
        email: email,
        token: token,
        password: password,
        password_confirmation: passwordConfirmation
      });

      if (response.data.success) {
        return { success: true, message: response.data.message };
      }
      return { success: false, message: response.data.message };
    } catch (error) {
      return this.handleError(error);
    }
  }

  /**
   * Get current user
   */
  static async getCurrentUser() {
    try {
      const token = this.getToken();
      if (!token) {
        return { success: false, message: 'No token found' };
      }

      const response = await axios.get(`${API_BASE_URL}/user`, {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });

      if (response.data.success) {
        this.setUser(response.data.data);
        return { success: true, data: response.data.data };
      }
      return { success: false, message: response.data.message };
    } catch (error) {
      return this.handleError(error);
    }
  }

  /**
   * Logout user
   */
  static async logout() {
    try {
      const token = this.getToken();
      if (token) {
        await axios.post(`${API_BASE_URL}/logout`, {}, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
      }

      // Clear stored data
      this.clearToken();
      this.clearUser();
      return { success: true, message: 'Logged out successfully' };
    } catch (error) {
      // Clear stored data even if logout fails
      this.clearToken();
      this.clearUser();
      return { success: true, message: 'Logged out' };
    }
  }

  /**
   * Token Management
   */
  static setToken(token) {
    localStorage.setItem(TOKEN_KEY, token);
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  }

  static getToken() {
    return localStorage.getItem(TOKEN_KEY);
  }

  static clearToken() {
    localStorage.removeItem(TOKEN_KEY);
    delete axios.defaults.headers.common['Authorization'];
  }

  /**
   * User Management
   */
  static setUser(user) {
    localStorage.setItem(USER_KEY, JSON.stringify(user));
  }

  static getUser() {
    const user = localStorage.getItem(USER_KEY);
    return user ? JSON.parse(user) : null;
  }

  static clearUser() {
    localStorage.removeItem(USER_KEY);
  }

  /**
   * Check if user is authenticated
   */
  static isAuthenticated() {
    return !!this.getToken();
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

export default AuthApiClient;

