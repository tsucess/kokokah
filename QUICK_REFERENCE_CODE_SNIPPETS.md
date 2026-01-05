# Quick Reference - Code Snippets

## 1️⃣ NotificationApiClient.js

```javascript
class NotificationApiClient extends BaseApiClient {
  static async getNotifications(filters = {}) {
    const params = new URLSearchParams();
    if (filters.status) params.append('status', filters.status);
    if (filters.type) params.append('type', filters.type);
    
    const queryString = params.toString();
    const endpoint = queryString ? `/users/notifications?${queryString}` : '/users/notifications';
    return this.get(endpoint);
  }

  static async getUnreadCount() {
    const response = await this.getNotifications({ status: 'unread' });
    if (response.success && response.data) {
      return Array.isArray(response.data) ? response.data.length : 0;
    }
    return 0;
  }

  static async markAsRead(notificationId) {
    return this.put(`/users/notifications/${notificationId}/read`, {});
  }

  static async markAllAsRead() {
    return this.put('/users/notifications/read-all', {});
  }
}

window.NotificationApiClient = NotificationApiClient;
```

---

## 2️⃣ Dashboard.js - Add to init()

```javascript
static init() {
  this.initLogout();
  this.initProfileNavigation();
  this.initTooltips();
  this.loadUserProfile();
  this.loadPointsAndBadges();
  this.initNotificationBell();  // ADD THIS
  this.highlightActivePage();
}
```

---

## 3️⃣ Dashboard.js - New Methods

```javascript
static async initNotificationBell() {
  const bellBtn = document.querySelector('.top-icons button:first-child');
  if (!bellBtn) return;
  
  bellBtn.style.position = 'relative';
  await this.loadNotifications();
  bellBtn.addEventListener('click', () => this.openNotificationModal());
  
  // Auto-refresh every 60 seconds
  setInterval(() => this.loadNotifications(), 60000);
}

static async loadNotifications() {
  try {
    const count = await window.NotificationApiClient.getUnreadCount();
    this.updateNotificationBadge(count);
  } catch (error) {
    console.error('Error loading notifications:', error);
  }
}

static updateNotificationBadge(count) {
  const bellBtn = document.querySelector('.top-icons button:first-child');
  let badge = bellBtn.querySelector('.notification-badge');
  
  if (count > 0) {
    if (!badge) {
      badge = document.createElement('span');
      badge.className = 'notification-badge';
      bellBtn.appendChild(badge);
    }
    badge.textContent = count > 9 ? '9+' : count;
  } else if (badge) {
    badge.remove();
  }
}

static openNotificationModal() {
  const modal = new bootstrap.Modal(
    document.getElementById('notificationModal')
  );
  modal.show();
}
```

---

## 4️⃣ CSS - Notification Badge

```css
.notification-badge {
  position: absolute;
  top: -8px;
  right: -8px;
  background-color: #fdaf22;
  color: #000;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: bold;
  border: 2px solid #fff;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
```

---

## 5️⃣ Template - Update Bell Icon

```html
<!-- BEFORE -->
<button class="icon-btn round-2 icon-btn-light" title="bell">
  <i class="fa-regular fa-bell fa-xs"></i>
</button>

<!-- AFTER -->
<button class="icon-btn round-2 icon-btn-light" title="Notifications" 
        id="notificationBellBtn" style="position: relative;">
  <i class="fa-regular fa-bell fa-xs"></i>
</button>
```

---

## 6️⃣ Template - Update Help Icon

```html
<!-- BEFORE -->
<button class="icon-btn round-2 icon-btn-light" title="question">
  <i class="fa-solid fa-question fa-xs"></i>
</button>

<!-- AFTER -->
<button class="icon-btn round-2 icon-btn-light" title="Help & FAQ"
        onclick="window.location.href='/help'">
  <i class="fa-solid fa-question fa-xs"></i>
</button>
```

---

## 7️⃣ Template - Add Script Includes

```html
<!-- Add before closing </body> tag -->
<script src="{{ asset('js/api/notificationApiClient.js') }}"></script>
<script src="{{ asset('js/components/notificationModal.js') }}"></script>
```

---

## 8️⃣ Notification Item Template

```html
<div class="notification-item unread" data-notification-id="123">
  <div class="notification-title">Announcement Title</div>
  <div class="notification-snippet">
    This is the first 100 characters of the notification message...
  </div>
  <button class="btn-read-more" onclick="window.location.href='/userannouncement'">
    Read More
  </button>
</div>
```

---

## 📋 File Locations

| File | Location | Action |
|------|----------|--------|
| NotificationApiClient | `public/js/api/notificationApiClient.js` | CREATE |
| NotificationModal | `public/js/components/notificationModal.js` | CREATE |
| Dashboard | `public/js/dashboard.js` | MODIFY |
| Template | `resources/views/layouts/usertemplate.blade.php` | MODIFY |
| CSS | `public/css/dashboard.css` | MODIFY |

---

## ✅ Testing Checklist

- [ ] Badge appears when unread > 0
- [ ] Badge disappears when unread = 0
- [ ] Badge shows "9+" when count > 9
- [ ] Modal opens on bell click
- [ ] 3 tabs display correctly
- [ ] Snippets truncate at 100 chars
- [ ] Read More links work
- [ ] Help icon links to /help
- [ ] Auto-refresh works
- [ ] Responsive on mobile

