# 🧪 Profile Page - Crop Image Feature Testing Guide

**Feature:** Image cropping on profile page  
**Status:** ✅ READY TO TEST  
**Date:** December 9, 2025  

---

## 📋 Test Cases

### Test 1: Upload Image via Click
**Steps:**
1. Navigate to profile page
2. Click on the upload area
3. Select an image file (JPG, PNG, etc.)
4. ✅ Cropper modal should open automatically

**Expected Result:**
- ✅ File picker opens
- ✅ Image selected
- ✅ Cropper modal displays image
- ✅ Crop area visible with guides

---

### Test 2: Upload Image via Drag & Drop
**Steps:**
1. Navigate to profile page
2. Drag an image file to upload area
3. Drop the image
4. ✅ Cropper modal should open automatically

**Expected Result:**
- ✅ Upload area highlights on drag-over
- ✅ Image dropped successfully
- ✅ Cropper modal displays image
- ✅ Crop area visible with guides

---

### Test 3: Zoom Control
**Steps:**
1. Upload an image (Test 1 or 2)
2. Cropper modal is open
3. Move zoom slider to the right
4. ✅ Image should zoom in
5. Move zoom slider to the left
6. ✅ Image should zoom out

**Expected Result:**
- ✅ Zoom slider works smoothly
- ✅ Image zooms in/out
- ✅ Crop area adjusts accordingly

---

### Test 4: Rotate Left
**Steps:**
1. Upload an image
2. Cropper modal is open
3. Click "Rotate Left" button
4. ✅ Image should rotate -45°
5. Click again
6. ✅ Image should rotate another -45°

**Expected Result:**
- ✅ Image rotates -45° each click
- ✅ Crop area adjusts
- ✅ Can rotate multiple times

---

### Test 5: Rotate Right
**Steps:**
1. Upload an image
2. Cropper modal is open
3. Click "Rotate Right" button
4. ✅ Image should rotate +45°
5. Click again
6. ✅ Image should rotate another +45°

**Expected Result:**
- ✅ Image rotates +45° each click
- ✅ Crop area adjusts
- ✅ Can rotate multiple times

---

### Test 6: Reset Button
**Steps:**
1. Upload an image
2. Zoom in, rotate, adjust crop area
3. Click "Reset" button
4. ✅ Image should return to original state
5. ✅ Zoom slider should reset to 1
6. ✅ Rotation should reset to 0

**Expected Result:**
- ✅ Image returns to original
- ✅ Zoom slider resets
- ✅ Crop area resets
- ✅ All changes undone

---

### Test 7: Crop & Save
**Steps:**
1. Upload an image
2. Adjust crop area (drag corners)
3. Click "Crop & Save" button
4. ✅ Modal should close
5. ✅ Preview should update with cropped image
6. ✅ Toast notification: "Image cropped successfully"

**Expected Result:**
- ✅ Modal closes
- ✅ Preview image updates
- ✅ Success toast appears
- ✅ File input updated with cropped image

---

### Test 8: Cancel Cropping
**Steps:**
1. Upload an image
2. Cropper modal is open
3. Click "Cancel" button
4. ✅ Modal should close
5. ✅ Original preview should remain

**Expected Result:**
- ✅ Modal closes
- ✅ No changes to preview
- ✅ File input unchanged

---

### Test 9: File Type Validation
**Steps:**
1. Try to upload a non-image file (PDF, TXT, etc.)
2. ✅ Error message should appear
3. ✅ Modal should not open

**Expected Result:**
- ✅ Error toast: "Please select a valid image file"
- ✅ Cropper modal does not open
- ✅ File input cleared

---

### Test 10: File Size Validation
**Steps:**
1. Try to upload an image larger than 5MB
2. ✅ Error message should appear
3. ✅ Modal should not open

**Expected Result:**
- ✅ Error toast: "File size must be less than 5MB"
- ✅ Cropper modal does not open
- ✅ File input cleared

---

### Test 11: Save Profile with Cropped Image
**Steps:**
1. Upload and crop an image (Test 7)
2. Fill in other profile fields
3. Click "Save Profile" button
4. ✅ Profile should update
5. ✅ Cropped image should be saved

**Expected Result:**
- ✅ Success toast: "Profile updated successfully"
- ✅ Cropped image saved to database
- ✅ Profile page reloads with new image

---

### Test 12: Edit Profile with Existing Photo
**Steps:**
1. Load profile page with existing photo
2. Click upload area
3. Upload a new image
4. Crop and save
5. ✅ Old photo should be replaced

**Expected Result:**
- ✅ New cropped image replaces old photo
- ✅ Profile updates successfully
- ✅ New image persists on reload

---

## ✅ Checklist

- [ ] Upload via click works
- [ ] Upload via drag-drop works
- [ ] Zoom control works
- [ ] Rotate left works
- [ ] Rotate right works
- [ ] Reset button works
- [ ] Crop & Save works
- [ ] Cancel button works
- [ ] File type validation works
- [ ] File size validation works
- [ ] Save profile with cropped image works
- [ ] Edit profile with existing photo works
- [ ] Toast notifications appear
- [ ] Modal opens/closes correctly
- [ ] Preview updates correctly

---

## 🔍 Debugging Tips

### Check Browser Console
1. Open Developer Tools (F12)
2. Go to Console tab
3. Look for any JavaScript errors
4. Check for validation messages

### Check Network Tab
1. Open Developer Tools (F12)
2. Go to Network tab
3. Check API requests when saving profile
4. Verify image is sent in FormData

### Common Issues

**Issue:** Cropper modal doesn't open
- Check browser console for errors
- Verify Cropper.js library loaded
- Check file validation messages

**Issue:** Zoom/rotate doesn't work
- Check if cropper instance initialized
- Verify modal is fully rendered
- Check browser console for errors

**Issue:** Image not saving
- Check file input has cropped image
- Verify FormData includes avatar field
- Check API response in Network tab

---

## 🚀 Ready to Test!

All crop image features are implemented and ready for testing!


