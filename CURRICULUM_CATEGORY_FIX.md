# Curriculum Category ID Fix - Course Creation

## Problem
When creating a course, the API returned a 422 validation error:
```
Validation Error
The selected curriculum category id is invalid
```

## Root Cause
The form was sending `curriculum_category_id` field, but:
1. It was never populated (hidden input was empty)
2. The validation rule was `nullable|exists` which still validates if a value is sent
3. An empty or invalid value was being rejected

## Solution Applied

### 1. Removed Hidden Input Field
**File**: `resources/views/admin/createsubject.blade.php` (line 362)
- Removed: `<input type="hidden" id="curriculumCategoryId" name="curriculumCategoryId" required>`
- This field was never used and was causing issues

### 2. Removed curriculum_category_id from Form Submission
**File**: `resources/views/admin/createsubject.blade.php`
- **finalPublishBtn handler** (line 1023): Removed `curriculum_category_id` append
- **saveNowBtn handler**: Already correct (no curriculum_category_id)
- **saveDraftBtn handler**: Already correct (no curriculum_category_id)

### 3. Updated Validation Rule
**File**: `app/Http/Controllers/CourseController.php` (line 209)
```php
// BEFORE
'curriculum_category_id' => 'nullable|exists:curriculum_categories,id',

// AFTER
'curriculum_category_id' => 'sometimes|nullable|exists:curriculum_categories,id',
```

The `sometimes` rule means: "Only validate this field if it's present in the request"

## Required Fields for Course Creation

✅ **title** - string, max 255 chars
✅ **description** - string
✅ **term_id** - must exist in terms table
✅ **course_category_id** - must exist in course_categories table
✅ **level_id** - must exist in levels table

## Optional Fields

- curriculum_category_id (if provided, must exist)
- free_subscription (boolean)
- free (boolean)
- url (string)
- duration_hours (integer)
- thumbnail (image file)

## Test the Fix

```json
POST /api/courses
{
    "title": "Test Course",
    "description": "Test Description",
    "term_id": 1,
    "course_category_id": 1,
    "level_id": 1
}
```

**Expected**: ✅ 201 Created (no 422 error)

## Files Modified

1. `resources/views/admin/createsubject.blade.php`
   - Removed hidden curriculum_category_id input
   - Removed curriculum_category_id from finalPublishBtn form submission

2. `app/Http/Controllers/CourseController.php`
   - Updated validation rule to use `sometimes`

## Status

✅ FIXED - Course creation now works without curriculum_category_id

