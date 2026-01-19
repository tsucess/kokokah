# Points to Wallet Conversion System - Visual Diagrams

## 1. System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    KOKOKAH PLATFORM                         │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌──────────────┐      ┌──────────────┐                     │
│  │   QUIZ       │      │   TOPICS     │                     │
│  │  SYSTEM      │      │  SYSTEM      │                     │
│  └──────┬───────┘      └──────┬───────┘                     │
│         │                     │                              │
│         └─────────┬───────────┘                             │
│                   │                                          │
│                   ▼                                          │
│         ┌─────────────────┐                                 │
│         │  POINTS EARNED  │                                 │
│         │  (User.points)  │                                 │
│         └────────┬────────┘                                 │
│                  │                                          │
│                  │ 10:1 Conversion                          │
│                  ▼                                          │
│         ┌─────────────────┐                                 │
│         │ WALLET BALANCE  │                                 │
│         │(Wallet.balance) │                                 │
│         └────────┬────────┘                                 │
│                  │                                          │
│         ┌────────┴────────┐                                 │
│         │                 │                                 │
│         ▼                 ▼                                 │
│    ┌─────────┐      ┌──────────┐                           │
│    │ PURCHASE│      │ TRANSFER │                           │
│    │ COURSES │      │ TO USERS │                           │
│    └─────────┘      └──────────┘                           │
│                                                               │
└─────────────────────────────────────────────────────────────┘
```

## 2. Conversion Flow

```
START
  │
  ▼
┌─────────────────────────┐
│ User Requests Conversion│
│ (X points to wallet)    │
└────────┬────────────────┘
         │
         ▼
    ┌─────────────┐
    │ Validate:   │
    │ X >= 10?    │
    │ X % 10==0?  │
    └──┬──────┬───┘
       │      │
      YES    NO
       │      │
       │      ▼
       │   ┌──────────┐
       │   │  ERROR   │
       │   │ RESPONSE │
       │   └──────────┘
       │
       ▼
┌──────────────────────┐
│ Calculate:           │
│ wallet = X / 10      │
└────────┬─────────────┘
         │
         ▼
┌──────────────────────┐
│ Deduct X points      │
│ from user.points     │
└────────┬─────────────┘
         │
         ▼
┌──────────────────────┐
│ Add wallet amount    │
│ to wallet.balance    │
└────────┬─────────────┘
         │
         ▼
┌──────────────────────┐
│ Log conversion in    │
│ user_points_history  │
└────────┬─────────────┘
         │
         ▼
┌──────────────────────┐
│ Return SUCCESS       │
│ with new balances    │
└────────┬─────────────┘
         │
         ▼
       END
```

## 3. Data Model Relationships

```
┌──────────────┐
│    USER      │
├──────────────┤
│ id           │
│ points ◄─────┼─── Points Balance
│ ...          │
└──────┬───────┘
       │
       │ 1:1
       │
       ▼
┌──────────────────┐
│    WALLET        │
├──────────────────┤
│ id               │
│ user_id (FK)     │
│ balance          │
│ currency         │
└──────┬───────────┘
       │
       │ 1:N
       │
       ▼
┌──────────────────────────┐
│ WALLET_TRANSACTIONS      │
├──────────────────────────┤
│ id                       │
│ wallet_id (FK)           │
│ amount                   │
│ type (credit/debit)      │
│ reference                │
│ description              │
└──────────────────────────┘

┌──────────────┐
│    USER      │
└──────┬───────┘
       │
       │ 1:N
       │
       ▼
┌──────────────────────────┐
│ USER_POINTS_HISTORY      │
├──────────────────────────┤
│ id                       │
│ user_id (FK)             │
│ points_change            │
│ reason                   │
│ action_type              │
│ created_at               │
└──────────────────────────┘
```

## 4. Conversion Ratio Visualization

```
POINTS SIDE          WALLET SIDE
─────────────────────────────────

10 points    ═════════════════════    ₦1.00
20 points    ═════════════════════    ₦2.00
50 points    ═════════════════════    ₦5.00
100 points   ═════════════════════    ₦10.00
500 points   ═════════════════════    ₦50.00
1000 points  ═════════════════════    ₦100.00

Formula: wallet_amount = points / 10
```

## 5. User Journey Timeline

```
Day 1: Quiz Completion
  └─ Earn 10 points (quiz pass bonus)
  └─ Total: 10 points

Day 3: Topic Completion
  └─ Earn 5 points
  └─ Total: 15 points

Day 5: Another Quiz
  └─ Earn 10 points
  └─ Total: 25 points

Day 7: Course Completion
  └─ Earn 50 points
  └─ Total: 75 points

Day 10: Conversion Request
  └─ Convert 70 points (multiple of 10)
  └─ Receive ₦7.00 in wallet
  └─ Remaining: 5 points

Day 12: Purchase Course
  └─ Spend ₦8.00 from wallet
  └─ Wallet balance: -₦1.00 (need to add more)

Day 15: Add Money
  └─ Deposit ₦10.00 via payment
  └─ Wallet balance: ₦9.00
```

## 6. Validation Decision Tree

```
                    Conversion Request
                           │
                           ▼
                    ┌──────────────┐
                    │ Points >= 10?│
                    └──┬───────┬───┘
                      YES     NO
                       │       │
                       │       ▼
                       │    ❌ FAIL
                       │    "Min 10 points"
                       │
                       ▼
                    ┌──────────────┐
                    │ Points % 10  │
                    │    == 0?     │
                    └──┬───────┬───┘
                      YES     NO
                       │       │
                       │       ▼
                       │    ❌ FAIL
                       │    "Multiple of 10"
                       │
                       ▼
                    ┌──────────────┐
                    │ Wallet       │
                    │ exists?      │
                    └──┬───────┬───┘
                      YES     NO
                       │       │
                       │       ▼
                       │    Create Wallet
                       │
                       ▼
                    ✅ SUCCESS
                    Process Conversion
```

## 7. Transaction Types

```
WALLET TRANSACTIONS
───────────────────

Credit Types:
  ├─ deposit      (User adds money)
  ├─ reward       (System reward)
  ├─ transfer_in  (Received from user)
  └─ conversion   (Points converted)

Debit Types:
  ├─ purchase     (Course purchase)
  ├─ transfer_out (Sent to user)
  ├─ withdrawal   (Withdraw to bank)
  └─ refund       (Refund issued)
```

## 8. Error Handling Flow

```
Conversion Request
       │
       ▼
   Validate
       │
   ┌───┴───┐
   │       │
  PASS    FAIL
   │       │
   │       ▼
   │   ┌─────────────────┐
   │   │ Determine Error │
   │   └────┬────┬────┬──┘
   │        │    │    │
   │        ▼    ▼    ▼
   │    Insufficient  Invalid  Wallet
   │    Points        Amount   Error
   │        │           │       │
   │        ▼           ▼       ▼
   │    422 Error   422 Error  500 Error
   │
   ▼
Process
   │
   ▼
Success Response
```

---

**These diagrams help visualize the points-to-wallet conversion system architecture and flow.**

