# Payment System - Testing Checklist

## 🧪 Pre-Testing Verification

- [ ] No JavaScript syntax errors in browser console
- [ ] No 422 validation errors
- [ ] Payment modal opens correctly
- [ ] All payment gateway options visible

---

## Test 1: Kudikah Wallet - Single Course

**Steps:**
1. Navigate to `/userenroll?level_id=1`
2. Select 1 course
3. Click "Proceed to Payment"
4. Select "Kudikah Wallet"
5. Click "Pay Now"

**Expected Results:**
- [ ] No 422 error
- [ ] No syntax error
- [ ] Loading state shows
- [ ] Success message: "Successfully purchased 1 course(s) via Kudikah Wallet!"
- [ ] Redirect to `/userclass`
- [ ] Course appears in enrolled courses

---

## Test 2: Kudikah Wallet - Multiple Courses

**Steps:**
1. Navigate to `/userenroll?level_id=1`
2. Select 3 courses
3. Click "Proceed to Payment"
4. Select "Kudikah Wallet"
5. Click "Pay Now"

**Expected Results:**
- [ ] No 422 error
- [ ] No syntax error
- [ ] Loading state shows
- [ ] Success message: "Successfully purchased 3 course(s) via Kudikah Wallet!"
- [ ] Redirect to `/userclass`
- [ ] All 3 courses appear in enrolled courses

---

## Test 3: Paystack Payment

**Steps:**
1. Navigate to `/userenroll?level_id=1`
2. Select 1 course
3. Click "Proceed to Payment"
4. Select "Paystack"
5. Click "Pay Now"

**Expected Results:**
- [ ] No 422 error
- [ ] No syntax error
- [ ] Redirect to Paystack payment page
- [ ] Can complete payment

---

## Test 4: Flutterwave Payment

**Steps:**
1. Navigate to `/userenroll?level_id=1`
2. Select 1 course
3. Click "Proceed to Payment"
4. Select "Flutterwave"
5. Click "Pay Now"

**Expected Results:**
- [ ] No 422 error
- [ ] No syntax error
- [ ] Redirect to Flutterwave payment page
- [ ] Can complete payment

---

## Test 5: Stripe Payment

**Steps:**
1. Navigate to `/userenroll?level_id=1`
2. Select 1 course
3. Click "Proceed to Payment"
4. Select "Stripe"
5. Click "Pay Now"

**Expected Results:**
- [ ] No 422 error
- [ ] No syntax error
- [ ] Redirect to Stripe payment page
- [ ] Can complete payment

---

## Test 6: PayPal Payment

**Steps:**
1. Navigate to `/userenroll?level_id=1`
2. Select 1 course
3. Click "Proceed to Payment"
4. Select "PayPal"
5. Click "Pay Now"

**Expected Results:**
- [ ] No 422 error
- [ ] No syntax error
- [ ] Redirect to PayPal payment page
- [ ] Can complete payment

---

## Test 7: Error Scenarios

**Test 7a: Insufficient Balance**
- [ ] Show error: "Insufficient balance"
- [ ] User stays on page
- [ ] No 422 error

**Test 7b: Already Enrolled**
- [ ] Show error: "You are already enrolled in this course"
- [ ] User stays on page
- [ ] No 422 error

**Test 7c: Invalid Course**
- [ ] Show error: "Course not found"
- [ ] User stays on page
- [ ] No 422 error

---

## Browser DevTools Verification

### Network Tab
- [ ] POST to `/api/wallet/purchase-course` returns 200
- [ ] Request payload has `course_id` (singular)
- [ ] Response has `success: true`

### Console Tab
- [ ] No JavaScript errors
- [ ] No validation errors
- [ ] Success messages logged

---

## Final Verification

- [ ] All tests passed
- [ ] No errors in console
- [ ] Payment system fully functional
- [ ] Ready for production

