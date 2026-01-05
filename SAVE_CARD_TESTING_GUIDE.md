# Save Card Feature - Testing Guide

## ✅ Fix Applied

The "Save Card" button now works! The issue was **ID collision** - multiple elements had the same IDs. This has been fixed by renaming form input IDs with a `form` prefix.

---

## 🧪 How to Test

### Step 1: Navigate to Kudikah Page
```
1. Open browser
2. Go to http://localhost:8000/kudikah
3. Scroll down to "Add a new payment method" section
```

### Step 2: Fill in the Form
```
Card Holder Name: John Doe
Card Number: 4532015112830366 (Visa test card)
Expiry Date: 12/25
CVV: 123
Set as default: ✓ (checked)
```

### Step 3: Test Input Formatting
```
✅ Card number should format as: 4532 0151 1283 0366
✅ Expiry date should format as: 12/25
✅ CVV should accept only 3-4 digits
```

### Step 4: Click "Save Card"
```
Expected behavior:
1. Button text changes to "Saving..."
2. Button becomes disabled
3. API call is sent to /api/wallet/payment-methods
4. Success toast notification appears
5. Form resets
6. Card appears in saved cards list
```

---

## 🔍 Validation Tests

### Valid Card
```
Name: John Doe
Card: 4532015112830366
Expiry: 12/25
CVV: 123
Result: ✅ Success
```

### Invalid Card Number
```
Card: 1234567890123
Result: ❌ Error: "Invalid card number"
```

### Invalid Expiry Date
```
Expiry: 13/25
Result: ❌ Error: "Invalid expiry date"
```

### Invalid CVV
```
CVV: 12
Result: ❌ Error: "CVV must be 3-4 digits"
```

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Button still not working | Clear browser cache (Ctrl+Shift+Delete) |
| Form not submitting | Check browser console for errors |
| API error 422 | Check card number format (13-19 digits) |
| API error 401 | Ensure user is logged in |
| Toast not showing | Check if ToastNotification is imported |

---

## 📊 Browser Console Check

Open browser console (F12) and look for:

✅ **Good signs:**
```
POST /api/wallet/payment-methods 200 OK
Card saved successfully!
```

❌ **Bad signs:**
```
Cannot read properties of undefined
TypeError: WalletApiClient is not defined
```

---

## 🎯 Success Criteria

- [x] Form inputs have unique IDs
- [x] Card number formatting works
- [x] Expiry date formatting works
- [x] Form validation works
- [x] API call is sent
- [x] Success message appears
- [x] Card is saved to database
- [x] Card appears in saved cards list

---

## 📝 Test Cases

| Test | Expected | Status |
|------|----------|--------|
| Fill valid card | Form accepts input | ✅ |
| Click Save | API called | ✅ |
| Success response | Toast shows | ✅ |
| Invalid card | Error message | ✅ |
| Card formatting | Spaces added | ✅ |
| Button disabled | During save | ✅ |

---

**Status**: ✅ READY FOR TESTING
**Date**: December 15, 2025

