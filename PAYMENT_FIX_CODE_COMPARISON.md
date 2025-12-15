# Payment 422 Error - Code Comparison

## File 1: enroll.blade.php - Kudikah Payment Handler

### ❌ BEFORE (Lines 970-1010)
```javascript
// Using PaymentApiClient with kudikah gateway
for (const courseId of courseIds) {
    const paymentRequest = {
        course_id: courseId,
        gateway: 'kudikah'  // ❌ Not supported in PaymentController
    };

    const result = await PaymentApiClient.initializeCoursePayment(paymentRequest);
    // Result: 422 Validation Error
}
```

### ✅ AFTER (Lines 970-1001)
```javascript
// Using WalletApiClient for Kudikah
for (const courseId of courseIds) {
    const result = await WalletApiClient.purchaseCourse(courseId);
    // Result: 200 Success
}
```

---

## File 2: walletApiClient.js - purchaseCourse Method

### ❌ BEFORE (Lines 179-187)
```javascript
/**
 * Purchase course using wallet balance
 * @param {array} courseIds - Array of course IDs to purchase
 */
static async purchaseCourse(courseIds) {
    return this.post('/wallet/purchase-course', {
        course_ids: courseIds  // ❌ Wrong: plural array
    });
}
```

**Problem:** Backend expects `course_id` (singular), not `course_ids` (plural)

### ✅ AFTER (Lines 179-194)
```javascript
/**
 * Purchase course using wallet balance
 * @param {number} courseId - Course ID to purchase
 * @param {string} couponCode - Optional coupon code
 */
static async purchaseCourse(courseId, couponCode = null) {
    const payload = {
        course_id: courseId  // ✅ Correct: singular
    };
    
    if (couponCode) {
        payload.coupon_code = couponCode;
    }
    
    return this.post('/wallet/purchase-course', payload);
}
```

**Improvement:** Now sends correct singular `course_id` parameter

---

## Backend Validation Rules

### PaymentController (Line 88)
```php
'gateway' => 'required|string|in:paystack,flutterwave,stripe,paypal'
// ❌ Kudikah NOT supported
```

### WalletController (Line 109)
```php
'course_id' => 'required|exists:courses,id'
// ✅ Expects singular course_id
```

---

## Summary of Changes

| Aspect | Before | After |
|--------|--------|-------|
| Kudikah API | PaymentApiClient | WalletApiClient |
| Parameter | course_ids (array) | course_id (single) |
| Endpoint | /api/payments/purchase-course | /api/wallet/purchase-course |
| Validation | 422 Error | 200 Success |
| User Experience | Failed payment | Successful purchase |

