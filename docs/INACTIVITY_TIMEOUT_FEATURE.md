# Auto Logout After Inactivity - Feature Documentation

## Overview
This feature automatically logs out users after 30 minutes of inactivity to enhance security and protect user sessions.

## How It Works

### Timeline
- **0-28 minutes**: User is actively logged in. Any user activity (mouse movement, keyboard input, clicks, scrolling, touch) resets the inactivity timer.
- **28 minutes**: A warning modal appears notifying the user that their session will expire in 2 minutes.
- **30 minutes**: If no activity is detected, the user is automatically logged out and redirected to the login page.

### User Activity Events Tracked
The system monitors the following events to detect user activity:
- `mousedown` - Mouse button pressed
- `mousemove` - Mouse movement
- `keypress` - Keyboard input
- `scroll` - Page scrolling
- `touchstart` - Touch screen interaction
- `click` - Mouse click
- `focus` - Window focus

Any of these events will reset the inactivity timer.

## Features

### Warning Modal
When the user is about to be logged out due to inactivity:
1. A modal dialog appears with a warning message
2. A countdown timer shows the remaining time (2 minutes)
3. User can click "Stay Logged In" to continue their session
4. User can click "Logout Now" to logout immediately

### Automatic Logout
When the 30-minute timeout is reached:
1. The user's authentication token is revoked via the `/api/logout` endpoint
2. Local storage is cleared (auth_token and user data)
3. User is redirected to the login page

### Graceful Fallback
If the logout API call fails:
1. Local storage is still cleared
2. User is still redirected to the login page
3. Error is logged to console for debugging

## Configuration

The inactivity timeout can be customized by modifying the initialization in `public/js/utils/inactivityTimeout.js`:

```javascript
window.inactivityManager = new InactivityTimeoutManager({
    inactivityTimeout: 30 * 60 * 1000, // 30 minutes in milliseconds
    warningTimeout: 28 * 60 * 1000     // Show warning at 28 minutes
});
```

### Configuration Options
- `inactivityTimeout`: Total time before logout (default: 30 minutes)
- `warningTimeout`: Time before showing warning modal (default: 28 minutes)
- `checkInterval`: Interval for checking inactivity (default: 60 seconds)

## Implementation Details

### Files Modified
1. **public/js/utils/inactivityTimeout.js** - Main inactivity manager class
2. **resources/views/layouts/dashboardtemp.blade.php** - Dashboard layout
3. **resources/views/layouts/dashboard.blade.php** - Alternative dashboard layout
4. **resources/views/layouts/usertemplate.blade.php** - User template layout
5. **resources/views/layouts/template.blade.php** - Main template layout

### Class: InactivityTimeoutManager

#### Methods
- `init()` - Initialize the manager and attach event listeners
- `attachActivityListeners()` - Attach event listeners to track user activity
- `resetInactivityTimer()` - Reset timer on user activity
- `startInactivityTimer()` - Start the inactivity and warning timers
- `showWarningModal()` - Display the warning modal
- `startCountdownTimer()` - Start the countdown in the warning modal
- `hideWarningModal()` - Hide the warning modal
- `performLogout()` - Execute the logout process
- `disable()` - Disable the inactivity timeout
- `enable()` - Re-enable the inactivity timeout

## API Integration

### Logout Endpoint
The feature uses the existing `/api/logout` endpoint:

```
POST /api/logout
Headers:
  - Authorization: Bearer {token}
  - Accept: application/json
  - Content-Type: application/json
```

This endpoint is already implemented in `app/Http/Controllers/AuthController.php`.

## Security Considerations

1. **Token Revocation**: All tokens for the user are revoked on logout
2. **Local Storage Cleanup**: Auth token and user data are cleared from localStorage
3. **Forced Redirect**: User is redirected to login page regardless of API response
4. **Static Modal**: Warning modal uses `backdrop: 'static'` to prevent dismissal by clicking outside

## Testing

### Manual Testing
1. Log in to the application
2. Wait 28 minutes - warning modal should appear
3. Click "Stay Logged In" - timer should reset
4. Wait another 28 minutes without activity
5. After 30 minutes total, you should be logged out

### Quick Testing (Modified Timeouts)
For testing purposes, you can temporarily modify the timeouts in the browser console:

```javascript
// Disable current manager
window.inactivityManager.disable();

// Create new manager with shorter timeouts (1 minute total, warning at 50 seconds)
window.inactivityManager = new InactivityTimeoutManager({
    inactivityTimeout: 60 * 1000,      // 1 minute
    warningTimeout: 50 * 1000          // Warning at 50 seconds
});
```

## Browser Compatibility

The feature uses standard JavaScript APIs and is compatible with:
- Chrome/Edge 90+
- Firefox 88+
- Safari 14+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Troubleshooting

### Warning Modal Not Appearing
- Check browser console for errors
- Verify Bootstrap is loaded
- Ensure JavaScript is enabled

### Not Logging Out After Timeout
- Check network tab for `/api/logout` request
- Verify auth token is valid
- Check browser console for errors

### Timer Resetting Unexpectedly
- Check if page has auto-refresh or polling
- Verify no other scripts are triggering activity events
- Check for background animations or timers

## Future Enhancements

Potential improvements:
1. Server-side session timeout validation
2. Configurable timeout per user role
3. Activity log tracking
4. Option to extend session before timeout
5. Remember me functionality
6. Multi-tab session synchronization

