/**
 * Lesson API Client
 * Handles all lesson-related API calls
 * Extends BaseApiClient for common functionality
 */

import BaseApiClient from './baseApiClient.js';

class LessonApiClient extends BaseApiClient {
    /**
     * Get all lessons for a course
     * @param {number} courseId - The course ID
     * @returns {Promise<Object>} Response with lessons data
     */
    static async getLessonsByCourse(courseId) {
        return this.get(`/courses/${courseId}/lessons`);
    }

    /**
     * Get a single lesson
     * @param {number} lessonId - The lesson ID
     * @returns {Promise<Object>} Response with lesson data
     */
    static async getLesson(lessonId) {
        return this.get(`/lessons/${lessonId}`);
    }

    /**
     * Create a new lesson
     * @param {number} courseId - The course ID
     * @param {Object} lessonData - The lesson data
     * @returns {Promise<Object>} Response with created lesson
     */
    static async createLesson(courseId, lessonData) {
        return this.post(`/courses/${courseId}/lessons`, lessonData);
    }

    /**
     * Update a lesson
     * @param {number} lessonId - The lesson ID
     * @param {Object} lessonData - The lesson data to update
     * @returns {Promise<Object>} Response with updated lesson
     */
    static async updateLesson(lessonId, lessonData) {
        return this.put(`/lessons/${lessonId}`, lessonData);
    }

    /**
     * Delete a lesson
     * @param {number} lessonId - The lesson ID
     * @returns {Promise<Object>} Response from delete operation
     */
    static async deleteLesson(lessonId) {
        return this.delete(`/lessons/${lessonId}`);
    }

    /**
     * Mark lesson as complete
     * @param {number} lessonId - The lesson ID
     * @returns {Promise<Object>} Response from complete operation
     */
    static async completeLesson(lessonId) {
        return this.post(`/lessons/${lessonId}/complete`, {});
    }

    /**
     * Get lesson progress
     * @param {number} lessonId - The lesson ID
     * @returns {Promise<Object>} Response with progress data
     */
    static async getLessonProgress(lessonId) {
        return this.get(`/lessons/${lessonId}/progress`);
    }

    /**
     * Track watch time for a lesson
     * @param {number} lessonId - The lesson ID
     * @param {number} timeSpent - Time spent in seconds
     * @returns {Promise<Object>} Response from track operation
     */
    static async trackWatchTime(lessonId, timeSpent) {
        return this.post(`/lessons/${lessonId}/watch-time`, {
            time_spent: timeSpent
        });
    }

    /**
     * Get lesson attachments
     * @param {number} lessonId - The lesson ID
     * @returns {Promise<Object>} Response with attachments
     */
    static async getLessonAttachments(lessonId) {
        return this.get(`/lessons/${lessonId}/attachments`);
    }

    /**
     * Get all lessons for a topic
     * @param {number} topicId - The topic ID
     * @returns {Promise<Object>} Response with lessons data
     */
    static async getLessonsByTopic(topicId) {
        return this.get(`/topic/${topicId}/lessons`);
    }

    /**
     * Mark lesson as complete
     * @param {number} lessonId - The lesson ID
     * @returns {Promise<Object>} Response from complete operation
     */
    static async markLessonComplete(lessonId) {
        return this.post(`/lessons/${lessonId}/complete`, {});
    }

    /**
     * Start quiz attempt
     * @param {number} quizId - The quiz ID
     * @returns {Promise<Object>} Response from start operation
     */
    static async startQuizAttempt(quizId) {
        return this.post(`/quizzes/${quizId}/start`, {});
    }

    /**
     * Get quizzes for a lesson
     * @param {number} lessonId - The lesson ID
     * @returns {Promise<Object>} Response with quizzes data
     */
    static async getQuizzesByLesson(lessonId) {
        return this.get(`/lessons/${lessonId}/quizzes`);
    }
}

export default LessonApiClient;