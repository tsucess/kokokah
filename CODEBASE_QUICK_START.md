# 🚀 Kokokah.com LMS - Quick Start Guide

**Last Updated:** December 9, 2025

---

## ⚡ 5-MINUTE SETUP

### Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Node.js 18+
- Composer
- Git

### Installation Steps

```bash
# 1. Clone repository
git clone <repository-url>
cd kokokah.com

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Setup environment
cp .env.example .env
php artisan key:generate

# 5. Setup database
php artisan migrate
php artisan seed

# 6. Start development server
php artisan serve
npm run dev
```

**Access:** http://localhost:8000

---

## 🎯 COMMON TASKS

### Running Tests
```bash
# All tests
php artisan test

# Specific test file
php artisan test tests/Feature/Endpoints/AuthEndpointsTest.php

# With coverage
php artisan test --coverage
```

### Database Operations
```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh database
php artisan migrate:fresh --seed

# Create migration
php artisan make:migration create_table_name
```

### Creating New Features
```bash
# Create controller
php artisan make:controller FeatureController --api

# Create model with migration
php artisan make:model Feature -m

# Create request class
php artisan make:request StoreFeatureRequest

# Create service class
php artisan make:class Services/FeatureService
```

### API Testing
```bash
# Using Postman
1. Import: postman/Kokokah_LMS_API.postman_collection.json
2. Set environment: postman/Kokokah_LMS_Environment.postman_environment.json
3. Run requests

# Using cURL
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'
```

---

## 📁 KEY FILES TO KNOW

### Configuration
- `.env` - Environment variables
- `config/kokokah.php` - LMS settings
- `config/database.php` - Database config
- `config/sanctum.php` - Auth config

### Routes
- `routes/api.php` - API endpoints (220+)
- `routes/web.php` - Web routes

### Database
- `database/migrations/` - Schema definitions
- `database/seeders/` - Sample data
- `database/factories/` - Model factories

### Application
- `app/Http/Controllers/` - Request handlers
- `app/Models/` - Data models
- `app/Services/` - Business logic
- `app/Http/Requests/` - Request validation

### Frontend
- `resources/views/` - Blade templates
- `resources/js/` - JavaScript files
- `public/js/api/` - API client files
- `resources/css/` - Stylesheets

### Tests
- `tests/Feature/` - Feature tests
- `tests/Unit/` - Unit tests
- `tests/Integration/` - Integration tests

---

## 🔐 AUTHENTICATION FLOW

### Register New User
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

### Login
```bash
POST /api/login
{
  "email": "john@example.com",
  "password": "password123"
}

# Response includes token
{
  "status": "success",
  "token": "1|QWERasdfZXCVtyui...",
  "user": { ... }
}
```

### Use Token
```bash
GET /api/user
Authorization: Bearer 1|QWERasdfZXCVtyui...
```

---

## 📊 COMMON API ENDPOINTS

### Courses
```
GET    /api/courses              # List courses
POST   /api/courses              # Create course
GET    /api/courses/{id}         # Get course
PUT    /api/courses/{id}         # Update course
DELETE /api/courses/{id}         # Delete course
POST   /api/courses/{id}/enroll  # Enroll in course
```

### Lessons
```
GET    /api/courses/{courseId}/lessons
POST   /api/courses/{courseId}/lessons
GET    /api/lessons/{id}
PUT    /api/lessons/{id}
DELETE /api/lessons/{id}
POST   /api/lessons/{id}/complete
```

### Quizzes
```
GET    /api/quizzes/{id}
POST   /api/quizzes/{id}/start
POST   /api/quizzes/{id}/submit
GET    /api/quizzes/{id}/results
```

### Wallet
```
GET    /api/wallet
POST   /api/wallet/transfer
POST   /api/wallet/purchase-course
GET    /api/wallet/transactions
```

### Dashboard
```
GET    /api/dashboard/student
GET    /api/dashboard/instructor
GET    /api/dashboard/admin
```

---

## 🐛 DEBUGGING

### Enable Debug Mode
```bash
# In .env
APP_DEBUG=true
```

### View Logs
```bash
# Real-time logs
php artisan pail

# Log file
tail -f storage/logs/laravel.log
```

### Database Debugging
```bash
# Enable query logging in tinker
php artisan tinker
>>> DB::enableQueryLog();
>>> // Run queries
>>> DB::getQueryLog();
```

### API Debugging
```bash
# Use Postman console
# Check network tab in browser DevTools
# Review response headers and body
```

---

## 📚 DOCUMENTATION REFERENCE

| Document | Purpose |
|----------|---------|
| CODEBASE_STUDY_SUMMARY.md | Complete overview |
| CODEBASE_TECHNICAL_REFERENCE.md | Technical details |
| API_DOCUMENTATION.md | API reference |
| TESTING_GUIDE.md | Testing instructions |
| DEPLOYMENT.md | Deployment guide |
| START_HERE.md | Getting started |

---

## 🆘 TROUBLESHOOTING

### Database Connection Error
```bash
# Check .env database settings
# Ensure MySQL is running
# Run: php artisan migrate
```

### Permission Denied
```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
```

### Composer Issues
```bash
# Clear cache
composer clear-cache

# Update dependencies
composer update
```

### Node Issues
```bash
# Clear npm cache
npm cache clean --force

# Reinstall
rm -rf node_modules package-lock.json
npm install
```

### Port Already in Use
```bash
# Use different port
php artisan serve --port=8001
```

---

## 🎓 LEARNING PATH

### For Backend Developers
1. Read CODEBASE_TECHNICAL_REFERENCE.md
2. Explore `app/Http/Controllers/`
3. Study `app/Models/` relationships
4. Review `routes/api.php`
5. Check `tests/Feature/` for examples

### For Frontend Developers
1. Read API_DOCUMENTATION_FRONTEND_EXAMPLES.md
2. Explore `resources/views/`
3. Study `public/js/api/` clients
4. Review Blade template syntax
5. Check Bootstrap/Tailwind usage

### For DevOps/Deployment
1. Read DEPLOYMENT.md
2. Review `config/` files
3. Check `.env.example`
4. Study database migrations
5. Review Docker setup (if available)

---

## 🚀 NEXT STEPS

1. **Setup Development Environment**
   - Follow 5-minute setup above
   - Verify all tests pass
   - Access application at localhost:8000

2. **Explore the Codebase**
   - Read CODEBASE_STUDY_SUMMARY.md
   - Review key controllers and models
   - Understand API structure

3. **Run Tests**
   - Execute `php artisan test`
   - Review test files for examples
   - Understand testing patterns

4. **Make Your First Change**
   - Create a new endpoint
   - Write tests for it
   - Deploy to development

5. **Deploy to Production**
   - Follow DEPLOYMENT.md
   - Setup environment variables
   - Run migrations
   - Configure payment gateways

---

## 📞 SUPPORT RESOURCES

- **Laravel Docs:** https://laravel.com/docs
- **Postman Docs:** https://learning.postman.com
- **MySQL Docs:** https://dev.mysql.com/doc
- **PHP Docs:** https://www.php.net/docs.php

---

## ✅ VERIFICATION CHECKLIST

- [ ] PHP 8.2+ installed
- [ ] MySQL 8.0+ running
- [ ] Node.js 18+ installed
- [ ] Composer installed
- [ ] Repository cloned
- [ ] Dependencies installed
- [ ] .env configured
- [ ] Database migrated
- [ ] Tests passing
- [ ] Server running
- [ ] API accessible

---

**You're ready to start developing! 🎉**

*For detailed information, refer to the comprehensive documentation files.*

