# Template Implementation Examples

## 1. PROFILE DATA BINDING

### Example 1: Basic Fetch API
```javascript
// Add to dashboard.js or inline script
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('/api/users/profile');
        const user = await response.json();
        
        document.getElementById('profileImage').src = user.photo_url || 'images/winner-round.png';
        document.getElementById('userName').textContent = user.name || 'User';
        document.getElementById('userRole').textContent = user.role || 'Student';
    } catch (error) {
        console.error('Failed to load profile:', error);
    }
});
```

### Example 2: Using Axios
```javascript
// Add to dashboard.js
document.addEventListener('DOMContentLoaded', () => {
    axios.get('/api/users/profile')
        .then(response => {
            const user = response.data;
            document.getElementById('profileImage').src = user.photo_url;
            document.getElementById('userName').textContent = user.name;
            document.getElementById('userRole').textContent = user.role;
        })
        .catch(error => {
            console.error('Profile load error:', error);
            showAlert('Failed to load profile', 'danger');
        });
});
```

### Example 3: Module Pattern
```javascript
// dashboard.js
export default {
    init() {
        this.loadUserProfile();
    },
    
    loadUserProfile() {
        axios.get('/api/users/profile')
            .then(response => this.updateProfileUI(response.data))
            .catch(error => this.handleError(error));
    },
    
    updateProfileUI(user) {
        const profileImage = document.getElementById('profileImage');
        const userName = document.getElementById('userName');
        const userRole = document.getElementById('userRole');
        
        if (profileImage) profileImage.src = user.photo_url;
        if (userName) userName.textContent = user.name;
        if (userRole) userRole.textContent = user.role;
    },
    
    handleError(error) {
        console.error('Error:', error);
        showAlert('An error occurred', 'danger');
    }
};
```

---

## 2. LOGOUT FUNCTIONALITY

### Example 1: Simple Redirect
```javascript
document.getElementById('logoutBtn').addEventListener('click', (e) => {
    e.preventDefault();
    window.location.href = '/logout';
});
```

### Example 2: API Call + Redirect
```javascript
document.getElementById('logoutBtn').addEventListener('click', (e) => {
    e.preventDefault();
    
    axios.post('/api/logout')
        .then(() => {
            window.location.href = '/login';
        })
        .catch(error => {
            console.error('Logout failed:', error);
            // Force redirect anyway
            window.location.href = '/login';
        });
});
```

### Example 3: With Confirmation
```javascript
document.getElementById('logoutBtn').addEventListener('click', (e) => {
    e.preventDefault();
    
    if (confirm('Are you sure you want to logout?')) {
        showLoading(true);
        
        axios.post('/api/logout')
            .then(() => {
                window.location.href = '/login';
            })
            .catch(error => {
                showLoading(false);
                showAlert('Logout failed. Please try again.', 'danger');
            });
    }
});
```

### Example 4: With Toast Notification
```javascript
document.getElementById('logoutBtn').addEventListener('click', (e) => {
    e.preventDefault();
    
    showAlert('Logging out...', 'info');
    
    axios.post('/api/logout')
        .then(() => {
            showAlert('Logged out successfully', 'success');
            setTimeout(() => {
                window.location.href = '/login';
            }, 1000);
        })
        .catch(error => {
            showAlert('Logout failed', 'danger');
        });
});
```

---

## 3. ALERT/TOAST SYSTEM

### Example 1: Basic Toast
```javascript
function showAlert(message, type = 'info') {
    const alertContainer = document.getElementById('alertContainer');
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.appendChild(alert);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        alert.remove();
    }, 3000);
}

// Usage
showAlert('Profile updated successfully', 'success');
showAlert('An error occurred', 'danger');
```

### Example 2: Advanced Toast with Icon
```javascript
function showAlert(message, type = 'info', duration = 3000) {
    const alertContainer = document.getElementById('alertContainer');
    
    const icons = {
        success: 'fa-check-circle',
        danger: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show d-flex align-items-center`;
    alert.innerHTML = `
        <i class="fa-solid ${icons[type]} me-2"></i>
        <span>${message}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.appendChild(alert);
    
    if (duration > 0) {
        setTimeout(() => alert.remove(), duration);
    }
}
```

### Example 3: Toast with Progress Bar
```javascript
function showAlert(message, type = 'info', duration = 3000) {
    const alertContainer = document.getElementById('alertContainer');
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <div class="progress" style="height: 2px; margin-top: 8px;">
            <div class="progress-bar" style="width: 100%;"></div>
        </div>
    `;
    
    alertContainer.appendChild(alert);
    
    const progressBar = alert.querySelector('.progress-bar');
    const startTime = Date.now();
    
    const animate = () => {
        const elapsed = Date.now() - startTime;
        const progress = 100 - (elapsed / duration) * 100;
        progressBar.style.width = progress + '%';
        
        if (progress > 0) {
            requestAnimationFrame(animate);
        } else {
            alert.remove();
        }
    };
    
    animate();
}
```

---

## 4. LOADING STATE MANAGEMENT

### Example 1: Simple Loading Overlay
```javascript
function showLoading(show = true) {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = show ? 'flex' : 'none';
    }
}

// Usage
showLoading(true);
// ... do something ...
showLoading(false);
```

### Example 2: Loading with Timeout
```javascript
function showLoading(show = true, timeout = 0) {
    const overlay = document.getElementById('loadingOverlay');
    if (!overlay) return;
    
    overlay.style.display = show ? 'flex' : 'none';
    
    if (show && timeout > 0) {
        setTimeout(() => {
            overlay.style.display = 'none';
        }, timeout);
    }
}

// Usage
showLoading(true, 5000); // Auto-hide after 5 seconds
```

### Example 3: Loading with Message
```javascript
function showLoading(show = true, message = 'Loading...') {
    const overlay = document.getElementById('loadingOverlay');
    if (!overlay) return;
    
    if (show) {
        overlay.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-light mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-light">${message}</p>
            </div>
        `;
        overlay.style.display = 'flex';
    } else {
        overlay.style.display = 'none';
    }
}
```

---

## 5. API CALL PATTERNS

### Example 1: Basic API Call with Loading
```javascript
async function loadDashboardData() {
    showLoading(true);
    
    try {
        const response = await axios.get('/api/dashboard/stats');
        updateDashboard(response.data);
        showAlert('Data loaded successfully', 'success');
    } catch (error) {
        console.error('Error:', error);
        showAlert('Failed to load data', 'danger');
    } finally {
        showLoading(false);
    }
}
```

### Example 2: API Call with Retry
```javascript
async function loadDataWithRetry(url, maxRetries = 3) {
    for (let i = 0; i < maxRetries; i++) {
        try {
            showLoading(true);
            const response = await axios.get(url);
            showLoading(false);
            return response.data;
        } catch (error) {
            if (i === maxRetries - 1) {
                showLoading(false);
                showAlert('Failed to load data after retries', 'danger');
                throw error;
            }
            // Wait before retry
            await new Promise(resolve => setTimeout(resolve, 1000));
        }
    }
}
```

### Example 3: Batch API Calls
```javascript
async function loadAllData() {
    showLoading(true);
    
    try {
        const [profile, stats, notifications] = await Promise.all([
            axios.get('/api/users/profile'),
            axios.get('/api/dashboard/stats'),
            axios.get('/api/notifications')
        ]);
        
        updateProfileUI(profile.data);
        updateDashboard(stats.data);
        updateNotifications(notifications.data);
        
        showAlert('All data loaded', 'success');
    } catch (error) {
        console.error('Error:', error);
        showAlert('Failed to load data', 'danger');
    } finally {
        showLoading(false);
    }
}
```

---

## 6. FORM SUBMISSION WITH API

### Example 1: Simple Form Submit
```javascript
document.getElementById('profileForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    showLoading(true);
    
    try {
        const response = await axios.put('/api/users/profile', formData);
        showAlert('Profile updated successfully', 'success');
        updateProfileUI(response.data);
    } catch (error) {
        showAlert('Failed to update profile', 'danger');
    } finally {
        showLoading(false);
    }
});
```

### Example 2: Form with Validation
```javascript
document.getElementById('profileForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const form = e.target;
    const name = form.querySelector('[name="name"]').value.trim();
    const email = form.querySelector('[name="email"]').value.trim();
    
    // Validation
    if (!name) {
        showAlert('Name is required', 'warning');
        return;
    }
    
    if (!email.includes('@')) {
        showAlert('Valid email is required', 'warning');
        return;
    }
    
    showLoading(true);
    
    try {
        const formData = new FormData(form);
        const response = await axios.put('/api/users/profile', formData);
        showAlert('Profile updated', 'success');
    } catch (error) {
        showAlert('Update failed', 'danger');
    } finally {
        showLoading(false);
    }
});
```

---

## 7. NAVIGATION ACTIVE STATE (Admin)

### Example: Enhanced Active State
```javascript
function setActiveNavigation() {
    const currentPath = window.location.pathname;
    
    // Clear previous
    document.querySelectorAll('.nav-item-link, .nav-child')
        .forEach(link => link.classList.remove('active'));
    
    // Find matching link
    document.querySelectorAll('.nav-item-link, .nav-child')
        .forEach(link => {
            const href = link.getAttribute('href');
            
            if (!href || href.startsWith('#')) return;
            
            if (href === currentPath) {
                link.classList.add('active');
                
                // Open parent if child
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

// Initialize
document.addEventListener('DOMContentLoaded', setActiveNavigation);
window.addEventListener('popstate', setActiveNavigation);
```

---

## 8. MOBILE SIDEBAR ENHANCEMENT

### Example: Smooth Transitions
```javascript
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
const hamburger = document.getElementById('hamburger');

// Add CSS transitions
sidebar.style.transition = 'transform 0.3s ease-in-out';
overlay.style.transition = 'opacity 0.3s ease-in-out';

function openSidebar() {
    sidebar.classList.add('show');
    overlay.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    sidebar.classList.remove('show');
    overlay.classList.remove('show');
    document.body.style.overflow = '';
}

hamburger.addEventListener('click', openSidebar);
overlay.addEventListener('click', closeSidebar);

// Close on nav click (mobile)
document.querySelectorAll('.nav-item-link:not(.nav-parent)').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth < 992) closeSidebar();
    });
});

// Reset on resize
window.addEventListener('resize', () => {
    if (window.innerWidth >= 992) {
        overlay.classList.remove('show');
        sidebar.classList.remove('show');
        document.body.style.overflow = '';
    }
});
```

---

## 9. ERROR HANDLING UTILITY

### Example: Comprehensive Error Handler
```javascript
function handleError(error, defaultMessage = 'An error occurred') {
    console.error('Error:', error);
    
    let message = defaultMessage;
    
    if (error.response) {
        // Server responded with error status
        const status = error.response.status;
        const data = error.response.data;
        
        if (status === 401) {
            message = 'Unauthorized. Please login again.';
            setTimeout(() => window.location.href = '/login', 2000);
        } else if (status === 403) {
            message = 'You do not have permission to perform this action.';
        } else if (status === 404) {
            message = 'Resource not found.';
        } else if (status === 422) {
            message = data.message || 'Validation failed.';
        } else if (status >= 500) {
            message = 'Server error. Please try again later.';
        } else {
            message = data.message || defaultMessage;
        }
    } else if (error.request) {
        // Request made but no response
        message = 'No response from server. Check your connection.';
    } else {
        // Error in request setup
        message = error.message || defaultMessage;
    }
    
    showAlert(message, 'danger');
}
```

---

## 10. COMPLETE INTEGRATION EXAMPLE

### dashboard.js - Full Module
```javascript
export default {
    init() {
        this.setupEventListeners();
        this.loadUserProfile();
        this.loadDashboardStats();
    },
    
    setupEventListeners() {
        document.getElementById('logoutBtn')?.addEventListener('click', 
            (e) => this.handleLogout(e));
    },
    
    loadUserProfile() {
        axios.get('/api/users/profile')
            .then(res => this.updateProfileUI(res.data))
            .catch(err => this.handleError(err, 'Failed to load profile'));
    },
    
    loadDashboardStats() {
        showLoading(true);
        axios.get('/api/dashboard/stats')
            .then(res => this.updateDashboard(res.data))
            .catch(err => this.handleError(err, 'Failed to load stats'))
            .finally(() => showLoading(false));
    },
    
    updateProfileUI(user) {
        document.getElementById('profileImage').src = user.photo_url;
        document.getElementById('userName').textContent = user.name;
        document.getElementById('userRole').textContent = user.role;
    },
    
    updateDashboard(stats) {
        // Update dashboard content
        console.log('Dashboard stats:', stats);
    },
    
    handleLogout(e) {
        e.preventDefault();
        showLoading(true);
        
        axios.post('/api/logout')
            .then(() => {
                showAlert('Logged out successfully', 'success');
                setTimeout(() => window.location.href = '/login', 1000);
            })
            .catch(err => this.handleError(err, 'Logout failed'))
            .finally(() => showLoading(false));
    },
    
    handleError(error, message) {
        console.error(message, error);
        showAlert(message, 'danger');
    }
};
```

