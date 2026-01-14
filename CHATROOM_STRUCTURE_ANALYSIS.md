# Chatroom Structure & Code Analysis

## Overview
The chatroom is a Laravel Blade template (`resources/views/chat/chatroom.blade.php`) with comprehensive messaging features including text, audio, images, and file attachments.

## Key Components

### 1. **Layout Structure**
- **Extends**: `layouts.usertemplate`
- **Main Container**: `container-fluid` with responsive grid layout
- **Sidebar**: Desktop (col-lg-4) and Mobile (hidden by default)
- **Chat Panel**: Right side (col-12 col-lg-8)

### 2. **UI Sections**

#### Header
- Chat room name display (`#current-room-name`)
- Mobile sidebar toggle button

#### Sidebar (Left)
- Search bar for conversations
- List of chatrooms with unread badges
- Active state highlighting
- Mobile overlay for responsive design

#### Chat Area (Right)
- Welcome message (initial state)
- Chat history with date separators
- Message display area (`#chat-messages`)
- Input area with multiple action buttons

#### Input Area Features
- Text message input
- Attachment button (paperclip icon)
- Audio recording button (mic icon)
- Emoji picker button
- Camera button (take pictures)
- Send button

### 3. **Message Types**
1. **Text Messages**: Plain text with edit/delete options
2. **Image Messages**: Displayed with max-width 300px
3. **Audio Messages**: Custom player with waveform animation
4. **File Messages**: Download links with file icons

### 4. **Core JavaScript Functions**

#### Initialization
- `loadChatrooms()`: Fetches chatrooms from API
- `renderChatrooms()`: Renders chatroom list in sidebar
- `selectChatroom()`: Loads selected chatroom

#### Message Management
- `loadMessages()`: Fetches messages for chatroom
- `renderMessages()`: Renders all messages with date separators
- `renderSingleMessage()`: Renders individual message with type handling
- `saveEditMessage()`: Updates message content via API
- `confirmDeleteMessage()`: Deletes message via API

#### Media Features
- **Audio Recording**: `startRecordingBtn`, `stopRecordingBtn`, `sendAudioBtn`
- **Camera**: `startCameraBtn`, `capturePhotoBtn`, `sendPhotoBtn`
- **File Upload**: `attachmentBtn`, `fileInput`, `sendFileBtn`
- **Emoji Picker**: `emojiBtn`, `insertEmoji()`
- **Audio Playback**: `toggleAudioPlayback()`, `onAudioEnded()`

### 5. **Styling Features**

#### Message Styling
- Current user messages: Dark teal background, right-aligned
- Other users: Light gray background, left-aligned
- Admin badge: Gold gradient with uppercase text
- Date separators: Centered with horizontal lines

#### Modals
- Edit message modal
- Delete confirmation modal
- Audio recording modal
- Camera capture modal
- File preview modal
- All with overlay backdrop

#### Responsive Design
- Mobile sidebar slides from left
- Overlay prevents interaction with main content
- Breakpoint: 991.98px (lg)

### 6. **API Endpoints Used**
- `GET /chatrooms` - List all chatrooms
- `GET /chatrooms/{roomId}/messages` - Get messages
- `POST /chatrooms/{roomId}/messages` - Send message
- `PUT /chatrooms/{roomId}/messages/{messageId}` - Edit message
- `DELETE /chatrooms/{roomId}/messages/{messageId}` - Delete message

### 7. **Authentication**
- Uses Bearer token from localStorage (`auth_token`)
- User data stored in localStorage (`auth_user`)
- Checks for admin/superadmin role
- Loads admin sidebar manager for privileged users

### 8. **Key Features**
- ✅ Real-time message loading
- ✅ Edit/delete messages (own or admin)
- ✅ Audio recording and playback
- ✅ Camera capture
- ✅ File attachments
- ✅ Emoji picker
- ✅ Date separators
- ✅ Admin badges
- ✅ Unread message counts
- ✅ Persistent chatroom selection
- ✅ Mobile responsive design
- ✅ Message type indicators
- ✅ Edited message indicators

### 9. **State Management**
- `currentChatroomId`: Currently selected chatroom
- `currentContextMessage`: Message being edited/deleted
- `currentPlayingAudioId`: Currently playing audio
- `LAST_CHATROOM_KEY`: localStorage key for persistence

### 10. **Security Considerations**
- Bearer token authentication
- Admin/superadmin role checks
- Message ownership validation
- XSS protection via proper escaping

