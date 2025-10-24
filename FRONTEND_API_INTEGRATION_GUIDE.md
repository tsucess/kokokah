# 🚀 Frontend API Integration Guide

## Overview

This guide shows how to integrate the **200+ API endpoints** into your frontend pages using the comprehensive API service layer.

---

## 📦 Installation

### Step 1: Install Dependencies

```bash
npm install axios aos
```

### Step 2: Update Template

Add to `resources/views/layouts/template.blade.php` before closing `</head>`:

```html
<!-- Animations CSS -->
<link rel="stylesheet" href="{{ asset('css/animations.css') }}">

<!-- Mobile Responsive CSS -->
<link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">

<!-- AOS CSS -->
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
```

### Step 3: Update app.js

Add to `resources/js/app.js`:

```javascript
import './services/api';
import AOS from 'aos';

// Initialize AOS
AOS.init({
    duration: 1000,
    once: true,
    offset: 100,
});
```

---

## 🔐 Authentication Integration

### Login Page Example

```html
<form id="loginForm">
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" id="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" id="password" class="form-control" required>
    </div>
    <button type="submit" class="btn primaryButton w-100">Login</button>
</form>

<script>
    import { authAPI } from '/js/services/api.js';

    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        try {
            const response = await authAPI.login(
                document.getElementById('email').value,
                document.getElementById('password').value
            );
            
            // Save token
            localStorage.setItem('auth_token', response.data.token);
            
            // Redirect to dashboard
            window.location.href = '/dashboard';
        } catch (error) {
            alert('Login failed: ' + error.response?.data?.message);
        }
    });
</script>
```

### Registration Page Example

```html
<form id="registerForm">
    <input type="text" id="name" class="form-control" placeholder="Full Name" required>
    <input type="email" id="email" class="form-control" placeholder="Email" required>
    <input type="password" id="password" class="form-control" placeholder="Password" required>
    <button type="submit" class="btn primaryButton w-100">Register</button>
</form>

<script>
    import { authAPI } from '/js/services/api.js';

    document.getElementById('registerForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        try {
            const response = await authAPI.register({
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
            });
            
            localStorage.setItem('auth_token', response.data.token);
            window.location.href = '/dashboard';
        } catch (error) {
            alert('Registration failed: ' + error.response?.data?.message);
        }
    });
</script>
```

---

## 📚 Course Integration

### Display Courses

```html
<div id="coursesList" class="row">
    <!-- Courses will be loaded here -->
</div>

<script>
    import { courseAPI } from '/js/services/api.js';

    async function loadCourses() {
        try {
            const response = await courseAPI.list();
            const courses = response.data.data;
            
            const html = courses.map(course => `
                <div class="col-md-4 mb-4" data-aos="fade-up">
                    <div class="card course-card">
                        <img src="${course.image}" class="card-img-top" alt="${course.title}">
                        <div class="card-body">
                            <h5 class="card-title">${course.title}</h5>
                            <p class="card-text">${course.description}</p>
                            <p class="price">₦${course.price}</p>
                            <button class="btn primaryButton w-100" onclick="enrollCourse(${course.id})">
                                Enroll Now
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
            
            document.getElementById('coursesList').innerHTML = html;
        } catch (error) {
            console.error('Failed to load courses:', error);
        }
    }

    async function enrollCourse(courseId) {
        try {
            await courseAPI.enroll(courseId);
            alert('Successfully enrolled in course!');
            window.location.href = `/course/${courseId}`;
        } catch (error) {
            alert('Enrollment failed: ' + error.response?.data?.message);
        }
    }

    // Load courses on page load
    loadCourses();
</script>
```

---

## 💳 Payment Integration

### Payment Processing

```html
<div id="paymentForm">
    <select id="gateway" class="form-control mb-3">
        <option value="">Select Payment Gateway</option>
        <option value="paystack">Paystack</option>
        <option value="flutterwave">Flutterwave</option>
        <option value="stripe">Stripe</option>
    </select>
    <input type="number" id="amount" class="form-control mb-3" placeholder="Amount">
    <button class="btn primaryButton w-100" onclick="processPayment()">Pay Now</button>
</div>

<script>
    import { paymentAPI } from '/js/services/api.js';

    async function processPayment() {
        try {
            const response = await paymentAPI.initializeWalletDeposit({
                amount: document.getElementById('amount').value,
                gateway: document.getElementById('gateway').value,
            });
            
            // Redirect to payment gateway
            window.location.href = response.data.payment_url;
        } catch (error) {
            alert('Payment initialization failed: ' + error.response?.data?.message);
        }
    }
</script>
```

---

## 📊 Dashboard Integration

### Student Dashboard

```html
<div class="dashboard-container">
    <div class="row">
        <div class="col-md-3" data-aos="fade-up">
            <div class="stat-card">
                <h3 id="enrolledCount">0</h3>
                <p>Courses Enrolled</p>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card">
                <h3 id="completedCount">0</h3>
                <p>Courses Completed</p>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card">
                <h3 id="certificateCount">0</h3>
                <p>Certificates Earned</p>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card">
                <h3 id="badgeCount">0</h3>
                <p>Badges Earned</p>
            </div>
        </div>
    </div>
</div>

<script>
    import { dashboardAPI, userAPI } from '/js/services/api.js';

    async function loadDashboard() {
        try {
            const response = await dashboardAPI.studentDashboard();
            const data = response.data;
            
            document.getElementById('enrolledCount').textContent = data.enrolled_courses;
            document.getElementById('completedCount').textContent = data.completed_courses;
            document.getElementById('certificateCount').textContent = data.certificates;
            document.getElementById('badgeCount').textContent = data.badges;
        } catch (error) {
            console.error('Failed to load dashboard:', error);
        }
    }

    loadDashboard();
</script>
```

---

## 🎯 Quiz Integration

### Quiz Attempt

```html
<div id="quizContainer">
    <!-- Quiz will be loaded here -->
</div>

<script>
    import { quizAPI } from '/js/services/api.js';

    async function startQuiz(quizId) {
        try {
            // Start quiz attempt
            const startResponse = await quizAPI.startAttempt(quizId);
            const attemptId = startResponse.data.attempt_id;
            
            // Get quiz questions
            const quizResponse = await quizAPI.get(quizId);
            const quiz = quizResponse.data;
            
            // Display quiz
            displayQuiz(quiz, attemptId);
        } catch (error) {
            alert('Failed to start quiz: ' + error.response?.data?.message);
        }
    }

    async function submitQuiz(quizId, answers) {
        try {
            const response = await quizAPI.submitQuiz(quizId, { answers });
            alert('Quiz submitted! Score: ' + response.data.score);
            window.location.href = `/quiz/${quizId}/results`;
        } catch (error) {
            alert('Failed to submit quiz: ' + error.response?.data?.message);
        }
    }
</script>
```

---

## 📁 File Upload

### File Upload Example

```html
<input type="file" id="fileInput" class="form-control mb-3">
<button class="btn primaryButton" onclick="uploadFile()">Upload</button>
<div id="uploadProgress"></div>

<script>
    import { fileAPI } from '/js/services/api.js';

    async function uploadFile() {
        const file = document.getElementById('fileInput').files[0];
        if (!file) {
            alert('Please select a file');
            return;
        }
        
        const formData = new FormData();
        formData.append('file', file);
        
        try {
            const response = await fileAPI.upload(formData);
            alert('File uploaded successfully!');
            console.log('File ID:', response.data.id);
        } catch (error) {
            alert('Upload failed: ' + error.response?.data?.message);
        }
    }
</script>
```

---

## 🔍 Search Integration

### Global Search

```html
<input type="text" id="searchInput" class="form-control" placeholder="Search courses, users...">
<div id="searchResults"></div>

<script>
    import { searchAPI } from '/js/services/api.js';

    document.getElementById('searchInput').addEventListener('input', async (e) => {
        const query = e.target.value;
        if (query.length < 2) return;
        
        try {
            const response = await searchAPI.globalSearch(query);
            const results = response.data;
            
            const html = `
                <div class="search-results">
                    <h5>Courses (${results.courses.length})</h5>
                    ${results.courses.map(c => `<p>${c.title}</p>`).join('')}
                    
                    <h5>Users (${results.users.length})</h5>
                    ${results.users.map(u => `<p>${u.name}</p>`).join('')}
                </div>
            `;
            
            document.getElementById('searchResults').innerHTML = html;
        } catch (error) {
            console.error('Search failed:', error);
        }
    });
</script>
```

---

## 🎨 Animations with API Data

```html
<!-- Fade in on scroll -->
<div data-aos="fade-in">Content fades in</div>

<!-- Slide up on scroll -->
<div data-aos="fade-up">Content slides up</div>

<!-- Zoom in on scroll -->
<div data-aos="zoom-in">Content zooms in</div>

<!-- With delay -->
<div data-aos="fade-up" data-aos-delay="200">Delayed animation</div>

<!-- CSS animation class -->
<div class="animate-bounce">Bounces</div>
<div class="animate-pulse">Pulses</div>
```

---

## 📱 Mobile Responsive Classes

```html
<!-- Hide on mobile -->
<div class="mobile-hidden">Only on desktop</div>

<!-- Show only on mobile -->
<div class="mobile-visible">Only on mobile</div>

<!-- Full width on mobile -->
<div class="mobile-full-width">Full width on mobile</div>

<!-- Responsive image -->
<img src="image.jpg" class="img-fluid" alt="Description">

<!-- Touch-friendly button -->
<button class="btn primaryButton mobile-full-width">Button</button>
```

---

## ✅ Testing Checklist

- [ ] Install dependencies (`npm install axios aos`)
- [ ] Add CSS files to template
- [ ] Initialize AOS in app.js
- [ ] Test login/registration
- [ ] Test course listing
- [ ] Test course enrollment
- [ ] Test payment processing
- [ ] Test dashboard loading
- [ ] Test quiz functionality
- [ ] Test file upload
- [ ] Test search functionality
- [ ] Test animations on scroll
- [ ] Test mobile responsiveness
- [ ] Test on 320px devices
- [ ] Test on 375px devices
- [ ] Test on 480px devices
- [ ] Test on tablet (768px)
- [ ] Test on desktop (1024px+)

---

## 🐛 Troubleshooting

### CORS Issues
```javascript
// If you get CORS errors, ensure your API allows requests from your frontend domain
// Check config/cors.php in your Laravel backend
```

### Token Not Persisting
```javascript
// Make sure token is saved to localStorage after login
localStorage.setItem('auth_token', response.data.token);
```

### API Timeout
```javascript
// Increase timeout in api.js if needed
timeout: 60000, // 60 seconds
```

### 401 Unauthorized
```javascript
// Token expired, user will be redirected to login automatically
// Or manually clear token and redirect
localStorage.removeItem('auth_token');
window.location.href = '/login';
```

---

## 📚 API Service Modules

All available API modules:
- `authAPI` - Authentication
- `courseAPI` - Courses
- `lessonAPI` - Lessons
- `quizAPI` - Quizzes
- `assignmentAPI` - Assignments
- `enrollmentAPI` - Enrollments
- `userAPI` - Users
- `paymentAPI` - Payments
- `walletAPI` - Wallet
- `dashboardAPI` - Dashboard
- `reviewAPI` - Reviews
- `forumAPI` - Forum
- `certificateAPI` - Certificates
- `badgeAPI` - Badges
- `progressAPI` - Progress
- `gradingAPI` - Grading
- `adminAPI` - Admin
- `analyticsAPI` - Analytics
- `learningPathAPI` - Learning Paths
- `chatAPI` - AI Chat
- `recommendationAPI` - Recommendations
- `couponAPI` - Coupons
- `reportAPI` - Reports
- `settingAPI` - Settings
- `auditAPI` - Audit
- `searchAPI` - Search
- `fileAPI` - Files
- `advancedAnalyticsAPI` - Advanced Analytics
- `localizationAPI` - Localization
- `videoAPI` - Video Streaming
- `realtimeAPI` - Real-time Features
- `emailVerificationAPI` - Email Verification
- `categoryAPI` - Categories
- `notificationAPI` - Notifications

---

**Status:** ✅ Ready for Implementation  
**Last Updated:** October 23, 2025

