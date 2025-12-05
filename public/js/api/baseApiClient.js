/**
 * Base API Client
 * Provides common functionality for all API clients
 * Handles authentication, error handling, and request/response processing
 */

const API_BASE_URL = '/api';
const TOKEN_KEY = 'auth_token';
const USER_KEY = 'auth_user';
const REQUEST_TIMEOUT = 30000; // 30 seconds

// Configure axios with timeout
if (typeof axios !== 'undefined') {
  axios.defaults.timeout = REQUEST_TIMEOUT;
}

class BaseApiClient {
  /**
   * Get authorization token from localStorage
   */
  static getToken() {
    return localStorage.getItem(TOKEN_KEY) || '';
  }

  /**
   * Set authorization token in localStorage
   */
  static setToken(token) {
    if (token) {
      localStorage.setItem(TOKEN_KEY, token);
      if (typeof axios !== 'undefined') {
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      }
    }
  }

  /**
   * Clear authorization token
   */
  static clearToken() {
    localStorage.removeItem(TOKEN_KEY);
    if (typeof axios !== 'undefined') {
      delete axios.defaults.headers.common['Authorization'];
    }
  }

  /**
   * Get current user from localStorage
   */
  static getUser() {
    const user = localStorage.getItem(USER_KEY);
    return user ? JSON.parse(user) : null;
  }

  /**
   * Set current user in localStorage
   */
  static setUser(user) {
    if (user) {
      localStorage.setItem(USER_KEY, JSON.stringify(user));
    }
  }

  /**
   * Clear current user
   */
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
   * Make a GET request
   */
  static async get(endpoint, config = {}) {
    try {
      const response = await axios.get(`${API_BASE_URL}${endpoint}`, {
        headers: this.getAuthHeaders(),
        ...config
      });
      return this.handleSuccess(response);
    } catch (error) {
      return this.handleError(error);
    }
  }

  /**
   * Make a POST request
   */
  static async post(endpoint, data = {}, config = {}) {
    try {
      const response = await axios.post(`${API_BASE_URL}${endpoint}`, data, {
        headers: this.getAuthHeaders(),
        ...config
      });
      return this.handleSuccess(response);
    } catch (error) {
      return this.handleError(error);
    }
  }

  /**
   * Make a PUT request
   */
  static async put(endpoint, data = {}, config = {}) {
    try {
      const response = await axios.put(`${API_BASE_URL}${endpoint}`, data, {
        headers: this.getAuthHeaders(),
        ...config
      });
      return this.handleSuccess(response);
    } catch (error) {
      return this.handleError(error);
    }
  }

  /**
   * Make a DELETE request
   */
  static async delete(endpoint, config = {}) {
    try {
      const response = await axios.delete(`${API_BASE_URL}${endpoint}`, {
        headers: this.getAuthHeaders(),
        ...config
      });
      return this.handleSuccess(response);
    } catch (error) {
      return this.handleError(error);
    }
  }

  /**
   * Get authorization headers
   */
  static getAuthHeaders() {
    const headers = {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    };

    const token = this.getToken();
    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }

    return headers;
  }

  /**
   * Handle successful response
   */
  static handleSuccess(response) {
    const data = response.data;
    return {
      success: true,
      data: data.data || data,
      message: data.message || 'Success',
      status: response.status
    };
  }

  /**
   * Handle error response
   */
  static handleError(error) {
    console.error('API Error:', error);

    if (error.response) {
      // Server responded with error status
      const status = error.response.status;
      const data = error.response.data;

      if (status === 401) {
        this.clearToken();
        this.clearUser();
        window.location.href = '/login';
      }

      return {
        success: false,
        message: data.message || 'Request failed',
        status: status,
        errors: data.errors || {}
      };
    } else if (error.request) {
      // Request made but no response
      return {
        success: false,
        message: 'No response from server',
        status: 0
      };
    } else {
      // Error in request setup
      return {
        success: false,
        message: error.message || 'An error occurred',
        status: 0
      };
    }
  }
}

