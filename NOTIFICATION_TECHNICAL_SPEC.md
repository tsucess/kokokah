# Notification System - Technical Specification

---

## 🏗️ Architecture Overview

```
┌─────────────────────────────────────────────────────────┐
│                    User Dashboard                        │
│  ┌──────────────────────────────────────────────────┐   │
│  │ Topbar                                           │   │
│  │ [🏆 Badges] [⭐ Points] [🔔 Bell] [❓ Help]     │   │
│  │                          ↓                       │   │
│  │                    Notification Modal            │   │
│  │                  [Announcements|Messages|Notif]  │   │
│  └──────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
         ↓
┌─────────────────────────────────────────────────────────┐
│              Frontend JavaScript Layer                   │
│  ┌──────────────────────────────────────────────────┐   │
│  │ DashboardModule                                  │   │
│  │ - initNotificationBell()                         │   │
│  │ - loadNotifications()                            │   │
│  │ - updateNotificationBadge()                      │   │
│  │ - openNotificationModal()                        │   │
│  └──────────────────────────────────────────────────┘   │
│  ┌──────────────────────────────────────────────────┐   │
│  │ NotificationApiClient                            │   │
│  │ - getNotifications(filters)                      │   │
│  │ - getUnreadCount()                               │   │
│  │ - markAsRead(id)                                 │   │
│  │ - markAllAsRead()                                │   │
│  └──────────────────────────────────────────────────┘   │
│  ┌──────────────────────────────────────────────────┐   │
│  │ NotificationModal Component                      │   │
│  │ - renderTabs()                                   │   │
│  │ - renderNotifications()                          │   │
│  │ - handleReadMore()                               │   │
│  └──────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
         ↓
┌─────────────────────────────────────────────────────────┐
│              Backend API Layer (Laravel)                 │
│  ┌──────────────────────────────────────────────────┐   │
│  │ NotificationController                           │   │
│  │ - index() → GET /notifications                   │   │
│  │ - markAsRead() → PUT /notifications/{id}/read    │   │
│  │ - markAllAsRead() → PUT /notifications/read-all  │   │
│  │ - getPreferences() → GET /notifications/prefs    │   │
│  └──────────────────────────────────────────────────┘   │
│  ┌──────────────────────────────────────────────────┐   │
│  │ Notification Model                               │   │
│  │ - user_id, type, title, message                  │   │
│  │ - priority, category, read_at, action_url        │   │
│  └──────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
```

---

## 📋 API Specifications

### GET /notifications
**Purpose:** Fetch user notifications with optional filters

**Query Parameters:**
```
status: 'read' | 'unread' (optional)
type: 'course' | 'assignment' | 'quiz' | 'system' | 'payment' | 'social' (optional)
page: integer (optional, default: 1)
per_page: integer (optional, default: 15, max: 100)
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": "uuid",
      "type": "announcement",
      "title": "New Course Available",
      "message": "Check out our new Python course...",
      "category": "course",
      "priority": "normal",
      "read_at": null,
      "action_url": "/courses/python-101",
      "created_at": "2025-01-05T10:00:00Z"
    }
  ],
  "pagination": {
    "total": 25,
    "per_page": 15,
    "current_page": 1,
    "last_page": 2
  }
}
```

### PUT /notifications/{id}/read
**Purpose:** Mark single notification as read

**Response:**
```json
{
  "success": true,
  "message": "Notification marked as read"
}
```

### PUT /notifications/read-all
**Purpose:** Mark all notifications as read

**Response:**
```json
{
  "success": true,
  "message": "All notifications marked as read"
}
```

---

## 🎨 UI Components

### Badge Component
```
Position: Absolute (top-right of bell icon)
Size: 20px × 20px
Background: #fdaf22 (orange)
Color: #000 (black text)
Border: 2px white
Border-radius: 50%
Font-size: 12px
Font-weight: bold
Display: flex, center
Z-index: 10
```

### Modal Component
```
Width: 600px (desktop), 90% (tablet), 100% (mobile)
Max-height: 80vh
Overflow: auto
Border-radius: 10px
Box-shadow: 0 4px 12px rgba(0,0,0,0.15)
```

### Notification Item
```
Padding: 12px
Border-bottom: 1px solid #eee
Display: flex
Gap: 12px

Title: font-weight: 600, font-size: 14px
Snippet: font-size: 13px, color: #666, max-lines: 2
Button: padding: 6px 12px, bg: #004a53, color: white
```

---

## 🔄 State Management

### Local State
```javascript
{
  notifications: [],
  unreadCount: 0,
  selectedTab: 'announcements',
  isLoading: false,
  error: null,
  lastRefresh: timestamp
}
```

### Cache Strategy
- Unread count: Cache 30 seconds
- Notifications: Cache 60 seconds
- Auto-refresh: Every 60 seconds

---

## ⚡ Performance Targets

| Metric | Target | Current |
|--------|--------|---------|
| Initial load | <500ms | TBD |
| Badge update | <200ms | TBD |
| Modal open | <300ms | TBD |
| API response | <1s | TBD |
| Memory usage | <5MB | TBD |

---

## 🔐 Security Considerations

1. **Authentication:** All endpoints require auth:sanctum
2. **Authorization:** Users can only see their own notifications
3. **Input Validation:** All filters validated server-side
4. **XSS Prevention:** Sanitize notification content
5. **CSRF Protection:** Use CSRF tokens for state-changing requests

---

## 📊 Database Queries

### Optimizations
- Index on (user_id, read_at) for unread queries
- Index on (user_id, created_at) for sorting
- Eager load relationships
- Paginate large result sets

### Query Examples
```sql
-- Get unread count
SELECT COUNT(*) FROM notifications 
WHERE user_id = ? AND read_at IS NULL

-- Get recent notifications
SELECT * FROM notifications 
WHERE user_id = ? 
ORDER BY created_at DESC 
LIMIT 15
```

