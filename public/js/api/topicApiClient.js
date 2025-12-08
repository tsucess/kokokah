/**
 * Topic API Client
 * Handles all topic-related API calls
 * Extends BaseApiClient for common functionality
 */

import BaseApiClient from './baseApiClient.js';

class TopicApiClient extends BaseApiClient {
  /**
   * Get all topics for a course
   * @param {number} courseId - Course ID
   */
  static async getTopicsByCourse(courseId) {
    return this.get(`/courses/${courseId}/topics`);
  }

  /**
   * Get all topics (without course filter)
   */
  static async getAllTopics() {
    return this.get('/topic');
  }

  /**
   * Get topic by ID
   * @param {number} topicId - Topic ID
   */
  static async getTopic(topicId) {
    return this.get(`/topic/${topicId}`);
  }

  /**
   * Create a new topic
   * @param {object} topicData - Topic data (title, course_id, order)
   */
  static async createTopic(topicData) {
    return this.post('/topic', topicData);
  }

  /**
   * Update topic
   * @param {number} topicId - Topic ID
   * @param {object} topicData - Updated topic data
   */
  static async updateTopic(topicId, topicData) {
    return this.put(`/topic/${topicId}`, topicData);
  }

  /**
   * Delete topic
   * @param {number} topicId - Topic ID
   */
  static async deleteTopic(topicId) {
    return this.delete(`/topic/${topicId}`);
  }

  /**
   * Get lessons for a topic
   * @param {number} topicId - Topic ID
   */
  static async getTopicLessons(topicId) {
    return this.get(`/topic/${topicId}/lessons`);
  }
}

export default TopicApiClient;

