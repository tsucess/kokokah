# Notification System - Code Examples

## 1. NotificationApiClient.js

```javascript
class NotificationApiClient extends BaseApiClient {
  static async getNotifications(filters = {}) {
    const params = new URLSearchParams();
    if (filters.status) params.append('status', filters.status);
    if (filters.type) params.append('type', filters.type);
    if (filters.page) params.append('page', filters.page);
    if (filters.per_page) params.append('per_page', filters.per_page);
    
    const queryString = params.toString();
    const endpoint = queryString ? `/notifications?${queryString}` : '/notifications';
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
    return this.put(`/notifications/${notificationId}/read`, {});
  }

  static async markAllAsRead() {
    return this.put('/notifications/read-all', {});
  }
}

window.NotificationApiClient = NotificationApiClient;
```

## 2. Dashboard.js - Add to init()

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

static async initNotificationBell() {
  const bellBtn = document.querySelector('.top-icons button:first-child');
  if (!bellBtn) return;

  // Load unread count
  await this.loadNotifications();

  // Add click handler
  bellBtn.addEventListener('click', () => {
    this.openNotificationModal();
  });
}

static async loadNotifications() {
  try {
    const response = await window.NotificationApiClient.getUnreadCount();
    this.updateNotificationBadge(response);
  } catch (error) {
    console.error('Error loading notifications:', error);
  }
}

static updateNotificationBadge(count) {
  const badge = document.querySelector('.notification-badge');
  if (count > 0) {
    if (!badge) {
      const bellBtn = document.querySelector('.top-icons button:first-child');
      const newBadge = document.createElement('span');
      newBadge.className = 'notification-badge';
      newBadge.textContent = count > 9 ? '9+' : count;
      bellBtn.appendChild(newBadge);
    } else {
      badge.textContent = count > 9 ? '9+' : count;
    }
  }
}

static openNotificationModal() {
  const modal = new bootstrap.Modal(
    document.getElementById('notificationModal')
  );
  modal.show();
}
```

## 3. CSS for Badge

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
  border: 2px solid white;
}

.icon-btn {
  position: relative;
}
```

## 4. Modal HTML Structure

```html
<div class="modal fade" id="notificationModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Notifications</h5>
        <button type="button" class="btn-close" 
                data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" 
               href="#announcements">Announcements</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" 
               href="#messages">Messages</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" 
               href="#notifications">Notifications</a>
          </li>
        </ul>
        <div class="tab-content mt-3">
          <div id="announcements" class="tab-pane fade show active">
            <!-- Announcements list -->
          </div>
          <div id="messages" class="tab-pane fade">
            <!-- Messages list -->
          </div>
          <div id="notifications" class="tab-pane fade">
            <!-- Notifications list -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
```

## 5. Help Icon Link

```html
<!-- In usertemplate.blade.php -->
<button class="icon-btn round-2 icon-btn-light" 
        onclick="window.location.href='/help'" 
        title="Help & FAQ">
  <i class="fa-solid fa-question fa-xs"></i>
</button>
```

---

## 🔄 Data Flow

1. User loads dashboard
2. `DashboardModule.init()` calls `initNotificationBell()`
3. `loadNotifications()` fetches unread count
4. Badge updates with count
5. User clicks bell icon
6. Modal opens with notification tabs
7. User clicks "Read More"
8. Navigates to respective page
9. Modal marks notification as read

