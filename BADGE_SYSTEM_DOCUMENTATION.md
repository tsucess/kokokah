# Badge System Documentation (30 Badges)

## Overview
Comprehensive badge system with 30 badges covering lessons, topics, courses, quizzes, points, speed, consistency, chatroom, enrollments, and special achievements.

## Badge Categories

### 1. LESSON BADGES (3 badges)
- **Badge 1**: First Lesson - Complete your first lesson (10 pts)
- **Badge 2**: Lesson Enthusiast - Complete 10 lessons (25 pts)
- **Badge 3**: Lesson Master - Complete 50 lessons (50 pts)

### 2. TOPIC BADGES (3 badges)
- **Badge 4**: Topic Starter - Complete your first topic (15 pts)
- **Badge 5**: Topic Explorer - Complete 5 topics (30 pts)
- **Badge 6**: Topic Conqueror - Complete 20 topics (60 pts)

### 3. COURSE BADGES (4 badges)
- **Badge 7**: Course Starter - Enroll in your first course (5 pts)
- **Badge 8**: Course Completer - Complete your first course (50 pts)
- **Badge 9**: Scholar - Complete 10 courses (100 pts)
- **Badge 10**: Master Student - Complete 25 courses (150 pts)

### 4. QUIZ BADGES (4 badges)
- **Badge 11**: Quiz Taker - Complete your first quiz (10 pts)
- **Badge 12**: Perfect Score - Get 100% on a quiz (40 pts)
- **Badge 13**: Quiz Master - Pass 25 quizzes (75 pts)
- **Badge 14**: Quiz Legend - Pass 50 quizzes (120 pts)

### 5. POINTS BADGES (3 badges)
- **Badge 15**: Point Collector - Earn 100 points (20 pts)
- **Badge 16**: Point Hoarder - Earn 500 points (50 pts)
- **Badge 17**: Point Master - Earn 1000 points (100 pts)

### 6. SPEED & TIME BADGES (3 badges)
- **Badge 18**: Quick Learner - Complete course in <7 days (35 pts)
- **Badge 19**: Early Bird - Complete 5 lessons before 8 AM (25 pts)
- **Badge 20**: Night Owl - Complete 5 lessons after 10 PM (25 pts)

### 7. CONSISTENCY BADGES (3 badges)
- **Badge 21**: Consistent Learner - Study 7 consecutive days (40 pts)
- **Badge 22**: Dedicated Learner - Study 30 consecutive days (80 pts)
- **Badge 23**: Unstoppable - Study 100 consecutive days (150 pts)

### 8. CHATROOM & SOCIAL BADGES (3 badges)
- **Badge 24**: Social Butterfly - 10 chatroom discussions (20 pts)
- **Badge 25**: Community Helper - Help 5 students (35 pts)
- **Badge 26**: Community Leader - Help 20 students (70 pts)

### 9. ENROLLMENT BADGES (2 badges)
- **Badge 27**: Multi-Learner - Enroll in 5 courses simultaneously (30 pts)
- **Badge 28**: Enrollment Master - Enroll in 20 courses total (60 pts)

### 10. SPECIAL BADGES (2 badges)
- **Badge 29**: Instructor - Create your first course (50 pts)
- **Badge 30**: Legendary Learner - Achieve Expert level (200 pts)

## Badge Criteria Format

Badges use simple string format: `criteria_type:value`

Examples:
- `lesson_completion:10` - Complete 10 lessons
- `course_completion:5` - Complete 5 courses
- `quiz_pass:25` - Pass 25 quizzes
- `points:500` - Earn 500 points
- `consecutive_days:30` - Study 30 consecutive days

## Implementation Details

### Automatic Badge Award Triggers
1. **Lesson Completion** - Triggers lesson_completion badges
2. **Topic Completion** - Triggers topic_completion badges
3. **Course Completion** - Triggers course_completion badges
4. **Quiz Pass** - Triggers quiz_pass badges
5. **Points Earned** - Triggers points badges
6. **Chatroom Activity** - Triggers social badges
7. **Enrollment** - Triggers enrollment badges

### Badge Qualification Logic
- Badges are checked automatically after each qualifying action
- Users cannot earn the same badge twice
- Badge criteria are evaluated in real-time
- All badge types support progressive unlocking

## Database Schema

```sql
badges table:
- id (primary key)
- name (badge name)
- description (what user needs to do)
- points (points awarded)
- icon (emoji or icon)
- criteria (criteria string)
- category (learning, achievement, social, special)
- type (lesson_completion, course_completion, etc.)
- created_at, updated_at
```

## Total Points by Category

- Lesson Badges: 85 points
- Topic Badges: 105 points
- Course Badges: 305 points
- Quiz Badges: 245 points
- Points Badges: 170 points
- Speed/Time Badges: 85 points
- Consistency Badges: 270 points
- Social Badges: 125 points
- Enrollment Badges: 90 points
- Special Badges: 250 points

**Total Maximum Points: 1,725 points**

## Usage in Frontend

Badges are displayed in:
- User profile page
- Leaderboard
- Achievement section
- User dashboard
- Badge showcase

## Future Enhancements

- Badge progression levels
- Badge trading/gifting
- Badge collections
- Seasonal badges
- Limited-time badges

