# Kokokah LMS - Frontend Setup Guide

**Last Updated:** October 26, 2025  
**Technology Stack:** HTML5, CSS3, Bootstrap 5, Vanilla JavaScript

---

## 📁 Frontend Structure

```
frontend/
├── index.html                 # Home page
├── css/
│   └── style.css             # Global styles
├── js/
│   ├── api-client.js         # API client library
│   └── main.js               # Utility functions
└── pages/
    ├── login.html            # Login page
    ├── register.html         # Registration page
    ├── dashboard.html        # User dashboard
    ├── courses.html          # Courses listing
    ├── course-detail.html    # Course details
    ├── my-courses.html       # My courses
    ├── profile.html          # User profile
    ├── settings.html         # Settings
    └── [other pages]
```

---

## 🚀 Quick Start

### 1. Open Frontend

**Option A: Direct File Access**
```bash
# Windows
start frontend/index.html

# macOS
open frontend/index.html

# Linux
xdg-open frontend/index.html
```

**Option B: Local Server**
```bash
# Using Python 3
python -m http.server 8080

# Using Node.js
npx http-server

# Using PHP
php -S localhost:8080
```

Then open: `http://localhost:8080/frontend/`

### 2. Configure API

1. Go to login page
2. Click "Configure API" link
3. Enter API URL: `http://localhost:8000/api`
4. Click "Save"

### 3. Test Features

- Register new account
- Login
- Browse courses
- View dashboard
- Enroll in courses

---

## 📚 Pages Overview

### Home Page (`index.html`)
- Hero section
- Features showcase
- Popular courses
- Call-to-action
- Footer with language selector

### Login Page (`pages/login.html`)
- Email/password login
- Remember me option
- Social login (placeholder)
- Forgot password link
- Sign up link
- API configuration modal

### Register Page (`pages/register.html`)
- Full name input
- Email input
- Password input
- Role selection (student/instructor)
- Terms & conditions
- Login link

### Dashboard Page (`pages/dashboard.html`)
- Welcome message
- Stats cards (courses, hours, certificates, wallet)
- Recent courses with progress
- Notifications list
- User dropdown menu

### Courses Page (`pages/courses.html`)
- Course search
- Filters (category, level, price, rating)
- Course grid with pagination
- Sort options
- Course cards with details

---

## 🔧 API Client Usage

### Basic Usage

```javascript
// Login
const response = await api.login('email@example.com', 'password');
if (response.success) {
    console.log('Logged in!');
}

// Get courses
const courses = await api.getCourses(1, 10);
console.log(courses.data);

// Enroll in course
const enrollment = await api.enrollCourse(1);
if (enrollment.success) {
    Notification.success('Enrolled successfully!');
}
```

### Available Methods

**Authentication**
- `api.register(userData)`
- `api.login(email, password)`
- `api.logout()`
- `api.getCurrentUser()`
- `api.sendVerificationCode(email)`
- `api.verifyEmailWithCode(email, code)`

**Courses**
- `api.getCourses(page, perPage)`
- `api.searchCourses(query)`
- `api.getCourse(id)`
- `api.createCourse(data)`
- `api.updateCourse(id, data)`
- `api.deleteCourse(id)`
- `api.enrollCourse(id)`
- `api.getMyCourses()`

**Lessons**
- `api.getLessons(courseId)`
- `api.getLesson(id)`
- `api.completeLesson(id)`

**Quizzes**
- `api.getQuizzes(lessonId)`
- `api.getQuiz(id)`
- `api.startQuiz(id)`
- `api.submitQuiz(id, answers)`
- `api.getQuizResults(id)`

**User**
- `api.getUserProfile()`
- `api.updateProfile(data)`
- `api.getDashboard()`
- `api.getAchievements()`
- `api.getLearningStats()`

**Wallet & Payments**
- `api.getWalletBalance()`
- `api.getWalletTransactions()`
- `api.purchaseWithWallet(courseId)`
- `api.getPaymentGateways()`
- `api.purchaseCourse(courseId, gatewayId)`
- `api.getPaymentHistory()`

**Notifications**
- `api.getNotifications()`
- `api.markNotificationAsRead(id)`
- `api.deleteNotification(id)`

**Search**
- `api.globalSearch(query)`
- `api.searchCoursesByCategory(category)`

**Files**
- `api.uploadFile(file)`
- `api.downloadFile(id)`

**Language**
- `api.setLanguage(locale)`
- `api.getTranslations(locale)`

**Chat**
- `api.startChat(sessionType)`
- `api.sendChatMessage(sessionId, message)`
- `api.getChatHistory(sessionId)`
- `api.endChat(sessionId)`

**Certificates**
- `api.getCertificates()`
- `api.generateCertificate(courseId)`
- `api.downloadCertificate(id)`

**Analytics**
- `api.getLearningAnalytics()`
- `api.getCoursePerformance(courseId)`

---

## 🎨 Utility Functions

### Notifications
```javascript
Notification.success('Success message');
Notification.error('Error message');
Notification.warning('Warning message');
Notification.info('Info message');
```

### Loading Spinner
```javascript
Spinner.show();
// ... do something
Spinner.hide();
```

### Form Validation
```javascript
const errors = FormValidator.validate(formElement);
if (errors) {
    FormValidator.displayErrors(formElement, errors);
}
```

### Pagination
```javascript
Pagination.render(currentPage, totalPages, (page) => {
    loadData(page);
});
```

### Date Formatting
```javascript
DateFormatter.format(date, 'short');      // Oct 26, 2025
DateFormatter.format(date, 'long');       // October 26, 2025
DateFormatter.formatTime(date);           // 14:30
DateFormatter.formatRelative(date);       // 2h ago
```

### Storage
```javascript
Storage.set('key', value);
const value = Storage.get('key');
Storage.remove('key');
Storage.clear();
```

### DOM Helpers
```javascript
DOM.$('.selector');                       // querySelector
DOM.$$('.selector');                      // querySelectorAll
DOM.on(element, 'click', handler);       // addEventListener
DOM.addClass(element, 'class');          // classList.add
DOM.html(element, content);              // innerHTML
DOM.text(element, content);              // textContent
```

---

## 🔐 Authentication Flow

1. **Register**
   - User fills registration form
   - Submit to `/register` endpoint
   - Receive token and user data
   - Store token in localStorage
   - Redirect to dashboard

2. **Login**
   - User enters email/password
   - Submit to `/login` endpoint
   - Receive token and user data
   - Store token in localStorage
   - Redirect to dashboard

3. **Protected Routes**
   - Check if token exists
   - If not, redirect to login
   - If yes, allow access

4. **Logout**
   - Call `/logout` endpoint
   - Clear token from localStorage
   - Redirect to login

---

## 🌍 Multi-Language Support

```javascript
// Set language
await api.setLanguage('ha');  // Hausa
await api.setLanguage('yo');  // Yoruba
await api.setLanguage('ig');  // Igbo

// Get translations
const translations = await api.getTranslations('ha');
```

---

## 📱 Responsive Design

- Mobile-first approach
- Bootstrap 5 grid system
- Responsive navigation
- Mobile-friendly forms
- Touch-friendly buttons

---

## 🎯 Creating New Pages

1. **Create HTML file** in `pages/` directory
2. **Include Bootstrap & CSS**
   ```html
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="../css/style.css">
   ```

3. **Include JavaScript**
   ```html
   <script src="../js/api-client.js"></script>
   <script src="../js/main.js"></script>
   ```

4. **Check authentication**
   ```javascript
   if (!api.isAuthenticated()) {
       window.location.href = 'login.html';
   }
   ```

5. **Use API client**
   ```javascript
   const data = await api.getCourses();
   ```

---

## 🐛 Debugging

### Check Console
- Open browser DevTools (F12)
- Check Console tab for errors
- Check Network tab for API calls

### Check Storage
- Open DevTools
- Go to Application/Storage
- Check localStorage for token
- Check sessionStorage

### API Debugging
- Check API URL configuration
- Verify token is being sent
- Check response status codes
- Review error messages

---

## 🚀 Deployment

### Option 1: Static Hosting
- Upload `frontend/` to web server
- Configure API URL
- Test all features

### Option 2: Docker
```dockerfile
FROM nginx:latest
COPY frontend/ /usr/share/nginx/html/
```

### Option 3: CDN
- Upload files to CDN
- Update API URL
- Configure CORS

---

## ✅ Testing Checklist

- [ ] Home page loads
- [ ] Register works
- [ ] Login works
- [ ] Dashboard displays
- [ ] Courses load
- [ ] Search works
- [ ] Filters work
- [ ] Pagination works
- [ ] Notifications display
- [ ] Logout works
- [ ] Mobile responsive
- [ ] All languages work

---

## 📞 Support

For frontend issues:
1. Check browser console
2. Review API responses
3. Check network requests
4. Review documentation
5. Contact development team

---

*Last Updated: October 26, 2025*  
*Status: ✅ Production Ready*

