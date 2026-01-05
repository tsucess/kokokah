# Badge System Usage Guide

## Quick Start

### 1. Seed the Badges
```bash
php artisan db:seed --class=BadgesSeeder
```

This will insert all 30 badges into the database.

### 2. How Badges Are Awarded

Badges are automatically awarded when users meet criteria. The system checks for badge qualification after:
- Completing a lesson
- Completing a topic
- Passing a quiz
- Completing a course
- Earning points
- Chatroom activity
- Enrollment actions

### 3. Badge Criteria Format

All badges use simple string format: `type:value`

**Examples:**
```
lesson_completion:10      → Complete 10 lessons
topic_completion:5        → Complete 5 topics
course_completion:1       → Complete 1 course
quiz_pass:25             → Pass 25 quizzes
quiz_perfect:1           → Get 100% on a quiz
points:500               → Earn 500 points
consecutive_days:30      → Study 30 consecutive days
chatroom_posts:10        → Make 10 chatroom posts
helpful_posts:5          → Help 5 students
active_enrollments:5     → Have 5 active enrollments
total_enrollments:20     → Enroll in 20 courses total
instructor:1             → Create 1 course
level:expert             → Reach Expert level
```

## Adding New Badges

### Method 1: Direct Database Insert
```sql
INSERT INTO badges (name, description, points, icon, criteria, category, type, created_at, updated_at)
VALUES ('Badge Name', 'Description', 50, '🎯', 'criteria_type:value', 'category', 'type', NOW(), NOW());
```

### Method 2: Using Seeder
Edit `database/seeders/BadgesSeeder.php` and add to the badges array:
```php
['name' => 'New Badge', 'description' => 'Description', 'points' => 50, 'icon' => '🎯', 'criteria' => 'criteria_type:value', 'category' => 'category', 'type' => 'type'],
```

## Badge Categories

- **learning** - Course, lesson, topic related
- **achievement** - Quiz, points, speed related
- **social** - Chatroom, community related
- **special** - Instructor, milestone related

## Badge Types

- lesson_completion
- topic_completion
- course_completion
- course_enrollment
- quiz_mastery
- points
- speed
- time
- streak
- participation
- instructor
- milestone

## Testing Badges

### Test Lesson Completion Badge
```bash
# Complete a lesson
POST /api/lessons/{lessonId}/complete

# Check user badges
GET /api/users/{userId}/badges
```

### Test Quiz Badge
```bash
# Submit quiz with 100% score
POST /api/quizzes/{quizId}/submit
Body: { "answers": [...], "score": 100 }

# Check if Perfect Score badge awarded
GET /api/users/{userId}/badges
```

### Test Points Badge
```bash
# Get user points
GET /api/users/points

# Should show points and any earned badges
```

### Test Consecutive Days Badge
```bash
# Complete lessons on consecutive days
# System automatically tracks and awards badge
```

## Frontend Integration

### Display User Badges
```javascript
// Get user badges
const response = await fetch('/api/users/profile');
const data = await response.json();
const badges = data.data.badges;

// Display badges
badges.forEach(badge => {
  console.log(`${badge.icon} ${badge.name}`);
});
```

### Display Leaderboard with Badges
```javascript
// Get leaderboard
const response = await fetch('/api/badges/leaderboard');
const data = await response.json();

// Shows users with their badges and points
```

## Badge Progression Example

**User Journey:**
1. Enroll in course → "Course Starter" badge (5 pts)
2. Complete 1st lesson → "First Lesson" badge (10 pts)
3. Complete 10 lessons → "Lesson Enthusiast" badge (25 pts)
4. Pass 1st quiz → "Quiz Taker" badge (10 pts)
5. Get 100% on quiz → "Perfect Score" badge (40 pts)
6. Complete 1st course → "Course Completer" badge (50 pts)
7. Earn 100 points → "Point Collector" badge (20 pts)
8. Study 7 consecutive days → "Consistent Learner" badge (40 pts)
9. Complete 10 courses → "Scholar" badge (100 pts)
10. Reach 1000 points → "Legendary Learner" badge (200 pts)

**Total Points Earned: 500 points**

## Troubleshooting

### Badge Not Awarded
1. Check if user meets criteria
2. Verify badge criteria format is correct
3. Check if user already has badge
4. Run: `php artisan tinker` and manually check

### Check User Badges
```bash
php artisan tinker
$user = App\Models\User::find(2);
$user->badges()->get();
```

### Reset User Badges
```bash
php artisan tinker
$user = App\Models\User::find(2);
$user->badges()->detach();
```

## Performance Considerations

- Badge checking happens after each qualifying action
- Badges are cached in user relationships
- Consider indexing criteria column for large datasets
- Batch badge checks for bulk operations

## Future Enhancements

- Badge progression levels (Bronze → Silver → Gold)
- Seasonal badges
- Limited-time badges
- Badge trading/gifting
- Badge collections
- Leaderboard filtering by badge

