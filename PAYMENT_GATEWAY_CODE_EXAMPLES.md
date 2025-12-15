# Payment Gateway Integration - Code Examples

## PaymentApiClient Usage

### Initialize Course Payment
```javascript
import PaymentApiClient from './paymentApiClient.js';

// Single course
const result = await PaymentApiClient.initializeCoursePayment({
    course_ids: [1],
    gateway: 'paystack'
});

// Multiple courses
const result = await PaymentApiClient.initializeCoursePayment({
    course_ids: [1, 2, 3],
    gateway: 'flutterwave'
});

// With coupon
const result = await PaymentApiClient.initializeCoursePayment({
    course_ids: [1, 2],
    gateway: 'stripe',
    coupon_code: 'SAVE10'
});
```

### Initialize Wallet Deposit
```javascript
const result = await PaymentApiClient.initializeWalletDeposit({
    amount: 5000,
    gateway: 'paystack',
    currency: 'NGN'
});
```

### Get Payment History
```javascript
const history = await PaymentApiClient.getHistory({
    page: 1,
    per_page: 20,
    type: 'course_purchase',
    status: 'completed'
});
```

---

## Payment Processing Functions

### Kudikah Wallet
```javascript
async function processKudikahPayment(paymentData) {
    try {
        showLoadingState('Processing Kudikah Wallet payment...');
        
        const result = await PaymentApiClient.initializeCoursePayment({
            course_ids: paymentData.courses,
            gateway: 'kudikah'
        });

        if (result.success) {
            showSuccessMessage('Payment processed successfully!');
            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 2000);
        }
    } catch (error) {
        showErrorMessage('Error: ' + error.message);
    }
}
```

### Paystack
```javascript
async function processPaystackPayment(paymentData) {
    try {
        showLoadingState('Initializing Paystack payment...');
        
        const result = await PaymentApiClient.initializeCoursePayment({
            course_ids: paymentData.courses,
            gateway: 'paystack'
        });

        if (result.success && result.data.authorization_url) {
            window.location.href = result.data.authorization_url;
        }
    } catch (error) {
        showErrorMessage('Error: ' + error.message);
    }
}
```

### Flutterwave
```javascript
async function processFlutterwavePayment(paymentData) {
    try {
        showLoadingState('Initializing Flutterwave payment...');
        
        const result = await PaymentApiClient.initializeCoursePayment({
            course_ids: paymentData.courses,
            gateway: 'flutterwave'
        });

        if (result.success && result.data.authorization_url) {
            window.location.href = result.data.authorization_url;
        }
    } catch (error) {
        showErrorMessage('Error: ' + error.message);
    }
}
```

---

## API Response Examples

### Success Response
```json
{
    "success": true,
    "message": "Payment initialized successfully",
    "data": {
        "payment_id": 123,
        "reference": "PAY_123456",
        "authorization_url": "https://checkout.paystack.com/...",
        "access_code": "abc123",
        "gateway": "paystack"
    }
}
```

### Error Response
```json
{
    "success": false,
    "message": "Payment initialization failed",
    "error": "Invalid course ID"
}
```

---

## Error Handling Examples

```javascript
try {
    const result = await PaymentApiClient.initializeCoursePayment(data);
    
    if (!result.success) {
        showErrorMessage(result.message);
        return;
    }
    
    // Process successful response
} catch (error) {
    if (error.message.includes('Network')) {
        showErrorMessage('Network error. Please check your connection.');
    } else if (error.message.includes('401')) {
        showErrorMessage('Session expired. Please login again.');
    } else {
        showErrorMessage('An error occurred: ' + error.message);
    }
}
```

---

## Testing Payment Flows

### Test Kudikah Wallet
1. Select courses
2. Click Proceed
3. Select Kudikah Wallet
4. Click Proceed with Payment
5. Verify success message
6. Check dashboard for enrolled courses

### Test External Gateways
1. Select courses
2. Click Proceed
3. Select gateway (Paystack/Flutterwave/Stripe/PayPal)
4. Click Proceed with Payment
5. Complete payment on gateway
6. Verify callback and enrollment

