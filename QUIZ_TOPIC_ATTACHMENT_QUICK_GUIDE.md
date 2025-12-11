# Quiz Topic Attachment - Quick Guide

## Overview
Quizzes can now be attached to **both topics and lessons** in the Kokokah LMS curriculum.

## How to Use

### Creating a Topic Quiz

1. **Navigate to Course Edit Page**
   - Go to Admin Dashboard → Courses → Edit Course

2. **Expand a Topic**
   - Click on any topic to expand it

3. **Click "Add Quiz" Button**
   - Look for the question-circle icon (?) in the topic header
   - Click it to open the quiz creation modal

4. **Fill Quiz Details**
   - Enter question text
   - Select question type (Multiple Choice or Theory)
   - Add options (for multiple choice)
   - Enter correct answer
   - Assign marks
   - Click "Add Category" to save

5. **Verify**
   - Quiz appears in the topic section
   - Can be used for topic-level assessment

### Creating a Lesson Quiz

1. **Navigate to Course Edit Page**
   - Go to Admin Dashboard → Courses → Edit Course

2. **Expand a Topic**
   - Click on any topic to expand it

3. **Click "Add Quiz" on Lesson**
   - Look for the question-circle icon (?) next to lesson name
   - Click it to open the quiz creation modal

4. **Fill Quiz Details**
   - Same as topic quiz process
   - Click "Add Category" to save

5. **Verify**
   - Quiz appears under the lesson
   - Can be used for lesson-specific practice

## API Endpoints

### Topic Quizzes
```
GET    /api/topics/{topicId}/quizzes
POST   /api/topics/{topicId}/quizzes
```

### Lesson Quizzes
```
GET    /api/lessons/{lessonId}/quizzes
POST   /api/lessons/{lessonId}/quizzes
```

### General Quiz Operations
```
GET    /api/quizzes/{id}
PUT    /api/quizzes/{id}
DELETE /api/quizzes/{id}
POST   /api/quizzes/{id}/start
POST   /api/quizzes/{id}/submit
GET    /api/quizzes/{id}/results
GET    /api/quizzes/{id}/analytics
```

## JavaScript API

### QuizApiClient Methods

```javascript
// Get topic quizzes
await QuizApiClient.getQuizzesByTopic(topicId, filters);

// Create topic quiz
await QuizApiClient.createQuizForTopic(topicId, quizData);

// Get lesson quizzes
await QuizApiClient.getQuizzesByLesson(lessonId, filters);

// Create lesson quiz
await QuizApiClient.createQuizForLesson(lessonId, quizData);

// Get quiz details
await QuizApiClient.getQuiz(quizId);

// Update quiz
await QuizApiClient.updateQuiz(quizId, quizData);

// Delete quiz
await QuizApiClient.deleteQuiz(quizId);
```

## Database Schema

### Quizzes Table
```sql
- id (primary key)
- lesson_id (nullable, foreign key)
- topic_id (nullable, foreign key) ← NEW
- title
- slug (nullable, unique) ← NEW
- description
- type (mcq, theory)
- time_limit_minutes
- max_attempts
- passing_score
- shuffle_questions
- created_at
- updated_at
```

## Key Features

✅ **Dual Attachment**: Attach to topic OR lesson
✅ **Reference Slug**: Unique slug for linking back
✅ **Access Control**: Only instructors/admins can create
✅ **Validation**: Comprehensive form validation
✅ **Error Handling**: User-friendly error messages
✅ **Toast Notifications**: Success/error feedback

## Important Notes

- A quiz can be attached to EITHER a topic OR a lesson, not both
- The slug field is auto-generated and unique
- Only course instructors and admins can create quizzes
- Quizzes are deleted when topic/lesson is deleted (cascade)
- All existing lesson quizzes continue to work

## Troubleshooting

**Quiz not appearing?**
- Refresh the page
- Check browser console for errors
- Verify you have instructor/admin role

**Cannot create quiz?**
- Ensure you're logged in as instructor/admin
- Check form validation messages
- Verify all required fields are filled

**API errors?**
- Check network tab in browser dev tools
- Verify topic/lesson ID is correct
- Ensure authentication token is valid

