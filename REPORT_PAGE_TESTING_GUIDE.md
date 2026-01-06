# Report Page Testing Guide

## Quick Start

### Prerequisites
1. User must be logged in as Admin
2. Auth token must be stored in `localStorage.auth_token`
3. API endpoints must be accessible

### Testing Checklist

#### 1. Dashboard Statistics
- [ ] Page loads without errors
- [ ] Total Students displays correct count
- [ ] Total Teachers displays correct count
- [ ] Active Courses displays correct count
- [ ] Total Enrollments displays correct count
- [ ] All values are formatted with commas (e.g., 23,453)

#### 2. Engagement Chart
- [ ] Chart renders without errors
- [ ] Day button shows daily engagement data
- [ ] Week button shows weekly data
- [ ] Month button shows monthly data
- [ ] Year button shows yearly data
- [ ] Chart updates smoothly when switching ranges
- [ ] Fallback data displays if API fails

#### 3. Course Performance Chart
- [ ] Chart renders with course names
- [ ] Bar heights represent performance correctly
- [ ] Legend displays at bottom
- [ ] Chart is responsive on mobile
- [ ] Fallback data displays if API fails

#### 4. Student Performance Table
- [ ] Table loads with student data
- [ ] Pagination controls appear
- [ ] Previous/Next buttons work correctly
- [ ] Page numbers display correctly
- [ ] Current page is highlighted

#### 5. Search Functionality
- [ ] Type in search box filters by name
- [ ] Search filters by email
- [ ] Search is case-insensitive
- [ ] Results update in real-time
- [ ] Clear search shows all data

#### 6. Filter Functionality
- [ ] "All Classes" shows all students
- [ ] "All Courses" filters by course
- [ ] "All Categories" filters by category
- [ ] "Students" shows only student role
- [ ] "Instructors" shows only instructor role
- [ ] "Admins" shows only admin role

#### 7. Table Columns
- [ ] ID column displays correctly
- [ ] Student Name displays correctly
- [ ] Subjects column shows course name
- [ ] Completion Rate shows percentage with badge
- [ ] Average Score shows percentage
- [ ] Last Active shows formatted date

#### 8. Error Handling
- [ ] Page doesn't crash if API fails
- [ ] Fallback data displays gracefully
- [ ] Error messages appear in console
- [ ] User sees helpful error message in table

## Browser Console Checks

Open DevTools (F12) and check:
```javascript
// Check if token exists
localStorage.getItem('auth_token')

// Check API calls in Network tab
// Should see successful requests to:
// - /api/dashboard/admin
// - /api/analytics/engagement
// - /api/analytics/course-performance
// - /api/analytics/student-progress
```

## Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| "Failed to fetch" errors | Check auth token in localStorage |
| No data displays | Verify API endpoints are working |
| Charts don't render | Check Chart.js is loaded |
| Search not working | Ensure data is loaded first |
| Pagination broken | Check API returns pagination data |

## Performance Notes

- Dashboard stats cached for 5 minutes on backend
- Student data paginated (10 per page by default)
- Charts render asynchronously
- Search/filter happens client-side

