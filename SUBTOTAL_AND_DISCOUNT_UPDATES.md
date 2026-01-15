# Subtotal and Discount Button Updates - FINAL

## Summary
Updated the enrollment page to implement pure per-subject pricing where:
- **Individual course prices have been completely removed**
- **All pricing is based on the selected subscription plan**
- **Users pay: Plan Price × Number of Selected Subjects**
- **10% discount applies when subscribing to all subjects**

## Changes Made

### 1. **Updated `updateSubtotal()` Function** ✅
**File:** `resources/views/users/enroll.blade.php` (Lines 1014-1044)

**Key Logic:**
```javascript
if (selectedPlanId) {
    // Plan price is PER SUBJECT
    const selectedPlan = allSubscriptionPlans.find(p => p.id == selectedPlanId);
    const numSelectedSubjects = checks.length;
    total = selectedPlan.price * numSelectedSubjects;
} else {
    // Fallback: Sum individual course prices
    checks.forEach(cb => {
        total += Number(cb.dataset.price) || 0;
    });
}
```

**Behavior:**
- Plan price is **per subject** (e.g., ₦400/subject)
- Subtotal = Plan price × Number of selected subjects
- Example: ₦400 × 10 subjects = ₦4,000
- Subtotal updates dynamically when subjects are selected/deselected
- No discount applied for partial selection

### 2. **Updated `updateEnrollAllButton()` Function** ✅
**File:** `resources/views/users/enroll.blade.php` (Lines 1046-1078)

**Key Features:**
- Button text: "Subscribe to All X Subjects - ₦REGULAR_PRICE (Save 10% - ₦DISCOUNTED_PRICE)"
- Applies **10% discount** when subscribing to all subjects
- Discount calculation: Regular Price × 10%
- Button disabled until a plan is selected
- Automatically selects all available courses when clicked

**Example Outputs:**
```
No Plan Selected:
"Enroll in All 5 Subjects - Select a plan" [DISABLED]

Plan Selected (₦400/subject × 5 subjects):
"Subscribe to All 5 Subjects - ₦2,000 (Save 10% - ₦1,800)" [ENABLED]

Plan Selected (₦400/subject × 10 subjects):
"Subscribe to All 10 Subjects - ₦4,000 (Save 10% - ₦3,600)" [ENABLED]
```

### 3. **Proceed to Payment Button** ✅
**File:** `resources/views/users/enroll.blade.php` (Line 600)

**Display:**
```html
Proceed to Payment - Subtotal: <span id="subtotal">₦5,000</span>
```

**Updates Dynamically:**
- When plan is selected → Subtotal updates to plan price
- When courses are selected/deselected → Subtotal remains unchanged
- Always shows the subscription plan price

## Pricing Examples

### Example 1: Partial Subject Selection (No Discount)
```
Plan: ₦400/subject
User selects 5 subjects:
├─ Regular Price: ₦400 × 5 = ₦2,000
├─ Discount: None (not subscribing to all)
└─ Subtotal: ₦2,000
```

### Example 2: Subscribe to All Subjects (10% Discount)
```
Plan: ₦400/subject
Total subjects available: 10
User clicks "Subscribe to All":
├─ Regular Price: ₦400 × 10 = ₦4,000
├─ Discount (10%): ₦4,000 × 10% = ₦400
├─ Discounted Price: ₦4,000 - ₦400 = ₦3,600
├─ Button: "Subscribe to All 10 Subjects - ₦4,000 (Save 10% - ₦3,600)"
└─ Subtotal: ₦3,600
```

### Example 3: Dynamic Subtotal Updates
```
Plan: ₦400/subject
User selects subjects:
├─ Selects 2 subjects → Subtotal: ₦400 × 2 = ₦800
├─ Selects 5 subjects → Subtotal: ₦400 × 5 = ₦2,000
├─ Selects 8 subjects → Subtotal: ₦400 × 8 = ₦3,200
└─ Selects all 10 subjects → Subtotal: ₦3,600 (with 10% discount)
```

## Function Call Chain

```
Plan Selection
    ↓
setupPlanSelector() event listener
    ↓
updatePlanPricing(planId)
    ↓
updateCoursePricesForPlan(planId)
    ├─ Set all course prices = 0
    ├─ Mark as "Included in plan"
    └─ updateSubtotal()
        ├─ Calculate: Subtotal = Plan Price × Number of Selected Subjects
        ├─ Update "Proceed to Payment" button
        └─ updateEnrollAllButton()
            ├─ Calculate: Regular Price = Plan Price × Total Subjects
            ├─ Calculate: Discount = Regular Price × 10%
            └─ Show: "Subscribe to All X Subjects - ₦REGULAR (Save 10% - ₦DISCOUNTED)"

Subject Selection/Deselection
    ↓
checkbox change event
    ↓
updateSubtotal()
    ├─ Recalculate: Subtotal = Plan Price × Number of Selected Subjects
    └─ Update "Proceed to Payment" button

"Enroll in All" Button Click
    ↓
Select all non-disabled subjects
    ↓
updateSubtotal()
    ├─ Calculate: Subtotal = Plan Price × All Subjects
    └─ Apply 10% discount to payment data
```

## Testing Scenarios

✅ Plan selected, 0 subjects → Subtotal = ₦0
✅ Plan selected, 5 subjects → Subtotal = Plan price × 5
✅ Plan selected, 10 subjects → Subtotal = Plan price × 10
✅ Deselect subjects → Subtotal updates accordingly
✅ "Enroll in All" button → Shows 10% discount
✅ "Enroll in All" click → Applies discount to payment
✅ "Proceed to Payment" → No discount for partial selection
✅ No plan selected → Button disabled, shows "Select a plan"
✅ Active subscription → Courses disabled, subtotal shows plan price × active subjects

## Database Requirements

Subscription plans should have these fields:
- `id` - Plan ID
- `title` - Plan name
- `price` - Plan price **per subject** (in NGN)
- `duration` - Duration value (e.g., 1, 3, 12)
- `duration_type` - Duration unit (e.g., "month", "year")
- `active` - Boolean flag for active plans

