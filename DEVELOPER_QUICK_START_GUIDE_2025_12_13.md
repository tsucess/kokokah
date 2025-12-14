# 🚀 KOKOKAH.COM - DEVELOPER QUICK START GUIDE
**Date:** December 13, 2025  
**For:** Backend & Frontend Developers

---

## ⚡ 5-MINUTE SETUP

### 1. Clone & Install
```bash
git clone <repository-url>
cd kokokah.com
composer install
npm install
```

### 2. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### 4. Start Development
```bash
php artisan serve
npm run dev
```

**Access:** http://localhost:8000

---

## 📁 KEY DIRECTORIES

| Directory | Purpose |
|-----------|---------|
| `app/Http/Controllers/` | API endpoints (40+ controllers) |
| `app/Models/` | Database models (50+ models) |
| `routes/api.php` | API routes (220+ endpoints) |
| `resources/views/` | Blade templates |
| `public/js/api/` | JavaScript API clients |
| `database/migrations/` | Database schema |
| `tests/` | Test suite (263+ tests) |

---

## 🔑 ESSENTIAL FILES

### Backend
- **routes/api.php** - All API endpoints
- **app/Http/Controllers/AuthController.php** - Authentication
- **app/Http/Controllers/CourseController.php** - Course management
- **app/Models/User.php** - User model
- **app/Models/Course.php** - Course model
- **config/kokokah.php** - LMS configuration

### Frontend
- **resources/views/layouts/dashboardtemp.blade.php** - Admin layout
- **resources/views/layouts/usertemplate.blade.php** - Student layout
- **public/js/api/baseApiClient.js** - Base API client
- **public/js/api/authClient.js** - Auth API client
- **public/js/utils/toastNotification.js** - Toast notifications

---

## 🔐 AUTHENTICATION FLOW

### 1. Register User
```bash
POST /api/register
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "student"
}
```

### 2. Login
```bash
POST /api/login
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "success": true,
  "user": { /* user data */ },
  "token": "api_token_here"
}
```

### 3. Use Token
```bash
GET /api/user
Authorization: Bearer api_token_here
```

---

## 📚 COMMON API ENDPOINTS

### Courses
```bash
GET /api/courses                    # List courses
POST /api/courses                   # Create course
GET /api/courses/{id}               # Get course
PUT /api/courses/{id}               # Update course
DELETE /api/courses/{id}            # Delete course
POST /api/courses/{id}/enroll       # Enroll in course
```

### Lessons
```bash
GET /api/lessons/{id}               # Get lesson
PUT /api/lessons/{id}               # Update lesson
POST /api/lessons/{id}/complete     # Mark complete
GET /api/lessons/{id}/progress      # Get progress
```

### Quizzes
```bash
GET /api/quizzes                    # List quizzes
POST /api/quizzes                   # Create quiz
GET /api/quizzes/{id}               # Get quiz
POST /api/quizzes/{id}/attempt      # Start attempt
POST /api/quizzes/{id}/submit       # Submit quiz
```

### User Profile
```bash
GET /api/users/profile              # Get profile
PUT /api/users/profile              # Update profile
POST /api/users/change-password     # Change password
```

### Payments
```bash
POST /api/payments/deposit          # Deposit funds
POST /api/payments/purchase-course  # Purchase course
GET /api/payments/history           # Payment history
GET /api/wallet/balance             # Wallet balance
```

---

## 🛠️ DEVELOPMENT WORKFLOW

### 1. Create New Feature
```bash
# Create controller
php artisan make:controller FeatureController

# Create model
php artisan make:model Feature -m

# Create migration
php artisan make:migration create_features_table
```

### 2. Add Routes
Edit `routes/api.php`:
```php
Route::apiResource('features', FeatureController::class);
```

### 3. Implement Controller
```php
class FeatureController extends Controller {
    public function index() { /* ... */ }
    public function store(Request $request) { /* ... */ }
    public function show($id) { /* ... */ }
    public function update(Request $request, $id) { /* ... */ }
    public function destroy($id) { /* ... */ }
}
```

### 4. Write Tests
```bash
php artisan make:test FeatureTest
```

### 5. Run Tests
```bash
php artisan test
```

---

## 🎨 FRONTEND INTEGRATION

### Using API Clients
```javascript
// Import client
import CourseApiClient from '{{ asset("js/api/courseApiClient.js") }}';

// Call static method
const courses = await CourseApiClient.getCourses();

// Handle response
if (courses.success) {
    console.log(courses.data);
} else {
    console.error(courses.message);
}
```

### Show Toast Notification
```javascript
import ToastNotification from '{{ asset("js/utils/toastNotification.js") }}';

// Success
ToastNotification.success('Title', 'Message');

// Error
ToastNotification.error('Title', 'Message');

// Info
ToastNotification.info('Title', 'Message');
```

---

## 🧪 TESTING

### Run All Tests
```bash
php artisan test
```

### Run Specific Test
```bash
php artisan test tests/Feature/CourseTest.php
```

### Run with Coverage
```bash
php artisan test --coverage
```

### Test Structure
```
tests/
├── Feature/          # Feature tests
├── Unit/             # Unit tests
├── Integration/      # Integration tests
└── TestCase.php      # Base test class
```

---

## 📊 DATABASE OPERATIONS

### Create Migration
```bash
php artisan make:migration create_table_name
```

### Run Migrations
```bash
php artisan migrate
```

### Rollback Migrations
```bash
php artisan migrate:rollback
```

### Seed Database
```bash
php artisan db:seed
```

### Create Seeder
```bash
php artisan make:seeder TableNameSeeder
```

---

## 🔍 DEBUGGING

### Enable Debug Mode
Edit `.env`:
```
APP_DEBUG=true
```

### View Logs
```bash
tail -f storage/logs/laravel.log
```

### Database Queries
```php
DB::enableQueryLog();
// ... your code ...
dd(DB::getQueryLog());
```

### Dump Variables
```php
dd($variable);  // Dump and die
dump($variable); // Dump only
```

---

## 📦 COMMON COMMANDS

```bash
# Clear cache
php artisan cache:clear

# Clear config
php artisan config:clear

# Clear routes
php artisan route:clear

# Optimize
php artisan optimize

# Tinker (REPL)
php artisan tinker

# Generate API docs
php artisan scribe:generate

# Create backup
php artisan backup:run
```

---

## 🚀 DEPLOYMENT

### Production Build
```bash
npm run build
php artisan optimize
php artisan config:cache
php artisan route:cache
```

### Environment Setup
```bash
cp .env.production .env
php artisan migrate --force
php artisan db:seed --force
```

### Health Check
```bash
curl http://your-domain.com/api/health
```

---

## 📚 DOCUMENTATION

- **CODEBASE_COMPLETE_STUDY_2025_12_13.md** - Full codebase overview
- **API_DOCUMENTATION_FRONTEND_EXAMPLES.md** - API examples
- **CODEBASE_QUICK_START.md** - Setup guide
- **PROJECT_COMPREHENSIVE_REVIEW.md** - Project review

---

## 🆘 TROUBLESHOOTING

### 404 Errors
- Check route in `routes/api.php`
- Verify controller exists
- Check method name matches

### 401 Unauthorized
- Verify token in Authorization header
- Check token hasn't expired
- Verify user is authenticated

### 422 Validation Error
- Check request validation rules
- Verify all required fields present
- Check field data types

### 500 Server Error
- Check `storage/logs/laravel.log`
- Verify database connection
- Check file permissions

---

## 💡 BEST PRACTICES

1. **Always validate input** - Use Request validation
2. **Use transactions** - For critical operations
3. **Write tests** - For all features
4. **Document code** - Use PHPDoc comments
5. **Follow conventions** - PSR-12 coding standard
6. **Use migrations** - For schema changes
7. **Cache queries** - For performance
8. **Log errors** - For debugging
9. **Use policies** - For authorization
10. **Handle exceptions** - Gracefully

---

## 🎯 NEXT STEPS

1. Read **CODEBASE_COMPLETE_STUDY_2025_12_13.md**
2. Review **routes/api.php**
3. Study key controllers
4. Examine model relationships
5. Run tests
6. Start developing!

---

**Happy Coding! 🎉**


