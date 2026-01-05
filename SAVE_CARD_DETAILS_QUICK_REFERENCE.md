# Save Card Details - Quick Reference

## 🚀 Quick Start

### 1. Database Setup
```bash
php artisan migrate
```

### 2. Test the Feature
```bash
# Run tests
php artisan test tests/Feature/PaymentMethodApiTest.php

# Test in browser
# Navigate to /kudikah page and fill the "Add a new payment method" form
```

### 3. API Usage (JavaScript)
```javascript
import WalletApiClient from '/js/api/walletApiClient.js';

// Get saved cards
const cards = await WalletApiClient.getPaymentMethods();

// Save a card
const result = await WalletApiClient.addPaymentMethod({
    card_holder_name: 'John Doe',
    card_number: '4532015112830366',
    expiry_date: '12/25',
    cvv: '123',
    is_default: true
});

// Delete a card
await WalletApiClient.deletePaymentMethod(cardId);

// Set as default
await WalletApiClient.setDefaultPaymentMethod(cardId);
```

---

## 📁 File Locations

| Component | File |
|-----------|------|
| Model | `app/Models/PaymentMethod.php` |
| Migration | `database/migrations/2025_12_15_083319_create_payment_methods_table.php` |
| Controller | `app/Http/Controllers/WalletController.php` |
| Routes | `routes/api.php` (lines 153-168) |
| Frontend | `resources/views/users/kudikah.blade.php` (lines 250-665) |
| Tests | `tests/Feature/PaymentMethodApiTest.php` |
| Factory | `database/factories/PaymentMethodFactory.php` |

---

## 🔑 Key Methods

### WalletController
```php
// Get user's payment methods
public function getPaymentMethods()

// Add new payment method
public function addPaymentMethod(Request $request)

// Delete payment method
public function deletePaymentMethod($methodId)

// Set default payment method
public function setDefaultPaymentMethod($methodId)

// Detect card type
private function detectCardType($cardNumber)
```

### PaymentMethod Model
```php
// Get masked card number
$method->getMaskedCardNumber(); // Returns: **** **** **** 0366

// Scopes
PaymentMethod::default()->get();  // Get default cards
PaymentMethod::saved()->get();    // Get saved cards

// Relationships
$method->user;  // Get card owner
```

---

## 🧪 Test Examples

```php
// Test adding a card
$response = $this->actingAs($user)
    ->postJson('/api/wallet/payment-methods', [
        'card_holder_name' => 'John Doe',
        'card_number' => '4532015112830366',
        'expiry_date' => '12/25',
        'cvv' => '123'
    ]);

// Test getting cards
$response = $this->actingAs($user)
    ->getJson('/api/wallet/payment-methods');

// Test deleting a card
$response = $this->actingAs($user)
    ->deleteJson("/api/wallet/payment-methods/{$cardId}");
```

---

## 🔐 Validation Rules

| Field | Rule | Example |
|-------|------|---------|
| Card Holder | 3+ chars | John Doe |
| Card Number | 13-19 digits | 4532015112830366 |
| Expiry Date | MM/YY format | 12/25 |
| CVV | 3-4 digits | 123 |

---

## 🎨 Frontend Form IDs

```html
<form id="cardDetailsForm">
    <input id="cardHolderName" />
    <input id="cardNumber" />
    <input id="expiryDate" />
    <input id="cvv" />
    <input id="isDefault" type="checkbox" />
    <button id="saveCardBtn" type="submit">Save Card</button>
</form>
```

---

## 📊 Database Schema

```sql
CREATE TABLE payment_methods (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    card_holder_name VARCHAR(255),
    card_number TEXT,  -- Encrypted
    card_last_four VARCHAR(4),
    expiry_date VARCHAR(5),
    cvv TEXT,  -- Encrypted
    card_type VARCHAR(50),
    is_default BOOLEAN DEFAULT FALSE,
    is_saved BOOLEAN DEFAULT TRUE,
    last_used_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| 422 Validation Error | Check card number format (13-19 digits) |
| 401 Unauthorized | Ensure user is authenticated |
| Encryption Error | Check APP_KEY is set in .env |
| Form not submitting | Check browser console for JavaScript errors |

---

## 📞 Support Resources

- Implementation Guide: `SAVE_CARD_DETAILS_IMPLEMENTATION_GUIDE.md`
- Complete Summary: `SAVE_CARD_DETAILS_FEATURE_SUMMARY.md`
- Test Suite: `tests/Feature/PaymentMethodApiTest.php`

