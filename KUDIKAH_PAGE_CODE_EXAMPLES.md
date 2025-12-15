# Kudikah Page - Code Examples

## WalletApiClient Usage

### Get Wallet Balance
```javascript
import WalletApiClient from './walletApiClient.js';

const result = await WalletApiClient.getWallet();
if (result.success) {
    console.log('Balance:', result.data.balance);
    console.log('Card:', result.data.card_number);
}
```

### Get Transaction History
```javascript
const result = await WalletApiClient.getTransactions({
    type: 'deposit',
    status: 'completed',
    per_page: 50
});

if (result.success) {
    result.data.forEach(transaction => {
        console.log(transaction.description, transaction.amount);
    });
}
```

### Get Rewards
```javascript
const result = await WalletApiClient.getRewards();
if (result.success) {
    console.log('Total Earned:', result.data.total_earned);
    console.log('Login Streak:', result.data.login_streak);
}
```

### Claim Daily Reward
```javascript
const result = await WalletApiClient.claimLoginReward();
if (result.success) {
    console.log('Reward Claimed!', result.data.reward);
    console.log('New Balance:', result.data.new_balance);
}
```

### Purchase Course
```javascript
const result = await WalletApiClient.purchaseCourse([1, 2, 3]);
if (result.success) {
    console.log('Courses Purchased!');
    console.log('New Balance:', result.data.new_balance);
}
```

### Check Affordability
```javascript
const result = await WalletApiClient.checkAffordability(5);
if (result.success) {
    console.log('Can Afford:', result.data.can_afford);
    console.log('Balance:', result.data.balance);
    console.log('Course Price:', result.data.course_price);
}
```

---

## Page Functions

### Load Wallet Data
```javascript
async function loadWalletData() {
    const result = await WalletApiClient.getWallet();
    if (result.success) {
        document.getElementById('walletBalance').textContent = 
            formatNGN(result.data.balance);
    }
}
```

### Load Transactions
```javascript
async function loadTransactions() {
    const result = await WalletApiClient.getTransactions({
        type: currentTypeFilter,
        status: currentStatusFilter
    });
    if (result.success) {
        displayTransactions(result.data);
    }
}
```

### Filter Transactions
```javascript
window.filterTransactions = async function(type, status) {
    currentTypeFilter = type;
    currentStatusFilter = status;
    await loadTransactions();
};
```

### Show Toast Notification
```javascript
function showToast(message, type = 'success') {
    const toast = document.getElementById('toastNotification');
    toast.textContent = message;
    toast.className = `toast-notification ${type}`;
    toast.style.display = 'block';
    
    setTimeout(() => {
        toast.style.display = 'none';
    }, 3000);
}
```

---

## API Response Examples

### Wallet Response
```json
{
    "success": true,
    "data": {
        "balance": 5000.00,
        "card_number": "444 221 224 ****",
        "card_holder_name": "John Doe",
        "card_expiry": "03/30",
        "stats": {
            "total_deposits": 10000,
            "total_spending": 5000,
            "total_rewards": 500
        }
    }
}
```

### Transactions Response
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "type": "deposit",
            "amount": 1000,
            "description": "Deposit via Paystack",
            "status": "completed",
            "created_at": "2025-12-14T10:30:00Z"
        }
    ]
}
```

---

## Error Handling

```javascript
try {
    const result = await WalletApiClient.getWallet();
    if (!result.success) {
        showToast(result.message, 'error');
        return;
    }
    // Process data
} catch (error) {
    console.error('Error:', error);
    showToast('Error: ' + error.message, 'error');
}
```

