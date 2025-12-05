# 🔄 FRONTEND ENDPOINT REFACTORING GUIDE

**Date:** December 5, 2025  
**Status:** In Progress  
**Objective:** Refactor all 41+ integrated endpoints to use centralized API clients

---

## 📋 REFACTORING OVERVIEW

### What We're Doing
Converting from scattered inline `fetch()` calls to a centralized, well-organized API client library.

### Why
- **Consistency:** All API calls follow the same pattern
- **Maintainability:** Changes to API logic in one place
- **Error Handling:** Unified error handling across all endpoints
- **Authentication:** Automatic token management
- **Type Safety:** Better IDE support and documentation
- **Testing:** Easier to mock and test

---

## 🏗️ NEW API CLIENT ARCHITECTURE

### Base Layer
**File:** `public/js/api/baseApiClient.js`
- Common functionality for all clients
- Token management
- Error handling
- HTTP methods (GET, POST, PUT, DELETE)
- Authentication headers

### Client Layers
1. **AuthApiClient** - Authentication endpoints
2. **AdminApiClient** - Admin operations
3. **CourseApiClient** - Course management
4. **TransactionApiClient** - Transaction handling
5. **WalletApiClient** - Wallet operations
6. **LessonApiClient** - Lesson management (NEW)
7. **QuizApiClient** - Quiz operations (NEW)
8. **AssignmentApiClient** - Assignment handling (NEW)
9. **ProgressApiClient** - Progress tracking (NEW)
10. **CertificateApiClient** - Certificate operations (NEW)

---

## ✅ COMPLETED REFACTORING

### 1. BaseApiClient ✅
**File:** `public/js/api/baseApiClient.js`
**Status:** Complete
**Methods:**
- `get()`, `post()`, `put()`, `delete()`
- `getToken()`, `setToken()`, `clearToken()`
- `getUser()`, `setUser()`, `clearUser()`
- `isAuthenticated()`
- `getAuthHeaders()`
- `handleSuccess()`, `handleError()`

### 2. AuthApiClient ✅
**File:** `public/js/api/authClient.js`
**Status:** Refactored
**Methods:**
- `register()`, `login()`, `logout()`
- `sendVerificationCode()`, `verifyEmailWithCode()`
- `sendPasswordResetLink()`, `resetPassword()`
- `getCurrentUser()`, `updateProfile()`
- `changePassword()`, `verifyPassword()`

### 3. AdminApiClient ✅
**File:** `public/js/api/adminApiClient.js`
**Status:** Complete
**Methods:**
- `getDashboardStats()`, `getRecentUsers()`
- `getUsers()`, `getUser()`, `createUser()`, `updateUser()`, `deleteUser()`
- `getTransactions()`, `getTransaction()`
- `getUserActivity()`, `getSystemStats()`
- `exportUsers()`, `exportTransactions()`
- `getLogs()`

### 4. CourseApiClient ✅
**File:** `public/js/api/courseApiClient.js`
**Status:** Complete
**Methods:**
- `getCourses()`, `getCourse()`, `createCourse()`, `updateCourse()`, `deleteCourse()`
- `publishCourse()`, `unpublishCourse()`
- `getCategories()`, `getCategory()`, `createCategory()`, `updateCategory()`, `deleteCategory()`
- `getLevels()`, `getLevel()`, `createLevel()`, `updateLevel()`, `deleteLevel()`
- `getTerms()`, `getTerm()`, `createTerm()`, `updateTerm()`, `deleteTerm()`
- `getCurriculumCategories()`, `createCurriculumCategory()`, `updateCurriculumCategory()`, `deleteCurriculumCategory()`

### 5. TransactionApiClient ✅
**File:** `public/js/api/transactionApiClient.js`
**Status:** Complete
**Methods:**
- `getTransactions()`, `getTransaction()`, `createTransaction()`, `updateTransaction()`, `deleteTransaction()`
- `getUserTransactions()`, `getStatistics()`, `getSummary()`
- `exportTransactions()`, `verifyTransaction()`, `refundTransaction()`
- `getReceipt()`, `sendReceipt()`
- `getPaymentMethods()`, `getByReference()`

### 6. WalletApiClient ✅
**File:** `public/js/api/walletApiClient.js`
**Status:** Complete
**Methods:**
- `getBalance()`, `getWallet()`, `getTransactions()`
- `addFunds()`, `withdrawFunds()`, `transferFunds()`
- `getTransaction()`, `getStatistics()`, `getHistory()`
- `exportTransactions()`, `getPaymentMethods()`
- `addPaymentMethod()`, `deletePaymentMethod()`, `setDefaultPaymentMethod()`
- `getLimits()`, `requestVerification()`, `verifyWithCode()`

---

## 🔄 REFACTORING TEMPLATES

### Before (Old Pattern)
```javascript
const response = await fetch('/api/admin/dashboard', {
    method: 'GET',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
    }
});
const data = await response.json();
if (data.success) {
    // Handle success
}
```

### After (New Pattern)
```javascript
import AdminApiClient from '{{ asset('js/api/adminApiClient.js') }}';

const result = await AdminApiClient.getDashboardStats();
if (result.success) {
    // Handle success
}
```

---

## 📝 TEMPLATE USAGE EXAMPLES

### Authentication
```javascript
import AuthApiClient from '{{ asset('js/api/authClient.js') }}';

// Login
const result = await AuthApiClient.login(email, password);

// Get current user
const user = await AuthApiClient.getCurrentUser();

// Logout
await AuthApiClient.logout();
```

### Admin Operations
```javascript
import AdminApiClient from '{{ asset('js/api/adminApiClient.js') }}';

// Get dashboard stats
const stats = await AdminApiClient.getDashboardStats();

// Get users with filters
const users = await AdminApiClient.getUsers({
    page: 1,
    per_page: 20,
    role: 'student'
});

// Create user
const newUser = await AdminApiClient.createUser({
    first_name: 'John',
    last_name: 'Doe',
    email: 'john@example.com',
    role: 'student'
});
```

### Course Management
```javascript
import CourseApiClient from '{{ asset('js/api/courseApiClient.js') }}';

// Get all courses
const courses = await CourseApiClient.getCourses();

// Get categories
const categories = await CourseApiClient.getCategories();

// Create course
const course = await CourseApiClient.createCourse({
    title: 'Math 101',
    description: 'Basic Mathematics',
    category_id: 1
});
```

---

## 📊 REFACTORING PROGRESS

| Client | Status | Pages | Endpoints |
|--------|--------|-------|-----------|
| BaseApiClient | ✅ | - | - |
| AuthApiClient | ✅ | 6 | 8 |
| AdminApiClient | ✅ | 14 | 15+ |
| CourseApiClient | ✅ | 2 | 20+ |
| TransactionApiClient | ✅ | 1 | 8 |
| WalletApiClient | ✅ | 1 | 15+ |
| **Total** | **✅** | **24** | **66+** |

---

## 🎯 NEXT STEPS

### Phase 1: Update Blade Templates (In Progress)
1. Admin dashboard - Replace fetch with AdminApiClient
2. Admin users - Replace fetch with AdminApiClient
3. Admin courses - Replace fetch with CourseApiClient
4. Admin categories - Replace fetch with CourseApiClient
5. Admin levels - Replace fetch with CourseApiClient
6. Admin terms - Replace fetch with CourseApiClient
7. Admin transactions - Replace fetch with TransactionApiClient
8. User wallet - Replace fetch with WalletApiClient
9. User dashboard - Replace fetch with ProgressApiClient (NEW)
10. User classes - Replace fetch with CourseApiClient

### Phase 2: Create Missing API Clients
1. LessonApiClient
2. QuizApiClient
3. AssignmentApiClient
4. ProgressApiClient
5. CertificateApiClient
6. ForumApiClient
7. ChatApiClient
8. PaymentApiClient

### Phase 3: Testing & Validation
1. Test all refactored endpoints
2. Verify error handling
3. Check authentication flow
4. Validate response handling

---

## 💡 BEST PRACTICES

1. **Always import at top of script**
   ```javascript
   import AdminApiClient from '{{ asset('js/api/adminApiClient.js') }}';
   ```

2. **Check response.success**
   ```javascript
   const result = await AdminApiClient.getUsers();
   if (result.success) {
       // Use result.data
   } else {
       // Handle error with result.message
   }
   ```

3. **Use filters for pagination**
   ```javascript
   const result = await AdminApiClient.getUsers({
       page: 1,
       per_page: 20
   });
   ```

4. **Handle errors gracefully**
   ```javascript
   try {
       const result = await AdminApiClient.getUsers();
       if (!result.success) {
           UIHelpers.showError(result.message);
       }
   } catch (error) {
       UIHelpers.showError('An error occurred');
   }
   ```

---

## 📚 FILES CREATED

1. `public/js/api/baseApiClient.js` - Base class
2. `public/js/api/authClient.js` - Refactored
3. `public/js/api/adminApiClient.js` - New
4. `public/js/api/courseApiClient.js` - New
5. `public/js/api/transactionApiClient.js` - New
6. `public/js/api/walletApiClient.js` - New

---

## ✨ BENEFITS

✅ **Consistency** - All API calls follow same pattern  
✅ **Maintainability** - Easy to update API logic  
✅ **Error Handling** - Unified error handling  
✅ **Authentication** - Automatic token management  
✅ **Documentation** - Clear method signatures  
✅ **Testing** - Easier to mock and test  
✅ **Scalability** - Easy to add new endpoints  

---

**Status:** 66+ endpoints refactored into 6 API clients  
**Next:** Update Blade templates to use new clients

