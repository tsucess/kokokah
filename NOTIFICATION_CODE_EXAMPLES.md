# Toast & Modal - Code Examples

## Example 1: Form Submission with Validation

```javascript
document.getElementById('myForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const email = document.getElementById('email').value.trim();
    const name = document.getElementById('name').value.trim();
    
    // Validation
    if (!email) {
        ToastNotification.warning('Validation Error', 'Email is required');
        return;
    }
    
    if (!name) {
        ToastNotification.warning('Validation Error', 'Name is required');
        return;
    }
    
    // Submit
    try {
        const response = await fetch('/api/submit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
            },
            body: JSON.stringify({ email, name })
        });
        
        if (response.ok) {
            ToastNotification.success('Success', 'Form submitted successfully!');
            setTimeout(() => window.location.href = '/dashboard', 1500);
        } else {
            const error = await response.json();
            ToastNotification.error('Error', error.message || 'Submission failed');
        }
    } catch (error) {
        ToastNotification.error('Error', 'Network error: ' + error.message);
    }
});
```

## Example 2: Delete with Confirmation

```javascript
async function deleteItem(itemId, itemName) {
    // Show confirmation
    const confirmed = await confirmationModal.showDeleteConfirmation(itemName);
    if (!confirmed) return;
    
    try {
        const response = await fetch(`/api/items/${itemId}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
            }
        });
        
        if (response.ok) {
            ToastNotification.success('Success', `${itemName} deleted successfully!`);
            // Reload table or redirect
            setTimeout(() => location.reload(), 1500);
        } else {
            ToastNotification.error('Error', 'Failed to delete item');
        }
    } catch (error) {
        ToastNotification.error('Error', error.message);
    }
}

// Usage
deleteBtn.addEventListener('click', () => {
    deleteItem(123, 'announcement');
});
```

## Example 3: API Call with Error Handling

```javascript
async function fetchUserData() {
    try {
        const response = await fetch('/api/users/profile', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
            }
        });
        
        if (!response.ok) {
            if (response.status === 401) {
                ToastNotification.error('Error', 'Session expired. Please log in again.');
                setTimeout(() => window.location.href = '/login', 2000);
                return null;
            } else if (response.status === 403) {
                ToastNotification.error('Error', 'You do not have permission to access this');
                return null;
            } else {
                ToastNotification.error('Error', 'Failed to load user data');
                return null;
            }
        }
        
        const data = await response.json();
        ToastNotification.success('Success', 'User data loaded');
        return data;
    } catch (error) {
        ToastNotification.error('Error', 'Network error: ' + error.message);
        return null;
    }
}
```

## Example 4: Logout with Confirmation

```javascript
async function logout() {
    // Show confirmation
    const confirmed = await confirmationModal.showLogoutConfirmation();
    if (!confirmed) return;
    
    try {
        const response = await fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
            }
        });
        
        if (response.ok) {
            // Clear storage
            localStorage.removeItem('auth_token');
            localStorage.removeItem('auth_user');
            
            ToastNotification.success('Success', 'Logged out successfully!');
            setTimeout(() => window.location.href = '/login', 1500);
        } else {
            ToastNotification.error('Error', 'Logout failed');
        }
    } catch (error) {
        ToastNotification.error('Error', error.message);
    }
}
```

## Example 5: Custom Confirmation

```javascript
async function publishAnnouncement(announcementId) {
    // Custom confirmation
    const confirmed = await confirmationModal.show(
        'Publish Announcement',
        'Are you sure you want to publish this announcement? It will be visible to all users.',
        'Publish',
        'Cancel'
    );
    
    if (!confirmed) return;
    
    try {
        const response = await fetch(`/api/announcements/${announcementId}/publish`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
            }
        });
        
        if (response.ok) {
            ToastNotification.success('Success', 'Announcement published!');
            setTimeout(() => location.reload(), 1500);
        } else {
            ToastNotification.error('Error', 'Failed to publish announcement');
        }
    } catch (error) {
        ToastNotification.error('Error', error.message);
    }
}
```

## Example 6: Using NotificationHelper

```javascript
// Simpler syntax with helper
async function saveSettings(settings) {
    try {
        const response = await fetch('/api/settings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
            },
            body: JSON.stringify(settings)
        });
        
        if (response.ok) {
            // Use helper for success with redirect
            NotificationHelper.successAndRedirect(
                'Settings saved successfully!',
                '/dashboard',
                1500
            );
        } else {
            NotificationHelper.error('Failed to save settings');
        }
    } catch (error) {
        NotificationHelper.error(error.message);
    }
}
```

## Example 7: Bulk Actions with Confirmation

```javascript
async function deleteSelected(selectedIds) {
    if (selectedIds.length === 0) {
        ToastNotification.warning('Warning', 'Please select items to delete');
        return;
    }
    
    const confirmed = await confirmationModal.show(
        'Delete Multiple Items',
        `Are you sure you want to delete ${selectedIds.length} item(s)? This cannot be undone.`,
        'Delete All',
        'Cancel'
    );
    
    if (!confirmed) return;
    
    try {
        const response = await fetch('/api/items/bulk-delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
            },
            body: JSON.stringify({ ids: selectedIds })
        });
        
        if (response.ok) {
            ToastNotification.success('Success', `${selectedIds.length} items deleted!`);
            setTimeout(() => location.reload(), 1500);
        } else {
            ToastNotification.error('Error', 'Failed to delete items');
        }
    } catch (error) {
        ToastNotification.error('Error', error.message);
    }
}
```

## Example 8: Progress Notification

```javascript
async function uploadFile(file) {
    // Show info toast (no auto-hide)
    const toastId = ToastNotification.info('Uploading', 'Please wait...', 0);
    
    try {
        const formData = new FormData();
        formData.append('file', file);
        
        const response = await fetch('/api/upload', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
            },
            body: formData
        });
        
        // Hide the progress toast
        ToastNotification.hide(toastId);
        
        if (response.ok) {
            ToastNotification.success('Success', 'File uploaded successfully!');
        } else {
            ToastNotification.error('Error', 'Upload failed');
        }
    } catch (error) {
        ToastNotification.hide(toastId);
        ToastNotification.error('Error', error.message);
    }
}
```

## Best Practices

1. **Always validate before showing success** - Verify API response
2. **Provide specific error messages** - Help users understand what went wrong
3. **Use appropriate toast types** - Match message to type
4. **Handle async confirmations** - Always await the result
5. **Redirect after success** - Give user feedback before navigation
6. **Clear storage on logout** - Remove tokens and user data
7. **Test error scenarios** - Verify error handling works
8. **Use helper methods** - Simpler and more consistent code

