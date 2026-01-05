# 🚀 Payment Gateway Setup Guide

## Step 1: Get Paystack API Keys

### **Create Paystack Account**
1. Go to https://paystack.com
2. Click "Sign Up"
3. Fill in your details
4. Verify your email

### **Get API Keys**
1. Log in to https://dashboard.paystack.com
2. Go to **Settings → API Keys & Webhooks**
3. You'll see two keys:
   - **Public Key** (starts with `pk_test_` or `pk_live_`)
   - **Secret Key** (starts with `sk_test_` or `sk_live_`)

### **Copy Your Keys**
```
Public Key:  pk_test_[your_public_key_here]
Secret Key:  sk_test_[your_secret_key_here]
```

⚠️ **IMPORTANT:** Never commit actual API keys to version control. Always use `.env` file.

---

## Step 2: Get Flutterwave API Keys

### **Create Flutterwave Account**
1. Go to https://flutterwave.com
2. Click "Sign Up"
3. Fill in your details
4. Verify your email

### **Get API Keys**
1. Log in to https://dashboard.flutterwave.com
2. Go to **Settings → API Keys**
3. You'll see:
   - **Public Key** (starts with `FLWPUBK_`)
   - **Secret Key** (starts with `FLWSECK_`)
   - **Webhook Secret** (for webhook verification)

### **Copy Your Keys**
```
Public Key:     FLWPUBK_TEST_[your_public_key_here]
Secret Key:     FLWSECK_TEST_[your_secret_key_here]
Webhook Secret: [your_webhook_secret_here]
```

⚠️ **IMPORTANT:** Never commit actual API keys to version control. Always use `.env` file.

---

## Step 3: Update .env File

### **Open .env File**
```bash
# In your project root
nano .env
# or
vim .env
```

### **Add Paystack Keys**
```env
PAYSTACK_PUBLIC_KEY=pk_test_[your_public_key_here]
PAYSTACK_SECRET_KEY=sk_test_[your_secret_key_here]
```

### **Add Flutterwave Keys**
```env
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK_TEST_[your_public_key_here]
FLUTTERWAVE_SECRET_KEY=FLWSECK_TEST_[your_secret_key_here]
FLUTTERWAVE_WEBHOOK_SECRET=[your_webhook_secret_here]
```

⚠️ **IMPORTANT:** The `.env` file is in `.gitignore` and should never be committed to version control.

### **Save File**
- If using `nano`: Press `Ctrl+X`, then `Y`, then `Enter`
- If using `vim`: Press `Esc`, then `:wq`, then `Enter`

---

## Step 4: Clear Configuration Cache

### **Run These Commands**
```bash
# Clear configuration cache
php artisan config:cache

# Clear all cache
php artisan cache:clear

# Optional: Clear view cache
php artisan view:clear
```

---

## Step 5: Configure Webhook Endpoints

### **Paystack Webhook**
1. Go to https://dashboard.paystack.com
2. Settings → API Keys & Webhooks
3. Scroll to "Webhooks"
4. Add webhook URL:
   ```
   https://yourdomain.com/webhooks/paystack
   ```
5. Select events: `charge.success`
6. Save

### **Flutterwave Webhook**
1. Go to https://dashboard.flutterwave.com
2. Settings → Webhooks
3. Add webhook URL:
   ```
   https://yourdomain.com/webhooks/flutterwave
   ```
4. Copy webhook secret
5. Add to `.env`: `FLUTTERWAVE_WEBHOOK_SECRET=...`
6. Save

---

## Step 6: Test Payment Flow

### **Using Test Cards**

**Paystack Test Card:**
```
Card Number: 4084084084084081
Expiry:      Any future date (e.g., 12/25)
CVV:         Any 3 digits (e.g., 123)
OTP:         123456 (when prompted)
```

**Flutterwave Test Card:**
```
Card Number: 5531886652142950
Expiry:      09/32
CVV:         564
PIN:         1234
OTP:         12345
```

### **Test Steps**
1. Go to your app's wallet page
2. Click "Add Money"
3. Enter amount: ₦1000
4. Select "Paystack"
5. Click "Continue"
6. Should redirect to Paystack checkout
7. Use test card above
8. Complete payment
9. Should return to app with success message

---

## Step 7: Verify Setup

### **Check Configuration**
```bash
# SSH into server
php artisan tinker

# Check if keys are loaded
config('services.paystack.secret_key')
config('services.paystack.public_key')
config('services.flutterwave.secret_key')
```

### **Check Database**
```bash
# View payment records
SELECT * FROM payments ORDER BY id DESC LIMIT 5;

# Check for errors
SELECT id, gateway, status, gateway_response FROM payments WHERE status = 'failed';
```

### **Check Logs**
```bash
# View recent logs
tail -f storage/logs/laravel.log

# Look for payment-related messages
grep -i "payment\|paystack\|flutterwave" storage/logs/laravel.log
```

---

## ✅ Verification Checklist

- [ ] Paystack account created
- [ ] Paystack API keys copied
- [ ] Flutterwave account created
- [ ] Flutterwave API keys copied
- [ ] `.env` file updated with all keys
- [ ] `php artisan config:cache` executed
- [ ] `php artisan cache:clear` executed
- [ ] Paystack webhook configured
- [ ] Flutterwave webhook configured
- [ ] Test payment successful
- [ ] Payment recorded in database
- [ ] Wallet balance updated

---

## 🔧 Troubleshooting

### **"Invalid key" Error**
- Check API keys are correct
- Ensure no extra spaces in `.env`
- Run `php artisan config:cache`
- Verify keys match dashboard

### **"Connection refused" Error**
- Check server internet connection
- Check firewall allows outbound HTTPS
- Verify API endpoints are accessible

### **Webhook Not Received**
- Verify webhook URL is correct
- Check webhook secret matches
- Ensure server is publicly accessible
- Check logs for webhook errors

---

## 🚀 Go Live

### **Switch to Live Keys**
1. Get live API keys from payment gateway
2. Update `.env` with live keys
3. Run `php artisan config:cache`
4. Test with small amount
5. Monitor transactions

### **Before Going Live**
- [ ] Test with live keys
- [ ] Verify webhook delivery
- [ ] Test error scenarios
- [ ] Monitor logs
- [ ] Have support contact ready

---

## 📞 Support

- **Paystack Support**: https://paystack.com/support
- **Flutterwave Support**: https://support.flutterwave.com
- **Documentation**: See `PAYMENT_GATEWAY_TROUBLESHOOTING.md`

---

**Setup complete! Ready to accept payments!** ✅

