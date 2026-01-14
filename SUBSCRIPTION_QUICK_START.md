# Subscription System - Quick Start Guide

## Installation & Setup

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Initial Data
```bash
php artisan db:seed --class=SubscriptionPlanSeeder
```

### 3. Run Tests
```bash
php artisan test tests/Feature/SubscriptionTest.php
```

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── SubscriptionController.php
│   │   └── UserSubscriptionController.php
│   └── Requests/
│       └── (validation requests if needed)
├── Models/
│   ├── SubscriptionPlan.php
│   └── UserSubscription.php

database/
├── migrations/
│   ├── create_subscription_plans_table.php
│   └── create_user_subscriptions_table.php
└── seeders/
    └── SubscriptionPlanSeeder.php

resources/views/
├── admin/
│   └── subscription.blade.php (Admin dashboard)
└── subscriptions/
    ├── plans.blade.php (Browse plans)
    └── my-subscriptions.blade.php (User subscriptions)

routes/
├── api.php (API endpoints)
└── web.php (Web routes)

tests/Feature/
└── SubscriptionTest.php (All tests)
```

## Key Files to Know

### Models
- **SubscriptionPlan** - Represents a subscription plan
- **UserSubscription** - Represents a user's subscription

### Controllers
- **SubscriptionController** - Admin plan management
- **UserSubscriptionController** - User subscription operations

### Views
- **admin/subscription.blade.php** - Admin dashboard with full CRUD
- **subscriptions/plans.blade.php** - User-facing plan browsing
- **subscriptions/my-subscriptions.blade.php** - User subscription management

## Common Tasks

### Add a New Subscription Plan (Admin)
1. Go to `/subscription`
2. Click "Add New Subscription"
3. Fill in the form
4. Click "Save"

### Subscribe to a Plan (User)
1. Go to `/subscriptions/plans`
2. Click "Subscribe Now" on desired plan
3. Enter payment amount
4. Click "Subscribe Now"

### View My Subscriptions (User)
1. Go to `/subscriptions/my-subscriptions`
2. View all active subscriptions
3. Pause, resume, or cancel as needed

## API Quick Reference

### Get All Plans
```bash
curl http://localhost:8000/api/subscriptions/plans
```

### Create Plan (Admin)
```bash
curl -X POST http://localhost:8000/api/subscriptions/plans \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TOKEN" \
  -d '{
    "title": "Premium",
    "price": 5000,
    "duration": 30,
    "duration_type": "monthly",
    "features": ["Feature 1", "Feature 2"]
  }'
```

### Subscribe to Plan (User)
```bash
curl -X POST http://localhost:8000/api/subscriptions/subscribe \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TOKEN" \
  -d '{
    "subscription_plan_id": 1,
    "amount_paid": 5000,
    "payment_reference": "TXN123"
  }'
```

### Get My Subscriptions (User)
```bash
curl http://localhost:8000/api/subscriptions/my-subscriptions \
  -H "Authorization: Bearer TOKEN"
```

## Troubleshooting

### Tests Failing
- Ensure database is migrated: `php artisan migrate`
- Clear cache: `php artisan cache:clear`
- Run tests with: `php artisan test tests/Feature/SubscriptionTest.php`

### Routes Not Found
- Check routes are registered in `routes/api.php` and `routes/web.php`
- Clear route cache: `php artisan route:clear`

### CSRF Token Errors
- Ensure `X-CSRF-TOKEN` header is included in POST/PUT/DELETE requests
- Token is available in `meta[name="csrf-token"]` tag

## Status Codes

- **200** - Success
- **201** - Created
- **400** - Bad Request
- **401** - Unauthorized
- **403** - Forbidden
- **404** - Not Found
- **422** - Validation Error
- **500** - Server Error

## Next Steps

1. Integrate payment gateway (Paystack, Flutterwave)
2. Add subscription renewal logic
3. Implement subscription analytics
4. Add email notifications
5. Create subscription reports

