# Announcement System - Quick Reference Guide

## 🚀 Quick Start

### For Admins
```
CREATE:
1. Go to /announcement
2. Click "Create New Announcement"
3. Fill form (title, type, priority, audience, description)
4. Click "Publish" or "Save As Draft"

EDIT:
1. Go to /announcement
2. Click three vertical dots on announcement
3. Click "Edit"
4. Modify fields
5. Click "Save Changes"

DELETE:
1. Go to /announcement
2. Click three vertical dots on announcement
3. Click "Delete"
4. Confirm deletion
```

### For Students
```
1. Go to /announcement
2. View announcements
3. Click announcement to view details
4. Filter by type using tabs
5. No create/edit/delete options
```

## 📁 Key Files

| File | Purpose |
|------|---------|
| `app/Models/Announcement.php` | Database model |
| `app/Http/Controllers/AnnouncementController.php` | API logic |
| `routes/api.php` | API endpoints |
| `resources/views/admin/createannouncement.blade.php` | Admin create form |
| `resources/views/admin/announcement.blade.php` | Admin list view |
| `resources/views/users/userannouncement.blade.php` | Student view |
| `public/js/announcements.js` | Frontend logic |

## 🔌 API Endpoints

### List Announcements
```
GET /api/announcements
GET /api/announcements?type=Exams
GET /api/announcements?status=published
GET /api/announcements?search=exam
```

### Create Announcement (Admin)
```
POST /api/announcements
{
  "title": "string",
  "description": "string",
  "type": "Exams|Events|Alert|General Info",
  "priority": "Info|Urgent|Warning",
  "audience": "All students|Specific class|Specific level",
  "status": "draft|published"
}
```

### Get Single Announcement
```
GET /api/announcements/{id}
```

### Update Announcement (Admin)
```
PUT /api/announcements/{id}
{
  "title": "string",
  "status": "draft|published|archived"
}
```

### Delete Announcement (Admin)
```
DELETE /api/announcements/{id}
```

### Get Type Counts
```
GET /api/announcements/types
```

## 🎨 Announcement Types

| Type | Use Case |
|------|----------|
| **Exams** | Exam schedules, results |
| **Events** | School events, activities |
| **Alert** | Urgent alerts, warnings |
| **General Info** | General information |

## 🚨 Priority Levels

| Priority | Color | Use Case |
|----------|-------|----------|
| **Info** | Black | Regular information |
| **Urgent** | Orange | Important updates |
| **Warning** | Yellow | Warnings, cautions |

## 👥 Audience Options

| Audience | Description |
|----------|-------------|
| **All students** | Visible to all |
| **Specific class** | Target specific class |
| **Specific level** | Target specific level |

## 📊 Database Fields

```
id              - Primary key
user_id         - Creator (admin)
title           - Announcement title
description     - Full text
type            - Category
priority        - Importance level
audience        - Target audience
audience_value  - Specific audience ID
scheduled_at    - Future publish time
status          - draft/published/archived
view_count      - Number of views
created_at      - Creation timestamp
updated_at      - Last update timestamp
deleted_at      - Soft delete timestamp
```

## 🔐 Authorization

| Action | Required Role |
|--------|---------------|
| View announcements | Authenticated user |
| Create announcement | Admin |
| Update announcement | Admin |
| Delete announcement | Admin |

## 🛠️ JavaScript Classes

### AnnouncementManager (Base)
```javascript
new AnnouncementManager('/api/announcements')
- loadAnnouncements()
- submitAnnouncement(status)
- getToken()
- getTimeAgo(date)
```

### AdminAnnouncementManager
```javascript
extends AnnouncementManager
- setupTabFilters()
- updateTabCounts()
- renderAnnouncements()
- editAnnouncement(id)          // Opens edit modal
- submitEditAnnouncement()       // Saves changes
- deleteAnnouncement(id)         // Opens delete modal
- confirmDeleteAnnouncement()    // Confirms deletion
```

### StudentAnnouncementManager
```javascript
extends AnnouncementManager
- setupTabFilters()
- updateTabCounts()
- renderAnnouncements()
- viewAnnouncement(id)           // Opens view modal
- Read-only view
- No edit/delete
```

## 🔍 Query Scopes

```php
Announcement::published()      // status = 'published'
Announcement::draft()          // status = 'draft'
Announcement::archived()       // status = 'archived'
Announcement::byType('Exams')  // type = 'Exams'
Announcement::byPriority('Urgent') // priority = 'Urgent'
Announcement::recent()         // order by created_at desc
```

## 📱 Web Routes

| Route | Purpose | User |
|-------|---------|------|
| `/announcement` | List announcements | Admin |
| `/createannouncement` | Create form | Admin |
| `/userannouncement` | View announcements | Student |

## ⚡ Common Tasks

### Create via API
```bash
curl -X POST http://localhost:8000/api/announcements \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"title":"Test","description":"Test","type":"Exams","priority":"Info","audience":"All students","status":"published"}'
```

### Get All
```bash
curl http://localhost:8000/api/announcements \
  -H "Authorization: Bearer TOKEN"
```

### Delete
```bash
curl -X DELETE http://localhost:8000/api/announcements/1 \
  -H "Authorization: Bearer TOKEN"
```

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Announcements not loading | Check token, verify API endpoint |
| Create button missing | Verify admin role |
| Preview not updating | Check browser console, reload page |
| Authorization error | Verify user role is 'admin' |
| Soft delete not working | Check deleted_at column exists |

## 📚 Documentation Files

- `ANNOUNCEMENT_IMPLEMENTATION.md` - Full implementation details
- `ANNOUNCEMENT_FILES_SUMMARY.md` - File structure
- `ANNOUNCEMENT_TESTING_GUIDE.md` - Testing procedures
- `ANNOUNCEMENT_SYSTEM_COMPLETE.md` - Project summary
- `ANNOUNCEMENT_QUICK_REFERENCE.md` - This file

## ✅ Checklist

- [x] Model created with relationships
- [x] Migration executed
- [x] Controller with CRUD
- [x] API routes configured
- [x] Admin create page
- [x] Admin list page
- [x] Student view page
- [x] JavaScript manager
- [x] Real-time preview
- [x] Type filtering
- [x] Authorization checks
- [x] Documentation complete
- [x] Edit modal with form
- [x] Delete confirmation modal
- [x] Three vertical dots menu
- [x] Student view modal
- [x] Real-time list updates
- [x] Form validation
- [x] Error handling

## 🎯 Next Steps

1. Test all functionality
2. Deploy to production
3. Monitor usage
4. Gather user feedback
5. Plan enhancements

---

**Last Updated:** January 2, 2026
**Status:** ✅ Production Ready

