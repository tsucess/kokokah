/**
 * QuizApiClient
 * Handles all quiz-related API operations
 * Extends BaseApiClient for consistent error handling and authentication
 *
 * Quizzes can be attached to:
 * - Lessons: For lesson-specific practice and assessment
 * - Topics: For topic-level assessment and reference
 */

import BaseApiClient from './baseApiClient.js';

class QuizApiClient extends BaseApiClient {
  /**
   * Get all quizzes for a lesson
   * @param {number} lessonId - The lesson ID
   * @param {object} filters - Filter options (page, per_page)
   */
  static async getQuizzesByLesson(lessonId, filters = {}) {
    const params = new URLSearchParams();
    if (filters.page) params.append('page', filters.page);
    if (filters.per_page) params.append('per_page', filters.per_page);

    const queryString = params.toString();
    const endpoint = queryString
      ? `/lessons/${lessonId}/quizzes?${queryString}`
      : `/lessons/${lessonId}/quizzes`;
    return this.get(endpoint);
  }

  /**
   * Get all quizzes for a topic
   * @param {number} topicId - The topic ID
   * @param {object} filters - Filter options (page, per_page)
   */
  static async getQuizzesByTopic(topicId, filters = {}) {
    const params = new URLSearchParams();
    if (filters.page) params.append('page', filters.page);
    if (filters.per_page) params.append('per_page', filters.per_page);

    const queryString = params.toString();
    const endpoint = queryString
      ? `/topics/${topicId}/quizzes?${queryString}`
      : `/topics/${topicId}/quizzes`;
    return this.get(endpoint);
  }

  /**
   * Create a new quiz for a lesson
   * @param {number} lessonId - The lesson ID
   * @param {object} quizData - Quiz data (title, type, questions, etc.)
   */
  static async createQuizForLesson(lessonId, quizData) {
    return this.post(`/lessons/${lessonId}/quizzes`, quizData);
  }

  /**
   * Create a new quiz for a topic
   * @param {number} topicId - The topic ID
   * @param {object} quizData - Quiz data (title, type, questions, etc.)
   */
  static async createQuizForTopic(topicId, quizData) {
    return this.post(`/topics/${topicId}/quizzes`, quizData);
  }

  /**
   * Create a new quiz (legacy method - defaults to lesson)
   * @param {number} lessonId - The lesson ID
   * @param {object} quizData - Quiz data (title, type, questions, etc.)
   */
  static async createQuiz(lessonId, quizData) {
    return this.createQuizForLesson(lessonId, quizData);
  }

  /**
   * Get a single quiz by ID
   * @param {number} quizId - The quiz ID
   */
  static async getQuiz(quizId) {
    return this.get(`/quizzes/${quizId}`);
  }

  /**
   * Update a quiz
   * @param {number} quizId - The quiz ID
   * @param {object} quizData - Updated quiz data
   */
  static async updateQuiz(quizId, quizData) {
    return this.put(`/quizzes/${quizId}`, quizData);
  }

  /**
   * Delete a quiz
   * @param {number} quizId - The quiz ID
   */
  static async deleteQuiz(quizId) {
    return this.delete(`/quizzes/${quizId}`);
  }

  /**
   * Start a quiz attempt
   * @param {number} quizId - The quiz ID
   */
  static async startQuiz(quizId) {
    return this.post(`/quizzes/${quizId}/start`, {});
  }

  /**
   * Submit quiz answers
   * @param {number} quizId - The quiz ID
   * @param {object} answers - Array of answers {question_id, answer}
   */
  static async submitQuiz(quizId, answers) {
    return this.post(`/quizzes/${quizId}/submit`, { answers });
  }

  /**
   * Get quiz results
   * @param {number} quizId - The quiz ID
   */
  static async getQuizResults(quizId) {
    return this.get(`/quizzes/${quizId}/results`);
  }

  /**
   * Get quiz analytics (instructor/admin only)
   * @param {number} quizId - The quiz ID
   */
  static async getQuizAnalytics(quizId) {
    return this.get(`/quizzes/${quizId}/analytics`);
  }
}

export default QuizApiClient;
