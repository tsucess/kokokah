# Display Saved Card - Testing Guide

## ✅ Feature Complete

The saved card is now automatically displayed on the card display section when the page loads.

---

## 🧪 Test Scenario 1: Page Load with Existing Card

### Steps
1. Navigate to `/kudikah` page
2. Wait for page to load

### Expected Result
- ✅ Card display shows the default saved card
- ✅ Card number is masked (e.g., **** **** **** 0366)
- ✅ Card holder name is displayed
- ✅ Expiry date is displayed (MM/YY)

### Example Display
```
Card Number: **** **** **** 0366
Card Holder: John Doe
Valid Thru: 12/25
```

---

## 🧪 Test Scenario 2: Save New Card

### Steps
1. Navigate to `/kudikah` page
2. Scroll to "Add a new payment method" form
3. Fill in card details:
   - Name: Jane Smith
   - Card: 5425233010103442 (Mastercard test)
   - Expiry: 06/26
   - CVV: 456
4. Check "Set as default payment method"
5. Click "Save Card"

### Expected Result
- ✅ Success toast notification appears
- ✅ Form resets
- ✅ Card display updates automatically
- ✅ Shows new card details:
  - Card Number: **** **** **** 3442
  - Card Holder: Jane Smith
  - Expiry: 06/26

---

## 🧪 Test Scenario 3: No Saved Cards

### Steps
1. Delete all saved cards (if any exist)
2. Navigate to `/kudikah` page
3. Wait for page to load

### Expected Result
- ✅ Card display shows placeholder:
  - Card Number: **** **** **** ****
  - Card Holder: User Name
  - Expiry: MM/YY

---

## 🧪 Test Scenario 4: Multiple Cards with Default

### Steps
1. Save 2-3 different cards
2. Set one as default
3. Navigate to `/kudikah` page
4. Refresh page

### Expected Result
- ✅ Default card is displayed
- ✅ Not the first card, but the one marked as default
- ✅ Card details match the default card

---

## 🧪 Test Scenario 5: Change Default Card

### Steps
1. Have 2+ saved cards
2. Set a different card as default
3. Refresh page

### Expected Result
- ✅ New default card is displayed
- ✅ Card details update correctly

---

## 🔍 Browser Console Check

Open browser console (F12) and look for:

✅ **Good signs:**
```
GET /api/wallet/payment-methods 200 OK
Card details displayed successfully
```

❌ **Bad signs:**
```
Error loading payment methods
Cannot read properties of undefined
```

---

## 📊 Test Checklist

- [ ] Page loads and displays card
- [ ] Card number is masked
- [ ] Card holder name displays
- [ ] Expiry date displays
- [ ] Save new card updates display
- [ ] Default card is shown
- [ ] Placeholder shows when no cards
- [ ] Multiple cards work correctly
- [ ] Refresh page keeps card display
- [ ] No console errors

---

## 🎯 Success Criteria

✅ Card displays on page load
✅ Card details are correct
✅ Card number is masked
✅ Display updates after saving
✅ Default card is prioritized
✅ Placeholder shows when needed
✅ No errors in console

---

**Status**: ✅ READY FOR TESTING
**Date**: December 15, 2025

