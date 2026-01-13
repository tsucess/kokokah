# Wallet Page - Quick Reference Guide

## Button IDs & Actions

| Button ID | Feature | Modal | Handler |
|-----------|---------|-------|---------|
| `addMoneyBtn` | Add Money | `amountModal` → `paymentGatewayModal` | `openAmountModal()` |
| `transferMoneyBtn` | Transfer Money | `transferMoneyModal` | `handleTransferMoney()` |
| `enrollSubjectBtn` | Enroll Subject | - | Redirect to `/userclass` |
| `addCardBtn` | Add Card | `addCard` | Modal toggle |
| `editCardBtn` | Edit Card | `addCard` | `populateCardFormModal()` |
| `deleteCardBtn` | Delete Card | `deleteCard` | `handleDeleteCard()` |

## Form IDs & Fields

### Card Form (`cardForm`)
```
- modalCardHolderName (text)
- modalCardNumber (text, numeric)
- modalExpiryDate (text, MM/YY format)
- modalCvv (password, 3-4 digits)
- modalIsDefault (checkbox)
```

### Transfer Form (`transferForm`)
```
- recipientEmail (email)
- transferAmount (number, min 1)
- transferDescription (text, optional)
```

## Key Functions

### Event Setup
- `setupEventListeners()` - Initialize all event listeners on page load

### Card Management
- `populateCardFormModal(card)` - Pre-populate form for editing
- `resetCardFormModal()` - Reset form to add mode
- `handleSaveCard(e)` - Save/update card
- `handleDeleteCard()` - Delete card with confirmation

### Transfer
- `handleTransferMoney(e)` - Process transfer

### Utilities
- `togglePasswordVisibility(inputId, buttonId)` - Toggle field visibility
- `formatCardNumber(e)` - Format card input
- `formatExpiryDate(e)` - Format expiry date
- `validateCardForm()` - Validate card inputs

### Modals
- `openAmountModal()` - Show amount input
- `closeAmountModal()` - Hide amount input
- `openPaymentGatewayModal()` - Show gateway selection
- `closePaymentGatewayModal()` - Hide gateway selection
- `proceedToGatewaySelection()` - Validate amount & proceed
- `proceedWithGateway()` - Process payment

## API Calls

```javascript
// Add/Update Card
WalletApiClient.addPaymentMethod(payload)

// Delete Card
WalletApiClient.deletePaymentMethod(methodId)

// Transfer Money
WalletApiClient.transferFunds(recipientEmail, amount, description)

// Load Wallet Data
WalletApiClient.getWallet()
WalletApiClient.getPaymentMethods()
WalletApiClient.getTransactions(filters)

// Payment
PaymentApiClient.initializeWalletDeposit(payload)
```

## Global Variables

- `currentCard` - Currently displayed card object
- `currentTypeFilter` - Transaction type filter
- `currentStatusFilter` - Transaction status filter

## CSS Classes

- `.btn-eye` - Eye toggle button styling
- `.payment-method-modal` - Modal overlay
- `.toast-notification` - Toast message styling
- `.page-loader` - Full page loader

## Important Notes

1. **Card Form is Unified**: Same form for add and edit operations
2. **Edit Mode Detection**: Check `cardSubmitBtn.dataset.cardId`
3. **Modal Management**: Uses Bootstrap 5 Modal API
4. **Auto-reload**: Wallet data reloads after card/transfer operations
5. **Validation**: Client-side validation before API calls
6. **Error Handling**: All errors show toast notifications

## Testing Quick Commands

```javascript
// Open Add Money
document.getElementById('addMoneyBtn').click()

// Open Transfer
document.getElementById('transferMoneyBtn').click()

// Open Add Card
document.getElementById('addCardBtn').click()

// Open Edit Card (if card exists)
document.getElementById('editCardBtn').click()

// Open Delete Card
document.getElementById('deleteCardBtn').click()

// Check current card
console.log(currentCard)

// Show toast
showToast('Test message', 'success')
```

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Modal not opening | Check Bootstrap 5 is loaded |
| Form not submitting | Verify form ID and button type |
| API errors | Check browser console for details |
| Toast not showing | Verify `toastNotification` element exists |
| Card not displaying | Check `loadAndDisplayPaymentMethods()` |

