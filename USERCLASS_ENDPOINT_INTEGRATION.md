# User Class Page - Endpoint Integration

## Feature Implemented

Integrated CourseApiClient to dynamically load and display courses on the user class page instead of hardcoded cards.

---

## Changes Made

### File: `resources/views/users/userclass.blade.php`

#### 1. Replaced Hardcoded Cards (Lines 46-58)
**Before**: 4 hardcoded course cards with static data
**After**: Dynamic container with loading message

```html
<div class="card-container" id="coursesContainer">
    <div class="text-center w-100" id="loadingMessage">
        <p class="text-muted">Loading courses...</p>
    </div>
</div>
```

#### 2. Implemented API Integration (Lines 65-136)
**Added**:
- ES6 module import for CourseApiClient
- `loadCourses()` function to fetch courses from API
- `attachEnrollListeners()` function to handle enroll button clicks
- Dynamic HTML generation for course cards
- Error handling and loading states

---

## How It Works

### Flow
1. Page loads
2. DOMContentLoaded event triggers
3. `loadCourses()` is called
4. API request: `GET /api/courses?per_page=12`
5. Courses are fetched and displayed
6. Event listeners attached to enroll buttons
7. User clicks enroll → navigates to `/userenroll?course_id={id}`

### Course Card Structure
```
┌─────────────────────────┐
│   Course Image          │
├─────────────────────────┤
│ Level Badge (JSS 1)     │
│ Course Title            │
│ Course Description      │
│ [Enroll Button]         │
└─────────────────────────┘
```

---

## Features

✅ **Dynamic Loading** - Courses loaded from API
✅ **Loading State** - Shows "Loading courses..." message
✅ **Error Handling** - Displays error if API fails
✅ **Course Image** - Displays course image or fallback
✅ **Level Display** - Shows course level/class
✅ **Description** - Shows truncated course description
✅ **Enroll Button** - Passes course ID to enrollment page
✅ **Responsive Design** - Grid layout adapts to screen size
✅ **No Courses Message** - Shows message if no courses available

---

## API Integration

### Endpoint Used
- `GET /api/courses` - Fetch all courses

### Parameters
- `per_page: 12` - Limit to 12 courses per page

### Response Structure
```javascript
{
    success: true,
    data: [
        {
            id: 1,
            title: "Course Title",
            description: "Course description...",
            image_url: "path/to/image.jpg",
            level: {
                id: 1,
                name: "JSS 1"
            },
            ...
        }
    ]
}
```

---

## Course Card Data Mapping

| Card Element | Data Source |
|--------------|-------------|
| Image | `course.image_url` (fallback: Kokokah_Logo.png) |
| Level Badge | `course.level.name` (fallback: "Course") |
| Title | `course.title` |
| Description | `course.description` (truncated to 80 chars) |
| Enroll Button | `course.id` (passed as query param) |

---

## Enrollment Flow

1. User clicks "Enroll" button
2. Course ID extracted from button's `data-course-id` attribute
3. Navigate to: `/userenroll?course_id={courseId}`
4. Enrollment page receives course ID via query parameter

---

## Error Handling

### Loading Error
```
"Failed to load courses. Please try again later."
```

### No Courses
```
"No courses available at the moment."
```

### API Failure
- Logs error to console
- Shows user-friendly error message
- Allows retry by refreshing page

---

## Status: ✅ COMPLETE AND READY FOR TESTING

The user class page now dynamically loads courses from the API with proper error handling and user feedback.

