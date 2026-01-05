# ⚡ Pagination Quick Reference

**Component:** Dashboard Recently Registered Users Pagination  
**Status:** ✅ READY TO USE  

---

## 🚀 Quick Start

### Load a Specific Page
```javascript
loadRecentUsers(2);  // Load page 2
```

### Get Current Page
```javascript
console.log(currentPage);  // Current page number
console.log(totalRecentPages);  // Total pages
```

### Pagination Info
```javascript
// Automatically updated in: #recentUsersInfo
// Example: "Showing 1-10 of 150 users"
```

---

## 🎯 Key Functions

### `loadRecentUsers(page)`
Loads users for a specific page and updates the table.

```javascript
// Load page 1
loadRecentUsers(1);

// Load next page
loadRecentUsers(currentPage + 1);

// Load previous page
loadRecentUsers(currentPage - 1);
```

### `generateRecentPageNumbers(current, total)`
Creates page number buttons dynamically.

```javascript
// Called automatically by loadRecentUsers()
// No need to call manually
```

---

## 📊 HTML Elements

### Pagination Info
```html
<small id="recentUsersInfo">Showing 1-10 of 150 users</small>
```

### Page Numbers Container
```html
<div id="recentPageNumbers">
  <!-- Buttons generated here -->
</div>
```

### Navigation Buttons
```html
<button id="recentPrevBtn">Previous</button>
<button id="recentNextBtn">Next</button>
```

---

## 🎨 Styling

### Current Page Button
```css
background-color: #004A53;
color: white;
font-weight: 600;
```

### Other Page Buttons
```css
border: 1px solid #ddd;
color: #333;
```

### Disabled State
```css
opacity: 0.5;
cursor: not-allowed;
```

---

## 🔧 Configuration

### Items Per Page
```javascript
const recentUsersPerPage = 10;  // Change here
```

### API Endpoint
```javascript
AdminApiClient.getRecentUsers(page, perPage)
// GET /admin/users/recent?page={page}&per_page={perPage}
```

---

## 📱 Responsive Breakpoints

### Desktop (>768px)
- Full pagination visible
- Page numbers displayed
- Buttons side-by-side

### Mobile (<768px)
- Buttons may stack
- Page numbers wrap
- Touch-friendly (40px buttons)

---

## 🐛 Troubleshooting

### Page numbers not showing?
```javascript
// Check if multiple pages exist
if (totalRecentPages <= 1) {
  // Page numbers hidden (single page)
}
```

### Buttons not working?
```javascript
// Check auth token
console.log(localStorage.getItem('auth_token'));

// Check API client loaded
console.log(typeof AdminApiClient);
```

### Wrong item count?
```javascript
// Check API response
console.log(result.data.total);
console.log(result.data.per_page);
```

---

## 📋 Common Tasks

### Navigate to Page 3
```javascript
loadRecentUsers(3);
```

### Go to Last Page
```javascript
loadRecentUsers(totalRecentPages);
```

### Go to First Page
```javascript
loadRecentUsers(1);
```

### Check Current Page
```javascript
console.log(`Currently on page ${currentPage} of ${totalRecentPages}`);
```

### Refresh Current Page
```javascript
loadRecentUsers(currentPage);
```

---

## 🎯 Event Handlers

### Previous Button Click
```html
<button onclick="loadRecentUsers(currentPage - 1)">
  Previous
</button>
```

### Next Button Click
```html
<button onclick="loadRecentUsers(currentPage + 1)">
  Next
</button>
```

### Page Number Click
```javascript
btn.onclick = () => loadRecentUsers(pageNumber);
```

---

## 📊 Data Flow

```
User clicks page number
    ↓
loadRecentUsers(page) called
    ↓
API request sent
    ↓
Table updated with new users
    ↓
Pagination info updated
    ↓
Page numbers regenerated
    ↓
Buttons enabled/disabled
```

---

## ✨ Features

✅ Previous/Next buttons  
✅ Dynamic page numbers  
✅ Smart ellipsis  
✅ Current page highlight  
✅ Pagination info  
✅ Button state management  
✅ Mobile responsive  
✅ Error handling  

---

## 🎉 Ready to Use!

The pagination is fully implemented and ready for production use.

**No additional setup required!**

