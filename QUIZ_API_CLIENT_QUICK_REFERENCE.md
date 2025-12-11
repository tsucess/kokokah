# 🎯 QuizApiClient - Quick Reference Guide

## Import
```javascript
import QuizApiClient from '{{ asset('js/api/quizApiClient.js') }}';
```

---

## Methods

### 1. Get Quizzes for a Lesson
```javascript
const result = await QuizApiClient.getQuizzesByLesson(lessonId);
// Returns: { success: true, data: [...quizzes] }
```

### 2. Create a Quiz
```javascript
const quizData = {
  title: "Quiz Title",
  type: "mcq",  // or "theory"
  questions: [{
    question_text: "Question?",
    type: "mcq",
    options: ["A", "B", "C"],
    correct_answer: "A",
    points: 5
  }],
  passing_score: 50,
  shuffle_questions: false
};

const result = await QuizApiClient.createQuiz(lessonId, quizData);
```

### 3. Get Single Quiz
```javascript
const result = await QuizApiClient.getQuiz(quizId);
// Returns: { success: true, data: {...quiz details} }
```

### 4. Update Quiz
```javascript
const result = await QuizApiClient.updateQuiz(quizId, {
  title: "Updated Title",
  passing_score: 60
});
```

### 5. Delete Quiz
```javascript
const result = await QuizApiClient.deleteQuiz(quizId);
```

### 6. Start Quiz Attempt
```javascript
const result = await QuizApiClient.startQuiz(quizId);
// Returns: { success: true, data: { quiz, questions, attempt_number } }
```

### 7. Submit Quiz Answers
```javascript
const answers = [
  { question_id: 1, answer: "Option A" },
  { question_id: 2, answer: "Option B" }
];

const result = await QuizApiClient.submitQuiz(quizId, answers);
// Returns: { success: true, data: { score, percentage, passed } }
```

### 8. Get Quiz Results
```javascript
const result = await QuizApiClient.getQuizResults(quizId);
// Returns: { success: true, data: { score, attempts, results } }
```

### 9. Get Quiz Analytics (Admin/Instructor)
```javascript
const result = await QuizApiClient.getQuizAnalytics(quizId);
// Returns: { success: true, data: { stats, performance } }
```

---

## Error Handling

```javascript
try {
  const result = await QuizApiClient.createQuiz(lessonId, quizData);
  if (result.success) {
    console.log('Quiz created:', result.data);
  } else {
    console.error('Error:', result.message);
  }
} catch (error) {
  console.error('API Error:', error);
}
```

---

## Common Patterns

### Create and Display Quiz
```javascript
const quizData = { /* ... */ };
const result = await QuizApiClient.createQuiz(lessonId, quizData);
if (result.success) {
  ToastNotification.success('Success', 'Quiz created');
  // Reload quizzes
  const quizzes = await QuizApiClient.getQuizzesByLesson(lessonId);
}
```

### Take a Quiz
```javascript
// Start quiz
const start = await QuizApiClient.startQuiz(quizId);
const questions = start.data.questions;

// Submit answers
const answers = questions.map(q => ({
  question_id: q.id,
  answer: userAnswers[q.id]
}));
const result = await QuizApiClient.submitQuiz(quizId, answers);
```

### Get Quiz Results
```javascript
const results = await QuizApiClient.getQuizResults(quizId);
console.log(`Score: ${results.data.score}/${results.data.total}`);
```

---

## Response Format

All methods return:
```javascript
{
  success: true/false,
  data: { /* response data */ },
  message: "Success or error message"
}
```

---

## API Endpoints

| Method | Endpoint | Auth |
|--------|----------|------|
| GET | `/lessons/{lessonId}/quizzes` | ✅ |
| POST | `/lessons/{lessonId}/quizzes` | ✅ |
| GET | `/quizzes/{id}` | ✅ |
| PUT | `/quizzes/{id}` | ✅ |
| DELETE | `/quizzes/{id}` | ✅ |
| POST | `/quizzes/{id}/start` | ✅ |
| POST | `/quizzes/{id}/submit` | ✅ |
| GET | `/quizzes/{id}/results` | ✅ |
| GET | `/quizzes/{id}/analytics` | ✅ |

---

## Notes

- All methods require authentication (Bearer token)
- Questions can be MCQ or Theory type
- Options array required for MCQ questions
- Correct answer must match one of the options
- Points must be positive integer
- Passing score is percentage (0-100)

