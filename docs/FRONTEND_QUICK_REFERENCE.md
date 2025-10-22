# 🚀 **FRONTEND DEVELOPER QUICK REFERENCE**

## 🔗 **Base Configuration**

```javascript
const API_BASE_URL = 'https://api.kokokah.com/api';
const API_HEADERS = {
  'Content-Type': 'application/json',
  'Accept': 'application/json'
};

// With authentication
const authHeaders = (token) => ({
  ...API_HEADERS,
  'Authorization': `Bearer ${token}`
});
```

---

## 👥 **User Management**

### **Get All Users (Admin Only)**
```javascript
const getAllUsers = async (page = 1, filters = {}) => {
  const params = new URLSearchParams({ page, ...filters });
  const response = await fetch(`${API_BASE_URL}/admin/users?${params}`, {
    headers: authHeaders(localStorage.getItem('auth_token'))
  });

  const data = await response.json();
  if (data.success) return data.data;
  throw new Error(data.message);
};

// Usage examples
const allUsers = await getAllUsers();
const activeStudents = await getAllUsers(1, { role: 'student', is_active: true });
const page2Users = await getAllUsers(2);
```

### **Search Users**
```javascript
const searchUsers = async (query, role = null, page = 1) => {
  const params = new URLSearchParams({ q: query, page });
  if (role) params.append('role', role);

  const response = await fetch(`${API_BASE_URL}/search/users?${params}`, {
    headers: authHeaders(localStorage.getItem('auth_token'))
  });

  const data = await response.json();
  if (data.success) return data.data;
  throw new Error(data.message);
};

// Usage examples
const students = await searchUsers('', 'student');
const mathInstructors = await searchUsers('math', 'instructor');
const johnUsers = await searchUsers('john');
```

### **Get User Profile**
```javascript
const getUserProfile = async () => {
  const response = await fetch(`${API_BASE_URL}/users/profile`, {
    headers: authHeaders(localStorage.getItem('auth_token'))
  });

  const data = await response.json();
  if (data.success) return data.data;
  throw new Error(data.message);
};
```

### **Update User Profile**
```javascript
const updateUserProfile = async (profileData) => {
  const response = await fetch(`${API_BASE_URL}/users/profile`, {
    method: 'PUT',
    headers: authHeaders(localStorage.getItem('auth_token')),
    body: JSON.stringify(profileData)
  });

  const data = await response.json();
  if (data.success) return data.data;
  throw new Error(data.message);
};

// Usage
await updateUserProfile({
  first_name: 'John',
  last_name: 'Doe',
  contact: '+234-800-123-4567',
  address: 'Lagos, Nigeria'
});
```

### **Ban/Unban User (Admin Only)**
```javascript
const banUser = async (userId, reason, duration = null) => {
  const response = await fetch(`${API_BASE_URL}/admin/users/${userId}/ban`, {
    method: 'POST',
    headers: authHeaders(localStorage.getItem('auth_token')),
    body: JSON.stringify({ reason, duration })
  });

  const data = await response.json();
  if (data.success) return data.data;
  throw new Error(data.message);
};

const unbanUser = async (userId) => {
  const response = await fetch(`${API_BASE_URL}/admin/users/${userId}/unban`, {
    method: 'POST',
    headers: authHeaders(localStorage.getItem('auth_token'))
  });

  const data = await response.json();
  if (data.success) return data.data;
  throw new Error(data.message);
};
```

---

## 🔐 **Authentication Flow**

### **1. Register User**
```javascript
const register = async (userData) => {
  const response = await fetch(`${API_BASE_URL}/register`, {
    method: 'POST',
    headers: API_HEADERS,
    body: JSON.stringify({
      first_name: userData.firstName,
      last_name: userData.lastName,
      email: userData.email,
      password: userData.password,
      password_confirmation: userData.passwordConfirmation,
      role: 'student'
    })
  });
  
  const data = await response.json();
  if (data.success) {
    localStorage.setItem('auth_token', data.data.token);
    return data.data.user;
  }
  throw new Error(data.message);
};
```

### **2. Login User**
```javascript
const login = async (email, password) => {
  const response = await fetch(`${API_BASE_URL}/login`, {
    method: 'POST',
    headers: API_HEADERS,
    body: JSON.stringify({ email, password })
  });
  
  const data = await response.json();
  if (data.success) {
    localStorage.setItem('auth_token', data.data.token);
    return data.data.user;
  }
  throw new Error(data.message);
};
```

### **3. Get Current User**
```javascript
const getCurrentUser = async () => {
  const token = localStorage.getItem('auth_token');
  const response = await fetch(`${API_BASE_URL}/user`, {
    headers: authHeaders(token)
  });
  
  const data = await response.json();
  return data.success ? data.data : null;
};
```

### **4. Logout**
```javascript
const logout = async () => {
  const token = localStorage.getItem('auth_token');
  await fetch(`${API_BASE_URL}/logout`, {
    method: 'POST',
    headers: authHeaders(token)
  });
  localStorage.removeItem('auth_token');
};
```

---

## 📚 **Course Management**

### **1. Get All Courses**
```javascript
const getCourses = async (filters = {}) => {
  const params = new URLSearchParams(filters);
  const response = await fetch(`${API_BASE_URL}/courses?${params}`);
  const data = await response.json();
  return data.success ? data.data : [];
};

// Usage
const courses = await getCourses({
  page: 1,
  per_page: 12,
  category_id: 1,
  difficulty: 'beginner',
  search: 'mathematics'
});
```

### **2. Get Single Course**
```javascript
const getCourse = async (courseId) => {
  const response = await fetch(`${API_BASE_URL}/courses/${courseId}`);
  const data = await response.json();
  return data.success ? data.data : null;
};
```

### **3. Enroll in Course**
```javascript
const enrollInCourse = async (courseId) => {
  const token = localStorage.getItem('auth_token');
  const response = await fetch(`${API_BASE_URL}/courses/${courseId}/enroll`, {
    method: 'POST',
    headers: authHeaders(token)
  });
  
  const data = await response.json();
  return data.success ? data.data : null;
};
```

### **4. Get My Courses**
```javascript
const getMyCourses = async () => {
  const token = localStorage.getItem('auth_token');
  const response = await fetch(`${API_BASE_URL}/courses/my-courses`, {
    headers: authHeaders(token)
  });
  
  const data = await response.json();
  return data.success ? data.data : [];
};
```

---

## 📖 **Lessons & Learning**

### **1. Get Course Lessons**
```javascript
const getCourseLessons = async (courseId) => {
  const token = localStorage.getItem('auth_token');
  const response = await fetch(`${API_BASE_URL}/courses/${courseId}/lessons`, {
    headers: authHeaders(token)
  });
  
  const data = await response.json();
  return data.success ? data.data : [];
};
```

### **2. Get Single Lesson**
```javascript
const getLesson = async (lessonId) => {
  const token = localStorage.getItem('auth_token');
  const response = await fetch(`${API_BASE_URL}/lessons/${lessonId}`, {
    headers: authHeaders(token)
  });
  
  const data = await response.json();
  return data.success ? data.data : null;
};
```

### **3. Mark Lesson Complete**
```javascript
const completeLesson = async (lessonId) => {
  const token = localStorage.getItem('auth_token');
  const response = await fetch(`${API_BASE_URL}/lessons/${lessonId}/complete`, {
    method: 'POST',
    headers: authHeaders(token)
  });
  
  const data = await response.json();
  return data.success;
};
```

---

## 📝 **Quizzes**

### **1. Start Quiz**
```javascript
const startQuiz = async (quizId) => {
  const token = localStorage.getItem('auth_token');
  const response = await fetch(`${API_BASE_URL}/quizzes/${quizId}/start`, {
    method: 'POST',
    headers: authHeaders(token)
  });
  
  const data = await response.json();
  return data.success ? data.data : null;
};
```

### **2. Submit Quiz**
```javascript
const submitQuiz = async (quizId, answers) => {
  const token = localStorage.getItem('auth_token');
  const response = await fetch(`${API_BASE_URL}/quizzes/${quizId}/submit`, {
    method: 'POST',
    headers: authHeaders(token),
    body: JSON.stringify({ answers })
  });
  
  const data = await response.json();
  return data.success ? data.data : null;
};

// Usage
const result = await submitQuiz(1, [
  { question_id: 1, answer: "Option A" },
  { question_id: 2, answer: "Option C" }
]);
```

---

## 💰 **Payments**

### **1. Get Payment Gateways**
```javascript
const getPaymentGateways = async () => {
  const token = localStorage.getItem('auth_token');
  const response = await fetch(`${API_BASE_URL}/payments/gateways`, {
    headers: authHeaders(token)
  });
  
  const data = await response.json();
  return data.success ? data.data : [];
};
```

### **2. Initialize Course Payment**
```javascript
const initializeCoursePayment = async (courseId, gateway, amount) => {
  const token = localStorage.getItem('auth_token');
  const response = await fetch(`${API_BASE_URL}/payments/purchase-course`, {
    method: 'POST',
    headers: authHeaders(token),
    body: JSON.stringify({
      course_id: courseId,
      gateway: gateway,
      amount: amount,
      currency: 'NGN'
    })
  });
  
  const data = await response.json();
  return data.success ? data.data : null;
};
```

---

## 📊 **Dashboard Data**

### **1. Student Dashboard**
```javascript
const getStudentDashboard = async () => {
  const token = localStorage.getItem('auth_token');
  const response = await fetch(`${API_BASE_URL}/dashboard/student`, {
    headers: authHeaders(token)
  });
  
  const data = await response.json();
  return data.success ? data.data : null;
};
```

### **2. User Profile**
```javascript
const getUserProfile = async () => {
  const token = localStorage.getItem('auth_token');
  const response = await fetch(`${API_BASE_URL}/users/profile`, {
    headers: authHeaders(token)
  });
  
  const data = await response.json();
  return data.success ? data.data : null;
};
```

---

## 🔍 **Search**

### **1. Global Search**
```javascript
const globalSearch = async (query, type = 'courses') => {
  const token = localStorage.getItem('auth_token');
  const params = new URLSearchParams({ q: query, type });
  const response = await fetch(`${API_BASE_URL}/search/global?${params}`, {
    headers: authHeaders(token)
  });
  
  const data = await response.json();
  return data.success ? data.data : [];
};
```

---

## 🔔 **Notifications**

### **1. Get Notifications**
```javascript
const getNotifications = async (unreadOnly = false) => {
  const token = localStorage.getItem('auth_token');
  const params = unreadOnly ? '?unread_only=true' : '';
  const response = await fetch(`${API_BASE_URL}/notifications${params}`, {
    headers: authHeaders(token)
  });
  
  const data = await response.json();
  return data.success ? data.data : [];
};
```

### **2. Mark Notification as Read**
```javascript
const markNotificationRead = async (notificationId) => {
  const token = localStorage.getItem('auth_token');
  const response = await fetch(`${API_BASE_URL}/notifications/${notificationId}/read`, {
    method: 'PUT',
    headers: authHeaders(token)
  });
  
  const data = await response.json();
  return data.success;
};
```

---

## 🛡️ **Error Handling**

### **Generic API Call Handler**
```javascript
const apiCall = async (url, options = {}) => {
  try {
    const response = await fetch(url, options);
    const data = await response.json();
    
    if (!response.ok) {
      if (response.status === 401) {
        // Redirect to login
        localStorage.removeItem('auth_token');
        window.location.href = '/login';
        return;
      }
      
      if (response.status === 429) {
        // Rate limit exceeded
        throw new Error('Too many requests. Please try again later.');
      }
      
      throw new Error(data.message || 'An error occurred');
    }
    
    return data;
  } catch (error) {
    console.error('API Error:', error);
    throw error;
  }
};
```

### **Validation Error Handler**
```javascript
const handleValidationErrors = (errors) => {
  const errorMessages = {};
  
  Object.keys(errors).forEach(field => {
    errorMessages[field] = errors[field][0]; // Get first error message
  });
  
  return errorMessages;
};
```

---

## 📱 **React Hooks Examples**

### **useAuth Hook**
```javascript
import { useState, useEffect, createContext, useContext } from 'react';

const AuthContext = createContext();

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within AuthProvider');
  }
  return context;
};

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      getCurrentUser()
        .then(setUser)
        .catch(() => localStorage.removeItem('auth_token'))
        .finally(() => setLoading(false));
    } else {
      setLoading(false);
    }
  }, []);

  const login = async (email, password) => {
    const userData = await loginUser(email, password);
    setUser(userData);
    return userData;
  };

  const logout = async () => {
    await logoutUser();
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, login, logout, loading }}>
      {children}
    </AuthContext.Provider>
  );
};
```

### **useCourses Hook**
```javascript
import { useState, useEffect } from 'react';

export const useCourses = (filters = {}) => {
  const [courses, setCourses] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchCourses = async () => {
      try {
        setLoading(true);
        const data = await getCourses(filters);
        setCourses(data);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchCourses();
  }, [JSON.stringify(filters)]);

  return { courses, loading, error, refetch: () => fetchCourses() };
};
```

---

## 🎯 **Common Patterns**

### **Pagination Component**
```javascript
const Pagination = ({ currentPage, totalPages, onPageChange }) => {
  return (
    <div className="pagination">
      <button 
        disabled={currentPage === 1}
        onClick={() => onPageChange(currentPage - 1)}
      >
        Previous
      </button>
      
      <span>{currentPage} of {totalPages}</span>
      
      <button 
        disabled={currentPage === totalPages}
        onClick={() => onPageChange(currentPage + 1)}
      >
        Next
      </button>
    </div>
  );
};
```

### **Loading States**
```javascript
const CourseList = () => {
  const { courses, loading, error } = useCourses();

  if (loading) return <div>Loading courses...</div>;
  if (error) return <div>Error: {error}</div>;
  if (!courses.length) return <div>No courses found</div>;

  return (
    <div className="course-grid">
      {courses.map(course => (
        <CourseCard key={course.id} course={course} />
      ))}
    </div>
  );
};
```

---

## 📞 **Support**

- **API Documentation:** [API_DOCUMENTATION.md](./API_DOCUMENTATION.md)
- **OpenAPI Spec:** [openapi.yaml](./openapi.yaml)
- **Support:** api-support@kokokah.com
