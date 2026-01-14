# Loader Visibility Timeout Fix ✅

## Problem
The Kokokah loader was staying visible for too long, triggering the warning:
```
installHook.js:1 Loader visible for too long, force hiding
```

## Root Cause
In `public/js/utils/kokokahLoader.js`, the safety check mechanism had a logic error:

1. The `setupEventListeners()` method runs a `setInterval` every 5 seconds
2. It checks if the loader has been visible for more than 10 seconds
3. **BUG**: It was using `this.pageLoadStartTime` to calculate elapsed time
4. **ISSUE**: `this.pageLoadStartTime` gets reset every time `show()` is called
5. **RESULT**: The elapsed time calculation was always incorrect, causing the loader to not hide properly

## Solution
Introduced a separate `visibilityStartTime` property to track when the loader actually becomes visible:

### Changes Made

**File: `public/js/utils/kokokahLoader.js`**

1. **Constructor** (line 13):
   - Added: `this.visibilityStartTime = null;` to track visibility start time

2. **setupEventListeners()** (lines 89-98):
   - Changed: `const elapsedTime = Date.now() - this.pageLoadStartTime;`
   - To: `const elapsedTime = Date.now() - this.visibilityStartTime;`
   - Added check: `if (this.isVisible && this.visibilityStartTime)`

3. **show()** (line 109):
   - Added: `this.visibilityStartTime = Date.now();` to mark when loader becomes visible

4. **hide()** (line 152):
   - Added: `this.visibilityStartTime = null;` to reset when loader is hidden

5. **forceHide()** (line 185):
   - Added: `this.visibilityStartTime = null;` to reset when loader is force hidden

## How It Works Now
- When loader is shown: `visibilityStartTime` is set to current time
- Safety check runs every 5 seconds and calculates elapsed time from `visibilityStartTime`
- If loader is visible for more than 10 seconds, it's force hidden
- When loader is hidden: `visibilityStartTime` is reset to null
- This ensures accurate tracking of actual visibility duration

## Testing
The fix ensures:
✅ Loader properly hides after expected timeouts
✅ No more "Loader visible for too long" warnings
✅ Accurate visibility duration tracking
✅ Proper cleanup on hide/forceHide operations

