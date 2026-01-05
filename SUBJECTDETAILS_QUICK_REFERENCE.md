# Subject Details Page - Quick Reference

## 🚀 Quick Start

### Access the Page
```
/subjectdetails?lesson_id=5
```

### What It Does
- Displays lesson content with video
- Shows lesson progress
- Displays attachments (PDFs)
- Shows quizzes for lesson
- Allows marking lesson complete
- Enables navigation between lessons

## 📋 Key Features

| Feature | Status | Details |
|---------|--------|---------|
| Dynamic Lesson Loading | ✅ | Fetches from `/api/lessons/{id}` |
| Video Display | ✅ | HTML5 player with controls |
| Material & Links | ✅ | Shows content and attachments |
| PDF Viewer | ✅ | Modal viewer, no download |
| Quiz Display | ✅ | Lists quizzes, start button |
| Mark Complete | ✅ | Disables button, updates progress |
| Navigation | ✅ | Previous/Next lesson buttons |
| Progress Tracking | ✅ | Shows lesson progress |
| Error Handling | ✅ | User-friendly messages |
| Responsive Design | ✅ | Mobile, tablet, desktop |

## 🔌 API Endpoints

```javascript
// Get lesson details
GET /api/lessons/{id}

// Get lesson progress
GET /api/lessons/{id}/progress

// Get lesson quizzes
GET /api/lessons/{id}/quizzes

// Get lesson attachments
GET /api/lessons/{id}/attachments

// Mark lesson complete
POST /api/lessons/{id}/complete

// Start quiz
POST /api/quizzes/{id}/start
```

## 📝 Response Examples

### Lesson Response
```json
{
  "success": true,
  "data": {
    "id": 5,
    "title": "Part of Speech",
    "content": "Lesson content here...",
    "video_url": "https://example.com/video.mp4",
    "order": 2,
    "topic": {
      "id": 13,
      "title": "Topic 13",
      "lessons": [...]
    },
    "previous_lesson": {"id": 4, "title": "Previous"},
    "next_lesson": {"id": 6, "title": "Next"}
  }
}
```

### Progress Response
```json
{
  "success": true,
  "data": {
    "lesson_id": 5,
    "is_completed": false,
    "progress_percentage": 50,
    "completed_at": null,
    "time_spent": 300
  }
}
```

### Quizzes Response
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Quiz 1",
      "description": "Test your knowledge",
      "time_limit_minutes": 30
    }
  ]
}
```

## 🎯 JavaScript Functions

### Load Data
```javascript
loadLessonData()           // Load lesson from API
loadLessonProgress()       // Load progress
loadAttachments()          // Load attachments
loadQuizzes()              // Load quizzes
```

### Update UI
```javascript
updateLessonUI()           // Update all lesson UI
displayQuizzes(quizzes)    // Display quiz list
viewPDF(path, name)        // Open PDF modal
```

### User Actions
```javascript
markLessonComplete()       // Mark as complete
navigateToPreviousLesson() // Go to previous
navigateToNextLesson()     // Go to next
startQuiz(quizId)          // Start quiz
```

### Utilities
```javascript
setupTabNavigation()       // Setup tabs
setupStarRating()          // Setup rating
showError(msg)             // Show error
showSuccess(msg)           // Show success
```

## 🎨 CSS Classes

| Class | Purpose |
|-------|---------|
| `.box` | Container box |
| `.box-title` | Box title text |
| `.box-progress-bar` | Progress bar container |
| `.progress-track` | Progress bar fill |
| `.video-box` | Video container |
| `.lecture-box` | Content box |
| `.lecture-download-btn` | Attachment button |
| `.mark-complete-btn` | Complete button |
| `.nav-btn` | Navigation button |
| `.tab-content-section` | Tab content |
| `.d-none` | Hide element |

## 🔧 Global Variables

```javascript
currentLesson           // Current lesson data
currentTopic            // Current topic data
lessonId                // Current lesson ID
totalLessonsInTopic     // Total lessons
currentLessonOrder      // Current lesson order
```

## 📱 Element IDs

| ID | Purpose |
|----|---------|
| `lessonTitle` | Lesson title |
| `lessonProgress` | Progress text |
| `progressTrack` | Progress bar |
| `videoContainer` | Video player |
| `lessonContent` | Lesson content |
| `attachmentsContainer` | Attachments list |
| `quizContainer` | Quizzes list |
| `markCompleteBtn` | Complete button |
| `prevBtn` | Previous button |
| `nextBtn` | Next button |
| `pdfViewerModal` | PDF modal |
| `pdfFrame` | PDF iframe |

## 🐛 Debugging

### Check Console
```javascript
console.log(currentLesson)    // View lesson data
console.log(currentTopic)     // View topic data
```

### Test API Call
```javascript
await lessonApiClient.getLesson(5)
```

### Check Element
```javascript
document.getElementById('lessonTitle').textContent
```

## ⚠️ Common Issues

| Issue | Solution |
|-------|----------|
| Page not loading | Check lesson_id parameter |
| Video not playing | Verify video_url is valid |
| PDF not displaying | Check file permissions |
| Quiz not showing | Verify quiz exists for lesson |
| Button not working | Check browser console |
| API error | Verify user is enrolled |

## 📚 Related Files

- `public/js/api/lessonApiClient.js` - API client
- `public/js/api/baseApiClient.js` - Base client
- `app/Http/Controllers/LessonController.php` - Backend
- `app/Models/Lesson.php` - Lesson model
- `app/Models/Quiz.php` - Quiz model

## 🔐 Authentication

- All API calls require authentication token
- Token sent in Authorization header
- Handled by BaseApiClient automatically

## 📊 Data Flow

```
URL: /subjectdetails?lesson_id=5
  ↓
Extract lesson_id
  ↓
Load Lesson Data (API)
  ↓
Update UI
  ↓
Load Progress (API)
  ↓
Load Attachments (API)
  ↓
Load Quizzes (API)
  ↓
Display Complete Page
```

## 🎓 Learning Path

1. User clicks lesson link
2. Page loads with lesson_id
3. Lesson data fetched from API
4. UI updated with lesson info
5. User views content, video, attachments
6. User can take quiz
7. User marks lesson complete
8. User navigates to next lesson

## 📞 Support

For issues or questions:
1. Check browser console for errors
2. Verify API endpoints are working
3. Check user enrollment status
4. Review API response data
5. Check network tab in DevTools

