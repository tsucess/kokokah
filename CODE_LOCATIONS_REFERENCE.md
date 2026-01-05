# Code Locations Reference

## 📍 Current Implementation Locations

### usertemplate.blade.php
**File:** `resources/views/layouts/usertemplate.blade.php`

| Component | Lines | Status | Action |
|-----------|-------|--------|--------|
| Badge Icon | 115 | ✅ Working | Keep as is |
| Points Icon | 117-118 | ✅ Working | Keep as is |
| Bell Icon | 123-124 | ❌ Broken | Update |
| Message Icon | 125-126 | ❌ Broken | Future |
| Help Icon | 127-128 | ❌ Broken | Update |

**Current Code:**
```html
<!-- Line 114-120: Badge & Points (WORKING) -->
<div class="d-flex gap-2 shadow-sm rounded-pill align-items-center py-2 px-3 mx-1 mx-lg-3">
  <div><img src="./images/leaderboard-award-icon.png" alt=""> <span data-badges>0</span></div>
  <div></div>
  <div class="ps-2" style="border-left: 1px solid #000000;">
    <img src="./images/point-icon.png" alt=""> <span data-points>0</span>
  </div>
</div>

<!-- Line 122-129: Icons (NEEDS UPDATE) -->
<div class="top-icons">
  <button class="icon-btn round-2 icon-btn-light" title="bell">
    <i class="fa-regular fa-bell fa-xs"></i>
  </button>
  <button class="icon-btn round-2 icon-btn-light" title="message">
    <i class="fa-regular fa-envelope fa-xs"></i>
  </button>
  <button class="icon-btn round-2 icon-btn-light" title="question">
    <i class="fa-solid fa-question fa-xs"></i>
  </button>
</div>
```

---

### dashboard.js
**File:** `public/js/dashboard.js`

| Method | Lines | Status | Action |
|--------|-------|--------|--------|
| init() | 10-17 | ✅ Working | Add notification call |
| loadPointsAndBadges() | 198-242 | ✅ Working | Reference pattern |
| loadUserProfile() | 140-193 | ✅ Working | Keep as is |

**Current Code (lines 198-242):**
```javascript
static async loadPointsAndBadges() {
  try {
    // Fetch user points
    const pointsResponse = await window.PointsAndBadgesApiClient.getUserPoints();
    if (pointsResponse.success && pointsResponse.data) {
      const { points = 0 } = pointsResponse.data;
      const pointsElements = document.querySelectorAll('[data-points]');
      pointsElements.forEach(el => {
        el.textContent = points.toLocaleString();
      });
    }
    // Fetch user badges
    const badgesResponse = await window.PointsAndBadgesApiClient.getUserBadges();
    if (badgesResponse.success && badgesResponse.data) {
      const badgeCount = Array.isArray(badgesResponse.data)
        ? badgesResponse.data.length
        : (badgesResponse.data.data ? badgesResponse.data.data.length : 0);
      const badgeElements = document.querySelectorAll('[data-badges]');
      badgeElements.forEach(el => {
        el.textContent = badgeCount;
      });
    }
  } catch (error) {
    console.error('Error loading points and badges:', error);
  }
}
```

---

### dashboard.css
**File:** `public/css/dashboard.css`

| Section | Lines | Status | Action |
|---------|-------|--------|--------|
| Topbar | 316-328 | ✅ Working | Keep as is |
| Icon Button | 360-376 | ✅ Working | Keep as is |
| Notification Badge | - | ❌ Missing | Add new |

**Current Topbar Code (lines 316-328):**
```css
.topbar {
  position: sticky;
  top: 0;
  z-index: 1020;
  background: #fff;
  border-bottom: 1px solid #e9f0f4;
  padding: 12px 18px;
  display: flex;
  gap: 12px;
  align-items: center;
  justify-content: space-between;
  overflow: hidden;
}
```

**Current Icon Button Code (lines 360-376):**
```css
.icon-btn {
  width: 32px;
  height: 32px;
  border-radius: 10px;
  background: #fff;
  border: 1px solid #e6edf3;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

@media screen and (min-width:768px) {
  .icon-btn {
    width: 42px;
    height: 42px;
  }
}
```

---

### API Clients
**File:** `public/js/api/userApiClient.js`

| Method | Lines | Status |
|--------|-------|--------|
| getNotifications() | 78-86 | ✅ Available |
| markNotificationsRead() | 88+ | ✅ Available |

**Current Code (lines 78-86):**
```javascript
static async getNotifications(filters = {}) {
  const params = new URLSearchParams();
  if (filters.page) params.append('page', filters.page);
  if (filters.per_page) params.append('per_page', filters.per_page);

  const queryString = params.toString();
  const endpoint = queryString ? `/users/notifications?${queryString}` : '/users/notifications';
  return this.get(endpoint);
}
```

---

## 📝 Files to Create

| File | Location | Lines | Purpose |
|------|----------|-------|---------|
| NotificationApiClient | `public/js/api/notificationApiClient.js` | 40 | API calls |
| NotificationModal | `public/js/components/notificationModal.js` | 150 | Modal component |

---

## 🔗 Related Files

- **BaseApiClient:** `public/js/api/baseApiClient.js` (reference)
- **PointsAndBadgesApiClient:** `public/js/api/pointsAndBadgesApiClient.js` (reference)
- **Bootstrap:** CDN (already included)
- **Font Awesome:** CDN (already included)

---

## ✅ Implementation Checklist

- [ ] Create notificationApiClient.js
- [ ] Create notificationModal.js
- [ ] Update dashboard.js (add 4 methods)
- [ ] Update usertemplate.blade.php (update 2 icons, add modal HTML)
- [ ] Update dashboard.css (add badge styles)
- [ ] Test all functionality
- [ ] Deploy to production

