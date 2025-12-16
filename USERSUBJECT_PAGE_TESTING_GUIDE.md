# User Subject Page - Testing Guide

## 📋 Overview
This guide helps you verify that the `/usersubject` page is correctly loading and displaying enrolled courses from the database.

## ✅ Testing Steps

### 1. **Start the Development Server**
```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start Vite dev server
npm run dev
```

### 2. **Navigate to the User Subject Page**
- Open browser: `http://127.0.0.1:8000/usersubject`
- You should see a loading spinner briefly, then courses should appear

### 3. **Verify User Greeting**
- ✅ Check that the greeting displays: "Hello [FirstName] 👋"
- ✅ The first name should match the logged-in user's profile

### 4. **Verify Course Cards Display**
Each course card should show:
- ✅ Course thumbnail image (or default Kokokah logo)
- ✅ Course level/class name (yellow badge)
- ✅ Course title
- ✅ Progress percentage (0-100%)
- ✅ Progress bar visualization
- ✅ "View Subjects" button

### 5. **Verify Course Data**
For each enrolled course, verify:
- ✅ Course title matches database
- ✅ Course level matches database
- ✅ Progress percentage matches enrollment record
- ✅ Course image displays correctly

### 6. **Test Navigation**
- ✅ Click "View Subjects" button
- ✅ Should navigate to `/termsubject?course_id={id}`
- ✅ Course details page should load with correct course

### 7. **Check Browser Console**
- ✅ No JavaScript errors
- ✅ No 404 errors for resources
- ✅ API calls should show in Network tab

### 8. **Test Empty State**
If user has no enrolled courses:
- ✅ Should display: "No courses enrolled yet. Browse courses"
- ✅ "Browse courses" link should navigate to `/allcourses`

## 🔍 Debugging Tips

### If courses don't load:
1. Check Network tab → `/api/courses/my-courses`
   - Should return 200 status
   - Response should have `{ success: true, data: { courses: [...] } }`

2. Check browser console for errors
3. Verify user is authenticated (check localStorage for auth_token)
4. Verify enrollments exist in database for logged-in user

### If images don't load:
1. Check that course has `thumbnail_url` or `image_url` set
2. Verify image file exists in `public/images/`
3. Check Network tab for 404 errors on image requests

### If progress bar doesn't show:
1. Verify enrollment record has `progress` field set
2. Check that progress value is 0-100

## 📊 Expected API Response

```json
{
  "success": true,
  "message": "OK",
  "data": {
    "courses": [
      {
        "id": 1,
        "user_id": 1,
        "course_id": 1,
        "progress": 45,
        "enrolled_at": "2025-12-15T10:30:00",
        "course": {
          "id": 1,
          "title": "Mathematics 101",
          "thumbnail_url": "https://...",
          "level": {
            "id": 1,
            "name": "JSS 1"
          }
        }
      }
    ],
    "total": 1
  }
}
```

## 🎯 Success Criteria

- [ ] Page loads without errors
- [ ] User greeting displays correctly
- [ ] All enrolled courses display as cards
- [ ] Course data is accurate
- [ ] Navigation to course details works
- [ ] No console errors
- [ ] Responsive design works on mobile

## 📝 Notes

- Page uses CourseApiClient.getMyCourses() endpoint
- Requires user authentication (Bearer token)
- Courses are sorted by latest enrollment date
- Progress is stored in enrollment pivot table

