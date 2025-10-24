/**
 * API Service Layer - Kokokah.com LMS
 * Comprehensive API client for 200+ backend endpoints
 *
 * Features:
 * - Centralized API client with Axios
 * - Token-based authentication (Sanctum)
 * - Request/response interceptors
 * - Error handling
 * - Organized by feature
 */

import axios from 'axios';

// Create axios instance
const apiClient = axios.create({
    baseURL: process.env.MIX_API_URL || 'http://localhost:8000/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    timeout: 30000, // 30 second timeout
});

// Request interceptor - Add token to headers
apiClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('auth_token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response interceptor - Handle errors
apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Token expired or invalid
            localStorage.removeItem('auth_token');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

// ============================================
// AUTHENTICATION ENDPOINTS
// ============================================

export const authAPI = {
    register: (data) => apiClient.post('/auth/register', data),
    login: (email, password) => apiClient.post('/auth/login', { email, password }),
    logout: () => apiClient.post('/auth/logout'),
    refreshToken: () => apiClient.post('/auth/refresh'),
    getCurrentUser: () => apiClient.get('/auth/me'),
    resetPassword: (email) => apiClient.post('/auth/password-reset', { email }),
};

// ============================================
// COURSE ENDPOINTS
// ============================================

export const courseAPI = {
    // List courses with filters
    list: (params = {}) => apiClient.get('/courses', { params }),
    
    // Get single course
    get: (id) => apiClient.get(`/courses/${id}`),
    
    // Create course (admin/instructor)
    create: (data) => apiClient.post('/courses', data),
    
    // Update course
    update: (id, data) => apiClient.put(`/courses/${id}`, data),
    
    // Delete course
    delete: (id) => apiClient.delete(`/courses/${id}`),
    
    // Get course lessons
    getLessons: (courseId) => apiClient.get(`/courses/${courseId}/lessons`),
    
    // Get course reviews
    getReviews: (courseId) => apiClient.get(`/courses/${courseId}/reviews`),
    
    // Enroll in course
    enroll: (courseId) => apiClient.post(`/courses/${courseId}/enroll`),
    
    // Get featured courses
    getFeatured: () => apiClient.get('/courses/featured'),
    
    // Search courses
    search: (query) => apiClient.get('/courses/search', { params: { q: query } }),
};

// ============================================
// PAYMENT ENDPOINTS
// ============================================

export const paymentAPI = {
    // Initialize payment
    initialize: (data) => apiClient.post('/payments/initialize', data),
    
    // Verify payment
    verify: (reference) => apiClient.post('/payments/verify', { reference }),
    
    // Get payment history
    getHistory: (params = {}) => apiClient.get('/payments/history', { params }),
    
    // Get wallet balance
    getWallet: () => apiClient.get('/wallet'),
    
    // Deposit to wallet
    deposit: (amount, gateway) => apiClient.post('/wallet/deposit', { amount, gateway }),
    
    // Get transactions
    getTransactions: (params = {}) => apiClient.get('/transactions', { params }),
    
    // Apply coupon
    applyCoupon: (code) => apiClient.post('/coupons/apply', { code }),
};

// ============================================
// USER ENDPOINTS
// ============================================

export const userAPI = {
    // List users (admin)
    list: (params = {}) => apiClient.get('/users', { params }),
    
    // Get user details
    get: (id) => apiClient.get(`/users/${id}`),
    
    // Update user
    update: (id, data) => apiClient.put(`/users/${id}`, data),
    
    // Delete user (admin)
    delete: (id) => apiClient.delete(`/users/${id}`),
    
    // Get user enrollments
    getEnrollments: (userId) => apiClient.get(`/users/${userId}/enrollments`),
    
    // Get user certificates
    getCertificates: (userId) => apiClient.get(`/users/${userId}/certificates`),
    
    // Get user profile
    getProfile: () => apiClient.get('/users/profile'),
    
    // Update profile
    updateProfile: (data) => apiClient.put('/users/profile', data),
};

// ============================================
// QUIZ ENDPOINTS
// ============================================

export const quizAPI = {
    // List quizzes
    list: (params = {}) => apiClient.get('/quizzes', { params }),
    
    // Get quiz details
    get: (id) => apiClient.get(`/quizzes/${id}`),
    
    // Create quiz (instructor)
    create: (data) => apiClient.post('/quizzes', data),
    
    // Update quiz
    update: (id, data) => apiClient.put(`/quizzes/${id}`, data),
    
    // Delete quiz
    delete: (id) => apiClient.delete(`/quizzes/${id}`),
    
    // Start quiz attempt
    startAttempt: (quizId) => apiClient.post(`/quizzes/${quizId}/attempt`),
    
    // Submit quiz attempt
    submitAttempt: (quizId, answers) => apiClient.post(`/quizzes/${quizId}/submit`, { answers }),
    
    // Get quiz results
    getResults: (quizId) => apiClient.get(`/quizzes/${quizId}/results`),
    
    // Get user quiz attempts
    getAttempts: (quizId) => apiClient.get(`/quizzes/${quizId}/attempts`),
};

// ============================================
// ANALYTICS ENDPOINTS
// ============================================

export const analyticsAPI = {
    // Get dashboard statistics
    getDashboard: () => apiClient.get('/analytics/dashboard'),
    
    // Get student progress
    getStudentProgress: (userId) => apiClient.get(`/analytics/student-progress/${userId}`),
    
    // Get course statistics
    getCourseStats: (courseId) => apiClient.get(`/analytics/course-stats/${courseId}`),
    
    // Get student predictions
    getPredictions: (userId) => apiClient.get(`/analytics/predictions/${userId}`),
    
    // Get engagement scores
    getEngagementScores: () => apiClient.get('/analytics/engagement-scores'),
    
    // Get cohort analysis
    getCohortAnalysis: (params = {}) => apiClient.get('/analytics/cohort-analysis', { params }),
};

// ============================================
// ENROLLMENT ENDPOINTS
// ============================================

export const enrollmentAPI = {
    // Get user enrollments
    list: (params = {}) => apiClient.get('/enrollments', { params }),
    
    // Get enrollment details
    get: (id) => apiClient.get(`/enrollments/${id}`),
    
    // Update enrollment progress
    updateProgress: (id, progress) => apiClient.put(`/enrollments/${id}`, { progress }),
    
    // Complete enrollment
    complete: (id) => apiClient.post(`/enrollments/${id}/complete`),
};

// ============================================
// LESSON ENDPOINTS
// ============================================

export const lessonAPI = {
    // List lessons
    list: (params = {}) => apiClient.get('/lessons', { params }),
    
    // Get lesson details
    get: (id) => apiClient.get(`/lessons/${id}`),
    
    // Mark lesson as complete
    markComplete: (id) => apiClient.post(`/lessons/${id}/complete`),
    
    // Get lesson resources
    getResources: (id) => apiClient.get(`/lessons/${id}/resources`),
};

// ============================================
// ASSIGNMENT ENDPOINTS
// ============================================

export const assignmentAPI = {
    // List assignments
    list: (params = {}) => apiClient.get('/assignments', { params }),
    
    // Get assignment details
    get: (id) => apiClient.get(`/assignments/${id}`),
    
    // Submit assignment
    submit: (id, data) => apiClient.post(`/assignments/${id}/submit`, data),
    
    // Get submission
    getSubmission: (id) => apiClient.get(`/assignments/${id}/submission`),
};

// ============================================
// FORUM ENDPOINTS
// ============================================

export const forumAPI = {
    // List forum topics
    list: (params = {}) => apiClient.get('/forums', { params }),
    
    // Get forum topic
    get: (id) => apiClient.get(`/forums/${id}`),
    
    // Create forum topic
    create: (data) => apiClient.post('/forums', data),
    
    // Get forum posts
    getPosts: (forumId) => apiClient.get(`/forums/${forumId}/posts`),
    
    // Create forum post
    createPost: (forumId, data) => apiClient.post(`/forums/${forumId}/posts`, data),
};

// ============================================
// CERTIFICATE ENDPOINTS
// ============================================

export const certificateAPI = {
    // List certificates
    list: (params = {}) => apiClient.get('/certificates', { params }),
    
    // Get certificate
    get: (id) => apiClient.get(`/certificates/${id}`),
    
    // Download certificate
    download: (id) => apiClient.get(`/certificates/${id}/download`, { responseType: 'blob' }),
};

// ============================================
// BADGE ENDPOINTS
// ============================================

export const badgeAPI = {
    // List badges
    list: (params = {}) => apiClient.get('/badges', { params }),
    
    // Get badge
    get: (id) => apiClient.get(`/badges/${id}`),
    
    // Get user badges
    getUserBadges: (userId) => apiClient.get(`/users/${userId}/badges`),
};

// ============================================
// NOTIFICATION ENDPOINTS
// ============================================

export const notificationAPI = {
    // Get notifications
    list: (params = {}) => apiClient.get('/notifications', { params }),

    // Mark as read
    markAsRead: (id) => apiClient.put(`/notifications/${id}/read`),

    // Mark all as read
    markAllAsRead: () => apiClient.put('/notifications/read-all'),

    // Delete notification
    delete: (id) => apiClient.delete(`/notifications/${id}`),

    // Get preferences
    getPreferences: () => apiClient.get('/notifications/preferences'),

    // Update preferences
    updatePreferences: (data) => apiClient.put('/notifications/preferences', data),

    // Send notification (admin)
    send: (data) => apiClient.post('/notifications/send', data),

    // Broadcast notification (admin)
    broadcast: (data) => apiClient.post('/notifications/broadcast', data),

    // Get analytics (admin)
    getAnalytics: () => apiClient.get('/notifications/analytics'),
};

// ============================================
// CATEGORY ENDPOINTS
// ============================================

export const categoryAPI = {
    // List categories
    list: (params = {}) => apiClient.get('/category', { params }),

    // Create category
    create: (data) => apiClient.post('/category', data),

    // Get category
    get: (id) => apiClient.get(`/category/${id}`),

    // Update category
    update: (id, data) => apiClient.put(`/category/${id}`, data),

    // Delete category
    delete: (id) => apiClient.delete(`/category/${id}`),
};

// ============================================
// RECOMMENDATION ENDPOINTS
// ============================================

export const recommendationAPI = {
    // Get recommendations
    getRecommendations: () => apiClient.get('/recommendations'),

    // Get course-based recommendations
    getCourseBasedRecommendations: (courseId) =>
        apiClient.get(`/recommendations/courses/${courseId}`),

    // Get learning path recommendations
    getLearningPathRecommendations: () =>
        apiClient.get('/recommendations/learning-paths'),

    // Get instructor recommendations
    getInstructorRecommendations: () =>
        apiClient.get('/recommendations/instructors'),

    // Get content recommendations
    getContentRecommendations: () =>
        apiClient.get('/recommendations/content'),

    // Update preferences
    updatePreferences: (data) =>
        apiClient.put('/recommendations/preferences', data),

    // Get analytics (admin)
    getAnalytics: () => apiClient.get('/recommendations/analytics'),
};

// ============================================
// COUPON ENDPOINTS
// ============================================

export const couponAPI = {
    // List coupons
    list: (params = {}) => apiClient.get('/coupons', { params }),

    // Create coupon
    create: (data) => apiClient.post('/coupons', data),

    // Get coupon
    get: (id) => apiClient.get(`/coupons/${id}`),

    // Update coupon
    update: (id, data) => apiClient.put(`/coupons/${id}`, data),

    // Delete coupon
    delete: (id) => apiClient.delete(`/coupons/${id}`),

    // Validate coupon
    validate: (code) => apiClient.post('/coupons/validate', { code }),

    // Apply coupon
    apply: (code) => apiClient.post('/coupons/apply', { code }),

    // Get user coupons
    getUserCoupons: () => apiClient.get('/coupons/user/available'),

    // Get analytics (admin)
    getAnalytics: () => apiClient.get('/coupons/admin/analytics'),

    // Bulk action
    bulkAction: (data) => apiClient.post('/coupons/bulk-action', data),
};

// ============================================
// REPORT ENDPOINTS
// ============================================

export const reportAPI = {
    // Get report types
    getReportTypes: () => apiClient.get('/reports/types'),

    // Generate financial report
    generateFinancialReport: (data) =>
        apiClient.post('/reports/financial', data),

    // Generate academic report
    generateAcademicReport: (data) =>
        apiClient.post('/reports/academic', data),

    // Generate user report (admin)
    generateUserReport: (data) =>
        apiClient.post('/reports/user', data),

    // Generate content report
    generateContentReport: (data) =>
        apiClient.post('/reports/content', data),

    // Get scheduled reports
    getScheduledReports: () => apiClient.get('/reports/scheduled'),

    // Schedule report (admin)
    scheduleReport: (data) => apiClient.post('/reports/schedule', data),

    // Get report history
    getReportHistory: () => apiClient.get('/reports/history'),
};

// ============================================
// SETTINGS ENDPOINTS
// ============================================

export const settingAPI = {
    // Get all settings
    list: () => apiClient.get('/settings'),

    // Get setting by key
    get: (key) => apiClient.get(`/settings/${key}`),

    // Update setting
    update: (key, value) => apiClient.put(`/settings/${key}`, { value }),

    // Update bulk settings
    updateBulk: (data) => apiClient.put('/settings', data),

    // Reset settings
    reset: () => apiClient.post('/settings/reset'),

    // Get email settings
    getEmailSettings: () => apiClient.get('/settings/email/config'),

    // Get payment settings
    getPaymentSettings: () => apiClient.get('/settings/payment/config'),

    // Get feature toggles
    getFeatureToggles: () => apiClient.get('/settings/features/toggles'),

    // Get public settings (no auth)
    getPublicSettings: () => apiClient.get('/settings/public'),
};

// ============================================
// AUDIT ENDPOINTS
// ============================================

export const auditAPI = {
    // Get audit logs
    list: (params = {}) => apiClient.get('/audit/logs', { params }),

    // Get audit log
    get: (id) => apiClient.get(`/audit/logs/${id}`),

    // Get user activity
    getUserActivity: (userId) =>
        apiClient.get(`/audit/users/${userId}/activity`),

    // Get system events
    getSystemEvents: () => apiClient.get('/audit/system/events'),

    // Get security events
    getSecurityEvents: () => apiClient.get('/audit/security/events'),

    // Export logs
    exportLogs: (data) => apiClient.post('/audit/export', data),
};

// ============================================
// SEARCH ENDPOINTS
// ============================================

export const searchAPI = {
    // Global search
    globalSearch: (query, params = {}) =>
        apiClient.get('/search/global', { params: { q: query, ...params } }),

    // Course search
    courseSearch: (query, params = {}) =>
        apiClient.get('/search/courses', { params: { q: query, ...params } }),

    // User search
    userSearch: (query, params = {}) =>
        apiClient.get('/search/users', { params: { q: query, ...params } }),

    // Content search
    contentSearch: (query, params = {}) =>
        apiClient.get('/search/content', { params: { q: query, ...params } }),

    // Get suggestions
    getSuggestions: (query) =>
        apiClient.get('/search/suggestions', { params: { q: query } }),

    // Get filters
    getFilters: () => apiClient.get('/search/filters'),
};

// ============================================
// FILE MANAGEMENT ENDPOINTS
// ============================================

export const fileAPI = {
    // Upload file
    upload: (formData) => apiClient.post('/files/upload', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
    }),

    // Download file
    download: (id) => apiClient.get(`/files/download/${id}`, {
        responseType: 'blob'
    }),

    // Delete file
    delete: (id) => apiClient.delete(`/files/${id}`),

    // List files
    list: (params = {}) => apiClient.get('/files/list', { params }),

    // Preview file
    preview: (id) => apiClient.get(`/files/preview/${id}`),

    // Share file
    share: (id, data) => apiClient.post(`/files/${id}/share`, data),

    // Organize files
    organize: (data) => apiClient.post('/files/organize', data),

    // Get storage stats
    getStorageStats: () => apiClient.get('/files/storage/stats'),
};

// ============================================
// ADVANCED ANALYTICS ENDPOINTS
// ============================================

export const advancedAnalyticsAPI = {
    // Student predictions
    getStudentPredictions: (studentId) =>
        apiClient.get(`/analytics/advanced/predictions/student/${studentId}`),

    calculateStudentPredictions: (studentId) =>
        apiClient.post(`/analytics/advanced/predictions/student/${studentId}/calculate`),

    // Cohort analysis
    listCohorts: () => apiClient.get('/analytics/advanced/cohorts'),

    createCohort: (data) => apiClient.post('/analytics/advanced/cohorts', data),

    getCohortAnalysis: (cohortId) =>
        apiClient.get(`/analytics/advanced/cohorts/${cohortId}`),

    compareCohorts: (cohortId1, cohortId2) =>
        apiClient.post(`/analytics/advanced/cohorts/${cohortId1}/compare/${cohortId2}`),

    // Engagement scores
    getCourseEngagement: (courseId) =>
        apiClient.get(`/analytics/advanced/engagement/course/${courseId}`),

    getStudentEngagement: (studentId, courseId) =>
        apiClient.get(`/analytics/advanced/engagement/student/${studentId}/course/${courseId}`),

    calculateCourseEngagement: (courseId) =>
        apiClient.post(`/analytics/advanced/engagement/course/${courseId}/calculate`),

    // At-risk and high-performing students
    getAtRiskStudents: (courseId) =>
        apiClient.get(`/analytics/advanced/at-risk/course/${courseId}`),

    getHighPerformingStudents: (courseId) =>
        apiClient.get(`/analytics/advanced/high-performing/course/${courseId}`),

    // Dashboard
    getDashboard: () => apiClient.get('/analytics/advanced/dashboard'),
};

// ============================================
// LOCALIZATION ENDPOINTS
// ============================================

export const localizationAPI = {
    // Get preferences
    getPreferences: () => apiClient.get('/localization/preferences'),

    // Update preferences
    updatePreferences: (data) => apiClient.put('/localization/preferences', data),

    // Get supported languages
    getSupportedLanguages: () => apiClient.get('/localization/languages'),

    // Get supported currencies
    getSupportedCurrencies: () => apiClient.get('/localization/currencies'),

    // Get supported timezones
    getSupportedTimezones: () => apiClient.get('/localization/timezones'),

    // Convert currency
    convertCurrency: (data) => apiClient.post('/localization/convert-currency', data),

    // Translate content
    translateContent: (data) => apiClient.post('/localization/translate', data),

    // Get translations
    getTranslations: () => apiClient.get('/localization/translations'),
};

// ============================================
// VIDEO STREAMING ENDPOINTS
// ============================================

export const videoAPI = {
    // Create video stream
    create: (data) => apiClient.post('/videos', data),

    // Process video stream (admin)
    process: (videoStreamId) =>
        apiClient.post(`/videos/${videoStreamId}/process`),

    // Get video stream
    get: (videoStreamId) => apiClient.get(`/videos/${videoStreamId}`),

    // Record video view
    recordView: (videoStreamId) =>
        apiClient.post(`/videos/${videoStreamId}/view`),

    // Update watch time
    updateWatchTime: (videoStreamId, data) =>
        apiClient.post(`/videos/${videoStreamId}/watch-time`, data),

    // Create download request
    createDownloadRequest: (videoStreamId) =>
        apiClient.post(`/videos/${videoStreamId}/download`),

    // Get video analytics
    getAnalytics: (videoStreamId) =>
        apiClient.get(`/videos/${videoStreamId}/analytics`),

    // Get top videos
    getTopVideos: () => apiClient.get('/videos/top/videos'),

    // Get user downloads
    getUserDownloads: () => apiClient.get('/videos/user/downloads'),
};

// ============================================
// REAL-TIME FEATURES ENDPOINTS
// ============================================

export const realtimeAPI = {
    // Mark user online
    markOnline: () => apiClient.post('/realtime/online'),

    // Mark user offline
    markOffline: () => apiClient.post('/realtime/offline'),

    // Get online users
    getOnlineUsers: () => apiClient.get('/realtime/users/online'),

    // Get online count
    getOnlineCount: () => apiClient.get('/realtime/users/online/count'),

    // Get online users in course
    getOnlineUsersInCourse: (courseId) =>
        apiClient.get(`/realtime/course/${courseId}/users/online`),

    // Get online count in course
    getOnlineCountInCourse: (courseId) =>
        apiClient.get(`/realtime/course/${courseId}/users/online/count`),

    // Send typing indicator
    sendTypingIndicator: (data) => apiClient.post('/realtime/typing', data),

    // Get user activity status
    getUserActivityStatus: (userId) =>
        apiClient.get(`/realtime/activity/${userId}`),

    // Get current user activity status
    getCurrentUserActivityStatus: () =>
        apiClient.get('/realtime/activity'),
};

// ============================================
// EMAIL VERIFICATION ENDPOINTS
// ============================================

export const emailVerificationAPI = {
    // Send verification notification
    sendVerificationNotification: () =>
        apiClient.post('/email/verification-notification'),

    // Verify email (via link)
    verifyEmail: (id, hash) =>
        apiClient.get(`/email/verify/${id}/${hash}`),
};

export default apiClient;

