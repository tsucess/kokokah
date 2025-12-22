# Points & Badges API Endpoints Reference

## Base URL
```
http://localhost:8000/api/points-badges
```

## Authentication
All endpoints require Bearer token authentication:
```
Authorization: Bearer YOUR_TOKEN
```

---

## Points Endpoints

### 1. Get User Points
**Endpoint**: `GET /points`

**Description**: Get user's current points, level, and progress to next level

**Response**:
```json
{
  "success": true,
  "data": {
    "user_id": 2,
    "points": 250,
    "level": "Intermediate",
    "next_level_points": 500,
    "progress_to_next_level": 50
  }
}
```

**Status Codes**:
- `200` - Success
- `500` - Server error

---

### 2. Get Points History
**Endpoint**: `GET /points/history`

**Description**: Get user's points transaction history with pagination

**Query Parameters**:
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| limit | integer | 50 | Records per page |
| page | integer | 1 | Page number |

**Response**:
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
        "created_at": "2025-12-22T10:00:00Z",
        "updated_at": "2025-12-22T10:00:00Z"
      }
    ],
    "links": {...},
    "meta": {...}
  }
}
```

**Status Codes**:
- `200` - Success
- `500` - Server error

---

## Badge Endpoints

### 3. Get User Badges
**Endpoint**: `GET /badges`

**Description**: Get user's earned badges with optional category filtering

**Query Parameters**:
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| category | string | null | Filter by category (learning, achievement, social, special) |
| per_page | integer | 20 | Records per page |
| page | integer | 1 | Page number |

**Response**:
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
        "created_at": "2025-12-22T10:00:00Z",
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
    "links": {...},
    "meta": {...}
  }
}
```

**Status Codes**:
- `200` - Success
- `500` - Server error

---

### 4. Get Badge Details
**Endpoint**: `GET /badges/{badgeId}`

**Description**: Get specific badge details with user's progress

**URL Parameters**:
| Parameter | Type | Description |
|-----------|------|-------------|
| badgeId | integer | Badge ID |

**Response**:
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
      "is_active": true,
      "created_at": "2025-12-22T10:00:00Z"
    },
    "earned": true,
    "earned_at": "2025-12-22T10:00:00Z",
    "progress": 0
  }
}
```

**Status Codes**:
- `200` - Success
- `404` - Badge not found
- `500` - Server error

---

### 5. Get Badge Statistics
**Endpoint**: `GET /badges/stats`

**Description**: Get user's badge statistics and summary

**Response**:
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
    "recent_badges": [
      {
        "id": 1,
        "name": "First Lesson",
        "icon": "🎓",
        "earned_at": "2025-12-22T10:00:00Z"
      }
    ]
  }
}
```

**Status Codes**:
- `200` - Success
- `500` - Server error

---

## Leaderboard Endpoint

### 6. Get Global Leaderboard
**Endpoint**: `GET /leaderboard`

**Description**: Get global leaderboard ranked by points

**Query Parameters**:
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| period | string | all_time | Time period (all_time) |
| limit | integer | 50 | Records per page |
| page | integer | 1 | Page number |

**Response**:
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
    "links": {...},
    "meta": {...}
  }
}
```

**Status Codes**:
- `200` - Success
- `500` - Server error

---

## Error Responses

All endpoints return consistent error format:

```json
{
  "success": false,
  "message": "Error description"
}
```

**Common Errors**:
- `401` - Unauthorized (missing/invalid token)
- `404` - Resource not found
- `422` - Validation error
- `500` - Server error

---

## Level System

| Level | Points Range | Description |
|-------|--------------|-------------|
| Amateur | 0-99 | Beginner |
| Intermediate | 100-499 | Intermediate |
| Advanced | 500-999 | Advanced |
| Expert | 1000+ | Expert |

---

## Badge Categories

| Category | Description |
|----------|-------------|
| learning | Learning-related badges |
| achievement | Achievement badges |
| social | Social/community badges |
| special | Special/milestone badges |

---

## Badge Types

| Type | Description |
|------|-------------|
| lesson_completion | Lesson completion badges |
| topic_completion | Topic completion badges |
| course_completion | Course completion badges |
| course_enrollment | Enrollment badges |
| quiz_mastery | Quiz mastery badges |
| points | Points-based badges |
| speed | Speed/time badges |
| time | Time-based badges |
| streak | Streak badges |
| participation | Participation badges |
| instructor | Instructor badges |
| milestone | Milestone badges |

---

## Example Usage

### Get User Points
```bash
curl -H "Authorization: Bearer TOKEN" \
  http://localhost:8000/api/points-badges/points
```

### Get Points History (Last 10)
```bash
curl -H "Authorization: Bearer TOKEN" \
  "http://localhost:8000/api/points-badges/points/history?limit=10"
```

### Get Learning Badges
```bash
curl -H "Authorization: Bearer TOKEN" \
  "http://localhost:8000/api/points-badges/badges?category=learning"
```

### Get Leaderboard Top 20
```bash
curl -H "Authorization: Bearer TOKEN" \
  "http://localhost:8000/api/points-badges/leaderboard?limit=20"
```

---

## Rate Limiting

No rate limiting currently implemented. Consider adding for production.

## Pagination

All list endpoints support pagination:
- Default page size varies by endpoint
- Use `page` parameter to navigate
- Response includes `links` and `meta` for pagination info

## Caching

Consider implementing caching for:
- Leaderboard (cache for 1 hour)
- Badge statistics (cache for 30 minutes)
- User points (cache for 5 minutes)

