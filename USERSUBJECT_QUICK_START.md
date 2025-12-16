# User Subject Page - Quick Start Guide

## 🚀 Quick Test

### 1. Start Servers
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

### 2. Navigate to Page
```
http://127.0.0.1:8000/usersubject
```

### 3. Expected Result
✅ Page loads without errors
✅ User greeting displays: "Hello [FirstName] 👋"
✅ Course cards display for all enrolled courses
✅ Each card shows:
   - Course image
   - Course level (yellow badge)
   - Course title
   - Progress percentage
   - Progress bar
   - "View Subjects" button

### 4. Test Navigation
Click "View Subjects" → Should navigate to `/termsubject?course_id={id}`

## 🔍 Debugging

### If courses don't load:
1. Open Browser DevTools (F12)
2. Go to Network tab
3. Look for `/api/courses/my-courses` request
4. Check response status (should be 200)
5. Check response data structure

### If you see errors:
1. Check Console tab for JavaScript errors
2. Verify user is logged in
3. Verify enrollments exist in database

## 📊 Database Check

Verify enrolled courses exist:
```sql
SELECT e.*, c.title, c.thumbnail_url, l.name as level_name
FROM enrollments e
JOIN courses c ON e.course_id = c.id
LEFT JOIN levels l ON c.level_id = l.id
WHERE e.user_id = [YOUR_USER_ID];
```

## 📝 Files Modified

- `resources/views/users/usersubject.blade.php` ✅

## 🔗 Related Files

- `public/js/api/courseApiClient.js` - API client
- `public/js/api/userApiClient.js` - User API client
- `public/js/utils/toastNotification.js` - Notifications
- `app/Http/Controllers/CourseController.php` - Backend endpoint

## ✨ Features

- Dynamic course loading from API
- Responsive grid layout
- Progress tracking
- Error handling
- Empty state message
- Kokokah design system colors

## 🎯 Success Criteria

- [ ] Page loads without 404 errors
- [ ] User greeting displays correctly
- [ ] Courses load from database
- [ ] Course cards display properly
- [ ] Navigation works
- [ ] No console errors

