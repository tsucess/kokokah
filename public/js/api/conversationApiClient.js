/**
 * Conversation API Client
 * Handles all conversation and messaging operations
 */

class ConversationApiClient extends BaseApiClient {
    constructor() {
        super('/api/conversations');
    }

    /**
     * Get all conversations for a course
     * @param {number} courseId - The course ID
     * @param {number} page - Page number for pagination
     * @returns {Promise}
     */
    async getConversationsByCourse(courseId, page = 1) {
        try {
            const response = await this.get(`/course/${courseId}?page=${page}`);
            return response;
        } catch (error) {
            console.error('Error fetching conversations:', error);
            throw error;
        }
    }

    /**
     * Create a new conversation
     * @param {object} data - Conversation data {course_id, name, description}
     * @returns {Promise}
     */
    async createConversation(data) {
        try {
            const response = await this.post('/', data);
            return response;
        } catch (error) {
            console.error('Error creating conversation:', error);
            throw error;
        }
    }

    /**
     * Get messages for a conversation
     * @param {number} conversationId - The conversation ID
     * @param {number} page - Page number for pagination
     * @returns {Promise}
     */
    async getMessages(conversationId, page = 1) {
        try {
            const response = await this.get(`/${conversationId}/messages?page=${page}`);
            return response;
        } catch (error) {
            console.error('Error fetching messages:', error);
            throw error;
        }
    }

    /**
     * Send a message to a conversation
     * @param {number} conversationId - The conversation ID
     * @param {string} message - The message text
     * @param {array} attachments - Optional attachments
     * @returns {Promise}
     */
    async sendMessage(conversationId, message, attachments = null) {
        try {
            const data = {
                message: message,
                attachments: attachments
            };
            const response = await this.post(`/${conversationId}/messages`, data);
            return response;
        } catch (error) {
            console.error('Error sending message:', error);
            throw error;
        }
    }

    /**
     * Join a conversation
     * @param {number} conversationId - The conversation ID
     * @returns {Promise}
     */
    async joinConversation(conversationId) {
        try {
            const response = await this.post(`/${conversationId}/join`, {});
            return response;
        } catch (error) {
            console.error('Error joining conversation:', error);
            throw error;
        }
    }

    /**
     * Handle API errors with user-friendly messages
     * @param {object} error - The error object
     * @returns {string} User-friendly error message
     */
    handleError(error) {
        if (error.response?.status === 403) {
            return 'You do not have access to this conversation';
        }
        if (error.response?.status === 422) {
            return 'Invalid message data. Please check your input.';
        }
        if (error.response?.status === 404) {
            return 'Conversation not found';
        }
        return error.response?.data?.message || 'An error occurred while processing your request';
    }
}

// Create and export singleton instance
const conversationApiClient = new ConversationApiClient();

