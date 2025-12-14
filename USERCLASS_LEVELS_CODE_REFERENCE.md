# 📖 USER CLASS PAGE - CLASS LEVELS CODE REFERENCE

**Date:** December 13, 2025  
**Purpose:** Quick reference for userclass.blade.php implementation

---

## 🎨 HTML STRUCTURE

### Class Level Container
```html
<div class="card-container" id="coursesContainer">
    <!-- Levels will be loaded here dynamically -->
</div>
```

### Class Level Card Template
```html
<template id="courseCardTemplate">
    <div class="p-3 rounded-4 bg-white mysubject d-flex flex-column gap-3 w-100">
        <div class="border border-dark p-2 text-center" style="border-radius: 10px; background-color: #f8f9fa;">
            <div class="course-level" style="font-size: 32px; font-weight: bold; color: #004A53; margin: 0;">
                📚
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <div class="card-item-class align-self-start course-level">Level</div>
            <div class="enrolled-badge" style="display: none;">Enrolled</div>
        </div>
        <h5 class="subjects course-name">Class Name</h5>
        <p class="course-description" style="font-size: 14px; color: #666; margin: 0;">Course count</p>
        <button class="enroll-btn view-level-btn" type="button" data-level-id="">View Courses</button>
    </div>
</template>
```

---

## 🎯 CSS STYLING

### Button Styles
```css
.enroll-btn {
    border: 1px solid #004A53;
    border-radius: 4px;
    padding: 16px 20px;
    color: #004A53;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    background: none;
}

.enroll-btn:hover {
    background-color: #004A53;
    color: white;
}

.enroll-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background-color: #ccc;
    color: #666;
    border-color: #ccc;
}
```

### Card Container
```css
.card-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
    position: relative;
    z-index: 10;
}
```

### Empty State
```css
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #999;
}

.empty-state-icon {
    font-size: 48px;
    margin-bottom: 20px;
    opacity: 0.5;
}
```

---

## 🔧 JAVASCRIPT FUNCTIONS

### Load Class Levels
```javascript
async function loadClassLevels() {
    try {
        // Fetch levels from API
        const response = await fetch('/api/level', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            console.error('Failed to load levels:', response.statusText);
            ToastNotification.error('Error', 'Failed to load class levels');
            showEmptyState();
            return;
        }

        const data = await response.json();
        const levels = Array.isArray(data) ? data : data.data || data.levels || [];

        const container = document.getElementById('coursesContainer');
        const template = document.getElementById('courseCardTemplate');

        container.innerHTML = '';

        if (levels.length === 0) {
            showEmptyState();
            return;
        }

        // Render each level
        levels.forEach(level => {
            const card = template.content.cloneNode(true);

            // Update level data
            card.querySelector('.course-name').textContent = level.name || 'Untitled Level';
            card.querySelector('.course-level').textContent = level.name || 'Level';

            // Show course count if available
            const courseCount = level.courses ? level.courses.length : 0;
            card.querySelector('.course-description').textContent = 
                `${courseCount} course${courseCount !== 1 ? 's' : ''} available`;

            // Set level ID for navigation
            const viewBtn = card.querySelector('.enroll-course-btn');
            viewBtn.setAttribute('data-level-id', level.id);
            viewBtn.setAttribute('data-level-name', level.name);
            viewBtn.textContent = 'View Courses';
            viewBtn.classList.remove('enroll-course-btn');
            viewBtn.classList.add('view-level-btn');

            // Hide enrolled badge for levels
            card.querySelector('.enrolled-badge').style.display = 'none';

            container.appendChild(card);
        });

    } catch (error) {
        console.error('Error loading levels:', error);
        ToastNotification.error('Error', 'An error occurred while loading class levels');
        showEmptyState();
    }
}
```

### Empty State
```javascript
function showEmptyState() {
    const container = document.getElementById('coursesContainer');
    container.innerHTML = `
        <div class="empty-state" style="grid-column: 1 / -1;">
            <div class="empty-state-icon">📚</div>
            <h4>No Class Levels Available</h4>
            <p>Check back later for new class levels!</p>
        </div>
    `;
}
```

---

## 🔗 EVENT LISTENERS

### DOMContentLoaded & Navigation
```javascript
document.addEventListener('DOMContentLoaded', async () => {
    // Load all available class levels
    await loadClassLevels();

    // Handle class level card clicks
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('button.view-level-btn');
        if (!btn) return;

        const levelId = btn.getAttribute('data-level-id');
        const levelName = btn.getAttribute('data-level-name');
        if (levelId) {
            // Navigate to courses for this level
            window.location.href = `/usersubject?level_id=${levelId}&level_name=${encodeURIComponent(levelName)}`;
        }
    });
});
```

---

## 📱 RESPONSIVE DESIGN

```css
@media screen and (max-width: 500px) {
    .enroll-btn {
        padding-block: 10px;
    }
}
```

---

## 🔌 API ENDPOINT

**GET /api/level**

Returns array of level objects with:
- `id` - Level ID
- `name` - Level name (e.g., "JSS 1", "SSS 2")
- `curriculum_category_id` - Category ID
- `description` - Level description
- `courses` - Array of courses in this level
- `created_at` - Creation timestamp
- `updated_at` - Update timestamp

---

**Code Reference Complete! 📖**


