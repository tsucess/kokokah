# Quick Reference: Emoji, Audio, Picture Messages

## User Guide

### How to Send Emoji Messages
1. Click the **😊 Emoji Icon** in the chat input
2. Select an emoji from the picker
3. Type additional text if desired
4. Click **Send**

**Available Emojis:** 😀 😂 😍 🥰 😎 🤔 👍 👏 🎉 🔥 💯 ✨ 😢 😡 🤷 👋 💪 🙏 ❤️ 💔

### How to Send Audio Messages
1. Click the **🎤 Microphone Icon**
2. Click **Start Recording**
3. Speak your message
4. Click **Stop Recording**
5. Preview audio (optional)
6. Click **Send Audio**

**Supported Format:** WebM audio
**Max Duration:** Unlimited (limited by file size)
**Max File Size:** 50MB

### How to Send Picture Messages
1. Click the **📷 Camera Icon**
2. Select an image from your device
3. Review the preview
4. Click **Send Image**

**Supported Formats:** JPG, PNG, GIF, WebP, BMP, SVG
**Max File Size:** 50MB
**Max Resolution:** Unlimited

## Technical Details

### Message Types
```
text   - Regular text messages
image  - Picture/image messages
audio  - Audio recording messages
file   - File attachments
system - System notifications
```

### File Storage
- **Location:** `storage/app/public/chat-messages/`
- **Naming:** Auto-generated unique names
- **Access:** Public URL via `/storage/` path

### Metadata Stored
```json
{
  "file_name": "original_filename.ext",
  "file_path": "chat-messages/unique_name.ext",
  "file_url": "https://domain.com/storage/...",
  "file_size": 1024000,
  "mime_type": "image/jpeg"
}
```

## API Reference

### Send Message with File
```bash
curl -X POST /api/chatrooms/{id}/messages \
  -H "Authorization: Bearer {token}" \
  -F "content=Check this out!" \
  -F "type=image" \
  -F "file=@image.jpg"
```

### Response
```json
{
  "success": true,
  "message": "Message sent successfully",
  "data": {
    "id": 123,
    "type": "image",
    "content": "Check this out!",
    "metadata": {
      "file_name": "image.jpg",
      "file_url": "https://...",
      "file_size": 102400,
      "mime_type": "image/jpeg"
    }
  }
}
```

## Keyboard Shortcuts
- **Tab** - Navigate between input elements
- **Enter** - Send message (if configured)
- **Escape** - Close modals

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Emoji not inserting | Refresh page, check JS enabled |
| Audio not recording | Check microphone permissions |
| Image not uploading | Check file size < 50MB |
| Modal not closing | Click overlay or close button |
| File not displaying | Check storage permissions |

## Browser Requirements

| Feature | Requirement |
|---------|-------------|
| Emoji Picker | Modern browser (ES6+) |
| Audio Recording | Chrome, Firefox, Safari, Edge |
| Image Upload | All modern browsers |
| File Storage | Public disk access |

## Performance Tips

1. **Emoji Picker:** Loads instantly with 20 emojis
2. **Audio:** Use shorter recordings for faster upload
3. **Images:** Compress before uploading for speed
4. **Files:** Monitor storage usage regularly

## Security Notes

✅ File size validated (50MB max)
✅ User authorization required
✅ File type validation
✅ Unique file naming prevents conflicts
✅ CSRF protection enabled

## File Size Limits

| Type | Max Size |
|------|----------|
| Audio | 50MB |
| Image | 50MB |
| File | 50MB |
| Text | 5000 chars |

## Supported Audio Formats
- WebM (primary)
- MP3 (via file upload)
- WAV (via file upload)
- OGG (via file upload)
- M4A (via file upload)

## Supported Image Formats
- JPG/JPEG
- PNG
- GIF
- WebP
- BMP
- SVG

## Common Issues & Solutions

**Q: Audio recording not working?**
A: Check microphone permissions in browser settings

**Q: Image too large?**
A: Compress image before uploading (max 50MB)

**Q: Emoji not showing?**
A: Clear browser cache and refresh

**Q: File not downloading?**
A: Check storage permissions and file path

## Support
For issues, check browser console (F12) for error messages.

