# 🚀 REFACTORING QUICK REFERENCE GUIDE

**For Developers Working with Refactored Endpoints**

---

## 📋 QUICK START

### 1. Import the API Client
```javascript
<script type="module">
    import CourseApiClient from '{{ asset('js/api/courseApiClient.js') }}';
    // or
    import AdminApiClient from '{{ asset('js/api/adminApiClient.js') }}';
</script>
```

### 2. Use the API Client
```javascript
// Get data
const result = await CourseApiClient.getCourses();

// Create data
const newCourse = await CourseApiClient.createCourse(data);

// Update data
const updated = await CourseApiClient.updateCourse(id, data);

// Delete data
const deleted = await CourseApiClient.deleteCourse(id);
```

### 3. Handle Response
```javascript
if (result.success) {
    console.log('Success:', result.data);
} else {
    console.error('Error:', result.message);
}
```

---

## 🎯 AVAILABLE API CLIENTS

| Client | Location | Purpose |
|--------|----------|---------|
| **BaseApiClient** | public/js/api/baseApiClient.js | Foundation class |
| **AuthApiClient** | public/js/api/authClient.js | Authentication |
| **AdminApiClient** | public/js/api/adminApiClient.js | Admin operations |
| **CourseApiClient** | public/js/api/courseApiClient.js | Course management |
| **TransactionApiClient** | public/js/api/transactionApiClient.js | Transactions |
| **WalletApiClient** | public/js/api/walletApiClient.js | Wallet operations |

---

## 📚 COMMON OPERATIONS

### Courses
```javascript
// Get all courses
const courses = await CourseApiClient.getCourses();

// Get single course
const course = await CourseApiClient.getCourse(id);

// Create course
const newCourse = await CourseApiClient.createCourse(formData);

// Update course
const updated = await CourseApiClient.updateCourse(id, formData);

// Delete course
const deleted = await CourseApiClient.deleteCourse(id);
```

### Users
```javascript
// Get all users
const users = await AdminApiClient.getUsers({ page: 1 });

// Get single user
const user = await AdminApiClient.getUser(id);

// Create user
const newUser = await AdminApiClient.createUser(formData);

// Update user
const updated = await AdminApiClient.updateUser(id, formData);

// Delete user
const deleted = await AdminApiClient.deleteUser(id);
```

### Categories
```javascript
// Get all categories
const categories = await CourseApiClient.getCategories();

// Create category
const newCat = await CourseApiClient.createCategory(data);

// Update category
const updated = await CourseApiClient.updateCategory(id, data);

// Delete category
const deleted = await CourseApiClient.deleteCategory(id);
```

---

## ⚠️ ERROR HANDLING

```javascript
try {
    const result = await ApiClient.method(params);
    
    if (result.success) {
        // Handle success
        console.log(result.data);
    } else {
        // Handle API error
        console.error(result.message);
        
        // Check for validation errors
        if (result.errors) {
            for (const [field, messages] of Object.entries(result.errors)) {
                console.error(`${field}: ${messages.join(', ')}`);
            }
        }
    }
} catch (error) {
    // Handle network/system error
    console.error('Error:', error);
}
```

---

## 🔐 AUTHENTICATION

Token is automatically managed:
- Stored in localStorage as 'auth_token'
- Automatically added to all requests
- Automatically redirects to login on 401

```javascript
// Get current token
const token = BaseApiClient.getToken();

// Get current user
const user = BaseApiClient.getUser();

// Set token (usually done by login)
BaseApiClient.setToken(token);

// Set user
BaseApiClient.setUser(userData);

// Logout
BaseApiClient.logout();
```

---

## 📤 FILE UPLOADS

Use FormData for file uploads:

```javascript
const formData = new FormData();
formData.append('title', 'Course Title');
formData.append('thumbnail', fileInput.files[0]);
formData.append('category_id', 1);

const result = await CourseApiClient.createCourse(formData);
```

---

## 🔍 RESPONSE FORMAT

All responses follow this format:

```javascript
{
    success: true/false,
    data: { /* response data */ },
    message: "Success or error message",
    status: 200/400/401/500,
    errors: { /* validation errors if any */ }
}
```

---

## 🛠️ DEBUGGING

Enable console logging:

```javascript
// All API calls log to console
// Check browser console for request/response details

// Example log output:
// GET /api/courses
// Response: { success: true, data: [...] }
```

---

## 📝 REFACTORED TEMPLATES

All these templates use the new API clients:

✅ admin/dashboard.blade.php  
✅ admin/users.blade.php  
✅ admin/transactions.blade.php  
✅ admin/categories.blade.php  
✅ admin/levels.blade.php  
✅ admin/terms.blade.php  
✅ admin/curriculum-categories.blade.php  
✅ admin/createsubject.blade.php  
✅ admin/edituser.blade.php  
✅ admin/createuser.blade.php  

---

## 🚀 BEST PRACTICES

1. **Always check result.success** before using data
2. **Use try-catch** for error handling
3. **Show user feedback** on success/error
4. **Validate input** before sending
5. **Use FormData** for file uploads
6. **Handle 401 errors** for re-authentication
7. **Log errors** for debugging
8. **Test thoroughly** before deployment

---

## 📞 NEED HELP?

1. Check the API client source code
2. Review template examples
3. Check browser console for errors
4. Refer to the full refactoring report
5. Contact the development team

---

**Last Updated:** December 5, 2025  
**Version:** 1.0  
**Status:** Production Ready

