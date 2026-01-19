# Points to Wallet Conversion System - 10:1 Ratio

## Overview
Users can convert their earned points to Kokokah wallet balance using a **10:1 conversion ratio**. This means:
- **10 points = 1 wallet unit (₦1.00)**
- Users must have at least 10 points to perform a conversion

## Conversion Ratio Details

| Points | Wallet Amount |
|--------|---------------|
| 10     | ₦1.00         |
| 100    | ₦10.00        |
| 500    | ₦50.00        |
| 1000   | ₦100.00       |

## How Points Are Earned

### Quiz Completion
- **Passing a quiz**: +10 points (bonus)
- **Individual questions**: Points vary per question (defined when quiz is created)
- **Example**: If a quiz has 5 questions worth 20 points each (100 total), passing gives 10 bonus points

### Topic/Lesson Completion
- **Per topic completion**: +5 points

### Course Completion
- **Per course completion**: +50 points

## Current System Architecture

### Key Models
1. **User Model** (`app/Models/User.php`)
   - `points` field - stores user's current points
   - `addPoints($amount)` - add points
   - `deductPoints($amount)` - deduct points
   - `hasEnoughPoints($amount)` - check if user has sufficient points

2. **Wallet Model** (`app/Models/Wallet.php`)
   - `balance` field - stores wallet balance
   - `deposit()` - add funds to wallet
   - `withdraw()` - remove funds from wallet
   - `purchaseCourse()` - spend wallet balance on courses

3. **UserPointsHistory Model** (`app/Models/UserPointsHistory.php`)
   - Tracks all point changes
   - Records reason, action type, and metadata

4. **WalletTransaction Model** (`app/Models/WalletTransaction.php`)
   - Tracks all wallet transactions
   - Records type (credit/debit), amount, reference, status

### Services
- **PointsAndBadgesService** - Manages point awards and badge checks
- **WalletService** - Manages wallet operations (deposits, purchases, transfers)

## Conversion Implementation Status

**Current Status**: ❌ **NOT YET IMPLEMENTED**

The conversion functionality needs to be added to:
1. Create a new method in `PointsAndBadgesService` or `WalletService`
2. Add API endpoint in `WalletController`
3. Create database migration to track conversion history
4. Add frontend UI for conversion feature

## Proposed Implementation

### Backend Method (WalletService)
```php
public function convertPointsToWallet(User $user, int $points)
{
    const CONVERSION_RATIO = 10; // 10 points = 1 wallet unit
    
    if (!$user->hasEnoughPoints($points)) {
        return ['success' => false, 'message' => 'Insufficient points'];
    }
    
    if ($points % CONVERSION_RATIO !== 0) {
        return ['success' => false, 'message' => 'Points must be multiple of 10'];
    }
    
    $walletAmount = $points / CONVERSION_RATIO;
    
    // Deduct points
    $user->deductPoints($points);
    
    // Add to wallet
    $wallet = $user->getOrCreateWallet();
    $wallet->deposit($walletAmount, 'PTS-' . uniqid(), 
                     "Points conversion: {$points} points");
    
    // Log conversion
    UserPointsHistory::create([
        'user_id' => $user->id,
        'points_change' => -$points,
        'reason' => 'Converted to wallet',
        'action_type' => 'conversion'
    ]);
    
    return ['success' => true, 'wallet_amount' => $walletAmount];
}
```

## Usage Scenarios

### Scenario 1: User Converts 100 Points
- User has: 150 points
- Converts: 100 points
- Receives: ₦10.00 in wallet
- Remaining: 50 points

### Scenario 2: User Converts All Points
- User has: 500 points
- Converts: 500 points
- Receives: ₦50.00 in wallet
- Remaining: 0 points

## Wallet Usage After Conversion

Once converted to wallet balance, users can:
1. **Purchase Courses** - Use wallet balance to enroll in courses
2. **Transfer Funds** - Send wallet balance to other users
3. **Withdraw** - Withdraw funds to bank account (if enabled)

## Related Files

- `app/Services/PointsAndBadgesService.php` - Point management
- `app/Services/WalletService.php` - Wallet operations
- `app/Models/User.php` - User points field
- `app/Models/Wallet.php` - Wallet balance field
- `app/Models/UserPointsHistory.php` - Point history tracking
- `app/Http/Controllers/WalletController.php` - Wallet API endpoints

