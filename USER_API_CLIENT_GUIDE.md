# 📚 UserApiClient - Quick Reference Guide

**Location:** `public/js/api/userApiClient.js`  
**Extends:** `BaseApiClient`  
**Status:** ✅ Production Ready

---

## 🚀 Quick Start

### Import in Blade Template
```blade
<script type="module">
    import UserApiClient from '{{ asset('js/api/userApiClient.js') }}';
    window.UserApiClient = UserApiClient;
</script>
```

### Import in JavaScript Module
```javascript
import UserApiClient from '{{ asset('js/api/userApiClient.js') }}';
```

---

## 📋 Available Methods

### Profile Management

#### Get User Profile
```javascript
const response = await UserApiClient.getProfile();
// Returns: { success: true, data: { id, first_name, last_name, ... } }
```

#### Update User Profile
```javascript
const formData = new FormData();
formData.append('first_name', 'John');
formData.append('last_name', 'Doe');
formData.append('gender', 'male');
formData.append('date_of_birth', '1995-05-15');
formData.append('avatar', fileInput.files[0]); // Optional

const response = await UserApiClient.updateProfile(formData);
// Returns: { success: true, message: 'Profile updated successfully', data: {...} }
```

### Account Security

#### Change Password
```javascript
const response = await UserApiClient.changePassword(
    'currentPassword',
    'newPassword',
    'newPassword'
);
// Returns: { success: true, message: 'Password changed successfully' }
```

### Dashboard & Stats

#### Get Dashboard Data
```javascript
const dashboard = await UserApiClient.getDashboard();
// Returns: { success: true, data: { stats, recent_activity, ... } }
```

#### Get Achievements
```javascript
const achievements = await UserApiClient.getAchievements();
// Returns: { success: true, data: [ { id, name, description, ... } ] }
```

#### Get Learning Statistics
```javascript
const stats = await UserApiClient.getLearningStats();
// Returns: { success: true, data: { total_hours, courses_completed, ... } }
```

### Preferences & Notifications

#### Update Preferences
```javascript
const response = await UserApiClient.updatePreferences({
    email_notifications: true,
    push_notifications: false,
    theme: 'dark',
    language: 'en',
    timezone: 'UTC'
});
// Returns: { success: true, message: 'Preferences updated' }
```

#### Get Notifications
```javascript
const notifications = await UserApiClient.getNotifications({
    page: 1,
    per_page: 10
});
// Returns: { success: true, data: [ { id, title, message, ... } ] }
```

#### Mark Notifications as Read
```javascript
const response = await UserApiClient.markNotificationsRead([1, 2, 3]);
// Returns: { success: true, message: 'Notifications marked as read' }
```

---

## 🎯 Common Use Cases

### Load and Display Profile
```javascript
async function displayProfile() {
    const response = await UserApiClient.getProfile();
    if (response.success) {
        const user = response.data;
        document.getElementById('name').textContent = user.first_name + ' ' + user.last_name;
        document.getElementById('email').textContent = user.email;
    }
}
```

### Update Profile with Validation
```javascript
async function updateUserProfile(formData) {
    try {
        const response = await UserApiClient.updateProfile(formData);
        if (response.success) {
            showSuccessMessage('Profile updated successfully');
            await displayProfile(); // Reload
        } else {
            showErrorMessage(response.message);
        }
    } catch (error) {
        showErrorMessage('An error occurred');
    }
}
```

### Handle Password Change
```javascript
async function handlePasswordChange(current, newPass, confirm) {
    if (newPass !== confirm) {
        showErrorMessage('Passwords do not match');
        return;
    }
    
    const response = await UserApiClient.changePassword(current, newPass, confirm);
    if (response.success) {
        showSuccessMessage('Password changed successfully');
    } else {
        showErrorMessage(response.message);
    }
}
```

---

## ⚠️ Error Handling

All methods return a response object with this structure:
```javascript
{
    success: boolean,
    message: string,
    data: object|array,
    errors: object // Only on validation errors
}
```

### Example Error Handling
```javascript
try {
    const response = await UserApiClient.updateProfile(data);
    
    if (!response.success) {
        if (response.errors) {
            // Handle validation errors
            console.log('Validation errors:', response.errors);
        } else {
            // Handle general error
            console.log('Error:', response.message);
        }
    }
} catch (error) {
    console.error('Network error:', error);
}
```

---

## 🔐 Authentication

All requests automatically include the Bearer token from localStorage:
- Token is retrieved from `localStorage.getItem('auth_token')`
- Token is sent in `Authorization: Bearer {token}` header
- Token is managed by `BaseApiClient`

---

## 📊 Response Examples

### Profile Response
```json
{
    "success": true,
    "data": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com",
        "gender": "male",
        "date_of_birth": "1995-05-15",
        "profile_photo": "/storage/avatars/...",
        "parent_first_name": "Jane",
        "parent_last_name": "Doe",
        "parent_email": "jane@example.com",
        "parent_phone": "+234-800-123-4567",
        "stats": {
            "total_enrollments": 5,
            "completed_courses": 2,
            "certificates_earned": 1,
            "total_rewards": 500,
            "current_streak": 7,
            "total_study_time": 120
        }
    }
}
```


