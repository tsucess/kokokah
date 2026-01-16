# Quick Reference - Course API

## Create Course

**Endpoint**: `POST /api/courses`

**Required Fields**:
```json
{
    "title": "Course Title",
    "description": "Course Description",
    "term_id": 1,
    "course_category_id": 1,
    "level_id": 1
}
```

**Optional Fields**:
```json
{
    "curriculum_category_id": 1,
    "duration_hours": 40,
    "url": "https://example.com/video",
    "free_subscription": true,
    "thumbnail": <file>
}
```

**Success Response** (201):
```json
{
    "success": true,
    "message": "Course created successfully",
    "data": { ... }
}
```

**Error Response** (422):
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "title": ["The title field is required"]
    }
}
```

## Update Course

**Endpoint**: `PUT /api/courses/{id}`

**All fields are optional** (use `sometimes` validation)

## Delete Course

**Endpoint**: `DELETE /api/courses/{id}`

**Note**: Cannot delete if course has enrollments

## Get Course

**Endpoint**: `GET /api/courses/{id}`

## List Courses

**Endpoint**: `GET /api/courses`

## Search Courses

**Endpoint**: `GET /api/courses/search`

**Parameters**:
- `q` (required): search query
- `curriculum_category_id` (optional)
- `course_category_id` (optional)
- `level_id` (optional)
- `sort_by` (optional): title, created_at, rating
- `sort_order` (optional): asc, desc

## Key Changes

✅ **Removed**: `price` field
✅ **Added**: `free_subscription` field
✅ **Required**: `term_id`, `course_category_id`, `level_id`
✅ **Optional**: `curriculum_category_id`

## Pricing Model

Courses are now linked to subscription plans:
- Free courses auto-enroll in free plan
- Paid courses linked via subscription plans
- No individual course pricing

## Status

✅ Production Ready
✅ All validations working
✅ Error handling improved
✅ Documentation complete

