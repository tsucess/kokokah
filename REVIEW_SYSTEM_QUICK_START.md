# Review & Rating System - Quick Start Guide

## 🚀 Getting Started

### 1. Database Setup
```bash
# Run migrations (if not already done)
php artisan migrate
```

### 2. Access Points

**Admin Dashboard:**
- URL: `/rating`
- Shows all courses with ratings
- Click "View Review" to see details

**Course Rating Details:**
- URL: `/rating/{courseId}`
- Shows reviews, statistics, and moderation options
- Filter by status (approved/pending/rejected)

### 3. User Review Submission

**Include in Course Details Page:**
```blade
@include('components.review-form')
```

**Or use API directly:**
```javascript
const reviewData = {
  rating: 5,
  title: "Great Course!",
  comment: "Very informative...",
  pros: ["Good instructor"],
  cons: ["Could use more examples"]
};

fetch(`/api/courses/${courseId}/reviews`, {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': csrfToken
  },
  body: JSON.stringify(reviewData)
})
```

## 📊 Key Endpoints

### For Users
```
POST   /api/courses/{courseId}/reviews      - Submit review
GET    /api/courses/{courseId}/reviews      - View reviews
POST   /api/reviews/{id}/helpful            - Mark helpful
GET    /api/reviews/my-reviews              - My reviews
```

### For Instructors/Admins
```
GET    /api/reviews/moderate                - Pending reviews
POST   /api/reviews/{id}/approve            - Approve review
POST   /api/reviews/{id}/reject             - Reject review
GET    /api/courses/{courseId}/reviews/analytics - Analytics
```

## 🔍 Common Tasks

### View All Reviews for a Course
```
GET /api/courses/{courseId}/reviews?per_page=10&sort_by=created_at
```

### Filter by Rating
```
GET /api/courses/{courseId}/reviews?rating=5
```

### Sort by Helpful
```
GET /api/courses/{courseId}/reviews?sort_by=helpful&sort_order=desc
```

### Get Moderation Queue
```
GET /api/reviews/moderate
```

### Approve a Review
```
POST /api/reviews/{id}/approve
```

### Reject a Review
```
POST /api/reviews/{id}/reject
Body: { "reason": "Inappropriate content" }
```

### Mark as Helpful
```
POST /api/reviews/{id}/helpful
```

## 🔐 Permissions

| Action | Student | Instructor | Admin |
|--------|---------|-----------|-------|
| Create Review | ✅ (if enrolled) | ✅ | ✅ |
| View Approved | ✅ | ✅ | ✅ |
| Mark Helpful | ✅ | ✅ | ✅ |
| Approve Review | ❌ | ✅ (own courses) | ✅ |
| Reject Review | ❌ | ✅ (own courses) | ✅ |
| View Pending | ❌ | ✅ (own courses) | ✅ |
| View All Reviews | ❌ | ❌ | ✅ |

## 📱 Response Format

### Success Response
```json
{
  "success": true,
  "message": "Review created successfully",
  "data": {
    "id": 1,
    "course_id": 5,
    "user_id": 10,
    "rating": 5,
    "title": "Great Course!",
    "comment": "Very informative...",
    "status": "pending",
    "created_at": "2024-01-05T10:30:00Z"
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "You must be enrolled in this course to leave a review",
  "errors": {}
}
```

## 🧪 Quick Test

1. **Create Review:**
   ```bash
   curl -X POST http://localhost/api/courses/1/reviews \
     -H "Content-Type: application/json" \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -d '{
       "rating": 5,
       "title": "Test Review",
       "comment": "This is a test review"
     }'
   ```

2. **View Reviews:**
   ```bash
   curl http://localhost/api/courses/1/reviews \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```

3. **Mark Helpful:**
   ```bash
   curl -X POST http://localhost/api/reviews/1/helpful \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```

## 📚 Documentation Files

- `REVIEW_SYSTEM_FINAL_SUMMARY.md` - Complete overview
- `REVIEW_SYSTEM_TESTING_GUIDE.md` - Detailed test cases
- `REVIEW_SYSTEM_IMPLEMENTATION_COMPLETE.md` - Technical details

## ⚠️ Important Notes

1. Reviews start in "pending" status and need approval
2. Users can only review once per course
3. Only enrolled users can review
4. Helpful marks can be toggled (click again to remove)
5. Instructors can only moderate their own courses
6. Admins can moderate all reviews

## 🆘 Troubleshooting

**"You must be enrolled in this course"**
- User is not enrolled in the course
- Check enrollments table

**"You have already reviewed this course"**
- User already has a review (pending or approved)
- User must delete existing review first

**"Unauthorized to approve this review"**
- User is not the course instructor or admin
- Check user roles

**Reviews not showing**
- Check review status (must be "approved")
- Check pagination parameters
- Verify course_id is correct

---

**Ready to use!** Start integrating the review system into your course pages.

