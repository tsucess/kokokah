# 🔧 API CONSUMPTION - TECHNICAL DEEP DIVE

**Kokokah.com LMS - Detailed Implementation Analysis**

---

## 📂 FILE STRUCTURE

```
app/Services/
├── Gateways/
│   ├── PaymentGatewayInterface.php    (Interface)
│   ├── PaystackGateway.php            (Paystack implementation)
│   ├── FlutterwaveGateway.php         (Flutterwave implementation)
│   ├── StripeGateway.php              (Stripe implementation)
│   └── PaypalGateway.php              (PayPal implementation)
├── NotificationService.php             (Email, SMS, Push)
├── VideoStreamingService.php           (Video handling)
├── RealtimeService.php                 (WebSocket/Broadcasting)
├── CacheService.php                    (API response caching)
└── PaymentGatewayService.php           (Gateway orchestration)

resources/js/
├── bootstrap.js                        (Axios setup)
└── app.js                              (Main app entry)

config/
├── services.php                        (Service credentials)
└── kokokah.php                         (App configuration)
```

---

## 🔌 PAYMENT GATEWAY INTERFACE

**File:** `app/Services/Gateways/PaymentGatewayInterface.php`

```php
interface PaymentGatewayInterface
{
    public function initializePayment(Payment $payment): array;
    public function verifyPayment(string $reference): array;
    public function handleWebhook(array $payload): array;
    public function refundPayment(string $reference, float $amount): array;
}
```

**Benefits:**
- Consistent API across all gateways
- Easy to add new payment providers
- Polymorphic payment handling

---

## 💳 PAYSTACK IMPLEMENTATION DETAILS

### Request Flow
```
1. User initiates payment
   ↓
2. PaystackGateway::initializePayment()
   ├─ POST /transaction/initialize
   ├─ Headers: Authorization: Bearer {SECRET_KEY}
   └─ Body: email, amount (in kobo), currency, metadata
   ↓
3. Response: authorization_url, access_code
   ↓
4. User redirected to Paystack checkout
   ↓
5. Webhook callback received
   ↓
6. PaystackGateway::verifyPayment()
   ├─ GET /transaction/verify/{reference}
   └─ Verify payment status
```

### Amount Conversion
```php
// NGN to Kobo (Paystack uses kobo internally)
$amount_in_kobo = $payment->amount * 100;

// Verification response
$amount_in_naira = $data['data']['amount'] / 100;
```

### Metadata Structure
```php
'metadata' => [
    'payment_id' => $payment->id,
    'user_id' => $payment->user_id,
    'type' => $payment->type,
    'course_id' => $payment->course_id,
    'custom_fields' => [
        [
            'display_name' => 'Payment Type',
            'variable_name' => 'payment_type',
            'value' => $payment->getTypeDescription()
        ]
    ]
]
```

---

## 🌊 FLUTTERWAVE IMPLEMENTATION DETAILS

### Request Flow
```
1. initializePayment()
   ├─ POST /payments
   ├─ Headers: Authorization: Bearer {SECRET_KEY}
   └─ Body: tx_ref, amount, currency, customer, customizations
   ↓
2. Response: link (redirect URL)
   ↓
3. User completes payment on Flutterwave
   ↓
4. Webhook: charge.completed event
   ↓
5. verifyPayment()
   ├─ GET /transactions/{tx_ref}/verify
   └─ Confirm payment status
```

### Webhook Verification
```php
$signature = request()->header('verif-hash');
$secretHash = config('services.flutterwave.webhook_secret');

if ($signature !== $secretHash) {
    throw new Exception('Invalid webhook signature');
}
```

### Customer Structure
```php
'customer' => [
    'email' => $payment->user->email,
    'name' => $payment->user->full_name,
]
```

---

## 💳 STRIPE IMPLEMENTATION DETAILS

### Authentication Method
```php
Http::withBasicAuth($this->secretKey, '')
    ->asForm()
    ->post($this->baseUrl . '/checkout/sessions', [...])
```

### Amount Conversion
```php
// USD to Cents (Stripe uses cents)
$amount_in_cents = $payment->amount * 100;

// Verification response
$amount_in_dollars = $data['amount_total'] / 100;
```

### Session Creation
```php
'line_items[0][price_data][currency]' => strtolower($payment->currency),
'line_items[0][price_data][unit_amount]' => $payment->amount * 100,
'mode' => 'payment',
'success_url' => route('payment.success', ['gateway' => 'stripe']),
'cancel_url' => route('payment.cancel', ['gateway' => 'stripe']),
```

---

## 🅿️ PAYPAL IMPLEMENTATION DETAILS

### OAuth 2.0 Token Flow
```php
// Get access token
$response = Http::asForm()->post($this->baseUrl . '/v1/oauth2/token', [
    'grant_type' => 'client_credentials',
    'client_id' => $this->clientId,
    'client_secret' => $this->clientSecret,
]);

$this->accessToken = $response->json()['access_token'];
```

### Order Creation
```php
'purchase_units' => [
    [
        'reference_id' => $payment->gateway_reference,
        'amount' => [
            'currency_code' => $payment->currency,
            'value' => number_format($payment->amount, 2, '.', '')
        ],
        'description' => $payment->getDescription(),
    ]
]
```

### Application Context
```php
'application_context' => [
    'return_url' => route('payment.success', ['gateway' => 'paypal']),
    'cancel_url' => route('payment.cancel', ['gateway' => 'paypal']),
    'brand_name' => config('app.name'),
    'landing_page' => 'BILLING',
    'user_action' => 'PAY_NOW'
]
```

---

## 📧 NOTIFICATION SERVICE ARCHITECTURE

### Multi-Channel Delivery
```php
public function sendToUser(User $user, string $type, string $title, 
                          string $message, array $data = [])
{
    // Create notification record
    $notification = Notification::createForUser(...);
    
    // Get user preferences
    $preferences = $user->notificationPreferences;
    
    // Send via enabled channels
    if ($preferences->isNotificationEnabled($type, 'email')) {
        $this->sendEmail($user, $notification);
    }
    
    if ($preferences->isNotificationEnabled($type, 'push')) {
        $this->sendPushNotification($user, $notification);
    }
    
    if ($preferences->isNotificationEnabled($type, 'sms')) {
        $this->sendSMS($user, $notification);
    }
    
    return $notification;
}
```

### Broadcast Methods
- `broadcastToRole()` - Send to users with specific role
- `broadcastToAll()` - Send to all users
- `sendCourseNotification()` - Send to course enrollees
- `sendTemplatedNotification()` - Use predefined templates

---

## 🎬 VIDEO STREAMING SERVICE

### Stream Creation
```php
public function createVideoStream($lessonId, $videoUrl)
{
    $lesson = Lesson::find($lessonId);
    
    $videoStream = VideoStream::create([
        'lesson_id' => $lessonId,
        'original_url' => $videoUrl,
        'status' => 'pending'
    ]);
    
    // Create quality variants
    foreach (VideoQuality::getStandardQualities() as $quality) {
        VideoQuality::createForStream($videoStream->id, $quality);
    }
    
    return $videoStream;
}
```

### Quality Variants
- 1080p (Full HD)
- 720p (HD)
- 480p (SD)
- 360p (Mobile)
- 240p (Low bandwidth)

---

## 🔄 CACHING STRATEGY

### API Response Caching
```php
public static function cacheApiResponse($endpoint, $params, $data)
{
    $key = "api:" . md5($endpoint . serialize($params));
    $ttl = config('cache.ttl.api_responses', 300); // 5 minutes
    
    return Cache::tags(['api'])->put($key, $data, $ttl);
}

public static function getCachedApiResponse($endpoint, $params)
{
    $key = "api:" . md5($endpoint . serialize($params));
    return Cache::tags(['api'])->get($key);
}
```

### Cache Invalidation
```php
public static function clearCourseCache()
{
    return self::clearByTags(['courses', 'search']);
}
```

---

## 🌐 FRONTEND HTTP CLIENTS

### Axios Configuration
```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: process.env.REACT_APP_API_URL,
  headers: {
    'Content-Type': 'application/json'
  }
});

// Request interceptor
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Response interceptor
api.interceptors.response.use(
  (response) => response.data,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);
```

---

## 🔐 ERROR HANDLING PATTERNS

### Backend Pattern
```php
try {
    $response = Http::withHeaders([...])->post($url, $data);
    
    if ($response->successful()) {
        return ['success' => true, 'data' => $response->json()];
    }
    
    throw new Exception('API call failed: ' . $response->body());
    
} catch (Exception $e) {
    Log::error('API Error: ' . $e->getMessage());
    
    return [
        'success' => false,
        'message' => 'Operation failed',
        'error' => $e->getMessage()
    ];
}
```

### Frontend Pattern
```javascript
try {
  const response = await fetch(url, options);
  
  if (response.status === 401) {
    localStorage.removeItem('token');
    window.location.href = '/login';
  }
  
  const data = await response.json();
  return data;
  
} catch (error) {
  console.error('API Error:', error);
  throw error;
}
```

---

## 📊 CONFIGURATION MANAGEMENT

### Environment Variables
```bash
# Payment Gateways
PAYSTACK_PUBLIC_KEY=pk_live_...
PAYSTACK_SECRET_KEY=sk_live_...
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK_...
FLUTTERWAVE_SECRET_KEY=FLWSECK_...
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
PAYPAL_CLIENT_ID=...
PAYPAL_CLIENT_SECRET=...

# AI Services
OPENAI_API_KEY=sk-...
GEMINI_API_KEY=...

# Notifications
TWILIO_SID=...
TWILIO_TOKEN=...
FCM_SERVER_KEY=...
```

---

## ✅ TESTING RECOMMENDATIONS

1. **Unit Tests:** Test each gateway independently
2. **Integration Tests:** Test payment flow end-to-end
3. **Webhook Tests:** Verify webhook handling
4. **Error Scenarios:** Test timeout, network errors
5. **Security Tests:** Verify signature validation

---

## 🚀 DEPLOYMENT CHECKLIST

- [ ] All API keys configured in production
- [ ] SSL/TLS certificates valid
- [ ] Webhook URLs updated for production
- [ ] Rate limiting configured
- [ ] Error logging enabled
- [ ] Monitoring alerts set up
- [ ] Backup payment gateway configured
- [ ] Database backups scheduled

