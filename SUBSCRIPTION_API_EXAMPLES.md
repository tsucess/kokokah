# Subscription API - Request & Response Examples

## 1. Get All Subscription Plans

### Request
```bash
GET /api/subscriptions/plans
```

### Response (200 OK)
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "title": "Daily Plan",
        "description": "Access to class notes for 24 hours",
        "price": "300.00",
        "duration": 1,
        "duration_type": "daily",
        "features": ["24-hour access", "All subjects"],
        "is_active": true,
        "max_users": null,
        "created_at": "2026-01-13T10:00:00Z",
        "updated_at": "2026-01-13T10:00:00Z"
      },
      {
        "id": 2,
        "title": "Monthly Plan",
        "description": "Full access for one month",
        "price": "5000.00",
        "duration": 30,
        "duration_type": "monthly",
        "features": ["Unlimited access", "Priority support", "Offline downloads"],
        "is_active": true,
        "max_users": 500,
        "created_at": "2026-01-13T10:00:00Z",
        "updated_at": "2026-01-13T10:00:00Z"
      }
    ]
  }
}
```

## 2. Get Specific Subscription Plan

### Request
```bash
GET /api/subscriptions/plans/1
```

### Response (200 OK)
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Daily Plan",
    "description": "Access to class notes for 24 hours",
    "price": "300.00",
    "duration": 1,
    "duration_type": "daily",
    "features": ["24-hour access", "All subjects"],
    "is_active": true,
    "max_users": null,
    "created_at": "2026-01-13T10:00:00Z",
    "updated_at": "2026-01-13T10:00:00Z"
  },
  "message": "Subscription plan retrieved successfully"
}
```

## 3. Create Subscription Plan (Admin)

### Request
```bash
POST /api/subscriptions/plans
Content-Type: application/json
Authorization: Bearer {token}
X-CSRF-TOKEN: {csrf_token}

{
  "title": "Premium Plan",
  "description": "Full access to all courses",
  "price": 10000,
  "duration": 365,
  "duration_type": "yearly",
  "features": ["Unlimited access", "Priority support", "Offline downloads", "Certificate"],
  "is_active": true,
  "max_users": 1000
}
```

### Response (201 Created)
```json
{
  "success": true,
  "data": {
    "id": 3,
    "title": "Premium Plan",
    "description": "Full access to all courses",
    "price": "10000.00",
    "duration": 365,
    "duration_type": "yearly",
    "features": ["Unlimited access", "Priority support", "Offline downloads", "Certificate"],
    "is_active": true,
    "max_users": 1000,
    "created_at": "2026-01-13T10:30:00Z",
    "updated_at": "2026-01-13T10:30:00Z"
  },
  "message": "Subscription plan created successfully"
}
```

## 4. Update Subscription Plan (Admin)

### Request
```bash
PUT /api/subscriptions/plans/1
Content-Type: application/json
Authorization: Bearer {token}
X-CSRF-TOKEN: {csrf_token}

{
  "title": "Daily Plan Updated",
  "price": 500,
  "is_active": true
}
```

### Response (200 OK)
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Daily Plan Updated",
    "description": "Access to class notes for 24 hours",
    "price": "500.00",
    "duration": 1,
    "duration_type": "daily",
    "features": ["24-hour access", "All subjects"],
    "is_active": true,
    "max_users": null,
    "created_at": "2026-01-13T10:00:00Z",
    "updated_at": "2026-01-13T10:35:00Z"
  },
  "message": "Subscription plan updated successfully"
}
```

## 5. Delete Subscription Plan (Admin)

### Request
```bash
DELETE /api/subscriptions/plans/1
Authorization: Bearer {token}
X-CSRF-TOKEN: {csrf_token}
```

### Response (200 OK)
```json
{
  "success": true,
  "message": "Subscription plan deleted successfully"
}
```

## 6. Subscribe to Plan (User)

### Request
```bash
POST /api/subscriptions/subscribe
Content-Type: application/json
Authorization: Bearer {token}
X-CSRF-TOKEN: {csrf_token}

{
  "subscription_plan_id": 2,
  "amount_paid": 5000,
  "payment_reference": "TXN20260113001"
}
```

### Response (201 Created)
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 5,
    "subscription_plan_id": 2,
    "started_at": "2026-01-13T10:40:00Z",
    "expires_at": "2026-02-12T10:40:00Z",
    "status": "active",
    "amount_paid": "5000.00",
    "payment_reference": "TXN20260113001",
    "created_at": "2026-01-13T10:40:00Z",
    "updated_at": "2026-01-13T10:40:00Z",
    "subscription_plan": {
      "id": 2,
      "title": "Monthly Plan",
      "description": "Full access for one month",
      "price": "5000.00",
      "duration": 30,
      "duration_type": "monthly",
      "features": ["Unlimited access", "Priority support", "Offline downloads"],
      "is_active": true,
      "max_users": 500
    }
  },
  "message": "Successfully subscribed to plan"
}
```

## 7. Get User Subscriptions

### Request
```bash
GET /api/subscriptions/my-subscriptions
Authorization: Bearer {token}
```

### Response (200 OK)
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "user_id": 5,
        "subscription_plan_id": 2,
        "started_at": "2026-01-13T10:40:00Z",
        "expires_at": "2026-02-12T10:40:00Z",
        "status": "active",
        "amount_paid": "5000.00",
        "payment_reference": "TXN20260113001",
        "created_at": "2026-01-13T10:40:00Z",
        "updated_at": "2026-01-13T10:40:00Z",
        "subscription_plan": {
          "id": 2,
          "title": "Monthly Plan",
          "description": "Full access for one month",
          "price": "5000.00",
          "duration": 30,
          "duration_type": "monthly",
          "features": ["Unlimited access", "Priority support", "Offline downloads"],
          "is_active": true,
          "max_users": 500
        }
      }
    ]
  }
}
```

## 8. Cancel Subscription

### Request
```bash
POST /api/subscriptions/1/cancel
Authorization: Bearer {token}
X-CSRF-TOKEN: {csrf_token}
```

### Response (200 OK)
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 5,
    "subscription_plan_id": 2,
    "started_at": "2026-01-13T10:40:00Z",
    "expires_at": "2026-02-12T10:40:00Z",
    "status": "cancelled",
    "amount_paid": "5000.00",
    "payment_reference": "TXN20260113001",
    "created_at": "2026-01-13T10:40:00Z",
    "updated_at": "2026-01-13T10:45:00Z"
  },
  "message": "Subscription cancelled successfully"
}
```

## 9. Pause Subscription

### Request
```bash
POST /api/subscriptions/1/pause
Authorization: Bearer {token}
X-CSRF-TOKEN: {csrf_token}
```

### Response (200 OK)
```json
{
  "success": true,
  "data": {
    "id": 1,
    "status": "paused",
    ...
  },
  "message": "Subscription paused successfully"
}
```

## 10. Resume Subscription

### Request
```bash
POST /api/subscriptions/1/resume
Authorization: Bearer {token}
X-CSRF-TOKEN: {csrf_token}
```

### Response (200 OK)
```json
{
  "success": true,
  "data": {
    "id": 1,
    "status": "active",
    ...
  },
  "message": "Subscription resumed successfully"
}
```

## Error Responses

### 422 Validation Error
```json
{
  "success": false,
  "errors": {
    "title": ["The title field is required."],
    "price": ["The price must be a number."]
  }
}
```

### 401 Unauthorized
```json
{
  "message": "Unauthorized"
}
```

### 403 Forbidden
```json
{
  "message": "Forbidden. Required role: admin or superadmin"
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Subscription plan not found"
}
```

