# User Enroll Page - Endpoint Integration

## Feature Implemented

Successfully integrated API endpoints to dynamically load and display courses for enrollment on the user enroll page.

---

## Changes Made

### File: `resources/views/users/enroll.blade.php`

#### 1. **Updated Header Section** (Lines 176-177)
- Changed hardcoded title to dynamic `id="levelTitle"`
- Changed hardcoded button to dynamic `id="enrollAllBtn"`
- Button now shows course count and total price

#### 2. **Replaced Hardcoded Courses** (Lines 202-209)
- Removed 12 hardcoded course rows
- Added dynamic container `id="coursesList"`
- Shows "Loading courses..." initially

#### 3. **Implemented API Integration** (Lines 226-392)
- Imported CourseApiClient
- Created `loadCourses()` function
- Created `displayCourses()` function
- Created `updateLevelTitle()` function
- Created `attachCheckboxListeners()` function
- Created `updateSubtotal()` function
- Created `updateEnrollAllButton()` function

---

## How It Works

### Flow
```
Page Load
    ↓
DOMContentLoaded Event
    ↓
loadCourses()
    ↓
Get level_id from URL query parameter
    ↓
GET /api/courses?level_id={levelId}
    ↓
Display courses as checkboxes
    ↓
Update level title
    ↓
Attach event listeners
```

### API Endpoints Used
1. **GET /api/courses** - Fetch courses by level_id
   - Parameters: `level_id`, `per_page`
   - Returns: Array of courses with title, price, id

2. **GET /api/level** - Fetch all levels
   - Returns: Array of levels with name, id

---

## Key Functions

### loadCourses()
- Gets level_id from URL query parameter
- Calls `CourseApiClient.getCourses({ level_id, per_page: 50 })`
- Displays courses or error message

### displayCourses(courses)
- Generates HTML for each course
- Creates checkbox with course data
- Shows course title and price
- Attaches event listeners

### updateSubtotal()
- Calculates total of checked courses
- Updates subtotal display
- Updates "Enroll in All" button

### updateEnrollAllButton()
- Shows total course count
- Shows total price for all courses

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

## Features

✅ **Dynamic Course Loading** - Loads courses from API
✅ **Level-Based Filtering** - Filters by level_id from URL
✅ **Price Display** - Shows formatted NGN prices
✅ **Checkbox Selection** - Select individual courses
✅ **Subtotal Calculation** - Real-time subtotal updates
✅ **Enroll All Button** - Select all courses at once
✅ **Error Handling** - Shows error messages
✅ **Loading State** - Shows loading message initially
✅ **Currency Formatting** - Formats prices as NGN

---

## URL Format

```
/userenroll?level_id=1
```

---

## Status: ✅ COMPLETE AND READY FOR TESTING

All endpoints are integrated and courses load dynamically from the API.

