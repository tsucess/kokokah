# Real-time Chat - Advanced Features Guide

## 🎯 Overview

This guide covers advanced features for the real-time chat system:
- Emoji Reactions
- File Uploads
- Message Search
- Message Pinning
- User Typing Indicator
- Online Status

---

## 😊 Emoji Reactions

### API Endpoints

#### Add Reaction
```http
POST /api/chatrooms/{chatRoomId}/messages/{messageId}/reactions
Content-Type: application/json
Authorization: Bearer {token}

{
    "emoji": "👍"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Reaction added successfully",
    "data": {
        "id": 1,
        "message_id": 5,
        "user_id": 10,
        "emoji": "👍",
        "created_at": "2025-12-31T10:30:00Z"
    }
}
```

#### Remove Reaction
```http
DELETE /api/chatrooms/{chatRoomId}/messages/{messageId}/reactions/{emoji}
Authorization: Bearer {token}
```

#### Get Message Reactions
```http
GET /api/chatrooms/{chatRoomId}/messages/{messageId}/reactions
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "emoji": "👍",
            "count": 2,
            "users": [
                {"id": 10, "name": "John Doe"},
                {"id": 11, "name": "Jane Smith"}
            ]
        },
        {
            "emoji": "❤️",
            "count": 1,
            "users": [
                {"id": 10, "name": "John Doe"}
            ]
        }
    ]
}
```

### JavaScript Implementation

```javascript
// Add reaction
async function addReaction(chatRoomId, messageId, emoji) {
    const response = await fetch(
        `/api/chatrooms/${chatRoomId}/messages/${messageId}/reactions`,
        {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ emoji })
        }
    );
    return response.json();
}

// Remove reaction
async function removeReaction(chatRoomId, messageId, emoji) {
    const response = await fetch(
        `/api/chatrooms/${chatRoomId}/messages/${messageId}/reactions/${emoji}`,
        {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        }
    );
    return response.json();
}

// Get reactions
async function getReactions(chatRoomId, messageId) {
    const response = await fetch(
        `/api/chatrooms/${chatRoomId}/messages/${messageId}/reactions`,
        {
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        }
    );
    return response.json();
}
```

---

## 📁 File Uploads

### API Endpoint

```http
POST /api/chatrooms/{chatRoomId}/messages
Content-Type: multipart/form-data
Authorization: Bearer {token}

content: "Check this file!"
type: "file"
file: <binary file data>
```

### JavaScript Implementation

```javascript
async function uploadFile(chatRoomId, file, message) {
    const formData = new FormData();
    formData.append('content', message);
    formData.append('type', 'file');
    formData.append('file', file);

    const response = await fetch(
        `/api/chatrooms/${chatRoomId}/messages`,
        {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: formData
        }
    );
    return response.json();
}

// Usage
const fileInput = document.getElementById('fileInput');
fileInput.addEventListener('change', async (e) => {
    const file = e.target.files[0];
    const result = await uploadFile(chatRoomId, file, 'Check this out!');
    console.log(result);
});
```

---

## 🔍 Message Search

### API Endpoint

```http
GET /api/chatrooms/{chatRoomId}/messages/search?q=hello&type=text&user_id=10
Authorization: Bearer {token}
```

### Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| q | string | Search query |
| type | string | Message type (text, image, file) |
| user_id | integer | Filter by user |
| from_date | date | Start date |
| to_date | date | End date |
| per_page | integer | Results per page |

### JavaScript Implementation

```javascript
async function searchMessages(chatRoomId, query, filters = {}) {
    const params = new URLSearchParams({
        q: query,
        ...filters
    });

    const response = await fetch(
        `/api/chatrooms/${chatRoomId}/messages/search?${params}`,
        {
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        }
    );
    return response.json();
}

// Usage
const results = await searchMessages(chatRoomId, 'hello', {
    type: 'text',
    user_id: 10,
    per_page: 20
});
```

---

## 📌 Message Pinning

### API Endpoints

#### Pin Message
```http
POST /api/chatrooms/{chatRoomId}/messages/{messageId}/pin
Authorization: Bearer {token}
```

#### Unpin Message
```http
DELETE /api/chatrooms/{chatRoomId}/messages/{messageId}/pin
Authorization: Bearer {token}
```

#### Get Pinned Messages
```http
GET /api/chatrooms/{chatRoomId}/messages/pinned
Authorization: Bearer {token}
```

### JavaScript Implementation

```javascript
async function pinMessage(chatRoomId, messageId) {
    const response = await fetch(
        `/api/chatrooms/${chatRoomId}/messages/${messageId}/pin`,
        {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        }
    );
    return response.json();
}

async function unpinMessage(chatRoomId, messageId) {
    const response = await fetch(
        `/api/chatrooms/${chatRoomId}/messages/${messageId}/pin`,
        {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        }
    );
    return response.json();
}

async function getPinnedMessages(chatRoomId) {
    const response = await fetch(
        `/api/chatrooms/${chatRoomId}/messages/pinned`,
        {
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        }
    );
    return response.json();
}
```

---

## 👤 User Typing Indicator

### Broadcasting Event

**Event Name:** `user.typing`

**Channel:** `chatroom.{id}` (Public)

**Data:**
```json
{
    "user_id": 10,
    "user_name": "John Doe",
    "chat_room_id": 5,
    "timestamp": "2025-12-31T10:45:00Z"
}
```

### JavaScript Implementation

```javascript
// Listen for typing indicator
Echo.channel(`chatroom.${chatRoomId}`)
    .listen('user.typing', (event) => {
        showTypingIndicator(event.user_name);
        
        // Hide after 3 seconds
        setTimeout(() => {
            hideTypingIndicator(event.user_name);
        }, 3000);
    });

// Broadcast typing (throttled)
let typingTimeout;
document.getElementById('messageInput').addEventListener('input', () => {
    clearTimeout(typingTimeout);
    
    // Broadcast typing
    fetch(`/api/chatrooms/${chatRoomId}/typing`, {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            user_id: userId,
            user_name: userName
        })
    });
    
    // Stop broadcasting after 1 second of inactivity
    typingTimeout = setTimeout(() => {
        // Stop typing broadcast
    }, 1000);
});
```

---

## 🟢 Online Status

### Broadcasting Event

**Event Name:** `user.online`

**Channel:** `chatroom.{id}` (Public)

**Data:**
```json
{
    "user_id": 10,
    "user_name": "John Doe",
    "status": "online",
    "timestamp": "2025-12-31T10:45:00Z"
}
```

### JavaScript Implementation

```javascript
// Listen for online status
Echo.channel(`chatroom.${chatRoomId}`)
    .listen('user.online', (event) => {
        updateUserStatus(event.user_id, event.status);
    });

// Broadcast online status
window.addEventListener('beforeunload', () => {
    fetch(`/api/chatrooms/${chatRoomId}/status`, {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            status: 'offline'
        })
    });
});
```

---

## 🧪 Testing Advanced Features

### Test Reactions
```bash
php artisan test tests/Feature/ChatReactionsTest.php
```

### Test Real-time Chat
```bash
php artisan test tests/Feature/RealtimeChatTest.php
```

### Test All Chat Features
```bash
php artisan test tests/Feature/ChatMessageControllerTest.php
php artisan test tests/Feature/RealtimeChatTest.php
php artisan test tests/Feature/ChatReactionsTest.php
```

---

## 📊 Database Schema

### message_reactions table
```sql
CREATE TABLE message_reactions (
    id BIGINT PRIMARY KEY,
    message_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    emoji VARCHAR(10) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (message_id) REFERENCES chat_messages(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    UNIQUE KEY (message_id, user_id, emoji)
);
```

---

## 🚀 Implementation Checklist

- [ ] Emoji reactions API endpoints
- [ ] File upload functionality
- [ ] Message search functionality
- [ ] Message pinning
- [ ] Typing indicator
- [ ] Online status
- [ ] Tests for all features
- [ ] Frontend integration
- [ ] Real-time broadcasting

---

## 📚 Related Documentation

- [REALTIME_CHAT_EVENTS.md](./REALTIME_CHAT_EVENTS.md)
- [REALTIME_CHAT_IMPLEMENTATION.md](./REALTIME_CHAT_IMPLEMENTATION.md)
- [REALTIME_CHAT_ENV_SETUP.md](./REALTIME_CHAT_ENV_SETUP.md)


