# 🚀 **KOKOKAH.COM LMS API DOCUMENTATION**

**Version:** 1.0  
**Base URL:** `https://api.kokokah.com/api`  
**Authentication:** Bearer Token (Laravel Sanctum)  
**Content-Type:** `application/json`

---

## 📋 **TABLE OF CONTENTS**

1. [Authentication](#authentication)
2. [Response Format](#response-format)
3. [Error Handling](#error-handling)
4. [Rate Limiting](#rate-limiting)
5. [Core Endpoints](#core-endpoints)
   - [User Management](#user-management)
   - [Courses](#courses)
   - [Lessons](#lessons)
   - [Quizzes](#quizzes)
   - [Payments](#payments)
6. [Data Models](#data-models)
7. [Examples](#examples)

---

## 🔐 **AUTHENTICATION**

### **Register User**
```http
POST /register
```

**Request Body:**
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "student"
}
```

**Response:**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "email": "john@example.com",
      "role": "student",
      "is_active": true,
      "created_at": "2025-01-01T00:00:00.000000Z"
    },
    "token": "1|abc123def456..."
  }
}
```

### **Login User**
```http
POST /login
```

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

### **Get Current User**
```http
GET /user
Authorization: Bearer {token}
```

### **Logout**
```http
POST /logout
Authorization: Bearer {token}
```

---

## 📊 **RESPONSE FORMAT**

All API responses follow this consistent format:

**Success Response:**
```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": {
    // Response data here
  },
  "meta": {
    "pagination": {
      "current_page": 1,
      "total_pages": 10,
      "per_page": 15,
      "total": 150
    }
  }
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Error description",
  "error": "Detailed error message",
  "code": "ERROR_CODE",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

---

## ⚠️ **ERROR HANDLING**

### **HTTP Status Codes**
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `429` - Rate Limit Exceeded
- `500` - Internal Server Error

### **Error Codes**
- `AUTH_REQUIRED` - Authentication required
- `VALIDATION_ERROR` - Input validation failed
- `PERMISSION_DENIED` - Insufficient permissions
- `RESOURCE_NOT_FOUND` - Resource not found
- `RATE_LIMIT_EXCEEDED` - Too many requests

---

## 🚦 **RATE LIMITING**

- **Limit:** 60 requests per minute per user
- **Headers:** 
  - `X-RateLimit-Limit: 60`
  - `X-RateLimit-Remaining: 45`
- **Exceeded Response:** HTTP 429 with `Retry-After` header

---

## 🎯 **CORE ENDPOINTS**

### **👥 USER MANAGEMENT**

#### **Get All Users (Admin Only)**
```http
GET /admin/users
Authorization: Bearer {token}
```

**Query Parameters:**
- `page` (int) - Page number (default: 1)
- `per_page` (int) - Items per page (default: 15)
- `role` (string) - Filter by role (student, instructor, admin)
- `is_active` (boolean) - Filter by active status
- `level_id` (int) - Filter by education level
- `search` (string) - Search by name or email

**Response:**
```json
{
  "success": true,
  "message": "Users retrieved successfully",
  "data": {
    "data": [
      {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com",
        "role": "student",
        "is_active": true,
        "contact": "+234-800-123-4567",
        "gender": "male",
        "level": {
          "id": 4,
          "name": "400 Level",
          "type": "university"
        },
        "date_of_birth": "1995-05-15",
        "address": "Lagos, Nigeria",
        "email_verified_at": "2025-01-13T10:30:00.000000Z",
        "created_at": "2025-01-13T10:30:00.000000Z",
        "updated_at": "2025-01-13T10:30:00.000000Z"
      }
    ],
    "current_page": 1,
    "per_page": 15,
    "total": 109,
    "last_page": 8
  }
}
```

#### **Search Users**
```http
GET /search/users?q=john&role=student
Authorization: Bearer {token}
```

**Query Parameters:**
- `q` (string) - Search query (name, email)
- `role` (string) - Filter by role
- `level_id` (int) - Filter by education level
- `is_active` (boolean) - Filter by active status
- `page` (int) - Page number
- `per_page` (int) - Items per page

#### **Get User Profile**
```http
GET /users/profile
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Profile retrieved successfully",
  "data": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "role": "student",
    "is_active": true,
    "contact": "+234-800-123-4567",
    "gender": "male",
    "level": {
      "id": 4,
      "name": "400 Level",
      "type": "university"
    },
    "wallet": {
      "balance": "1500.00"
    },
    "total_courses": 5,
    "completed_courses": 2,
    "certificates_earned": 2
  }
}
```

#### **Update User Profile**
```http
PUT /users/profile
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "contact": "+234-800-123-4567",
  "gender": "male",
  "date_of_birth": "1995-05-15",
  "address": "Lagos, Nigeria",
  "level_id": 4
}
```

#### **Ban User (Admin Only)**
```http
POST /admin/users/{userId}/ban
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "reason": "Violation of terms of service",
  "duration": "30" // days, null for permanent
}
```

#### **Unban User (Admin Only)**
```http
POST /admin/users/{userId}/unban
Authorization: Bearer {token}
```

#### **Get User Activity (Admin Only)**
```http
GET /audit/users/{userId}/activity
Authorization: Bearer {token}
```

**Query Parameters:**
- `page` (int) - Page number
- `per_page` (int) - Items per page
- `action` (string) - Filter by action type
- `date_from` (date) - Start date (YYYY-MM-DD)
- `date_to` (date) - End date (YYYY-MM-DD)

#### **Get User Badges**
```http
GET /users/{userId}/badges
Authorization: Bearer {token}
```

### **📚 COURSES**

#### **Get All Courses (Public)**
```http
GET /courses?page=1&per_page=15&category_id=1&level_id=2&difficulty=beginner
```

**Query Parameters:**
- `page` (int) - Page number (default: 1)
- `per_page` (int) - Items per page (default: 15, max: 50)
- `category_id` (int) - Filter by category
- `level_id` (int) - Filter by academic level
- `difficulty` (string) - beginner, intermediate, advanced
- `price_min` (decimal) - Minimum price
- `price_max` (decimal) - Maximum price
- `search` (string) - Search in title/description

#### **Get Single Course**
```http
GET /courses/{id}
```

#### **Create Course** (Instructor/Admin only)
```http
POST /courses
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "title": "Introduction to Mathematics",
  "description": "Learn basic mathematical concepts",
  "category_id": 1,
  "term_id": 1,
  "level_id": 1,
  "price": 99.99,
  "difficulty": "beginner",
  "duration_hours": 40,
  "max_students": 100
}
```

#### **Update Course**
```http
PUT /courses/{id}
Authorization: Bearer {token}
```

#### **Delete Course**
```http
DELETE /courses/{id}
Authorization: Bearer {token}
```

#### **Enroll in Course**
```http
POST /courses/{id}/enroll
Authorization: Bearer {token}
```

#### **Get My Courses**
```http
GET /courses/my-courses
Authorization: Bearer {token}
```

### **📖 LESSONS**

#### **Get Course Lessons**
```http
GET /courses/{courseId}/lessons
Authorization: Bearer {token}
```

#### **Get Single Lesson**
```http
GET /lessons/{id}
Authorization: Bearer {token}
```

#### **Create Lesson**
```http
POST /courses/{courseId}/lessons
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "title": "Lesson 1: Introduction",
  "content": "Lesson content here...",
  "video_url": "https://youtube.com/watch?v=abc123",
  "duration_minutes": 30,
  "order": 1,
  "is_free": false
}
```

#### **Mark Lesson Complete**
```http
POST /lessons/{id}/complete
Authorization: Bearer {token}
```

### **📝 QUIZZES**

#### **Get Lesson Quizzes**
```http
GET /lessons/{lessonId}/quizzes
Authorization: Bearer {token}
```

#### **Start Quiz Attempt**
```http
POST /quizzes/{id}/start
Authorization: Bearer {token}
```

#### **Submit Quiz**
```http
POST /quizzes/{id}/submit
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "answers": [
    {
      "question_id": 1,
      "answer": "Option A"
    },
    {
      "question_id": 2,
      "answer": "Option C"
    }
  ]
}
```

### **📊 USER DASHBOARD**

#### **Student Dashboard**
```http
GET /dashboard/student
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "enrolled_courses": 5,
    "completed_courses": 2,
    "certificates_earned": 2,
    "total_study_hours": 45.5,
    "current_streak": 7,
    "recent_courses": [...],
    "upcoming_assignments": [...],
    "progress_summary": {
      "overall_progress": 65,
      "courses_in_progress": 3
    }
  }
}
```

#### **Instructor Dashboard**
```http
GET /dashboard/instructor
Authorization: Bearer {token}
```

#### **Admin Dashboard**
```http
GET /dashboard/admin
Authorization: Bearer {token}
```

### **💰 PAYMENTS**

#### **Get Payment Gateways**
```http
GET /payments/gateways
Authorization: Bearer {token}
```

#### **Initialize Course Payment**
```http
POST /payments/purchase-course
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "course_id": 1,
  "gateway": "paystack",
  "amount": 99.99,
  "currency": "NGN"
}
```

#### **Payment History**
```http
GET /payments/history?page=1&per_page=15
Authorization: Bearer {token}
```

### **🎖️ CERTIFICATES**

#### **Get User Certificates**
```http
GET /certificates
Authorization: Bearer {token}
```

#### **Download Certificate**
```http
GET /certificates/{id}/download
Authorization: Bearer {token}
```

#### **Verify Certificate (Public)**
```http
GET /certificates/verify/{certificateNumber}
```

---

## 📋 **DATA MODELS**

### **User Model**
```json
{
  "id": 1,
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "role": "student",
  "is_active": true,
  "email_verified_at": "2025-01-01T00:00:00.000000Z",
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z"
}
```

### **Course Model**
```json
{
  "id": 1,
  "title": "Introduction to Mathematics",
  "description": "Learn basic mathematical concepts",
  "category_id": 1,
  "instructor_id": 2,
  "term_id": 1,
  "level_id": 1,
  "price": "99.99",
  "status": "published",
  "difficulty": "beginner",
  "duration_hours": 40,
  "max_students": 100,
  "published_at": "2025-01-01T00:00:00.000000Z",
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z",
  "category": {
    "id": 1,
    "title": "Mathematics",
    "description": "Mathematical subjects"
  },
  "instructor": {
    "id": 2,
    "first_name": "Jane",
    "last_name": "Smith",
    "email": "jane@example.com"
  }
}
```

### **Lesson Model**
```json
{
  "id": 1,
  "course_id": 1,
  "title": "Lesson 1: Introduction",
  "content": "Lesson content here...",
  "video_url": "https://youtube.com/watch?v=abc123",
  "duration_minutes": 30,
  "order": 1,
  "is_free": false,
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z"
}
```

### **Enrollment Model**
```json
{
  "id": 1,
  "user_id": 1,
  "course_id": 1,
  "progress": 65,
  "status": "active",
  "enrolled_at": "2025-01-01T00:00:00.000000Z",
  "completed_at": null,
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z"
}
```

### **Payment Model**
```json
{
  "id": 1,
  "user_id": 1,
  "course_id": 1,
  "amount": "99.99",
  "currency": "NGN",
  "gateway": "paystack",
  "gateway_reference": "ref_abc123",
  "type": "course_purchase",
  "status": "completed",
  "completed_at": "2025-01-01T00:00:00.000000Z",
  "created_at": "2025-01-01T00:00:00.000000Z"
}
```

### **Quiz Model**
```json
{
  "id": 1,
  "lesson_id": 1,
  "title": "Lesson 1 Quiz",
  "type": "multiple_choice",
  "time_limit_minutes": 30,
  "max_attempts": 3,
  "passing_score": 70,
  "shuffle_questions": true,
  "questions": [
    {
      "id": 1,
      "question_text": "What is 2 + 2?",
      "type": "multiple_choice",
      "options": ["3", "4", "5", "6"],
      "correct_answer": "4"
    }
  ]
}
```

---

## 🔍 **ADVANCED ENDPOINTS**

### **🔍 SEARCH**

#### **Global Search**
```http
GET /search/global?q=mathematics&type=courses&page=1
Authorization: Bearer {token}
```

**Query Parameters:**
- `q` (string) - Search query
- `type` (string) - courses, users, content
- `category_id` (int) - Filter by category
- `level_id` (int) - Filter by level

#### **Course Search**
```http
GET /search/courses?q=calculus&difficulty=intermediate
Authorization: Bearer {token}
```

### **📊 ANALYTICS**

#### **Learning Analytics** (Instructor/Admin)
```http
GET /analytics/learning?course_id=1&date_from=2025-01-01&date_to=2025-01-31
Authorization: Bearer {token}
```

#### **Course Performance** (Instructor/Admin)
```http
GET /analytics/course-performance/{courseId}
Authorization: Bearer {token}
```

### **🎯 RECOMMENDATIONS**

#### **Get Personalized Recommendations**
```http
GET /recommendations
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "recommended_courses": [
      {
        "course": {...},
        "reason": "Based on your interest in Mathematics",
        "confidence_score": 0.85
      }
    ],
    "learning_paths": [...],
    "instructors": [...]
  }
}
```

### **💬 AI CHAT**

#### **Start Chat Session**
```http
POST /chat/start
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "context": "course_help",
  "course_id": 1,
  "initial_message": "I need help with this lesson"
}
```

#### **Send Message**
```http
POST /chat/sessions/{sessionId}/message
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "message": "Can you explain quadratic equations?"
}
```

### **🏆 BADGES & ACHIEVEMENTS**

#### **Get User Badges**
```http
GET /my-badges
Authorization: Bearer {token}
```

#### **Get Leaderboard**
```http
GET /badges/leaderboard?type=monthly&limit=10
Authorization: Bearer {token}
```

### **📁 FILE MANAGEMENT**

#### **Upload File**
```http
POST /files/upload
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Form Data:**
- `file` (file) - File to upload
- `type` (string) - assignment, profile_picture, course_material
- `course_id` (int) - Optional, for course materials

#### **Download File**
```http
GET /files/download/{id}
Authorization: Bearer {token}
```

### **🔔 NOTIFICATIONS**

#### **Get Notifications**
```http
GET /notifications?page=1&unread_only=true
Authorization: Bearer {token}
```

#### **Mark as Read**
```http
PUT /notifications/{id}/read
Authorization: Bearer {token}
```

#### **Update Notification Preferences**
```http
PUT /notifications/preferences
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "email_notifications": true,
  "push_notifications": true,
  "course_updates": true,
  "assignment_reminders": true,
  "marketing_emails": false
}
```

---

## 💡 **USAGE EXAMPLES**

### **Complete Course Enrollment Flow**

```javascript
// 1. Get available courses
const courses = await fetch('/api/courses?category_id=1', {
  headers: { 'Authorization': 'Bearer ' + token }
});

// 2. Get course details
const course = await fetch('/api/courses/1', {
  headers: { 'Authorization': 'Bearer ' + token }
});

// 3. Enroll in course
const enrollment = await fetch('/api/courses/1/enroll', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json'
  }
});

// 4. Get course lessons
const lessons = await fetch('/api/courses/1/lessons', {
  headers: { 'Authorization': 'Bearer ' + token }
});

// 5. Complete a lesson
const completion = await fetch('/api/lessons/1/complete', {
  method: 'POST',
  headers: { 'Authorization': 'Bearer ' + token }
});
```

### **Payment Processing Flow**

```javascript
// 1. Get available payment gateways
const gateways = await fetch('/api/payments/gateways', {
  headers: { 'Authorization': 'Bearer ' + token }
});

// 2. Initialize payment
const payment = await fetch('/api/payments/purchase-course', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    course_id: 1,
    gateway: 'paystack',
    amount: 99.99,
    currency: 'NGN'
  })
});

// 3. Redirect user to payment gateway
window.location.href = payment.data.payment_url;
```

### **Quiz Taking Flow**

```javascript
// 1. Get quiz details
const quiz = await fetch('/api/quizzes/1', {
  headers: { 'Authorization': 'Bearer ' + token }
});

// 2. Start quiz attempt
const attempt = await fetch('/api/quizzes/1/start', {
  method: 'POST',
  headers: { 'Authorization': 'Bearer ' + token }
});

// 3. Submit quiz answers
const result = await fetch('/api/quizzes/1/submit', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    answers: [
      { question_id: 1, answer: "Option A" },
      { question_id: 2, answer: "Option C" }
    ]
  })
});

// 4. Get quiz results
const results = await fetch('/api/quizzes/1/results', {
  headers: { 'Authorization': 'Bearer ' + token }
});
```

---

## 🛡️ **SECURITY CONSIDERATIONS**

### **Authentication**
- Use HTTPS for all API calls
- Store tokens securely (not in localStorage for sensitive apps)
- Implement token refresh mechanism
- Handle 401 responses by redirecting to login

### **Rate Limiting**
- Implement exponential backoff for rate limit errors
- Cache responses when appropriate
- Use pagination for large datasets

### **Data Validation**
- Always validate user input on frontend
- Handle validation errors gracefully
- Display user-friendly error messages

---

## 📚 **COMPLETE ENDPOINT REFERENCE**

### **Authentication Endpoints**
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/register` | Register new user | No |
| POST | `/login` | Login user | No |
| GET | `/user` | Get current user | Yes |
| POST | `/logout` | Logout user | Yes |
| POST | `/forgot-password` | Send password reset | No |
| POST | `/reset-password` | Reset password | No |

### **User Management Endpoints**
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/admin/users` | Get all users | Yes (Admin) |
| GET | `/search/users` | Search users | Yes |
| GET | `/users/profile` | Get user profile | Yes |
| PUT | `/users/profile` | Update user profile | Yes |
| GET | `/users/dashboard` | Get user dashboard | Yes |
| GET | `/users/achievements` | Get user achievements | Yes |
| GET | `/users/learning-stats` | Get learning statistics | Yes |
| PUT | `/users/preferences` | Update user preferences | Yes |
| POST | `/users/change-password` | Change password | Yes |
| POST | `/admin/users/{userId}/ban` | Ban user | Yes (Admin) |
| POST | `/admin/users/{userId}/unban` | Unban user | Yes (Admin) |
| GET | `/audit/users/{userId}/activity` | Get user activity | Yes (Admin) |
| GET | `/users/{userId}/badges` | Get user badges | Yes |

### **Course Endpoints**
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/courses` | Get all courses | No |
| GET | `/courses/{id}` | Get single course | No |
| POST | `/courses` | Create course | Yes (Instructor/Admin) |
| PUT | `/courses/{id}` | Update course | Yes (Instructor/Admin) |
| DELETE | `/courses/{id}` | Delete course | Yes (Instructor/Admin) |
| POST | `/courses/{id}/enroll` | Enroll in course | Yes |
| DELETE | `/courses/{id}/unenroll` | Unenroll from course | Yes |
| GET | `/courses/my-courses` | Get user's courses | Yes |
| GET | `/courses/search` | Search courses | No |
| GET | `/courses/featured` | Get featured courses | No |
| GET | `/courses/popular` | Get popular courses | No |

### **Lesson Endpoints**
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/courses/{courseId}/lessons` | Get course lessons | Yes |
| POST | `/courses/{courseId}/lessons` | Create lesson | Yes (Instructor/Admin) |
| GET | `/lessons/{id}` | Get single lesson | Yes |
| PUT | `/lessons/{id}` | Update lesson | Yes (Instructor/Admin) |
| DELETE | `/lessons/{id}` | Delete lesson | Yes (Instructor/Admin) |
| POST | `/lessons/{id}/complete` | Mark lesson complete | Yes |
| GET | `/lessons/{id}/progress` | Get lesson progress | Yes |

### **Quiz Endpoints**
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/lessons/{lessonId}/quizzes` | Get lesson quizzes | Yes |
| POST | `/lessons/{lessonId}/quizzes` | Create quiz | Yes (Instructor/Admin) |
| GET | `/quizzes/{id}` | Get single quiz | Yes |
| PUT | `/quizzes/{id}` | Update quiz | Yes (Instructor/Admin) |
| DELETE | `/quizzes/{id}` | Delete quiz | Yes (Instructor/Admin) |
| POST | `/quizzes/{id}/start` | Start quiz attempt | Yes |
| POST | `/quizzes/{id}/submit` | Submit quiz | Yes |
| GET | `/quizzes/{id}/results` | Get quiz results | Yes |

### **Payment Endpoints**
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/payments/gateways` | Get payment gateways | Yes |
| POST | `/payments/purchase-course` | Initialize course payment | Yes |
| POST | `/payments/deposit` | Initialize wallet deposit | Yes |
| GET | `/payments/history` | Get payment history | Yes |
| GET | `/payments/{id}` | Get single payment | Yes |

### **Dashboard Endpoints**
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/dashboard/student` | Student dashboard | Yes (Student) |
| GET | `/dashboard/instructor` | Instructor dashboard | Yes (Instructor) |
| GET | `/dashboard/admin` | Admin dashboard | Yes (Admin) |

---

## 🔧 **SDK & LIBRARIES**

### **JavaScript/TypeScript SDK**

```javascript
// Install: npm install @kokokah/api-sdk

import { KokokahAPI } from '@kokokah/api-sdk';

const api = new KokokahAPI({
  baseURL: 'https://api.kokokah.com/api',
  token: 'your-auth-token'
});

// Usage examples
const courses = await api.courses.getAll({ category_id: 1 });
const course = await api.courses.get(1);
await api.courses.enroll(1);
const lessons = await api.lessons.getByCourse(1);
await api.lessons.complete(1);
```

### **React Hooks**

```javascript
// Install: npm install @kokokah/react-hooks

import { useCourses, useCourse, useEnrollment } from '@kokokah/react-hooks';

function CoursePage({ courseId }) {
  const { data: course, loading, error } = useCourse(courseId);
  const { enroll, enrolling } = useEnrollment();

  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error.message}</div>;

  return (
    <div>
      <h1>{course.title}</h1>
      <button
        onClick={() => enroll(courseId)}
        disabled={enrolling}
      >
        {enrolling ? 'Enrolling...' : 'Enroll Now'}
      </button>
    </div>
  );
}
```

---

## 📞 **SUPPORT**

- **Documentation:** [https://docs.kokokah.com](https://docs.kokokah.com)
- **Support Email:** api-support@kokokah.com
- **Status Page:** [https://status.kokokah.com](https://status.kokokah.com)
- **GitHub:** [https://github.com/kokokah/api-examples](https://github.com/kokokah/api-examples)

---

**© 2025 Kokokah.com - All rights reserved**
