# Template Architecture & Visual Guide

## DASHBOARDTEMP.BLADE.PHP - Layout Architecture

### Visual Structure
```
┌─────────────────────────────────────────────────────────────┐
│                        TOPBAR (header)                       │
│  [☰] Search [🔔] [✉️] [?]                                   │
├──────────────┬──────────────────────────────────────────────┤
│              │                                               │
│  SIDEBAR     │                                               │
│  ┌────────┐  │                                               │
│  │ Logo   │  │                                               │
│  └────────┘  │                                               │
│              │                                               │
│  Navigation  │          @yield('content')                   │
│  ├─ 📊 Dash  │                                               │
│  ├─ 👥 Users │          (Child View Content)                │
│  │  ├─ All   │                                               │
│  │  ├─ Stud  │                                               │
│  │  └─ Inst  │                                               │
│  ├─ 📚 Cour  │                                               │
│  ├─ 💳 Pay   │                                               │
│  ├─ 📊 Rep   │                                               │
│  └─ 💬 Comm  │                                               │
│              │                                               │
│  Profile     │                                               │
│  ┌────────┐  │                                               │
│  │ Avatar │  │                                               │
│  │ Name   │  │                                               │
│  │ Role   │  │                                               │
│  │ Logout │  │                                               │
│  └────────┘  │                                               │
├──────────────┴──────────────────────────────────────────────┤
│                        FOOTER                                │
│  © Copyright Kokokah 2025 | License | Docs | Support        │
└─────────────────────────────────────────────────────────────┘
```

### Z-Index Stack
```
9999 ─── Alert Container (#alertContainer)
9998 ─── Loading Overlay (#loadingOverlay)
1000 ─── Topbar (header)
 500 ─── Sidebar (#sidebar)
 100 ─── Sidebar Overlay (#sidebarOverlay)
   0 ─── Main Content
```

### Component Hierarchy
```
html
├─ head
│  ├─ meta (charset, viewport)
│  ├─ title
│  ├─ favicon
│  ├─ fonts (Fredoka, Inter)
│  ├─ stylesheets
│  │  ├─ Bootstrap 5.3.3
│  │  ├─ Font Awesome 6.5.0
│  │  ├─ style_theme.css
│  │  ├─ dashboard.css
│  │  ├─ access.css
│  │  └─ loader.css
│  └─ scripts (Bootstrap, Axios, Chart.js)
│
└─ body
   ├─ #loadingOverlay (hidden by default)
   ├─ #sidebarOverlay (hidden by default)
   ├─ #sidebar
   │  ├─ .brand (logo)
   │  ├─ #sidebarNav
   │  │  ├─ Dashboard link
   │  │  ├─ Users Management (collapsible)
   │  │  ├─ Course Management (collapsible)
   │  │  ├─ Payments (collapsible)
   │  │  ├─ Reports (collapsible)
   │  │  └─ Communication (collapsible)
   │  └─ .sidebar-footer
   │     ├─ Settings link
   │     └─ #profileSection
   │        ├─ #profileImage
   │        ├─ #userName
   │        ├─ #userRole
   │        └─ #logoutBtn
   ├─ header.topbar
   │  ├─ #hamburger (mobile)
   │  ├─ .search-wrap
   │  └─ .top-icons
   ├─ #alertContainer
   ├─ @yield('content')
   ├─ .page-footer
   └─ scripts
      ├─ Chart.js
      ├─ Axios
      ├─ Dashboard Module
      ├─ Sidebar behavior
      ├─ Navigation logic
      ├─ Dropdown logic
      ├─ kokokahLoader.js
      └─ confirmationModal.js
```

---

## USERTEMPLATE.BLADE.PHP - Layout Architecture

### Visual Structure
```
┌─────────────────────────────────────────────────────────────┐
│                        TOPBAR (header)                       │
│  [☰] Search [🔔] [✉️] [?]                                   │
├──────────────┬──────────────────────────────────────────────┤
│              │                                               │
│  SIDEBAR     │                                               │
│  ┌────────┐  │                                               │
│  │ Logo   │  │                                               │
│  └────────┘  │                                               │
│              │                                               │
│  Navigation  │          @yield('content')                   │
│  ├─ 📊 Dash  │                                               │
│  ├─ 📚 Class │          (Child View Content)                │
│  ├─ 📖 Subj  │                                               │
│  ├─ 📊 Res   │                                               │
│  ├─ 🎓 Kudi  │                                               │
│  ├─ 🔔 Notif │                                               │
│  └─ 💬 Comm  │                                               │
│     ├─ Ann   │                                               │
│     ├─ Email │                                               │
│     └─ Feed  │                                               │
│              │                                               │
│  Profile     │                                               │
│  ┌────────┐  │                                               │
│  │ Avatar │  │                                               │
│  │ Name   │  │                                               │
│  │ Role   │  │                                               │
│  │ Logout │  │                                               │
│  └────────┘  │                                               │
├──────────────┴──────────────────────────────────────────────┤
│                        FOOTER                                │
│  © Copyright Kokokah 2025 | License | Docs | Support        │
└─────────────────────────────────────────────────────────────┘
```

### Component Hierarchy
```
html
├─ head
│  ├─ meta (charset, viewport)
│  ├─ title
│  ├─ favicon
│  ├─ fonts (Fredoka, Inter)
│  ├─ stylesheets
│  │  ├─ Bootstrap 5.3.3
│  │  ├─ Font Awesome 6.5.0
│  │  ├─ style_theme.css
│  │  └─ dashboard.css
│  └─ scripts (Bootstrap, Chart.js)
│
└─ body
   ├─ #sidebarOverlay (hidden by default)
   ├─ #sidebar
   │  ├─ .brand (logo)
   │  ├─ #sidebarNav
   │  │  ├─ Dashboard link
   │  │  ├─ Class link
   │  │  ├─ Subject link
   │  │  ├─ Results link
   │  │  ├─ Kudikah link
   │  │  ├─ Notification link
   │  │  └─ Communication (collapsible)
   │  │     ├─ Announcement
   │  │     ├─ Email/Messaging
   │  │     └─ Feedback
   │  └─ .sidebar-footer
   │     ├─ Settings link
   │     └─ #profileSection
   │        ├─ #profileImage
   │        ├─ #userName
   │        ├─ #userRole
   │        └─ #logoutBtn
   ├─ header.topbar
   │  ├─ #hamburger (mobile)
   │  ├─ .search-wrap
   │  └─ .top-icons
   ├─ @yield('content')
   ├─ .page-footer
   └─ scripts
      ├─ Chart.js
      └─ Sidebar behavior
```

---

## DATA FLOW DIAGRAM

### Profile Data Flow
```
┌──────────────────┐
│  Page Load       │
└────────┬─────────┘
         │
         ▼
┌──────────────────────────────┐
│ DOMContentLoaded Event       │
└────────┬─────────────────────┘
         │
         ▼
┌──────────────────────────────┐
│ Dashboard Module Init        │ (Admin only)
│ (dashboard.js)               │
└────────┬─────────────────────┘
         │
         ▼
┌──────────────────────────────┐
│ API Call                     │
│ GET /api/users/profile       │
└────────┬─────────────────────┘
         │
         ▼
┌──────────────────────────────┐
│ Response Data                │
│ {name, role, photo_url}      │
└────────┬─────────────────────┘
         │
         ▼
┌──────────────────────────────┐
│ Update DOM Elements          │
│ #profileImage.src            │
│ #userName.textContent        │
│ #userRole.textContent        │
└────────┬─────────────────────┘
         │
         ▼
┌──────────────────────────────┐
│ UI Rendered                  │
│ Profile visible              │
└──────────────────────────────┘
```

### Navigation Active State Flow
```
┌──────────────────┐
│  Page Load       │
└────────┬─────────┘
         │
         ▼
┌──────────────────────────────┐
│ DOMContentLoaded Event       │
└────────┬─────────────────────┘
         │
         ▼
┌──────────────────────────────┐
│ setActiveNavigation()        │
└────────┬─────────────────────┘
         │
         ▼
┌──────────────────────────────┐
│ Get current path             │
│ window.location.pathname     │
└────────┬─────────────────────┘
         │
         ▼
┌──────────────────────────────┐
│ Clear all 'active' classes   │
└────────┬─────────────────────┘
         │
         ▼
┌──────────────────────────────┐
│ Loop through nav links       │
└────────┬─────────────────────┘
         │
         ▼
┌──────────────────────────────┐
│ Compare href with path       │
│ Exact match?                 │
└────────┬─────────────────────┘
         │
    ┌────┴────┐
    │          │
   YES        NO
    │          │
    ▼          ▼
┌──────┐   ┌──────┐
│ Add  │   │ Skip │
│active│   │      │
└──┬───┘   └──────┘
   │
   ▼
┌──────────────────────────────┐
│ If child link:               │
│ Open parent dropdown         │
└──────────────────────────────┘
```

### Mobile Sidebar Toggle Flow
```
┌──────────────────┐
│  User Action     │
└────────┬─────────┘
         │
    ┌────┴────────────────┐
    │                     │
    ▼                     ▼
┌──────────┐      ┌──────────────┐
│ Click    │      │ Click        │
│Hamburger │      │ Overlay      │
└────┬─────┘      └────┬─────────┘
     │                 │
     ▼                 ▼
┌──────────────┐  ┌──────────────┐
│openSidebar() │  │closeSidebar()│
└────┬─────────┘  └────┬─────────┘
     │                 │
     ▼                 ▼
┌──────────────────────────────┐
│ Add/Remove 'show' class      │
│ Toggle overlay visibility    │
│ Control body overflow        │
└──────────────────────────────┘
```

---

## EVENT LISTENER MAP

### Admin Template (dashboardtemp)
```
DOMContentLoaded
├─ setActiveNavigation()
├─ Chevron animation setup
└─ Dropdown exclusivity setup

popstate
└─ setActiveNavigation()

click (#hamburger)
└─ openSidebar()

click (#sidebarOverlay)
└─ closeSidebar()

click (.nav-item-link:not(.nav-parent))
└─ closeSidebar() [mobile only]

click (.nav-parent)
└─ Toggle dropdown + close others

show.bs.collapse
└─ Rotate chevron + change icon

hide.bs.collapse
└─ Reset chevron + change icon

resize (window)
└─ Reset sidebar state [>992px]
```

### Student Template (usertemplate)
```
DOMContentLoaded
└─ Sidebar behavior setup

click (#hamburger)
└─ openSidebar()

click (#sidebarOverlay)
└─ closeSidebar()

click (.nav-item-link)
└─ closeSidebar() [mobile only]

resize (window)
└─ Reset sidebar state [>992px]
```

---

## Responsive Breakpoints

### Bootstrap Grid System
```
xs: < 576px   (Mobile)
sm: ≥ 576px   (Mobile)
md: ≥ 768px   (Tablet)
lg: ≥ 992px   (Desktop) ← Used in templates
xl: ≥ 1200px  (Large Desktop)
xxl: ≥ 1400px (Extra Large)
```

### Template Behavior
```
< 992px (Mobile/Tablet)
├─ Sidebar hidden
├─ Hamburger visible
├─ Overlay visible when sidebar open
└─ Auto-close sidebar on nav click

≥ 992px (Desktop)
├─ Sidebar visible
├─ Hamburger hidden
├─ Overlay hidden
└─ Sidebar stays open
```

---

## CSS Classes Reference

### Sidebar Classes
- `.sidebar` - Main sidebar container
- `.sidebar.show` - Visible state (mobile)
- `.nav-group` - Navigation container
- `.nav-item-link` - Navigation link
- `.nav-item-link.active` - Active link
- `.nav-child` - Child navigation item
- `.nav-parent` - Parent toggle item
- `.collapse` - Collapsible menu
- `.collapse.show` - Expanded menu
- `.chevron-icon` - Chevron icon
- `.chevron-icon.rotate` - Rotated state

### Topbar Classes
- `.topbar` - Top navigation bar
- `.search-wrap` - Search container
- `.search-input` - Search input
- `.top-icons` - Icon buttons container
- `.icon-btn` - Icon button
- `.icon-btn-light` - Light icon button

### Profile Classes
- `.profile` - Profile section
- `.avatar` - Profile image
- `.sidebar-footer` - Footer section

### Overlay Classes
- `.overlay` - Sidebar overlay
- `.overlay.show` - Visible state

### Alert Classes
- `#alertContainer` - Alert container
- `.alert` - Alert element
- `.alert-success` - Success alert
- `.alert-danger` - Error alert
- `.alert-warning` - Warning alert
- `.alert-info` - Info alert

---

## Bootstrap Utilities Used

### Display
- `d-flex` - Flexbox
- `d-block` - Block display
- `d-lg-none` - Hide on desktop
- `d-none` - Hide element

### Spacing
- `p-3` - Padding
- `m-3` - Margin
- `mt-auto` - Margin top auto
- `gap-2` - Gap between flex items

### Alignment
- `justify-content-between` - Space between
- `align-items-center` - Vertical center
- `text-truncate` - Truncate text

### Text
- `fw-semibold` - Font weight
- `text-muted` - Muted color
- `small` - Small text
- `text-decoration-none` - Remove underline

### Responsive
- `d-lg-none` - Hide on lg+
- `pe-3` - Padding end (right)
- `me-2` - Margin end (right)

