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

        .chat-message.current-user-message .message-timestamp {
            color: #999;
            font-size: 0.85rem;
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

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .loading-spinner.show {
            display: block;
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
                console.log('Messages to render:', messages);

                renderMessages(messages);
            } catch (error) {
                console.error('Error loading messages:', error);
            }
        }

        // Render messages
        function renderMessages(messages) {
            console.log('renderMessages called with:', messages);

            // Get current user ID from localStorage
            let currentUserId = null;
            const authUserStr = localStorage.getItem('auth_user');
            if (authUserStr) {
                try {
                    const authUser = JSON.parse(authUserStr);
                    currentUserId = authUser?.id;
                } catch (e) {
                    console.error('Failed to parse auth_user:', e);
                }
            }
            console.log('Current user ID:', currentUserId);

            if (!messages || messages.length === 0) {
                console.log('No messages to render');
                document.getElementById('chat-messages').innerHTML = '<p class="text-muted text-center">No messages yet</p>';
                return;
            }

            const html = messages.map(msg => {
                const isCurrentUser = msg.user_id === currentUserId || msg.user_id == currentUserId;
                const userFirstName = msg.user?.first_name || 'Unknown';
                const userLastName = msg.user?.last_name || 'User';
                const profilePhoto = msg.user?.profile_photo || '/images/default-avatar.png';
                const messageTime = new Date(msg.created_at).toLocaleTimeString();
                const messageContent = msg.content || '';

                return `
                    <div class="chat-message ${isCurrentUser ? 'current-user-message' : ''}">
                        ${!isCurrentUser ? `<img src="${profilePhoto}" alt="Avatar" class="message-avatar" onerror="this.src='/images/default-avatar.png'">` : ''}
                        <div class="message-content">
                            ${!isCurrentUser ? `<span class="message-user">${userFirstName} ${userLastName}</span>` : ''}
                            <span class="message-timestamp">${messageTime}</span>
                            <p class="mb-1">${messageContent}</p>
                        </div>
                    </div>
                `;
            }).join('');

            console.log('Rendered HTML length:', html.length);
            document.getElementById('chat-messages').innerHTML = html;
        }

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
    </script>
@endsection

