# User Enroll Page - Enroll All Button with 10% Discount

## ✅ Feature Implemented

Updated the "Enroll in All" button to calculate the total price of all courses with a 10% discount applied.

---

## 📝 Changes Made

### File: `resources/views/users/enroll.blade.php`

#### 1. Updated `updateEnrollAllButton()` Function (Lines 421-435)

**Before:**
```javascript
function updateEnrollAllButton() {
    const totalPrice = allCourses.reduce((sum, course) => sum + (course.price || 0), 0);
    const courseCount = allCourses.length;
    document.getElementById('enrollAllBtn').textContent =
        `Enroll in All ${courseCount} Subjects - ${formatNGN(totalPrice)}`;
}
```

**After:**
```javascript
function updateEnrollAllButton() {
    // Calculate total price of all courses
    const totalPrice = allCourses.reduce((sum, course) => sum + (Number(course.price) || 0), 0);
    
    // Apply 10% discount
    const discountAmount = totalPrice * 0.10;
    const discountedPrice = totalPrice - discountAmount;
    
    const courseCount = allCourses.length;
    document.getElementById('enrollAllBtn').textContent =
        `Enroll in All ${courseCount} Subjects - ${formatNGN(discountedPrice)}`;
}
```

#### 2. Call `updateEnrollAllButton()` on Course Load (Line 341)

**Added:**
```javascript
if (courses && courses.length > 0) {
    allCourses = courses;
    displayCourses(allCourses);
    updateLevelTitle(levelId);
    updateEnrollAllButton();  // ← Added this line
}
```

This ensures the button displays the correct discounted price immediately when courses are loaded, not just when a checkbox is selected.

---

## 🎯 Calculation Logic

**Formula:**
```
Total Price = Sum of all course prices
Discount Amount = Total Price × 10%
Discounted Price = Total Price - Discount Amount
```

**Example:**
- Course 1: ₦5,000
- Course 2: ₦6,000
- Course 3: ₦5,500
- **Total:** ₦16,500
- **Discount (10%):** ₦1,650
- **Final Price:** ₦14,850

---

## 🎯 Features

✅ **Automatic Calculation** - Calculates total of all courses
✅ **10% Discount Applied** - Automatically deducts 10% from total
✅ **Dynamic Updates** - Updates when courses load
✅ **Formatted Display** - Shows price in NGN currency format
✅ **Course Count** - Displays number of courses in button text

---

## 🧪 Testing

- [x] Button displays correct total with 10% discount
- [x] Discount is calculated correctly
- [x] Price is formatted as NGN currency
- [x] Course count is accurate
- [x] Updates when courses load
- [x] Works with different level_id values

---

## ✅ Status: COMPLETE

The "Enroll in All" button now displays the total price of all courses with a 10% discount applied.

