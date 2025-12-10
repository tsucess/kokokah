# Template Quick Reference Card

## File Locations
```
Admin:    resources/views/layouts/dashboardtemp.blade.php (386 lines)
Student:  resources/views/layouts/usertemplate.blade.php (192 lines)
```

---

## Key HTML Elements

### Profile Section (Both)
```html
<img id="profileImage" src="images/winner-round.png">
<h6 id="userName">Culacino_</h6>
<p id="userRole">UX Designer</p>
<a id="logoutBtn" href="#">Logout</a>
```

### Sidebar (Both)
```html
<aside class="sidebar" id="sidebar">
<div class="overlay" id="sidebarOverlay"></div>
<button id="hamburger">☰</button>
```

### Admin Only
```html
<div id="loadingOverlay"><!-- Loading spinner --></div>
<div id="alertContainer"><!-- Toast notifications --></div>
```

---

## Navigation Structure

### Admin (dashboardtemp)
```
Dashboard
Users Management (collapsible)
  ├─ All Users
  ├─ Students
  ├─ Instructors
  ├─ Add Users
  └─ Users Activity Log
Course Management (collapsible)
  ├─ All Courses
  ├─ Create New Course
  ├─ Course Categories
  ├─ Curriculum Categories
  ├─ Levels & Classes
  ├─ Academic Terms
  ├─ Course Reviews & Rating
  └─ Course Approval
Payments & Transactions (collapsible)
Reports & Analytics (collapsible)
Communication (collapsible)
```

### Student (usertemplate)
```
Dashboard
Class
Subject
Results & Scoring
Kudikah
Notification
Communication (collapsible)
  ├─ Announcement
  ├─ Email/Messaging Center
  └─ Feedback/Surveys
```

---

## JavaScript Functions to Implement

### Profile Loading
```javascript
// Load user profile from API
axios.get('/api/users/profile')
    .then(res => {
        document.getElementById('profileImage').src = res.data.photo_url;
        document.getElementById('userName').textContent = res.data.name;
        document.getElementById('userRole').textContent = res.data.role;
    });
```

### Logout Handler
```javascript
document.getElementById('logoutBtn').addEventListener('click', (e) => {
    e.preventDefault();
    axios.post('/api/logout').then(() => {
        window.location.href = '/login';
    });
});
```

### Show Alert (Admin)
```javascript
function showAlert(message, type = 'info') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.textContent = message;
    document.getElementById('alertContainer').appendChild(alert);
    setTimeout(() => alert.remove(), 3000);
}
```

### Show Loading (Admin)
```javascript
function showLoading(show = true) {
    document.getElementById('loadingOverlay').style.display = 
        show ? 'flex' : 'none';
}
```

---

## CSS Classes

### Navigation
- `.nav-item-link` - Nav link
- `.nav-item-link.active` - Active link
- `.nav-child` - Child item
- `.nav-parent` - Parent toggle
- `.collapse` - Collapsible menu
- `.collapse.show` - Expanded menu

### Sidebar
- `.sidebar` - Sidebar container
- `.sidebar.show` - Visible (mobile)
- `.overlay` - Overlay
- `.overlay.show` - Visible overlay

### Alerts
- `.alert` - Alert container
- `.alert-success` - Success
- `.alert-danger` - Error
- `.alert-warning` - Warning
- `.alert-info` - Info

---

## Event Listeners (Admin)

| Event | Element | Handler |
|-------|---------|---------|
| click | #hamburger | openSidebar() |
| click | #sidebarOverlay | closeSidebar() |
| click | .nav-item-link | closeSidebar() (mobile) |
| click | .nav-parent | Toggle dropdown |
| DOMContentLoaded | document | setActiveNavigation() |
| popstate | window | setActiveNavigation() |
| resize | window | Reset sidebar state |
| show.bs.collapse | .collapse | Rotate chevron |
| hide.bs.collapse | .collapse | Reset chevron |

---

## Event Listeners (Student)

| Event | Element | Handler |
|-------|---------|---------|
| click | #hamburger | openSidebar() |
| click | #sidebarOverlay | closeSidebar() |
| click | .nav-item-link | closeSidebar() (mobile) |
| resize | window | Reset sidebar state |

---

## Bootstrap Breakpoints

```
xs: < 576px
sm: ≥ 576px
md: ≥ 768px
lg: ≥ 992px  ← Used in templates
xl: ≥ 1200px
xxl: ≥ 1400px
```

---

## Z-Index Stack

```
9999 ─ Alert Container
9998 ─ Loading Overlay
1000 ─ Topbar
 500 ─ Sidebar
 100 ─ Overlay
   0 ─ Content
```

---

## API Endpoints (Expected)

```
GET  /api/users/profile      - Load user profile
POST /api/logout             - Logout user
GET  /api/dashboard/stats    - Dashboard statistics
```

---

## External Libraries

```
Bootstrap 5.3.3
Font Awesome 6.5.0
Chart.js 4.4.3
Axios (HTTP client)
```

---

## CSS Files

```
css/style_theme.css    - Theme colors
css/dashboard.css      - Layout styles
css/access.css         - Access control (admin)
css/loader.css         - Loading animation (admin)
```

---

## JavaScript Files

```
js/dashboard.js                    - Dashboard module (admin)
js/utils/kokokahLoader.js          - Logo loader (admin)
js/utils/confirmationModal.js      - Modals (admin)
```

---

## Mobile Behavior

### < 992px (Mobile)
- Sidebar hidden
- Hamburger visible
- Overlay visible when sidebar open
- Auto-close sidebar on nav click
- Body overflow hidden

### ≥ 992px (Desktop)
- Sidebar visible
- Hamburger hidden
- Overlay hidden
- Sidebar stays open
- Body overflow normal

---

## Common Patterns

### Async/Await
```javascript
async function loadData() {
    try {
        const res = await axios.get('/api/endpoint');
        // Handle success
    } catch (error) {
        // Handle error
    }
}
```

### Event Delegation
```javascript
document.querySelectorAll('.selector').forEach(el => {
    el.addEventListener('event', handler);
});
```

### Class Toggle
```javascript
element.classList.add('class');
element.classList.remove('class');
element.classList.toggle('class');
```

### Bootstrap Collapse
```javascript
const collapse = new bootstrap.Collapse(element, {toggle: false});
collapse.show();
collapse.hide();
```

---

## Debugging Tips

### Check Profile Loading
```javascript
// In browser console
axios.get('/api/users/profile').then(r => console.log(r.data));
```

### Check Active Navigation
```javascript
// In browser console
console.log(window.location.pathname);
document.querySelectorAll('.nav-item-link.active');
```

### Check Sidebar State
```javascript
// In browser console
document.getElementById('sidebar').classList.contains('show');
```

### Check Alert Container
```javascript
// In browser console
document.getElementById('alertContainer').innerHTML;
```

---

## Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Profile not updating | Check API endpoint, verify response data |
| Logout not working | Check API endpoint, verify click listener |
| Sidebar not closing | Check window.innerWidth, verify 'show' class |
| Active nav not highlighting | Check exact path match, verify href attributes |
| Dropdowns not toggling | Check Bootstrap Collapse initialization |
| Loading overlay stuck | Check showLoading() calls, add timeout |
| Alerts not showing | Check alertContainer exists, verify append |
| Mobile sidebar broken | Check CSS transitions, verify event listeners |

---

## Testing Checklist

- [ ] Profile loads on page load
- [ ] User name displays correctly
- [ ] User role displays correctly
- [ ] Profile image loads
- [ ] Logout button works
- [ ] Mobile sidebar opens
- [ ] Mobile sidebar closes
- [ ] Overlay appears/disappears
- [ ] Nav links close sidebar (mobile)
- [ ] Active nav highlights (admin)
- [ ] Dropdowns toggle (admin)
- [ ] Only one dropdown open (admin)
- [ ] Chevron rotates (admin)
- [ ] Loading overlay shows/hides (admin)
- [ ] Alerts display (admin)
- [ ] Responsive at 992px
- [ ] No console errors
- [ ] No memory leaks

---

## Quick Start Implementation

1. **Add profile loading to dashboard.js**
   ```javascript
   axios.get('/api/users/profile').then(res => {
       // Update profile UI
   });
   ```

2. **Add logout listener**
   ```javascript
   document.getElementById('logoutBtn').addEventListener('click', (e) => {
       e.preventDefault();
       axios.post('/api/logout').then(() => {
           window.location.href = '/login';
       });
   });
   ```

3. **Add alert function**
   ```javascript
   function showAlert(msg, type = 'info') {
       const alert = document.createElement('div');
       alert.className = `alert alert-${type}`;
       alert.textContent = msg;
       document.getElementById('alertContainer').appendChild(alert);
       setTimeout(() => alert.remove(), 3000);
   }
   ```

4. **Test everything**
   - Load page
   - Check profile
   - Test logout
   - Test mobile sidebar
   - Check console for errors

