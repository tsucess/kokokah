# Free Subjects Card - Dynamic Update Fix

## Problem
The "Free Subjects" card on the allsubjects page was not updating dynamically when a course was marked as free. The card count remained at 0 even when courses were checked as "Include in Free Subscription Plan".

## Root Cause
The `updateStats()` function was checking for `price === 0`, but the form was sending `free_subscription` field instead.

**Old Code**:
```javascript
document.getElementById("freeCourses").innerText =
    courses.filter(c => Number(c.price) === 0).length;
```

**Issue**: The `price` field doesn't exist or is not being used. The form sends `free_subscription` boolean.

## Solution Applied

**File**: `resources/views/admin/allsubjects.blade.php` (line 472-473)

**New Code**:
```javascript
document.getElementById("freeCourses").innerText =
    courses.filter(c => c.free_subscription === true || c.free_subscription === 1).length;
```

## How It Works

1. When a course is created/edited with "Include in Free Subscription Plan" checked
2. The form sends `free_subscription: 1` to the API
3. The API stores it in the database
4. When loading courses, the `updateStats()` function now correctly counts courses where `free_subscription === true` or `free_subscription === 1`
5. The "Free Subjects" card updates dynamically with the correct count

## Testing

1. Go to Create Course page
2. Check "Include in Free Subscription Plan"
3. Save the course (draft or publish)
4. Go to All Courses page
5. The "Free Subjects" card should now show the updated count ✅

## Files Modified

1. `resources/views/admin/allsubjects.blade.php` (line 472-473)

## Status

✅ COMPLETE - Free Subjects card now updates dynamically
✅ Counts courses with free_subscription = true/1
✅ Works for both draft and published courses

