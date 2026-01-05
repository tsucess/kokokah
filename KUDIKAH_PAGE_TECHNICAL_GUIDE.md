# Kudikah Page - Technical Implementation Guide

## Architecture Overview

The Kudikah wallet page consumes 7 wallet endpoints to display:
1. Wallet balance and card information
2. Transaction history with filtering
3. Reward information
4. User actions (Add Money, Enroll, Edit Card)

---

## Key Functions

### 1. loadWalletData()
**Purpose:** Load wallet balance and card information
**Endpoint:** GET `/api/wallet`
**Updates:**
- Wallet balance display
- Card number (masked)
- Card holder name
- Card expiry date

### 2. loadTransactions()
**Purpose:** Load transaction history with filters
**Endpoint:** GET `/api/wallet/transactions`
**Filters:**
- `type`: transfer, deposit, purchase, reward
- `status`: completed, pending, failed
- `per_page`: 50

### 3. displayTransactions(transactions)
**Purpose:** Render transactions in UI
**Features:**
- Dynamic icon based on transaction type
- Formatted date and time
- Amount with color coding (green for credit, red for debit)
- Responsive layout

### 4. filterTransactions(type, status)
**Purpose:** Filter transactions by type and status
**Called by:** Dropdown menu items
**Updates:** Transaction list dynamically

### 5. setupEventListeners()
**Purpose:** Attach event handlers to buttons
**Buttons:**
- Add Money → `/payments/deposit`
- Enroll Class → `/userenroll?level_id=1`
- Edit Card → Coming soon
- Toggle Balance → Show/hide balance

---

## Data Flow

```
Page Load
  ↓
loadWalletData() → GET /api/wallet
  ↓
Update Balance, Card Info
  ↓
loadTransactions() → GET /api/wallet/transactions
  ↓
displayTransactions()
  ↓
setupEventListeners()
  ↓
Ready for User Interaction
```

---

## Error Handling

- Try-catch blocks for all API calls
- Toast notifications for errors
- Fallback values for missing data
- User-friendly error messages

---

## UI Components

**Toast Notification:**
- Success (green)
- Error (red)
- Warning (yellow)
- Auto-hide after 3 seconds

**Loading State:**
- Spinner animation
- "Loading..." message
- Disabled interactions

**Transaction Icons:**
- Transfer: ↔️
- Deposit: ↓
- Purchase: 🛍️
- Reward: 🎁
- Debit: ↑

---

## Testing Checklist

- [ ] Wallet balance loads correctly
- [ ] Card information displays
- [ ] Transactions load and display
- [ ] Filter by type works
- [ ] Filter by status works
- [ ] Add Money button redirects
- [ ] Enroll Class button redirects
- [ ] Toast notifications appear
- [ ] Error handling works
- [ ] Responsive on mobile

