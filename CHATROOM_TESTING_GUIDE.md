# Chatroom Admin/Superadmin Testing Guide

## 🧪 Test Scenarios

### Test 1: Admin Can Send Messages
**Steps:**
1. Login as admin user
2. Navigate to any chatroom (general or course)
3. Type a message in the message input field
4. Click "Send" button

**Expected Result:** ✅ Message sends successfully (no 403 error)

### Test 2: Superadmin Can Send Messages
**Steps:**
1. Login as superadmin user
2. Navigate to any chatroom
3. Send a message

**Expected Result:** ✅ Message sends successfully

### Test 3: Admin Can React to Messages
**Steps:**
1. Login as admin
2. Go to any chatroom
3. Hover over a message
4. Click reaction emoji button
5. Select an emoji

**Expected Result:** ✅ Reaction added successfully

### Test 4: Admin Can Pin Messages
**Steps:**
1. Login as admin
2. Go to any chatroom
3. Click message options menu
4. Select "Pin message"

**Expected Result:** ✅ Message pinned successfully

### Test 5: Regular User Still Has Restrictions
**Steps:**
1. Login as regular student user
2. Go to a chatroom where user is muted
3. Try to send a message

**Expected Result:** ✅ Gets "You are muted" error (proper restriction)

### Test 6: Admin Can Update Chatroom
**Steps:**
1. Login as admin
2. Go to chatroom settings
3. Update chatroom name/description
4. Click Save

**Expected Result:** ✅ Chatroom updated successfully

## 🔍 Verification Checklist

- [ ] Admin can send messages in general chatroom
- [ ] Admin can send messages in course chatroom
- [ ] Superadmin can send messages in all chatrooms
- [ ] Admin can react to messages
- [ ] Admin can pin/unpin messages
- [ ] Admin can update chatroom settings
- [ ] Regular users still get muted error when muted
- [ ] No 403 permission errors for admin/superadmin

## 📋 Error Messages to Watch For

❌ **Should NOT see:**
- "You do not have permission to send messages in this chat room."
- "You are muted in this chat room." (for admin/superadmin)

✅ **Should see:**
- Message sent successfully
- Reaction added
- Message pinned
- Chatroom updated

## 🐛 Troubleshooting

If admin/superadmin still gets 403 error:
1. Check user role in database: `SELECT role FROM users WHERE id = ?`
2. Verify role is 'admin' or 'superadmin'
3. Clear browser cache and refresh
4. Check server logs for authorization errors

