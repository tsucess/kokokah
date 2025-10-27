# Kokokah LMS - Complete API Documentation with Frontend Examples

**Last Updated:** October 26, 2025  
**Total Endpoints:** 150+  
**Status:** ✅ Production Ready

---

## 📋 Quick Navigation

- [Authentication](#authentication)
- [Courses](#courses)
- [Lessons](#lessons)
- [Quizzes](#quizzes)
- [Assignments](#assignments)
- [Wallet & Payments](#wallet--payments)
- [Certificates & Badges](#certificates--badges)
- [Progress & Grading](#progress--grading)
- [Reviews & Forum](#reviews--forum)
- [Learning Paths](#learning-paths)
- [Admin](#admin)
- [Analytics](#analytics)
- [Notifications](#notifications)
- [Search](#search)
- [Files](#files)
- [Language](#language)
- [Chat](#chat)
- [Recommendations](#recommendations)
- [Coupons](#coupons)
- [Reports](#reports)

---

## 🔐 Authentication

### 1. Register User

**Endpoint:**
```
POST /api/register
Content-Type: application/json
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
      "role": "student"
    },
    "token": "1|abc123..."
  }
}
```

**Frontend - React (Fetch API):**
```javascript
const register = async (userData) => {
  try {
    const response = await fetch('/api/register', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(userData)
    });
    const data = await response.json();
    if (data.success) {
      localStorage.setItem('token', data.data.token);
      localStorage.setItem('user', JSON.stringify(data.data.user));
    }
    return data;
  } catch (error) {
    console.error('Registration error:', error);
    return { success: false, message: error.message };
  }
};

// Usage
register({
  first_name: 'John',
  last_name: 'Doe',
  email: 'john@example.com',
  password: 'password123',
  password_confirmation: 'password123',
  role: 'student'
});
```

**Frontend - React (Axios):**
```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api'
});

const register = async (userData) => {
  try {
    const response = await api.post('/register', userData);
    if (response.data.success) {
      localStorage.setItem('token', response.data.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.data.user));
    }
    return response.data;
  } catch (error) {
    return { success: false, message: error.response?.data?.message };
  }
};
```

**Frontend - React Hook:**
```javascript
import { useState } from 'react';

const useRegister = () => {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const register = async (userData) => {
    setLoading(true);
    setError(null);
    try {
      const response = await fetch('/api/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(userData)
      });
      const data = await response.json();
      if (data.success) {
        localStorage.setItem('token', data.data.token);
        localStorage.setItem('user', JSON.stringify(data.data.user));
      } else {
        setError(data.message);
      }
      return data;
    } catch (err) {
      setError(err.message);
      return { success: false };
    } finally {
      setLoading(false);
    }
  };

  return { register, loading, error };
};
```

---

### 2. Login User

**Endpoint:**
```
POST /api/login
Content-Type: application/json
```

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "first_name": "John",
      "email": "john@example.com",
      "role": "student"
    },
    "token": "1|abc123..."
  }
}
```

**Frontend - React:**
```javascript
const useLogin = () => {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const login = async (email, password) => {
    setLoading(true);
    setError(null);
    try {
      const response = await fetch('/api/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
      });
      const data = await response.json();
      if (data.success) {
        localStorage.setItem('token', data.data.token);
        localStorage.setItem('user', JSON.stringify(data.data.user));
        // Redirect to dashboard
        window.location.href = '/dashboard';
      } else {
        setError(data.message);
      }
      return data;
    } catch (err) {
      setError(err.message);
      return { success: false };
    } finally {
      setLoading(false);
    }
  };

  return { login, loading, error };
};
```

---

### 3. Get Current User

**Endpoint:**
```
GET /api/user
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "role": "student",
    "avatar": "url",
    "bio": "I am a student"
  }
}
```

**Frontend - React:**
```javascript
const getCurrentUser = async (token) => {
  try {
    const response = await fetch('/api/user', {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    return response.json();
  } catch (error) {
    console.error('Error fetching user:', error);
    return { success: false };
  }
};

// In a React component
import { useEffect, useState } from 'react';

function UserProfile() {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const token = localStorage.getItem('token');
    if (token) {
      getCurrentUser(token).then(data => {
        if (data.success) {
          setUser(data.data);
        }
        setLoading(false);
      });
    }
  }, []);

  if (loading) return <div>Loading...</div>;
  if (!user) return <div>No user found</div>;

  return (
    <div>
      <h1>{user.first_name} {user.last_name}</h1>
      <p>Email: {user.email}</p>
      <p>Role: {user.role}</p>
    </div>
  );
}
```

---

### 4. Logout

**Endpoint:**
```
POST /api/logout
Authorization: Bearer {token}
```

**Frontend - React:**
```javascript
const logout = async (token) => {
  try {
    await fetch('/api/logout', {
      method: 'POST',
      headers: { 'Authorization': `Bearer ${token}` }
    });
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = '/login';
  } catch (error) {
    console.error('Logout error:', error);
  }
};
```

---

### 5. Send Verification Code

**Endpoint:**
```
POST /api/email/send-verification-code
Content-Type: application/json
```

**Request Body:**
```json
{
  "email": "john@example.com"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Verification code sent to your email"
}
```

**Frontend - React:**
```javascript
const sendVerificationCode = async (email) => {
  try {
    const response = await fetch('/api/email/send-verification-code', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email })
    });
    return response.json();
  } catch (error) {
    return { success: false, message: error.message };
  }
};
```

---

### 6. Verify Email with Code

**Endpoint:**
```
POST /api/email/verify-with-code
Content-Type: application/json
```

**Request Body:**
```json
{
  "email": "john@example.com",
  "code": "ABC123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Email verified successfully"
}
```

**Frontend - React:**
```javascript
const verifyEmailWithCode = async (email, code) => {
  try {
    const response = await fetch('/api/email/verify-with-code', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, code })
    });
    return response.json();
  } catch (error) {
    return { success: false, message: error.message };
  }
};

// In a React component
function VerificationForm() {
  const [email, setEmail] = useState('');
  const [code, setCode] = useState('');
  const [step, setStep] = useState('send'); // 'send' or 'verify'

  const handleSendCode = async () => {
    const result = await sendVerificationCode(email);
    if (result.success) {
      setStep('verify');
    }
  };

  const handleVerify = async () => {
    const result = await verifyEmailWithCode(email, code);
    if (result.success) {
      alert('Email verified!');
    }
  };

  return (
    <div>
      {step === 'send' ? (
        <>
          <input
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            placeholder="Enter your email"
          />
          <button onClick={handleSendCode}>Send Code</button>
        </>
      ) : (
        <>
          <input
            type="text"
            value={code}
            onChange={(e) => setCode(e.target.value)}
            placeholder="Enter verification code"
          />
          <button onClick={handleVerify}>Verify</button>
        </>
      )}
    </div>
  );
}
```

---

## 📚 Courses

### 1. Get All Courses (Public)

**Endpoint:**
```
GET /api/courses?page=1&per_page=10
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Python Basics",
      "description": "Learn Python from scratch",
      "price": 99.99,
      "level": "beginner",
      "instructor": {
        "id": 1,
        "name": "John Instructor"
      },
      "students_count": 150,
      "rating": 4.5
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 100,
    "last_page": 10
  }
}
```

**Frontend - React:**
```javascript
import { useState, useEffect } from 'react';

function CoursesList() {
  const [courses, setCourses] = useState([]);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);

  useEffect(() => {
    const fetchCourses = async () => {
      try {
        const response = await fetch(`/api/courses?page=${page}&per_page=10`);
        const data = await response.json();
        if (data.success) {
          setCourses(data.data);
        }
      } catch (error) {
        console.error('Error fetching courses:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchCourses();
  }, [page]);

  if (loading) return <div>Loading courses...</div>;

  return (
    <div>
      <h1>Available Courses</h1>
      <div className="courses-grid">
        {courses.map(course => (
          <div key={course.id} className="course-card">
            <h3>{course.title}</h3>
            <p>{course.description}</p>
            <p>Price: ${course.price}</p>
            <p>Level: {course.level}</p>
            <p>Rating: {course.rating}/5 ({course.students_count} students)</p>
            <button onClick={() => enrollCourse(course.id)}>Enroll Now</button>
          </div>
        ))}
      </div>
      <button onClick={() => setPage(page - 1)} disabled={page === 1}>Previous</button>
      <button onClick={() => setPage(page + 1)}>Next</button>
    </div>
  );
}
```

---

### 2. Search Courses

**Endpoint:**
```
GET /api/courses/search?q=python&category=1&level=beginner
```

**Frontend - React:**
```javascript
function CourseSearch() {
  const [query, setQuery] = useState('');
  const [results, setResults] = useState([]);
  const [loading, setLoading] = useState(false);

  const handleSearch = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      const response = await fetch(`/api/courses/search?q=${query}`);
      const data = await response.json();
      if (data.success) {
        setResults(data.data);
      }
    } catch (error) {
      console.error('Search error:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div>
      <form onSubmit={handleSearch}>
        <input
          type="text"
          value={query}
          onChange={(e) => setQuery(e.target.value)}
          placeholder="Search courses..."
        />
        <button type="submit">Search</button>
      </form>
      {loading && <p>Searching...</p>}
      <div>
        {results.map(course => (
          <div key={course.id}>
            <h3>{course.title}</h3>
            <p>{course.description}</p>
          </div>
        ))}
      </div>
    </div>
  );
}
```

---

### 3. Get Featured Courses

**Endpoint:**
```
GET /api/courses/featured
```

**Frontend - React:**
```javascript
function FeaturedCourses() {
  const [courses, setCourses] = useState([]);

  useEffect(() => {
    fetch('/api/courses/featured')
      .then(res => res.json())
      .then(data => {
        if (data.success) setCourses(data.data);
      });
  }, []);

  return (
    <div>
      <h2>Featured Courses</h2>
      {courses.map(course => (
        <div key={course.id}>
          <h3>{course.title}</h3>
          <p>{course.description}</p>
        </div>
      ))}
    </div>
  );
}
```

---

### 4. Get My Courses

**Endpoint:**
```
GET /api/courses/my-courses
Authorization: Bearer {token}
```

**Frontend - React:**
```javascript
function MyCourses() {
  const [courses, setCourses] = useState([]);
  const token = localStorage.getItem('token');

  useEffect(() => {
    fetch('/api/courses/my-courses', {
      headers: { 'Authorization': `Bearer ${token}` }
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) setCourses(data.data);
      });
  }, [token]);

  return (
    <div>
      <h2>My Courses</h2>
      {courses.map(course => (
        <div key={course.id}>
          <h3>{course.title}</h3>
          <p>Progress: {course.progress}%</p>
          <a href={`/course/${course.id}`}>Continue Learning</a>
        </div>
      ))}
    </div>
  );
}
```

---

### 5. Create Course (Instructor)

**Endpoint:**
```
POST /api/courses
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "title": "Advanced Python",
  "description": "Learn advanced Python concepts",
  "category_id": 1,
  "price": 199.99,
  "level": "advanced"
}
```

**Frontend - React:**
```javascript
function CreateCourse() {
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    category_id: '',
    price: '',
    level: 'beginner'
  });
  const token = localStorage.getItem('token');

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch('/api/courses', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
      });
      const data = await response.json();
      if (data.success) {
        alert('Course created successfully!');
        // Redirect to course page
      }
    } catch (error) {
      console.error('Error creating course:', error);
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <input
        type="text"
        placeholder="Course Title"
        value={formData.title}
        onChange={(e) => setFormData({...formData, title: e.target.value})}
        required
      />
      <textarea
        placeholder="Description"
        value={formData.description}
        onChange={(e) => setFormData({...formData, description: e.target.value})}
        required
      />
      <input
        type="number"
        placeholder="Price"
        value={formData.price}
        onChange={(e) => setFormData({...formData, price: e.target.value})}
        required
      />
      <select
        value={formData.level}
        onChange={(e) => setFormData({...formData, level: e.target.value})}
      >
        <option value="beginner">Beginner</option>
        <option value="intermediate">Intermediate</option>
        <option value="advanced">Advanced</option>
      </select>
      <button type="submit">Create Course</button>
    </form>
  );
}
```

---

### 6. Enroll in Course

**Endpoint:**
```
POST /api/courses/{id}/enroll
Authorization: Bearer {token}
```

**Frontend - React:**
```javascript
const enrollCourse = async (courseId) => {
  const token = localStorage.getItem('token');
  try {
    const response = await fetch(`/api/courses/${courseId}/enroll`, {
      method: 'POST',
      headers: { 'Authorization': `Bearer ${token}` }
    });
    const data = await response.json();
    if (data.success) {
      alert('Enrolled successfully!');
      // Redirect to course
      window.location.href = `/course/${courseId}`;
    } else {
      alert(data.message);
    }
  } catch (error) {
    console.error('Enrollment error:', error);
  }
};
```

---

## 💰 Wallet & Payments

### 1. Get Wallet Balance

**Endpoint:**
```
GET /api/wallet
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "balance": 500.00,
    "currency": "NGN",
    "transactions_count": 10
  }
}
```

**Frontend - React:**
```javascript
function WalletBalance() {
  const [wallet, setWallet] = useState(null);
  const token = localStorage.getItem('token');

  useEffect(() => {
    fetch('/api/wallet', {
      headers: { 'Authorization': `Bearer ${token}` }
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) setWallet(data.data);
      });
  }, [token]);

  if (!wallet) return <div>Loading...</div>;

  return (
    <div className="wallet-card">
      <h3>Wallet Balance</h3>
      <p className="balance">{wallet.currency} {wallet.balance.toFixed(2)}</p>
      <p>Total Transactions: {wallet.transactions_count}</p>
    </div>
  );
}
```

---

### 2. Initialize Payment

**Endpoint:**
```
POST /api/payments/purchase-course
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "course_id": 1,
  "gateway": "paystack"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "authorization_url": "https://checkout.paystack.com/...",
    "access_code": "...",
    "reference": "..."
  }
}
```

**Frontend - React:**
```javascript
function PaymentButton({ courseId }) {
  const [loading, setLoading] = useState(false);
  const token = localStorage.getItem('token');

  const handlePayment = async () => {
    setLoading(true);
    try {
      const response = await fetch('/api/payments/purchase-course', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          course_id: courseId,
          gateway: 'paystack'
        })
      });
      const data = await response.json();
      if (data.success) {
        // Redirect to payment gateway
        window.location.href = data.data.authorization_url;
      }
    } catch (error) {
      console.error('Payment error:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <button onClick={handlePayment} disabled={loading}>
      {loading ? 'Processing...' : 'Pay Now'}
    </button>
  );
}
```

---

## 🎯 Quizzes

### 1. Start Quiz

**Endpoint:**
```
POST /api/quizzes/{id}/start
Authorization: Bearer {token}
```

**Frontend - React:**
```javascript
function QuizStart({ quizId }) {
  const [quiz, setQuiz] = useState(null);
  const [started, setStarted] = useState(false);
  const token = localStorage.getItem('token');

  const handleStart = async () => {
    try {
      const response = await fetch(`/api/quizzes/${quizId}/start`, {
        method: 'POST',
        headers: { 'Authorization': `Bearer ${token}` }
      });
      const data = await response.json();
      if (data.success) {
        setQuiz(data.data);
        setStarted(true);
      }
    } catch (error) {
      console.error('Error starting quiz:', error);
    }
  };

  if (!started) {
    return <button onClick={handleStart}>Start Quiz</button>;
  }

  return <QuizContent quiz={quiz} />;
}
```

---

### 2. Submit Quiz

**Endpoint:**
```
POST /api/quizzes/{id}/submit
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "answers": {
    "question_1": "answer_a",
    "question_2": "answer_b",
    "question_3": "answer_c"
  }
}
```

**Frontend - React:**
```javascript
function QuizContent({ quiz }) {
  const [answers, setAnswers] = useState({});
  const [submitted, setSubmitted] = useState(false);
  const [result, setResult] = useState(null);
  const token = localStorage.getItem('token');

  const handleAnswerChange = (questionId, answer) => {
    setAnswers({
      ...answers,
      [questionId]: answer
    });
  };

  const handleSubmit = async () => {
    try {
      const response = await fetch(`/api/quizzes/${quiz.id}/submit`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ answers })
      });
      const data = await response.json();
      if (data.success) {
        setResult(data.data);
        setSubmitted(true);
      }
    } catch (error) {
      console.error('Error submitting quiz:', error);
    }
  };

  if (submitted) {
    return (
      <div className="quiz-result">
        <h2>Quiz Results</h2>
        <p>Score: {result.score}/{result.total_questions}</p>
        <p>Percentage: {result.percentage}%</p>
        <p>Status: {result.passed ? 'PASSED' : 'FAILED'}</p>
      </div>
    );
  }

  return (
    <div className="quiz-container">
      <h2>{quiz.title}</h2>
      {quiz.questions.map(question => (
        <div key={question.id} className="question">
          <h4>{question.text}</h4>
          {question.options.map(option => (
            <label key={option.id}>
              <input
                type="radio"
                name={`question_${question.id}`}
                value={option.id}
                onChange={() => handleAnswerChange(`question_${question.id}`, option.id)}
              />
              {option.text}
            </label>
          ))}
        </div>
      ))}
      <button onClick={handleSubmit}>Submit Quiz</button>
    </div>
  );
}
```

---

## 📝 Assignments

### 1. Submit Assignment

**Endpoint:**
```
POST /api/assignments/{id}/submit
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Frontend - React:**
```javascript
function AssignmentSubmit({ assignmentId }) {
  const [file, setFile] = useState(null);
  const [comments, setComments] = useState('');
  const [loading, setLoading] = useState(false);
  const token = localStorage.getItem('token');

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);

    const formData = new FormData();
    formData.append('submission', file);
    formData.append('comments', comments);

    try {
      const response = await fetch(`/api/assignments/${assignmentId}/submit`, {
        method: 'POST',
        headers: { 'Authorization': `Bearer ${token}` },
        body: formData
      });
      const data = await response.json();
      if (data.success) {
        alert('Assignment submitted successfully!');
        setFile(null);
        setComments('');
      }
    } catch (error) {
      console.error('Submission error:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <div>
        <label>Upload File:</label>
        <input
          type="file"
          onChange={(e) => setFile(e.target.files[0])}
          required
        />
      </div>
      <div>
        <label>Comments:</label>
        <textarea
          value={comments}
          onChange={(e) => setComments(e.target.value)}
          placeholder="Add any comments..."
        />
      </div>
      <button type="submit" disabled={loading}>
        {loading ? 'Submitting...' : 'Submit Assignment'}
      </button>
    </form>
  );
}
```

---

## 🎓 Certificates

### 1. Get My Certificates

**Endpoint:**
```
GET /api/certificates
Authorization: Bearer {token}
```

**Frontend - React:**
```javascript
function MyCertificates() {
  const [certificates, setCertificates] = useState([]);
  const token = localStorage.getItem('token');

  useEffect(() => {
    fetch('/api/certificates', {
      headers: { 'Authorization': `Bearer ${token}` }
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) setCertificates(data.data);
      });
  }, [token]);

  return (
    <div>
      <h2>My Certificates</h2>
      {certificates.map(cert => (
        <div key={cert.id} className="certificate-card">
          <h3>{cert.course_title}</h3>
          <p>Issued: {new Date(cert.issued_at).toLocaleDateString()}</p>
          <a href={`/api/certificates/${cert.id}/download`} download>
            Download Certificate
          </a>
        </div>
      ))}
    </div>
  );
}
```

---

## 🔔 Notifications

### 1. Get Notifications

**Endpoint:**
```
GET /api/notifications
Authorization: Bearer {token}
```

**Frontend - React:**
```javascript
function NotificationCenter() {
  const [notifications, setNotifications] = useState([]);
  const token = localStorage.getItem('token');

  useEffect(() => {
    // Fetch notifications
    const fetchNotifications = () => {
      fetch('/api/notifications', {
        headers: { 'Authorization': `Bearer ${token}` }
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) setNotifications(data.data);
        });
    };

    fetchNotifications();
    // Poll for new notifications every 30 seconds
    const interval = setInterval(fetchNotifications, 30000);
    return () => clearInterval(interval);
  }, [token]);

  const markAsRead = async (notificationId) => {
    await fetch(`/api/notifications/${notificationId}/read`, {
      method: 'PUT',
      headers: { 'Authorization': `Bearer ${token}` }
    });
    setNotifications(notifications.filter(n => n.id !== notificationId));
  };

  return (
    <div className="notification-center">
      <h2>Notifications ({notifications.length})</h2>
      {notifications.map(notif => (
        <div key={notif.id} className="notification">
          <h4>{notif.title}</h4>
          <p>{notif.message}</p>
          <button onClick={() => markAsRead(notif.id)}>Mark as Read</button>
        </div>
      ))}
    </div>
  );
}
```

---

## 🔍 Search

### 1. Global Search

**Endpoint:**
```
GET /api/search?q=python
Authorization: Bearer {token}
```

**Frontend - React:**
```javascript
function GlobalSearch() {
  const [query, setQuery] = useState('');
  const [results, setResults] = useState(null);
  const [loading, setLoading] = useState(false);
  const token = localStorage.getItem('token');

  const handleSearch = async (e) => {
    const searchQuery = e.target.value;
    setQuery(searchQuery);

    if (searchQuery.length < 2) {
      setResults(null);
      return;
    }

    setLoading(true);
    try {
      const response = await fetch(`/api/search?q=${searchQuery}`, {
        headers: { 'Authorization': `Bearer ${token}` }
      });
      const data = await response.json();
      if (data.success) {
        setResults(data.data);
      }
    } catch (error) {
      console.error('Search error:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="search-container">
      <input
        type="text"
        value={query}
        onChange={handleSearch}
        placeholder="Search courses, users, content..."
      />
      {loading && <p>Searching...</p>}
      {results && (
        <div className="search-results">
          <div>
            <h4>Courses ({results.courses?.length || 0})</h4>
            {results.courses?.map(course => (
              <div key={course.id}>
                <a href={`/course/${course.id}`}>{course.title}</a>
              </div>
            ))}
          </div>
          <div>
            <h4>Users ({results.users?.length || 0})</h4>
            {results.users?.map(user => (
              <div key={user.id}>
                <a href={`/user/${user.id}`}>{user.name}</a>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}
```

---

## 📁 Files

### 1. Upload File

**Endpoint:**
```
POST /api/files/upload
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Frontend - React:**
```javascript
function FileUpload() {
  const [file, setFile] = useState(null);
  const [uploading, setUploading] = useState(false);
  const [progress, setProgress] = useState(0);
  const token = localStorage.getItem('token');

  const handleUpload = async (e) => {
    e.preventDefault();
    if (!file) return;

    setUploading(true);
    const formData = new FormData();
    formData.append('file', file);

    try {
      const xhr = new XMLHttpRequest();

      xhr.upload.addEventListener('progress', (e) => {
        if (e.lengthComputable) {
          const percentComplete = (e.loaded / e.total) * 100;
          setProgress(percentComplete);
        }
      });

      xhr.addEventListener('load', () => {
        if (xhr.status === 200) {
          const data = JSON.parse(xhr.responseText);
          if (data.success) {
            alert('File uploaded successfully!');
            setFile(null);
            setProgress(0);
          }
        }
      });

      xhr.open('POST', '/api/files/upload');
      xhr.setRequestHeader('Authorization', `Bearer ${token}`);
      xhr.send(formData);
    } catch (error) {
      console.error('Upload error:', error);
    } finally {
      setUploading(false);
    }
  };

  return (
    <form onSubmit={handleUpload}>
      <input
        type="file"
        onChange={(e) => setFile(e.target.files[0])}
        disabled={uploading}
      />
      {progress > 0 && <progress value={progress} max="100"></progress>}
      <button type="submit" disabled={!file || uploading}>
        {uploading ? `Uploading ${progress.toFixed(0)}%` : 'Upload'}
      </button>
    </form>
  );
}
```

---

## 🌍 Language

### 1. Set User Language

**Endpoint:**
```
POST /api/language/user/set
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "locale": "ha"
}
```

**Frontend - React:**
```javascript
function LanguageSwitcher() {
  const [language, setLanguage] = useState('en');
  const token = localStorage.getItem('token');

  const handleLanguageChange = async (locale) => {
    try {
      const response = await fetch('/api/language/user/set', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ locale })
      });
      const data = await response.json();
      if (data.success) {
        setLanguage(locale);
        // Reload page to apply language changes
        window.location.reload();
      }
    } catch (error) {
      console.error('Language change error:', error);
    }
  };

  return (
    <div className="language-switcher">
      <select value={language} onChange={(e) => handleLanguageChange(e.target.value)}>
        <option value="en">English</option>
        <option value="ha">Hausa</option>
        <option value="yo">Yoruba</option>
        <option value="ig">Igbo</option>
        <option value="fr">French</option>
        <option value="ar">Arabic</option>
      </select>
    </div>
  );
}
```

---

## 💬 Chat

### 1. Start Chat Session

**Endpoint:**
```
POST /api/chat/start
Authorization: Bearer {token}
Content-Type: application/json
```

**Frontend - React:**
```javascript
function ChatInterface() {
  const [messages, setMessages] = useState([]);
  const [input, setInput] = useState('');
  const [sessionId, setSessionId] = useState(null);
  const token = localStorage.getItem('token');

  const startChat = async () => {
    try {
      const response = await fetch('/api/chat/start', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ topic: 'Learning Help' })
      });
      const data = await response.json();
      if (data.success) {
        setSessionId(data.data.id);
      }
    } catch (error) {
      console.error('Chat error:', error);
    }
  };

  const sendMessage = async () => {
    if (!input.trim() || !sessionId) return;

    const userMessage = { role: 'user', content: input };
    setMessages([...messages, userMessage]);
    setInput('');

    try {
      const response = await fetch(`/api/chat/sessions/${sessionId}/message`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ message: input })
      });
      const data = await response.json();
      if (data.success) {
        setMessages(prev => [...prev, { role: 'assistant', content: data.data.response }]);
      }
    } catch (error) {
      console.error('Message error:', error);
    }
  };

  if (!sessionId) {
    return <button onClick={startChat}>Start Chat</button>;
  }

  return (
    <div className="chat-interface">
      <div className="messages">
        {messages.map((msg, idx) => (
          <div key={idx} className={`message ${msg.role}`}>
            {msg.content}
          </div>
        ))}
      </div>
      <div className="input-area">
        <input
          type="text"
          value={input}
          onChange={(e) => setInput(e.target.value)}
          onKeyPress={(e) => e.key === 'Enter' && sendMessage()}
          placeholder="Type your message..."
        />
        <button onClick={sendMessage}>Send</button>
      </div>
    </div>
  );
}
```

---

## 🎁 Coupons

### 1. Apply Coupon

**Endpoint:**
```
POST /api/coupons/apply
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "code": "SAVE20",
  "course_id": 1
}
```

**Frontend - React:**
```javascript
function CouponApply({ courseId, coursePrice }) {
  const [couponCode, setCouponCode] = useState('');
  const [discount, setDiscount] = useState(null);
  const [loading, setLoading] = useState(false);
  const token = localStorage.getItem('token');

  const applyCoupon = async () => {
    setLoading(true);
    try {
      const response = await fetch('/api/coupons/apply', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          code: couponCode,
          course_id: courseId
        })
      });
      const data = await response.json();
      if (data.success) {
        setDiscount(data.data);
      } else {
        alert(data.message);
      }
    } catch (error) {
      console.error('Coupon error:', error);
    } finally {
      setLoading(false);
    }
  };

  const finalPrice = discount
    ? coursePrice - (discount.discount_type === 'percentage'
        ? coursePrice * (discount.discount_value / 100)
        : discount.discount_value)
    : coursePrice;

  return (
    <div className="coupon-section">
      <div className="coupon-input">
        <input
          type="text"
          value={couponCode}
          onChange={(e) => setCouponCode(e.target.value)}
          placeholder="Enter coupon code"
        />
        <button onClick={applyCoupon} disabled={loading}>
          {loading ? 'Applying...' : 'Apply'}
        </button>
      </div>
      {discount && (
        <div className="discount-info">
          <p>Original Price: ${coursePrice}</p>
          <p>Discount: ${discount.discount_type === 'percentage'
            ? (coursePrice * (discount.discount_value / 100)).toFixed(2)
            : discount.discount_value}</p>
          <p className="final-price">Final Price: ${finalPrice.toFixed(2)}</p>
        </div>
      )}
    </div>
  );
}
```

---

## 📊 Response Format

All endpoints return JSON responses in the following format:

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {
    // Response data
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Error details"]
  }
}
```

---

## 🔑 Authentication

All authenticated endpoints require a Bearer token in the Authorization header:

```
Authorization: Bearer {token}
```

---

## ✅ Status Codes

- `200` - OK
- `201` - Created
- `204` - No Content
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Unprocessable Entity
- `429` - Too Many Requests
- `500` - Server Error

---

*Documentation Last Updated: October 26, 2025*  
*Total Endpoints: 150+*  
*Status: ✅ Production Ready*

