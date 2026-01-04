# 🔧 Payment Gateway Troubleshooting Guide

## ❌ Error: "Invalid key" - Paystack

### **Problem**
```
Paystack initialization failed: {"status":false,"message":"Invalid key",...}
SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'gateway_reference' cannot be null
```

### **Root Cause**
The Paystack API key in your `.env` file is invalid or missing.

### **Solution**

#### **Step 1: Verify Your Paystack Keys**

1. Go to https://dashboard.paystack.com
2. Log in to your account
3. Navigate to **Settings → API Keys & Webhooks**
4. Copy your **Secret Key** (starts with `sk_live_` or `sk_test_`)
5. Copy your **Public Key** (starts with `pk_live_` or `pk_test_`)

#### **Step 2: Update .env File**

```env
# For Testing (Test Keys)
PAYSTACK_PUBLIC_KEY=pk_test_your_test_public_key_here
PAYSTACK_SECRET_KEY=sk_test_your_test_secret_key_here

# For Production (Live Keys)
PAYSTACK_PUBLIC_KEY=pk_live_your_live_public_key_here
PAYSTACK_SECRET_KEY=sk_live_your_live_secret_key_here
```

#### **Step 3: Clear Configuration Cache**

```bash
php artisan config:cache
php artisan cache:clear
```

#### **Step 4: Test Again**

1. Click "Add Money"
2. Enter amount: ₦1000
3. Select "Paystack"
4. Click "Continue"
5. Should redirect to Paystack checkout

---

## ❌ Error: "Invalid key" - Flutterwave

### **Problem**
```
Flutterwave initialization failed: {"status":0,"message":"Invalid key",...}
```

### **Solution**

#### **Step 1: Get Your Flutterwave Keys**

1. Go to https://dashboard.flutterwave.com
2. Log in to your account
3. Navigate to **Settings → API Keys**
4. Copy your **Secret Key**
5. Copy your **Public Key**

#### **Step 2: Update .env File**

```env
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK_TEST_your_public_key
FLUTTERWAVE_SECRET_KEY=FLWSECK_TEST_your_secret_key
FLUTTERWAVE_WEBHOOK_SECRET=your_webhook_secret
```

#### **Step 3: Clear Cache**

```bash
php artisan config:cache
php artisan cache:clear
```

---

## ❌ Error: "gateway_reference cannot be null"

### **Problem**
Payment record created but gateway initialization failed, leaving `gateway_reference` as NULL.

### **Root Cause**
The payment gateway API call failed (invalid key, network error, etc.)

### **Solution**

1. **Check API Keys** (see above)
2. **Check Network Connection** - Ensure server can reach payment gateway APIs
3. **Check Logs** - Review `storage/logs/laravel.log` for detailed error
4. **Test API Directly** - Use Postman to test gateway API

### **View Error Details**

Check the `gateway_response` column in the `payments` table:

```bash
# In database
SELECT id, gateway, status, gateway_response FROM payments WHERE id = 4;
```

---

## ✅ Verification Checklist

### **Before Testing**

- [ ] Paystack account created
- [ ] Paystack API keys copied
- [ ] `.env` file updated with correct keys
- [ ] `php artisan config:cache` executed
- [ ] `php artisan cache:clear` executed
- [ ] Server can reach `api.paystack.co`
- [ ] Server can reach `api.flutterwave.com`

### **During Testing**

- [ ] Use test API keys (not live)
- [ ] Use test card numbers provided
- [ ] Check browser console for errors
- [ ] Check network tab for API responses
- [ ] Check Laravel logs for detailed errors

### **Common Issues**

| Issue | Solution |
|-------|----------|
| "Invalid key" | Update `.env` with correct API keys |
| "gateway_reference is null" | Check API key validity |
| "Connection refused" | Check server firewall/network |
| "Timeout" | Check server internet connection |
| "CORS error" | Check API endpoint configuration |

---

## 🔍 Debugging Steps

### **Step 1: Check Environment Variables**

```bash
# SSH into server
php artisan tinker

# Check if keys are loaded
config('services.paystack.secret_key')
config('services.paystack.public_key')
config('services.flutterwave.secret_key')
```

### **Step 2: Check Laravel Logs**

```bash
tail -f storage/logs/laravel.log

# Look for lines containing:
# - "Paystack initialization error"
# - "Flutterwave initialization error"
# - "Payment initialization failed"
```

### **Step 3: Test API Directly**

Using Postman or curl:

```bash
# Test Paystack
curl -X POST https://api.paystack.co/transaction/initialize \
  -H "Authorization: Bearer sk_test_YOUR_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "amount": 100000,
    "currency": "NGN"
  }'
```

### **Step 4: Check Database**

```sql
-- View failed payment
SELECT * FROM payments WHERE id = 4;

-- Check gateway_response for error details
SELECT gateway_response FROM payments WHERE id = 4;
```

---

## 📞 Support Resources

### **Paystack**
- **Docs**: https://paystack.com/docs
- **API Reference**: https://paystack.com/docs/api
- **Support**: https://paystack.com/support

### **Flutterwave**
- **Docs**: https://developer.flutterwave.com
- **API Reference**: https://developer.flutterwave.com/reference
- **Support**: https://support.flutterwave.com

---

## 🎯 Next Steps

1. **Verify API Keys** - Ensure correct keys in `.env`
2. **Clear Cache** - Run `php artisan config:cache`
3. **Test Again** - Try payment flow again
4. **Check Logs** - Review `storage/logs/laravel.log`
5. **Contact Support** - If issue persists

---

**Once API keys are correct, payment gateway will work!** ✅

