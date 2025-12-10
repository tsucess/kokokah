# 🧪 YouTube Duration Extraction - Testing Guide

**Feature:** Automatic YouTube duration extraction  
**Status:** ✅ READY TO TEST  
**Date:** December 9, 2025  

---

## 📋 Test Cases

### Test 1: YouTube Short URL Format
**URL:** `https://youtu.be/cU3Wtr8RESA`

**Steps:**
1. Navigate to course editing page
2. Click "Add Lesson" button
3. Select "Youtube Url" from lesson type dropdown
4. Paste URL: `https://youtu.be/cU3Wtr8RESA`
5. Click outside the URL field (blur event)
6. Wait 2-3 seconds for extraction

**Expected Result:**
- ✅ "Extracting duration..." message appears
- ✅ Duration field is disabled temporarily
- ✅ Duration auto-populates (e.g., "10" minutes)
- ✅ Success toast: "Duration extracted: 10 minutes"
- ✅ Duration field is re-enabled

**Console Output:**
```
YouTube video detected. Duration field is ready for manual input or API integration.
YouTube oEmbed data: {...}
YouTube noembed data: {...}
```

---

### Test 2: YouTube Full URL Format
**URL:** `https://www.youtube.com/watch?v=cU3Wtr8RESA`

**Steps:**
1. Navigate to course editing page
2. Click "Add Lesson" button
3. Select "Youtube Url" from lesson type dropdown
4. Paste URL: `https://www.youtube.com/watch?v=cU3Wtr8RESA`
5. Click outside the URL field (blur event)
6. Wait 2-3 seconds for extraction

**Expected Result:**
- ✅ "Extracting duration..." message appears
- ✅ Duration field is disabled temporarily
- ✅ Duration auto-populates (e.g., "10" minutes)
- ✅ Success toast: "Duration extracted: 10 minutes"
- ✅ Duration field is re-enabled

---

### Test 3: YouTube Embed URL Format
**URL:** `https://www.youtube.com/embed/cU3Wtr8RESA`

**Steps:**
1. Navigate to course editing page
2. Click "Add Lesson" button
3. Select "Youtube Url" from lesson type dropdown
4. Paste URL: `https://www.youtube.com/embed/cU3Wtr8RESA`
5. Click outside the URL field (blur event)
6. Wait 2-3 seconds for extraction

**Expected Result:**
- ✅ "Extracting duration..." message appears
- ✅ Duration field is disabled temporarily
- ✅ Duration auto-populates (e.g., "10" minutes)
- ✅ Success toast: "Duration extracted: 10 minutes"
- ✅ Duration field is re-enabled

---

### Test 4: Save Lesson with Auto-Extracted Duration
**Steps:**
1. Complete Test 1, 2, or 3
2. Duration field should be populated
3. Enter lesson title: "Test Lesson"
4. Click "Save Lesson" button
5. Wait for success message

**Expected Result:**
- ✅ Lesson saved successfully
- ✅ Toast: "Lesson created successfully"
- ✅ Modal closes
- ✅ Lesson appears in topic list
- ✅ Duration saved to database

---

### Test 5: Edit Lesson with Duration
**Steps:**
1. Create a lesson with YouTube URL (Test 4)
2. Click edit icon on the lesson
3. Modal opens with existing data

**Expected Result:**
- ✅ URL field populated with video URL
- ✅ Duration field populated with saved duration
- ✅ Can modify duration if needed
- ✅ Can save changes

---

### Test 6: Invalid URL Handling
**URL:** `https://example.com/invalid`

**Steps:**
1. Navigate to course editing page
2. Click "Add Lesson" button
3. Select "Youtube Url" from lesson type dropdown
4. Paste invalid URL: `https://example.com/invalid`
5. Click outside the URL field (blur event)
6. Wait 2-3 seconds

**Expected Result:**
- ✅ "Extracting duration..." message appears
- ✅ Info toast: "Please enter the video duration manually"
- ✅ Duration field is enabled for manual entry
- ✅ Placeholder: "Enter duration manually (in minutes)"
- ✅ User can enter duration manually

---

### Test 7: Manual Duration Entry
**Steps:**
1. Complete Test 6 (invalid URL)
2. Enter duration manually: "15"
3. Enter lesson title: "Test Lesson"
4. Click "Save Lesson" button

**Expected Result:**
- ✅ Lesson saved successfully
- ✅ Duration "15" saved to database
- ✅ Modal closes
- ✅ Lesson appears in topic list

---

### Test 8: Vimeo URL (Should Still Work)
**URL:** `https://vimeo.com/123456789`

**Steps:**
1. Navigate to course editing page
2. Click "Add Lesson" button
3. Select "Youtube Url" from lesson type dropdown
4. Paste Vimeo URL
5. Click outside the URL field (blur event)
6. Wait 2-3 seconds

**Expected Result:**
- ✅ "Extracting duration..." message appears
- ✅ Duration auto-populates from Vimeo API
- ✅ Success toast: "Duration extracted: X minutes"
- ✅ Duration field is re-enabled

---

## 🔍 Debugging Tips

### Check Browser Console
1. Open Developer Tools (F12)
2. Go to Console tab
3. Look for messages like:
   - "YouTube video detected..."
   - "YouTube oEmbed data: {...}"
   - "YouTube noembed data: {...}"
   - "Could not fetch YouTube duration..."

### Check Network Tab
1. Open Developer Tools (F12)
2. Go to Network tab
3. Look for requests to:
   - `noembed.com/embed?url=...`
   - `youtube.com/oembed?url=...`
   - `youtube.com/watch?v=...`

### Common Issues

**Issue:** Duration not extracting
- Check browser console for errors
- Verify URL format is correct
- Try different URL format (short vs full)
- Check network requests in Network tab

**Issue:** "Please enter duration manually" message
- This is expected if noembed.com API fails
- User can enter duration manually
- This is a graceful fallback

**Issue:** Duration field disabled
- Wait 2-3 seconds for extraction to complete
- Check console for errors
- Refresh page and try again

---

## ✅ Checklist

- [ ] Test YouTube short URL (youtu.be)
- [ ] Test YouTube full URL (youtube.com/watch)
- [ ] Test YouTube embed URL (youtube.com/embed)
- [ ] Test saving lesson with auto-extracted duration
- [ ] Test editing lesson with duration
- [ ] Test invalid URL handling
- [ ] Test manual duration entry
- [ ] Test Vimeo URL (should still work)
- [ ] Check browser console for errors
- [ ] Check network requests
- [ ] Verify duration saved to database

---

## 🚀 Ready to Test!

All YouTube duration extraction features are implemented and ready for testing!


