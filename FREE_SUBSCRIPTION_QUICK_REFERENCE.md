# Free Subscription - Quick Reference

## Files Modified

### Database Migrations
- `database/migrations/2026_01_15_000001_add_free_subscription_to_courses_table.php` (NEW)
- `database/migrations/2026_01_15_000002_create_course_subscription_plan_table.php` (NEW)

### Models
- `app/Models/Course.php` - Added subscriptionPlans() relationship
- `app/Models/SubscriptionPlan.php` - Added courses() relationship

### Controllers
- `app/Http/Controllers/SubscriptionController.php` - Updated validation
- `app/Http/Controllers/CourseController.php` - Added free_subscription field
- `app/Http/Controllers/UserSubscriptionController.php` - Added checkCourseAccess()

### Observers
- `app/Observers/CourseObserver.php` - Enhanced with free subscription logic

### Routes
- `routes/api.php` - Added /subscriptions/courses/{courseId}/access route

### Documentation
- `FREE_SUBSCRIPTION_IMPLEMENTATION.md` (NEW)
- `FREE_SUBSCRIPTION_QUICK_REFERENCE.md` (NEW)

## Database Schema

### courses table
```sql
ALTER TABLE courses ADD COLUMN free_subscription BOOLEAN DEFAULT FALSE;
```

### course_subscription_plan table
```sql
CREATE TABLE course_subscription_plan (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    course_id BIGINT NOT NULL,
    subscription_plan_id BIGINT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE(course_id, subscription_plan_id),
    FOREIGN KEY(course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY(subscription_plan_id) REFERENCES subscription_plans(id) ON DELETE CASCADE
);
```

## API Examples

### Create Free Subscription Plan
```bash
POST /api/subscriptions/plans
{
    "title": "Free Plan",
    "description": "Free access to selected courses",
    "price": 0,
    "duration": 1,
    "duration_type": "free",
    "features": ["Access to free courses"],
    "is_active": true
}
```

### Create Course with Free Subscription
```bash
POST /api/courses
{
    "title": "Free Course",
    "description": "A free course",
    "free_subscription": true,
    ...other fields
}
```

### Check Course Access
```bash
GET /api/subscriptions/courses/{courseId}/access
```

Response Examples:
```json
{
    "success": true,
    "data": {
        "course_id": 1,
        "has_access": true,
        "reason": "User has access to free courses (new/unsubscribed user)"
    }
}
```

```json
{
    "success": true,
    "data": {
        "course_id": 1,
        "has_access": true,
        "reason": "User has active free subscription"
    }
}
```

```json
{
    "success": true,
    "data": {
        "course_id": 1,
        "has_access": true,
        "reason": "User is enrolled in this course"
    }
}
```

## Validation Rules

### Duration Types
Valid values: free, daily, weekly, quarterly, monthly, half_yearly, yearly

### Course Fields
- `free_subscription`: boolean (optional)

## Automatic Behavior

### Course Management
When a course is created/updated with `free_subscription: true`:
1. System finds active free subscription plan
2. Automatically attaches course to plan
3. Course becomes available to all users with free subscription

When `free_subscription` is changed to false:
1. Course is automatically detached from free plan
2. Course no longer available via free subscription

### User Access
When checking course access for free courses:
1. **New users** (no subscriptions) → Automatic access granted
2. **Unsubscribed users** (no active subscriptions) → Automatic access granted
3. **Users with free subscription** → Access granted
4. **Users with paid subscriptions** → Access denied (unless enrolled)

## Next Steps

1. Run migrations: `php artisan migrate`
2. Create a free subscription plan via admin panel
3. Create courses with free_subscription checkbox
4. Test user access via API endpoint

