# Testing Automatic Data Refresh - Quick Guide

## Overview
This guide helps you test the automatic data refresh system to ensure data updates without manual page reloads.

## Test Cases

### 1. Points Conversion ✅
**Location**: `/userkudikah` (Kudikah Wallet page)
**Steps**:
1. Open browser DevTools (F12) → Console
2. Go to Kudikah wallet page
3. Click "Convert Points" button
4. Enter amount and confirm
5. **Expected**: Points balance updates immediately without reload
6. **Verify**: Check console for `[DataRefreshService]` and `[DashboardRefreshManager]` logs

### 2. Course Completion ✅
**Location**: `/subjectdetails` (Lesson page)
**Steps**:
1. Open browser DevTools (F12) → Console
2. Go to a course with lessons
3. Complete all lessons by clicking "Mark Lesson Complete"
4. When all lessons are done, complete the course
5. **Expected**: User points and badges update automatically
6. **Verify**: Check console for `[EnrollmentEventEmitter]` and `[DashboardRefreshManager]` logs

### 3. Wallet Transfer ✅
**Location**: `/userkudikah` (Kudikah Wallet page)
**Steps**:
1. Open browser DevTools (F12) → Console
2. Go to Kudikah wallet page
3. Click "Transfer Money" button
4. Enter recipient email and amount
5. Confirm transfer
6. **Expected**: Wallet balance and transaction history update immediately
7. **Verify**: Check console for `[WalletRefreshManager]` logs

### 4. Course Purchase via Wallet ✅
**Location**: `/enroll` (Course enrollment page)
**Steps**:
1. Open browser DevTools (F12) → Console
2. Go to enroll page
3. Select courses and choose "Kudikah Wallet" payment
4. Confirm purchase
5. **Expected**: Wallet balance updates, user courses list updates
6. **Verify**: Check console for `[WalletRefreshManager]` logs

### 5. Lesson Completion ✅
**Location**: `/subjectdetails` (Lesson page)
**Steps**:
1. Open browser DevTools (F12) → Console
2. Go to a lesson
3. Click "Mark Lesson Complete"
4. **Expected**: Progress bar updates, lesson marked as complete
5. **Verify**: Check console for `[ActivityRefreshManager]` logs

### 6. User Activity Updates ✅
**Location**: `/admin/useractivity` (Admin activity page)
**Steps**:
1. Open browser DevTools (F12) → Console
2. Go to user activity page
3. Perform an action (complete lesson, convert points, etc.)
4. **Expected**: Activity table updates automatically
5. **Verify**: Check console for `[Activity Page]` logs

## Console Debugging

### Expected Log Messages
```
[DataRefreshService] Emitting event: points_converted
[DashboardRefreshManager] Points converted, refreshing...
[WalletRefreshManager] Wallet updated, refreshing...
[ActivityRefreshManager] Activity event: points_converted, refreshing...
[EnrollmentEventEmitter] Course completed, emitting event...
```

### Checking Event Emissions
1. Open DevTools Console
2. Look for messages starting with `[DataRefreshService]`
3. Verify event type matches action performed
4. Check that refresh managers respond to events

### Checking API Calls
1. Open DevTools Network tab
2. Perform an action (e.g., convert points)
3. Look for API calls to:
   - `/api/users/points` (refresh points)
   - `/api/wallet` (refresh wallet)
   - `/api/wallet/transactions` (refresh transactions)
   - `/api/users/activity` (refresh activity)

## Troubleshooting

### Data Not Updating
1. Check browser console for errors
2. Verify all service scripts are loaded:
   - `dataRefreshService.js`
   - `dashboardRefreshManager.js`
   - `walletRefreshManager.js`
   - `activityRefreshManager.js`
   - `enrollmentEventEmitter.js`
3. Check Network tab for failed API calls
4. Verify user is authenticated

### Services Not Initializing
1. Check if `DOMContentLoaded` event fired
2. Verify `DataRefreshService` is defined globally
3. Check for JavaScript errors in console
4. Ensure scripts are loaded in correct order

### Events Not Emitting
1. Check if action was successful (API returned success: true)
2. Verify `window.DataRefreshService` exists
3. Check console for event emission logs
4. Verify event type is correct

## Performance Notes

- Services use async/await for non-blocking updates
- API calls are made in parallel where possible
- No page reload required
- Minimal performance impact
- Services auto-initialize on page load

## Browser Compatibility

- Chrome/Edge: ✅ Fully supported
- Firefox: ✅ Fully supported
- Safari: ✅ Fully supported
- IE11: ⚠️ May require polyfills

## Next Steps

After testing:
1. Verify all test cases pass
2. Check console for any errors
3. Monitor performance in DevTools
4. Test on different browsers
5. Test on mobile devices

