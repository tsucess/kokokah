# Points to Wallet Conversion - Technical Reference

## Conversion Ratio: 10:1

**Formula**: `wallet_amount = points / 10`

## Database Schema

### User Table
```sql
ALTER TABLE users ADD COLUMN points INT DEFAULT 0;
```

### Wallet Table
```sql
CREATE TABLE wallets (
    id BIGINT PRIMARY KEY,
    user_id BIGINT UNIQUE,
    balance DECIMAL(10,2) DEFAULT 0,
    currency VARCHAR(3) DEFAULT 'NGN',
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Wallet Transactions Table
```sql
CREATE TABLE wallet_transactions (
    id BIGINT PRIMARY KEY,
    wallet_id BIGINT,
    amount DECIMAL(10,2),
    type ENUM('credit', 'debit', 'transfer_in', 'transfer_out'),
    reference VARCHAR(255),
    status VARCHAR(50),
    description TEXT,
    created_at TIMESTAMP
);
```

### User Points History Table
```sql
CREATE TABLE user_points_history (
    id BIGINT PRIMARY KEY,
    user_id BIGINT,
    points_change INT,
    points_before INT,
    points_after INT,
    reason VARCHAR(255),
    action_type VARCHAR(50),
    action_id BIGINT,
    action_model VARCHAR(255),
    metadata JSON,
    created_at TIMESTAMP
);
```

## API Endpoints (To Be Implemented)

### Convert Points to Wallet
```
POST /api/wallet/convert-points
Authorization: Bearer {token}
Content-Type: application/json

{
    "points": 100
}

Response:
{
    "success": true,
    "message": "Successfully converted 100 points to ₦10.00",
    "data": {
        "points_converted": 100,
        "wallet_amount": 10.00,
        "remaining_points": 50,
        "new_wallet_balance": 60.00,
        "conversion_id": "CONV-123456"
    }
}
```

### Get Conversion History
```
GET /api/wallet/conversion-history
Authorization: Bearer {token}

Response:
{
    "success": true,
    "data": [
        {
            "id": 1,
            "points": 100,
            "wallet_amount": 10.00,
            "converted_at": "2024-01-15T10:30:00Z"
        }
    ]
}
```

## Service Methods

### PointsAndBadgesService
```php
// Award points
awardPointsForQuizPass(User $user, QuizAttempt $attempt)
awardPointsForTopicCompletion(User $user, $topicId)
awardPointsForCourseCompletion(User $user, $courseId)
```

### WalletService (To Add)
```php
// Convert points to wallet
convertPointsToWallet(User $user, int $points): array
getConversionHistory(User $user): Collection
```

## Validation Rules

1. **Minimum Points**: User must have at least 10 points
2. **Multiple of 10**: Points must be divisible by 10
3. **Sufficient Balance**: User cannot convert more points than they have
4. **Wallet Exists**: User must have a wallet (auto-created if needed)

## Transaction Flow

1. User requests conversion of X points
2. Validate: X >= 10 and X % 10 == 0
3. Calculate: wallet_amount = X / 10
4. Deduct points from user
5. Add wallet_amount to wallet
6. Create wallet transaction (credit)
7. Log in user_points_history
8. Return success response

## Error Handling

| Error | Status | Message |
|-------|--------|---------|
| Insufficient points | 422 | "You need at least 10 points to convert" |
| Invalid amount | 422 | "Points must be a multiple of 10" |
| Wallet error | 500 | "Failed to process wallet conversion" |
| Database error | 500 | "Conversion failed. Please try again" |

## Security Considerations

1. **Authentication**: Require user authentication
2. **Authorization**: Only user can convert their own points
3. **Validation**: Server-side validation of all inputs
4. **Logging**: All conversions logged for audit trail
5. **Transactions**: Use database transactions for atomicity

## Performance Optimization

1. Cache user points in session
2. Use database transactions
3. Index user_id in points_history table
4. Batch conversions if needed
5. Monitor conversion frequency per user

## Testing Checklist

- [ ] User with 0 points cannot convert
- [ ] User with 5 points cannot convert
- [ ] User with 10 points can convert exactly 10
- [ ] User with 100 points receives ₦10.00
- [ ] Points deducted correctly
- [ ] Wallet balance increased correctly
- [ ] History logged correctly
- [ ] Concurrent conversions handled safely
- [ ] API returns correct response format

