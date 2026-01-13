# Wallet Page - Code Structure & Architecture

## File: `resources/views/users/kudikah.blade.php`

### HTML Structure

#### Modals
1. **Add/Edit Card Modal** (`#addCard`)
   - Form: `#cardForm`
   - Fields: Card holder name, number, expiry, CVV
   - Eye toggles for sensitive fields
   - Submit button: `#cardSubmitBtn`

2. **Delete Card Modal** (`#deleteCard`)
   - Confirmation message
   - Cancel & Delete buttons
   - Delete handler: `#confirmDeleteCardBtn`

3. **Transfer Money Modal** (`#transferMoneyModal`)
   - Form: `#transferForm`
   - Fields: Recipient email, amount, description
   - Submit button: `#transferSubmitBtn`

4. **Amount Input Modal** (`#amountModal`)
   - Amount input: `#depositAmount`
   - Continue button triggers gateway selection

5. **Payment Gateway Modal** (`#paymentGatewayModal`)
   - 4 payment options (Paystack, Flutterwave, Stripe, PayPal)
   - Selection handler: `selectPaymentGateway()`

#### Main Content
- Balance display with toggle visibility
- Action buttons: Add Money, Transfer Money, Enroll Subject
- Card display with card details
- Card action buttons: Add, Edit, Delete
- Transaction history with filters

### JavaScript Structure

#### Initialization
```javascript
document.addEventListener('DOMContentLoaded', async () => {
  hidePageLoader();
  await loadWalletData();
  await loadTransactions();
  setupEventListeners();
});
```

#### Core Functions

**Data Loading**
- `loadWalletData()` - Fetch wallet balance & payment methods
- `loadAndDisplayPaymentMethods()` - Get saved cards
- `loadTransactions()` - Fetch transaction history
- `displayTransactions()` - Render transactions

**Card Management**
- `displayCardDetails(card)` - Show card on display
- `displayCardPlaceholder()` - Show empty state
- `populateCardFormModal(card)` - Pre-fill edit form
- `resetCardFormModal()` - Clear form for add mode
- `handleSaveCard(e)` - Save/update card via API
- `handleDeleteCard()` - Delete card via API

**Transfer**
- `handleTransferMoney(e)` - Process transfer via API

**Modals**
- `openAmountModal()` - Show amount input
- `closeAmountModal()` - Hide amount input
- `openPaymentGatewayModal()` - Show gateway selection
- `closePaymentGatewayModal()` - Hide gateway selection
- `proceedToGatewaySelection()` - Validate & proceed
- `proceedWithGateway()` - Initialize payment
- `selectPaymentGateway(gateway)` - Select payment method

**Utilities**
- `setupEventListeners()` - Attach all event handlers
- `togglePasswordVisibility()` - Toggle field visibility
- `formatCardNumber(e)` - Format card input
- `formatExpiryDate(e)` - Format expiry input
- `validateCardForm()` - Validate card fields
- `filterTransactions()` - Filter transaction list
- `formatNGN(n)` - Format currency
- `showToast()` - Show notification
- `showPageLoader()` - Show loading overlay
- `hidePageLoader()` - Hide loading overlay

### CSS Structure

#### Custom Styles
- `.addmoney-btn` - Primary button style
- `.enroll-btn` - Secondary button style
- `.input-border` - Form input container
- `.form-input` - Input field styling
- `.form-label` - Label positioning
- `.payment-method-modal` - Modal overlay
- `.payment-method-item` - Gateway option
- `.toast-notification` - Toast message
- `.page-loader` - Full page loader
- `.btn-eye` - Eye toggle button

#### Animations
- `fadeIn` - Modal fade in
- `slideUp` - Modal slide up
- `slideIn` - Toast slide in
- `slideOut` - Toast slide out
- `spin` - Loader spinner

### API Integration

#### WalletApiClient Methods
```javascript
WalletApiClient.getWallet()
WalletApiClient.getPaymentMethods()
WalletApiClient.getTransactions(filters)
WalletApiClient.addPaymentMethod(data)
WalletApiClient.deletePaymentMethod(id)
WalletApiClient.transferFunds(email, amount, desc)
```

#### PaymentApiClient Methods
```javascript
PaymentApiClient.initializeWalletDeposit(data)
```

### Event Listeners

Attached in `setupEventListeners()`:
- Add Money button → `openAmountModal()`
- Transfer Money button → Show transfer modal
- Enroll Subject button → Redirect to `/userclass`
- Edit Card button → `populateCardFormModal()`
- Delete Card button → Validation check
- Card form submit → `handleSaveCard()`
- Transfer form submit → `handleTransferMoney()`
- Eye toggles → `togglePasswordVisibility()`
- Delete confirm → `handleDeleteCard()`
- Modal close → `resetCardFormModal()`

### Global Variables

```javascript
let currentTypeFilter = 'all';
let currentStatusFilter = 'all';
let currentCard = null;
```

### Data Flow

1. **Page Load** → Load wallet data → Load transactions → Setup listeners
2. **Add Money** → Amount modal → Gateway selection → Payment redirect
3. **Transfer** → Transfer modal → Validate → API call → Reload data
4. **Add Card** → Card modal → Validate → API call → Reload data
5. **Edit Card** → Populate form → Validate → API call → Reload data
6. **Delete Card** → Confirmation → API call → Reset display → Reload data

### Error Handling

- Form validation before API calls
- Try-catch blocks around async operations
- Toast notifications for all errors
- Button state management during loading
- Graceful fallbacks for missing data

