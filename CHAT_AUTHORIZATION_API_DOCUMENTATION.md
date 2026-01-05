# 📚 Chat Authorization - API Documentation

## 🔐 Authentication

All chat endpoints require authentication using Bearer tokens (Sanctum).

```bash
curl -H "Authorization: Bearer $TOKEN" \
  http://localhost:8000/api/chatrooms/1/messages
```

---

## 📋 Chat Room Endpoints

### Get Chat Room Messages
```
GET /api/chatrooms/{chatRoom}/messages
```

**Authorization:** User must have access to the room

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "chat_room_id": 1,
      "user_id": 1,
      "content": "Hello!",
      "created_at": "2024-01-01T12:00:00Z",
      "updated_at": "2024-01-01T12:00:00Z"
    }
  ]
}
```

**Errors:**
- `401 Unauthorized` - User not authenticated
- `403 Forbidden` - User cannot access room
- `404 Not Found` - Room not found

---

### Send Message
```
POST /api/chatrooms/{chatRoom}/messages
```

**Authorization:** User must be room member and not muted

**Request:**
```json
{
  "content": "Hello everyone!"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "chat_room_id": 1,
    "user_id": 1,
    "content": "Hello everyone!",
    "created_at": "2024-01-01T12:00:00Z"
  }
}
```

**Errors:**
- `401 Unauthorized` - User not authenticated
- `403 Forbidden` - User cannot send messages (muted or not member)
- `422 Unprocessable Entity` - Validation error

---

### Update Message
```
PUT /api/chatrooms/{chatRoom}/messages/{message}
```

**Authorization:** User must be message owner or admin

**Request:**
```json
{
  "content": "Updated message"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "chat_room_id": 1,
    "user_id": 1,
    "content": "Updated message",
    "updated_at": "2024-01-01T12:05:00Z"
  }
}
```

**Errors:**
- `401 Unauthorized` - User not authenticated
- `403 Forbidden` - User cannot edit this message
- `404 Not Found` - Message not found

---

### Delete Message
```
DELETE /api/chatrooms/{chatRoom}/messages/{message}
```

**Authorization:** User must be message owner, room creator, or admin

**Response:**
```json
{
  "success": true,
  "message": "Message deleted successfully"
}
```

**Errors:**
- `401 Unauthorized` - User not authenticated
- `403 Forbidden` - User cannot delete this message
- `404 Not Found` - Message not found

---

## 🏠 Chat Room Management Endpoints

### Get Chat Room
```
GET /api/chatrooms/{chatRoom}
```

**Authorization:** User must have access to the room

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "General",
    "description": "General discussion",
    "type": "general",
    "created_by": 1,
    "is_archived": false,
    "created_at": "2024-01-01T12:00:00Z"
  }
}
```

---

### Update Chat Room
```
PUT /api/chatrooms/{chatRoom}
```

**Authorization:** User must be room creator or admin

**Request:**
```json
{
  "name": "Updated Room Name",
  "description": "Updated description"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Updated Room Name",
    "description": "Updated description"
  }
}
```

---

### Delete Chat Room
```
DELETE /api/chatrooms/{chatRoom}
```

**Authorization:** User must be room creator or admin

**Response:**
```json
{
  "success": true,
  "message": "Chat room deleted successfully"
}
```

---

## 👥 Member Management Endpoints

### Get Room Members
```
GET /api/chatrooms/{chatRoom}/members
```

**Authorization:** User must have access to the room

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "member",
      "is_muted": false
    }
  ]
}
```

---

### Add Member
```
POST /api/chatrooms/{chatRoom}/members
```

**Authorization:** User must be room creator or admin

**Request:**
```json
{
  "user_id": 2
}
```

**Response:**
```json
{
  "success": true,
  "message": "Member added successfully"
}
```

---

### Remove Member
```
DELETE /api/chatrooms/{chatRoom}/members/{user}
```

**Authorization:** User must be room creator or admin

**Response:**
```json
{
  "success": true,
  "message": "Member removed successfully"
}
```

---

### Mute Member
```
POST /api/chatrooms/{chatRoom}/members/{user}/mute
```

**Authorization:** User must be room creator or admin

**Response:**
```json
{
  "success": true,
  "message": "Member muted successfully"
}
```

---

### Unmute Member
```
POST /api/chatrooms/{chatRoom}/members/{user}/unmute
```

**Authorization:** User must be room creator or admin

**Response:**
```json
{
  "success": true,
  "message": "Member unmuted successfully"
}
```

---

## 💬 Message Reaction Endpoints

### React to Message
```
POST /api/chatrooms/{chatRoom}/messages/{message}/reactions
```

**Authorization:** User must have access to the room

**Request:**
```json
{
  "emoji": "👍"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Reaction added successfully"
}
```

---

### Remove Reaction
```
DELETE /api/chatrooms/{chatRoom}/messages/{message}/reactions/{emoji}
```

**Authorization:** User must have added the reaction

**Response:**
```json
{
  "success": true,
  "message": "Reaction removed successfully"
}
```

---

## 📌 Message Pinning Endpoints

### Pin Message
```
POST /api/chatrooms/{chatRoom}/messages/{message}/pin
```

**Authorization:** User must be room creator or admin

**Response:**
```json
{
  "success": true,
  "message": "Message pinned successfully"
}
```

---

### Unpin Message
```
POST /api/chatrooms/{chatRoom}/messages/{message}/unpin
```

**Authorization:** User must be room creator or admin

**Response:**
```json
{
  "success": true,
  "message": "Message unpinned successfully"
}
```

---

## 🔍 Authorization Rules by Endpoint

| Endpoint | Method | Admin | Instructor | Creator | Member | Non-Member |
|----------|--------|-------|-----------|---------|--------|------------|
| /messages | GET | ✅ | ✅ | ✅ | ✅ | ❌ |
| /messages | POST | ✅ | ✅ | ✅ | ✅ | ❌ |
| /messages/{id} | PUT | ✅ | ✅ | ✅ | ✅* | ❌ |
| /messages/{id} | DELETE | ✅ | ✅ | ✅ | ✅* | ❌ |
| /chatroom | GET | ✅ | ✅ | ✅ | ✅ | ❌ |
| /chatroom | PUT | ✅ | ✅ | ✅ | ❌ | ❌ |
| /chatroom | DELETE | ✅ | ✅ | ✅ | ❌ | ❌ |
| /members | GET | ✅ | ✅ | ✅ | ✅ | ❌ |
| /members | POST | ✅ | ✅ | ✅ | ❌ | ❌ |
| /members/{id} | DELETE | ✅ | ✅ | ✅ | ❌ | ❌ |
| /members/{id}/mute | POST | ✅ | ✅ | ✅ | ❌ | ❌ |

*Only own messages

---

## 🚨 Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "message": "This action is unauthorized."
}
```

### 404 Not Found
```json
{
  "message": "Not found."
}
```

### 422 Unprocessable Entity
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "content": ["The content field is required."]
  }
}
```

---

**Status:** ✅ **API DOCUMENTATION COMPLETE!** 📚


