# Activity Types Reference Guide

## Complete List of All User Activities

| # | Activity Type | Icon | Source Model | Status Options | Description |
|---|---|---|---|---|---|
| 1 | User Registration | fa-user-plus | User | Completed | New user account creation |
| 2 | Course Created | fa-book | Course | Completed | Instructor creates new course |
| 3 | Course Enrollment | fa-graduation-cap | Enrollment | Active/Completed/Pending | Student enrolls in course |
| 4 | Lesson Completed | fa-check-circle | LessonCompletion | Completed | Student completes lesson |
| 5 | Quiz Attempted | fa-clipboard-list | QuizAttempt | Completed/Pending | Student takes quiz (status based on pass/fail) |
| 6 | Course Reviewed | fa-star | CourseReview | Completed | Student leaves course review |
| 7 | Course Completed | fa-trophy | Enrollment | Completed | Student completes entire course |
| 8 | Payment Completed | fa-credit-card | Payment | Completed/Pending/Failed | Course purchase or wallet deposit |
| 9 | Learning Path Enrolled | fa-road | LearningPathEnrollment | Active/Completed | Student enrolls in learning path |
| 10 | Certificate Issued | fa-certificate | Certificate | Completed | Certificate awarded to student |

## Filter Options

### Status Filters
- **Completed**: Activity successfully finished
- **Pending**: Activity in progress or awaiting action
- **Failed**: Activity did not succeed
- **Active**: Activity currently ongoing

### Activity Type Filters
All 10 activity types listed above can be individually filtered

## Search Capabilities
- User first name and last name
- User email address
- Activity description
- Timestamp (date format: YYYY-MM-DD)

## Data Sources

### Backend (AdminController.php)
- `getRecentActivityPaginated()` method
- Collects from 10 different models
- Limits: 20 records per activity type
- Pagination: 10 items per page

### Frontend (useractivity.blade.php)
- Receives paginated activity data
- Client-side filtering and search
- Dynamic icon and label rendering
- CSV export functionality

## Color Coding

| Status | Color | Hex Code |
|--------|-------|----------|
| Completed | Green | #28a745 |
| Pending | Yellow | #ffc107 |
| Failed | Red | #dc3545 |
| Active | Cyan | #17a2b8 |
| Inactive | Gray | #6c757d |

## CSV Export Format
```
No,User Name,Activity Type,Description,Timestamp,Status
1,"John Doe","Course Enrollment","Enrolled in course: React Basics","2025-01-15","Active"
```

## Performance Notes
- Each activity type limited to 20 records
- Total activities per page: ~200 records max
- Pagination reduces load on frontend
- Client-side filtering is instant
- Sorting by timestamp (DESC)

