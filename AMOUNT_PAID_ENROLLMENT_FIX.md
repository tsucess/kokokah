# Amount Paid Column Fix - Enrollments Table

## 🎯 Issue
The `amount_paid` column in the enrollments table was always showing `0.00` even though users were paying for courses.

## 🔍 Root Cause
1. The `amount_paid` column existed in the database (added in migration `2025_10_24_000001_add_missing_columns_to_tables.php`)
2. However, the `Enrollment` model was NOT including `amount_paid` in the `$fillable` array
3. When enrollments were created, the `amount_paid` was never being set

## ✅ Solution Implemented

### 1. Updated `Enrollment` Model
**File**: `app/Models/Enrollment.php`

**Changes**:
- Added `'amount_paid'` to the `$fillable` array
- Added `'amount_paid' => 'decimal:2'` to the `$casts` array

**Before**:
```php
protected $fillable = [
    'user_id',
    'course_id',
    'progress',
    'status',
    'enrolled_at',
    'completed_at'
];
```

**After**:
```php
protected $fillable = [
    'user_id',
    'course_id',
    'progress',
    'status',
    'enrolled_at',
    'completed_at',
    'amount_paid'
];
```

### 2. Updated `PaymentGatewayService.php`
**File**: `app/Services/PaymentGatewayService.php` (Line 155-162)

**Change**: Added `'amount_paid' => $payment->amount` when creating enrollment

**Before**:
```php
$enrollment = $payment->user->enrollments()->create([
    'course_id' => $payment->course_id,
    'status' => 'active',
    'enrolled_at' => now()
]);
```

**After**:
```php
$enrollment = $payment->user->enrollments()->create([
    'course_id' => $payment->course_id,
    'status' => 'active',
    'enrolled_at' => now(),
    'amount_paid' => $payment->amount
]);
```

### 3. Updated `Wallet.php`
**File**: `app/Models/Wallet.php` (Line 131-139)

**Change**: Added `'amount_paid' => $amount` when creating enrollment via wallet

**Before**:
```php
$this->user->enrollments()->create([
    'course_id' => $course->id,
    'status' => 'active',
    'enrolled_at' => now()
]);
```

**After**:
```php
$this->user->enrollments()->create([
    'course_id' => $course->id,
    'status' => 'active',
    'enrolled_at' => now(),
    'amount_paid' => $amount
]);
```

## 📊 Impact

Now when users enroll in courses:
- ✅ **External Payment Gateways** (Paystack, Stripe, PayPal, Flutterwave): `amount_paid` = payment amount
- ✅ **Wallet Purchases** (Kudikah): `amount_paid` = deducted amount
- ✅ **Free Courses**: `amount_paid` = 0.00

## 🧪 Testing

To verify the fix:
1. Enroll a user in a paid course via payment gateway
2. Check the enrollments table - `amount_paid` should show the payment amount
3. Enroll a user via wallet - `amount_paid` should show the deducted amount
4. Enroll in a free course - `amount_paid` should be 0.00

## ✨ Status: COMPLETE

All three locations where enrollments are created now properly set the `amount_paid` column!

