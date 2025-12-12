# User Enroll Page - Code Reference

## File Location
`resources/views/users/enroll.blade.php`

---

## Key Functions

### 1. loadCourses()
**Location**: Lines 240-260

**Purpose**: Load courses from API by level_id

**Flow**:
1. Get level_id from URL query parameter
2. Call `CourseApiClient.getCourses({ level_id, per_page: 50 })`
3. Display courses or error message
4. Update level title

---

### 2. displayCourses(courses)
**Location**: Lines 262-280

**Purpose**: Generate and display course checkboxes

**Flow**:
1. Map each course to HTML row
2. Create checkbox with course data
3. Show course title and formatted price
4. Insert into DOM
5. Attach event listeners

---

### 3. updateLevelTitle(levelId)
**Location**: Lines 282-295

**Purpose**: Fetch and display level name

**Flow**:
1. Call `CourseApiClient.getLevels()`
2. Find level by ID
3. Update title element

---

### 4. updateSubtotal()
**Location**: Lines 315-325

**Purpose**: Calculate and update subtotal

**Flow**:
1. Get all checked checkboxes
2. Sum their prices
3. Update subtotal display
4. Update "Enroll in All" button

---

### 5. attachCheckboxListeners()
**Location**: Lines 297-302

**Purpose**: Attach change listeners to checkboxes

---

## HTML Elements

### Level Title
```html
<h1 id="levelTitle">Loading...</h1>
```

### Enroll All Button
```html
<button id="enrollAllBtn">Enroll in All Subjects - ₦0.00</button>
```

### Courses Container
```html
<div class="txn-list" id="coursesList">
    <!-- Courses loaded here -->
</div>
```

### Course Row Template
```html
<div class="txn-row">
    <div class="txn-left">
        <input class="form-check-input check-subject" 
               type="checkbox" 
               data-price="${course.price}" 
               data-course-id="${course.id}"
               id="cb${index}">
        <label for="cb${index}" class="subject">${course.title}</label>
    </div>
    <div class="txn-price">${formatNGN(course.price)}</div>
</div>
```

---

## API Integration

### CourseApiClient Import
```javascript
import CourseApiClient from '{{ asset("js/api/courseApiClient.js") }}';
```

### API Calls
```javascript
// Get courses by level
const result = await CourseApiClient.getCourses({ 
    level_id: levelId, 
    per_page: 50 
});

// Get all levels
const result = await CourseApiClient.getLevels();
```

---

## Data Mapping

| Element | Source |
|---------|--------|
| Level Title | `level.name` |
| Course Title | `course.title` |
| Course Price | `course.price` |
| Course ID | `course.id` |
| Subtotal | Sum of checked prices |

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
displayCourses()
    ↓
attachCheckboxListeners()
    ↓
User Checks Checkbox
    ↓
updateSubtotal()
    ↓
Update Display
```

---

## Error Handling

### No Level Selected
```javascript
showError('No level selected. Please go back and select a class.');
```

### No Courses Available
```javascript
showError('No courses available for this class.');
```

### API Error
```javascript
showError('Failed to load courses. Please try again later.');
```

---

## Status: ✅ COMPLETE

All code is implemented and ready for testing.

