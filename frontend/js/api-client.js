/**
 * Kokokah LMS - API Client
 * Handles all API communication with the backend
 */

class APIClient {
    constructor() {
        this.baseURL = localStorage.getItem('apiUrl') || 'http://localhost:8000/api';
        this.token = localStorage.getItem('token');
        this.locale = localStorage.getItem('locale') || 'en';
    }

    /**
     * Make API request
     */
    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;
        const headers = {
            'Content-Type': 'application/json',
            'Accept-Language': this.locale,
            ...options.headers
        };

        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        try {
            const response = await fetch(url, {
                ...options,
                headers
            });

            // Handle 401 Unauthorized
            if (response.status === 401) {
                this.logout();
                window.location.href = 'pages/login.html';
                return { success: false, message: 'Unauthorized' };
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('API Error:', error);
            return { success: false, message: error.message };
        }
    }

    /**
     * GET request
     */
    get(endpoint, options = {}) {
        return this.request(endpoint, { method: 'GET', ...options });
    }

    /**
     * POST request
     */
    post(endpoint, body, options = {}) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(body),
            ...options
        });
    }

    /**
     * PUT request
     */
    put(endpoint, body, options = {}) {
        return this.request(endpoint, {
            method: 'PUT',
            body: JSON.stringify(body),
            ...options
        });
    }

    /**
     * DELETE request
     */
    delete(endpoint, options = {}) {
        return this.request(endpoint, { method: 'DELETE', ...options });
    }

    /**
     * Authentication Methods
     */

    async register(userData) {
        return this.post('/register', userData);
    }

    async login(email, password) {
        const response = await this.post('/login', { email, password });
        if (response.success) {
            this.setToken(response.data.token);
            this.setUser(response.data.user);
        }
        return response;
    }

    async logout() {
        await this.post('/logout', {});
        this.clearAuth();
    }

    async getCurrentUser() {
        return this.get('/user');
    }

    async sendVerificationCode(email) {
        return this.post('/email/send-verification-code', { email });
    }

    async verifyEmailWithCode(email, code) {
        return this.post('/email/verify-with-code', { email, code });
    }

    /**
     * Course Methods
     */

    async getCourses(page = 1, perPage = 10) {
        return this.get(`/courses?page=${page}&per_page=${perPage}`);
    }

    async searchCourses(query) {
        return this.get(`/courses/search?q=${query}`);
    }

    async getCourse(id) {
        return this.get(`/courses/${id}`);
    }

    async createCourse(courseData) {
        return this.post('/courses', courseData);
    }

    async updateCourse(id, courseData) {
        return this.put(`/courses/${id}`, courseData);
    }

    async deleteCourse(id) {
        return this.delete(`/courses/${id}`);
    }

    async enrollCourse(courseId) {
        return this.post(`/courses/${courseId}/enroll`, {});
    }

    async getMyCourses() {
        return this.get('/courses/my-courses');
    }

    /**
     * Lesson Methods
     */

    async getLessons(courseId) {
        return this.get(`/courses/${courseId}/lessons`);
    }

    async getLesson(id) {
        return this.get(`/lessons/${id}`);
    }

    async completeLesson(id) {
        return this.post(`/lessons/${id}/complete`, {});
    }

    /**
     * Quiz Methods
     */

    async getQuizzes(lessonId) {
        return this.get(`/lessons/${lessonId}/quizzes`);
    }

    async getQuiz(id) {
        return this.get(`/quizzes/${id}`);
    }

    async startQuiz(id) {
        return this.post(`/quizzes/${id}/start`, {});
    }

    async submitQuiz(id, answers) {
        return this.post(`/quizzes/${id}/submit`, { answers });
    }

    async getQuizResults(id) {
        return this.get(`/quizzes/${id}/results`);
    }

    /**
     * User Methods
     */

    async getUserProfile() {
        return this.get('/users/profile');
    }

    async updateProfile(profileData) {
        return this.put('/users/profile', profileData);
    }

    async getDashboard() {
        return this.get('/users/dashboard');
    }

    async getAchievements() {
        return this.get('/users/achievements');
    }

    async getLearningStats() {
        return this.get('/users/learning-stats');
    }

    /**
     * Wallet Methods
     */

    async getWalletBalance() {
        return this.get('/wallet');
    }

    async getWalletTransactions() {
        return this.get('/wallet/transactions');
    }

    async purchaseWithWallet(courseId) {
        return this.post('/wallet/purchase-course', { course_id: courseId });
    }

    /**
     * Payment Methods
     */

    async getPaymentGateways() {
        return this.get('/payments/gateways');
    }

    async purchaseCourse(courseId, gatewayId) {
        return this.post('/payments/purchase-course', {
            course_id: courseId,
            gateway_id: gatewayId
        });
    }

    async getPaymentHistory() {
        return this.get('/payments/history');
    }

    /**
     * Notification Methods
     */

    async getNotifications() {
        return this.get('/notifications');
    }

    async markNotificationAsRead(id) {
        return this.put(`/notifications/${id}/read`, {});
    }

    async deleteNotification(id) {
        return this.delete(`/notifications/${id}`);
    }

    /**
     * Search Methods
     */

    async globalSearch(query) {
        return this.get(`/search?q=${query}`);
    }

    async searchCoursesByCategory(category) {
        return this.get(`/courses?filter[category]=${category}`);
    }

    /**
     * File Methods
     */

    async uploadFile(file) {
        const formData = new FormData();
        formData.append('file', file);

        return this.request('/files/upload', {
            method: 'POST',
            body: formData,
            headers: {} // Let browser set Content-Type
        });
    }

    async downloadFile(id) {
        return this.get(`/files/download/${id}`);
    }

    /**
     * Language Methods
     */

    async setLanguage(locale) {
        this.locale = locale;
        localStorage.setItem('locale', locale);
        return this.post('/language/set', { locale });
    }

    async getTranslations(locale) {
        return this.get(`/language/translations/${locale}`);
    }

    /**
     * Chat Methods
     */

    async startChat(sessionType = 'general') {
        return this.post('/chat/start', { session_type: sessionType });
    }

    async sendChatMessage(sessionId, message) {
        return this.post(`/chat/sessions/${sessionId}/message`, { message });
    }

    async getChatHistory(sessionId) {
        return this.get(`/chat/sessions/${sessionId}`);
    }

    async endChat(sessionId) {
        return this.post(`/chat/sessions/${sessionId}/end`, {});
    }

    /**
     * Certificate Methods
     */

    async getCertificates() {
        return this.get('/certificates');
    }

    async generateCertificate(courseId) {
        return this.post('/certificates/generate', { course_id: courseId });
    }

    async downloadCertificate(id) {
        return this.get(`/certificates/${id}/download`);
    }

    /**
     * Analytics Methods
     */

    async getLearningAnalytics() {
        return this.get('/analytics/learning');
    }

    async getCoursePerformance(courseId) {
        return this.get(`/analytics/course-performance?course_id=${courseId}`);
    }

    /**
     * Auth Helper Methods
     */

    setToken(token) {
        this.token = token;
        localStorage.setItem('token', token);
    }

    setUser(user) {
        localStorage.setItem('user', JSON.stringify(user));
    }

    getUser() {
        const user = localStorage.getItem('user');
        return user ? JSON.parse(user) : null;
    }

    isAuthenticated() {
        return !!this.token;
    }

    clearAuth() {
        this.token = null;
        localStorage.removeItem('token');
        localStorage.removeItem('user');
    }

    setApiUrl(url) {
        this.baseURL = url;
        localStorage.setItem('apiUrl', url);
    }
}

// Create global instance
const api = new APIClient();

