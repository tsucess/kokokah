# ChatMessageController - Quick Reference

## 📋 Endpoints Summary

| Method | Endpoint | Purpose | Auth |
|--------|----------|---------|------|
| GET | `/api/chatrooms/{id}/messages` | Fetch messages (paginated) | ✅ |
| POST | `/api/chatrooms/{id}/messages` | Send new message | ✅ |
| GET | `/api/chatrooms/{id}/messages/{msg}` | Get specific message | ✅ |
| PUT | `/api/chatrooms/{id}/messages/{msg}` | Edit message | ✅ |
| DELETE | `/api/chatrooms/{id}/messages/{msg}` | Delete message | ✅ |

---

## 🚀 Quick Examples

### Fetch Messages
```bash
# Get first 50 messages
GET /api/chatrooms/5/messages?per_page=50&page=1

# Get messages in ascending order
GET /api/chatrooms/5/messages?sort=asc

# Filter by type
GET /api/chatrooms/5/messages?type=image
```

### Send Message
```bash
POST /api/chatrooms/5/messages
{
  "content": "Hello!",
  "type": "text"
}
```

### Reply to Message
```bash
POST /api/chatrooms/5/messages
{
  "content": "Great point!",
  "reply_to_id": 150
}
```

### Edit Message
```bash
PUT /api/chatrooms/5/messages/151
{
  "content": "Updated content"
}
```

### Delete Message
```bash
DELETE /api/chatrooms/5/messages/151
```

---

## 🔐 Authorization

| Action | Owner | Moderator | Admin |
|--------|-------|-----------|-------|
| View | ✅ | ✅ | ✅ |
| Send | ✅ | ✅ | ✅ |
| Edit Own | ✅ | ✅ | ✅ |
| Edit Others | ❌ | ❌ | ✅ |
| Delete Own | ✅ | ✅ | ✅ |
| Delete Others | ❌ | ✅ | ✅ |

---

## 📊 Response Structure

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description",
  "errors": { ... }
}
```

---

## 🔄 Real-time Broadcasting

**Channel:** `private-chatroom.{id}`

**Event:** `message.sent`

**JavaScript:**
```javascript
Echo.private(`chatroom.5`)
    .listen('message.sent', (event) => {
        console.log('New message:', event);
    });
```

---

## ✅ Validation Rules

| Field | Rules |
|-------|-------|
| content | required, string, max:5000 |
| type | nullable, in:text,image,file,system |
| reply_to_id | nullable, exists:chat_messages,id |
| metadata | nullable, array |

---

## 🎯 Key Features

✅ **Pagination** - Efficient message loading  
✅ **Filtering** - By message type  
✅ **Authorization** - Member-only access  
✅ **Real-time** - Broadcasting updates  
✅ **Editing** - Track edits with timestamps  
✅ **Soft Delete** - Preserve message history  
✅ **Replies** - Thread conversations  
✅ **Reactions** - Emoji support  

---

## 📝 Message Types

- `text` - Regular text messages
- `image` - Image attachments
- `file` - File attachments
- `system` - System notifications

---

## 🛡️ Security

- ✅ Authentication required
- ✅ Authorization checks
- ✅ Input validation
- ✅ SQL injection prevention
- ✅ XSS protection
- ✅ Rate limiting

---

## 📚 Files Created

```
app/Http/Controllers/ChatMessageController.php
app/Http/Requests/StoreChatMessageRequest.php
app/Http/Requests/UpdateChatMessageRequest.php
app/Http/Resources/ChatMessageResource.php
app/Policies/ChatMessagePolicy.php
app/Events/MessageSent.php
tests/Feature/ChatMessageControllerTest.php
docs/CHAT_MESSAGE_CONTROLLER.md
docs/CHAT_MESSAGE_QUICK_REFERENCE.md
```

---

## 🔗 Related Documentation

- [Full Documentation](./CHAT_MESSAGE_CONTROLLER.md)
- [API Endpoints](./API_ENDPOINTS_SUMMARY.md)
- [Chat System Overview](./CHAT_QUICK_REFERENCE.md)


