# Kokokah.com LMS API Routes Documentation

## Overview

This document outlines the complete API structure for the Kokokah.com Learning Management System, focusing on the wallet and payment functionality.

## Route Structure

### 🔐 Authentication Routes (Public)
```
POST /api/register                    - User registration
POST /api/login                       - User login
POST /api/forgot-password            - Request password reset
POST /api/reset-password             - Reset password with token
```

### 💰 Wallet Routes (Authenticated)
**Base URL:** `/api/wallet`

```
GET    /api/wallet/                   - Get wallet dashboard & balance
POST   /api/wallet/transfer           - Transfer money to another user
POST   /api/wallet/purchase-course    - Buy course using wallet balance
GET    /api/wallet/transactions       - Get wallet transaction history
GET    /api/wallet/rewards            - Get reward history
POST   /api/wallet/claim-login-reward - Manually claim daily login reward
POST   /api/wallet/check-affordability - Check if user can afford a course
```

### 💳 Payment Routes (Gateway Integration)
**Base URL:** `/api/payments`

#### Authenticated Endpoints
```
GET    /api/payments/gateways         - Get available payment gateways
POST   /api/payments/deposit          - Initialize wallet deposit via gateway
POST   /api/payments/purchase-course  - Initialize course purchase via gateway
GET    /api/payments/history          - Get payment history
GET    /api/payments/{id}             - Get specific payment details
```

#### Public Endpoints (No Authentication)
```
POST   /api/payments/webhook/{gateway}    - Payment webhook handler
GET    /api/payments/callback/{gateway}   - Payment callback/redirect
GET    /api/payments/success/{gateway}    - Payment success page
GET    /api/payments/cancel/{gateway}     - Payment cancellation page
```

## Key Differences Between Wallet & Payment Routes

### 🏦 Wallet Routes
- **Purpose**: Manage existing wallet balance and internal transactions
- **Use Cases**: 
  - Transfer money between users
  - Purchase courses using existing wallet balance
  - View transaction history
  - Claim rewards

### 💳 Payment Routes  
- **Purpose**: Handle external payment gateway integrations
- **Use Cases**:
  - Add money to wallet via Paystack/Flutterwave/Stripe/PayPal
  - Purchase courses directly via payment gateways
  - Handle payment callbacks and webhooks

## API Usage Examples

### 1. Check Wallet Balance
```http
GET /api/wallet/
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "balance": 5000.00,
    "stats": {
      "total_deposits": 10000.00,
      "total_spending": 3000.00,
      "total_rewards": 500.00
    },
    "recent_transactions": [...],
    "login_streak": 7
  }
}
```

### 2. Add Money to Wallet (via Payment Gateway)
```http
POST /api/payments/deposit
Authorization: Bearer {token}
Content-Type: application/json

{
  "amount": 1000,
  "gateway": "paystack",
  "currency": "NGN"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Payment initialized successfully",
  "data": {
    "payment_id": 123,
    "gateway_data": {
      "authorization_url": "https://checkout.paystack.com/...",
      "reference": "PAY_123456789"
    }
  }
}
```

### 3. Transfer Money to Another User
```http
POST /api/wallet/transfer
Authorization: Bearer {token}
Content-Type: application/json

{
  "recipient_email": "friend@example.com",
  "amount": 500,
  "description": "Thanks for helping with homework!"
}
```

### 4. Purchase Course with Wallet Balance
```http
POST /api/wallet/purchase-course
Authorization: Bearer {token}
Content-Type: application/json

{
  "course_id": 1,
  "coupon_code": "STUDENT10"
}
```

### 5. Purchase Course via Payment Gateway
```http
POST /api/payments/purchase-course
Authorization: Bearer {token}
Content-Type: application/json

{
  "course_id": 1,
  "gateway": "paystack",
  "coupon_code": "STUDENT10"
}
```

## Payment Flow Comparison

### Wallet-Based Purchase Flow
```
User has wallet balance → Select course → Apply coupon → 
Deduct from wallet → Enroll immediately → Success
```

### Gateway-Based Purchase Flow
```
User selects course → Choose payment gateway → Apply coupon → 
Redirect to gateway → Complete payment → Webhook verification → 
Enroll user → Success
```

## Error Handling

### Common Error Responses

#### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "amount": ["The amount field is required."],
    "gateway": ["The selected gateway is invalid."]
  }
}
```

#### Insufficient Balance (400)
```json
{
  "success": false,
  "message": "Insufficient balance for this transaction",
  "data": {
    "required": 1000.00,
    "available": 500.00,
    "shortfall": 500.00
  }
}
```

#### Authentication Error (401)
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

## Rate Limiting

- **Authentication endpoints**: 5 requests per minute
- **Payment endpoints**: 10 requests per minute  
- **Wallet endpoints**: 60 requests per minute
- **General API**: 100 requests per minute

## Security Headers Required

```http
Authorization: Bearer {jwt_token}
Content-Type: application/json
Accept: application/json
```

## Webhook Security

### Webhook URLs
```
Paystack:    https://yourdomain.com/api/payments/webhook/paystack
Flutterwave: https://yourdomain.com/api/payments/webhook/flutterwave
Stripe:      https://yourdomain.com/api/payments/webhook/stripe
PayPal:      https://yourdomain.com/api/payments/webhook/paypal
```

### Signature Verification
All webhooks verify signatures using gateway-specific secrets configured in environment variables.

## Testing

### Test Environment
- Use sandbox/test credentials for all gateways
- Test webhook endpoints using gateway testing tools
- Verify payment flows with test card numbers

### Test Cards
- **Paystack**: 4084084084084081
- **Flutterwave**: 5531886652142950  
- **Stripe**: 4242424242424242
- **PayPal**: Use sandbox accounts

## Support

For API integration support:
- Check response status codes and error messages
- Verify authentication tokens
- Test in sandbox mode before production
- Monitor webhook delivery status
- Implement proper error handling and user feedback
