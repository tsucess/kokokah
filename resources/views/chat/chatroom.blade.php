@extends('layouts.usertemplate')

@section('content')
    <style>
        /* Current user message styling */
        .chat-message.current-user-message {
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .chat-message.current-user-message .message-content {
            background-color: var(--bs-dark-teal, #004A53);
            color: white;
            border-radius: 12px;
            padding: 10px 15px;
            max-width: 70%;
            margin-left: auto;
        }

        .chat-message.current-user-message .message-content .message-user {
            color: white;
        }

        .chat-message.current-user-message .message-content p {
            color: white;
        }

        .chat-message.current-user-message .message-timestamp {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
        }

        /* Other users message styling */
        .chat-message:not(.current-user-message) .message-content {
            background-color: #e8e8e8;
            color: #333;
            border-radius: 12px;
            padding: 10px 15px;
            max-width: 70%;
        }

        .chat-message:not(.current-user-message) .message-timestamp {
            color: #666;
            font-size: 0.85rem;
        }

        /* Date separator styling */
        .message-date-separator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0 15px 0;
            gap: 10px;
        }

        .message-date-separator::before,
        .message-date-separator::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #ddd;
        }

        .message-date-separator span {
            color: #999;
            font-size: 0.9rem;
            font-weight: 500;
            white-space: nowrap;
        }

        /* Administrator badge styling */
        .admin-badge {
            display: inline-block;
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: #333;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 4px rgba(255, 165, 0, 0.3);
        }

        /* Mobile sidebar hidden by default */
        @media (max-width: 991.98px) {
            .chat-app-container {
                overflow: visible !important;
            }

            .overlay.show {
                display: block;
            }

            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.28);
                z-index: 946;
            }

            .sidebar-mobile {
                position: fixed;
                top: 0;
                left: -100%;
                height: 100vh;
                width: 80%;
                max-width: 320px;
                background: #fff;
                z-index: 950;
                padding: 1rem;
                overflow-y: auto;
                transition: left 0.3s ease-in-out;
                box-shadow: 2px 0 15px rgba(0, 0, 0, 0.15);
            }

            .sidebar-mobile.show {
                left: 0;
            }

            /* Prevent chat from being covered */
            .chat-panel-right {
                width: 100%;
            }
        }

        .chatroom-item {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .chatroom-item:hover {
            background-color: #f5f5f5;
        }

        /* Active state for chatroom items - override sidebar-item styles */
        .chatroom-item.active {
            background-color: var(--bs-dark-teal) !important;
            color: white !important;
            border-left: 3px solid var(--bs-dark-teal);
            font-weight: 600;
        }

        /* Ensure badge icon is visible on active state */
        .chatroom-item.active .badge {
            background-color: rgba(255, 255, 255, 0.3) !important;
        }

        /* Message actions styling */
        .message-actions {
            display: flex;
            gap: 5px;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .message-actions .btn {
            padding: 4px 8px;
            font-size: 0.75rem;
        }

        .chat-message:hover .message-actions {
            display: flex !important;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .loading-spinner.show {
            display: block;
        }

        /* Message Context Menu Modal Styles */
        .message-context-menu {
            position: fixed;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 40px rgba(0, 0, 0, 0.16);
            z-index: 1050;
            min-width: 200px;
            overflow: hidden;
            display: none;
        }

        .message-context-menu.show {
            display: block;
        }

        .message-context-menu-item {
            padding: 12px 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-size: 0.95rem;
            color: #333;
            transition: background-color 0.2s ease;
        }

        .message-context-menu-item:hover {
            background-color: #f5f5f5;
        }

        .message-context-menu-item.danger {
            color: #dc3545;
        }

        .message-context-menu-item.danger:hover {
            background-color: #ffe5e5;
        }

        .message-context-menu-item i {
            width: 18px;
            text-align: center;
        }

        /* Edit Message Modal */
        .edit-message-modal {
            display: none;
            position: fixed;
            z-index: 1051;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.2s ease;
        }

        .edit-message-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .edit-message-modal-content {
            background-color: white;
            padding: 24px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 40px rgba(0, 0, 0, 0.16);
            animation: slideUp 0.3s ease;
        }

        .edit-message-modal-header {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 16px;
            color: #333;
        }

        .edit-message-modal-body {
            margin-bottom: 20px;
        }

        .edit-message-modal-body textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            resize: vertical;
            min-height: 100px;
        }

        .edit-message-modal-body textarea:focus {
            outline: none;
            border-color: var(--bs-dark-teal, #004A53);
            box-shadow: 0 0 0 3px rgba(0, 74, 83, 0.1);
        }

        .edit-message-modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .edit-message-modal-footer button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .edit-message-modal-footer .btn-cancel {
            background-color: #f0f0f0;
            color: #333;
        }

        .edit-message-modal-footer .btn-cancel:hover {
            background-color: #e0e0e0;
        }

        .edit-message-modal-footer .btn-save {
            background-color: var(--bs-dark-teal, #004A53);
            color: white;
        }

        .edit-message-modal-footer .btn-save:hover {
            background-color: #003339;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Long press indicator for mobile */
        .message-long-press-active {
            background-color: rgba(0, 74, 83, 0.1);
            border-radius: 12px;
        }
    </style>
    <main>
        <div class="container-fluid py-4">
            <div class="overlay" id="sidebarOverlay"></div>
            <div id="sidebar-mobile" class="sidebar-mobile d-lg-none">
                <div class="input-group mb-4">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" placeholder="Find a Conversation" id="searchChatrooms">
                </div>

                <h6 class="text-muted text-uppercase small mb-2">Conversations</h6>
                <div id="chatrooms-list-mobile" class="chatrooms-list"></div>
            </div>

            <div class="row g-0 chat-app-container">
                <div class="col-lg-4 d-none d-lg-block sidebar-left">
                    <div class="input-group mb-4">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Find a Conversation" id="searchChatroomsDesktop">
                    </div>

                    <h6 class="text-muted text-uppercase small mb-2">Conversations</h6>
                    <div id="chatrooms-list-desktop" class="chatrooms-list"></div>
                </div>

                <div class="col-12 col-lg-8 chat-panel-right">
                    <div class="chat-header">
                        <button class="btn btn-outline-secondary d-lg-none" id="toggleSidebar">
                            <i class="bi bi-list"></i>
                        </button>
                        <span id="current-room-name">#General</span>
                    </div>

                    <div class="chat-history" id="chat-history">
                        <div class="welcome-message mb-5">
                            <div class="welcome-icon mx-auto mb-3">
                                <i class="bi bi-hash text-white"></i>
                            </div>
                            <h4 class="fw-bold mb-1" style="color: var(--color-primary-button: #004A53);">Welcome to Chatroom</h4>
                            <p class="text-muted">Select a conversation to start chatting</p>
                        </div>
                        <div class="chat-messages p-2" id="chat-messages">

                        </div>
                    </div>

                    <div class="chat-input-area">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 d-flex align-items-center border rounded-pill py-2 px-3 me-3"
                                style="border-color: var(--bs-light-gray) !important;">
                                <i class="bi bi-paperclip me-2 text-muted"></i>
                                <input type="text" class="form-control border-0 p-0 shadow-none" id="messageInput"
                                    placeholder="Type a message..." style="height: auto;">
                            </div>

                            <div class="d-flex gap-3 text-secondary fs-5 me-3 d-none d-md-flex">
                                <i class="bi bi-mic-fill"></i>
                                <i class="bi bi-emoji-smile-fill"></i>
                                <i class="bi bi-camera-fill"></i>
                            </div>
                            <button class="btn btn-send d-flex align-items-center" id="sendBtn">
                                Send <i class="bi bi-send-fill ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Message Context Menu -->
    <div class="message-context-menu" id="messageContextMenu">
        <button class="message-context-menu-item" id="contextEditBtn" onclick="openEditModal()">
            <i class="fa-solid fa-edit"></i>
            <span>Edit</span>
        </button>
        <button class="message-context-menu-item danger" id="contextDeleteBtn" onclick="openDeleteConfirmModal()">
            <i class="fa-solid fa-trash"></i>
            <span>Delete</span>
        </button>
    </div>

    <!-- Edit Message Modal -->
    <div class="edit-message-modal" id="editMessageModal">
        <div class="edit-message-modal-content">
            <div class="edit-message-modal-header">Edit Message</div>
            <div class="edit-message-modal-body">
                <textarea id="editMessageInput" placeholder="Enter your message..."></textarea>
            </div>
            <div class="edit-message-modal-footer">
                <button class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                <button class="btn-save" onclick="saveEditMessage()">Save</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="edit-message-modal" id="deleteConfirmModal">
        <div class="edit-message-modal-content">
            <div class="edit-message-modal-header">Delete Message</div>
            <div class="edit-message-modal-body">
                <p style="color: #666; margin: 0;">Are you sure you want to delete this message? This action cannot be undone.</p>
            </div>
            <div class="edit-message-modal-footer">
                <button class="btn-cancel" onclick="closeDeleteConfirmModal()">Cancel</button>
                <button class="btn-save" style="background-color: #dc3545;" onclick="confirmDeleteMessage()">Delete</button>
            </div>
        </div>
    </div>

    <script>
        let currentChatroomId = null;
        const LAST_CHATROOM_KEY = 'last_selected_chatroom';

        // Load chatrooms on page load
        document.addEventListener('DOMContentLoaded', async () => {
            // Wait for API_BASE_URL to be defined
            if (typeof API_BASE_URL === 'undefined') {
                console.error('API_BASE_URL is not defined. Waiting for scripts to load...');
                setTimeout(() => {
                    loadChatrooms();
                }, 500);
                return;
            }

            // Check if user has a valid token
            let token = localStorage.getItem('auth_token');

            // If no token in localStorage, check if one was provided by the server
            @if(isset($token))
                if (!token) {
                    token = '{{ $token }}';
                    localStorage.setItem('auth_token', token);
                    console.log('Token set from server');
                }
            @endif

            if (!token) {
                console.warn('No auth token found. User may not be logged in.');
                alert('Please log in to access the chatroom');
                window.location.href = '/login';
                return;
            } else {
                console.log('Auth token found:', token.substring(0, 10) + '...');
            }

            await loadChatrooms();
        });

        // Load chatrooms from API
        async function loadChatrooms() {
            try {
                const token = localStorage.getItem('auth_token');
                console.log('Loading chatrooms with token:', token ? 'present' : 'missing');

                const response = await fetch(`${API_BASE_URL}/chatrooms`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                console.log('Chatrooms response status:', response.status);

                if (!response.ok) {
                    const errorData = await response.json();
                    console.error('Failed to load chatrooms:', errorData);
                    throw new Error(`Failed to load chatrooms: ${response.status}`);
                }

                const data = await response.json();
                console.log('Chatrooms loaded:', data);
                const chatrooms = data.data || data;

                renderChatrooms(chatrooms);

                // Load last selected chatroom or default to "General"
                const lastChatroomId = localStorage.getItem(LAST_CHATROOM_KEY);
                let chatroomToLoad = null;

                if (lastChatroomId) {
                    // Check if last selected chatroom still exists (convert to string for comparison)
                    chatroomToLoad = chatrooms.find(room => String(room.id) === String(lastChatroomId));
                }

                // If no last chatroom or it doesn't exist, find "General" chatroom
                if (!chatroomToLoad) {
                    chatroomToLoad = chatrooms.find(room => room.name.toLowerCase() === 'general');
                    console.log('No last chatroom, found General:', chatroomToLoad ? chatroomToLoad.name : 'NOT FOUND');
                }

                // If still no chatroom found, use the first one
                if (!chatroomToLoad && chatrooms.length > 0) {
                    chatroomToLoad = chatrooms[0];
                    console.log('No General chatroom, using first:', chatroomToLoad.name);
                }

                // Load the selected chatroom
                if (chatroomToLoad) {
                    console.log('About to load chatroom:', chatroomToLoad.id, chatroomToLoad.name, 'Type of ID:', typeof chatroomToLoad.id);
                    // Use setTimeout to ensure DOM is fully updated before applying active state
                    setTimeout(() => {
                        console.log('Timeout fired, calling selectChatroom');
                        selectChatroom(chatroomToLoad.id, chatroomToLoad.name);
                    }, 200);
                } else {
                    console.warn('No chatroom to load!');
                }
            } catch (error) {
                console.error('Error loading chatrooms:', error);
            }
        }

        // Render chatrooms in sidebar
        function renderChatrooms(chatrooms) {
            const html = chatrooms.map(room => {
                const roomId = String(room.id); // Ensure ID is a string
                return `
                <a href="#" class="sidebar-item chatroom-item" data-room-id="${roomId}" data-room-name="${room.name}" onclick="selectChatroom('${roomId}', '${room.name}'); return false;">
                    <div class="d-flex align-items-center">
                        <span class="badge me-2 d-flex justify-content-center align-items-center"
                            style="background: #114243; border-radius:20px; width: 25px;">
                            <i class="bi bi-hash text-white"></i>
                        </span>
                        ${room.name}
                    </div>
                    ${room.unread_count ? `<span class="new-message-badge">${room.unread_count}</span>` : ''}
                </a>
            `;
            }).join('');

            document.getElementById('chatrooms-list-desktop').innerHTML = html;
            document.getElementById('chatrooms-list-mobile').innerHTML = html;
            console.log('Chatrooms rendered:', chatrooms.length, 'rooms');
        }

        // Select chatroom
        async function selectChatroom(roomId, roomName) {
            // Ensure roomId is a string for consistent selector matching
            roomId = String(roomId);
            console.log('selectChatroom called with ID:', roomId, 'Name:', roomName, 'Type:', typeof roomId);

            currentChatroomId = roomId;
            document.getElementById('current-room-name').textContent = `#${roomName}`;

            // Save selected chatroom to localStorage for persistence
            localStorage.setItem(LAST_CHATROOM_KEY, roomId);

            // Update active state - remove from all
            const allItems = document.querySelectorAll('.chatroom-item');
            console.log('Total chatroom items found:', allItems.length);

            allItems.forEach(item => {
                item.classList.remove('active');
            });

            // Add active to the selected one(s) - there may be multiple (desktop and mobile)
            const selector = `[data-room-id="${roomId}"]`;
            console.log('Looking for elements with selector:', selector);

            const activeElements = document.querySelectorAll(selector);
            console.log('Elements found:', activeElements.length);

            if (activeElements.length > 0) {
                activeElements.forEach(element => {
                    element.classList.add('active');
                    console.log('✓ Active class added to chatroom element');
                });
                console.log('✓ Active class added to', activeElements.length, 'chatroom element(s) for:', roomName, 'ID:', roomId);
            } else {
                console.warn('✗ Could not find chatroom element with ID:', roomId);
                console.log('Available data-room-ids:', Array.from(allItems).map(item => item.getAttribute('data-room-id')));
            }

            await loadMessages(roomId);
            closeSidebar();
        }

        // Load messages for chatroom
        async function loadMessages(roomId) {
            try {
                const token = localStorage.getItem('auth_token');
                console.log('=== LOAD MESSAGES START ===');
                console.log('Loading messages for room', roomId, 'with token:', token ? 'present' : 'missing');

                const response = await fetch(`${API_BASE_URL}/chatrooms/${roomId}/messages`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                console.log('Messages response status:', response.status);

                if (!response.ok) {
                    const errorData = await response.json();
                    console.error('Failed to load messages:', errorData);
                    throw new Error(`Failed to load messages: ${response.status}`);
                }

                const data = await response.json();
                console.log('API Response:', data);
                const messages = data.data || data;
                console.log('Messages to render:', messages.length, 'messages');

                // Log the first message to see if edited_content is present
                if (messages.length > 0) {
                    console.log('First message:', messages[0]);
                }

                renderMessages(messages);
                console.log('=== LOAD MESSAGES COMPLETE ===');
            } catch (error) {
                console.error('=== LOAD MESSAGES ERROR ===');
                console.error('Error loading messages:', error);
            }
        }

        // Render messages
        function renderMessages(messages) {
            console.log('=== RENDER MESSAGES START ===');
            console.log('renderMessages called with:', messages.length, 'messages');

            // Get current user ID and role from localStorage
            let currentUserId = null;
            let userRole = null;
            const authUserStr = localStorage.getItem('auth_user');
            if (authUserStr) {
                try {
                    const authUser = JSON.parse(authUserStr);
                    currentUserId = authUser?.id;
                    userRole = authUser?.role;
                } catch (e) {
                    console.error('Failed to parse auth_user:', e);
                }
            }
            console.log('Current user ID:', currentUserId, 'Role:', userRole);

            if (!messages || messages.length === 0) {
                console.log('No messages to render');
                document.getElementById('chat-messages').innerHTML = '<p class="text-muted text-center">No messages yet</p>';
                return;
            }

            // Sort messages by created_at date (oldest first, so newest appears at bottom)
            const sortedMessages = [...messages].sort((a, b) => {
                return new Date(a.created_at) - new Date(b.created_at);
            });

            // Build HTML with date separators
            let html = '';
            let lastDate = null;

            sortedMessages.forEach((msg, index) => {
                // Get the date of the message
                const messageDate = new Date(msg.created_at);
                const messageDateString = messageDate.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                // Add date separator if this is a new day
                if (lastDate !== messageDateString) {
                    html += `<div class="message-date-separator"><span>${messageDateString}</span></div>`;
                    lastDate = messageDateString;
                }

                // Now render the message
                const messageHtml = renderSingleMessage(msg, currentUserId, userRole, currentChatroomId);
                html += messageHtml;

                // Log edited messages
                if (msg.edited_content) {
                    console.log('Message', index, 'is edited:', {
                        id: msg.id,
                        content: msg.content,
                        edited_content: msg.edited_content,
                        edited_at: msg.edited_at
                    });
                }
            });

            console.log('Rendered HTML length:', html.length);
            document.getElementById('chat-messages').innerHTML = html;
            console.log('=== RENDER MESSAGES COMPLETE ===');
        }

        // Render a single message
        function renderSingleMessage(msg, currentUserId, userRole, currentChatroomId) {
                const isCurrentUser = msg.user_id === currentUserId || msg.user_id == currentUserId;
                const isCurrentUserAdmin = ['admin', 'superadmin'].includes(userRole);
                const canEditDelete = isCurrentUser || isCurrentUserAdmin;
                const userFirstName = msg.user?.first_name || 'Unknown';
                const userLastName = msg.user?.last_name || 'User';
                const profilePhoto = msg.user?.profile_photo || '/images/default-avatar.png';
                const messageTime = new Date(msg.created_at).toLocaleTimeString();
                // Display edited_content if it exists, otherwise display original content
                const messageContent = msg.edited_content || msg.content || '';
                const isDeleted = msg.is_deleted;
                const isEdited = msg.edited_content && msg.edited_at;

                // Debug logging for edited messages
                if (msg.edited_content) {
                    console.log('Rendering edited message:', {
                        id: msg.id,
                        original: msg.content,
                        edited: msg.edited_content,
                        displaying: messageContent
                    });
                }

                // Check if the message sender is an admin or superadmin
                const senderRole = msg.user?.role || null;
                const isSenderAdmin = ['admin', 'superadmin'].includes(senderRole);
                const adminBadge = isSenderAdmin ? '<span class="admin-badge">Administrator</span>' : '';

                // Show deleted message indicator
                if (isDeleted) {
                    return `
                        <div class="chat-message ${isCurrentUser ? 'current-user-message' : ''}">
                            ${!isCurrentUser ? `<img src="${profilePhoto}" alt="Avatar" class="message-avatar" onerror="this.src='/images/default-avatar.png'">` : ''}
                            <div class="message-content">
                                ${!isCurrentUser ? `<span class="message-user">${userFirstName} ${userLastName}${adminBadge}</span>` : ''}
                                <span class="message-timestamp">${messageTime}</span>
                                <p class="mb-1 text-muted fst-italic">This message has been deleted</p>
                            </div>
                        </div>
                    `;
                }

                // Build context menu attributes for messages that can be edited/deleted
                let contextMenuAttrs = '';
                if (canEditDelete) {
                    contextMenuAttrs = `
                        oncontextmenu="showMessageContextMenu(event, ${msg.id}, '${currentChatroomId}', '${messageContent.replace(/'/g, "\\'")}')"
                        ontouchstart="startLongPress(event, ${msg.id}, '${currentChatroomId}', '${messageContent.replace(/'/g, "\\'")}')"
                        ontouchend="endLongPress()"
                        style="cursor: context-menu;"
                    `;
                }

                // Build edited indicator
                const editedIndicator = isEdited ? '<span class="message-edited-indicator" style="font-size: 0.75rem; color: #999; margin-left: 4px;">(edited)</span>' : '';

                return `
                    <div class="chat-message ${isCurrentUser ? 'current-user-message' : ''}" data-message-id="${msg.id}" ${contextMenuAttrs}>
                        ${!isCurrentUser ? `<img src="${profilePhoto}" alt="Avatar" class="message-avatar" onerror="this.src='/images/default-avatar.png'">` : ''}
                        <div class="message-content">
                            ${!isCurrentUser ? `<span class="message-user">${userFirstName} ${userLastName}${adminBadge}</span>` : ''}
                            <span class="message-timestamp">${messageTime}</span>
                            <p class="mb-1">${messageContent}${editedIndicator}</p>
                        </div>
                    </div>
                `;
        }

        // Context menu state
        let currentContextMessage = {
            id: null,
            roomId: null,
            content: null
        };

        let longPressTimer = null;
        const LONG_PRESS_DURATION = 500; // milliseconds

        // Show message context menu on right-click
        function showMessageContextMenu(event, messageId, roomId, messageContent) {
            event.preventDefault();

            console.log('Context menu triggered:', {
                messageId: messageId,
                roomId: roomId,
                contentLength: messageContent.length
            });

            // Store current message info
            currentContextMessage = {
                id: messageId,
                roomId: roomId,
                content: messageContent
            };

            const contextMenu = document.getElementById('messageContextMenu');
            contextMenu.classList.add('show');

            // Position the menu at cursor
            contextMenu.style.left = event.clientX + 'px';
            contextMenu.style.top = event.clientY + 'px';

            console.log('Context menu positioned at:', event.clientX, event.clientY);

            // Close menu when clicking elsewhere
            document.addEventListener('click', closeContextMenu);
        }

        // Long press handler for mobile
        function startLongPress(event, messageId, roomId, messageContent) {
            longPressTimer = setTimeout(() => {
                // Store current message info
                currentContextMessage = {
                    id: messageId,
                    roomId: roomId,
                    content: messageContent
                };

                const contextMenu = document.getElementById('messageContextMenu');
                contextMenu.classList.add('show');

                // Position the menu at touch point
                const touch = event.touches[0];
                contextMenu.style.left = touch.clientX + 'px';
                contextMenu.style.top = touch.clientY + 'px';

                // Highlight the message
                const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
                if (messageEl) {
                    messageEl.classList.add('message-long-press-active');
                }

                // Close menu when clicking elsewhere
                document.addEventListener('click', closeContextMenu);
            }, LONG_PRESS_DURATION);
        }

        // End long press
        function endLongPress() {
            if (longPressTimer) {
                clearTimeout(longPressTimer);
                longPressTimer = null;
            }
        }

        // Close context menu
        function closeContextMenu() {
            const contextMenu = document.getElementById('messageContextMenu');
            contextMenu.classList.remove('show');

            // Remove highlight from message
            const messageEl = document.querySelector(`[data-message-id="${currentContextMessage.id}"]`);
            if (messageEl) {
                messageEl.classList.remove('message-long-press-active');
            }

            document.removeEventListener('click', closeContextMenu);
        }

        // Open edit modal
        function openEditModal() {
            console.log('Opening edit modal for message:', currentContextMessage.id);
            closeContextMenu();
            const editInput = document.getElementById('editMessageInput');
            editInput.value = currentContextMessage.content;
            editInput.focus();
            editInput.select();

            const modal = document.getElementById('editMessageModal');
            modal.classList.add('show');
            console.log('Edit modal opened');
        }

        // Close edit modal
        function closeEditModal() {
            const modal = document.getElementById('editMessageModal');
            modal.classList.remove('show');
            console.log('Edit modal closed');
        }

        // Save edited message
        async function saveEditMessage() {
            const newContent = document.getElementById('editMessageInput').value.trim();

            if (!newContent) {
                alert('Message cannot be empty');
                return;
            }

            try {
                const token = localStorage.getItem('auth_token');
                const url = `${API_BASE_URL}/chatrooms/${currentContextMessage.roomId}/messages/${currentContextMessage.id}`;

                console.log('=== EDIT MESSAGE START ===');
                console.log('Editing message:', {
                    messageId: currentContextMessage.id,
                    roomId: currentContextMessage.roomId,
                    url: url,
                    newContent: newContent,
                    tokenPresent: !!token
                });

                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content: newContent })
                });

                console.log('Edit response status:', response.status);
                const responseData = await response.json();
                console.log('Edit response data:', responseData);

                if (!response.ok) {
                    console.error('Edit failed with status:', response.status);
                    alert('Failed to edit message: ' + (responseData.message || 'Unknown error'));
                    return;
                }

                console.log('Edit successful, closing modal and reloading messages...');
                closeEditModal();

                console.log('About to load messages for room:', currentContextMessage.roomId);
                await loadMessages(currentContextMessage.roomId);
                console.log('=== EDIT MESSAGE SUCCESS ===');
            } catch (error) {
                console.error('=== EDIT MESSAGE ERROR ===');
                console.error('Error editing message:', error);
                alert('Failed to edit message: ' + error.message);
            }
        }

        // Open delete confirmation modal
        function openDeleteConfirmModal() {
            console.log('Opening delete confirmation modal for message:', currentContextMessage.id);
            closeContextMenu();
            const modal = document.getElementById('deleteConfirmModal');
            modal.classList.add('show');
            console.log('Delete confirmation modal opened');
        }

        // Close delete confirmation modal
        function closeDeleteConfirmModal() {
            const modal = document.getElementById('deleteConfirmModal');
            modal.classList.remove('show');
            console.log('Delete confirmation modal closed');
        }

        // Confirm delete message
        async function confirmDeleteMessage() {
            try {
                const token = localStorage.getItem('auth_token');
                const url = `${API_BASE_URL}/chatrooms/${currentContextMessage.roomId}/messages/${currentContextMessage.id}`;

                console.log('Deleting message:', {
                    messageId: currentContextMessage.id,
                    roomId: currentContextMessage.roomId,
                    url: url,
                    tokenPresent: !!token
                });

                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                console.log('Delete response status:', response.status);
                const responseData = await response.json();
                console.log('Delete response data:', responseData);

                if (!response.ok) {
                    alert('Failed to delete message: ' + (responseData.message || 'Unknown error'));
                    return;
                }

                closeDeleteConfirmModal();
                await loadMessages(currentContextMessage.roomId);
                console.log('Message deleted successfully');
            } catch (error) {
                console.error('Error deleting message:', error);
                alert('Failed to delete message: ' + error.message);
            }
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            const editModal = document.getElementById('editMessageModal');
            const deleteModal = document.getElementById('deleteConfirmModal');

            if (event.target === editModal) {
                closeEditModal();
            }
            if (event.target === deleteModal) {
                closeDeleteConfirmModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeEditModal();
                closeDeleteConfirmModal();
                closeContextMenu();
            }
        });

        // Send message
        document.getElementById('sendBtn')?.addEventListener('click', async () => {
            if (!currentChatroomId) {
                alert('Please select a chatroom first');
                return;
            }

            const messageInput = document.getElementById('messageInput');
            const content = messageInput.value.trim();

            if (!content) return;

            try {
                const token = localStorage.getItem('auth_token');
                console.log('Sending message to room:', currentChatroomId);
                console.log('Token present:', token ? 'YES' : 'NO');

                const response = await fetch(`${API_BASE_URL}/chatrooms/${currentChatroomId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content })
                });

                console.log('Send message response status:', response.status);

                if (!response.ok) {
                    const errorData = await response.json();
                    console.error('Server error response:', errorData);
                    throw new Error(errorData.message || 'Failed to send message');
                }

                const responseData = await response.json();
                console.log('Message sent successfully:', responseData);

                messageInput.value = '';
                await loadMessages(currentChatroomId);
            } catch (error) {
                console.error('Error sending message:', error);
                alert('Failed to send message: ' + error.message);
            }
        });

        // Sidebar toggle
        const overlayMobile = document.getElementById('sidebarOverlay');
        const sidebarMobile = document.getElementById('sidebar-mobile');
        const toggleBtn = document.getElementById('toggleSidebar');

        function openSidebar() {
            sidebarMobile.classList.add('show');
            overlayMobile.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebarMobile.classList.remove('show');
            overlayMobile.classList.remove('show');
            document.body.style.overflow = '';
        }

        toggleBtn?.addEventListener('click', openSidebar);
        overlayMobile.addEventListener('click', closeSidebar);

        // ===== ADMIN/SUPERADMIN SIDEBAR MANAGER =====
        // Check if user is admin or superadmin and load the admin sidebar manager
        document.addEventListener('DOMContentLoaded', function() {
            const userStr = localStorage.getItem('auth_user');
            if (userStr) {
                try {
                    const user = JSON.parse(userStr);
                    // If user is admin or superadmin, load the admin sidebar manager
                    if (['admin', 'superadmin'].includes(user.role)) {
                        // Load the admin sidebar manager script
                        const script = document.createElement('script');
                        script.src = "{{ asset('js/sidebarManager.js') }}";
                        script.onload = function() {
                            // Initialize the sidebar manager for admin users
                            if (typeof SidebarManager !== 'undefined') {
                                SidebarManager.init();
                            }
                        };
                        document.body.appendChild(script);
                    }
                } catch (e) {
                    console.error('Failed to parse user data:', e);
                }
            }
        });
    </script>
@endsection

