# Subject Details Page - Code Structure

## File: resources/views/users/subjectdetails.blade.php

### Structure Overview

```
@extends('layouts.usertemplate')
@section('content')
    ├── PHP: Extract lesson_id from URL
    ├── STYLE: CSS for all components
    ├── MAIN CONTENT
    │   ├── PDF Viewer Modal
    │   ├── Review Modal
    │   └── Main Section
    │       ├── Lesson Title (dynamic)
    │       ├── Progress Box (dynamic)
    │       ├── Video Container (dynamic)
    │       ├── Tab Navigation
    │       │   ├── Material & Links Tab
    │       │   ├── Quiz Tab
    │       │   └── AI Chat Tab
    │       └── Navigation Buttons
    └── SCRIPT
        ├── API Client Imports
        ├── Global Variables
        ├── DOMContentLoaded Event
        ├── Data Loading Functions
        ├── UI Update Functions
        ├── User Action Functions
        ├── Utility Functions
        └── Notification Functions
```

## Key Sections

### 1. PHP Section (Lines 3-6)
```php
@php
    $lessonId = request()->route('lessonId') ?? request()->query('lesson_id');
@endphp
```
- Extracts lesson ID from route or query parameter
- Passes to JavaScript for API calls

### 2. Styles (Lines 8-176)
- Box styling
- Progress bar styling
- Video box styling
- Button styling
- Modal styling
- Star rating styling

### 3. PDF Viewer Modal (Lines 183-197)
```html
<div class="modal fade" id="pdfViewerModal">
    <iframe id="pdfFrame"></iframe>
</div>
```
- Modal for viewing PDFs
- Uses iframe for display
- No download capability

### 4. Main Content Section (Lines 237-318)
```html
<section class="container-fluid">
    <h1 id="lessonTitle">Loading...</h1>
    <div id="videoContainer"></div>
    <ul class="nav nav-underline">
        <li><a data-tab="material">Material & Links</a></li>
        <li><a data-tab="quiz">Quiz</a></li>
        <li><a data-tab="ai-chat">AI Chat</a></li>
    </ul>
    <div id="material" class="tab-content-section">
        <p id="lessonContent"></p>
        <div id="attachmentsContainer"></div>
    </div>
    <div id="quiz" class="tab-content-section d-none">
        <div id="quizContainer"></div>
    </div>
    <div id="ai-chat" class="tab-content-section d-none">
        <!-- AI Chat UI -->
    </div>
    <div class="d-flex justify-content-between">
        <button id="prevBtn" onclick="navigateToPreviousLesson()">
        <button id="markCompleteBtn" onclick="markLessonComplete()">
        <button id="nextBtn" onclick="navigateToNextLesson()">
    </div>
</section>
```

### 5. JavaScript Section (Lines 322-648)

#### Imports (Lines 322-323)
```javascript
<script src="{{ asset('js/api/baseApiClient.js') }}"></script>
<script src="{{ asset('js/api/lessonApiClient.js') }}"></script>
```

#### Global Variables (Lines 325-330)
```javascript
let currentLesson = null;
let currentTopic = null;
let lessonId = {{ $lessonId ?? 'null' }};
let totalLessonsInTopic = 0;
let currentLessonOrder = 0;
```

#### Initialization (Lines 333-348)
```javascript
document.addEventListener('DOMContentLoaded', async () => {
    // Get lesson ID
    // Load lesson data
    // Load quizzes
    // Setup UI
});
```

#### Data Loading Functions (Lines 350-450)
- `loadLessonData()` - Fetch lesson from API
- `updateLessonUI()` - Update UI elements
- `loadLessonProgress()` - Fetch progress
- `loadAttachments()` - Fetch attachments
- `loadQuizzes()` - Fetch quizzes

#### User Action Functions (Lines 452-550)
- `viewPDF()` - Open PDF modal
- `startQuiz()` - Start quiz
- `markLessonComplete()` - Mark complete
- `navigateToPreviousLesson()` - Previous lesson
- `navigateToNextLesson()` - Next lesson

#### Setup Functions (Lines 552-631)
- `setupTabNavigation()` - Tab switching
- `setupStarRating()` - Star rating

#### Utility Functions (Lines 633-647)
- `showError()` - Error toast
- `showSuccess()` - Success toast

## Dynamic Elements

### Elements Updated by JavaScript

| Element ID | Updated By | Content |
|-----------|-----------|---------|
| `lessonTitle` | `updateLessonUI()` | Lesson title with topic |
| `lessonProgress` | `loadLessonProgress()` | "Lesson X of Y" |
| `progressTrack` | `loadLessonProgress()` | Progress bar width |
| `videoContainer` | `updateLessonUI()` | Video player |
| `lessonContent` | `updateLessonUI()` | Lesson description |
| `attachmentsContainer` | `loadAttachments()` | PDF buttons |
| `quizContainer` | `displayQuizzes()` | Quiz list |
| `markCompleteBtn` | `loadLessonProgress()` | Button state |

## API Integration

### LessonApiClient Methods Used

```javascript
lessonApiClient.getLesson(lessonId)
lessonApiClient.getLessonProgress(lessonId)
lessonApiClient.getAttachments(lessonId)
lessonApiClient.getQuizzesByLesson(lessonId)
lessonApiClient.markLessonComplete(lessonId)
lessonApiClient.startQuizAttempt(quizId)
```

## Error Handling

All functions wrapped in try-catch:
```javascript
try {
    // API call
    if (response.success) {
        // Update UI
    } else {
        showError(response.message);
    }
} catch (error) {
    console.error('Error:', error);
    showError('Error message');
}
```

## Performance Optimizations

1. Lazy loading of attachments and quizzes
2. Single API call per data type
3. Minimal DOM manipulation
4. Event delegation for tabs
5. Cached lesson data in memory

## Browser Compatibility

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Requires ES6+ support
- Requires Fetch API
- Requires Bootstrap 5

