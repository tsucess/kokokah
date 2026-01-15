# Per-Subject Pricing with 10% Discount Implementation

## Overview
Updated the enrollment page to implement pure per-subject pricing where users pay the plan price multiplied by the number of subjects they select. Individual course prices have been completely removed from the system. A 10% discount is automatically applied when subscribing to all available subjects.

## Key Changes

### 0. **Removed Individual Course Prices** ✅
**Changes:**
- Removed `data-price` attribute from course checkboxes
- Removed individual course price display logic
- Removed `basePrice` and `originalPrice` references
- Removed fallback pricing logic for non-subscription mode
- All pricing now exclusively based on selected subscription plan

**Impact:**
- Courses no longer have individual prices
- Each course is priced at: Plan Price × 1 subject
- No price variations between courses
- Simplified pricing model

### 1. **Subtotal Calculation** ✅
**Function:** `updateSubtotal()` (Lines 1002-1029)

**Logic:**
```javascript
Subtotal = Plan Price × Number of Selected Subjects
```

**Examples:**
- Plan: ₦400/subject, Select 5 subjects → Subtotal: ₦2,000
- Plan: ₦400/subject, Select 10 subjects → Subtotal: ₦4,000
- Plan: ₦400/subject, Select 0 subjects → Subtotal: ₦0

**Behavior:**
- Updates dynamically when subjects are selected/deselected
- No discount applied for partial selection
- Displays in "Proceed to Payment" button
- **No fallback logic** - all pricing based on selected plan only

### 2. **"Enroll in All" Button with 10% Discount** ✅
**Function:** `updateEnrollAllButton()` (Lines 1031-1063)

**Logic:**
```javascript
Regular Price = Plan Price × Total Subjects
Discount = Regular Price × 10%
Discounted Price = Regular Price - Discount
```

**Display Format:**
```
Subscribe to All X Subjects - ₦REGULAR_PRICE (Save 10% - ₦DISCOUNTED_PRICE)
```

**Examples:**
- 10 subjects × ₦400 = ₦4,000 (Save 10% - ₦3,600)
- 5 subjects × ₦400 = ₦2,000 (Save 10% - ₦1,800)

### 3. **Payment Data Structure** ✅
**"Enroll in All" Handler** (Lines 1120-1163)

**Includes:**
- `courses` - Array of selected course IDs
- `subtotal` - Discounted price (₦3,600)
- `planId` - Selected plan ID
- `planPrice` - Price per subject (₦400)
- `courseCount` - Number of subjects (10)
- `regularPrice` - Total before discount (₦4,000)
- `discountAmount` - 10% discount (₦400)
- `discountedPrice` - Final price (₦3,600)
- `hasDiscount` - true (indicates discount applied)
- `isSubscription` - true

### 4. **Proceed to Payment Handler** ✅
**Function Handler** (Lines 1079-1118)

**Behavior:**
- No discount for partial selection
- Subtotal = Plan Price × Selected Subjects
- Payment data includes course count and total price

## Pricing Examples

### Scenario 1: Partial Selection (No Discount)
```
Plan: ₦400/subject
User selects 5 subjects:
├─ Subtotal: ₦400 × 5 = ₦2,000
├─ Discount: None
└─ Payment: ₦2,000
```

### Scenario 2: Subscribe to All (10% Discount)
```
Plan: ₦400/subject
Total subjects: 10
User clicks "Subscribe to All":
├─ Regular Price: ₦400 × 10 = ₦4,000
├─ Discount (10%): ₦4,000 × 10% = ₦400
├─ Discounted Price: ₦4,000 - ₦400 = ₦3,600
└─ Payment: ₦3,600
```

### Scenario 3: Dynamic Updates
```
Plan: ₦400/subject
├─ Select 2 subjects → Subtotal: ₦800
├─ Select 5 subjects → Subtotal: ₦2,000
├─ Select 8 subjects → Subtotal: ₦3,200
└─ Click "Enroll in All" → Subtotal: ₦3,600 (with 10% discount)
```

## User Experience Flow

```
1. User selects plan (₦400/subject)
2. User selects subjects (e.g., 5)
   → Subtotal updates: ₦2,000
   → "Proceed to Payment" button shows: ₦2,000
   → "Enroll in All" button shows: ₦4,000 (Save 10% - ₦3,600)
3. User can either:
   a) Click "Proceed to Payment" → Pay ₦2,000 for 5 subjects
   b) Click "Enroll in All" → Pay ₦3,600 for all 10 subjects (10% discount)
```

## Testing Checklist

- [ ] Plan selected, 0 subjects → Subtotal: ₦0
- [ ] Plan selected, 5 subjects → Subtotal: ₦2,000
- [ ] Plan selected, 10 subjects → Subtotal: ₦4,000
- [ ] Deselect subjects → Subtotal updates correctly
- [ ] "Enroll in All" shows correct discount calculation
- [ ] "Enroll in All" click → Applies 10% discount
- [ ] "Proceed to Payment" → No discount for partial selection
- [ ] Payment data includes all required fields
- [ ] Active subscriptions → Courses disabled, pricing correct

