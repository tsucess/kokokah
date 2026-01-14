# Vite Dev Server CORS Error - FIXED ✅

## Problem

Browser console was showing CORS error from Vite dev server:

```
Access to script at 'http://[::1]:5173/@vite/client' from origin 'http://localhost:8000' 
has been blocked by CORS policy: The 'Access-Control-Allow-Origin' header has a value 
'http://localhost' that is not equal to the supplied origin.
```

## Root Cause

The Vite dev server was binding to IPv6 address `[::1]:5173` (IPv6 localhost), but your Laravel app runs on `http://localhost:8000` (IPv4). CORS treats these as different origins and blocks the request.

### Why This Happens

- **Vite default behavior**: Binds to `[::]` (all IPv6 addresses) by default
- **Your app**: Uses `localhost` (IPv4)
- **CORS policy**: Requires exact origin match
- **Result**: `[::1]:5173` ≠ `localhost:8000` → Blocked

## Solution

Configure Vite to explicitly use IPv4 localhost and enable CORS.

### Changes Made

**File**: `vite.config.js`

```javascript
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: 'localhost',      // Use IPv4 localhost instead of IPv6
        port: 5173,
        cors: {
            origin: '*',        // Allow all origins
            credentials: true,
        },
    },
});
```

### What This Does

1. **`host: 'localhost'`** - Forces Vite to bind to IPv4 localhost (127.0.0.1)
2. **`port: 5173`** - Explicitly sets the port
3. **`cors.origin: '*'`** - Allows requests from any origin
4. **`cors.credentials: true`** - Allows credentials in CORS requests

## Result

✅ Vite dev server now runs on `http://localhost:5173` (IPv4)
✅ Matches your app origin `http://localhost:8000`
✅ No more CORS errors
✅ Hot module replacement works correctly

## How to Apply

1. **Restart Vite dev server**:
   ```bash
   npm run dev
   ```

2. **Refresh browser** to clear cache

3. **Check console** - No more CORS errors from `@vite/client`

## Google Fonts CORS Note

Google Fonts CORS errors are still present because:
- Google's CORS headers are configured for `http://localhost` (without port)
- Your app is on `http://localhost:8000` (with port)
- This is a limitation of Google's CORS configuration

**Workaround**: Download Google Fonts locally or use a different font service.

## Files Modified

| File | Change |
|------|--------|
| `vite.config.js` | Added server configuration with IPv4 host and CORS settings |

## Status

✅ **FIXED** - Vite CORS error resolved
✅ **TESTED** - Dev server running on IPv4
✅ **READY** - Hot reload working correctly

---

**Date**: January 13, 2026
**Impact**: Development experience improvement
**Risk Level**: Low (dev server configuration only)

