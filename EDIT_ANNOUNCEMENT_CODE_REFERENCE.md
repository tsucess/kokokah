# 💻 Edit Announcement - Code Reference

## 🔧 Web Route

**File:** `routes/web.php`

```php
Route::get('/announcement/{id}/edit', function ($id) {
    return view('admin.editannouncement', ['announcementId' => $id]);
});
```

---

## 📝 EditAnnouncementManager Class

**Location:** `resources/views/admin/editannouncement.blade.php`

### Constructor
```javascript
constructor() {
    this.announcementId = '{{ $announcementId }}';
    this.apiBaseUrl = '/api/announcements';
    this.init();
}
```

### Initialize
```javascript
async init() {
    try {
        await this.loadAnnouncement();
        this.setupEventListeners();
    } catch (error) {
        alert('Error loading announcement');
        window.location.href = '/announcement';
    }
}
```

### Load Announcement
```javascript
async loadAnnouncement() {
    const response = await fetch(`${this.apiBaseUrl}/${this.announcementId}`, {
        headers: {
            'Authorization': `Bearer ${this.getToken()}`
        }
    });
    
    const data = await response.json();
    const announcement = data.data;
    
    // Populate form fields
    document.getElementById('title').value = announcement.title;
    document.getElementById('type').value = announcement.type;
    document.getElementById('audience').value = announcement.audience;
    document.getElementById('description').value = announcement.description;
    
    // Set priority badge
    // Set scheduled_at if exists
    // Update preview
}
```

### Setup Event Listeners
```javascript
setupEventListeners() {
    // Title input
    document.getElementById('title').addEventListener('input', 
        () => this.updatePreview());
    
    // Priority badges
    document.querySelectorAll('.badge.preview-card-badge').forEach(badge => {
        badge.addEventListener('click', (e) => {
            // Update active badge
            this.updatePreview();
        });
    });
    
    // Buttons
    document.querySelector('.cancel-btn').addEventListener('click', 
        () => window.location.href = '/announcement');
    
    document.querySelector('.draft-btn').addEventListener('click', 
        () => this.submitForm('draft'));
    
    document.querySelector('.publish-btn').addEventListener('click', 
        () => this.submitForm('published'));
}
```

### Update Preview
```javascript
updatePreview() {
    const title = document.getElementById('title').value || 'Title';
    const description = document.getElementById('description').value || 'Description';
    const priority = document.querySelector('.badge.preview-card-badge.active')
        ?.dataset.priority || 'Info';
    
    document.getElementById('previewTitle').textContent = title;
    document.getElementById('previewDescription').textContent = description;
    document.getElementById('previewPriority').textContent = priority;
}
```

### Submit Form
```javascript
async submitForm(status) {
    const title = document.getElementById('title').value.trim();
    const description = document.getElementById('description').value.trim();
    const type = document.getElementById('type').value;
    const priority = document.querySelector('.badge.preview-card-badge.active')
        ?.dataset.priority || 'Info';
    const audience = document.getElementById('audience').value;
    const scheduled_at = document.getElementById('scheduled_at').value;
    
    // Validate
    if (!title || !description) {
        alert('Please fill all required fields');
        return;
    }
    
    // Send PUT request
    const response = await fetch(`${this.apiBaseUrl}/${this.announcementId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${this.getToken()}`
        },
        body: JSON.stringify({
            title, description, type, priority, audience,
            audience_value: null,
            scheduled_at: scheduled_at || null,
            status
        })
    });
    
    if (response.ok) {
        alert('Announcement updated successfully!');
        window.location.href = '/announcement';
    } else {
        alert('Error updating announcement');
    }
}
```

### Get Token
```javascript
getToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || 
           localStorage.getItem('auth_token') || '';
}
```

---

## 🔌 API Endpoint

**Method:** PUT
**URL:** `/api/announcements/{id}`

**Request Body:**
```json
{
    "title": "Updated Title",
    "description": "Updated description",
    "type": "Exams",
    "priority": "Urgent",
    "audience": "All students",
    "audience_value": null,
    "scheduled_at": "2026-01-02 10:00:00",
    "status": "published"
}
```

**Response:**
```json
{
    "status": 200,
    "message": "Announcement updated successfully",
    "data": {
        "id": 1,
        "title": "Updated Title",
        "description": "Updated description",
        ...
    }
}
```

---

## ✅ Status

**Implementation:** ✅ COMPLETE
**Ready:** ✅ YES

---

**All code is ready to use!**

