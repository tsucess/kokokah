# Kokokah LMS - Frontend Integration Guide

**Last Updated:** October 26, 2025

---

## 📋 Table of Contents

1. [Setup](#setup)
2. [Authentication](#authentication)
3. [API Client Setup](#api-client-setup)
4. [Common Patterns](#common-patterns)
5. [Error Handling](#error-handling)
6. [Best Practices](#best-practices)

---

## 🚀 Setup

### Installation

```bash
# React with Axios
npm install axios react-router-dom

# Or with Fetch API (no installation needed)
```

### Environment Configuration

Create `.env` file:
```
REACT_APP_API_URL=http://localhost:8000/api
REACT_APP_APP_NAME=Kokokah LMS
```

---

## 🔐 Authentication

### 1. Create Auth Context

```javascript
// contexts/AuthContext.js
import React, { createContext, useState, useEffect } from 'react';

export const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(localStorage.getItem('token'));
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (token) {
      // Verify token and fetch user
      fetchCurrentUser();
    } else {
      setLoading(false);
    }
  }, [token]);

  const fetchCurrentUser = async () => {
    try {
      const response = await fetch(`${process.env.REACT_APP_API_URL}/user`, {
        headers: { 'Authorization': `Bearer ${token}` }
      });
      const data = await response.json();
      if (data.success) {
        setUser(data.data);
      } else {
        logout();
      }
    } catch (error) {
      console.error('Error fetching user:', error);
      logout();
    } finally {
      setLoading(false);
    }
  };

  const login = async (email, password) => {
    try {
      const response = await fetch(`${process.env.REACT_APP_API_URL}/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
      });
      const data = await response.json();
      if (data.success) {
        setToken(data.data.token);
        setUser(data.data.user);
        localStorage.setItem('token', data.data.token);
        return { success: true };
      }
      return { success: false, message: data.message };
    } catch (error) {
      return { success: false, message: error.message };
    }
  };

  const register = async (userData) => {
    try {
      const response = await fetch(`${process.env.REACT_APP_API_URL}/register`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(userData)
      });
      const data = await response.json();
      if (data.success) {
        setToken(data.data.token);
        setUser(data.data.user);
        localStorage.setItem('token', data.data.token);
        return { success: true };
      }
      return { success: false, message: data.message };
    } catch (error) {
      return { success: false, message: error.message };
    }
  };

  const logout = async () => {
    if (token) {
      try {
        await fetch(`${process.env.REACT_APP_API_URL}/logout`, {
          method: 'POST',
          headers: { 'Authorization': `Bearer ${token}` }
        });
      } catch (error) {
        console.error('Logout error:', error);
      }
    }
    setToken(null);
    setUser(null);
    localStorage.removeItem('token');
  };

  return (
    <AuthContext.Provider value={{ user, token, loading, login, register, logout }}>
      {children}
    </AuthContext.Provider>
  );
};
```

### 2. Use Auth Context

```javascript
// hooks/useAuth.js
import { useContext } from 'react';
import { AuthContext } from '../contexts/AuthContext';

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within AuthProvider');
  }
  return context;
};
```

---

## 🔌 API Client Setup

### 1. Axios Instance

```javascript
// services/api.js
import axios from 'axios';

const api = axios.create({
  baseURL: process.env.REACT_APP_API_URL,
  headers: {
    'Content-Type': 'application/json'
  }
});

// Add token to requests
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Handle responses
api.interceptors.response.use(
  (response) => response.data,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      window.location.href = '/login';
    }
    return Promise.reject(error.response?.data || error);
  }
);

export default api;
```

### 2. Fetch API Wrapper

```javascript
// services/fetchClient.js
class FetchClient {
  constructor(baseURL) {
    this.baseURL = baseURL;
  }

  async request(endpoint, options = {}) {
    const url = `${this.baseURL}${endpoint}`;
    const token = localStorage.getItem('token');

    const headers = {
      'Content-Type': 'application/json',
      ...options.headers
    };

    if (token) {
      headers.Authorization = `Bearer ${token}`;
    }

    try {
      const response = await fetch(url, {
        ...options,
        headers
      });

      if (response.status === 401) {
        localStorage.removeItem('token');
        window.location.href = '/login';
      }

      const data = await response.json();
      return data;
    } catch (error) {
      throw error;
    }
  }

  get(endpoint) {
    return this.request(endpoint, { method: 'GET' });
  }

  post(endpoint, body) {
    return this.request(endpoint, {
      method: 'POST',
      body: JSON.stringify(body)
    });
  }

  put(endpoint, body) {
    return this.request(endpoint, {
      method: 'PUT',
      body: JSON.stringify(body)
    });
  }

  delete(endpoint) {
    return this.request(endpoint, { method: 'DELETE' });
  }
}

export default new FetchClient(process.env.REACT_APP_API_URL);
```

---

## 🎯 Common Patterns

### 1. Custom Hooks for API Calls

```javascript
// hooks/useFetch.js
import { useState, useEffect } from 'react';
import api from '../services/api';

export const useFetch = (endpoint, options = {}) => {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const result = await api.get(endpoint);
        if (result.success) {
          setData(result.data);
        } else {
          setError(result.message);
        }
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [endpoint]);

  return { data, loading, error };
};

// Usage
function CoursesList() {
  const { data: courses, loading, error } = useFetch('/courses');

  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error}</div>;

  return (
    <div>
      {courses?.map(course => (
        <div key={course.id}>{course.title}</div>
      ))}
    </div>
  );
}
```

### 2. Mutation Hook

```javascript
// hooks/useMutation.js
import { useState } from 'react';
import api from '../services/api';

export const useMutation = (method, endpoint) => {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const mutate = async (data) => {
    setLoading(true);
    setError(null);
    try {
      const result = await api[method](endpoint, data);
      if (result.success) {
        return result;
      } else {
        setError(result.message);
        return result;
      }
    } catch (err) {
      setError(err.message);
      return { success: false };
    } finally {
      setLoading(false);
    }
  };

  return { mutate, loading, error };
};

// Usage
function CreateCourse() {
  const { mutate, loading, error } = useMutation('post', '/courses');

  const handleSubmit = async (formData) => {
    const result = await mutate(formData);
    if (result.success) {
      alert('Course created!');
    }
  };

  return (
    <form onSubmit={(e) => {
      e.preventDefault();
      handleSubmit(new FormData(e.target));
    }}>
      {/* form fields */}
      <button disabled={loading}>{loading ? 'Creating...' : 'Create'}</button>
      {error && <p className="error">{error}</p>}
    </form>
  );
}
```

### 3. Protected Route

```javascript
// components/ProtectedRoute.js
import { Navigate } from 'react-router-dom';
import { useAuth } from '../hooks/useAuth';

export const ProtectedRoute = ({ children, requiredRole }) => {
  const { user, loading } = useAuth();

  if (loading) return <div>Loading...</div>;

  if (!user) {
    return <Navigate to="/login" />;
  }

  if (requiredRole && user.role !== requiredRole) {
    return <Navigate to="/unauthorized" />;
  }

  return children;
};

// Usage
<Routes>
  <Route path="/login" element={<Login />} />
  <Route
    path="/dashboard"
    element={
      <ProtectedRoute>
        <Dashboard />
      </ProtectedRoute>
    }
  />
  <Route
    path="/admin"
    element={
      <ProtectedRoute requiredRole="admin">
        <AdminPanel />
      </ProtectedRoute>
    }
  />
</Routes>
```

---

## ❌ Error Handling

### 1. Global Error Handler

```javascript
// services/errorHandler.js
export const handleApiError = (error) => {
  if (error.response) {
    // Server responded with error status
    const { status, data } = error.response;

    switch (status) {
      case 400:
        return {
          message: data.message || 'Bad request',
          errors: data.errors
        };
      case 401:
        localStorage.removeItem('token');
        window.location.href = '/login';
        return { message: 'Unauthorized' };
      case 403:
        return { message: 'Forbidden' };
      case 404:
        return { message: 'Not found' };
      case 422:
        return {
          message: 'Validation error',
          errors: data.errors
        };
      case 429:
        return { message: 'Too many requests. Please try again later.' };
      case 500:
        return { message: 'Server error. Please try again later.' };
      default:
        return { message: data.message || 'An error occurred' };
    }
  } else if (error.request) {
    return { message: 'No response from server' };
  } else {
    return { message: error.message };
  }
};

// Usage
try {
  const result = await api.post('/courses', courseData);
} catch (error) {
  const { message, errors } = handleApiError(error);
  console.error(message);
  if (errors) {
    // Display field-specific errors
  }
}
```

### 2. Error Boundary

```javascript
// components/ErrorBoundary.js
import React from 'react';

export class ErrorBoundary extends React.Component {
  constructor(props) {
    super(props);
    this.state = { hasError: false, error: null };
  }

  static getDerivedStateFromError(error) {
    return { hasError: true, error };
  }

  componentDidCatch(error, errorInfo) {
    console.error('Error caught:', error, errorInfo);
  }

  render() {
    if (this.state.hasError) {
      return (
        <div className="error-container">
          <h1>Something went wrong</h1>
          <p>{this.state.error?.message}</p>
          <button onClick={() => window.location.reload()}>
            Reload Page
          </button>
        </div>
      );
    }

    return this.props.children;
  }
}
```

---

## ✅ Best Practices

### 1. Request Debouncing

```javascript
// hooks/useDebounce.js
import { useState, useEffect } from 'react';

export const useDebounce = (value, delay) => {
  const [debouncedValue, setDebouncedValue] = useState(value);

  useEffect(() => {
    const handler = setTimeout(() => {
      setDebouncedValue(value);
    }, delay);

    return () => clearTimeout(handler);
  }, [value, delay]);

  return debouncedValue;
};

// Usage in search
function CourseSearch() {
  const [query, setQuery] = useState('');
  const debouncedQuery = useDebounce(query, 500);

  useEffect(() => {
    if (debouncedQuery) {
      // Fetch search results
    }
  }, [debouncedQuery]);

  return (
    <input
      value={query}
      onChange={(e) => setQuery(e.target.value)}
      placeholder="Search..."
    />
  );
}
```

### 2. Request Caching

```javascript
// hooks/useCache.js
import { useState, useEffect } from 'react';

const cache = new Map();

export const useCachedFetch = (endpoint, ttl = 5 * 60 * 1000) => {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const cached = cache.get(endpoint);
    if (cached && Date.now() - cached.timestamp < ttl) {
      setData(cached.data);
      setLoading(false);
      return;
    }

    fetch(endpoint)
      .then(res => res.json())
      .then(data => {
        cache.set(endpoint, { data, timestamp: Date.now() });
        setData(data);
        setLoading(false);
      });
  }, [endpoint, ttl]);

  return { data, loading };
};
```

### 3. Pagination Helper

```javascript
// hooks/usePagination.js
import { useState, useEffect } from 'react';
import api from '../services/api';

export const usePagination = (endpoint, perPage = 10) => {
  const [data, setData] = useState([]);
  const [page, setPage] = useState(1);
  const [total, setTotal] = useState(0);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    setLoading(true);
    api.get(`${endpoint}?page=${page}&per_page=${perPage}`)
      .then(result => {
        if (result.success) {
          setData(result.data);
          setTotal(result.pagination.total);
        }
      })
      .finally(() => setLoading(false));
  }, [page, endpoint, perPage]);

  const nextPage = () => setPage(p => p + 1);
  const prevPage = () => setPage(p => Math.max(1, p - 1));

  return {
    data,
    page,
    total,
    loading,
    nextPage,
    prevPage,
    hasNextPage: page * perPage < total
  };
};
```

### 4. Form Handling

```javascript
// hooks/useForm.js
import { useState } from 'react';

export const useForm = (initialValues, onSubmit) => {
  const [values, setValues] = useState(initialValues);
  const [errors, setErrors] = useState({});
  const [touched, setTouched] = useState({});
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setValues(prev => ({ ...prev, [name]: value }));
  };

  const handleBlur = (e) => {
    const { name } = e.target;
    setTouched(prev => ({ ...prev, [name]: true }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      await onSubmit(values);
    } catch (error) {
      setErrors(error.errors || {});
    } finally {
      setLoading(false);
    }
  };

  return {
    values,
    errors,
    touched,
    loading,
    handleChange,
    handleBlur,
    handleSubmit,
    setValues,
    setErrors
  };
};

// Usage
function LoginForm() {
  const form = useForm(
    { email: '', password: '' },
    async (values) => {
      const result = await api.post('/login', values);
      if (!result.success) {
        throw result;
      }
    }
  );

  return (
    <form onSubmit={form.handleSubmit}>
      <input
        name="email"
        value={form.values.email}
        onChange={form.handleChange}
        onBlur={form.handleBlur}
      />
      {form.touched.email && form.errors.email && (
        <span className="error">{form.errors.email}</span>
      )}
      <button disabled={form.loading}>
        {form.loading ? 'Logging in...' : 'Login'}
      </button>
    </form>
  );
}
```

### 5. Notification System

```javascript
// contexts/NotificationContext.js
import React, { createContext, useState } from 'react';

export const NotificationContext = createContext();

export const NotificationProvider = ({ children }) => {
  const [notifications, setNotifications] = useState([]);

  const addNotification = (message, type = 'info', duration = 3000) => {
    const id = Date.now();
    setNotifications(prev => [...prev, { id, message, type }]);

    if (duration) {
      setTimeout(() => {
        removeNotification(id);
      }, duration);
    }

    return id;
  };

  const removeNotification = (id) => {
    setNotifications(prev => prev.filter(n => n.id !== id));
  };

  return (
    <NotificationContext.Provider value={{ addNotification, removeNotification }}>
      {children}
      <NotificationContainer notifications={notifications} />
    </NotificationContext.Provider>
  );
};

// Usage
function MyComponent() {
  const { addNotification } = useContext(NotificationContext);

  const handleSuccess = () => {
    addNotification('Operation successful!', 'success');
  };

  const handleError = () => {
    addNotification('An error occurred', 'error', 5000);
  };

  return (
    <>
      <button onClick={handleSuccess}>Success</button>
      <button onClick={handleError}>Error</button>
    </>
  );
}
```

---

## 📱 Vue.js Integration

### 1. Vue Composable

```javascript
// composables/useApi.js
import { ref } from 'vue';

export const useApi = () => {
  const data = ref(null);
  const loading = ref(false);
  const error = ref(null);

  const request = async (endpoint, options = {}) => {
    loading.value = true;
    error.value = null;

    try {
      const token = localStorage.getItem('token');
      const response = await fetch(
        `${process.env.VUE_APP_API_URL}${endpoint}`,
        {
          ...options,
          headers: {
            'Content-Type': 'application/json',
            ...(token && { 'Authorization': `Bearer ${token}` }),
            ...options.headers
          }
        }
      );

      const result = await response.json();
      if (result.success) {
        data.value = result.data;
      } else {
        error.value = result.message;
      }
      return result;
    } catch (err) {
      error.value = err.message;
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  return { data, loading, error, request };
};

// Usage in Vue component
<template>
  <div>
    <div v-if="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else>
      <div v-for="course in data" :key="course.id">
        {{ course.title }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useApi } from '@/composables/useApi';

const { data, loading, error, request } = useApi();

onMounted(() => {
  request('/courses');
});
</script>
```

---

## 🎯 Summary

- Use context for global state (auth, notifications)
- Create custom hooks for API calls
- Implement proper error handling
- Use debouncing for search
- Cache frequently accessed data
- Validate forms before submission
- Show loading states
- Handle 401 errors globally

---

*Last Updated: October 26, 2025*

