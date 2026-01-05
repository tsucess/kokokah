# Points & Badges Endpoints Testing Guide

## Setup

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Badges
```bash
php artisan db:seed --class=BadgesSeeder
```

### 3. Get Authentication Token
```bash
# Register or login to get token
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Or login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

## Testing Points Endpoints

### 1. Get User Points
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/points-badges/points
```

**Expected Response**:
```json
{
  "success": true,
  "data": {
    "user_id": 2,
    "points": 0,
    "level": "Amateur",
    "next_level_points": 100,
    "progress_to_next_level": 0
  }
}
```

### 2. Get Points History
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  "http://localhost:8000/api/points-badges/points/history?limit=10"
```

**Expected Response**:
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "user_id": 2,
        "points_change": 5,
        "points_before": 0,
        "points_after": 5,
        "reason": "Lesson completed",
        "action_type": "lesson_completion",
        "action_id": 1,
        "action_model": "Lesson",
        "metadata": null,
        "created_at": "2025-12-22T10:00:00Z"
      }
    ],
    "pagination": {...}
  }
}
```

## Testing Badge Endpoints

### 1. Get User Badges
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  "http://localhost:8000/api/points-badges/badges?per_page=20"
```

**With Category Filter**:
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  "http://localhost:8000/api/points-badges/badges?category=learning&per_page=20"
```

**Expected Response**:
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "name": "First Lesson",
        "description": "Complete your first lesson",
        "icon": "🎓",
        "points": 10,
        "category": "learning",
        "type": "lesson_completion",
        "is_active": true,
        "pivot": {
          "user_id": 2,
          "badge_id": 1,
          "earned_at": "2025-12-22T10:00:00Z",
          "revoked_at": null,
          "is_featured": false,
          "progress": 0
        }
      }
    ],
    "pagination": {...}
  }
}
```

### 2. Get Badge Details
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/points-badges/badges/1
```

**Expected Response**:
```json
{
  "success": true,
  "data": {
    "badge": {
      "id": 1,
      "name": "First Lesson",
      "description": "Complete your first lesson",
      "icon": "🎓",
      "points": 10,
      "category": "learning",
      "type": "lesson_completion",
      "is_active": true
    },
    "earned": true,
    "earned_at": "2025-12-22T10:00:00Z",
    "progress": 0
  }
}
```

### 3. Get Badge Statistics
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/points-badges/badges/stats
```

**Expected Response**:
```json
{
  "success": true,
  "data": {
    "total_badges": 5,
    "badges_by_category": [
      {
        "category": "learning",
        "count": 3
      },
      {
        "category": "achievement",
        "count": 2
      }
    ],
    "total_badge_points": 150,
    "recent_badges": [...]
  }
}
```

## Testing Leaderboard Endpoint

### 1. Get Global Leaderboard
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  "http://localhost:8000/api/points-badges/leaderboard?limit=50"
```

**Expected Response**:
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "rank": 1,
        "user_id": 2,
        "name": "John Doe",
        "email": "john@example.com",
        "profile_photo": "/storage/profile/john.jpg",
        "points": 500,
        "level": "Advanced",
        "badges_count": 10
      },
      {
        "rank": 2,
        "user_id": 3,
        "name": "Jane Smith",
        "email": "jane@example.com",
        "profile_photo": "/storage/profile/jane.jpg",
        "points": 450,
        "level": "Advanced",
        "badges_count": 9
      }
    ],
    "pagination": {...}
  }
}
```

## Testing Points Earning

### 1. Complete a Lesson
```bash
curl -X POST -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/lessons/1/complete
```

**Expected**: User should earn 5 points

### 2. Pass a Quiz
```bash
curl -X POST -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/quizzes/1/submit \
  -H "Content-Type: application/json" \
  -d '{
    "answers": [
      {"question_id": 1, "answer": "A"},
      {"question_id": 2, "answer": "B"}
    ]
  }'
```

**Expected**: User should earn 10 points if passed

### 3. Complete a Course
```bash
curl -X POST -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/enrollments/1/complete
```

**Expected**: User should earn 50 points

## Database Verification

### Check User Points
```bash
php artisan tinker
$user = App\Models\User::find(2);
echo $user->points;
```

### Check Points History
```bash
php artisan tinker
$history = App\Models\UserPointsHistory::where('user_id', 2)->get();
$history->each(fn($h) => echo $h->reason . ': ' . $h->points_change . PHP_EOL);
```

### Check User Badges
```bash
php artisan tinker
$user = App\Models\User::find(2);
$badges = $user->badges()->get();
$badges->each(fn($b) => echo $b->name . PHP_EOL);
```

### Check Badge Criteria Logs
```bash
php artisan tinker
$logs = App\Models\BadgeCriteriaLog::where('user_id', 2)->get();
$logs->each(fn($l) => echo $l->badge->name . ': ' . ($l->qualified ? 'QUALIFIED' : 'NOT QUALIFIED') . PHP_EOL);
```

## Error Handling

All endpoints return consistent error responses:

```json
{
  "success": false,
  "message": "Error description"
}
```

## Performance Notes

- Leaderboard is paginated (default 50 per page)
- Points history is paginated (default 50 per page)
- Badges are paginated (default 20 per page)
- All queries use indexes for optimal performance
- Relationships are eager loaded to prevent N+1 queries

## Next Steps

1. Test all endpoints with different users
2. Verify points are awarded correctly
3. Check badge qualification logic
4. Monitor database performance
5. Implement frontend integration

