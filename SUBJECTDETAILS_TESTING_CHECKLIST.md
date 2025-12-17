# Subject Details Page - Testing Checklist

## Pre-Testing Setup

- [ ] Ensure database has test lessons with topics
- [ ] Ensure lessons have video URLs
- [ ] Ensure lessons have content/description
- [ ] Ensure lessons have attachments (PDFs)
- [ ] Ensure lessons have quizzes
- [ ] Ensure user is enrolled in course
- [ ] Clear browser cache
- [ ] Open browser console for error checking

## Functional Testing

### 1. Page Loading
- [ ] Page loads without errors
- [ ] Lesson ID parameter is recognized
- [ ] Loading states display correctly
- [ ] No console errors on load

### 2. Lesson Data Display
- [ ] Lesson title displays correctly
- [ ] Topic name displays in title
- [ ] Lesson content displays
- [ ] Lesson order shows correctly
- [ ] Total lessons in topic shows correctly

### 3. Video Display
- [ ] Video loads and displays
- [ ] Video player controls work (play, pause, volume)
- [ ] Video is responsive
- [ ] Loading state shows while fetching
- [ ] Fallback message shows if no video

### 4. Progress Bar
- [ ] Progress bar displays
- [ ] Progress percentage updates correctly
- [ ] Progress bar width matches percentage
- [ ] Progress updates after marking complete

### 5. Material & Links Tab
- [ ] Tab is active by default
- [ ] Lesson content displays
- [ ] Attachments load and display
- [ ] PDF buttons show correct file names
- [ ] PDF icons display correctly

### 6. PDF Viewer
- [ ] Click "View" opens PDF modal
- [ ] PDF displays in iframe
- [ ] File name shows in modal title
- [ ] Close button works
- [ ] Modal closes on background click
- [ ] PDF cannot be downloaded

### 7. Quiz Tab
- [ ] Tab switches correctly
- [ ] Quizzes load and display
- [ ] Quiz titles display
- [ ] Quiz descriptions display
- [ ] "Start Quiz" button works
- [ ] Message shows if no quizzes
- [ ] Multiple quizzes display correctly

### 8. Mark Lesson Complete
- [ ] Button is clickable initially
- [ ] Click triggers API call
- [ ] Button becomes disabled after click
- [ ] Button text changes to "Lesson Completed ✓"
- [ ] Button opacity changes
- [ ] Success notification shows
- [ ] Progress bar updates to 100%
- [ ] Button stays disabled on page reload

### 9. Navigation Buttons
- [ ] Previous button disabled if no previous lesson
- [ ] Next button disabled if no next lesson
- [ ] Previous button navigates correctly
- [ ] Next button navigates correctly
- [ ] Page reloads with new lesson data
- [ ] All content updates for new lesson

### 10. Tab Navigation
- [ ] Material tab switches correctly
- [ ] Quiz tab switches correctly
- [ ] AI Chat tab switches correctly
- [ ] Active tab styling applies
- [ ] Content visibility toggles correctly

### 11. Star Rating (Review Modal)
- [ ] Stars highlight on hover
- [ ] Stars unhighlight on mouseout
- [ ] Stars stay highlighted on click
- [ ] Rating text updates on click
- [ ] All 5 stars work correctly

### 12. Error Handling
- [ ] Error shows if no lesson ID
- [ ] Error shows if lesson not found
- [ ] Error shows if API fails
- [ ] Error messages are user-friendly
- [ ] Page doesn't crash on errors

### 13. Notifications
- [ ] Success notification shows on complete
- [ ] Error notifications display correctly
- [ ] Notifications auto-dismiss
- [ ] Notification styling is correct

## Responsive Testing

### Mobile (375px width)
- [ ] Layout is responsive
- [ ] Video scales correctly
- [ ] Buttons are clickable
- [ ] Tabs work on mobile
- [ ] PDF modal displays correctly
- [ ] No horizontal scrolling

### Tablet (768px width)
- [ ] Layout is responsive
- [ ] All elements visible
- [ ] Touch interactions work
- [ ] Modal displays correctly

### Desktop (1920px width)
- [ ] Layout is optimal
- [ ] All elements properly spaced
- [ ] Video displays at good size
- [ ] No layout issues

## Browser Testing

- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile Safari (iOS)
- [ ] Chrome Mobile (Android)

## Performance Testing

- [ ] Page loads in < 2 seconds
- [ ] API calls complete quickly
- [ ] No memory leaks
- [ ] Smooth scrolling
- [ ] No lag on interactions
- [ ] Video plays smoothly

## Accessibility Testing

- [ ] Keyboard navigation works
- [ ] Tab order is logical
- [ ] Buttons have proper labels
- [ ] Color contrast is sufficient
- [ ] Screen reader compatible
- [ ] ARIA labels present

## Edge Cases

- [ ] Lesson with no video
- [ ] Lesson with no content
- [ ] Lesson with no attachments
- [ ] Lesson with no quizzes
- [ ] First lesson (no previous)
- [ ] Last lesson (no next)
- [ ] Already completed lesson
- [ ] Invalid lesson ID
- [ ] User not enrolled

## API Integration

- [ ] All API calls use correct endpoints
- [ ] Request headers are correct
- [ ] Response handling is correct
- [ ] Error responses handled
- [ ] Pagination works if applicable
- [ ] Authentication token included

## Data Validation

- [ ] Lesson data is complete
- [ ] Video URL is valid
- [ ] Attachment paths are valid
- [ ] Quiz data is complete
- [ ] Progress data is accurate
- [ ] Navigation data is correct

## Sign-Off

- [ ] All tests passed
- [ ] No critical bugs
- [ ] No console errors
- [ ] Performance acceptable
- [ ] Ready for production

**Tested By:** ________________
**Date:** ________________
**Notes:** ________________

