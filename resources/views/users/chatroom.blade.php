
@extends('layouts.usertemplate')

@section('content')
<style>
    .chat-app-container {
        height: calc(100vh - 100px);
        overflow: hidden;
    }

    .sidebar-left {
        border-right: 1px solid #e0e0e0;
        overflow-y: auto;
        max-height: calc(100vh - 100px);
    }

    .chat-panel-right {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 100px);
    }

    .chat-header {
        padding: 1rem;
        border-bottom: 1px solid #e0e0e0;
        background: #f8f9fa;
    }

    .chat-history {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
    }

    .chat-input-area {
        padding: 1rem;
        border-top: 1px solid #e0e0e0;
        background: #f8f9fa;
    }

    .sidebar-item {
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        color: #333;
        display: block;
    }

    .sidebar-item:hover {
        background: #f0f0f0;
    }

    .sidebar-item.active {
        background: #e8f4f8;
        border-left: 3px solid var(--bs-dark-teal);
    }

    .chat-message {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .message-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .message-content {
        flex: 1;
    }

    .message-user {
        font-weight: 600;
        color: #333;
    }

    .message-timestamp {
        font-size: 0.85rem;
        color: #999;
        margin-left: 0.5rem;
    }

    .welcome-message {
        text-align: center;
        padding: 2rem;
    }

    .welcome-icon {
        width: 80px;
        height: 80px;
        background: var(--bs-dark-teal);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }

    .btn-send {
        background: var(--bs-dark-teal);
        color: white;
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
    }

    .btn-send:hover {
        background: #003a42;
        color: white;
    }

    .new-message-badge {
        background: #ff6b6b;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        margin-left: auto;
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
            box-shadow: 2px 0 15px rgba(0,0,0,0.15);
        }

        .sidebar-mobile.show {
            left: 0;
        }

        .chat-panel-right {
            width: 100%;
        }
    }

    .loading-spinner {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
    }

    .spinner-border {
        width: 2rem;
        height: 2rem;
    }
</style>
<main>
<div class="container-fluid py-4">
    <div class="overlay" id="sidebarOverlay"></div>
    <div id="sidebar-mobile" class="sidebar-mobile d-lg-none">
        <div class="input-group mb-4">
            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control border-start-0 ps-0" placeholder="Find a Conversation" id="searchMobile">
        </div>
        <h6 class="text-muted text-uppercase small mb-2">Conversations</h6>
        <div id="conversationListMobile"></div>
    </div>

    <div class="row g-0 chat-app-container">
        <div class="col-lg-4 d-none d-lg-block sidebar-left">
            <div class="input-group mb-4">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control border-start-0 ps-0" placeholder="Find a Conversation" id="searchDesktop">
            </div>
            <h6 class="text-muted text-uppercase small mb-2">Conversations</h6>
            <div id="conversationListDesktop"></div>
        </div>

        <div class="col-12 col-lg-8 chat-panel-right">
            <div class="chat-header">
                <button class="btn btn-outline-secondary d-lg-none" id="toggleSidebar">
                    <i class="bi bi-list"></i>
                </button>
                <span id="currentConversationName">#General</span>
            </div>

            <div class="chat-history" id="chatHistory">
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div class="chat-input-area">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 d-flex align-items-center border rounded-pill py-2 px-3 me-3" style="border-color: var(--bs-light-gray) !important;">
                        <i class="bi bi-paperclip me-2 text-muted"></i>
                        <input type="text" class="form-control border-0 p-0 shadow-none" id="messageInput" placeholder="Type a message..." style="height: auto;">
                    </div>
                    <div class="d-flex gap-3 text-secondary fs-5 me-3 d-none d-md-flex">
                        <i class="bi bi-emoji-smile-fill" style="cursor: pointer;"></i>
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
<script src="{{ asset('js/api/baseApiClient.js') }}"></script>
<script src="{{ asset('js/api/conversationApiClient.js') }}"></script>
<script>
    // State management
    let currentConversationId = null;
    let currentCourseId = null;
    let conversations = [];
    let currentMessages = [];

    // Get course ID from URL or session
    function getCourseId() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('course_id') || sessionStorage.getItem('currentCourseId');
    }

    // Initialize chatroom
    async function initializeChatroom() {
        currentCourseId = getCourseId();
        if (!currentCourseId) {
            showError('No course selected. Please select a course first.');
            return;
        }

        await loadConversations();
        if (conversations.length > 0) {
            selectConversation(conversations[0].id);
        }
    }

    // Load conversations for the course
    async function loadConversations() {
        try {
            const response = await conversationApiClient.getConversationsByCourse(currentCourseId);
            if (response.success) {
                conversations = response.data.data || [];
                renderConversationList();
            }
        } catch (error) {
            showError('Failed to load conversations');
            console.error(error);
        }
    }

    // Render conversation list
    function renderConversationList() {
        const listDesktop = document.getElementById('conversationListDesktop');
        const listMobile = document.getElementById('conversationListMobile');

        let html = '';
        conversations.forEach(conv => {
            const isActive = conv.id === currentConversationId ? 'active' : '';
            html += `
                <a href="#" class="sidebar-item ${isActive}" data-conversation-id="${conv.id}" onclick="selectConversation(${conv.id}); return false;">
                    <div class="d-flex align-items-center">
                        <span class="badge me-2 d-flex justify-content-center align-items-center" style="background: #114243; border-radius:20px; width: 25px;">
                            <i class="bi bi-hash text-white"></i>
                        </span>
                        <span class="flex-grow-1">${conv.name}</span>
                        ${conv.unread_count ? `<span class="new-message-badge">${conv.unread_count}</span>` : ''}
                    </div>
                </a>
            `;
        });

        listDesktop.innerHTML = html;
        listMobile.innerHTML = html;
    }

    // Select a conversation
    async function selectConversation(conversationId) {
        currentConversationId = conversationId;
        const conversation = conversations.find(c => c.id === conversationId);

        if (conversation) {
            document.getElementById('currentConversationName').textContent = '#' + conversation.name;
            await loadMessages();
            updateActiveConversation();
        }
    }

    // Load messages for current conversation
    async function loadMessages() {
        try {
            document.getElementById('chatHistory').innerHTML = '<div class="loading-spinner"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

            const response = await conversationApiClient.getMessages(currentConversationId);
            if (response.success) {
                currentMessages = response.data.data || [];
                renderMessages();
                scrollToBottom();
            }
        } catch (error) {
            showError('Failed to load messages');
            console.error(error);
        }
    }

    // Render messages
    function renderMessages() {
        const chatHistory = document.getElementById('chatHistory');

        if (currentMessages.length === 0) {
            chatHistory.innerHTML = `
                <div class="welcome-message mb-5">
                    <div class="welcome-icon mx-auto mb-3">
                        <i class="bi bi-hash text-white"></i>
                    </div>
                    <h4 class="fw-bold mb-1" style="color: var(--color-primary-button: #004A53);">Welcome to #${conversations.find(c => c.id === currentConversationId)?.name || 'General'}</h4>
                    <p class="text-muted">This is the start of the conversation</p>
                </div>
            `;
            return;
        }

        let html = '';
        currentMessages.forEach(msg => {
            const timeAgo = getTimeAgo(msg.created_at);
            html += `
                <div class="chat-message">
                    <img src="${msg.user.avatar || 'https://via.placeholder.com/40'}" alt="Avatar" class="message-avatar">
                    <div class="message-content">
                        <span class="message-user">${msg.user.name}</span>
                        <span class="message-timestamp">${timeAgo}</span>
                        <p class="mb-1">${escapeHtml(msg.message)}</p>
                    </div>
                </div>
            `;
        });

        chatHistory.innerHTML = html;
    }

    // Send message
    async function sendMessage() {
        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value.trim();

        if (!message || !currentConversationId) {
            return;
        }

        try {
            const response = await conversationApiClient.sendMessage(currentConversationId, message);
            if (response.success) {
                messageInput.value = '';
                currentMessages.push(response.data);
                renderMessages();
                scrollToBottom();
            }
        } catch (error) {
            showError('Failed to send message');
            console.error(error);
        }
    }

    // Helper functions
    function updateActiveConversation() {
        document.querySelectorAll('.sidebar-item').forEach(item => {
            item.classList.remove('active');
        });
        document.querySelectorAll(`[data-conversation-id="${currentConversationId}"]`).forEach(item => {
            item.classList.add('active');
        });
    }

    function scrollToBottom() {
        const chatHistory = document.getElementById('chatHistory');
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    function getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        if (seconds < 60) return 'now';
        if (seconds < 3600) return Math.floor(seconds / 60) + 'm';
        if (seconds < 86400) return Math.floor(seconds / 3600) + 'h';
        return Math.floor(seconds / 86400) + 'd';
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function showError(message) {
        if (typeof ToastNotification !== 'undefined') {
            ToastNotification.error(message);
        } else {
            alert(message);
        }
    }

    // Mobile sidebar toggle
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

    // Event listeners
    document.getElementById('sendBtn').addEventListener('click', sendMessage);
    document.getElementById('messageInput').addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', initializeChatroom);
</script>

@endsection
