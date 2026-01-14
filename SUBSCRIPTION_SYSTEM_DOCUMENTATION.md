# Subscription System Documentation

## Overview
A complete subscription management system for the Kokokah.com platform, allowing admins to create and manage subscription plans, and users to subscribe to plans.

## Features

### Admin Features
- ✅ Create subscription plans
- ✅ Edit subscription plans
- ✅ Delete subscription plans
- ✅ View subscription statistics (total plans, active plans)
- ✅ Manage plan features and pricing
- ✅ Set plan duration (daily, weekly, monthly, yearly)
- ✅ Activate/deactivate plans

### User Features
- ✅ Browse available subscription plans
- ✅ Subscribe to plans with payment tracking
- ✅ View active subscriptions
- ✅ Pause subscriptions
- ✅ Resume paused subscriptions
- ✅ Cancel subscriptions
- ✅ Track subscription expiration dates

## Database Models

### SubscriptionPlan
```php
- id: integer (primary key)
- title: string
- description: text (nullable)
- price: decimal
- duration: integer
- duration_type: enum (daily, weekly, monthly, yearly)
- features: json (array of features)
- is_active: boolean
- max_users: integer (nullable)
- created_at: timestamp
- updated_at: timestamp
```

### UserSubscription
```php
- id: integer (primary key)
- user_id: integer (foreign key)
- subscription_plan_id: integer (foreign key)
- started_at: timestamp
- expires_at: timestamp
- status: enum (active, paused, cancelled, expired)
- amount_paid: decimal
- payment_reference: string (nullable)
- created_at: timestamp
- updated_at: timestamp
```

## API Endpoints

### Public Endpoints
- `GET /api/subscriptions/plans` - Get all subscription plans
- `GET /api/subscriptions/plans/{id}` - Get specific subscription plan

### Authenticated User Endpoints
- `GET /api/subscriptions/my-subscriptions` - Get user's subscriptions
- `POST /api/subscriptions/subscribe` - Subscribe to a plan
- `POST /api/subscriptions/{id}/cancel` - Cancel subscription
- `POST /api/subscriptions/{id}/pause` - Pause subscription
- `POST /api/subscriptions/{id}/resume` - Resume subscription

### Admin Endpoints (role:admin,superadmin)
- `POST /api/subscriptions/plans` - Create subscription plan
- `PUT /api/subscriptions/plans/{id}` - Update subscription plan
- `DELETE /api/subscriptions/plans/{id}` - Delete subscription plan

## Frontend Pages

### Admin Pages
- `/subscription` - Admin subscription management dashboard
  - View all plans
  - Create new plans
  - Edit existing plans
  - Delete plans
  - View statistics

### User Pages
- `/subscriptions/plans` - Browse and subscribe to plans
- `/subscriptions/my-subscriptions` - View and manage active subscriptions

## Controllers

### SubscriptionController
Handles subscription plan CRUD operations for admins.

**Methods:**
- `index()` - Get all subscription plans
- `show($id)` - Get specific subscription plan
- `store(Request $request)` - Create new subscription plan
- `update(Request $request, $id)` - Update subscription plan
- `destroy($id)` - Delete subscription plan

### UserSubscriptionController
Handles user subscription operations.

**Methods:**
- `getUserSubscriptions()` - Get user's subscriptions
- `subscribe(Request $request)` - Subscribe to a plan
- `cancelSubscription($id)` - Cancel subscription
- `pauseSubscription($id)` - Pause subscription
- `resumeSubscription($id)` - Resume subscription

## Seeder

### SubscriptionPlanSeeder
Seeds initial subscription plans into the database.

**Run with:**
```bash
php artisan db:seed --class=SubscriptionPlanSeeder
```

## Testing

All functionality is covered by comprehensive tests:

```bash
php artisan test tests/Feature/SubscriptionTest.php
```

**Test Coverage:**
- ✅ Get all subscription plans
- ✅ Get specific subscription plan
- ✅ User can subscribe to plan
- ✅ User can get their subscriptions
- ✅ User can cancel subscription
- ✅ Admin can create subscription plan
- ✅ Admin can update subscription plan
- ✅ Admin can delete subscription plan

## Usage Examples

### Create a Subscription Plan (Admin)
```javascript
POST /api/subscriptions/plans
{
    "title": "Premium Plan",
    "description": "Full access to all courses",
    "price": 10000,
    "duration": 30,
    "duration_type": "monthly",
    "features": ["Unlimited access", "Priority support"],
    "is_active": true,
    "max_users": 500
}
```

### Subscribe to a Plan (User)
```javascript
POST /api/subscriptions/subscribe
{
    "subscription_plan_id": 1,
    "amount_paid": 10000,
    "payment_reference": "TXN123456"
}
```

### Get User Subscriptions
```javascript
GET /api/subscriptions/my-subscriptions
```

## Security

- All admin endpoints require `role:admin,superadmin` middleware
- All user endpoints require `auth:sanctum` middleware
- CSRF protection on all POST/PUT/DELETE requests
- Input validation on all endpoints
- Proper error handling and logging

## Future Enhancements

- Payment gateway integration (Paystack, Flutterwave)
- Subscription renewal reminders
- Refund management
- Subscription analytics
- Bulk subscription management
- Subscription templates
- Custom pricing tiers

