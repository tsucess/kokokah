# Frontend Study: Users Directory

## Overview
The `resources/views/users/` directory contains 14 Blade template files for the student/user-facing frontend of the Kokokah LMS. All files extend `layouts.usertemplate` (student layout) except `users.blade.php` which extends `layouts.dashboardtemp` (admin layout).

## File Structure & Purpose

### 1. **profile.blade.php** (1,161 lines)
**Purpose**: Student profile management page
**Features**:
- My Details tab: First name, last name, email, date of birth, gender
- Parent Details: Parent first/last name, email, phone
- Login tab: Password change functionality
- Profile photo upload with image cropping (Cropper.js)
- Account deletion with email confirmation modal
- Form validation and error handling
- Toast notifications for user feedback
**Layout**: Extends `layouts.usertemplate`
**API Integration**: Uses UserApiClient for profile data loading/saving

### 2. **userannouncement.blade.php** (85 lines)
**Purpose**: Notifications and announcements page
**Features**:
- Tab-based filtering: All, Exams, Events, Alerts, General Info
- Notification cards with title, category, timestamp
- Notification text preview
- Action menu (ellipsis button)
- Responsive design
**Design**: Uses Kokokah color scheme (teal #004A53, yellow #FDAF22)

### 3. **userkoodies.blade.php** (314 lines)
**Purpose**: AI-powered Koodies assistant chat interface
**Features**:
- Suggestion cards for common tasks
- Chat input with microphone and emoji support
- History panel (right sidebar) showing past conversations
- Responsive layout with sidebar collapse on mobile
- Custom CSS for layout management
**Components**:
- Sidebar navigation
- Main chat area
- Right history panel
- Chat input wrapper

### 4. **leaderboard.blade.php** (324 lines)
**Purpose**: Student leaderboard and rankings
**Features**:
- Top 3 winners display with medals (1st, 2nd, 3rd place)
- Winner cards with profile images and progress tracks
- Leaderboard table with rank, name, class, points, level, badge
- Filter and search functionality
- Month selector dropdown
- Pagination controls (Previous/Next)
**Design**: Uses platform-style display with colored backgrounds

### 5. **userclass.blade.php** (127 lines)
**Purpose**: Class enrollment page
**Features**:
- Grid layout of available classes
- Class cards with logo, class name, enroll button
- Header section with greeting
- Responsive card grid
- Enroll button navigation to `/userenroll`

### 6. **usersdashboard.blade.php** (264 lines)
**Purpose**: Main student dashboard
**Features**:
- Stats cards with hover effects
- Header section with greeting
- Course/subject cards grid
- View buttons for detailed views
- Responsive design with mobile adjustments
- Card container with auto-fit grid layout

### 7. **enroll.blade.php** (373 lines)
**Purpose**: Course enrollment management
**Features**:
- Transaction-style list of courses
- Course cards with details
- Enroll buttons
- Filter and search controls
- Responsive table layout
- Header and controls section

### 8. **usersubject.blade.php** (140 lines)
**Purpose**: Subject listing and progress tracking
**Features**:
- Subject cards grid layout
- Progress bars showing completion percentage
- Subject name and class information
- View Subjects button
- Responsive card layout
- Color-coded badges

### 9. **userfeedback.blade.php** (206 lines)
**Purpose**: Feedback submission page
**Features**:
- Feature showcase containers
- Feedback form with textarea
- Share feedback button
- Input validation styling
- Responsive layout
- Custom form styling with Kokokah colors

### 10. **kudikah.blade.php** (265 lines)
**Purpose**: Kudikah (virtual currency) management
**Features**:
- Add money button
- Enroll button
- Form inputs with custom styling
- Toggle buttons
- Checkbox labels
- Form dividers
- Responsive design

### 11. **result.blade.php** (154 lines)
**Purpose**: Exam/quiz results display
**Features**:
- Header banner with yellow background (#FDAF22)
- Result score display in circular badge
- Progress bar showing performance
- Result items grid layout
- Responsive design with banner image positioning

### 12. **termsubject.blade.php** (204 lines)
**Purpose**: Term-based subject and lesson view
**Features**:
- Term selector buttons (First, Second, Third)
- Question navigation buttons (answered/unanswered)
- Lesson items with descriptions
- Homework completion tracking
- Lesson button for detailed view
- Responsive layout

### 13. **userkoodiesaudio.blade.php** (803 lines)
**Purpose**: Koodies AI chat interface with audio support
**Features**:
- Full chat interface with sidebar
- Message bubbles (user and AI)
- Audio message support
- History panel
- Responsive design
- Custom CSS for chat styling
- Sidebar navigation

### 14. **users.blade.php** (137 lines)
**Purpose**: Admin users management page
**Features**:
- Extends `layouts.dashboardtemp` (admin layout)
- Users list table
- User details: Name, ID, Email, Gender, Contact, Role
- Edit and delete actions
- Search and filter controls
- Welcome section with action buttons
- Responsive table layout

## Design System Implementation

### Colors Used
- **Primary Teal**: #004A53 (headings, borders, active states)
- **Secondary Yellow**: #FDAF22 (buttons, badges, highlights)
- **Light Gray**: #F5F4F9, #F5F6F8 (backgrounds)
- **Dark Teal**: #114243 (sidebar, active nav items)
- **Orange**: #F56824 (secondary accent)

### Typography
- **Headings**: Fredoka font family
- **Body**: Inter, Segoe UI, sans-serif
- **Font Weights**: 400 (regular), 500 (medium), 600 (semibold), 700 (bold)

### Layout Patterns
- **Grid Layout**: `grid-template-columns: repeat(auto-fit, minmax(300px, 1fr))`
- **Flexbox**: Used for alignment and spacing
- **Responsive**: Mobile-first approach with media queries
- **Cards**: Rounded corners (15-20px), subtle shadows, white backgrounds

### Common Components
- **Buttons**: Primary (yellow), Secondary (teal border), Tertiary (transparent)
- **Forms**: Custom input styling with borders and labels
- **Tables**: Responsive with hover effects
- **Modals**: Bootstrap 5 modals with custom styling
- **Tabs**: Pill-style tabs with active states

## API Integration Points

### UserApiClient Methods Used
- `getProfile()` - Load user profile data
- `updateProfile(data)` - Save profile changes
- `deleteAccount()` - Delete user account
- `changePassword(data)` - Update password

### Routes Connected
- `/userprofile` - Profile page
- `/userannouncement` - Announcements
- `/userkoodies` - Koodies chat
- `/userleaderboard` - Leaderboard
- `/userclass` - Class enrollment
- `/usersdashboard` - Main dashboard
- `/userenroll` - Course enrollment
- `/usersubject` - Subject listing
- `/userfeedback` - Feedback form
- `/kudikah` - Virtual currency
- `/result` - Results display
- `/termsubject` - Term subjects
- `/userkoodiesaudio` - Audio chat

## Key Features Summary

✅ **Profile Management**: Complete user profile with photo upload and cropping
✅ **Announcements**: Notification system with filtering
✅ **AI Assistant**: Koodies chat interface with history
✅ **Leaderboard**: Competitive ranking system
✅ **Course Management**: Enrollment and subject tracking
✅ **Feedback System**: User feedback collection
✅ **Results Display**: Exam/quiz results with progress
✅ **Virtual Currency**: Kudikah system for rewards
✅ **Responsive Design**: Mobile-friendly layouts
✅ **Form Validation**: Client-side validation with error messages
✅ **Toast Notifications**: User feedback notifications
✅ **Image Cropping**: Profile photo editing with Cropper.js

## Technical Stack

- **Framework**: Laravel Blade templating
- **CSS**: Bootstrap 5, custom CSS
- **JavaScript**: Vanilla JS, Cropper.js library
- **Icons**: Font Awesome 6.5.0
- **API Client**: UserApiClient (custom)
- **Authentication**: Bearer token (localStorage)

## Next Steps for Implementation

1. Implement dynamic data loading for all pages via API
2. Add form submission handlers for feedback and enrollment
3. Implement real-time notifications
4. Add pagination for leaderboard and results
5. Implement search and filter functionality
6. Add image upload for user avatars
7. Implement quiz/exam functionality
8. Add progress tracking and analytics

