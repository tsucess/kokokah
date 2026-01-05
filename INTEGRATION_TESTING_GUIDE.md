# Integration Testing Guide - Review & Rating System

## ✅ Pre-Testing Checklist

- [x] Database migrations completed
- [ ] Application running (`php artisan serve`)
- [ ] User logged in
- [ ] Course enrolled

## 🧪 Test 1: Review Form Display

**Location:** Course Details Page (`/users/subjectdetails`)

**Steps:**
1. Navigate to a course you're enrolled in
2. Scroll to the "Course Reviews" section
3. Verify the review form is displayed
4. Verify rating stars are visible
5. Verify all form fields are present

**Expected Result:** ✅ Review form displays correctly with all fields

---

## 🧪 Test 2: Submit a Review

**Steps:**
1. Fill in the review form:
   - Rating: 5 stars
   - Title: "Excellent Course!"
   - Comment: "Very informative and well-structured."
   - Pros: "Great instructor", "Clear explanations"
   - Cons: "Could use more examples"
2. Click "Submit Review"
3. Verify success message appears

**Expected Result:** ✅ Review submitted successfully

**API Call:** `POST /api/courses/{courseId}/reviews`

---

## 🧪 Test 3: View Reviews

**Steps:**
1. After submitting, scroll to reviews section
2. Verify review appears in the list
3. Verify rating stars display correctly
4. Verify pros and cons display correctly
5. Verify helpful count shows

**Expected Result:** ✅ Review displays with all details

**API Call:** `GET /api/courses/{courseId}/reviews`

---

## 🧪 Test 4: Mark Review as Helpful

**Steps:**
1. Click "👍 Helpful" button on a review
2. Verify helpful count increases
3. Click again to toggle off
4. Verify helpful count decreases

**Expected Result:** ✅ Helpful toggle works correctly

**API Call:** `POST /api/reviews/{id}/helpful`

---

## 🧪 Test 5: Admin Dashboard - Rating Overview

**Location:** `/rating`

**Steps:**
1. Login as admin
2. Navigate to "Course Reviews & Rating" in sidebar
3. Verify course cards display
4. Verify average ratings show
5. Verify review counts display

**Expected Result:** ✅ Admin overview displays all courses with ratings

**API Call:** `GET /rating`

---

## 🧪 Test 6: Admin Dashboard - Course Details

**Location:** `/rating/{courseId}`

**Steps:**
1. Click on a course card
2. Verify course details display
3. Verify pending reviews show
4. Verify approved reviews show
5. Verify moderation buttons appear

**Expected Result:** ✅ Course details page displays reviews and moderation options

**API Call:** `GET /rating/{courseId}`

---

## 🧪 Test 7: Approve Review (Instructor/Admin)

**Steps:**
1. Go to course rating details
2. Find a pending review
3. Click "Approve" button
4. Verify review status changes to approved
5. Verify review now appears in public list

**Expected Result:** ✅ Review approved and visible to users

**API Call:** `POST /api/reviews/{id}/approve`

---

## 🧪 Test 8: Reject Review (Instructor/Admin)

**Steps:**
1. Go to course rating details
2. Find a pending review
3. Click "Reject" button
4. Enter rejection reason
5. Verify review is removed from pending list

**Expected Result:** ✅ Review rejected and hidden from users

**API Call:** `POST /api/reviews/{id}/reject`

---

## 🧪 Test 9: Authorization - Student Cannot Moderate

**Steps:**
1. Login as student
2. Try to access `/rating` (admin page)
3. Verify access is denied or redirected

**Expected Result:** ✅ Student cannot access admin pages

---

## 🧪 Test 10: Authorization - One Review Per User

**Steps:**
1. Submit a review for a course
2. Try to submit another review for same course
3. Verify error message appears

**Expected Result:** ✅ Cannot submit duplicate reviews

---

## 📊 Test Results Summary

| Test | Status | Notes |
|------|--------|-------|
| 1. Form Display | ⏳ | |
| 2. Submit Review | ⏳ | |
| 3. View Reviews | ⏳ | |
| 4. Mark Helpful | ⏳ | |
| 5. Admin Overview | ⏳ | |
| 6. Course Details | ⏳ | |
| 7. Approve Review | ⏳ | |
| 8. Reject Review | ⏳ | |
| 9. Authorization | ⏳ | |
| 10. Duplicate Check | ⏳ | |

---

## 🐛 Troubleshooting

### Issue: Review form not showing
- **Solution:** Check if course ID is being passed correctly
- **Check:** `data-course-id` attribute in HTML

### Issue: API returns 401 Unauthorized
- **Solution:** Ensure user is logged in
- **Check:** Auth token in localStorage

### Issue: Reviews not loading
- **Solution:** Check browser console for errors
- **Check:** Network tab in DevTools

### Issue: Admin pages not accessible
- **Solution:** Verify user role is admin/instructor
- **Check:** User role in database

---

## 📝 Notes

- All tests should be performed in a test environment first
- Clear browser cache if experiencing issues
- Check Laravel logs: `storage/logs/laravel.log`
- Use browser DevTools Network tab to debug API calls

---

**Status:** Ready for Testing
**Date:** January 5, 2026

