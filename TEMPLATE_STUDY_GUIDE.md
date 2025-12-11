# Dashboard Templates Study Guide

## Overview
Two main layout templates for Kokokah LMS:
1. **dashboardtemp.blade.php** - Admin/Staff Dashboard (386 lines)
2. **usertemplate.blade.php** - Student/User Dashboard (192 lines)

---

## 1. DASHBOARDTEMP.BLADE.PHP (Admin Dashboard)

### Structure
- **Head Section**: Fonts, Bootstrap 5, Font Awesome, Chart.js, Axios
- **Body**: Sidebar, Topbar, Content Area, Footer
- **Scripts**: Dashboard module, sidebar toggle, navigation logic

### Key Dynamic Features

#### A. Loading Overlay (Lines 39-45)
```html
<div id="loadingOverlay" style="display: none; ...">
  <div class="spinner-border text-light">
```
- Hidden by default, shown during API calls
- Fixed positioning with semi-transparent backdrop
- Z-index: 9998 (below alerts)

#### B. Sidebar Navigation (Lines 50-159)
**Dynamic Elements:**
- **Profile Section** (Lines 142-157):
  - `#profileImage` - User avatar (40x40px, circular)
  - `#userName` - Displays user name
  - `#userRole` - Displays user role
  - `#logoutBtn` - Logout button

- **Navigation Menus** (Lines 57-137):
  - Users Management (collapsible)
  - Course Management (collapsible)
  - Payments & Transactions (collapsible)
  - Reports & Analytics (collapsible)
  - Communication (collapsible)

#### C. Topbar (Lines 162-183)
- Mobile hamburger button (`#hamburger`)
- Search input (`.search-input`)
- Top icons (bell, envelope, question)
- Welcome message (mobile only)

#### D. Alert Container (Line 186)
```html
<div id="alertContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
```
- Fixed position for toast notifications
- Z-index: 9999 (highest)

### Dynamic JavaScript Functionality

#### 1. Dashboard Module Initialization (Lines 215-225)
```javascript
import DashboardModule from '{{ asset('js/dashboard.js') }}';
// Initializes on DOM ready
```
- Loads dashboard.js module
- Handles dashboard-specific logic

#### 2. Mobile Sidebar Toggle (Lines 227-265)
**Functions:**
- `openSidebar()` - Adds 'show' class, hides body overflow
- `closeSidebar()` - Removes 'show' class, restores overflow
- Hamburger click opens sidebar
- Overlay click closes sidebar
- Auto-closes on nav link click (mobile only)
- Resets on window resize (>992px)

#### 3. Active Navigation Detection (Lines 267-313)
**Logic:**
- Compares `window.location.pathname` with nav link `href`
- Exact match only (no partial matches)
- Adds 'active' class to matching link
- Auto-opens parent dropdown if child is active
- Special case: `/dashboard` or `/` activates dashboard link

**Key Code:**
```javascript
if (href === currentPath) {
    link.classList.add('active');
    // If child, open parent dropdown
}
```

#### 4. Dropdown Chevron Animation (Lines 318-341)
**Events:**
- `show.bs.collapse` - Rotates chevron, changes to up arrow
- `hide.bs.collapse` - Removes rotation, changes to down arrow

#### 5. Dropdown Exclusivity (Lines 343-374)
**Behavior:**
- Only one dropdown open at a time
- Closing other dropdowns when one opens
- Toggle current dropdown on click
- Uses Bootstrap Collapse API

### External Scripts
- **kokokahLoader.js** - Logo loading animation
- **confirmationModal.js** - Confirmation dialogs

---

## 2. USERTEMPLATE.BLADE.PHP (Student Dashboard)

### Structure
- **Head Section**: Bootstrap 5, Font Awesome, Chart.js, Fonts
- **Body**: Sidebar, Topbar, Content Area, Footer
- **Scripts**: Sidebar toggle only (simpler than admin)

### Key Dynamic Features

#### A. Sidebar Navigation (Lines 40-100)
**Static Links:**
- Dashboard (`/usersdashboard`)
- Class (`/userclass`)
- Subject (`/usersubject`)
- Results & Scoring (`/userresult`)
- Kudikah (`/userkudikah`)
- Notification (`/results`)

**Collapsible:**
- Communication (Lines 60-71)
  - Announcement
  - Email/Messaging Center
  - Feedback/Surveys

#### B. Profile Section (Lines 80-98)
- Same structure as admin template
- `#profileImage`, `#userName`, `#userRole`, `#logoutBtn`

#### C. Topbar (Lines 103-126)
- Mobile hamburger button
- Search input
- Top icons (bell, envelope, question)
- No welcome message (commented out)

### Dynamic JavaScript Functionality

#### 1. Mobile Sidebar Toggle (Lines 149-188)
**Simpler than admin version:**
- `openSidebar()` - Adds 'show' class
- `closeSidebar()` - Removes 'show' class
- Hamburger click opens sidebar
- Overlay click closes sidebar
- Auto-closes on ANY nav link click
- Resets on window resize (>992px)

**Key Difference:**
- No active navigation detection
- No dropdown chevron animation
- No dropdown exclusivity logic
- Simpler, lighter implementation

---

## 3. SHARED DYNAMIC ELEMENTS

### Profile Section (Both Templates)
```html
<div class="profile mt-3" id="profileSection">
  <img class="avatar" id="profileImage" src="images/winner-round.png">
  <h6 id="userName">Culacino_</h6>
  <p id="userRole">UX Designer</p>
  <a href="#" id="logoutBtn">Logout</a>
</div>
```

**Populated by JavaScript:**
- User data loaded via API
- Profile image updated dynamically
- Name and role displayed
- Logout functionality

### Mobile Responsive Behavior
- Sidebar hidden on mobile (<992px)
- Hamburger button visible on mobile
- Overlay prevents background scroll
- Auto-closes on navigation

---

## 4. KEY DIFFERENCES

| Feature | Admin (dashboardtemp) | Student (usertemplate) |
|---------|----------------------|----------------------|
| Lines | 386 | 192 |
| Dropdowns | 5 collapsible menus | 1 collapsible menu |
| Active Nav | Yes | No |
| Chevron Animation | Yes | No |
| Dropdown Exclusivity | Yes | No |
| Loading Overlay | Yes | No |
| Alert Container | Yes | No |
| Dashboard Module | Yes | No |
| Complexity | High | Low |

---

## 5. INTEGRATION POINTS

### CSS Files
- `css/style_theme.css` - Theme colors
- `css/dashboard.css` - Layout styles
- `css/access.css` - Access control styles (admin only)
- `css/loader.css` - Loading animation (admin only)

### JavaScript Files
- `js/dashboard.js` - Dashboard module (admin only)
- `js/utils/kokokahLoader.js` - Logo loader (admin only)
- `js/utils/confirmationModal.js` - Modals (admin only)

### External Libraries
- Bootstrap 5.3.3
- Font Awesome 6.5.0
- Chart.js 4.4.3
- Axios (for API calls)

---

## 6. CONTENT INJECTION

Both templates use:
```blade
@yield('content')
```

This allows child views to inject content into the main area.

**Example Child Views:**
- Admin: `admin/dashboard.blade.php`, `admin/users.blade.php`
- Student: `users/usersdashboard.blade.php`, `users/usersubject.blade.php`

