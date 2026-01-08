# Message Edit/Delete - Testing Guide

## Quick Test Steps

### Test 1: Edit Your Own Message
1. Open the chatroom
2. Send a test message: "Hello World"
3. Right-click on the message
4. Click "Edit"
5. Change text to: "Hello World - Edited"
6. Click "Save"
7. ✅ Message should update immediately
8. ✅ No error alert should appear

### Test 2: Delete Your Own Message
1. Send a test message: "Delete me"
2. Right-click on the message
3. Click "Delete"
4. Click "Delete" in confirmation modal
5. ✅ Message should disappear
6. ✅ No error alert should appear

### Test 3: Edit as Admin
1. Login as admin user
2. Find another user's message
3. Right-click on it
4. ✅ "Edit" option should appear
5. Click "Edit"
6. Modify the message
7. Click "Save"
8. ✅ Message should update

### Test 4: Delete as Admin
1. Login as admin user
2. Find another user's message
3. Right-click on it
4. ✅ "Delete" option should appear
5. Click "Delete"
6. Confirm deletion
7. ✅ Message should be deleted

### Test 5: Regular User Cannot Edit Others
1. Login as regular user
2. Find another user's message
3. Right-click on it
4. ✅ Context menu should NOT appear
5. ✅ No edit/delete options visible

### Test 6: Empty Message Validation
1. Right-click on your message
2. Click "Edit"
3. Clear all text
4. Click "Save"
5. ✅ Alert should appear: "Message cannot be empty"
6. ✅ Modal should stay open

### Test 7: Long Message Edit
1. Send a long message (500+ characters)
2. Right-click and click "Edit"
3. ✅ Full text should appear in textarea
4. ✅ Text should be pre-selected
5. Edit and save
6. ✅ Should update successfully

### Test 8: Keyboard Shortcuts
1. Open edit modal
2. Press Escape key
3. ✅ Modal should close
4. Open delete confirmation
5. Press Escape key
6. ✅ Modal should close

### Test 9: Click Outside Modal
1. Open edit modal
2. Click outside the modal content
3. ✅ Modal should close
4. Open delete confirmation
5. Click outside the modal content
6. ✅ Modal should close

### Test 10: Mobile Long-Press Edit
1. On mobile device, long-press your message
2. ✅ Context menu should appear
3. Tap "Edit"
4. ✅ Modal should open
5. Edit and tap "Save"
6. ✅ Should update successfully

### Test 11: Mobile Long-Press Delete
1. On mobile device, long-press your message
2. ✅ Context menu should appear
3. Tap "Delete"
4. ✅ Confirmation modal should open
5. Tap "Delete"
6. ✅ Message should be deleted

### Test 12: Rapid Edit/Delete
1. Send 3 messages
2. Quickly edit message 1
3. While editing, delete message 2
4. Save message 1
5. ✅ All operations should complete without errors

## Expected Behavior

### Success Cases
- ✅ Edit updates message immediately
- ✅ Delete removes message from chat
- ✅ Admin can edit/delete any message
- ✅ User can only edit/delete own messages
- ✅ Modals close on Escape or outside click
- ✅ Text pre-selected in edit modal

### Error Cases
- ✅ Empty message shows validation error
- ✅ Unauthorized access shows 403 error
- ✅ Server errors show detailed message
- ✅ Network errors handled gracefully

## Browser Console
Check browser console (F12) for:
- ✅ No JavaScript errors
- ✅ Proper API responses (200, 403, 422, 500)
- ✅ Proper error logging

## Server Logs
Check `storage/logs/laravel.log` for:
- ✅ No TypeError exceptions
- ✅ Proper authorization checks
- ✅ Detailed error information if issues occur

