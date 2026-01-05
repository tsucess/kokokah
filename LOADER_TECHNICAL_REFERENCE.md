# 🔧 Loader Technical Reference

**Last Updated:** January 4, 2026

---

## 📁 Files Modified

### 1. `resources/views/layouts/template.blade.php`
**Changes:** Added loader CSS and JavaScript

```html
<!-- Line 29: Added CSS -->
<link rel="stylesheet" href="{{ asset('css/loader.css') }}">

<!-- Line 240: Added Script -->
<script src="{{ asset('js/utils/kokokahLoader.js') }}"></script>
```

### 2. `public/css/loader.css`
**Changes:** Added explicit visibility states

```css
.kokokah-loader-overlay {
  opacity: 1;
  visibility: visible;
}

.kokokah-loader-overlay.hidden {
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
}
```

### 3. `public/js/utils/kokokahLoader.js`
**Changes:** Loader shows immediately on init

```javascript
init() {
  this.createLoaderHTML();
  this.setupEventListeners();
  this.show(); // Show immediately
}

createLoaderHTML() {
  // Removed 'hidden' class from initial HTML
  const loaderHTML = `
    <div class="kokokah-loader-overlay" id="kokokahLoader">
      ...
    </div>
  `;
  // ...
  this.isVisible = true;
}
```

---

## 🎯 How It Works

### Page Load Flow
1. **HTML Loads** → Loader CSS applied
2. **DOM Ready** → Loader JavaScript initializes
3. **Loader Shows** → Immediately visible (no hidden class)
4. **Page Content Loads** → Loader stays visible
5. **Window Load Event** → Loader hides with fade transition

### User Interaction Flow
1. **User Clicks Link** → Loader shows
2. **Page Navigates** → Loader stays visible
3. **New Page Loads** → Loader hides

---

## 🔍 Key Methods

### `show()`
- Removes `hidden` class
- Sets `isVisible = true`
- Clears any pending hide timeout

### `hide()`
- Adds `hidden` class after 300ms
- Smooth fade transition
- Sets `isVisible = false`

### `forceHide()`
- Immediately hides loader
- No transition delay
- Clears all timeouts

### `showForAction(duration)`
- Shows loader for specific duration
- Auto-hides after duration expires

---

## 📊 Layout Coverage

| Layout | File | Loader | Pages |
|--------|------|--------|-------|
| Admin | dashboardtemp | ✅ | 20+ |
| User | usertemplate | ✅ | 15+ |
| Public | template | ✅ | 15+ |

---

## ⚙️ Configuration

### CSS Variables
- **Z-index:** 9999 (always on top)
- **Background:** rgba(255, 255, 255, 0.95)
- **Spinner Size:** 60px
- **Transition:** 0.3s ease

### JavaScript
- **Min Display Time:** 300ms
- **Spinner Animation:** 1s rotation
- **Dots Animation:** 1.5s steps

---

## 🧪 Testing Checklist

- [ ] Page load shows loader
- [ ] Loader hides when page loads
- [ ] Link click shows loader
- [ ] Form submission shows loader
- [ ] Back/forward shows loader
- [ ] Mobile responsive
- [ ] No FOUC
- [ ] Smooth animations

---

## 🚀 Deployment Notes

No database changes required. Pure frontend implementation.

**Files to Deploy:**
1. `resources/views/layouts/template.blade.php`
2. `public/css/loader.css`
3. `public/js/utils/kokokahLoader.js`

**No Breaking Changes** - Fully backward compatible.

