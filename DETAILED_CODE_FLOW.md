# Detailed Code Flow Analysis

## Frontend: usersubject.blade.php

### Current Flow (Lines 93-121)
```javascript
async function loadUserCourses() {
    const response = await CourseApiClient.getMyCourses();
    // Extracts courses from response
    userCourses = response.data.courses || response.data.data || response.data;
    renderCourses(userCourses);
}
```

**Issue**: Only calls `getMyCourses()` which returns enrolled courses only.

## API Client: courseApiClient.js

### getMyCourses() Method (Lines 239-249)
```javascript
static async getMyCourses(filters = {}) {
    const endpoint = `/courses/my-courses`;
    return this.get(endpoint);
}
```

**Calls**: `GET /api/courses/my-courses`

## Backend: CourseController.php

### myCourses() Method (Lines 473-503)
```php
public function myCourses() {
    $user = Auth::user();
    $enrollments = Enrollment::where('user_id', $user->id)
                             ->latest('enrolled_at')
                             ->get();
    
    $results = [];
    foreach ($enrollments as $e) {
        $course = Course::with(['courseCategory', 'instructor', 'level'])
                       ->find($e->course_id);
        if ($course) {
            $item = $e->toArray();
            $item['course'] = $course;
            $results[] = $item;
        }
    }
    
    return $this->success(['courses' => $results, 'total' => count($results)]);
}
```

**Problem**: Only fetches from `Enrollment` table. Ignores:
- Free subscription courses
- User's active subscriptions
- Courses from subscription plans

## What Should Happen

The endpoint should return:
1. **Enrolled courses** (current)
2. **Free courses** (new) - via free subscription plan
3. **Subscription courses** (new) - via user's active subscriptions

## Related Endpoint: checkCourseAccess()

Located in `UserSubscriptionController` (Lines 249-327), this method ALREADY implements the logic to determine if a user should have access to a course based on subscriptions.

**Key Logic**:
- Checks if course is in free subscription plan
- Grants access to new/unsubscribed users
- Grants access to users with active free subscription
- Checks enrollment status

**This logic should be integrated into `myCourses()`**

## Database Queries Needed

To fetch all accessible courses:
```php
// 1. Enrolled courses
$enrolledCourses = Enrollment::where('user_id', $user->id)
                             ->with('course')
                             ->get();

// 2. Free courses (for new/unsubscribed users)
$freeSubscriptionPlan = SubscriptionPlan::where('duration_type', 'free')
                                       ->where('is_active', true)
                                       ->first();
$freeCourses = $freeSubscriptionPlan->courses()
                                   ->where('status', 'published')
                                   ->get();

// 3. Subscription courses (for users with active subscriptions)
$subscriptionCourses = UserSubscription::where('user_id', $user->id)
                                      ->where('status', 'active')
                                      ->with('subscriptionPlan.courses')
                                      ->get();
```

## Summary

**Current**: Only enrolled courses shown
**Expected**: Enrolled + Free + Subscription courses shown
**Impact**: New users see empty page instead of available free courses

