# 📖 USER CLASS PAGE - CODE REFERENCE

**Date:** December 13, 2025  
**Purpose:** Quick reference for userclass.blade.php implementation

---

## 🎨 HTML STRUCTURE

### Course Container
```html
<div class="card-container" id="coursesContainer">
    <!-- Courses will be loaded here dynamically -->
</div>
```

### Course Card Template
```html
<template id="courseCardTemplate">
    <div class="p-3 rounded-4 bg-white mysubject d-flex flex-column gap-3 w-100">
        <div class="border border-dark p-2 text-center" style="border-radius: 10px;">
            <img src="{{ asset('images/Kokokah_Logo.png') }}" class="img-fluid userdasboard-card-img" alt="Course" />
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <div class="card-item-class align-self-start course-level">Level</div>
            <div class="enrolled-badge" style="display: none;">Enrolled</div>
        </div>
        <h5 class="subjects course-name">Course Name</h5>
        <p class="course-description" style="font-size: 14px; color: #666; margin: 0;">Course description</p>
        <button class="enroll-btn enroll-course-btn" type="button" data-course-id="">Enroll</button>
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

### Loading Spinner
```css
.loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #004A53;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
```

### Enrolled Badge
```css
.enrolled-badge {
    background-color: #4CAF50;
    color: white;
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}
```

---

## 🔧 JAVASCRIPT FUNCTIONS

### Load Courses
```javascript
async function loadAvailableCourses() {
    try {
        const response = await CourseApiClient.getCourses({ per_page: 20 });

        if (!response.success) {
            ToastNotification.error('Error', 'Failed to load courses');
            showEmptyState();
            return;
        }

        const courses = response.courses || response.data || [];
        const container = document.getElementById('coursesContainer');
        const template = document.getElementById('courseCardTemplate');

        container.innerHTML = '';

        if (courses.length === 0) {
            showEmptyState();
            return;
        }

        courses.forEach(course => {
            const card = template.content.cloneNode(true);
            card.querySelector('.course-name').textContent = course.title || course.name;
            card.querySelector('.course-level').textContent = course.level?.name || 'Level';
            card.querySelector('.course-description').textContent = 
                course.description ? course.description.substring(0, 80) + '...' : 'No description';

            const enrollBtn = card.querySelector('.enroll-course-btn');
            enrollBtn.setAttribute('data-course-id', course.id);

            if (course.is_enrolled) {
                enrollBtn.textContent = 'Already Enrolled';
                enrollBtn.disabled = true;
                card.querySelector('.enrolled-badge').style.display = 'block';
            }

            container.appendChild(card);
        });

    } catch (error) {
        console.error('Error loading courses:', error);
        ToastNotification.error('Error', 'An error occurred while loading courses');
        showEmptyState();
    }
}
```

### Enroll in Course
```javascript
async function enrollCourse(courseId, btn) {
    try {
        btn.disabled = true;
        const originalText = btn.textContent;
        btn.innerHTML = '<span class="loading-spinner"></span>';

        const response = await CourseApiClient.enrollCourse(courseId);

        if (!response.success) {
            btn.disabled = false;
            btn.textContent = originalText;
            ToastNotification.error('Error', response.message || 'Failed to enroll');
            return;
        }

        btn.textContent = 'Already Enrolled';
        btn.disabled = true;

        const badge = btn.closest('.mysubject').querySelector('.enrolled-badge');
        if (badge) {
            badge.style.display = 'block';
        }

        ToastNotification.success('Success', 'Successfully enrolled in course!');

    } catch (error) {
        console.error('Error enrolling:', error);
        btn.disabled = false;
        btn.textContent = originalText;
        ToastNotification.error('Error', 'An error occurred while enrolling');
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
            <h4>No Courses Available</h4>
            <p>Check back later for new courses!</p>
        </div>
    `;
}
```

---

## 📊 API METHODS

### CourseApiClient.getCourses(filters)
```javascript
static async getCourses(filters = {}) {
    const params = new URLSearchParams();
    if (filters.page) params.append('page', filters.page);
    if (filters.per_page) params.append('per_page', filters.per_page);
    if (filters.search) params.append('search', filters.search);
    if (filters.category_id) params.append('category_id', filters.category_id);
    if (filters.level_id) params.append('level_id', filters.level_id);
    if (filters.term_id) params.append('term_id', filters.term_id);

    const queryString = params.toString();
    const endpoint = queryString ? `/courses?${queryString}` : '/courses';
    return this.get(endpoint);
}
```

### CourseApiClient.enrollCourse(courseId)
```javascript
static async enrollCourse(courseId) {
    return this.post(`/courses/${courseId}/enroll`);
}
```

---

## 🔗 EVENT LISTENERS

### DOMContentLoaded
```javascript
document.addEventListener('DOMContentLoaded', async () => {
    await loadAvailableCourses();

    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('button.enroll-course-btn');
        if (!btn) return;

        const courseId = btn.getAttribute('data-course-id');
        if (courseId) {
            await enrollCourse(courseId, btn);
        }
    });
});
```

---

## 📱 RESPONSIVE DESIGN

```css
.card-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
}

@media screen and (max-width: 500px) {
    .enroll-btn {
        padding-block: 10px;
    }
}
```

---

**Code Reference Complete! 📖**


