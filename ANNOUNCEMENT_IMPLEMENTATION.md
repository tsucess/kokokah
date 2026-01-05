# Announcement System Implementation Guide

## Overview
A complete announcement management system has been implemented with dynamic backend integration, allowing admins to create, edit, and delete announcements while students can view them in a read-only format.

## Components Created

### 1. Database Model & Migration
**File:** `app/Models/Announcement.php`
**Migration:** `database/migrations/2026_01_02_124603_create_announcements_table.php`

**Fields:**
- `id` - Primary key
- `user_id` - Foreign key to users table (creator)
- `title` - Announcement title
- `description` - Full announcement text
- `type` - Enum: Exams, Events, Alert, General Info
- `priority` - Enum: Info, Urgent, Warning
- `audience` - Enum: All students, Specific class, Specific level
- `audience_value` - Optional value for specific audience
- `scheduled_at` - Optional scheduled publish time
- `status` - Enum: draft, published, archived
- `view_count` - Track views
- `timestamps` - created_at, updated_at
- `soft_deletes` - For safe deletion

**Scopes Available:**
- `published()` - Get published announcements
- `draft()` - Get draft announcements
- `archived()` - Get archived announcements
- `byType($type)` - Filter by type
- `byPriority($priority)` - Filter by priority
- `recent()` - Order by newest first

### 2. API Controller
**File:** `app/Http/Controllers/AnnouncementController.php`

**Endpoints:**
- `GET /api/announcements` - List all announcements (with filters)
- `POST /api/announcements` - Create announcement (admin only)
- `GET /api/announcements/{id}` - Get single announcement
- `PUT /api/announcements/{id}` - Update announcement (admin only)
- `DELETE /api/announcements/{id}` - Delete announcement (admin only)
- `GET /api/announcements/types` - Get type counts

**Features:**
- Full CRUD operations
- Search functionality
- Filtering by status, type, priority
- Authorization checks
- View count tracking
- Pagination support

### 3. API Routes
**File:** `routes/api.php`

```php
Route::middleware('auth:sanctum')->prefix('announcements')->group(function () {
    Route::get('/', [AnnouncementController::class, 'index']);
    Route::get('/types', [AnnouncementController::class, 'getByType']);
    Route::get('/{id}', [AnnouncementController::class, 'show']);
    
    Route::middleware('role:admin')->group(function () {
        Route::post('/', [AnnouncementController::class, 'store']);
        Route::put('/{id}', [AnnouncementController::class, 'update']);
        Route::delete('/{id}', [AnnouncementController::class, 'destroy']);
    });
});
```

### 4. Frontend Views

#### Admin Create Announcement
**File:** `resources/views/admin/createannouncement.blade.php`
- Dynamic form with real-time preview
- Priority selection (Info, Urgent, Warning)
- Type selection (Exams, Events, Alert, General Info)
- Audience targeting
- Optional scheduling
- Save as draft or publish directly

#### Admin Announcement List
**File:** `resources/views/admin/announcement.blade.php`
- Dynamic announcement listing
- Tab filtering by type
- Live count updates
- Edit/Delete actions
- Admin-only create button

#### Student Announcement View
**File:** `resources/views/users/userannouncement.blade.php`
- Read-only announcement view
- Same filtering as admin
- No create/edit/delete buttons
- Clean, student-friendly interface

### 5. JavaScript Manager
**File:** `public/js/announcements.js`

**Classes:**
- `AnnouncementManager` - Base class with core functionality
- `AdminAnnouncementManager` - Extends base for admin features
- `StudentAnnouncementManager` - Extends base for student view

**Features:**
- AJAX requests for CRUD operations
- Real-time preview updates
- Dynamic rendering
- Time ago formatting
- Token-based authentication
- Error handling

## Usage

### For Admins
1. Navigate to `/announcement` to view all announcements
2. Click "Create New Announcement" button
3. Fill in the form:
   - Title
   - Type (Exams, Events, Alert, General Info)
   - Priority (Info, Urgent, Warning)
   - Audience (All students, Specific class, Specific level)
   - Optional scheduled date/time
   - Description
4. Preview updates in real-time
5. Click "Publish" to publish immediately or "Save As Draft" to save for later
6. Manage announcements from the list view

### For Students
1. Navigate to `/userannouncement` to view announcements
2. Filter by type using tabs
3. View announcement details
4. No create/edit/delete options available

## API Usage Examples

### Create Announcement
```bash
POST /api/announcements
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Mid-term Exam Schedule",
    "description": "The mid-term exams will be held...",
    "type": "Exams",
    "priority": "Urgent",
    "audience": "All students",
    "status": "published"
}
```

### Get Announcements
```bash
GET /api/announcements?type=Exams&status=published
Authorization: Bearer {token}
```

### Update Announcement
```bash
PUT /api/announcements/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Updated Title",
    "status": "published"
}
```

### Delete Announcement
```bash
DELETE /api/announcements/{id}
Authorization: Bearer {token}
```

## Security
- All endpoints require authentication (auth:sanctum)
- Create/Update/Delete restricted to admin role
- Authorization checks prevent unauthorized modifications
- Soft deletes preserve data integrity

## Future Enhancements
- Email notifications for announcements
- Announcement scheduling with automatic publishing
- Rich text editor for descriptions
- File attachments
- Read receipts tracking
- Announcement analytics

