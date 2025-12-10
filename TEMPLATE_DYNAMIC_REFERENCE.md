# Template Dynamic Functionality - Technical Reference

## DASHBOARDTEMP.BLADE.PHP - Detailed Breakdown

### 1. PROFILE SECTION DYNAMICS

**HTML Elements:**
```html
<img class="avatar" id="profileImage" src="images/winner-round.png">
<h6 id="userName">Culacino_</h6>
<p id="userRole">UX Designer</p>
<a href="#" id="logoutBtn">Logout</a>
```

**Expected JavaScript Updates:**
- `#profileImage.src` - User's profile photo URL
- `#userName.textContent` - User's full name
- `#userRole.textContent` - User's role/position
- `#logoutBtn.onclick` - Logout handler

**Data Source:** Likely from `/api/users/profile` or similar endpoint

---

### 2. NAVIGATION ACTIVE STATE (Admin Only)

**Function:** `setActiveNavigation()` (Lines 268-307)

**Algorithm:**
1. Get current path: `window.location.pathname`
2. Clear all previous 'active' classes
3. Loop through all nav links
4. Compare link `href` with current path
5. If exact match:
   - Add 'active' class to link
   - If child link, open parent dropdown
6. Special case: `/dashboard` or `/` → activate dashboard link

**Triggers:**
- Page load: `DOMContentLoaded`
- Page change: `popstate` event (for SPAs)

**Example:**
```
Current URL: /students
→ Find link with href="/students"
→ Add 'active' class
→ Open parent dropdown (#usersMenu)
```

---

### 3. DROPDOWN MANAGEMENT (Admin Only)

**Chevron Animation (Lines 318-341):**
```javascript
target.addEventListener('show.bs.collapse', () => {
    icon.classList.add('rotate');
    icon.classList.remove('fa-chevron-down');
    icon.classList.add('fa-chevron-up');
});
```

**Exclusivity Logic (Lines 343-374):**
- When parent clicked:
  1. Close all other open dropdowns
  2. Toggle current dropdown
  3. Uses Bootstrap Collapse API

**Bootstrap Collapse Methods:**
- `new bootstrap.Collapse(target, {toggle: false})`
- `.show()` - Open dropdown
- `.hide()` - Close dropdown
- `.getInstance(element)` - Get existing instance

---

### 4. MOBILE SIDEBAR BEHAVIOR

**Functions:**
```javascript
openSidebar() {
    sidebar.classList.add('show');
    overlay.classList.add('show');
    document.body.style.overflow = 'hidden';
}

closeSidebar() {
    sidebar.classList.remove('show');
    overlay.classList.remove('show');
    document.body.style.overflow = '';
}
```

**Event Listeners:**
- Hamburger click → `openSidebar()`
- Overlay click → `closeSidebar()`
- Nav link click (mobile) → `closeSidebar()`
- Window resize (>992px) → Reset state

**CSS Classes:**
- `.show` - Makes sidebar visible
- `.overlay.show` - Makes overlay visible

---

### 5. LOADING OVERLAY

**HTML:**
```html
<div id="loadingOverlay" style="display: none; ...">
    <div class="spinner-border text-light">
```

**Usage Pattern:**
```javascript
// Show loading
document.getElementById('loadingOverlay').style.display = 'flex';

// Hide loading
document.getElementById('loadingOverlay').style.display = 'none';
```

**Z-Index Stack:**
- Loading Overlay: 9998
- Alert Container: 9999
- Sidebar: default
- Topbar: default

---

### 6. ALERT CONTAINER

**HTML:**
```html
<div id="alertContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
```

**Usage Pattern:**
```javascript
// Create alert
const alert = document.createElement('div');
alert.className = 'alert alert-success';
alert.textContent = 'Success message';
document.getElementById('alertContainer').appendChild(alert);

// Auto-remove after 3 seconds
setTimeout(() => alert.remove(), 3000);
```

---

## USERTEMPLATE.BLADE.PHP - Detailed Breakdown

### 1. SIMPLIFIED SIDEBAR BEHAVIOR

**Differences from Admin:**
- No active navigation detection
- No dropdown chevron animation
- No dropdown exclusivity
- Single collapsible menu (Communication)

**Mobile Behavior:**
```javascript
// Close sidebar on ANY nav link click
document.querySelectorAll('.nav-item-link').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth < 992) closeSidebar();
    });
});
```

**Admin Version:**
```javascript
// Only close on non-parent links
document.querySelectorAll('.nav-item-link:not(.nav-parent)').forEach(link => {
    // ...
});
```

---

### 2. COMMUNICATION DROPDOWN

**HTML:**
```html
<a class="nav-item-link d-flex justify-content-between align-items-center"
   data-bs-toggle="collapse" href="#communication">
    <span><i class="fa-solid fa-comments me-2 pe-2"></i> Communication</span>
    <i class="fa-solid fa-chevron-down small"></i>
</a>

<div class="collapse ps-4" id="communication">
    <a class="nav-item-link d-block" href="#">Announcement</a>
    <a class="nav-item-link d-block" href="#">Email / Messaging Center</a>
    <a class="nav-item-link d-block" href="/userfeedback">Feedback / Surveys</a>
</div>
```

**Bootstrap Collapse:**
- `data-bs-toggle="collapse"` - Enables toggle
- `href="#communication"` - Target ID
- `.collapse` - Hidden by default
- `.show` - Added when expanded

---

## SHARED PATTERNS

### 1. Responsive Breakpoint
```javascript
if (window.innerWidth < 992) {
    // Mobile behavior
} else {
    // Desktop behavior
}
```

**Bootstrap Breakpoint:** `lg` = 992px

### 2. Event Delegation
```javascript
document.querySelectorAll('.nav-item-link').forEach(link => {
    link.addEventListener('click', handler);
});
```

### 3. Class Manipulation
```javascript
element.classList.add('active');
element.classList.remove('active');
element.classList.toggle('active');
element.classList.contains('active');
```

---

## INTEGRATION WITH JAVASCRIPT MODULES

### Dashboard Module (Admin Only)
```javascript
import DashboardModule from '{{ asset('js/dashboard.js') }}';

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        DashboardModule.init();
    });
} else {
    DashboardModule.init();
}
```

**Expected Module Structure:**
```javascript
export default {
    init() {
        // Initialize dashboard
        // Load stats, charts, etc.
    }
};
```

---

## COMMON ISSUES & SOLUTIONS

### Issue: Profile not updating
**Solution:** Check if JavaScript is loading user data via API

### Issue: Sidebar not closing on mobile
**Solution:** Verify window.innerWidth check and 'show' class removal

### Issue: Active nav not highlighting
**Solution:** Ensure exact path match and proper href attributes

### Issue: Dropdowns not toggling
**Solution:** Check Bootstrap Collapse initialization and event listeners

---

## TESTING CHECKLIST

- [ ] Profile image loads correctly
- [ ] User name displays
- [ ] User role displays
- [ ] Logout button works
- [ ] Mobile sidebar opens/closes
- [ ] Overlay appears/disappears
- [ ] Nav links close sidebar (mobile)
- [ ] Active nav highlights correctly (admin)
- [ ] Dropdowns toggle (admin)
- [ ] Chevron rotates (admin)
- [ ] Only one dropdown open (admin)
- [ ] Loading overlay shows/hides
- [ ] Alerts display in container
- [ ] Responsive at 992px breakpoint

