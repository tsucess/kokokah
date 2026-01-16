# Database Column Fix - curriculum_category_id

## Problem
When creating a course, the API returned a 500 error:
```
SQLSTATE[HY000]: General error: 1364 Field 'curriculum_category_id' doesn't have a default value
```

## Root Cause
The `curriculum_category_id` column in the courses table was NOT nullable and had no default value. When the form didn't send this field, the database insertion failed.

## Solution Applied

### Created New Migration
**File**: `database/migrations/2026_01_15_000004_make_curriculum_category_id_nullable.php`

**Changes**:
```php
// Make curriculum_category_id nullable
$table->unsignedBigInteger('curriculum_category_id')->nullable()->change();
```

### Migration Executed
✅ Migration ran successfully
✅ Column is now nullable
✅ Courses can be created without curriculum_category_id

## What This Fixes

✅ Courses can now be created without providing curriculum_category_id
✅ No more "Field doesn't have a default value" errors
✅ curriculum_category_id is truly optional

## Required Fields for Course Creation

```json
{
    "title": "Course Title",
    "description": "Course Description",
    "term_id": 1,
    "course_category_id": 1,
    "level_id": 1
}
```

## Optional Fields

- curriculum_category_id (now nullable)
- duration_hours
- url
- free_subscription
- free
- thumbnail

## Test the Fix

Try creating a course now:

```bash
POST /api/courses
{
    "title": "Test Course",
    "description": "Test Description",
    "term_id": 1,
    "course_category_id": 1,
    "level_id": 1
}
```

**Expected Result**: ✅ 201 Created

## Files Modified

1. `database/migrations/2026_01_15_000004_make_curriculum_category_id_nullable.php` (NEW)

## Status

✅ COMPLETE - Database column is now nullable
✅ Migration executed successfully
✅ Ready for course creation

