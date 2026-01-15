# Test Card Details for Kudikah Wallet

This document contains sample card details for testing the add card feature on the kudikah page.

## ⚠️ Important Notice

These are **test card numbers only** - they are publicly available test numbers used for development and testing purposes. They are **NOT real credit cards** and should never be used for actual transactions.

---

## Sample Test Cards

### Card 1 (Visa)
```
Card Holder Name: John Doe
Card Number: 4532015112830366
Expiry Date: 12/25
CVV: 123
```

### Card 2 (Mastercard)
```
Card Holder Name: Jane Smith
Card Number: 5425233010103442
Expiry Date: 06/26
CVV: 456
```

### Card 3 (American Express)
```
Card Holder Name: Robert Johnson
Card Number: 374245455400126
Expiry Date: 09/27
CVV: 789
```

### Card 4 (Discover)
```
Card Holder Name: Sarah Williams
Card Number: 6011111111111117
Expiry Date: 03/25
CVV: 234
```

---

## Why These Cards Work

✅ All cards have exactly **16 digits**
✅ All cards pass the **Luhn algorithm validation**
✅ All expiry dates are in the **future** (valid MM/YY format)
✅ All CVV codes are exactly **3 digits**

---

## How to Test

1. Navigate to the kudikah page
2. Click the **"Add Card"** button
3. Fill in the form with any of the sample details above
4. Observe real-time validation feedback as you type
5. Click **"Save New Card"** to submit

---

## Testing Validation

Try entering invalid data to test the validation rules:

- **Card Number**: Less than 16 digits (should show error)
- **Card Number**: More than 16 digits (should auto-limit to 16)
- **Expiry Date**: Past date like 01/24 (should show "Card has expired")
- **Expiry Date**: Invalid month like 13/25 (should show "Invalid month (01-12)")
- **CVV**: Less than 3 digits (should show progress feedback)
- **CVV**: More than 3 digits (should auto-limit to 3)
- **Cardholder Name**: Less than 3 characters (should show error)
- **Cardholder Name**: Special characters like "John@Doe" (should show error)

---

## Validation Features

✅ Card number must be exactly 16 digits
✅ Card number passes Luhn algorithm check
✅ Expiry date must be MM/YY format
✅ Expiry date month must be 01-12
✅ Card cannot be expired
✅ CVV must be exactly 3 digits
✅ Cardholder name must be 3+ characters
✅ Cardholder name can only contain letters, spaces, hyphens, and apostrophes
✅ Real-time validation feedback as you type

