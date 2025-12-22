# Database and Endpoints Modifications for Badges & Points

## Overview
Enhanced the badges and points system with new database tables, models, and comprehensive API endpoints for better tracking and management.

## Database Changes

### 1. Enhanced Badges Table
**File**: `database/migrations/2025_09_09_164023_create_badges_table.php`

**New Columns**:
- `description` (text) - Badge description
- `points` (integer) - Points awarded for badge
- `category` (enum) - learning, achievement, social, special
- `type` (enum) - lesson_completion, topic_completion, course_completion, etc.
- `is_active` (boolean) - Badge active status
- `created_by` (bigInteger) - Admin who created badge

**Indexes**:
- category
- type
- is_active

### 2. Enhanced User Badges Pivot Table
**File**: `database/migrations/2025_09_10_000001_create_missing_pivot_tables.php`

**New Columns** (added via migration):
- `revoked_at` (timestamp) - When badge was revoked
- `is_featured` (boolean) - Featured on profile
- `progress` (integer) - Progress towards badge

### 3. New: User Points History Table
**File**: `database/migrations/2025_12_22_160000_enhance_badges_and_points_tables.php`

**Purpose**: Track all points changes with detailed history

**Columns**:
- `id` (primary key)
- `user_id` (foreign key)
- `points_change` (integer) - Amount changed
- `points_before` (integer) - Points before change
- `points_after` (integer) - Points after change
- `reason` (string) - Why points changed
- `action_type` (string) - lesson_completion, quiz_pass, etc.
- `action_id` (bigInteger) - ID of action
- `action_model` (string) - Model name (Lesson, Quiz, etc.)
- `metadata` (json) - Additional data
- `timestamps`

**Indexes**:
- user_id, created_at
- action_type

### 4. New: Badge Criteria Log Table
**File**: `database/migrations/2025_12_22_160000_enhance_badges_and_points_tables.php`

**Purpose**: Track badge qualification attempts

**Columns**:
- `id` (primary key)
- `user_id` (foreign key)
- `badge_id` (foreign key)
- `qualified` (boolean) - Did user qualify
- `criteria_data` (json) - User data for criteria
- `reason` (string) - Why they didn't qualify
- `timestamps`

**Indexes**:
- user_id, badge_id
- qualified

### 5. New: User Level History Table
**File**: `database/migrations/2025_12_22_160000_enhance_badges_and_points_tables.php`

**Purpose**: Track user level progression

**Columns**:
- `id` (primary key)
- `user_id` (foreign key)
- `previous_level` (string) - Amateur, Intermediate, Advanced, Expert
- `new_level` (string) - New level
- `points_at_change` (integer) - Points when level changed
- `timestamps`

**Indexes**:
- user_id, created_at

## New Models

### 1. UserPointsHistory
**File**: `app/Models/UserPointsHistory.php`

**Methods**:
- `user()` - Get user
- `scopeByActionType()` - Filter by action type
- `scopeRecent()` - Get recent history
- `getActionModel()` - Get related model instance

### 2. BadgeCriteriaLog
**File**: `app/Models/BadgeCriteriaLog.php`

**Methods**:
- `user()` - Get user
- `badge()` - Get badge
- `scopeQualified()` - Get qualified logs
- `scopeNotQualified()` - Get not qualified logs
- `scopeForBadge()` - Filter by badge
- `scopeForUser()` - Filter by user

### 3. UserLevelHistory
**File**: `app/Models/UserLevelHistory.php`

**Methods**:
- `user()` - Get user
- `scopeForUser()` - Get user's level changes
- `scopeToLevel()` - Filter by level
- `scopeRecent()` - Get recent changes
- `getLevelProgression()` - Get progression details

## Updated Models

### User Model
**New Relationships**:
- `pointsHistory()` - User's points history
- `badgeCriteriaLogs()` - Badge qualification logs
- `levelHistory()` - User's level changes

### Badge Model
**New Columns**:
- description, points, category, type, is_active, created_by

**New Relationships**:
- `criteriaLogs()` - Badge criteria logs

**New Methods**:
- `scopeActive()` - Get active badges
- `scopeByCategory()` - Filter by category
- `scopeByType()` - Filter by type
- `revokeFrom()` - Revoke badge from user
- `getTotalPointsAwarded()` - Total points from badge

## New API Endpoints

### Points Endpoints

#### GET /api/points-badges/points
Get user's current points and level

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

#### GET /api/points-badges/points/history
Get user's points history with pagination

**Query Parameters**:
- `limit` (default: 50)

**Response**:
```json
{
  "success": true,
  "data": {
    "data": [...],
    "pagination": {...}
  }
}
```

### Badge Endpoints

#### GET /api/points-badges/badges
Get user's badges with filtering

**Query Parameters**:
- `category` - Filter by category
- `per_page` (default: 20)

**Response**:
```json
{
  "success": true,
  "data": {
    "data": [...],
    "pagination": {...}
  }
}
```

#### GET /api/points-badges/badges/{badgeId}
Get badge details with user progress

**Response**:
```json
{
  "success": true,
  "data": {
    "badge": {...},
    "earned": true,
    "earned_at": "2025-12-22T10:00:00Z",
    "progress": 0
  }
}
```

#### GET /api/points-badges/badges/stats
Get user's badge statistics

**Response**:
```json
{
  "success": true,
  "data": {
    "total_badges": 5,
    "badges_by_category": [...],
    "total_badge_points": 150,
    "recent_badges": [...]
  }
}
```

### Leaderboard Endpoint

#### GET /api/points-badges/leaderboard
Get global leaderboard with points and badges

**Query Parameters**:
- `period` - all_time (default)
- `limit` (default: 50)

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
        "profile_photo": "/storage/...",
        "points": 500,
        "level": "Advanced",
        "badges_count": 10
      }
    ],
    "pagination": {...}
  }
}
```

## Migration Steps

```bash
# 1. Run migrations
php artisan migrate

# 2. Seed badges
php artisan db:seed --class=BadgesSeeder

# 3. Verify
php artisan tinker
$badges = App\Models\Badge::count();
echo "Total badges: " . $badges;
```

## Testing Endpoints

```bash
# Get user points
curl -H "Authorization: Bearer TOKEN" \
  http://localhost:8000/api/points-badges/points

# Get points history
curl -H "Authorization: Bearer TOKEN" \
  http://localhost:8000/api/points-badges/points/history

# Get user badges
curl -H "Authorization: Bearer TOKEN" \
  http://localhost:8000/api/points-badges/badges

# Get leaderboard
curl -H "Authorization: Bearer TOKEN" \
  http://localhost:8000/api/points-badges/leaderboard
```

## Performance Considerations

- Indexes on frequently queried columns
- Pagination for large datasets
- Efficient relationship loading
- JSON storage for flexible metadata
- Soft deletes for data integrity

## Future Enhancements

- Badge progression levels
- Real-time notifications
- Badge trading/gifting
- Advanced analytics
- Custom badge creation

