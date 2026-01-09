# Testing Guide: Emoji, Audio, and Picture Messages

## Quick Start Testing

### 1. Emoji Picker Testing
**Steps:**
1. Open the chat interface
2. Click the emoji icon (😊) in the chat input area
3. Verify emoji picker appears below the input
4. Click any emoji (e.g., 😀)
5. Verify emoji is inserted into the message input
6. Click outside the picker to close it
7. Send the message with emoji

**Expected Results:**
- Emoji picker displays 20 common emojis
- Emojis scale up on hover
- Selected emoji appears in input field
- Message sends successfully with emoji

### 2. Audio Recording Testing
**Steps:**
1. Click the microphone icon (🎤) in the chat input area
2. Verify audio recording modal appears
3. Click "Start Recording" button
4. Speak into microphone for 5-10 seconds
5. Click "Stop Recording" button
6. Verify audio playback appears
7. Click play button to preview audio
8. Click "Send Audio" button
9. Verify message appears in chat with audio player

**Expected Results:**
- Modal appears with recording controls
- Recording duration displays in real-time (0:00 format)
- Audio playback control appears after recording stops
- Audio plays correctly
- Message displays with audio player control
- Users can play/pause/seek audio

**Browser Permissions:**
- Allow microphone access when prompted
- Check browser console for any errors

### 3. Picture Upload Testing
**Steps:**
1. Click the camera icon (📷) in the chat input area
2. Select an image from your device
3. Verify image preview modal appears
4. Review the image preview
5. Click "Send Image" button
6. Verify message appears in chat with image
7. Click image to view full size

**Expected Results:**
- File picker opens for image selection
- Preview modal shows selected image
- Image displays in chat message
- Image is clickable/viewable
- Metadata is stored (file name, size, MIME type)

### 4. Message Type Display Testing
**Steps:**
1. Send a text message
2. Send an emoji message
3. Send an audio message
4. Send an image message
5. Verify each displays correctly with appropriate styling

**Expected Results:**
- Text messages: Regular paragraph display
- Emoji messages: Text with emojis rendered
- Audio messages: HTML5 audio player with controls
- Image messages: Responsive image display

### 5. Mobile Responsiveness Testing
**Steps:**
1. Open chat on mobile device
2. Test emoji picker on mobile
3. Test audio recording on mobile
4. Test image upload on mobile
5. Verify all controls are accessible

**Expected Results:**
- Icons are visible and clickable
- Modals are properly sized for mobile
- Touch events work correctly
- No layout issues

## Troubleshooting

### Audio Recording Not Working
- Check browser console for errors
- Verify microphone permissions are granted
- Try a different browser
- Ensure HTTPS in production

### Image Not Uploading
- Check file size (max 50MB)
- Verify image format is supported
- Check storage permissions
- Review server logs

### Emoji Not Inserting
- Verify JavaScript is enabled
- Check browser console for errors
- Try refreshing the page

## Performance Testing

### File Size Limits
- Audio: Test with 5MB, 10MB, 50MB files
- Image: Test with various resolutions
- Verify 50MB limit is enforced

### Concurrent Messages
- Send multiple messages rapidly
- Verify all display correctly
- Check for race conditions

## Browser Testing Matrix

| Browser | Emoji | Audio | Image | Notes |
|---------|-------|-------|-------|-------|
| Chrome  | ✅    | ✅    | ✅    | Full support |
| Firefox | ✅    | ✅    | ✅    | Full support |
| Safari  | ✅    | ✅    | ✅    | Full support |
| Edge    | ✅    | ✅    | ✅    | Full support |

## Success Criteria
- [ ] All emoji operations work smoothly
- [ ] Audio recording and playback function correctly
- [ ] Image upload and display work as expected
- [ ] Messages display with correct styling
- [ ] No console errors
- [ ] Mobile responsive
- [ ] File size limits enforced
- [ ] Metadata stored correctly

