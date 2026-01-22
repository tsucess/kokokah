# Quick Start: Auto Logout After 30 Minutes

## ✅ Feature is Ready to Use

The auto-logout feature has been fully implemented and integrated into your application.

## What Happens

1. **User logs in** → Inactivity timer starts
2. **User is inactive for 28 minutes** → Warning modal appears with 2-minute countdown
3. **User clicks "Stay Logged In"** → Timer resets, user continues working
4. **User is inactive for 30 minutes total** → Automatic logout, redirect to login page

## How to Test

### Option 1: Wait for Real Timeout (30 minutes)
1. Log in to your application
2. Don't interact with the page for 28 minutes
3. Warning modal will appear
4. Wait 2 more minutes without activity
5. You'll be logged out automatically

### Option 2: Quick Test with Modified Timeouts
Open browser console (F12) and run:

```javascript
// Disable current manager
window.inactivityManager.disable();

// Create new manager with 1-minute timeout (warning at 50 seconds)
window.inactivityManager = new InactivityTimeoutManager({
    inactivityTimeout: 60 * 1000,       // 1 minute total
    warningTimeout: 50 * 1000           // Warning at 50 seconds
});
```

Now:
1. Don't interact with the page for 50 seconds
2. Warning modal appears
3. Wait 10 more seconds without activity
4. You'll be logged out

## Customizing the Timeout

To change the default 30-minute timeout:

1. Open `public/js/utils/inactivityTimeout.js`
2. Find the initialization code at the bottom (around line 230)
3. Modify the timeouts:

```javascript
window.inactivityManager = new InactivityTimeoutManager({
    inactivityTimeout: 20 * 60 * 1000,  // Change to 20 minutes
    warningTimeout: 18 * 60 * 1000      // Warning at 18 minutes
});
```

## What Triggers Activity Reset

Any of these actions will reset the inactivity timer:
- Mouse movement
- Mouse click
- Keyboard input
- Page scrolling
- Touch screen interaction
- Window focus

## Warning Modal Features

When the warning modal appears:
- Shows "Session Timeout Warning" header
- Displays remaining time with countdown
- "Stay Logged In" button - extends session
- "Logout Now" button - logout immediately
- Modal cannot be dismissed by clicking outside (static backdrop)

## Files Involved

- **Main Script**: `public/js/utils/inactivityTimeout.js`
- **Layouts Updated**:
  - `resources/views/layouts/dashboardtemp.blade.php`
  - `resources/views/layouts/dashboard.blade.php`
  - `resources/views/layouts/usertemplate.blade.php`
  - `resources/views/layouts/template.blade.php`

## Troubleshooting

### Warning modal not appearing?
- Check browser console for errors (F12)
- Verify Bootstrap is loaded
- Ensure JavaScript is enabled

### Not logging out after timeout?
- Check Network tab (F12) for `/api/logout` request
- Verify auth token is valid
- Check console for error messages

### Timer resetting unexpectedly?
- Check if page has auto-refresh
- Look for background animations or timers
- Verify no other scripts trigger activity events

## Disabling the Feature (if needed)

In browser console:
```javascript
window.inactivityManager.disable();
```

To re-enable:
```javascript
window.inactivityManager.enable();
```

## Security Notes

✅ Tokens are revoked on logout
✅ Local storage is cleared
✅ User is redirected to login page
✅ Works even if API call fails

## Need More Details?

See `docs/INACTIVITY_TIMEOUT_FEATURE.md` for comprehensive documentation.

