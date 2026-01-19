# Points to Wallet Conversion - Quick Reference Card

## 🔢 The Ratio
```
10 POINTS = ₦1.00
```

## 📊 Conversion Table

| Points | Wallet | Points | Wallet |
|--------|--------|--------|--------|
| 10 | ₦1.00 | 600 | ₦60.00 |
| 20 | ₦2.00 | 700 | ₦70.00 |
| 30 | ₦3.00 | 800 | ₦80.00 |
| 40 | ₦4.00 | 900 | ₦90.00 |
| 50 | ₦5.00 | 1000 | ₦100.00 |
| 100 | ₦10.00 | 1500 | ₦150.00 |
| 200 | ₦20.00 | 2000 | ₦200.00 |
| 300 | ₦30.00 | 5000 | ₦500.00 |
| 400 | ₦40.00 | 10000 | ₦1000.00 |
| 500 | ₦50.00 | | |

## 🎯 Point Sources

| Activity | Points |
|----------|--------|
| Quiz Pass (Bonus) | +10 |
| Quiz Question (avg) | +20 |
| Topic Completion | +5 |
| Course Completion | +50 |

## ✅ Valid Conversions

```
✓ 10 points → ₦1.00
✓ 50 points → ₦5.00
✓ 100 points → ₦10.00
✓ 250 points → ₦25.00
✓ 1000 points → ₦100.00
```

## ❌ Invalid Conversions

```
✗ 5 points (less than 10)
✗ 15 points (not multiple of 10)
✗ 25 points (not multiple of 10)
✗ 35 points (not multiple of 10)
✗ 45 points (not multiple of 10)
```

## 🔄 Conversion Process

```
1. User has X points
2. User requests conversion
3. System validates:
   - X >= 10? ✓
   - X % 10 == 0? ✓
4. Calculate: wallet = X / 10
5. Deduct X points from user
6. Add wallet amount to wallet
7. Log conversion
8. Return success
```

## 📱 API Endpoint

```
POST /api/wallet/convert-points
Authorization: Bearer {token}

Request:
{
    "points": 100
}

Response:
{
    "success": true,
    "data": {
        "points_converted": 100,
        "wallet_amount": 10.00,
        "remaining_points": 50,
        "new_wallet_balance": 60.00
    }
}
```

## 🛡️ Validation Rules

| Rule | Check |
|------|-------|
| Minimum | points >= 10 |
| Multiple | points % 10 == 0 |
| Balance | points <= user.points |
| Wallet | wallet exists or auto-create |

## 💾 Database Tables

```
users
├── id
├── points (INT)
└── ...

wallets
├── id
├── user_id (FK)
├── balance (DECIMAL)
└── currency

wallet_transactions
├── id
├── wallet_id (FK)
├── amount
├── type (credit/debit)
└── ...

user_points_history
├── id
├── user_id (FK)
├── points_change
├── reason
└── ...
```

## 🎓 User Scenarios

### Scenario 1: Exact Conversion
- Has: 100 points
- Converts: 100 points
- Gets: ₦10.00
- Remaining: 0 points

### Scenario 2: Partial Conversion
- Has: 150 points
- Converts: 100 points
- Gets: ₦10.00
- Remaining: 50 points

### Scenario 3: Invalid Amount
- Has: 100 points
- Tries: 95 points
- Result: ❌ FAILED (not multiple of 10)

### Scenario 4: Insufficient Points
- Has: 5 points
- Tries: 10 points
- Result: ❌ FAILED (insufficient)

## 🚀 Implementation Checklist

- [ ] Add conversion method to WalletService
- [ ] Create API endpoint
- [ ] Add validation logic
- [ ] Create conversion history table
- [ ] Add frontend button
- [ ] Create conversion modal
- [ ] Add error handling
- [ ] Write unit tests
- [ ] Write integration tests
- [ ] Deploy to production

## 📞 Common Questions

**Q: Can I convert 15 points?**
A: No, must be multiple of 10

**Q: What's the minimum?**
A: 10 points minimum

**Q: Can I convert partial points?**
A: Only in multiples of 10

**Q: Is conversion reversible?**
A: No, conversion is one-way

**Q: Can I convert to wallet multiple times?**
A: Yes, unlimited conversions

## 🔗 Related Files

- `app/Services/WalletService.php`
- `app/Models/Wallet.php`
- `app/Models/User.php`
- `app/Http/Controllers/WalletController.php`
- `database/migrations/create_wallets_table.php`

---

**Print this card for quick reference!**

