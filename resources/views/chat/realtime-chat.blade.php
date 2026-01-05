@extends('layouts.app')

@section('content')
<div class="container-fluid h-100">
    <div class="row h-100">
        <!-- Chat Messages Area -->
        <div class="col-md-8 d-flex flex-column">
            <div class="card h-100">
                <!-- Chat Header -->
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ $chatRoom->name }}</h5>
                            <small class="text-light">{{ $chatRoom->description }}</small>
                        </div>
                        <div>
                            <span class="badge bg-success" id="onlineCount">0 online</span>
                        </div>
                    </div>
                </div>

                <!-- Messages Container -->
                <div class="card-body overflow-auto" id="messagesContainer" style="height: 500px;">
                    <div id="messagesList" class="messages-list">
                        <!-- Messages will be loaded here -->
                    </div>
                </div>

                <!-- Typing Indicator -->
                <div id="typingIndicator" class="px-3 py-2 text-muted small" style="display: none;">
                    <span id="typingUsers"></span> is typing...
                </div>

                <!-- Message Input -->
                <div class="card-footer">
                    <form id="messageForm" class="d-flex gap-2">
                        <input 
                            type="text" 
                            id="messageInput" 
                            class="form-control" 
                            placeholder="Type a message..."
                            autocomplete="off"
                        >
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Send
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Members List -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Members ({{ $chatRoom->users()->count() }})</h6>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    <div id="membersList" class="list-group">
                        @foreach($chatRoom->users as $user)
                            <div class="list-group-item d-flex justify-content-between align-items-center" 
                                 data-user-id="{{ $user->id }}">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->profile_photo ?? 'https://via.placeholder.com/32' }}" 
                                         alt="{{ $user->first_name }}" 
                                         class="rounded-circle me-2" 
                                         width="32" 
                                         height="32">
                                    <div>
                                        <div class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        <small class="text-muted user-status" data-user-id="{{ $user->id }}">offline</small>
                                    </div>
                                </div>
                                <span class="badge bg-secondary user-role">{{ $user->pivot->role ?? 'member' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Chat Info -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Chat Info</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-6">Type:</dt>
                        <dd class="col-sm-6">
                            <span class="badge bg-info">{{ ucfirst($chatRoom->type) }}</span>
                        </dd>

                        <dt class="col-sm-6">Created:</dt>
                        <dd class="col-sm-6">
                            <small>{{ $chatRoom->created_at->format('M d, Y') }}</small>
                        </dd>

                        <dt class="col-sm-6">Messages:</dt>
                        <dd class="col-sm-6">
                            <small id="messageCount">0</small>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Meta tags for authentication -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="api-token" content="{{ auth()->user()->currentAccessToken()?->plainTextToken ?? '' }}">
<meta name="chat-room-id" content="{{ $chatRoom->id }}">
<meta name="current-user-id" content="{{ auth()->id() }}">

@endsection

@section('scripts')
<script type="module">
    import RealtimeChat from '/resources/js/modules/realtime-chat.js';

    const chatRoomId = document.querySelector('meta[name="chat-room-id"]').content;
    const currentUserId = document.querySelector('meta[name="current-user-id"]').content;
    const apiToken = document.querySelector('meta[name="api-token"]').content;

    // Initialize real-time chat
    const chat = new RealtimeChat(chatRoomId, { debug: true });

    // Load initial messages
    async function loadMessages() {
        try {
            const response = await fetch(`/api/chatrooms/${chatRoomId}/messages?per_page=50`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            
            if (data.success) {
                const messagesList = document.getElementById('messagesList');
                messagesList.innerHTML = '';

                data.data.forEach(message => {
                    addMessageToUI(message);
                });

                document.getElementById('messageCount').textContent = data.pagination.total;
                scrollToBottom();
            }
        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }

    // Add message to UI
    function addMessageToUI(message) {
        const messagesList = document.getElementById('messagesList');
        const isOwnMessage = parseInt(message.user_id) === parseInt(currentUserId);

        const messageEl = document.createElement('div');
        messageEl.className = `message mb-3 ${isOwnMessage ? 'text-end' : ''}`;
        messageEl.id = `message-${message.id}`;
        messageEl.innerHTML = `
            <div class="d-flex ${isOwnMessage ? 'justify-content-end' : ''}">
                <div class="card ${isOwnMessage ? 'bg-primary text-white' : 'bg-light'}" style="max-width: 70%;">
                    <div class="card-body p-2">
                        ${!isOwnMessage ? `<small class="fw-bold d-block">${message.user.first_name} ${message.user.last_name}</small>` : ''}
                        <p class="mb-1">${escapeHtml(message.content)}</p>
                        <small class="text-muted d-block">${new Date(message.created_at).toLocaleTimeString()}</small>
                        ${message.is_edited ? '<small class="text-muted">(edited)</small>' : ''}
                    </div>
                </div>
            </div>
        `;

        messagesList.appendChild(messageEl);
    }

    // Send message
    document.getElementById('messageForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const content = document.getElementById('messageInput').value.trim();
        if (!content) return;

        try {
            const response = await fetch(`/api/chatrooms/${chatRoomId}/messages`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ content })
            });

            const data = await response.json();

            if (data.success) {
                document.getElementById('messageInput').value = '';
                // Message will be added via real-time event
            } else {
                alert('Error sending message: ' + data.message);
            }
        } catch (error) {
            console.error('Error sending message:', error);
            alert('Error sending message');
        }
    });

    // Listen for real-time events
    chat.onMessageSent((event) => {
        addMessageToUI(event.data);
        scrollToBottom();
    });

    chat.onMessageUpdated((event) => {
        const messageEl = document.getElementById(`message-${event.data.id}`);
        if (messageEl) {
            messageEl.innerHTML = `
                <div class="d-flex">
                    <div class="card bg-light" style="max-width: 70%;">
                        <div class="card-body p-2">
                            <p class="mb-1">${escapeHtml(event.data.edited_content || event.data.content)}</p>
                            <small class="text-muted d-block">${new Date(event.data.updated_at).toLocaleTimeString()}</small>
                            <small class="text-muted">(edited)</small>
                        </div>
                    </div>
                </div>
            `;
        }
    });

    chat.onMessageDeleted((event) => {
        const messageEl = document.getElementById(`message-${event.data.id}`);
        if (messageEl) {
            messageEl.remove();
        }
    });

    chat.onUserTyping((event) => {
        const indicator = document.getElementById('typingIndicator');
        const typingUsers = document.getElementById('typingUsers');
        
        typingUsers.textContent = event.user_name;
        indicator.style.display = 'block';

        setTimeout(() => {
            indicator.style.display = 'none';
        }, 3000);
    });

    // Typing indicator
    let typingTimeout;
    document.getElementById('messageInput').addEventListener('input', () => {
        clearTimeout(typingTimeout);
        
        chat.broadcastTyping(currentUserId, '{{ auth()->user()->first_name }}');

        typingTimeout = setTimeout(() => {
            // Stop typing
        }, 3000);
    });

    // Scroll to bottom
    function scrollToBottom() {
        const container = document.getElementById('messagesContainer');
        container.scrollTop = container.scrollHeight;
    }

    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Load initial messages
    loadMessages();

    // Refresh online count every 5 seconds
    setInterval(() => {
        const onlineCount = document.querySelectorAll('.user-status:contains("online")').length;
        document.getElementById('onlineCount').textContent = `${onlineCount} online`;
    }, 5000);
</script>
@endsection

@section('styles')
<style>
    .messages-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .message {
        animation: slideIn 0.3s ease-in-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card {
        border-radius: 0.5rem;
    }

    #messagesContainer {
        background-color: #f8f9fa;
    }
</style>
@endsection

