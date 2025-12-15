/**
 * Course API Client
 * Handles all course-related API calls
 * Extends BaseApiClient for common functionality
 */

import BaseApiClient from './baseApiClient.js';

class CourseApiClient extends BaseApiClient {
  /**
   * Get all courses
   * @param {object} filters - Filter options (page, per_page, search, category_id)
   */
  static async getCourses(filters = {}) {
    const params = new URLSearchParams();
    if (filters.page) params.append('page', filters.page);
    if (filters.per_page) params.append('per_page', filters.per_page);
    if (filters.search) params.append('search', filters.search);
    if (filters.category_id) params.append('category_id', filters.category_id);
    if (filters.level_id) params.append('level_id', filters.level_id);
    if (filters.term_id) params.append('term_id', filters.term_id);

    const queryString = params.toString();
    const endpoint = queryString ? `/courses?${queryString}` : '/courses';
    return this.get(endpoint);
  }

  /**
   * Get course by ID
   * @param {number} courseId - Course ID
   */
  static async getCourse(courseId) {
    return this.get(`/courses/${courseId}`);
  }

  /**
   * Create a new course
   * @param {object} courseData - Course data
   */
  static async createCourse(courseData) {
    return this.post('/courses', courseData);
  }

  /**
   * Update course
   * @param {number} courseId - Course ID
   * @param {object} courseData - Updated course data
   */
  static async updateCourse(courseId, courseData) {
    return this.put(`/courses/${courseId}`, courseData);
  }

  /**
   * Delete course
   * @param {number} courseId - Course ID
   */
  static async deleteCourse(courseId) {
    return this.delete(`/courses/${courseId}`);
  }

  /**
   * Publish course
   * @param {number} courseId - Course ID
   */
  static async publishCourse(courseId) {
    return this.post(`/courses/${courseId}/publish`);
  }

  /**
   * Unpublish course
   * @param {number} courseId - Course ID
   */
  static async unpublishCourse(courseId) {
    return this.post(`/courses/${courseId}/unpublish`);
  }

  /**
   * Get all course categories
   */
  static async getCategories() {
    return this.get('/course-category');
  }

  /**
   * Get category by ID
   * @param {number} categoryId - Category ID
   */
  static async getCategory(categoryId) {
    return this.get(`/course-category/${categoryId}`);
  }

  /**
   * Create a new category
   * @param {object} categoryData - Category data
   */
  static async createCategory(categoryData) {
    return this.post('/course-category', categoryData);
  }

  /**
   * Update category
   * @param {number} categoryId - Category ID
   * @param {object} categoryData - Updated category data
   */
  static async updateCategory(categoryId, categoryData) {
    return this.put(`/course-category/${categoryId}`, categoryData);
  }

  /**
   * Delete category
   * @param {number} categoryId - Category ID
   */
  static async deleteCategory(categoryId) {
    return this.delete(`/course-category/${categoryId}`);
  }

  /**
   * Get all levels
   */
  static async getLevels() {
    return this.get('/level');
  }

  /**
   * Get level by ID
   * @param {number} levelId - Level ID
   */
  static async getLevel(levelId) {
    return this.get(`/level/${levelId}`);
  }

  /**
   * Create a new level
   * @param {object} levelData - Level data
   */
  static async createLevel(levelData) {
    return this.post('/level', levelData);
  }

  /**
   * Update level
   * @param {number} levelId - Level ID
   * @param {object} levelData - Updated level data
   */
  static async updateLevel(levelId, levelData) {
    return this.put(`/level/${levelId}`, levelData);
  }

  /**
   * Delete level
   * @param {number} levelId - Level ID
   */
  static async deleteLevel(levelId) {
    return this.delete(`/level/${levelId}`);
  }

  /**
   * Get all terms
   */
  static async getTerms() {
    return this.get('/term');
  }

  /**
   * Get term by ID
   * @param {number} termId - Term ID
   */
  static async getTerm(termId) {
    return this.get(`/term/${termId}`);
  }

  /**
   * Create a new term
   * @param {object} termData - Term data
   */
  static async createTerm(termData) {
    return this.post('/term', termData);
  }

  /**
   * Update term
   * @param {number} termId - Term ID
   * @param {object} termData - Updated term data
   */
  static async updateTerm(termId, termData) {
    return this.put(`/term/${termId}`, termData);
  }

  /**
   * Delete term
   * @param {number} termId - Term ID
   */
  static async deleteTerm(termId) {
    return this.delete(`/term/${termId}`);
  }

  /**
   * Get curriculum categories
   */
  static async getCurriculumCategories() {
    return this.get('/curriculum-category');
  }

  /**
   * Get curriculum category by ID
   * @param {number} categoryId - Category ID
   */
  static async getCurriculumCategory(categoryId) {
    return this.get(`/curriculum-category/${categoryId}`);
  }

  /**
   * Create curriculum category
   * @param {object} data - Category data
   */
  static async createCurriculumCategory(data) {
    return this.post('/curriculum-category', data);
  }

  /**
   * Update curriculum category
   * @param {number} categoryId - Category ID
   * @param {object} data - Updated data
   */
  static async updateCurriculumCategory(categoryId, data) {
    return this.put(`/curriculum-category/${categoryId}`, data);
  }

  /**
   * Delete curriculum category
   * @param {number} categoryId - Category ID
   */
  static async deleteCurriculumCategory(categoryId) {
    return this.delete(`/curriculum-category/${categoryId}`);
  }

  /**
   * Get user's enrolled courses
   * @param {object} filters - Filter options (page, per_page, sort_by, sort_order)
   */
  static async getMyCourses(filters = {}) {
    const params = new URLSearchParams();
    if (filters.page) params.append('page', filters.page);
    if (filters.per_page) params.append('per_page', filters.per_page);
    if (filters.sort_by) params.append('sort_by', filters.sort_by);
    if (filters.sort_order) params.append('sort_order', filters.sort_order);

    const queryString = params.toString();
    const endpoint = queryString ? `/courses/my-courses?${queryString}` : '/courses/my-courses';
    return this.get(endpoint);
  }

  /**
   * Enroll in a course
   * @param {number} courseId - Course ID
   */
  static async enrollCourse(courseId) {
    return this.post(`/courses/${courseId}/enroll`);
  }

  /**
   * Unenroll from a course
   * @param {number} courseId - Course ID
   */
  static async unenrollCourse(courseId) {
    return this.delete(`/courses/${courseId}/unenroll`);
  }

  /**
   * Get course lessons
   * @param {number} courseId - Course ID
   */
  static async getCourseLessons(courseId) {
    return this.get(`/courses/${courseId}/lessons`);
  }

  /**
   * Get featured courses
   */
  static async getFeaturedCourses() {
    return this.get('/courses/featured');
  }

  /**
   * Get popular courses
   */
  static async getPopularCourses() {
    return this.get('/courses/popular');
  }

  /**
   * Search courses
   * @param {string} query - Search query
   */
  static async searchCourses(query) {
    return this.get(`/courses/search?search=${encodeURIComponent(query)}`);
  }
}

export default CourseApiClient;

