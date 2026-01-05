/**
 * Base API Client
 * Provides common functionality for all API clients
 * Handles authentication, error handling, and request/response processing
 * Uses native Fetch API for all HTTP requests
 */

const API_BASE_URL = '/api';
const TOKEN_KEY = 'auth_token';
const USER_KEY = 'auth_user';
const REQUEST_TIMEOUT = 30000; // 30 seconds

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
      this.showLoader();
      const response = await this.fetchWithTimeout(`${API_BASE_URL}${endpoint}`, {
        method: 'GET',
        headers: this.getAuthHeaders(),
        ...config
      });
      if (!response.ok) {
        this.hideLoader();
        return this.handleErrorResponse(response);
      }
      this.hideLoader();
      return this.handleSuccess(response);
    } catch (error) {
      this.hideLoader();
      return this.handleError(error);
    }
  }

  /**
   * Make a POST request
   */
  static async post(endpoint, data = {}, config = {}) {
    try {
      this.showLoader();
      // Check if data is FormData (for file uploads)
      const isFormData = data instanceof FormData;
      const headers = isFormData ? this.getAuthHeadersForFormData() : this.getAuthHeaders();

      const response = await this.fetchWithTimeout(`${API_BASE_URL}${endpoint}`, {
        method: 'POST',
        headers: headers,
        body: isFormData ? data : JSON.stringify(data),
        ...config
      });
      if (!response.ok) {
        this.hideLoader();
        return this.handleErrorResponse(response);
      }
      this.hideLoader();
      return this.handleSuccess(response);
    } catch (error) {
      this.hideLoader();
      return this.handleError(error);
    }
  }

  /**
   * Make a PUT request
   */
  static async put(endpoint, data = {}, config = {}) {
    try {
      this.showLoader();
      // Check if data is FormData (for file uploads)
      const isFormData = data instanceof FormData;
      const headers = isFormData ? this.getAuthHeadersForFormData() : this.getAuthHeaders();

      // If FormData, use POST with _method: PUT for Laravel method spoofing
      let body = isFormData ? data : JSON.stringify(data);
      const method = isFormData ? 'POST' : 'PUT';

      // Add _method field for Laravel method spoofing when using FormData
      if (isFormData) {
        body.append('_method', 'PUT');
      }

      const response = await this.fetchWithTimeout(`${API_BASE_URL}${endpoint}`, {
        method: method,
        headers: headers,
        body: body,
        ...config
      });
      if (!response.ok) {
        this.hideLoader();
        return this.handleErrorResponse(response);
      }
      this.hideLoader();
      return this.handleSuccess(response);
    } catch (error) {
      this.hideLoader();
      return this.handleError(error);
    }
  }

  /**
   * Make a DELETE request
   */
  static async delete(endpoint, config = {}) {
    try {
      this.showLoader();
      const response = await this.fetchWithTimeout(`${API_BASE_URL}${endpoint}`, {
        method: 'DELETE',
        headers: this.getAuthHeaders(),
        ...config
      });
      if (!response.ok) {
        this.hideLoader();
        return this.handleErrorResponse(response);
      }
      this.hideLoader();
      return this.handleSuccess(response);
    } catch (error) {
      this.hideLoader();
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
   * Get authorization headers for FormData (file uploads)
   * Don't set Content-Type - let browser set it to multipart/form-data
   */
  static getAuthHeadersForFormData() {
    const headers = {
      'Accept': 'application/json'
      // Don't set Content-Type - axios will set it to multipart/form-data automatically
    };

    const token = this.getToken();
    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }

    return headers;
  }

  /**
   * Fetch with timeout
   */
  static async fetchWithTimeout(url, options = {}) {
    const timeout = options.timeout || REQUEST_TIMEOUT;
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), timeout);

    try {
      const response = await fetch(url, {
        ...options,
        credentials: 'include', // Include cookies in requests
        signal: controller.signal
      });
      clearTimeout(timeoutId);

      // Parse response
      const contentType = response.headers.get('content-type');
      let data = null;

      if (contentType && contentType.includes('application/json')) {
        data = await response.json();
      } else {
        data = await response.text();
      }

      // Create response object compatible with handleSuccess
      return {
        status: response.status,
        ok: response.ok,
        data: data
      };
    } catch (error) {
      clearTimeout(timeoutId);
      throw error;
    }
  }

  /**
   * Handle error response from server
   */
  static handleErrorResponse(response) {
    const status = response.status;
    const data = response.data;

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
    // Handle abort/timeout errors
    if (error.name === 'AbortError') {
      return {
        success: false,
        message: 'Request timeout',
        status: 0
      };
    }

    // Handle network errors
    if (error instanceof TypeError) {
      return {
        success: false,
        message: 'Network error - unable to reach server',
        status: 0
      };
    }

    // Handle response errors (from fetchWithTimeout)
    if (error.response) {
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
    }

    // Generic error
    return {
      success: false,
      message: error.message || 'An error occurred',
      status: 0
    };
  }

  /**
   * Show the Kokokah loader
   */
  static showLoader() {
    if (window.kokokahLoader) {
      window.kokokahLoader.show();
    }
  }

  /**
   * Hide the Kokokah loader
   */
  static hideLoader() {
    if (window.kokokahLoader) {
      window.kokokahLoader.hide();
    }
  }
}

// Make available globally
window.BaseApiClient = BaseApiClient;