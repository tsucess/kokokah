# Payment Gateway Integration - Technical Guide

## Overview

Complete payment consumption implementation for 5 payment gateways in the user enroll page.

---

## Architecture

### PaymentApiClient (`public/js/api/paymentApiClient.js`)

**Purpose:** Centralized API client for all payment operations

**Key Methods:**
```javascript
// Initialize course payment
await PaymentApiClient.initializeCoursePayment({
    course_ids: [1, 2, 3],
    gateway: 'paystack'
});

// Initialize wallet deposit
await PaymentApiClient.initializeWalletDeposit({
    amount: 5000,
    gateway: 'paystack',
    currency: 'NGN'
});

// Get payment history
await PaymentApiClient.getHistory({
    page: 1,
    per_page: 20,
    type: 'course_purchase',
    status: 'completed'
});
```

---

## Payment Gateway Implementations

### 1. Kudikah Wallet
- **Type:** Direct wallet deduction
- **Flow:** API call → Success → Dashboard redirect
- **No external redirect required**

### 2. Paystack
- **Type:** External payment gateway
- **Flow:** API call → Get authorization_url → Redirect → User pays → Callback
- **Requires:** Paystack API key configured

### 3. Flutterwave
- **Type:** External payment gateway
- **Flow:** API call → Get authorization_url → Redirect → User pays → Callback
- **Requires:** Flutterwave API key configured

### 4. Stripe
- **Type:** External payment gateway
- **Flow:** API call → Get authorization_url → Redirect → User pays → Callback
- **Requires:** Stripe API key configured

### 5. PayPal
- **Type:** External payment gateway
- **Flow:** API call → Get authorization_url → Redirect → User pays → Callback
- **Requires:** PayPal API credentials configured

---

## Error Handling

**Try-Catch Blocks:**
- Catches network errors
- Catches API errors
- Shows user-friendly error messages
- Provides retry option

**Error Messages:**
- Network errors
- API validation errors
- Gateway initialization errors
- Payment processing errors

---

## UI Feedback

**Loading State:**
- Shows spinner
- Displays processing message
- Disables user interaction

**Success State:**
- Shows checkmark icon
- Displays success message
- Auto-redirects after 2 seconds

**Error State:**
- Shows error icon
- Displays error message
- Provides retry button

---

## Testing Checklist

- [ ] Kudikah Wallet payment works
- [ ] Paystack redirect works
- [ ] Flutterwave redirect works
- [ ] Stripe redirect works
- [ ] PayPal redirect works
- [ ] Error handling works
- [ ] Loading states display correctly
- [ ] Success messages show correctly
- [ ] Multiple courses can be purchased
- [ ] Payment history is recorded

---

## Configuration

**Backend Configuration:**
- Payment gateway API keys in `.env`
- Callback URLs configured
- Webhook handlers set up

**Frontend Configuration:**
- PaymentApiClient imported
- API endpoints accessible
- Authentication tokens available

---

## Next Steps

1. Test each payment gateway
2. Verify payment callbacks
3. Check payment history recording
4. Test error scenarios
5. Deploy to production

