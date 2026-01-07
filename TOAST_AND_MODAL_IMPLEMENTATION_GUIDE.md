# Toast Notifications & Confirmation Modals - Implementation Guide

## Overview
This guide explains how to use toast notifications for success/error messages and confirmation modals for user confirmations throughout the Kokokah.com application.

## 1. Toast Notifications

### Available Methods
```javascript
// Show toast with custom title and message
ToastNotification.show(title, message, type, timeout);

// Convenience methods
ToastNotification.success(title, message, timeout);
ToastNotification.error(title, message, timeout);
ToastNotification.warning(title, message, timeout);
ToastNotification.info(title, message, timeout);
```

### Toast Types & Colors
- **success** (Green #198754) - For successful operations
- **error** (Red #dc3545) - For errors and failures
- **warning** (Yellow #ffc107) - For warnings
- **info** (Blue #0d6efd) - For informational messages

### Default Timeouts
- Success: 3500ms
- Error: 5000ms
- Warning: 4000ms
- Info: 3500ms

### Usage Examples

#### Success Message
```javascript
ToastNotification.success('Success', 'Announcement created successfully!');
```

#### Error Message
```javascript
ToastNotification.error('Error', 'Failed to save announcement. Please try again.');
```

#### Validation Error
```javascript
ToastNotification.warning('Validation Error', 'Please fill in all required fields.');
```

#### Custom Timeout
```javascript
ToastNotification.info('Info', 'Processing...', 0); // No auto-hide
```

## 2. Confirmation Modals

### Available Methods
```javascript
// Show generic confirmation
confirmationModal.show(title, message, confirmText, cancelText);

// Convenience methods
confirmationModal.showDeleteConfirmation(itemName);
confirmationModal.showLogoutConfirmation();
confirmationModal.showAccountDeletionConfirmation();
```

### Usage Examples

#### Delete Confirmation
```javascript
const confirmed = await confirmationModal.showDeleteConfirmation('announcement');
if (confirmed) {
    // Proceed with deletion
    await deleteAnnouncement(id);
    ToastNotification.success('Success', 'Announcement deleted successfully!');
}
```

#### Logout Confirmation
```javascript
const confirmed = await confirmationModal.showLogoutConfirmation();
if (confirmed) {
    // Proceed with logout
    logout();
}
```

#### Custom Confirmation
```javascript
const confirmed = await confirmationModal.show(
    'Publish Announcement',
    'Are you sure you want to publish this announcement?',
    'Publish',
    'Cancel'
);
if (confirmed) {
    // Proceed with action
}
```

## 3. Global Helper Functions

### Available in window scope
```javascript
// Toast helpers
window.showSuccess(message);
window.showError(message);
window.showWarning(message);
window.showInfo(message);

// Confirmation helpers
window.confirmDelete(itemName);
window.confirmAction(title, message);
```

## 4. Implementation Checklist

- [ ] Replace all `alert()` calls with `ToastNotification`
- [ ] Replace all `confirm()` calls with `confirmationModal`
- [ ] Update form submission handlers
- [ ] Update API error handlers
- [ ] Update validation error messages
- [ ] Test all notification flows
- [ ] Verify modal accessibility

## 5. Best Practices

1. **Always use toast for feedback** - Never use browser alerts
2. **Use modals for confirmations** - Never use browser confirm()
3. **Provide clear messages** - Be specific about what happened
4. **Use appropriate types** - Match message type to content
5. **Handle async operations** - Wait for user confirmation before proceeding
6. **Test thoroughly** - Verify all notification flows work correctly

## 6. Files Involved

- `public/js/utils/toastNotification.js` - Toast notification system
- `public/js/utils/confirmationModal.js` - Confirmation modal system
- `resources/views/layouts/usertemplate.blade.php` - Loads toast & modal scripts
- `resources/views/layouts/dashboardtemp.blade.php` - Loads toast & modal scripts

