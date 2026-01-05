# Review & Rating System - Testing Guide

## Prerequisites
- Ensure database migrations have been run
- Have test users with different roles (student, instructor, admin)
- Have at least one course with enrollments

## Test Cases

### 1. User Review Submission
**Endpoint:** `POST /api/courses/{courseId}/reviews`

**Test Data:**
```json
{
  "rating": 5,
  "title": "Excellent Course!",
  "comment": "This course was very informative and well-structured.",
  "pros": ["Great instructor", "Clear explanations"],
  "cons": ["Could use more examples"]
}
```

**Expected Results:**
- ✅ Review created with status "pending"
- ✅ User can only review once per course
- ✅ Only enrolled users can review
- ✅ All fields validated

### 2. View Course Reviews
**Endpoint:** `GET /api/courses/{courseId}/reviews`

**Expected Results:**
- ✅ Returns paginated list of approved reviews
- ✅ Includes review statistics (average rating, distribution)
- ✅ Shows helpful count for each review
- ✅ Filters by status parameter

### 3. Mark Review as Helpful
**Endpoint:** `POST /api/reviews/{reviewId}/helpful`

**Expected Results:**
- ✅ Increments helpful_count
- ✅ Creates ReviewHelpful record
- ✅ Toggle: second click removes helpful mark
- ✅ Only approved reviews can be marked helpful

### 4. Approve Review (Instructor/Admin)
**Endpoint:** `POST /api/reviews/{reviewId}/approve`

**Expected Results:**
- ✅ Changes status to "approved"
- ✅ Sets moderated_by and moderated_at
- ✅ Only instructor/admin can approve
- ✅ Review becomes visible to public

### 5. Reject Review (Instructor/Admin)
**Endpoint:** `POST /api/reviews/{reviewId}/reject`

**Expected Results:**
- ✅ Changes status to "rejected"
- ✅ Stores rejection_reason
- ✅ Review hidden from public
- ✅ User notified of rejection

### 6. Admin Rating Overview
**Route:** `GET /rating`

**Expected Results:**
- ✅ Shows all courses with ratings
- ✅ Displays average rating and review count
- ✅ Shows rating distribution chart
- ✅ Links to detailed course ratings

### 7. Course Rating Details
**Route:** `GET /rating/{courseId}`

**Expected Results:**
- ✅ Shows course statistics
- ✅ Lists all reviews (with filter)
- ✅ Pagination works correctly
- ✅ Filter by status (approved/pending/rejected)

### 8. Review Moderation Queue
**Endpoint:** `GET /api/reviews/moderate`

**Expected Results:**
- ✅ Shows pending reviews only
- ✅ Includes reviewer info
- ✅ Allows bulk approval/rejection
- ✅ Sorted by newest first

### 9. User's Reviews
**Endpoint:** `GET /api/reviews/my-reviews`

**Expected Results:**
- ✅ Shows only current user's reviews
- ✅ Includes all statuses
- ✅ Allows editing pending reviews
- ✅ Shows moderation feedback

### 10. Review Analytics
**Endpoint:** `GET /api/courses/{courseId}/reviews/analytics`

**Expected Results:**
- ✅ Returns rating distribution
- ✅ Shows trend data
- ✅ Calculates average rating
- ✅ Shows helpful marks trend

## Authorization Tests

### Student User
- ✅ Can create review (if enrolled)
- ✅ Can view approved reviews
- ✅ Can mark reviews helpful
- ✅ Cannot approve/reject reviews
- ✅ Cannot see pending reviews

### Instructor User
- ✅ Can view their course reviews
- ✅ Can approve/reject reviews
- ✅ Cannot see other instructors' reviews
- ✅ Can see moderation queue

### Admin User
- ✅ Can view all reviews
- ✅ Can approve/reject any review
- ✅ Can access moderation queue
- ✅ Can view analytics

## Edge Cases

- [ ] User tries to review twice
- [ ] Unenrolled user tries to review
- [ ] Mark helpful on unapproved review
- [ ] Approve already approved review
- [ ] Delete review with helpful marks
- [ ] Update review after approval
- [ ] Pagination with no reviews
- [ ] Filter with invalid status

## Performance Tests

- [ ] Load 1000 reviews for a course
- [ ] Calculate statistics for 100 courses
- [ ] Pagination with large datasets
- [ ] Helpful marks query performance

## UI/UX Tests

- [ ] Review form validation
- [ ] Star rating interaction
- [ ] Pros/cons field addition
- [ ] Character count display
- [ ] Success/error messages
- [ ] Loading states

