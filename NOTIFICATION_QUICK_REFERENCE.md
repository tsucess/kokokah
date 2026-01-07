# Toast & Modal Quick Reference Guide

## 🎯 Quick Start

### Never Use These ❌
```javascript
alert('message');           // ❌ Browser alert
confirm('message');         // ❌ Browser confirm
console.log('error');       // ❌ Console only
```

### Always Use These ✅
```javascript
ToastNotification.success('Title', 'Message');
confirmationModal.show('Title', 'Message', 'Confirm', 'Cancel');
```

## 📢 Toast Notifications

### Basic Usage
```javascript
// Success
ToastNotification.success('Success', 'Operation completed!');

// Error
ToastNotification.error('Error', 'Something went wrong');

// Warning
ToastNotification.warning('Warning', 'Please check your input');

// Info
ToastNotification.info('Info', 'This is informational');
```

### With Custom Timeout
```javascript
// Show for 2 seconds
ToastNotification.success('Success', 'Message', 2000);

// Don't auto-hide
ToastNotification.info('Processing...', 'Please wait', 0);
```

### Using Helper (Recommended)
```javascript
NotificationHelper.success('Operation completed!');
NotificationHelper.error('Something went wrong');
NotificationHelper.warning('Please check your input');
NotificationHelper.info('This is informational');
```

## ✅ Confirmation Modals

### Delete Confirmation
```javascript
const confirmed = await confirmationModal.showDeleteConfirmation('announcement');
if (confirmed) {
    await deleteAnnouncement(id);
    ToastNotification.success('Success', 'Deleted successfully!');
}
```

### Logout Confirmation
```javascript
const confirmed = await confirmationModal.showLogoutConfirmation();
if (confirmed) {
    logout();
}
```

### Custom Confirmation
```javascript
const confirmed = await confirmationModal.show(
    'Publish Announcement',
    'Are you sure you want to publish this?',
    'Publish',
    'Cancel'
);
if (confirmed) {
    // Proceed with action
}
```

### Using Helper
```javascript
const confirmed = await NotificationHelper.confirmDelete('item name');
if (confirmed) {
    // Proceed with deletion
}
```

## 🔄 Common Patterns

### Form Submission with Validation
```javascript
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    // Validate
    if (!email) {
        ToastNotification.warning('Validation', 'Email is required');
        return;
    }
    
    // Submit
    try {
        const response = await fetch('/api/submit', {
            method: 'POST',
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            ToastNotification.success('Success', 'Submitted successfully!');
            // Redirect after 1.5 seconds
            setTimeout(() => window.location.href = '/dashboard', 1500);
        } else {
            ToastNotification.error('Error', 'Submission failed');
        }
    } catch (error) {
        ToastNotification.error('Error', error.message);
    }
});
```

### Delete with Confirmation
```javascript
deleteBtn.addEventListener('click', async () => {
    const confirmed = await confirmationModal.showDeleteConfirmation('item');
    if (!confirmed) return;
    
    try {
        const response = await fetch(`/api/items/${id}`, {
            method: 'DELETE'
        });
        
        if (response.ok) {
            ToastNotification.success('Success', 'Deleted successfully!');
            // Reload or redirect
            location.reload();
        } else {
            ToastNotification.error('Error', 'Delete failed');
        }
    } catch (error) {
        ToastNotification.error('Error', error.message);
    }
});
```

### Success with Redirect
```javascript
// Option 1: Manual
ToastNotification.success('Success', 'Saved!');
setTimeout(() => {
    window.location.href = '/dashboard';
}, 1500);

// Option 2: Using helper
NotificationHelper.successAndRedirect('Saved!', '/dashboard', 1500);
```

## 🎨 Toast Types & Colors

| Type | Color | Use Case |
|------|-------|----------|
| success | Green (#198754) | Successful operations |
| error | Red (#dc3545) | Errors and failures |
| warning | Yellow (#ffc107) | Warnings and validation |
| info | Blue (#0d6efd) | Informational messages |

## 📍 Available Methods

### ToastNotification
- `show(title, message, type, timeout)`
- `success(title, message, timeout)`
- `error(title, message, timeout)`
- `warning(title, message, timeout)`
- `info(title, message, timeout)`
- `hide(toastId)`

### ConfirmationModal
- `show(title, message, confirmText, cancelText)`
- `showDeleteConfirmation(itemName)`
- `showLogoutConfirmation()`
- `showAccountDeletionConfirmation()`

### NotificationHelper
- `success(message, title, timeout)`
- `error(message, title, timeout)`
- `warning(message, title, timeout)`
- `info(message, title, timeout)`
- `confirmDelete(itemName)`
- `confirmLogout()`
- `confirmAccountDeletion()`
- `confirm(title, message, confirmText, cancelText)`
- `successAndRedirect(message, url, delay)`
- `errorAndRedirect(message, url, delay)`

## 🔗 Files to Include

All layout templates already include:
- `public/js/utils/toastNotification.js`
- `public/js/utils/confirmationModal.js`
- `public/js/utils/notificationHelper.js`

No additional includes needed!

