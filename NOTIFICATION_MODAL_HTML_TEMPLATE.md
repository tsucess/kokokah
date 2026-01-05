# Notification Modal - HTML Template

## Modal HTML Structure

Add this to `resources/views/layouts/usertemplate.blade.php` before closing `</body>` tag:

```html
<!-- Notification Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Notifications</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body">
        <!-- Tab Navigation -->
        <ul class="nav nav-tabs mb-3" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" 
               href="#announcements" role="tab">
              Announcements
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" 
               href="#messages" role="tab">
              Messages
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" 
               href="#notifications" role="tab">
              Notifications
            </a>
          </li>
        </ul>
        
        <!-- Tab Content -->
        <div class="tab-content">
          <!-- Announcements Tab -->
          <div id="announcements" class="tab-pane fade show active" role="tabpanel">
            <div id="announcementsList" class="notification-list">
              <p class="text-muted">Loading announcements...</p>
            </div>
          </div>
          
          <!-- Messages Tab -->
          <div id="messages" class="tab-pane fade" role="tabpanel">
            <div id="messagesList" class="notification-list">
              <p class="text-muted">Loading messages...</p>
            </div>
          </div>
          
          <!-- Notifications Tab -->
          <div id="notifications" class="tab-pane fade" role="tabpanel">
            <div id="notificationsList" class="notification-list">
              <p class="text-muted">Loading notifications...</p>
            </div>
          </div>
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Close
        </button>
        <button type="button" class="btn btn-primary" id="markAllReadBtn">
          Mark All as Read
        </button>
      </div>
    </div>
  </div>
</div>
```

---

## CSS Styling

Add to `public/css/dashboard.css`:

```css
/* Notification Badge */
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
}

/* Notification List */
.notification-list {
  max-height: 400px;
  overflow-y: auto;
}

/* Notification Item */
.notification-item {
  padding: 12px;
  border-bottom: 1px solid #e9ecef;
  cursor: pointer;
  transition: background-color 0.2s;
}

.notification-item:hover {
  background-color: #f8f9fa;
}

.notification-item.unread {
  background-color: #fff3e0;
  border-left: 4px solid #fdaf22;
}

/* Notification Title */
.notification-title {
  font-weight: 600;
  color: #004a53;
  margin-bottom: 6px;
}

/* Notification Snippet */
.notification-snippet {
  font-size: 14px;
  color: #6b7280;
  margin-bottom: 8px;
  line-height: 1.4;
}

/* Read More Button */
.btn-read-more {
  font-size: 12px;
  padding: 4px 12px;
  background-color: #004a53;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.btn-read-more:hover {
  background-color: #003a42;
}

/* Empty State */
.notification-empty {
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
}

.notification-empty i {
  font-size: 48px;
  margin-bottom: 12px;
  opacity: 0.5;
}
```

---

## Script Includes

Add to `usertemplate.blade.php` before closing `</body>`:

```html
<!-- Notification API Client -->
<script src="{{ asset('js/api/notificationApiClient.js') }}"></script>

<!-- Notification Modal Component -->
<script src="{{ asset('js/components/notificationModal.js') }}"></script>
```

---

## Notification Item HTML Template

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

