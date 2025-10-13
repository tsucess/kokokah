# Payment Gateway Integration Guide

## Overview

Kokokah.com LMS supports multiple payment gateways for both wallet deposits and direct course purchases:

- **Paystack** (Recommended for Nigeria)
- **Flutterwave** (Africa-wide support)
- **Stripe** (International markets)
- **PayPal** (Global support)

## API Endpoints

### Get Available Gateways
```http
GET /api/payments/gateways
```

**Response:**
```json
{
  "success": true,
  "data": {
    "paystack": {
      "name": "Paystack",
      "currencies": ["NGN", "USD", "GHS", "ZAR"],
      "logo": "/images/gateways/paystack.png"
    },
    "flutterwave": {
      "name": "Flutterwave", 
      "currencies": ["NGN", "USD", "GHS", "KES", "UGX"],
      "logo": "/images/gateways/flutterwave.png"
    }
  }
}
```

### Initialize Wallet Deposit
```http
POST /api/payments/deposit
Authorization: Bearer {token}
```

**Request:**
```json
{
  "amount": 1000,
  "gateway": "paystack",
  "currency": "NGN"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Payment initialized successfully",
  "data": {
    "payment_id": 1,
    "gateway_data": {
      "authorization_url": "https://checkout.paystack.com/...",
      "reference": "PAY_123456789"
    }
  }
}
```

### Initialize Course Payment
```http
POST /api/payments/purchase-course
Authorization: Bearer {token}
```

**Request:**
```json
{
  "course_id": 1,
  "gateway": "paystack",
  "coupon_code": "STUDENT10",
  "currency": "NGN"
}
```

## Frontend Integration Examples

### React/Next.js Integration

#### Paystack Integration
```javascript
// Install: npm install react-paystack

import { PaystackButton } from 'react-paystack';

const PaystackPayment = ({ amount, email, onSuccess, onClose }) => {
  const config = {
    reference: new Date().getTime().toString(),
    email: email,
    amount: amount * 100, // Convert to kobo
    publicKey: process.env.NEXT_PUBLIC_PAYSTACK_PUBLIC_KEY,
  };

  return (
    <PaystackButton
      {...config}
      text="Pay with Paystack"
      onSuccess={onSuccess}
      onClose={onClose}
      className="btn btn-primary"
    />
  );
};
```

#### Flutterwave Integration
```javascript
// Install: npm install flutterwave-react-v3

import { FlutterWaveButton, closePaymentModal } from 'flutterwave-react-v3';

const FlutterwavePayment = ({ amount, email, onSuccess }) => {
  const config = {
    public_key: process.env.NEXT_PUBLIC_FLUTTERWAVE_PUBLIC_KEY,
    tx_ref: Date.now(),
    amount: amount,
    currency: 'NGN',
    payment_options: 'card,mobilemoney,ussd',
    customer: {
      email: email,
    },
    customizations: {
      title: 'Kokokah Payment',
      description: 'Payment for course',
    },
  };

  return (
    <FlutterWaveButton
      {...config}
      text="Pay with Flutterwave"
      callback={(response) => {
        onSuccess(response);
        closePaymentModal();
      }}
      onClose={() => {}}
    />
  );
};
```

#### Stripe Integration
```javascript
// Install: npm install @stripe/stripe-js

import { loadStripe } from '@stripe/stripe-js';

const stripePromise = loadStripe(process.env.NEXT_PUBLIC_STRIPE_PUBLIC_KEY);

const StripePayment = async ({ sessionId }) => {
  const stripe = await stripePromise;
  
  const { error } = await stripe.redirectToCheckout({
    sessionId: sessionId,
  });

  if (error) {
    console.error('Stripe error:', error);
  }
};
```

#### PayPal Integration
```javascript
// Install: npm install @paypal/react-paypal-js

import { PayPalScriptProvider, PayPalButtons } from "@paypal/react-paypal-js";

const PayPalPayment = ({ amount, onSuccess }) => {
  const initialOptions = {
    "client-id": process.env.NEXT_PUBLIC_PAYPAL_CLIENT_ID,
    currency: "USD",
    intent: "capture",
  };

  return (
    <PayPalScriptProvider options={initialOptions}>
      <PayPalButtons
        createOrder={(data, actions) => {
          return actions.order.create({
            purchase_units: [
              {
                amount: {
                  value: amount.toString(),
                },
              },
            ],
          });
        }}
        onApprove={(data, actions) => {
          return actions.order.capture().then((details) => {
            onSuccess(details);
          });
        }}
      />
    </PayPalScriptProvider>
  );
};
```

### Complete Payment Flow Component

```javascript
import { useState } from 'react';
import axios from 'axios';

const PaymentModal = ({ type, courseId, amount, onSuccess, onClose }) => {
  const [selectedGateway, setSelectedGateway] = useState('paystack');
  const [loading, setLoading] = useState(false);
  const [gateways, setGateways] = useState([]);

  useEffect(() => {
    fetchGateways();
  }, []);

  const fetchGateways = async () => {
    try {
      const response = await axios.get('/api/payments/gateways');
      setGateways(response.data.data);
    } catch (error) {
      console.error('Failed to fetch gateways:', error);
    }
  };

  const initializePayment = async () => {
    setLoading(true);
    try {
      const endpoint = type === 'wallet'
        ? '/api/payments/deposit'
        : '/api/payments/purchase-course';
      
      const payload = type === 'wallet'
        ? { amount, gateway: selectedGateway }
        : { course_id: courseId, gateway: selectedGateway };

      const response = await axios.post(endpoint, payload);
      const { authorization_url } = response.data.data.gateway_data;
      
      // Redirect to payment gateway
      window.location.href = authorization_url;
      
    } catch (error) {
      console.error('Payment initialization failed:', error);
      alert('Payment initialization failed. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="payment-modal">
      <h3>Choose Payment Method</h3>
      
      <div className="gateway-selection">
        {Object.entries(gateways).map(([key, gateway]) => (
          <div 
            key={key}
            className={`gateway-option ${selectedGateway === key ? 'selected' : ''}`}
            onClick={() => setSelectedGateway(key)}
          >
            <img src={gateway.logo} alt={gateway.name} />
            <span>{gateway.name}</span>
          </div>
        ))}
      </div>

      <div className="payment-summary">
        <p>Amount: ₦{amount.toLocaleString()}</p>
        <p>Gateway: {gateways[selectedGateway]?.name}</p>
      </div>

      <div className="modal-actions">
        <button onClick={onClose} disabled={loading}>
          Cancel
        </button>
        <button 
          onClick={initializePayment} 
          disabled={loading}
          className="btn-primary"
        >
          {loading ? 'Processing...' : 'Pay Now'}
        </button>
      </div>
    </div>
  );
};
```

## Webhook Handling

### Setting up Webhooks

1. **Paystack**: Add webhook URL in Paystack dashboard
   ```
   https://yourdomain.com/api/payments/webhook/paystack
   ```

2. **Flutterwave**: Configure webhook in Flutterwave dashboard
   ```
   https://yourdomain.com/api/payments/webhook/flutterwave
   ```

3. **Stripe**: Set up webhook endpoint in Stripe dashboard
   ```
   https://yourdomain.com/api/payments/webhook/stripe
   ```

4. **PayPal**: Configure webhook in PayPal developer dashboard
   ```
   https://yourdomain.com/api/payments/webhook/paypal
   ```

## Environment Variables

Add these to your `.env` file:

```env
# Payment Gateway Configuration
APP_CURRENCY=NGN
FRONTEND_URL=http://localhost:3000

# Paystack
PAYSTACK_PUBLIC_KEY=pk_test_your_key
PAYSTACK_SECRET_KEY=sk_test_your_key
PAYSTACK_WEBHOOK_SECRET=your_webhook_secret

# Flutterwave
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK_TEST-your_key
FLUTTERWAVE_SECRET_KEY=FLWSECK_TEST-your_key
FLUTTERWAVE_WEBHOOK_SECRET=your_webhook_secret

# Stripe
STRIPE_KEY=pk_test_your_key
STRIPE_SECRET=sk_test_your_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret

# PayPal
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=your_client_id
PAYPAL_CLIENT_SECRET=your_client_secret
PAYPAL_WEBHOOK_SECRET=your_webhook_secret
```

## Testing

### Test Cards

**Paystack Test Cards:**
- Success: 4084084084084081
- Insufficient Funds: 4084084084084081 (amount > 2500)

**Flutterwave Test Cards:**
- Success: 5531886652142950
- Insufficient Funds: 5531886652142950 (amount > 10000)

**Stripe Test Cards:**
- Success: 4242424242424242
- Declined: 4000000000000002

**PayPal:**
- Use PayPal sandbox accounts for testing

## Error Handling

```javascript
const handlePaymentError = (error) => {
  if (error.response?.status === 422) {
    // Validation errors
    const errors = error.response.data.errors;
    Object.keys(errors).forEach(field => {
      console.error(`${field}: ${errors[field].join(', ')}`);
    });
  } else if (error.response?.status === 400) {
    // Business logic errors
    alert(error.response.data.message);
  } else {
    // Network or server errors
    alert('Payment failed. Please try again.');
  }
};
```

## Security Best Practices

1. **Never expose secret keys** in frontend code
2. **Validate webhook signatures** on the backend
3. **Use HTTPS** for all payment-related requests
4. **Implement rate limiting** on payment endpoints
5. **Log all payment activities** for audit trails
6. **Verify payments** on the backend before fulfilling orders

## Support

For payment integration support:
- Check the gateway's official documentation
- Test thoroughly in sandbox mode before going live
- Monitor webhook delivery and payment status
- Implement proper error handling and user feedback
