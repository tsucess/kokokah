# 📖 USER DASHBOARD - CODE REFERENCE

**Date:** December 13, 2025  
**Purpose:** Quick reference for slider and API integration code

---

## 🎨 HTML STRUCTURE

### Slider Controls
```html
<div class="slider-controls">
    <button id="sliderPrevBtn" class="slider-btn" type="button" title="Previous">
        <i class="fa-solid fa-circle-chevron-left" style="color: #9E9E9E; font-size: 24px;"></i>
    </button>
    <button id="sliderNextBtn" class="slider-btn" type="button" title="Next">
        <i class="fa-solid fa-circle-chevron-right" style="color: #9E9E9E; font-size: 24px;"></i>
    </button>
</div>
```

### Course Card Template
```html
<template id="courseCardTemplate">
    <div class="p-3 bg-white mysubject d-flex flex-column gap-3 w-100 rounded-4">
        <div class="border border-dark p-2 text-center" style="border-radius: 10px;">
            <img src="{{ asset('images/Kokokah_Logo.png') }}" class="img-fluid userdasboard-card-img" alt="Course" />
        </div>
        <div class="card-item-class align-self-start course-level">JSS 1</div>
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="subjects course-name">Computer Science</h5>
            <h5 class="subjects course-progress">0%</h5>
        </div>
        <div class="progress" style="height:6px; background-color:#D9D9D9;">
            <div class="progress-bar course-progress-bar" style="width:0%; background:#F56824; height:100%;"></div>
        </div>
        <button class="view-btn view-course-btn" type="button" data-course-id="">View Subjects</button>
    </div>
</template>
```

---

## 🎯 CSS STYLING

### Container & Scrolling
```css
.card-container {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    overflow-y: hidden;
    scroll-behavior: smooth;
    scrollbar-width: none;
}

.card-container::-webkit-scrollbar {
    display: none;
}

.card-container > * {
    flex: 0 0 calc(33.333% - 0.67rem);
    min-width: 280px;
}

@media (max-width: 1024px) {
    .card-container > * {
        flex: 0 0 calc(50% - 0.5rem);
        min-width: 250px;
    }
}

@media (max-width: 768px) {
    .card-container > * {
        flex: 0 0 calc(100% - 0rem);
        min-width: 100%;
    }
}
```

### Button Styling
```css
.slider-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border-radius: 50%;
}

.slider-btn:hover {
    background-color: #f0f0f0;
    transform: scale(1.1);
}

.slider-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
```

---

## 🔧 JAVASCRIPT FUNCTIONS

### Setup Slider
```javascript
function setupSliderControls() {
    const container = document.getElementById('coursesContainer');
    const prevBtn = document.getElementById('sliderPrevBtn');
    const nextBtn = document.getElementById('sliderNextBtn');

    if (!container || !prevBtn || !nextBtn) return;

    const scrollAmount = 320;

    prevBtn.addEventListener('click', () => {
        container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        updateSliderButtonStates();
    });

    nextBtn.addEventListener('click', () => {
        container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        updateSliderButtonStates();
    });

    container.addEventListener('scroll', updateSliderButtonStates);
    updateSliderButtonStates();
}
```

### Update Button States
```javascript
function updateSliderButtonStates() {
    const container = document.getElementById('coursesContainer');
    const prevBtn = document.getElementById('sliderPrevBtn');
    const nextBtn = document.getElementById('sliderNextBtn');

    if (!container || !prevBtn || !nextBtn) return;

    const isAtStart = container.scrollLeft <= 0;
    prevBtn.disabled = isAtStart;

    const isAtEnd = container.scrollLeft >= (container.scrollWidth - container.clientWidth - 10);
    nextBtn.disabled = isAtEnd;
}
```

### Load Courses
```javascript
async function loadUserCourses() {
    try {
        const response = await CourseApiClient.getMyCourses({ per_page: 12 });
        
        if (!response.success) {
            ToastNotification.error('Error', 'Failed to load courses');
            return;
        }

        const courses = response.courses || [];
        const container = document.getElementById('coursesContainer');
        const template = document.getElementById('courseCardTemplate');

        container.innerHTML = '';

        if (courses.length === 0) {
            container.innerHTML = '<p class="text-center text-muted">No courses enrolled yet</p>';
            updateStats(0, 0);
            return;
        }

        let completedCount = 0;
        let ongoingCount = 0;

        courses.forEach(enrollment => {
            const course = enrollment.course;
            if (!course) return;

            const card = template.content.cloneNode(true);
            card.querySelector('.course-name').textContent = course.name || 'Untitled Course';
            card.querySelector('.course-level').textContent = course.level?.name || 'Level';
            
            const progress = enrollment.progress || 0;
            card.querySelector('.course-progress').textContent = `${Math.round(progress)}%`;
            card.querySelector('.course-progress-bar').style.width = `${progress}%`;
            card.querySelector('.view-course-btn').setAttribute('data-course-id', course.id);

            if (enrollment.status === 'completed') completedCount++;
            else if (enrollment.status === 'in_progress') ongoingCount++;

            container.appendChild(card);
        });

        updateStats(completedCount, ongoingCount);

    } catch (error) {
        console.error('Error loading courses:', error);
        ToastNotification.error('Error', 'An error occurred while loading courses');
    }
}
```

---

## 📊 API METHODS

### CourseApiClient
```javascript
static async getMyCourses(filters = {})
static async enrollCourse(courseId)
static async unenrollCourse(courseId)
static async getCourseLessons(courseId)
static async getFeaturedCourses()
static async getPopularCourses()
static async searchCourses(query)
```

### EnrollmentApiClient
```javascript
static async getEnrollments(filters = {})
static async getEnrollment(enrollmentId)
static async createEnrollment(enrollmentData)
static async updateEnrollment(enrollmentId, enrollmentData)
static async deleteEnrollment(enrollmentId)
static async getEnrollmentProgress(enrollmentId)
static async completeEnrollment(enrollmentId)
static async getEnrollmentCertificates()
static async getActiveEnrollments(filters = {})
static async getCompletedEnrollments(filters = {})
```

---

## 🔗 INITIALIZATION

```javascript
document.addEventListener('DOMContentLoaded', async () => {
    // Load user data
    const user = AuthApiClient.getUser();
    if (user) {
        document.getElementById('userGreeting').textContent = `Hello ${user.first_name} 👋`;
    }

    // Load courses
    await loadUserCourses();

    // Setup slider
    setupSliderControls();

    // Handle navigation
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('button.view-course-btn');
        if (!btn) return;
        
        const courseId = btn.getAttribute('data-course-id');
        if (courseId) {
            window.location.href = `/termsubject?course_id=${courseId}`;
        }
    });
});
```

---

## 📱 RESPONSIVE BREAKPOINTS

| Screen | Cards | Flex Basis |
|--------|-------|-----------|
| Desktop (>1024px) | 3 | 33.333% |
| Tablet (768-1024px) | 2 | 50% |
| Mobile (<768px) | 1 | 100% |

---

## 🎯 KEY VARIABLES

```javascript
const scrollAmount = 320;           // Pixels per scroll
const isAtStart = scrollLeft <= 0;  // Check start position
const isAtEnd = scrollLeft >= (scrollWidth - clientWidth - 10); // Check end
```

---

**Code Reference Complete! 📖**


