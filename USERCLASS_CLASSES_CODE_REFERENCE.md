# User Class Page - Classes Code Reference

## File Location
`resources/views/users/userclass.blade.php`

---

## Key Functions

### 1. loadClasses()
**Location**: Lines 75-116

**Purpose**: Fetch classes/levels from API and display them

**Flow**:
1. Get container and loading message elements
2. Call `CourseApiClient.getLevels()`
3. Check if response is successful
4. Generate HTML for each class
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
   - Get level ID from `data-level-id` attribute
   - Navigate to `/userenroll?level_id={levelId}`

---

## HTML Elements

### Container
```html
<div class="card-container" id="coursesContainer">
    <div class="text-center w-100" id="loadingMessage">
        <p class="text-muted">Loading classes...</p>
    </div>
</div>
```

### Dynamic Class Card Template
```html
<div class="p-3 rounded-4 bg-white mysubject d-flex flex-column gap-3 w-100">
    <div class="border border-dark p-2 text-center" style="border-radius: 10px;">
        <img src="images/Kokokah_Logo.png" class="img-fluid userdasboard-card-img" />
    </div>
    <div class="card-item-class align-self-start">${classItem.name}</div>
    <h5 class="subjects">${classItem.name}</h5>
    <p class="text-muted small">${classItem.description}</p>
    <button class="enroll-btn" data-level-id="${classItem.id}">Enroll</button>
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
const result = await CourseApiClient.getLevels();
```

### Response Structure
```javascript
{
    success: true,
    data: [
        {
            id: 1,
            name: "JSS 1",
            description: "Junior Secondary School 1",
            curriculum_category_id: 1
        }
    ]
}
```

---

## Data Mapping

```javascript
// Class/Level object from API
{
    id: 1,
    name: "JSS 1",
    description: "Junior Secondary School 1",
    curriculum_category_id: 1
}

// Mapped to HTML
Image: "images/Kokokah_Logo.png" (static)
Class Name Badge: classItem.name
Title: classItem.name
Description: classItem.description.substring(0, 80) + '...'
Button ID: classItem.id
```

---

## Event Flow

```
Page Load
    ↓
DOMContentLoaded
    ↓
loadClasses()
    ↓
CourseApiClient.getLevels()
    ↓
Generate HTML
    ↓
Insert into DOM
    ↓
attachEnrollListeners()
    ↓
User Clicks Enroll
    ↓
Navigate to /userenroll?level_id={id}
```

---

## Error States

### Loading Error
```javascript
loadingMessage.innerHTML = '<p class="text-danger">Failed to load classes. Please try again later.</p>';
```

### No Classes
```javascript
loadingMessage.innerHTML = '<p class="text-muted">No classes available at the moment.</p>';
```

---

## Status: ✅ COMPLETE

All code is implemented and ready for testing.

