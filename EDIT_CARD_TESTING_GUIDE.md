# Edit Card Details - Testing Guide

## ✅ Feature Complete

The Edit Card Details feature is now fully implemented and ready for testing.

---

## 🧪 Test Scenario 1: Edit Existing Card

### Steps
1. Navigate to `/kudikah` page
2. Verify a card is displayed
3. Click the "Edit" button
4. Wait for form to scroll into view

### Expected Result
- ✅ Form header changes to "Update Payment Method"
- ✅ Button text changes to "Update Card"
- ✅ Card holder name is pre-filled
- ✅ Expiry date is pre-filled
- ✅ Card number field is empty
- ✅ CVV field is empty
- ✅ Form scrolls smoothly into view

### Example
```
Before Edit:
- Header: "Add a new payment method"
- Button: "Save Card"
- Fields: Empty

After Edit:
- Header: "Update Payment Method"
- Button: "Update Card"
- Card Holder: "John Doe"
- Expiry: "12/25"
- Card Number: Empty
- CVV: Empty
```

---

## 🧪 Test Scenario 2: Update Card Holder Name

### Steps
1. Click Edit button
2. Change card holder name to "Jane Smith"
3. Click "Update Card"

### Expected Result
- ✅ Success toast: "Card updated successfully!"
- ✅ Form resets
- ✅ Card display updates to show "Jane Smith"
- ✅ Button text changes back to "Save Card"
- ✅ Header changes back to "Add a new payment method"

---

## 🧪 Test Scenario 3: Update Expiry Date

### Steps
1. Click Edit button
2. Change expiry date to "06/26"
3. Click "Update Card"

### Expected Result
- ✅ Success toast: "Card updated successfully!"
- ✅ Card display updates to show "06/26"
- ✅ Form resets

---

## 🧪 Test Scenario 4: Update Card Number

### Steps
1. Click Edit button
2. Enter new card number: "5425233010103442"
3. Enter new CVV: "456"
4. Click "Update Card"

### Expected Result
- ✅ Success toast: "Card updated successfully!"
- ✅ Card display updates with new card
- ✅ Form resets

---

## 🧪 Test Scenario 5: Update Multiple Fields

### Steps
1. Click Edit button
2. Change card holder name to "Alex Johnson"
3. Change expiry date to "08/27"
4. Enter new card number: "4532015112830366"
5. Enter new CVV: "789"
6. Click "Update Card"

### Expected Result
- ✅ All fields updated successfully
- ✅ Card display shows new card holder name
- ✅ Card display shows new expiry date
- ✅ Success message appears

---

## 🧪 Test Scenario 6: Set as Default

### Steps
1. Click Edit button
2. Check "Set as default payment method"
3. Click "Update Card"

### Expected Result
- ✅ Card is set as default
- ✅ Success message appears
- ✅ Card remains displayed on page load

---

## 🧪 Test Scenario 7: No Card to Edit

### Steps
1. Delete all saved cards
2. Click Edit button

### Expected Result
- ✅ Warning toast: "No card to edit. Please save a card first."
- ✅ Form does not populate

---

## 🧪 Test Scenario 8: Form Reset After Edit

### Steps
1. Click Edit button
2. Verify form is populated
3. Refresh page

### Expected Result
- ✅ Form resets to empty state
- ✅ Header shows "Add a new payment method"
- ✅ Button shows "Save Card"

---

## 🔍 Browser Console Check

Open browser console (F12) and look for:

✅ **Good signs:**
```
Form populated successfully
Card updated successfully
No errors in console
```

❌ **Bad signs:**
```
Cannot read properties of undefined
Error updating card
```

---

## 📊 Test Checklist

- [ ] Edit button populates form
- [ ] Form header changes to "Update"
- [ ] Button text changes to "Update Card"
- [ ] Card holder name pre-filled
- [ ] Expiry date pre-filled
- [ ] Card number cleared
- [ ] CVV cleared
- [ ] Form scrolls into view
- [ ] Can update card holder name
- [ ] Can update expiry date
- [ ] Can update card number
- [ ] Can update CVV
- [ ] Can set as default
- [ ] Success message appears
- [ ] Card display updates
- [ ] Form resets after update
- [ ] No card warning works

---

**Status**: ✅ READY FOR TESTING
**Date**: December 15, 2025

