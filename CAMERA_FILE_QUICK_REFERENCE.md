# Quick Reference: Camera & File Attachment

## User Guide

### 📷 Camera (Take Pictures)
**Location:** Right side of chat input (next to emoji and audio icons)

**Steps:**
1. Click the **camera icon** 📷
2. Camera modal opens
3. Click **"Start Camera"** to activate your webcam
4. Click **"Capture Photo"** to take a picture
5. Review the captured photo
6. Click **"Retake"** to try again OR **"Send Photo"** to send
7. Photo appears in chat as an image message

**Requirements:**
- Webcam/camera device
- Browser permission to access camera
- HTTPS in production

### 📎 File Attachment (Attach Files)
**Location:** Inside message input box (left side, paperclip icon)

**Steps:**
1. Click the **paperclip icon** 📎
2. File picker opens
3. Select a file from your device
4. File preview modal shows:
   - File icon
   - File name
   - File size
   - File type
5. Click **"Send File"** to send OR **"Cancel"** to close
6. File appears in chat as a file message

**Supported Files:**
- **Documents:** PDF, Word (.doc, .docx), Excel (.xls, .xlsx), PowerPoint (.ppt, .pptx), Text (.txt)
- **Images:** JPG, PNG, GIF, WebP
- **Archives:** ZIP, RAR, 7Z

## Features Comparison

| Feature | Camera | File Attachment |
|---------|--------|-----------------|
| Icon | 📷 | 📎 |
| Location | Right side | Left side (input) |
| Message Type | image | file |
| Preview | Photo preview | File info preview |
| Retake Option | Yes | No |
| File Size Limit | 50MB | 50MB |
| Requires Permission | Camera access | None |

## Message Types in Chat

| Type | Source | Display |
|------|--------|---------|
| text | Text input | Plain text |
| image | Camera 📷 | Image viewer |
| audio | Microphone 🎤 | Audio player |
| file | Paperclip 📎 | File download link |
| emoji | Emoji picker 😊 | Text with emojis |

## Troubleshooting

### Camera Not Working
- Check browser permissions for camera access
- Try a different browser
- Ensure HTTPS in production
- Check browser console for errors

### File Not Uploading
- Check file size (max 50MB)
- Verify file type is supported
- Check internet connection
- Try a different file

### File Picker Not Opening
- Check browser console for errors
- Refresh the page
- Try a different browser

## Browser Support

| Browser | Camera | Files |
|---------|--------|-------|
| Chrome  | ✅     | ✅    |
| Firefox | ✅     | ✅    |
| Safari  | ✅     | ✅    |
| Edge    | ✅     | ✅    |

## File Size Limits

- **Camera Photos:** ~2-5MB (JPEG)
- **File Attachments:** Up to 50MB
- **Audio Recordings:** Up to 50MB

## Tips & Tricks

1. **Camera Quality:** Better lighting = better photos
2. **File Organization:** Use descriptive file names
3. **Batch Uploads:** Send files one at a time
4. **Mobile:** Works on mobile devices with cameras
5. **Retake:** Use retake to get the perfect shot

## Keyboard Shortcuts

- **Tab** - Navigate between input elements
- **Escape** - Close modals
- **Enter** - Send text message (if configured)

## Security Notes

✅ Files are validated on the server
✅ User authentication required
✅ File size limits enforced
✅ Unique file naming prevents conflicts
✅ CSRF protection enabled

## API Details

### Camera Photo Upload
```
POST /api/chatrooms/{id}/messages
Content-Type: multipart/form-data

- content: "Sent a picture"
- type: "image"
- file: [JPEG blob from camera]
```

### File Attachment Upload
```
POST /api/chatrooms/{id}/messages
Content-Type: multipart/form-data

- content: "Sent a file: filename.ext"
- type: "file"
- file: [File from picker]
```

## Storage Location

Files are stored in:
```
storage/app/public/chat-messages/
```

Accessible via:
```
https://domain.com/storage/chat-messages/[filename]
```

## Common Issues

**Q: Camera permission denied?**
A: Check browser settings → Privacy → Camera permissions

**Q: File too large?**
A: Maximum file size is 50MB. Compress or split the file.

**Q: Photo quality poor?**
A: Ensure good lighting and steady hand when capturing

**Q: File not showing in chat?**
A: Refresh the page or check browser console for errors

