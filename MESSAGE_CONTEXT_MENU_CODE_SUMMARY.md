# Message Context Menu - Code Summary

## Key Implementation Details

### 1. Context Menu Trigger (Desktop)
```javascript
oncontextmenu="showMessageContextMenu(event, ${msg.id}, '${currentChatroomId}', '${messageContent}')"
```
- Prevents default browser context menu
- Passes message ID, room ID, and content
- Positions menu at cursor coordinates

### 2. Long-Press Trigger (Mobile)
```javascript
ontouchstart="startLongPress(event, ${msg.id}, '${currentChatroomId}', '${messageContent}')"
ontouchend="endLongPress()"
```
- Starts 500ms timer on touch
- Shows menu if timer completes
- Cancels if user releases before 500ms

### 3. Context Menu State Management
```javascript
let currentContextMessage = {
    id: null,
    roomId: null,
    content: null
};
```
- Stores selected message info
- Used by edit/delete functions
- Cleared when menu closes

### 4. Edit Message Flow
1. User clicks "Edit" in context menu
2. `openEditModal()` opens modal with message content
3. User edits text in textarea
4. `saveEditMessage()` sends PUT request
5. Messages reload to show update

### 5. Delete Message Flow
1. User clicks "Delete" in context menu
2. `openDeleteConfirmModal()` shows confirmation
3. User confirms deletion
4. `confirmDeleteMessage()` sends DELETE request
5. Messages reload to show deletion

### 6. Modal Closing
- Click outside modal
- Press Escape key
- Click Cancel button
- Successfully save/delete

### 7. CSS Classes
- `.message-context-menu`: Context menu container
- `.message-context-menu-item`: Menu buttons
- `.edit-message-modal`: Modal overlay
- `.message-long-press-active`: Long-press highlight

### 8. Animations
- `fadeIn`: 0.2s opacity transition
- `slideUp`: 0.3s transform + opacity

## File Structure
```
resources/views/chat/chatroom.blade.php
├── CSS Styles (Lines 178-345)
│   ├── Context Menu Styles
│   ├── Modal Styles
│   └── Animation Keyframes
├── HTML Elements (Lines 415-453)
│   ├── Context Menu
│   ├── Edit Modal
│   └── Delete Confirmation Modal
└── JavaScript (Lines 768-965)
    ├── Context Menu Functions
    ├── Edit Functions
    ├── Delete Functions
    └── Modal Controls
```

## API Integration
- **Edit**: `PUT /api/chatrooms/{roomId}/messages/{messageId}`
- **Delete**: `DELETE /api/chatrooms/{roomId}/messages/{messageId}`
- Both require Bearer token authentication
- Both reload messages after success

## Security Features
- Authorization checks on backend
- Only message owner or admin can edit/delete
- Soft delete (is_deleted flag)
- CSRF protection via API token

## Browser Events
- `oncontextmenu`: Right-click handler
- `ontouchstart`: Touch start for long-press
- `ontouchend`: Touch end to cancel long-press
- `click`: Close menu/modal
- `keydown`: Escape key handler

