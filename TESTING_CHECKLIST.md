# Testing Checklist: Camera & File Attachment

## Pre-Testing Setup

- [ ] Open browser developer tools (F12)
- [ ] Clear browser cache
- [ ] Ensure microphone/camera permissions are reset
- [ ] Test on multiple browsers
- [ ] Test on mobile device

## Camera Feature Testing

### Basic Functionality
- [ ] Click camera icon → Modal opens
- [ ] Modal displays "Start Camera" button
- [ ] Click "Start Camera" → Camera activates
- [ ] Live camera preview displays
- [ ] "Capture Photo" button appears
- [ ] Click "Capture Photo" → Photo captured
- [ ] Photo preview displays
- [ ] "Retake" button appears
- [ ] "Send Photo" button appears
- [ ] Click "Retake" → Camera resets
- [ ] Click "Send Photo" → Photo uploads
- [ ] Photo appears in chat as image message

### Error Handling
- [ ] Deny camera permission → Error message shows
- [ ] Close modal → Camera stream stops
- [ ] No camera device → Error message shows
- [ ] Network error → Error message shows

### Mobile Testing
- [ ] Works on mobile devices
- [ ] Camera orientation correct
- [ ] Touch controls responsive
- [ ] Modal fits screen

## File Attachment Testing

### Basic Functionality
- [ ] Click paperclip icon → File picker opens
- [ ] Select PDF file → Preview modal shows
- [ ] File name displays
- [ ] File size displays
- [ ] File type displays
- [ ] Click "Send File" → File uploads
- [ ] File appears in chat as file message
- [ ] Click "Cancel" → Modal closes

### File Type Testing
- [ ] PDF file works
- [ ] Word document works
- [ ] Excel spreadsheet works
- [ ] PowerPoint file works
- [ ] Text file works
- [ ] JPG image works
- [ ] PNG image works
- [ ] ZIP archive works
- [ ] RAR archive works

### File Size Testing
- [ ] Small file (< 1MB) works
- [ ] Medium file (5-10MB) works
- [ ] Large file (40-50MB) works
- [ ] File > 50MB rejected

### Error Handling
- [ ] Unsupported file type → Error message
- [ ] File too large → Error message
- [ ] Network error → Error message
- [ ] Cancel before sending → Works

## Audio Feature Testing (Unchanged)

- [ ] Click microphone icon → Modal opens
- [ ] Record audio → Works
- [ ] Audio preview → Works
- [ ] Send audio → Works
- [ ] Audio appears in chat

## Emoji Feature Testing (Unchanged)

- [ ] Click emoji icon → Picker opens
- [ ] Select emoji → Inserts to message
- [ ] Multiple emojis → Work
- [ ] Send message with emoji → Works

## Text Message Testing (Unchanged)

- [ ] Type text → Input works
- [ ] Send text → Works
- [ ] Text appears in chat

## Integration Testing

- [ ] Send camera photo → Displays correctly
- [ ] Send file → Displays correctly
- [ ] Send audio → Displays correctly
- [ ] Send emoji → Displays correctly
- [ ] Send text → Displays correctly
- [ ] Mix of message types → All display correctly
- [ ] Rapid message sending → No issues
- [ ] Page refresh → Messages persist

## UI/UX Testing

- [ ] Icons are visible
- [ ] Icons are clickable
- [ ] Modals are centered
- [ ] Buttons are responsive
- [ ] Text is readable
- [ ] Colors are appropriate
- [ ] No layout issues
- [ ] Responsive on mobile

## Performance Testing

- [ ] Camera modal opens quickly
- [ ] File preview displays quickly
- [ ] Photo capture is fast
- [ ] File upload is smooth
- [ ] No lag in UI
- [ ] No memory leaks
- [ ] Smooth animations

## Browser Compatibility

### Chrome
- [ ] Camera works
- [ ] Files work
- [ ] No console errors

### Firefox
- [ ] Camera works
- [ ] Files work
- [ ] No console errors

### Safari
- [ ] Camera works
- [ ] Files work
- [ ] No console errors

### Edge
- [ ] Camera works
- [ ] Files work
- [ ] No console errors

## Mobile Testing

### iOS
- [ ] Camera works
- [ ] Files work
- [ ] Touch controls responsive

### Android
- [ ] Camera works
- [ ] Files work
- [ ] Touch controls responsive

## Security Testing

- [ ] Unauthorized user cannot upload
- [ ] File size limits enforced
- [ ] File types validated
- [ ] No XSS vulnerabilities
- [ ] CSRF protection works

## Final Verification

- [ ] No console errors
- [ ] No network errors
- [ ] All features working
- [ ] Documentation accurate
- [ ] Ready for production

---

**Testing Date:** _______________
**Tester Name:** _______________
**Status:** _______________
**Notes:** _______________

