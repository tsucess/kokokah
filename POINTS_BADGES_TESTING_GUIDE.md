# Points and Badges System - Testing Guide

## API Endpoints to Test

### 1. Get User Points
```
GET /api/users/points
Authorization: Bearer {token}

Response:
{
  "success": true,
  "data": {
    "user_id": 2,
    "points": 150,
    "level": "Intermediate"
  }
}
```

### 2. Enroll with Points
```
POST /api/users/enroll-with-points
Authorization: Bearer {token}
Content-Type: application/json

Body:
{
  "course_id": 1
}

Response:
{
  "success": true,
  "message": "Successfully enrolled using points",
  "data": {
    "enrollment": {...},
    "remaining_points": 100
  }
}
```

### 3. Complete Lesson (Awards 5 Points)
```
POST /api/lessons/{lessonId}/complete
Authorization: Bearer {token}

Response:
{
  "success": true,
  "message": "Lesson marked as complete",
  "data": {...},
  "user_points": 155,
  "points_awarded": 5
}
```

### 4. Submit Quiz (Awards 10 Points if Passed)
```
POST /api/quizzes/{quizId}/submit
Authorization: Bearer {token}

Response includes:
{
  "passed": true,
  "user_points": 165,
  "points_awarded": 10
}
```

### 5. Complete Course (Awards 50 Points)
```
POST /api/enrollments/{enrollmentId}/complete
Authorization: Bearer {token}

Response:
{
  "success": true,
  "message": "Course completed successfully!",
  "data": {
    "enrollment": {...},
    "user_points": 215,
    "points_awarded": 50
  }
}
```

### 6. Get User Profile (Includes Points)
```
GET /api/users/profile
Authorization: Bearer {token}

Response includes:
{
  "stats": {
    "points": 215,
    "level": "Intermediate"
  }
}
```

### 7. Get Leaderboard (Shows Points)
```
GET /api/badges/leaderboard?period=all_time

Response:
{
  "success": true,
  "data": {
    "leaderboard": [
      {
        "id": 2,
        "first_name": "John",
        "last_name": "Doe",
        "points": 215,
        "level": "Intermediate",
        "badges_count": 3,
        "rank": 1
      }
    ]
  }
}
```

## Manual Testing Steps

1. **Create test user** and note the user ID
2. **Get initial points** - Should be 0
3. **Complete a lesson** - Points should increase by 5
4. **Pass a quiz** - Points should increase by 10
5. **Complete a course** - Points should increase by 50
6. **Check leaderboard** - User should appear with correct points
7. **Enroll with points** - Should deduct points from balance
8. **Check badges** - Should be awarded based on criteria

## Expected Point Totals After Each Action

- Initial: 0 points
- After lesson 1: 5 points
- After quiz 1 (pass): 15 points
- After lesson 2: 20 points
- After course completion: 70 points
- After enrolling with points (if course costs 50): 20 points remaining

## Level Progression

- 0-99: Amateur
- 100-499: Intermediate
- 500-999: Advanced
- 1000+: Expert

