# Save Card Details - Implementation Guide

## 🏗️ Architecture Overview

```
Frontend (Kudikah Page)
    ↓
Form Validation & Formatting
    ↓
WalletApiClient (JavaScript)
    ↓
API Routes (/api/wallet/payment-methods)
    ↓
WalletController Methods
    ↓
PaymentMethod Model
    ↓
Database (payment_methods table)
```

---

## 🔧 Backend Implementation

### 1. PaymentMethod Model (`app/Models/PaymentMethod.php`)
- Relationships: `belongsTo(User)`
- Scopes: `default()`, `saved()`
- Methods: `getMaskedCardNumber()`
- Casts: Automatic type casting for booleans and dates
- Hidden: card_number, cvv (never exposed in API)

### 2. WalletController Methods
- `getPaymentMethods()` - Returns user's saved cards with masked numbers
- `addPaymentMethod()` - Validates and encrypts card data
- `deletePaymentMethod()` - Removes card and reassigns default if needed
- `setDefaultPaymentMethod()` - Updates default payment method
- `detectCardType()` - Identifies card type from number

### 3. Database Schema
```sql
CREATE TABLE payment_methods (
    id BIGINT PRIMARY KEY,
    user_id BIGINT FOREIGN KEY,
    card_holder_name VARCHAR(255),
    card_number TEXT (encrypted),
    card_last_four VARCHAR(4),
    expiry_date VARCHAR(5),
    cvv TEXT (encrypted),
    card_type VARCHAR(50),
    is_default BOOLEAN,
    is_saved BOOLEAN,
    last_used_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)
```

---

## 🎨 Frontend Implementation

### 1. Form Structure (Kudikah Page)
- Card Holder Name: Text input with validation
- Card Number: Formatted with spaces (1234 5678 9012 3456)
- Expiry Date: MM/YY format with auto-formatting
- CVV: Password field, 3-4 digits
- Default Checkbox: Set as default payment method

### 2. JavaScript Functions
- `handleSaveCard()` - Form submission handler
- `validateCardForm()` - Client-side validation
- `formatCardNumber()` - Add spaces every 4 digits
- `formatExpiryDate()` - Format as MM/YY
- `showError()` - Display validation errors

### 3. API Integration
Uses `WalletApiClient` methods:
- `addPaymentMethod(data)` - POST request
- `getPaymentMethods()` - GET request
- `deletePaymentMethod(id)` - DELETE request
- `setDefaultPaymentMethod(id)` - POST request

---

## 🔐 Security Implementation

### Encryption
```php
// Encrypt sensitive data
$cardNumber = Crypt::encryptString($request->card_number);
$cvv = Crypt::encryptString($request->cvv);
```

### Validation Rules
```php
'card_number' => 'required|string|regex:/^\d{13,19}$/',
'expiry_date' => 'required|string|regex:/^\d{2}\/\d{2}$/',
'cvv' => 'required|string|regex:/^\d{3,4}$/',
```

### Card Type Detection
- Visa: Starts with 4
- Mastercard: Starts with 51-55
- Amex: Starts with 34 or 37
- Discover: Starts with 6011 or 65

---

## 📝 API Endpoints

### Get Payment Methods
```
GET /api/wallet/payment-methods
Authorization: Bearer {token}

Response:
{
    "success": true,
    "data": [
        {
            "id": 1,
            "card_holder_name": "John Doe",
            "card_last_four": "0366",
            "expiry_date": "12/25",
            "card_type": "visa",
            "is_default": true,
            "masked_card": "**** **** **** 0366"
        }
    ]
}
```

### Add Payment Method
```
POST /api/wallet/payment-methods
Authorization: Bearer {token}
Content-Type: application/json

Request:
{
    "card_holder_name": "John Doe",
    "card_number": "4532015112830366",
    "expiry_date": "12/25",
    "cvv": "123",
    "is_default": true
}

Response: 200 OK with saved card data
```

---

## 🧪 Testing

Run tests with:
```bash
php artisan test tests/Feature/PaymentMethodApiTest.php
```

Test coverage includes:
- CRUD operations
- Validation rules
- Card type detection
- User relationships
- Default payment method logic
- Encryption/decryption

---

## 🚀 Deployment Checklist

- [ ] Run migrations: `php artisan migrate`
- [ ] Clear cache: `php artisan cache:clear`
- [ ] Test API endpoints
- [ ] Test frontend form
- [ ] Verify encryption working
- [ ] Test with real payment data
- [ ] Monitor error logs

