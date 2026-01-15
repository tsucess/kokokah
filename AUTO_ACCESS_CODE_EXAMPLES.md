# Auto-Access Feature - Code Examples

## API Usage Examples

### Example 1: New User Accessing Free Course

**Request**:
```bash
curl -X GET "https://api.kokokah.com/api/subscriptions/courses/5/access" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

**Response** (200 OK):
```json
{
    "success": true,
    "data": {
        "course_id": 5,
        "has_access": true,
        "reason": "User has access to free courses (new/unsubscribed user)"
    }
}
```

### Example 2: User with Free Subscription

**Request**:
```bash
curl -X GET "https://api.kokokah.com/api/subscriptions/courses/5/access" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

**Response** (200 OK):
```json
{
    "success": true,
    "data": {
        "course_id": 5,
        "has_access": true,
        "reason": "User has active free subscription"
    }
}
```

### Example 3: User with Paid Subscription (No Access)

**Request**:
```bash
curl -X GET "https://api.kokokah.com/api/subscriptions/courses/5/access" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

**Response** (200 OK):
```json
{
    "success": true,
    "data": {
        "course_id": 5,
        "has_access": false,
        "reason": "Course requires free subscription which user does not have"
    }
}
```

### Example 4: Enrolled User

**Request**:
```bash
curl -X GET "https://api.kokokah.com/api/subscriptions/courses/10/access" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

**Response** (200 OK):
```json
{
    "success": true,
    "data": {
        "course_id": 10,
        "has_access": true,
        "reason": "User is enrolled in this course"
    }
}
```

## Frontend Implementation Example

### Vue.js Component
```javascript
async checkCourseAccess(courseId) {
    try {
        const response = await fetch(
            `/api/subscriptions/courses/${courseId}/access`,
            {
                headers: {
                    'Authorization': `Bearer ${this.token}`,
                    'Accept': 'application/json'
                }
            }
        );
        
        const data = await response.json();
        
        if (data.data.has_access) {
            // Show course content
            this.showCourseContent();
        } else {
            // Show access denied message
            this.showAccessDenied(data.data.reason);
        }
    } catch (error) {
        console.error('Error checking access:', error);
    }
}
```

### React Hook
```javascript
const useCourseAccess = (courseId) => {
    const [hasAccess, setHasAccess] = useState(null);
    const [reason, setReason] = useState(null);
    
    useEffect(() => {
        fetch(`/api/subscriptions/courses/${courseId}/access`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            setHasAccess(data.data.has_access);
            setReason(data.data.reason);
        });
    }, [courseId]);
    
    return { hasAccess, reason };
};
```

## Database Query Examples

### Check if User Has Any Active Subscriptions
```php
$hasAnyActiveSubscription = UserSubscription::where('user_id', $userId)
    ->where('status', 'active')
    ->where(function ($q) {
        $q->whereNull('expires_at')
          ->orWhere('expires_at', '>', Carbon::now());
    })
    ->exists();
```

### Check if User Has Free Subscription
```php
$hasFreeSubscription = UserSubscription::where('user_id', $userId)
    ->where('subscription_plan_id', $freePlanId)
    ->where('status', 'active')
    ->where(function ($q) {
        $q->whereNull('expires_at')
          ->orWhere('expires_at', '>', Carbon::now());
    })
    ->exists();
```

### Get Free Subscription Plan
```php
$freeSubscriptionPlan = SubscriptionPlan::where('duration_type', 'free')
    ->where('is_active', true)
    ->first();
```

## Testing Examples

### PHPUnit Test
```php
public function test_new_user_can_access_free_course()
{
    $user = User::factory()->create();
    $course = Course::factory()->create(['free_subscription' => true]);
    
    $response = $this->actingAs($user)
        ->getJson("/api/subscriptions/courses/{$course->id}/access");
    
    $response->assertStatus(200)
        ->assertJsonPath('data.has_access', true)
        ->assertJsonPath('data.reason', 
            'User has access to free courses (new/unsubscribed user)');
}
```

### Postman Test
```json
{
    "name": "Check Free Course Access",
    "request": {
        "method": "GET",
        "url": "{{base_url}}/api/subscriptions/courses/5/access",
        "header": [
            {
                "key": "Authorization",
                "value": "Bearer {{token}}"
            }
        ]
    },
    "tests": "pm.test('Has access', function() { pm.expect(pm.response.json().data.has_access).to.be.true; });"
}
```

