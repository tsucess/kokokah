# Message Context Menu - Testing Guide

## Quick Start

### Desktop Testing
1. **Right-Click on Your Message**
   - Context menu should appear at cursor position
   - Menu shows "Edit" and "Delete" options
   - Menu has shadow and rounded corners (WhatsApp style)

2. **Click Edit**
   - Modal opens with message content in textarea
   - Text is pre-selected for easy editing
   - Edit the message and click "Save"
   - Message updates in chat immediately

3. **Right-Click and Delete**
   - Confirmation modal appears
   - Shows warning: "Are you sure you want to delete this message?"
   - Click "Delete" to confirm or "Cancel" to abort
   - Message is removed from chat

### Mobile Testing
1. **Long-Press on Your Message** (hold for 500ms)
   - Message highlights with light background
   - Context menu appears at touch point
   - Same Edit/Delete options as desktop

2. **Edit on Mobile**
   - Tap "Edit" option
   - Modal opens with textarea
   - Edit message and tap "Save"
   - Message updates in chat

3. **Delete on Mobile**
   - Tap "Delete" option
   - Confirmation modal appears
   - Tap "Delete" to confirm
   - Message is removed

## Keyboard Shortcuts
- **Escape**: Close any open modal or context menu
- **Tab**: Navigate between buttons in modal
- **Enter**: Submit form (when focused on Save button)

## Permissions Testing

### Regular User
- ✅ Can edit own messages
- ✅ Can delete own messages
- ❌ Cannot edit/delete others' messages
- Context menu only appears on own messages

### Admin/Superadmin User
- ✅ Can edit all messages
- ✅ Can delete all messages
- ✅ Can edit/delete others' messages
- Context menu appears on all messages

## Edge Cases to Test

1. **Empty Message Edit**
   - Try to save empty message
   - Should show alert: "Message cannot be empty"

2. **Network Error**
   - Disable network and try to edit/delete
   - Should show error message

3. **Deleted Message**
   - Message shows "This message has been deleted"
   - No context menu appears on deleted messages

4. **Modal Interactions**
   - Click outside modal to close
   - Press Escape to close
   - Click Cancel button to close

5. **Multiple Messages**
   - Edit/delete different messages in sequence
   - Verify correct message is updated/deleted

## Visual Verification

- [ ] Context menu has rounded corners
- [ ] Context menu has shadow effect
- [ ] Edit icon appears before "Edit" text
- [ ] Delete icon appears before "Delete" text
- [ ] Delete option is red/danger colored
- [ ] Modal has centered layout
- [ ] Modal has smooth fade-in animation
- [ ] Message highlights on mobile long-press
- [ ] Cursor changes to context-menu on hover

## Browser Compatibility
- Chrome/Edge: ✅ Full support
- Firefox: ✅ Full support
- Safari: ✅ Full support (iOS long-press works)
- Mobile browsers: ✅ Long-press support

## Performance Notes
- Long-press duration: 500ms (adjustable in code)
- Context menu positioning: Dynamic based on cursor/touch
- Modal animations: 200-300ms (smooth and responsive)

