/**
 * Real-time Chat Module
 * 
 * Handles real-time message updates using Laravel Echo and WebSockets
 * Supports Pusher, Soketi, and Laravel WebSockets
 */

class RealtimeChat {
    constructor(chatRoomId, options = {}) {
        this.chatRoomId = chatRoomId;
        this.channelName = `chatroom.${chatRoomId}`;
        this.privateChannelName = `private-chatroom.${chatRoomId}`;
        this.channel = null;
        this.privateChannel = null;
        this.listeners = {};
        this.options = {
            debug: false,
            autoConnect: true,
            ...options
        };

        if (this.options.autoConnect) {
            this.connect();
        }
    }

    /**
     * Connect to the chat room channel
     */
    connect() {
        if (!window.Echo) {
            return;
        }

        // Subscribe to public channel
        this.channel = window.Echo.channel(this.channelName);

        // Subscribe to private channel (for authenticated users)
        this.privateChannel = window.Echo.private(this.privateChannelName);

        this.log('Connected to chat room:', this.chatRoomId);
    }

    /**
     * Listen for message sent event
     */
    onMessageSent(callback) {
        if (!this.privateChannel) {
            return;
        }

        this.privateChannel.listen('MessageSent', (event) => {
            this.log('Message sent:', event);
            callback(event);
        });

        return this;
    }

    /**
     * Listen for message updated event
     */
    onMessageUpdated(callback) {
        if (!this.privateChannel) {
            return;
        }

        this.privateChannel.listen('MessageUpdated', (event) => {
            this.log('Message updated:', event);
            callback(event);
        });

        return this;
    }

    /**
     * Listen for message deleted event
     */
    onMessageDeleted(callback) {
        if (!this.privateChannel) {
            return;
        }

        this.privateChannel.listen('MessageDeleted', (event) => {
            this.log('Message deleted:', event);
            callback(event);
        });

        return this;
    }

    /**
     * Listen for user typing event
     */
    onUserTyping(callback) {
        if (!this.channel) {
            return;
        }

        this.channel.listen('UserTyping', (event) => {
            this.log('User typing:', event);
            callback(event);
        });

        return this;
    }

    /**
     * Listen for reaction added event
     */
    onReactionAdded(callback) {
        if (!this.privateChannel) {
            return;
        }

        this.privateChannel.listen('ReactionAdded', (event) => {
            this.log('Reaction added:', event);
            callback(event);
        });

        return this;
    }

    /**
     * Listen for reaction removed event
     */
    onReactionRemoved(callback) {
        if (!this.privateChannel) {
            return;
        }

        this.privateChannel.listen('ReactionRemoved', (event) => {
            this.log('Reaction removed:', event);
            callback(event);
        });

        return this;
    }

    /**
     * Listen for user online/offline status
     */
    onUserStatusChanged(callback) {
        if (!this.channel) {
            return;
        }

        this.channel.listen('UserStatusChanged', (event) => {
            this.log('User status changed:', event);
            callback(event);
        });

        return this;
    }

    /**
     * Broadcast typing indicator
     */
    broadcastTyping(userId, userName) {
        return fetch(`/api/chatrooms/${this.chatRoomId}/typing`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${this.getAuthToken()}`,
                'X-CSRF-TOKEN': this.getCsrfToken(),
            },
            body: JSON.stringify({
                user_id: userId,
                user_name: userName,
            })
        });
    }

    /**
     * Disconnect from channel
     */
    disconnect() {
        if (this.channel) {
            window.Echo.leave(this.channelName);
            this.channel = null;
        }

        if (this.privateChannel) {
            window.Echo.leave(this.privateChannelName);
            this.privateChannel = null;
        }

        this.log('Disconnected from chat room');
    }

    /**
     * Check if connected
     */
    isConnected() {
        return this.channel !== null && this.privateChannel !== null;
    }

    /**
     * Get authentication token
     */
    getAuthToken() {
        const token = document.querySelector('meta[name="api-token"]');
        return token ? token.getAttribute('content') : '';
    }

    /**
     * Get CSRF token
     */
    getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }

    /**
     * Log debug messages
     */
    log(...args) {
        if (this.options.debug) {
        }
    }

    /**
     * Set debug mode
     */
    setDebug(enabled) {
        this.options.debug = enabled;
        return this;
    }
}

export default RealtimeChat;

