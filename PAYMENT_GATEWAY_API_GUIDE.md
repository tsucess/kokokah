# 💳 Payment Gateway API Guide

## Backend Endpoints

### **1. Initialize Wallet Deposit**

**Endpoint:** `POST /payments/deposit`

**Request:**
```json
{
    "amount": 5000,
    "gateway": "paystack",
    "currency": "NGN"
}
```

**Response (Success):**
```json
{
    "success": true,
    "data": {
        "payment_id": 123,
        "gateway_data": {
            "authorization_url": "https://checkout.paystack.com/...",
            "reference": "ref_abc123",
            "gateway_response": {...}
        },
        "payment": {
            "id": 123,
            "user_id": 1,
            "amount": 5000,
            "gateway": "paystack",
            "type": "wallet_deposit",
            "status": "pending"
        }
    }
}
```

---

## Gateway-Specific Details

### **Paystack**

**Configuration:**
```env
PAYSTACK_PUBLIC_KEY=pk_live_...
PAYSTACK_SECRET_KEY=sk_live_...
```

**Flow:**
1. Initialize payment → Get authorization URL
2. User redirected to Paystack checkout
3. User completes payment
4. Redirected back to app with reference
5. Verify payment with reference
6. Update wallet balance

**Webhook:**
- Event: `charge.success`
- Verify signature with `PAYSTACK_SECRET_KEY`

---

### **Flutterwave**

**Configuration:**
```env
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK_...
FLUTTERWAVE_SECRET_KEY=FLWSECK_...
FLUTTERWAVE_WEBHOOK_SECRET=...
```

**Flow:**
1. Initialize payment → Get payment link
2. User redirected to Flutterwave checkout
3. User completes payment
4. Redirected back to app with tx_ref
5. Verify payment with tx_ref
6. Update wallet balance

**Webhook:**
- Event: `charge.completed`
- Verify signature with `FLUTTERWAVE_WEBHOOK_SECRET`

---

## Payment Verification

### **Verify Payment**

**Endpoint:** `POST /payments/verify/{gateway}`

**Request:**
```json
{
    "reference": "ref_abc123"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Payment verified successfully",
    "data": {
        "payment_id": 123,
        "status": "completed",
        "amount": 5000,
        "wallet_balance": 15000
    }
}
```

---

## Webhook Handling

### **Paystack Webhook**

**Endpoint:** `POST /webhooks/paystack`

**Payload:**
```json
{
    "event": "charge.success",
    "data": {
        "reference": "ref_abc123",
        "amount": 500000,
        "status": "success",
        "customer": {
            "email": "user@example.com"
        }
    }
}
```

### **Flutterwave Webhook**

**Endpoint:** `POST /webhooks/flutterwave`

**Payload:**
```json
{
    "event": "charge.completed",
    "data": {
        "tx_ref": "ref_abc123",
        "amount": 5000,
        "status": "successful",
        "customer": {
            "email": "user@example.com"
        }
    }
}
```

---

## Error Handling

### **Common Errors**

| Error | Status | Message |
|-------|--------|---------|
| Invalid amount | 400 | Amount must be at least ₦100 |
| Gateway error | 400 | Payment initialization failed |
| Invalid reference | 404 | Payment not found |
| Webhook signature | 401 | Invalid webhook signature |

---

## Testing

### **Test Credentials**

**Paystack Test:**
- Card: 4084084084084081
- Expiry: Any future date
- CVV: Any 3 digits

**Flutterwave Test:**
- Card: 5531886652142950
- Expiry: 09/32
- CVV: 564

---

## Implementation Status

✅ Paystack integration complete
✅ Flutterwave integration complete
✅ Payment verification working
✅ Webhook handling ready
✅ Error handling implemented
✅ Transaction logging enabled

---

**All payment gateways are ready for production!**

