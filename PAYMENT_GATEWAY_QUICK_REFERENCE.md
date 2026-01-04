# 💳 Payment Gateway - Quick Reference

## Frontend Integration

### **JavaScript API**

```javascript
// Initialize wallet deposit
const result = await PaymentApiClient.initializeWalletDeposit({
    amount: 5000,
    gateway: 'paystack',  // or 'flutterwave'
    currency: 'NGN'
});

// Check result
if (result.success) {
    // Redirect to payment gateway
    window.location.href = result.data.gateway_data.authorization_url;
}
```

---

## Modal Functions

```javascript
// Amount Input Modal
openAmountModal()        // Show amount input
closeAmountModal()       // Hide amount input

// Gateway Selection Modal
openPaymentGatewayModal()   // Show gateway options
closePaymentGatewayModal()  // Hide gateway options

// Payment Flow
proceedToGatewaySelection() // Validate amount & proceed
selectPaymentGateway()      // Select gateway
proceedWithGateway()        // Initialize payment
```

---

## Configuration

### **Environment Variables**

```env
# Paystack
PAYSTACK_PUBLIC_KEY=pk_live_...
PAYSTACK_SECRET_KEY=sk_live_...

# Flutterwave
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK_...
FLUTTERWAVE_SECRET_KEY=FLWSECK_...
FLUTTERWAVE_WEBHOOK_SECRET=...
```

---

## Payment Flow Steps

1. **User clicks "Add Money"**
   - Amount modal opens

2. **User enters amount**
   - Minimum: ₦100
   - Validation: Client-side + Server-side

3. **User selects gateway**
   - Paystack, Flutterwave, Stripe, or PayPal
   - Visual feedback on selection

4. **Payment initialization**
   - API creates payment record
   - Gateway returns authorization URL
   - User redirected to gateway

5. **Payment completion**
   - User completes payment on gateway
   - Redirected back to app
   - Payment verified
   - Wallet updated

---

## Response Structure

### **Success Response**
```json
{
    "success": true,
    "data": {
        "payment_id": 123,
        "gateway_data": {
            "authorization_url": "https://...",
            "reference": "ref_abc123"
        }
    }
}
```

### **Error Response**
```json
{
    "success": false,
    "message": "Error description"
}
```

---

## Testing Gateways

### **Paystack Test Card**
- Number: 4084084084084081
- Expiry: Any future date
- CVV: Any 3 digits

### **Flutterwave Test Card**
- Number: 5531886652142950
- Expiry: 09/32
- CVV: 564

---

## Debugging

### **Console Logs**
```javascript
// Check payment initialization
console.log('Payment initialization response:', result);

// Check selected gateway
console.log('Selected gateway:', modal.dataset.selectedGateway);

// Check deposit amount
console.log('Deposit amount:', modal.dataset.depositAmount);
```

### **Network Requests**
- Check browser DevTools → Network tab
- Look for `/payments/deposit` request
- Verify response contains `authorization_url`

---

## Common Issues

| Issue | Solution |
|-------|----------|
| "Amount must be at least ₦100" | Enter amount ≥ ₦100 |
| "Please select a payment gateway" | Click on a gateway option |
| "Payment gateway URL not found" | Check API response in console |
| Redirect not working | Check browser console for errors |
| Payment not verified | Check webhook configuration |

---

## Files Modified

- `resources/views/users/kudikah.blade.php` - Frontend UI & JS
- `app/Services/PaymentGatewayService.php` - Payment logic
- `app/Services/Gateways/PaystackGateway.php` - Paystack integration
- `app/Services/Gateways/FlutterwaveGateway.php` - Flutterwave integration
- `app/Http/Controllers/PaymentController.php` - API endpoints

---

## Status

✅ Frontend UI complete
✅ API integration complete
✅ Paystack ready
✅ Flutterwave ready
✅ Error handling implemented
✅ Ready for testing

---

**For detailed documentation, see:**
- `PAYMENT_GATEWAY_IMPLEMENTATION.md`
- `PAYMENT_GATEWAY_API_GUIDE.md`

