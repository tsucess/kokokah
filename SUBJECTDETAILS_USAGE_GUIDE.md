# Subject Details Page - Usage Guide

## How to Access the Page

### URL Format
```
/subjectdetails?lesson_id={lessonId}
```

### Example
```
/subjectdetails?lesson_id=5
```

## Page Components

### 1. Header Section
- **Lesson Title**: Shows "Topic Name: Lesson Title"
- **Progress Indicator**: Shows "Lesson X of Y"
- **Progress Bar**: Visual representation of completion percentage

### 2. Video Section
- Displays lesson video with HTML5 player
- Supports standard video controls
- Shows loading state while fetching

### 3. Tabs
Three tabs for different content types:

#### Material & Links Tab (Default)
- Displays lesson content/description
- Shows PDF attachments
- Click "View" to open PDF in modal
- PDFs cannot be downloaded (view-only)

#### Quiz Tab
- Lists all quizzes for the lesson
- Shows quiz title and description
- Click "Start Quiz" to begin quiz
- Shows message if no quizzes available

#### AI Chat Tab
- Message input for AI assistance
- Emoji picker
- Send button

### 4. Navigation Buttons
- **Previous Lesson**: Navigate to previous lesson in topic
- **Mark Lesson Complete**: Mark current lesson as complete
- **Next Lesson**: Navigate to next lesson in topic

## Features

### Mark Lesson Complete
1. Click "Mark Lesson Complete" button
2. Button becomes disabled
3. Shows "Lesson Completed ✓"
4. Progress bar updates to 100%
5. Success notification appears

### View PDF Attachments
1. Go to "Material & Links" tab
2. Click "View" button on PDF attachment
3. PDF opens in modal viewer
4. Close modal to return to lesson

### Start Quiz
1. Go to "Quiz" tab
2. Click "Start Quiz" button
3. Redirected to quiz page
4. Complete quiz and submit

### Navigate Lessons
1. Click "Previous Lesson" to go back
2. Click "Next Lesson" to go forward
3. Page reloads with new lesson data
4. All content updates automatically

## Data Displayed

### From Lesson Model
- `title` - Lesson title
- `content` - Lesson description/content
- `video_url` - Video file URL
- `order` - Lesson order in topic
- `duration_minutes` - Lesson duration

### From Topic Model
- `title` - Topic name
- `lessons` - All lessons in topic

### From Progress
- `is_completed` - Completion status
- `progress_percentage` - Progress percentage
- `completed_at` - Completion timestamp

### From Attachments
- `file_name` - Attachment name
- `file_path` - File URL for viewing

### From Quizzes
- `title` - Quiz title
- `description` - Quiz description
- `id` - Quiz ID for starting

## Error Handling

### Common Errors
- **No lesson ID**: Shows error message
- **Lesson not found**: Shows 404 error
- **No quizzes**: Shows "No quizzes available" message
- **No attachments**: Attachments section hidden
- **API errors**: Shows error toast notification

### User Feedback
- Success messages for completed actions
- Error messages for failed operations
- Loading states while fetching data
- Disabled buttons when appropriate

## Browser Compatibility
- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Requires JavaScript enabled
- Requires Bootstrap 5

## Performance Notes
- Lazy loads attachments and quizzes
- Caches lesson data in memory
- Minimal API calls
- Responsive design for mobile

## Troubleshooting

### Page not loading
- Check lesson_id parameter in URL
- Verify user is enrolled in course
- Check browser console for errors

### Video not playing
- Verify video_url is valid
- Check video format (MP4 recommended)
- Check browser video support

### PDF not displaying
- Verify attachment file exists
- Check file permissions
- Try different PDF viewer

### Quiz not starting
- Verify quiz exists for lesson
- Check user enrollment status
- Check quiz access permissions

