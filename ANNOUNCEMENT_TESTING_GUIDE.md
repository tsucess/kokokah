# Announcement System - Testing Guide

## Prerequisites
- Admin user account
- Student user account
- Authentication token (from login)
- Postman or similar API testing tool (optional)

## Manual Testing

### 1. Admin Create Announcement
**Steps:**
1. Login as admin
2. Navigate to `/announcement`
3. Click "Create New Announcement" button
4. Fill in the form:
   - Title: "Test Announcement"
   - Type: "Exams"
   - Priority: Click "Urgent" badge
   - Audience: "All students"
   - Description: "This is a test announcement"
5. Verify preview updates in real-time
6. Click "Publish"
7. Should redirect to `/announcement` with success message

**Expected Result:** Announcement appears in the list

### 2. Admin Save as Draft
**Steps:**
1. Navigate to `/createannouncement`
2. Fill in form details
3. Click "Save As Draft"
4. Should redirect to `/announcement`

**Expected Result:** Announcement saved but not visible to students

### 3. Admin View Announcements
**Steps:**
1. Navigate to `/announcement`
2. Verify announcements load dynamically
3. Click on type tabs (Exams, Events, Alert, General Info)
4. Verify counts update correctly
5. Verify time ago formatting (e.g., "1 day ago")

**Expected Result:** Announcements filter by type, counts are accurate

### 4. Admin Edit Announcement
**Steps:**
1. On `/announcement` page
2. Click ellipsis menu on announcement
3. Click "Edit"
4. Modify announcement details
5. Click "Publish"

**Expected Result:** Announcement updated successfully

### 5. Admin Delete Announcement
**Steps:**
1. On `/announcement` page
2. Click ellipsis menu on announcement
3. Click "Delete"
4. Confirm deletion

**Expected Result:** Announcement removed from list

### 6. Student View Announcements
**Steps:**
1. Login as student
2. Navigate to `/userannouncement`
3. Verify announcements load
4. Verify no "Create New Announcement" button
5. Verify no edit/delete options
6. Test type filtering

**Expected Result:** Student sees announcements but cannot create/edit/delete

### 7. Priority Display
**Steps:**
1. Create announcements with different priorities
2. Verify badges display correctly:
   - Info: Black circle
   - Urgent: Orange circle
   - Warning: Yellow circle

**Expected Result:** Priority badges display with correct colors

### 8. Type Filtering
**Steps:**
1. Create announcements of different types
2. Click each type tab
3. Verify only that type displays
4. Verify count updates

**Expected Result:** Filtering works correctly

## API Testing

### Test Create Announcement
```bash
curl -X POST http://localhost:8000/api/announcements \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "API Test Announcement",
    "description": "Testing via API",
    "type": "Events",
    "priority": "Info",
    "audience": "All students",
    "status": "published"
  }'
```

### Test Get Announcements
```bash
curl -X GET http://localhost:8000/api/announcements \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Test Get Announcements by Type
```bash
curl -X GET "http://localhost:8000/api/announcements?type=Exams" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Test Get Single Announcement
```bash
curl -X GET http://localhost:8000/api/announcements/1 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Test Update Announcement
```bash
curl -X PUT http://localhost:8000/api/announcements/1 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Updated Title",
    "status": "published"
  }'
```

### Test Delete Announcement
```bash
curl -X DELETE http://localhost:8000/api/announcements/1 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Test Get Type Counts
```bash
curl -X GET http://localhost:8000/api/announcements/types \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Browser Console Testing

### Check JavaScript Manager
```javascript
// Check if manager is initialized
console.log(window.announcementManager);

// Manually load announcements
announcementManager.loadAnnouncements();

// Check current announcements
console.log(announcementManager.currentAnnouncements);

// Test delete
announcementManager.deleteAnnouncement(1);
```

## Common Issues & Solutions

### Issue: Announcements not loading
**Solution:**
- Check browser console for errors
- Verify authentication token is valid
- Check API endpoint is correct
- Verify user has proper role

### Issue: Create button not appearing
**Solution:**
- Verify user is logged in as admin
- Check user role is 'admin'
- Clear browser cache

### Issue: Preview not updating
**Solution:**
- Check JavaScript file is loaded
- Verify input elements have correct selectors
- Check browser console for errors

### Issue: Authorization error on create
**Solution:**
- Verify user role is 'admin'
- Check token is valid
- Verify middleware is applied correctly

## Performance Testing

### Load Test
1. Create 100+ announcements
2. Navigate to `/announcement`
3. Verify page loads within 2 seconds
4. Check pagination works

### Filter Performance
1. With 100+ announcements
2. Click type filters
3. Verify filtering is instant

## Security Testing

### Test Authorization
1. Login as student
2. Try to POST to `/api/announcements`
3. Should get 403 Forbidden

### Test Data Validation
1. Try to create announcement with empty title
2. Should get validation error
3. Try invalid type
4. Should get validation error

## Cleanup

After testing, you can delete test announcements:
```bash
curl -X DELETE http://localhost:8000/api/announcements/{id} \
  -H "Authorization: Bearer YOUR_TOKEN"
```

Or use the admin interface to delete them.

