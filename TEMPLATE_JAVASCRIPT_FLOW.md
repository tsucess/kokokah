# Template JavaScript Flow & Data Binding

## DASHBOARDTEMP.BLADE.PHP - Execution Flow

### 1. PAGE LOAD SEQUENCE

```
1. HTML Parsed
   ↓
2. CSS Loaded (style_theme.css, dashboard.css, access.css, loader.css)
   ↓
3. External Scripts Loaded (Bootstrap, Axios, Chart.js)
   ↓
4. DOMContentLoaded Event Fired
   ↓
5. Dashboard Module Imported & Initialized
   ↓
6. Navigation Setup Functions Execute
   ↓
7. Sidebar Behavior Initialized
   ↓
8. Page Ready for Interaction
```

### 2. INITIALIZATION SEQUENCE (Lines 215-225)

```javascript
// Check if DOM is still loading
if (document.readyState === 'loading') {
    // Wait for DOMContentLoaded
    document.addEventListener('DOMContentLoaded', () => {
        DashboardModule.init();
    });
} else {
    // DOM already loaded, initialize immediately
    DashboardModule.init();
}
```

**Why this pattern?**
- Script might load after DOM is ready
- Ensures DashboardModule.init() runs at right time
- Prevents race conditions

### 3. NAVIGATION SETUP (Lines 309-313)

```javascript
// Initial setup on page load
document.addEventListener('DOMContentLoaded', setActiveNavigation);

// Re-run when user navigates (SPA support)
window.addEventListener('popstate', setActiveNavigation);
```

**Triggers:**
- Page load
- Browser back/forward button
- SPA navigation

### 4. DROPDOWN SETUP (Lines 319-374)

```javascript
document.addEventListener('DOMContentLoaded', function() {
    // Setup chevron animations
    // Setup dropdown exclusivity
    // Setup event listeners
});
```

---

## PROFILE DATA BINDING

### Current State (Hardcoded)
```html
<h6 class="fw-semibold text-truncate" id="userName">Culacino_</h6>
<p class="small text-muted" id="userRole">UX Designer</p>
<img class="avatar" id="profileImage" src="images/winner-round.png">
```

### Expected Dynamic Binding

**Pattern 1: Direct API Call**
```javascript
// On page load
fetch('/api/users/profile')
    .then(res => res.json())
    .then(data => {
        document.getElementById('userName').textContent = data.name;
        document.getElementById('userRole').textContent = data.role;
        document.getElementById('profileImage').src = data.photo_url;
    });
```

**Pattern 2: Using Axios**
```javascript
axios.get('/api/users/profile')
    .then(response => {
        const user = response.data;
        document.getElementById('userName').textContent = user.name;
        document.getElementById('userRole').textContent = user.role;
        document.getElementById('profileImage').src = user.photo_url;
    })
    .catch(error => console.error('Profile load failed:', error));
```

**Pattern 3: From Dashboard Module**
```javascript
// In dashboard.js
export default {
    init() {
        this.loadUserProfile();
        this.loadDashboardStats();
    },
    
    loadUserProfile() {
        axios.get('/api/users/profile')
            .then(response => {
                this.updateProfileUI(response.data);
            });
    },
    
    updateProfileUI(user) {
        document.getElementById('userName').textContent = user.name;
        document.getElementById('userRole').textContent = user.role;
        document.getElementById('profileImage').src = user.photo_url;
    }
};
```

---

## LOGOUT FUNCTIONALITY

### Current State
```html
<a href="#" id="logoutBtn" title="Logout">
    <span><i class="fa-solid fa-arrow-right-from-bracket"></i></span>
</a>
```

### Expected Implementation

**Pattern 1: Simple Redirect**
```javascript
document.getElementById('logoutBtn').addEventListener('click', (e) => {
    e.preventDefault();
    window.location.href = '/logout';
});
```

**Pattern 2: API Call + Redirect**
```javascript
document.getElementById('logoutBtn').addEventListener('click', (e) => {
    e.preventDefault();
    
    axios.post('/api/logout')
        .then(() => {
            window.location.href = '/login';
        })
        .catch(error => {
            console.error('Logout failed:', error);
            window.location.href = '/login'; // Force redirect anyway
        });
});
```

**Pattern 3: With Confirmation**
```javascript
document.getElementById('logoutBtn').addEventListener('click', (e) => {
    e.preventDefault();
    
    if (confirm('Are you sure you want to logout?')) {
        axios.post('/api/logout')
            .then(() => {
                window.location.href = '/login';
            });
    }
});
```

---

## ACTIVE NAVIGATION FLOW

### Step-by-Step Execution

```
1. setActiveNavigation() called
   ↓
2. Get current path: window.location.pathname
   Example: "/students"
   ↓
3. Clear all 'active' classes
   ↓
4. Loop through all nav links
   ↓
5. For each link:
   a. Get href attribute
   b. Skip if href starts with '#' (dropdown toggles)
   c. Compare href with currentPath
   d. If exact match:
      - Add 'active' class
      - If child link, open parent dropdown
   ↓
6. Special case check:
   If path is '/dashboard' or '/'
   → Add 'active' to #dashboardLink
   ↓
7. Done
```

### Code Flow

```javascript
function setActiveNavigation() {
    const currentPath = window.location.pathname; // "/students"
    
    // Clear previous
    document.querySelectorAll('.nav-item-link, .nav-child')
        .forEach(link => link.classList.remove('active'));
    
    // Find and activate matching link
    document.querySelectorAll('.nav-item-link, .nav-child')
        .forEach(link => {
            const href = link.getAttribute('href');
            
            if (!href || href.startsWith('#')) return; // Skip toggles
            
            if (href === currentPath) { // Exact match
                link.classList.add('active');
                
                // If child, open parent
                if (link.classList.contains('nav-child')) {
                    const parentMenu = link.closest('.collapse');
                    if (parentMenu) {
                        const bsCollapse = new bootstrap.Collapse(parentMenu, {
                            toggle: false
                        });
                        bsCollapse.show();
                    }
                }
            }
        });
    
    // Special case
    if (currentPath === '/dashboard' || currentPath === '/') {
        document.getElementById('dashboardLink')?.classList.add('active');
    }
}
```

---

## DROPDOWN EXCLUSIVITY FLOW

### When Parent Clicked

```
1. Click event on .nav-parent
   ↓
2. Prevent default link behavior
   ↓
3. Get target dropdown ID
   ↓
4. Check if already open
   ↓
5. Close ALL other open dropdowns
   ↓
6. Toggle current dropdown
   - If was open → close it
   - If was closed → open it
   ↓
7. Update chevron icon
```

### Code Flow

```javascript
parent.addEventListener('click', function(e) {
    e.preventDefault();
    const targetId = this.getAttribute('href'); // "#usersMenu"
    const target = document.querySelector(targetId);
    
    if (!target) return;
    
    const isCurrentOpen = target.classList.contains('show');
    
    // Close all others
    document.querySelectorAll('.collapse').forEach(collapse => {
        if (collapse.id !== targetId && collapse.classList.contains('show')) {
            const bsCollapse = bootstrap.Collapse.getInstance(collapse);
            if (bsCollapse) {
                bsCollapse.hide();
            }
        }
    });
    
    // Toggle current
    const bsCollapse = bootstrap.Collapse.getInstance(target) || 
                       new bootstrap.Collapse(target, { toggle: false });
    
    if (isCurrentOpen) {
        bsCollapse.hide();
    } else {
        bsCollapse.show();
    }
});
```

---

## MOBILE SIDEBAR FLOW

### Opening Sidebar

```
1. User clicks hamburger button
   ↓
2. openSidebar() called
   ↓
3. Add 'show' class to sidebar
   ↓
4. Add 'show' class to overlay
   ↓
5. Set body overflow to 'hidden'
   ↓
6. Sidebar slides in, overlay appears
```

### Closing Sidebar

```
1. User clicks overlay OR nav link
   ↓
2. closeSidebar() called
   ↓
3. Remove 'show' class from sidebar
   ↓
4. Remove 'show' class from overlay
   ↓
5. Reset body overflow
   ↓
6. Sidebar slides out, overlay disappears
```

### Window Resize Handling

```
1. Window resized
   ↓
2. Check if width >= 992px (desktop)
   ↓
3. If yes:
   - Remove 'show' from sidebar
   - Remove 'show' from overlay
   - Reset body overflow
   ↓
4. Ensures clean state on desktop
```

---

## USERTEMPLATE.BLADE.PHP - Simplified Flow

### Initialization (Lines 149-188)

```javascript
// Only sidebar behavior, no navigation logic
document.addEventListener('DOMContentLoaded', function() {
    // Setup sidebar toggle
    // Setup mobile behavior
    // Setup window resize handler
});
```

### Key Differences

| Feature | Admin | Student |
|---------|-------|---------|
| Profile binding | Via DashboardModule | Manual implementation needed |
| Active nav | Automatic | Not implemented |
| Dropdown logic | Complex | Simple Bootstrap toggle |
| Chevron animation | Yes | No |
| Loading overlay | Yes | No |
| Alert container | Yes | No |

---

## DATA FLOW DIAGRAM

```
User Profile Data
    ↓
API Endpoint (/api/users/profile)
    ↓
JavaScript (Axios/Fetch)
    ↓
DOM Elements
    ├─ #profileImage.src
    ├─ #userName.textContent
    ├─ #userRole.textContent
    └─ #logoutBtn.onclick
    ↓
UI Update
```

---

## COMMON PATTERNS TO IMPLEMENT

### 1. Profile Loading
```javascript
async function loadProfile() {
    try {
        const response = await axios.get('/api/users/profile');
        updateProfileUI(response.data);
    } catch (error) {
        console.error('Failed to load profile:', error);
    }
}
```

### 2. Error Handling
```javascript
function showAlert(message, type = 'danger') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.getElementById('alertContainer').appendChild(alert);
}
```

### 3. Loading State
```javascript
function showLoading(show = true) {
    const overlay = document.getElementById('loadingOverlay');
    overlay.style.display = show ? 'flex' : 'none';
}
```

