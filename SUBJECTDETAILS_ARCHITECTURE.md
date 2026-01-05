# Subject Details Page - Architecture Diagram

## System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Browser / Frontend                        │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌──────────────────────────────────────────────────────┐   │
│  │         subjectdetails.blade.php                     │   │
│  │  ┌────────────────────────────────────────────────┐  │   │
│  │  │ HTML Structure                                 │  │   │
│  │  │ - Lesson Title (dynamic)                       │  │   │
│  │  │ - Video Container (dynamic)                    │  │   │
│  │  │ - Progress Bar (dynamic)                       │  │   │
│  │  │ - Tabs (Material, Quiz, AI Chat)               │  │   │
│  │  │ - Navigation Buttons                           │  │   │
│  │  │ - PDF Viewer Modal                             │  │   │
│  │  └────────────────────────────────────────────────┘  │   │
│  │                                                        │   │
│  │  ┌────────────────────────────────────────────────┐  │   │
│  │  │ JavaScript Functions                           │  │   │
│  │  │ - loadLessonData()                             │  │   │
│  │  │ - loadLessonProgress()                         │  │   │
│  │  │ - loadAttachments()                            │  │   │
│  │  │ - loadQuizzes()                                │  │   │
│  │  │ - markLessonComplete()                         │  │   │
│  │  │ - navigatePrevious/Next()                      │  │   │
│  │  │ - viewPDF()                                    │  │   │
│  │  └────────────────────────────────────────────────┘  │   │
│  │                                                        │   │
│  │  ┌────────────────────────────────────────────────┐  │   │
│  │  │ API Clients                                    │  │   │
│  │  │ - LessonApiClient                              │  │   │
│  │  │ - BaseApiClient                                │  │   │
│  │  └────────────────────────────────────────────────┘  │   │
│  └──────────────────────────────────────────────────────┘   │
│                                                               │
└─────────────────────────────────────────────────────────────┘
                            ↓ (HTTP/HTTPS)
┌─────────────────────────────────────────────────────────────┐
│                    Backend / API Server                      │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌──────────────────────────────────────────────────────┐   │
│  │         LessonController                             │   │
│  │  - show($id)                                         │   │
│  │  - progress($id)                                     │   │
│  │  - attachments($id)                                  │   │
│  │  - complete($id)                                     │   │
│  └──────────────────────────────────────────────────────┘   │
│                                                               │
│  ┌──────────────────────────────────────────────────────┐   │
│  │         QuizController                               │   │
│  │  - indexByLesson($lessonId)                          │   │
│  │  - show($id)                                         │   │
│  │  - startAttempt($id)                                 │   │
│  └──────────────────────────────────────────────────────┘   │
│                                                               │
│  ┌──────────────────────────────────────────────────────┐   │
│  │         Models                                       │   │
│  │  - Lesson                                            │   │
│  │  - Topic                                             │   │
│  │  - Quiz                                              │   │
│  │  - LessonCompletion                                  │   │
│  │  - Question                                          │   │
│  └──────────────────────────────────────────────────────┘   │
│                                                               │
│  ┌──────────────────────────────────────────────────────┐   │
│  │         Database                                     │   │
│  │  - lessons table                                     │   │
│  │  - topics table                                      │   │
│  │  - quizzes table                                     │   │
│  │  - questions table                                   │   │
│  │  - lesson_completions table                          │   │
│  │  - files table                                       │   │
│  └──────────────────────────────────────────────────────┘   │
│                                                               │
└─────────────────────────────────────────────────────────────┘
```

## Data Flow Diagram

```
User Opens Page
    ↓
/subjectdetails?lesson_id=5
    ↓
Extract lesson_id from URL
    ↓
DOMContentLoaded Event
    ↓
┌─────────────────────────────────────────┐
│ Parallel API Calls                      │
├─────────────────────────────────────────┤
│ 1. GET /api/lessons/5                   │
│    ↓                                     │
│    updateLessonUI()                     │
│    - Set title                          │
│    - Display video                      │
│    - Show content                       │
│                                         │
│ 2. GET /api/lessons/5/progress          │
│    ↓                                     │
│    loadLessonProgress()                 │
│    - Update progress bar                │
│    - Show lesson count                  │
│    - Check completion status            │
│                                         │
│ 3. GET /api/lessons/5/attachments       │
│    ↓                                     │
│    loadAttachments()                    │
│    - Display PDF buttons                │
│                                         │
│ 4. GET /api/lessons/5/quizzes           │
│    ↓                                     │
│    displayQuizzes()                     │
│    - Show quiz list                     │
└─────────────────────────────────────────┘
    ↓
Page Ready for User Interaction
    ↓
User Actions:
├─ Click "View PDF" → viewPDF() → Open Modal
├─ Click "Start Quiz" → startQuiz() → POST /api/quizzes/{id}/start
├─ Click "Mark Complete" → markLessonComplete() → POST /api/lessons/{id}/complete
├─ Click "Previous" → navigateToPreviousLesson() → Reload page
├─ Click "Next" → navigateToNextLesson() → Reload page
└─ Click Tab → setupTabNavigation() → Switch content
```

## Component Interaction

```
┌─────────────────────────────────────────────────────────┐
│                  Page Components                        │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ Lesson Title Component                           │  │
│  │ ├─ Topic Name (from API)                         │  │
│  │ └─ Lesson Title (from API)                       │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ Progress Component                               │  │
│  │ ├─ Progress Bar (width = percentage)             │  │
│  │ └─ Progress Text (Lesson X of Y)                 │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ Video Component                                  │  │
│  │ ├─ HTML5 Video Player                           │  │
│  │ ├─ Controls (play, pause, volume)               │  │
│  │ └─ Source (from lesson.video_url)               │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ Tab Navigation Component                         │  │
│  │ ├─ Material & Links Tab                          │  │
│  │ ├─ Quiz Tab                                      │  │
│  │ └─ AI Chat Tab                                   │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ Material & Links Tab Content                     │  │
│  │ ├─ Lesson Content (from API)                     │  │
│  │ └─ Attachments (PDF buttons)                     │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ Quiz Tab Content                                 │  │
│  │ ├─ Quiz List (from API)                          │  │
│  │ └─ Start Quiz Buttons                            │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ Navigation Component                             │  │
│  │ ├─ Previous Lesson Button                        │  │
│  │ ├─ Mark Complete Button                          │  │
│  │ └─ Next Lesson Button                            │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ PDF Viewer Modal                                 │  │
│  │ ├─ Modal Header (file name)                      │  │
│  │ ├─ PDF Iframe                                    │  │
│  │ └─ Close Button                                  │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

## API Call Sequence

```
1. Page Load
   └─ DOMContentLoaded
      ├─ loadLessonData()
      │  └─ GET /api/lessons/{id}
      │     └─ updateLessonUI()
      │        ├─ Set title
      │        ├─ Display video
      │        └─ Show content
      │
      ├─ loadLessonProgress()
      │  └─ GET /api/lessons/{id}/progress
      │     └─ Update progress bar
      │
      ├─ loadAttachments()
      │  └─ GET /api/lessons/{id}/attachments
      │     └─ Display PDF buttons
      │
      └─ loadQuizzes()
         └─ GET /api/lessons/{id}/quizzes
            └─ Display quiz list

2. User Marks Complete
   └─ markLessonComplete()
      └─ POST /api/lessons/{id}/complete
         └─ Disable button
         └─ Update UI

3. User Starts Quiz
   └─ startQuiz(quizId)
      └─ POST /api/quizzes/{id}/start
         └─ Redirect to quiz page

4. User Navigates
   └─ navigateToPreviousLesson() or navigateToNextLesson()
      └─ Reload page with new lesson_id
         └─ Repeat from step 1
```

## State Management

```
Global State:
├─ currentLesson (object)
│  ├─ id
│  ├─ title
│  ├─ content
│  ├─ video_url
│  ├─ order
│  ├─ previous_lesson
│  └─ next_lesson
│
├─ currentTopic (object)
│  ├─ id
│  ├─ title
│  └─ lessons
│
├─ lessonId (number)
├─ totalLessonsInTopic (number)
└─ currentLessonOrder (number)
```

## Error Handling Flow

```
API Call
    ↓
Try Block
    ├─ Success Response
    │  └─ response.success === true
    │     └─ Update UI
    │
    └─ Error Response
       └─ response.success === false
          └─ showError(response.message)
             └─ Toast Notification
    
Catch Block
    └─ Exception
       └─ console.error()
       └─ showError('Error message')
          └─ Toast Notification
```

