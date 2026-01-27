# ChatMessageController - Complete Guide

## Overview

The `ChatMessageController` provides a comprehensive API for managing messages in chat rooms. It includes:

- ✅ **Fetching messages** with pagination and filtering
- ✅ **Sending messages** with real-time broadcasting
- ✅ **Authorization** - Only room members can post
- ✅ **Lazy loading** - Efficient pagination support
- ✅ **Real-time updates** - Broadcasting message events
- ✅ **Message editing** - Update own messages
- ✅ **Message deletion** - Soft delete with broadcast
- ✅ **Reply support** - Thread conversations
- ✅ **Reactions** - Message emoji reactions
- ✅ **Metadata** - Custom message data

---

## API Endpoints

### 1. Fetch Messages (GET)

**Endpoint:** `GET /api/chatrooms/{chatRoom}/messages`

**Description:** Fetch paginated messages from a chat room.

**Authentication:** Required (Bearer token)

**Parameters:**
```json
{
  "per_page": 50,        // Optional: 1-100, default 50
  "page": 1,             // Optional: page number
  "sort": "desc",        // Optional: asc or desc, default desc
  "type": "text"         // Optional: text, image, file, system
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "chat_room_id": 5,
      "user_id": 10,
      "user": {
        "id": 10,
        "first_name": "John",
        "last_name": "Doe",
        "profile_photo": "https://..."
      },
      "content": "Hello everyone!",
      "type": "text",
      "reply_to_id": null,
      "edited_content": null,
      "edited_at": null,
      "is_edited": false,
      "is_deleted": false,
      "is_pinned": false,
      "reaction_count": 2,
      "metadata": null,
      "created_at": "2025-12-31T10:30:00Z",
      "updated_at": "2025-12-31T10:30:00Z"
    }
  ],
  "pagination": {
    "total": 150,
    "per_page": 50,
    "current_page": 1,
    "last_page": 3,
    "from": 1,
    "to": 50
  }
}
```

**Error Responses:**
- `403 Forbidden` - User is not a member of the chat room
- `404 Not Found` - Chat room not found
- `422 Unprocessable Entity` - Invalid parameters

---

### 2. Send Message (POST)

**Endpoint:** `POST /api/chatrooms/{chatRoom}/messages`

**Description:** Send a new message to a chat room.

**Authentication:** Required (Bearer token)

**Request Body:**
```json
{
  "content": "Hello, this is my message!",
  "type": "text",                    // Optional: text, image, file, system
  "reply_to_id": null,               // Optional: ID of message to reply to
  "metadata": {                      // Optional: custom data
    "attachment_url": "https://...",
    "file_size": "2.5MB"
  }
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Message sent successfully",
  "data": {
    "id": 151,
    "chat_room_id": 5,
    "user_id": 10,
    "user": {
      "id": 10,
      "first_name": "John",
      "last_name": "Doe",
      "profile_photo": "https://..."
    },
    "content": "Hello, this is my message!",
    "type": "text",
    "reply_to_id": null,
    "edited_content": null,
    "edited_at": null,
    "is_edited": false,
    "is_deleted": false,
    "is_pinned": false,
    "reaction_count": 0,
    "metadata": {
      "attachment_url": "https://...",
      "file_size": "2.5MB"
    },
    "created_at": "2025-12-31T10:35:00Z",
    "updated_at": "2025-12-31T10:35:00Z"
  }
}
```

**Error Responses:**
- `403 Forbidden` - User is not a member or is muted
- `404 Not Found` - Chat room not found
- `422 Unprocessable Entity` - Validation failed

---

### 3. Get Message (GET)

**Endpoint:** `GET /api/chatrooms/{chatRoom}/messages/{message}`

**Description:** Get a specific message with all details.

**Authentication:** Required (Bearer token)

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 151,
    "chat_room_id": 5,
    "user_id": 10,
    "user": { ... },
    "content": "Hello, this is my message!",
    "type": "text",
    "reply_to_id": null,
    "reply_to": null,
    "edited_content": null,
    "edited_at": null,
    "is_edited": false,
    "is_deleted": false,
    "is_pinned": false,
    "reaction_count": 2,
    "reactions": [
      {
        "emoji": "👍",
        "count": 2,
        "users": [10, 11]
      }
    ],
    "metadata": null,
    "created_at": "2025-12-31T10:35:00Z",
    "updated_at": "2025-12-31T10:35:00Z"
  }
}
```

---

### 4. Update Message (PUT)

**Endpoint:** `PUT /api/chatrooms/{chatRoom}/messages/{message}`

**Description:** Edit a message (only by sender or admin).

**Authentication:** Required (Bearer token)

**Request Body:**
```json
{
  "content": "Updated message content"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Message updated successfully",
  "data": {
    "id": 151,
    "content": "Updated message content",
    "edited_content": "Updated message content",
    "edited_at": "2025-12-31T10:40:00Z",
    "is_edited": true,
    ...
  }
}
```

**Error Responses:**
- `403 Forbidden` - Not message owner or admin
- `404 Not Found` - Message not found
- `422 Unprocessable Entity` - Validation failed

---

### 5. Delete Message (DELETE)

**Endpoint:** `DELETE /api/chatrooms/{chatRoom}/messages/{message}`

**Description:** Delete a message (soft delete).

**Authentication:** Required (Bearer token)

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Message deleted successfully"
}
```

**Error Responses:**
- `403 Forbidden` - Not message owner, moderator, or admin
- `404 Not Found` - Message not found

---

## Authorization Rules

| Action | Owner | Moderator | Admin | Member |
|--------|-------|-----------|-------|--------|
| View Messages | ✅ | ✅ | ✅ | ✅ |
| Send Message | ✅ | ✅ | ✅ | ✅ |
| Edit Own | ✅ | ✅ | ✅ | ✅ |
| Edit Others | ❌ | ❌ | ✅ | ❌ |
| Delete Own | ✅ | ✅ | ✅ | ✅ |
| Delete Others | ❌ | ✅ | ✅ | ❌ |
| Pin Message | ❌ | ✅ | ✅ | ❌ |

---

## Real-time Broadcasting

Messages are broadcast in real-time using Laravel Broadcasting.

**Channel:** `private-chatroom.{chatRoom.id}`

**Event:** `message.sent`

**Broadcast Data:**
```json
{
  "id": 151,
  "chat_room_id": 5,
  "user_id": 10,
  "user": { ... },
  "content": "Hello!",
  "type": "text",
  "created_at": "2025-12-31T10:35:00Z",
  ...
}
```

**JavaScript Example:**
```javascript
Echo.private(`chatroom.5`)
    .listen('message.sent', (event) => {
       
        // Update UI with new message
    });
```

---

## Pagination & Lazy Loading

The controller supports efficient pagination for large message histories.

**Example Request:**
```bash
GET /api/chatrooms/5/messages?page=2&per_page=25&sort=asc
```

**Pagination Response:**
```json
{
  "pagination": {
    "total": 500,
    "per_page": 25,
    "current_page": 2,
    "last_page": 20,
    "from": 26,
    "to": 50
  }
}
```

---

## Usage Examples

### Fetch Recent Messages
```bash
curl -X GET "http://localhost:8000/api/chatrooms/5/messages?per_page=50&sort=desc" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Send a Message
```bash
curl -X POST "http://localhost:8000/api/chatrooms/5/messages" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "content": "Hello everyone!",
    "type": "text"
  }'
```

### Reply to a Message
```bash
curl -X POST "http://localhost:8000/api/chatrooms/5/messages" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "content": "Great point!",
    "type": "text",
    "reply_to_id": 150
  }'
```

### Edit a Message
```bash
curl -X PUT "http://localhost:8000/api/chatrooms/5/messages/151" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "content": "Updated message"
  }'
```

### Delete a Message
```bash
curl -X DELETE "http://localhost:8000/api/chatrooms/5/messages/151" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Key Features

### 1. **Member-Only Access**
- Only active members can view/send messages
- Muted members cannot send messages
- Automatic last-read tracking

### 2. **Message Types**
- `text` - Regular text messages
- `image` - Image attachments
- `file` - File attachments
- `system` - System notifications

### 3. **Message Editing**
- Track original and edited content
- Show edit timestamp
- Broadcast updates in real-time

### 4. **Soft Deletes**
- Messages marked as deleted, not removed
- Can be restored by admin
- Broadcast deletion events

### 5. **Reply Threads**
- Support for message replies
- Load reply context
- Thread conversations

### 6. **Reactions**
- Emoji reactions on messages
- Reaction counts
- User tracking

---

## Database Schema

```sql
CREATE TABLE chat_messages (
    id BIGINT PRIMARY KEY,
    chat_room_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    content LONGTEXT NOT NULL,
    type VARCHAR(50) DEFAULT 'text',
    reply_to_id BIGINT NULL,
    edited_content LONGTEXT NULL,
    edited_at TIMESTAMP NULL,
    reaction_count INT DEFAULT 0,
    is_pinned BOOLEAN DEFAULT FALSE,
    is_deleted BOOLEAN DEFAULT FALSE,
    metadata JSON NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (chat_room_id) REFERENCES chat_rooms(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (reply_to_id) REFERENCES chat_messages(id)
);
```

---

## Testing

See `tests/Feature/ChatMessageControllerTest.php` for comprehensive test examples.

---

## Performance Considerations

1. **Pagination** - Always use pagination for large message histories
2. **Eager Loading** - Relations are eager-loaded to prevent N+1 queries
3. **Indexing** - Database indexes on `chat_room_id`, `user_id`, `created_at`
4. **Caching** - Consider caching frequently accessed messages
5. **Broadcasting** - Use queue for broadcasting to prevent blocking

---

## Security

- ✅ Authentication required for all endpoints
- ✅ Authorization checks for message ownership
- ✅ Input validation on all requests
- ✅ SQL injection prevention via Eloquent ORM
- ✅ XSS protection via JSON responses
- ✅ Rate limiting on API routes


