import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

/**
 * Laravel Echo Configuration
 * 
 * This file initializes Laravel Echo for real-time communication.
 * It supports both Pusher (production) and local development.
 */

// Set Pusher globally
window.Pusher = Pusher;

// Initialize Echo with Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
    enabledTransports: ['ws', 'wss'],
});

/**
 * Alternative: Soketi (Self-hosted WebSocket server)
 * 
 * Uncomment below and comment out Pusher config above to use Soketi
 */
/*
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_SOKETI_APP_KEY || 'app-key',
    wsHost: import.meta.env.VITE_SOKETI_HOST || 'localhost',
    wsPort: import.meta.env.VITE_SOKETI_PORT || 6001,
    wssPort: import.meta.env.VITE_SOKETI_PORT || 6001,
    forceTLS: false,
    encrypted: false,
    disableStats: true,
});
*/

/**
 * Alternative: Laravel WebSockets (Database-driven)
 * 
 * Uncomment below and comment out Pusher config above to use Laravel WebSockets
 */
/*
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY || 'app-key',
    wsHost: import.meta.env.VITE_PUSHER_HOST || 'localhost',
    wsPort: import.meta.env.VITE_PUSHER_PORT || 6001,
    wssPort: import.meta.env.VITE_PUSHER_PORT || 6001,
    forceTLS: false,
    encrypted: false,
    disableStats: true,
});
*/

export default window.Echo;

