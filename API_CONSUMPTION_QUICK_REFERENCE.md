# 🔍 API CONSUMPTION - QUICK REFERENCE GUIDE

**Kokokah.com LMS - At-a-Glance Reference**

---

## 🗂️ FILE LOCATIONS

```
Backend Services
├── app/Services/Gateways/
│   ├── PaymentGatewayInterface.php
│   ├── PaystackGateway.php
│   ├── FlutterwaveGateway.php
│   ├── StripeGateway.php
│   └── PaypalGateway.php
├── app/Services/NotificationService.php
├── app/Services/VideoStreamingService.php
├── app/Services/RealtimeService.php
└── app/Services/CacheService.php

Configuration
├── config/services.php
├── config/kokokah.php
└── .env.example

Frontend
├── resources/js/bootstrap.js
├── resources/js/app.js
└── FRONTEND_INTEGRATION_GUIDE.md

Routes
└── routes/api.php
```

---

## 💳 PAYMENT GATEWAYS AT A GLANCE

### Paystack
```
Base URL: https://api.paystack.co
Auth: Bearer Token
Amount: Kobo (multiply by 100)
Endpoints:
  POST /transaction/initialize
  GET /transaction/verify/{reference}
Config: PAYSTACK_SECRET_KEY, PAYSTACK_PUBLIC_KEY
```

### Flutterwave
```
Base URL: https://api.flutterwave.com/v3
Auth: Bearer Token
Amount: Native currency
Endpoints:
  POST /payments
  GET /transactions/{reference}/verify
Config: FLUTTERWAVE_SECRET_KEY, FLUTTERWAVE_PUBLIC_KEY
```

### Stripe
```
Base URL: https://api.stripe.com
Auth: Basic Auth (secret key)
Amount: Cents (multiply by 100)
Endpoints:
  POST /checkout/sessions
  GET /checkout/sessions/{session_id}
Config: STRIPE_SECRET, STRIPE_KEY
```

### PayPal
```
Base URL: https://api-m.sandbox.paypal.com (sandbox)
Auth: OAuth 2.0 Bearer Token
Amount: Native currency
Endpoints:
  POST /v2/checkout/orders
  GET /v2/checkout/orders/{order_id}
Config: PAYPAL_CLIENT_ID, PAYPAL_CLIENT_SECRET
```

---

## 🔑 ENVIRONMENT VARIABLES

```bash
# Payment Gateways
PAYSTACK_PUBLIC_KEY=pk_live_...
PAYSTACK_SECRET_KEY=sk_live_...
PAYSTACK_WEBHOOK_SECRET=...

FLUTTERWAVE_PUBLIC_KEY=FLWPUBK_...
FLUTTERWAVE_SECRET_KEY=FLWSECK_...
FLUTTERWAVE_WEBHOOK_SECRET=...

STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
STRIPE_WEBHOOK_SECRET=...

PAYPAL_CLIENT_ID=...
PAYPAL_CLIENT_SECRET=...
PAYPAL_WEBHOOK_ID=...
PAYPAL_MODE=sandbox|live

# Notifications
MAIL_MAILER=smtp|log
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=...
MAIL_PASSWORD=...

TWILIO_SID=...
TWILIO_TOKEN=...
TWILIO_FROM=+1234567890

FCM_SERVER_KEY=...
FCM_SENDER_ID=...

# AI Services
OPENAI_API_KEY=sk-...
OPENAI_MODEL=gpt-3.5-turbo

GEMINI_API_KEY=...
GEMINI_MODEL=gemini-pro
```

---

## 🔌 COMMON API PATTERNS

### Backend - Making HTTP Requests
```php
// Simple GET
$response = Http::get('https://api.example.com/endpoint');

// With headers
$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $token,
    'Content-Type' => 'application/json',
])->post('https://api.example.com/endpoint', $data);

// With timeout
$response = Http::timeout(30)->get($url);

// With retry
$response = Http::retry(3, 100)->post($url, $data);

// Check response
if ($response->successful()) {
    $data = $response->json();
}
```

### Frontend - Making HTTP Requests
```javascript
// Axios
const response = await axios.get('/api/endpoint', {
  headers: { 'Authorization': `Bearer ${token}` }
});

// Fetch API
const response = await fetch('/api/endpoint', {
  method: 'GET',
  headers: { 'Authorization': `Bearer ${token}` }
});

// With error handling
try {
  const response = await fetch(url);
  if (response.status === 401) {
    localStorage.removeItem('token');
    window.location.href = '/login';
  }
  return await response.json();
} catch (error) {
  console.error('API Error:', error);
}
```

---

## 🔐 AUTHENTICATION METHODS

### Bearer Token (Most Common)
```php
Http::withHeaders([
    'Authorization' => 'Bearer ' . $token
])->post($url, $data);
```

### Basic Auth
```php
Http::withBasicAuth($username, $password)->post($url, $data);
```

### API Key
```php
Http::withHeaders([
    'X-API-Key' => $apiKey
])->post($url, $data);
```

### OAuth 2.0
```php
$token = $this->getAccessToken();
Http::withHeaders([
    'Authorization' => 'Bearer ' . $token
])->post($url, $data);
```

---

## 📊 RESPONSE HANDLING

### Success Response
```php
if ($response->successful()) {
    $data = $response->json();
    // Process data
}
```

### Error Response
```php
if ($response->failed()) {
    $error = $response->json();
    Log::error('API Error', $error);
}
```

### Status Codes
```php
$response->status();        // 200, 404, 500, etc.
$response->successful();    // 2xx
$response->failed();        // 4xx, 5xx
$response->clientError();   // 4xx
$response->serverError();   // 5xx
```

---

## 🔄 WEBHOOK HANDLING

### Verify Signature
```php
$signature = request()->header('X-Signature');
$payload = request()->getContent();

$hash = hash_hmac('sha256', $payload, $secret);

if (!hash_equals($hash, $signature)) {
    return response('Invalid signature', 401);
}
```

### Process Webhook
```php
$event = $payload['event'];
$data = $payload['data'];

switch ($event) {
    case 'charge.completed':
        $this->handlePaymentSuccess($data);
        break;
    case 'charge.failed':
        $this->handlePaymentFailure($data);
        break;
}
```

---

## 🛠️ DEBUGGING TIPS

### Enable Logging
```php
// In .env
LOG_LEVEL=debug

// In code
Log::debug('API Request', ['url' => $url, 'data' => $data]);
Log::info('API Response', ['status' => $response->status()]);
Log::error('API Error', ['error' => $response->body()]);
```

### Check Response
```php
// Get raw response
$response->body();

// Get JSON
$response->json();

// Get headers
$response->headers();

// Get status
$response->status();
```

### Test with Postman
1. Import: `postman/Kokokah_LMS_API.postman_collection.json`
2. Import: `postman/Kokokah_LMS_Environment.postman_environment.json`
3. Set variables (token, base_url)
4. Run requests

---

## 📋 COMMON TASKS

### Initialize Payment
```php
$gateway = new PaystackGateway();
$result = $gateway->initializePayment($payment);
// Returns: authorization_url, access_code
```

### Verify Payment
```php
$gateway = new PaystackGateway();
$result = $gateway->verifyPayment($reference);
// Returns: status, amount, currency, transaction_date
```

### Send Notification
```php
$notificationService = new NotificationService();
$notificationService->sendToUser(
    $user,
    'payment_success',
    'Payment Successful',
    'Your payment has been processed'
);
```

### Get Cached Response
```php
$cached = CacheService::getCachedApiResponse('courses', ['page' => 1]);
if ($cached) {
    return $cached;
}
```

---

## ⚠️ ERROR CODES

### HTTP Status Codes
```
200 OK - Success
201 Created - Resource created
400 Bad Request - Invalid input
401 Unauthorized - Authentication failed
403 Forbidden - Permission denied
404 Not Found - Resource not found
429 Too Many Requests - Rate limited
500 Server Error - Server error
503 Service Unavailable - Service down
```

### Payment Gateway Errors
```
Paystack: Check response['data']['status']
Flutterwave: Check response['data']['status']
Stripe: Check response['payment_status']
PayPal: Check response['status']
```

---

## 🚀 PERFORMANCE TIPS

1. **Use Caching**
   ```php
   $data = Cache::remember('key', 3600, function () {
       return Http::get($url)->json();
   });
   ```

2. **Use Queues**
   ```php
   SendNotificationJob::dispatch($user, $notification);
   ```

3. **Use Async**
   ```php
   Http::async()->post($url, $data);
   ```

4. **Batch Requests**
   ```php
   $responses = Http::pool(fn (Factory $pool) => [
       $pool->get('url1'),
       $pool->get('url2'),
   ]);
   ```

---

## 🔗 USEFUL LINKS

- [Laravel HTTP Client](https://laravel.com/docs/http-client)
- [Paystack API Docs](https://paystack.com/docs/api/)
- [Flutterwave API Docs](https://developer.flutterwave.com/)
- [Stripe API Docs](https://stripe.com/docs/api)
- [PayPal API Docs](https://developer.paypal.com/)
- [Axios Documentation](https://axios-http.com/)
- [Fetch API MDN](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API)

---

## 📞 SUPPORT

For issues or questions:
1. Check the detailed documentation files
2. Review the implementation examples
3. Check logs: `storage/logs/`
4. Test with Postman collection
5. Review error messages carefully

---

## ✅ CHECKLIST

Before deploying:
- [ ] All API keys configured
- [ ] SSL certificates valid
- [ ] Webhook URLs updated
- [ ] Error logging enabled
- [ ] Rate limiting configured
- [ ] Timeouts set
- [ ] Monitoring alerts active
- [ ] Backup gateway ready
- [ ] Database backups scheduled
- [ ] Tests passing

---

**Last Updated:** October 28, 2025  
**Version:** 1.0  
**Status:** ✅ Complete

