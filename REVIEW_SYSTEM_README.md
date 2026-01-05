# 🌟 Review & Rating System

A comprehensive, production-ready review and rating system for the Kokokah.com learning platform.

## ✨ Features

- ⭐ **5-Star Rating System** - Users can rate courses from 1 to 5 stars
- 💬 **Detailed Reviews** - Title, comment, pros, and cons
- ✅ **Moderation Workflow** - Reviews require approval before display
- 👍 **Helpful Marks** - Users can mark reviews as helpful
- 📊 **Analytics** - Comprehensive statistics and trends
- 🔐 **Role-Based Access** - Student, Instructor, and Admin roles
- 📱 **Responsive Design** - Works on all devices
- ⚡ **Performance Optimized** - Efficient queries with pagination

## 🚀 Quick Start

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Add Review Form to Course Page
```blade
@include('components.review-form')
```

### 3. Access Admin Dashboard
```
/rating - View all course ratings
/rating/{courseId} - View course details and moderate reviews
```

## 📚 Documentation

| Document | Purpose |
|----------|---------|
| [REVIEW_SYSTEM_QUICK_START.md](REVIEW_SYSTEM_QUICK_START.md) | Quick reference guide |
| [INTEGRATION_GUIDE.md](INTEGRATION_GUIDE.md) | How to integrate into your app |
| [REVIEW_SYSTEM_TESTING_GUIDE.md](REVIEW_SYSTEM_TESTING_GUIDE.md) | Test cases and scenarios |
| [REVIEW_SYSTEM_FINAL_SUMMARY.md](REVIEW_SYSTEM_FINAL_SUMMARY.md) | Complete technical overview |
| [PROJECT_COMPLETION_SUMMARY.md](PROJECT_COMPLETION_SUMMARY.md) | Project deliverables |

## 🔌 API Endpoints

### Public Endpoints (Authenticated)
```
GET    /api/courses/{courseId}/reviews              - List reviews
POST   /api/courses/{courseId}/reviews              - Create review
GET    /api/courses/{courseId}/reviews/analytics    - Get analytics
GET    /api/reviews/{id}                            - View review
PUT    /api/reviews/{id}                            - Update review
DELETE /api/reviews/{id}                            - Delete review
POST   /api/reviews/{id}/helpful                    - Mark helpful
GET    /api/reviews/my-reviews                      - Get my reviews
```

### Admin/Instructor Endpoints
```
GET    /api/reviews/moderate                        - Moderation queue
POST   /api/reviews/{id}/approve                    - Approve review
POST   /api/reviews/{id}/reject                     - Reject review
```

## 🎯 User Roles

### Student
- ✅ Create reviews (if enrolled)
- ✅ View approved reviews
- ✅ Mark reviews as helpful
- ✅ Edit own pending reviews
- ❌ Cannot moderate reviews

### Instructor
- ✅ All student permissions
- ✅ View reviews for own courses
- ✅ Approve/reject reviews
- ✅ See moderation queue
- ❌ Cannot moderate other instructors' courses

### Admin
- ✅ All permissions
- ✅ View all reviews
- ✅ Moderate any review
- ✅ Access full analytics

## 📊 Database Schema

### course_reviews table
```sql
- id (PK)
- course_id (FK)
- user_id (FK)
- rating (1-5)
- title (string)
- comment (text)
- pros (JSON)
- cons (JSON)
- status (pending/approved/rejected)
- helpful_count (integer)
- moderated_by (FK)
- moderated_at (timestamp)
- rejection_reason (text)
- timestamps
```

### review_helpful table
```sql
- id (PK)
- review_id (FK)
- user_id (FK)
- timestamps
- UNIQUE(review_id, user_id)
```

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

See [REVIEW_SYSTEM_TESTING_GUIDE.md](REVIEW_SYSTEM_TESTING_GUIDE.md) for detailed test cases.

## 🔐 Security

- ✅ Role-based authorization
- ✅ Enrollment verification
- ✅ CSRF protection
- ✅ Input validation
- ✅ SQL injection prevention
- ✅ XSS protection

## 📁 File Structure

```
app/
├── Http/Controllers/
│   ├── ReviewController.php (615 lines)
│   └── RatingController.php (131 lines)
└── Models/
    ├── CourseReview.php
    └── ReviewHelpful.php

resources/views/
├── admin/
│   ├── rating_dynamic.blade.php
│   └── ratingdetails_dynamic.blade.php
└── components/
    └── review-form.blade.php

routes/
├── api.php (11 endpoints)
└── web.php (2 routes)
```

## 🚀 Deployment

1. Run migrations: `php artisan migrate`
2. Clear cache: `php artisan cache:clear`
3. Test endpoints
4. Deploy to production

## 📞 Support

For issues or questions:
1. Check the documentation files
2. Review the testing guide
3. Check authorization and roles
4. Verify database migrations

## 📝 License

This review system is part of the Kokokah.com platform.

## ✅ Status

**COMPLETE AND PRODUCTION READY**

- All endpoints implemented ✅
- All views created ✅
- All tests documented ✅
- Full documentation provided ✅
- Security implemented ✅

---

**Last Updated:** January 5, 2026
**Version:** 1.0.0
**Status:** Production Ready

