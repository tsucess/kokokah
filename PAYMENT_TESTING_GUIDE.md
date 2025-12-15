# Payment 422 Error - Testing Guide

## 🧪 Test Cases

### Test 1: Kudikah Wallet - Single Course
**Steps:**
1. Go to `/userenroll?level_id=1`
2. Select 1 course
3. Click "Proceed to Payment"
4. Select "Kudikah Wallet"
5. Click "Pay Now"

**Expected Result:**
- ✅ No 422 error
- ✅ Success message: "Successfully purchased 1 course(s) via Kudikah Wallet!"
- ✅ Redirect to `/userclass`
- ✅ Course appears in user's enrolled courses

---

### Test 2: Kudikah Wallet - Multiple Courses
**Steps:**
1. Go to `/userenroll?level_id=1`
2. Select 3 courses
3. Click "Proceed to Payment"
4. Select "Kudikah Wallet"
5. Click "Pay Now"

**Expected Result:**
- ✅ No 422 error
- ✅ Success message: "Successfully purchased 3 course(s) via Kudikah Wallet!"
- ✅ Redirect to `/userclass`
- ✅ All 3 courses appear in user's enrolled courses

---

### Test 3: Paystack Payment
**Steps:**
1. Go to `/userenroll?level_id=1`
2. Select 1 course
3. Click "Proceed to Payment"
4. Select "Paystack"
5. Click "Pay Now"

**Expected Result:**
- ✅ No 422 error
- ✅ Redirect to Paystack payment page
- ✅ Can complete payment

---

### Test 4: Flutterwave Payment
**Steps:**
1. Go to `/userenroll?level_id=1`
2. Select 1 course
3. Click "Proceed to Payment"
4. Select "Flutterwave"
5. Click "Pay Now"

**Expected Result:**
- ✅ No 422 error
- ✅ Redirect to Flutterwave payment page
- ✅ Can complete payment

---

### Test 5: Stripe Payment
**Steps:**
1. Go to `/userenroll?level_id=1`
2. Select 1 course
3. Click "Proceed to Payment"
4. Select "Stripe"
5. Click "Pay Now"

**Expected Result:**
- ✅ No 422 error
- ✅ Redirect to Stripe payment page
- ✅ Can complete payment

---

### Test 6: PayPal Payment
**Steps:**
1. Go to `/userenroll?level_id=1`
2. Select 1 course
3. Click "Proceed to Payment"
4. Select "PayPal"
5. Click "Pay Now"

**Expected Result:**
- ✅ No 422 error
- ✅ Redirect to PayPal payment page
- ✅ Can complete payment

---

### Test 7: Error Handling
**Steps:**
1. Try to purchase with insufficient wallet balance
2. Try to purchase already enrolled course
3. Try to purchase non-existent course

**Expected Result:**
- ✅ Appropriate error messages
- ✅ No 422 errors
- ✅ User stays on page

---

## 🔍 Browser DevTools Verification

### Network Tab
- Check POST requests to `/api/wallet/purchase-course`
- Verify request payload has `course_id` (singular)
- Verify response status is 200 (not 422)

### Console Tab
- No JavaScript errors
- Success messages logged
- No validation errors

---

## ✅ Checklist

- [ ] Kudikah single course works
- [ ] Kudikah multiple courses works
- [ ] Paystack works
- [ ] Flutterwave works
- [ ] Stripe works
- [ ] PayPal works
- [ ] Error handling works
- [ ] Success messages display
- [ ] Redirect works
- [ ] Courses appear in user class

