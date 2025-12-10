# 🚀 Duration Auto-Extract - Quick Guide

**Feature:** Automatic duration generation from YouTube/Vimeo URLs  
**Status:** ✅ READY TO TEST  

---

## 📋 What Changed

### Duration Field Updates
- ✅ Added unique ID: `id="videoDurationInput"`
- ✅ Changed to `type="number"` for validation
- ✅ Added helper text explaining auto-extraction
- ✅ Made read-only initially (enabled on blur)

### New JavaScript Function
- ✅ `extractVideoDuration(url)` - Extracts video ID and duration
- ✅ Supports YouTube and Vimeo URLs
- ✅ Uses Vimeo oEmbed API for automatic extraction
- ✅ Provides manual entry fallback for YouTube

### Event Listeners
- ✅ Blur event on YouTube URL input
- ✅ Auto-triggers duration extraction
- ✅ Shows loading state and success/error messages

### Save Handler
- ✅ Reads duration from input field
- ✅ Validates duration is positive integer
- ✅ Sends `duration_minutes` to backend

---

## 🧪 Testing Steps

### Test 1: Vimeo URL (Auto-Extract)
1. Click "Add Lesson"
2. Select "Youtube Url" type
3. Paste Vimeo URL: `https://vimeo.com/123456789`
4. Click outside URL field
5. ✅ Duration should auto-populate
6. Click "Save Lesson"
7. ✅ Duration should be saved

### Test 2: YouTube URL (Manual Entry)
1. Click "Add Lesson"
2. Select "Youtube Url" type
3. Paste YouTube URL: `https://youtube.com/watch?v=dQw4w9WgXcQ`
4. Click outside URL field
5. ✅ Info message: "Please enter duration manually"
6. Enter duration manually: `5`
7. Click "Save Lesson"
8. ✅ Duration should be saved

### Test 3: Edit Lesson
1. Click edit on existing lesson
2. ✅ Duration field should show existing value
3. Modify duration if needed
4. Click "Save Lesson"
5. ✅ Updated duration should be saved

### Test 4: Invalid URL
1. Click "Add Lesson"
2. Select "Youtube Url" type
3. Enter invalid URL: `https://example.com`
4. Click outside URL field
5. ✅ Error message should appear
6. Enter duration manually
7. Click "Save Lesson"
8. ✅ Should save successfully

---

## 🎯 Expected Behavior

### Vimeo URLs
```
Input: https://vimeo.com/123456789
↓
Duration extracted from Vimeo API
↓
Field populated: 15 (minutes)
↓
Toast: "Duration extracted: 15 minutes"
```

### YouTube URLs
```
Input: https://youtube.com/watch?v=dQw4w9WgXcQ
↓
Video ID extracted
↓
Info message: "Please enter duration manually"
↓
User enters: 5
↓
Field populated: 5 (minutes)
```

### Invalid URLs
```
Input: https://example.com
↓
No video ID found
↓
Info message: "Please enter duration manually"
↓
User enters: 10
↓
Field populated: 10 (minutes)
```

---

## 📊 Database

**Column:** `duration_minutes`  
**Table:** `lessons`  
**Type:** Integer (nullable)  
**Example:** `15` (minutes)  

---

## 🔧 Technical Details

### Supported URL Formats

**YouTube:**
- `https://www.youtube.com/watch?v=VIDEO_ID`
- `https://youtu.be/VIDEO_ID`
- `https://www.youtube.com/embed/VIDEO_ID`

**Vimeo:**
- `https://vimeo.com/VIDEO_ID`
- `https://player.vimeo.com/video/VIDEO_ID`

### API Used
- **Vimeo oEmbed API:** `https://vimeo.com/api/oembed.json?url=...`
- **YouTube:** Manual entry (API requires authentication)

---

## ✨ Features

✅ Auto-extract Vimeo duration  
✅ Manual entry for YouTube  
✅ Validation before save  
✅ Edit existing lessons  
✅ Toast notifications  
✅ Error handling  
✅ User guidance  

---

## 🚀 Ready to Test!

All changes implemented. Test the feature and report any issues!


