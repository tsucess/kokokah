# Quiz Edit Feature - Code Reference

## Key Variables

### Editing State
```javascript
window.editingQuizId = null; // Tracks which quiz is being edited
```

---

## Key Functions

### 1. editQuiz(quizId)
**Location**: Lines 3252-3338

**Purpose**: Load quiz data and populate modal for editing

**Flow**:
1. Fetch quiz via `QuizApiClient.getQuiz(quizId)`
2. Set `window.editingQuizId = quizId`
3. Populate form fields:
   - Question text
   - Quiz type (mcq → multiple-choice, alternate → alternative-choice)
   - Options (based on type)
   - Correct answer
   - Points
4. Update modal title to "Edit Quiz"
5. Update button text to "Update Quiz"
6. Show modal

**Error Handling**: Shows error toast if API fails

---

### 2. saveQuiz()
**Location**: Lines 3130-3224

**Purpose**: Save quiz (create or update)

**Key Logic**:
```javascript
if (window.editingQuizId) {
    // Update existing quiz
    result = await QuizApiClient.updateQuiz(window.editingQuizId, quizData);
} else if (isLessonQuiz) {
    // Create new lesson quiz
    result = await QuizApiClient.createQuizForLesson(resourceId, quizData);
} else {
    // Create new topic quiz
    result = await QuizApiClient.createQuizForTopic(resourceId, quizData);
}
```

**After Save**:
- Show success message
- Reset form
- Reset `window.editingQuizId = null`
- Close modal
- Reload topics

---

### 3. openQuizModal(lessonId)
**Location**: Lines 3234-3260

**Purpose**: Open modal for creating new lesson quiz

**Resets**:
- `window.editingQuizId = null`
- Form fields
- Modal title to "Interactive Quiz"
- Button text to "Save Quiz"

---

### 4. openTopicQuizModal(topicId)
**Location**: Lines 3262-3284

**Purpose**: Open modal for creating new topic quiz

**Resets**: Same as openQuizModal()

---

## Modal Structure

### Quiz Modal ID
```html
<div class="modal fade" id="quiz-modal" ...>
```

### Form Elements
- Question input: `input[placeholder*="Identify"]`
- Quiz type select: `#quiz-choice`
- MCQ options: `#multiple-choice-container input[placeholder*="Option"]`
- Alternative options: `#alternative-choice-container input[placeholder*="Option"]`
- Correct answer: `input[placeholder="Correct Answer"]`
- Marks: `input[placeholder="Assign Mark"]`

---

## API Calls

### Get Quiz
```javascript
const result = await QuizApiClient.getQuiz(quizId);
// Returns: { success: true, data: { id, title, type, questions, ... } }
```

### Update Quiz
```javascript
const result = await QuizApiClient.updateQuiz(quizId, quizData);
// quizData: { title, type, questions, passing_score, shuffle_questions }
```

---

## Data Flow

### Quiz Data Structure
```javascript
{
    id: 1,
    title: "Question text",
    type: "mcq" | "alternate",
    questions: [{
        question_text: "Question text",
        type: "mcq" | "alternate",
        options: ["Option 1", "Option 2", ...],
        correct_answer: "Option 1",
        points: 5
    }],
    passing_score: 50,
    shuffle_questions: false
}
```

---

## Type Mapping

| Frontend | Backend |
|----------|---------|
| multiple-choice | mcq |
| alternative-choice | alternate |

---

## Status: ✅ COMPLETE

All code is implemented and ready for testing.

