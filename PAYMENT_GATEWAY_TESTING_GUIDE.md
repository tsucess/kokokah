# 🧪 Payment Gateway Testing Guide

## Test Environment Setup

### **Prerequisites**
- Paystack test account (https://dashboard.paystack.com)
- Flutterwave test account (https://dashboard.flutterwave.com)
- Browser DevTools open (F12)
- Network tab visible

---

## Test Credentials

### **Paystack Test Card**
```
Card Number: 4084084084084081
Expiry: Any future date (e.g., 12/25)
CVV: Any 3 digits (e.g., 123)
OTP: 123456 (when prompted)
```

### **Flutterwave Test Card**
```
Card Number: 5531886652142950
Expiry: 09/32
CVV: 564
PIN: 1234
OTP: 12345
```

---

## Test Scenarios

### **Scenario 1: Valid Payment Flow**

**Steps:**
1. Click "Add Money" button
2. Enter amount: ₦5000
3. Click "Continue"
4. Select "Paystack"
5. Click "Continue"
6. Complete payment on Paystack
7. Return to app

**Expected Results:**
- ✅ Amount modal appears
- ✅ Gateway modal appears
- ✅ Redirected to Paystack
- ✅ Payment processed
- ✅ Wallet updated
- ✅ Success message shown

---

### **Scenario 2: Invalid Amount**

**Steps:**
1. Click "Add Money"
2. Enter amount: ₦50 (less than minimum)
3. Click "Continue"

**Expected Results:**
- ✅ Error message: "Amount must be at least ₦100"
- ✅ Modal stays open
- ✅ Amount field focused

---

### **Scenario 3: No Gateway Selected**

**Steps:**
1. Click "Add Money"
2. Enter amount: ₦5000
3. Click "Continue"
4. Click "Continue" without selecting gateway

**Expected Results:**
- ✅ Error message: "Please select a payment gateway"
- ✅ Modal stays open

---

### **Scenario 4: Cancel Payment**

**Steps:**
1. Click "Add Money"
2. Enter amount: ₦5000
3. Click "Cancel"

**Expected Results:**
- ✅ Modal closes
- ✅ Amount field cleared
- ✅ No payment initiated

---

### **Scenario 5: Flutterwave Payment**

**Steps:**
1. Click "Add Money"
2. Enter amount: ₦10000
3. Click "Continue"
4. Select "Flutterwave"
5. Click "Continue"
6. Complete payment on Flutterwave
7. Return to app

**Expected Results:**
- ✅ Redirected to Flutterwave
- ✅ Payment processed
- ✅ Wallet updated
- ✅ Transaction recorded

---

## Browser Console Testing

### **Check Payment Initialization**
```javascript
// Open DevTools Console (F12)
// Look for these logs:

// 1. Payment initialization log
console.log('Initializing wallet deposit:', {
    amount: 5000,
    gateway: 'paystack',
    currency: 'NGN'
});

// 2. Response log
console.log('Payment initialization response:', result);

// 3. Check authorization URL
result.data.gateway_data.authorization_url
```

### **Check Network Requests**
1. Open DevTools → Network tab
2. Click "Add Money"
3. Look for request: `POST /payments/deposit`
4. Check response:
   - Status: 200
   - Contains `authorization_url`
   - Contains `payment_id`

---

## Debugging Checklist

- [ ] Console shows no JavaScript errors
- [ ] Network request returns 200 status
- [ ] Response contains `authorization_url`
- [ ] Redirect happens within 1 second
- [ ] Payment gateway loads correctly
- [ ] Test card accepted by gateway
- [ ] Redirect back to app works
- [ ] Wallet balance updated
- [ ] Transaction appears in history

---

## Common Test Issues

| Issue | Solution |
|-------|----------|
| "Amount must be at least ₦100" | Enter amount ≥ ₦100 |
| "Please select a payment gateway" | Click on gateway option |
| Redirect not working | Check console for errors |
| Payment not verified | Check webhook configuration |
| Card declined | Use correct test card |
| OTP not working | Use correct OTP (123456 for Paystack) |

---

## Production Testing

### **Before Going Live**

1. **Switch to Live Keys**
   - Update `.env` with live API keys
   - Verify keys are correct

2. **Test with Real Payment**
   - Use small amount (₦100)
   - Complete full payment flow
   - Verify wallet updated

3. **Test Webhook**
   - Verify webhook endpoint accessible
   - Check webhook signature validation
   - Verify payment status updates

4. **Test Error Handling**
   - Test failed payment
   - Test network timeout
   - Test invalid reference

---

## Performance Testing

### **Load Testing**
- Test with multiple concurrent payments
- Monitor API response time
- Check database performance

### **Stress Testing**
- Test with large amounts
- Test with rapid requests
- Monitor server resources

---

## Security Testing

- [ ] CSRF token validated
- [ ] User authentication required
- [ ] Amount cannot be modified
- [ ] Gateway reference verified
- [ ] Webhook signature validated
- [ ] No sensitive data in logs
- [ ] HTTPS enforced

---

## Test Results Template

```
Date: ___________
Tester: ___________
Environment: Test / Production

Scenario 1 (Valid Payment): PASS / FAIL
Scenario 2 (Invalid Amount): PASS / FAIL
Scenario 3 (No Gateway): PASS / FAIL
Scenario 4 (Cancel): PASS / FAIL
Scenario 5 (Flutterwave): PASS / FAIL

Issues Found:
- ___________
- ___________

Notes:
___________
```

---

## Support

For issues or questions:
1. Check browser console for errors
2. Check network requests
3. Review API response
4. Check payment status in database
5. Contact payment gateway support

---

**Ready to test! 🚀**

