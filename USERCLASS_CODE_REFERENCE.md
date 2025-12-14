# User Class Page - Code Reference

## File Location
`resources/views/users/userclass.blade.php`

---

## Key Functions

### 1. loadCourses()
**Location**: Lines 75-116

**Purpose**: Fetch courses from API and display them

**Flow**:
1. Get container and loading message elements
2. Call `CourseApiClient.getCourses({ per_page: 12 })`
3. Check if response is successful
4. Generate HTML for each course
5. Insert HTML into container
6. Attach event listeners

**Error Handling**:
- Catches exceptions
- Shows error message to user
- Logs error to console

---

### 2. attachEnrollListeners()
**Location**: Lines 121-135

**Purpose**: Attach click handlers to enroll buttons

**Flow**:
1. Select all enroll buttons
2. For each button:
   - Get course ID from `data-course-id` attribute
   - Navigate to `/userenroll?course_id={courseId}`

---

## HTML Elements

### Container
```html
<div class="card-container" id="coursesContainer">
    <div class="text-center w-100" id="loadingMessage">
        <p class="text-muted">Loading courses...</p>
    </div>
</div>
```

### Dynamic Course Card Template
```html
<div class="p-3 rounded-4 bg-white mysubject d-flex flex-column gap-3 w-100">
    <div class="border border-dark p-2 text-center" style="border-radius: 10px;">
        <img src="${course.image_url}" class="img-fluid userdasboard-card-img" />
    </div>
    <div class="card-item-class align-self-start">${course.level?.name}</div>
    <h5 class="subjects">${course.title}</h5>
    <p class="text-muted small">${course.description}</p>
    <button class="enroll-btn" data-course-id="${course.id}">Enroll</button>
</div>
```

---

## API Integration

### CourseApiClient Import
```javascript
import CourseApiClient from '{{ asset("js/api/courseApiClient.js") }}';
```

### API Call
```javascript
const result = await CourseApiClient.getCourses({ per_page: 12 });
```

### Response Structure
```javascript
{
    success: true,
    data: [
        {
            id: 1,
            title: "Course Title",
            description: "Description...",
            image_url: "path/to/image.jpg",
            level: {
                id: 1,
                name: "JSS 1"
            }
        }
    ]
}
```

---

## Data Mapping

```javascript
// Course object from API
{
    id: 1,
    title: "Junior Secondary School 1",
    description: "Learn JSS 1 curriculum...",
    image_url: "storage/courses/jss1.jpg",
    level: {
        name: "JSS 1"
    }
}

// Mapped to HTML
Image: course.image_url || 'images/Kokokah_Logo.png'
Level: course.level?.name || 'Course'
Title: course.title
Description: course.description.substring(0, 80) + '...'
Button ID: course.id
```

---

## Event Flow

```
Page Load
    ↓
DOMContentLoaded
    ↓
loadCourses()
    ↓
CourseApiClient.getCourses()
    ↓
Generate HTML
    ↓
Insert into DOM
    ↓
attachEnrollListeners()
    ↓
User Clicks Enroll
    ↓
Navigate to /userenroll?course_id={id}
```

---

## Error States

### Loading Error
```javascript
loadingMessage.innerHTML = '<p class="text-danger">Failed to load courses. Please try again later.</p>';
```

### No Courses
```javascript
loadingMessage.innerHTML = '<p class="text-muted">No courses available at the moment.</p>';
```

---

## Status: ✅ COMPLETE

All code is implemented and ready for testing.

