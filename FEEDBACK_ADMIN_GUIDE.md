# User Feedback System - Admin Guide

## Overview

The feedback system allows administrators to view, manage, and respond to user feedback submitted through the `/userfeedback` page.

## Accessing Feedback

### Via API Endpoints

#### Get All Feedback
```bash
GET /api/feedback
Authorization: Bearer {admin_token}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "user_id": 5,
        "first_name": "John",
        "last_name": "Doe",
        "feedback_type": "bug",
        "rating": 3,
        "subject": "Login issue",
        "message": "Cannot login with email",
        "status": "new",
        "admin_response": null,
        "responded_at": null,
        "created_at": "2026-01-02T10:00:00Z"
      }
    ],
    "current_page": 1,
    "per_page": 20,
    "total": 45
  }
}
```

#### Get Single Feedback
```bash
GET /api/feedback/{id}
Authorization: Bearer {admin_token}
```

**Note:** Automatically marks feedback as "read"

## Feedback Status Workflow

### Status Types

| Status | Meaning | Action |
|--------|---------|--------|
| **new** | Just submitted | Review and categorize |
| **read** | Admin has viewed | Decide on action |
| **in_progress** | Being addressed | Work on resolution |
| **resolved** | Completed | Add response |

### Status Flow
```
new → read → in_progress → resolved
```

## Managing Feedback

### 1. Review New Feedback
```bash
GET /api/feedback?status=new
```

### 2. Mark as Read
Automatically done when you view feedback via GET endpoint

### 3. Update Status
Use database directly or future admin panel:
```php
$feedback = Feedback::find($id);
$feedback->update(['status' => 'in_progress']);
```

### 4. Add Response
```php
$feedback = Feedback::find($id);
$feedback->addResponse('Thank you for your feedback. We are working on this.');
```

## Filtering Feedback

### By Type
```bash
GET /api/feedback?type=bug
GET /api/feedback?type=feature_request
GET /api/feedback?type=general
GET /api/feedback?type=other
```

### By Status
```bash
GET /api/feedback?status=new
GET /api/feedback?status=read
GET /api/feedback?status=in_progress
GET /api/feedback?status=resolved
```

### By User
```php
$userFeedback = Feedback::where('user_id', $userId)->get();
```

### By Date Range
```php
$recent = Feedback::whereBetween('created_at', [
    now()->subDays(7),
    now()
])->get();
```

## Analytics & Reporting

### Feedback Statistics
```php
// Total feedback
$total = Feedback::count();

// By type
$bugs = Feedback::byType('bug')->count();
$features = Feedback::byType('feature_request')->count();
$general = Feedback::byType('general')->count();

// By status
$new = Feedback::byStatus('new')->count();
$resolved = Feedback::byStatus('resolved')->count();

// Average rating
$avgRating = Feedback::whereNotNull('rating')->avg('rating');

// Unread feedback
$unread = Feedback::unread()->count();
```

### Generate Report
```php
$report = [
    'total' => Feedback::count(),
    'unread' => Feedback::unread()->count(),
    'by_type' => [
        'bugs' => Feedback::byType('bug')->count(),
        'features' => Feedback::byType('feature_request')->count(),
        'general' => Feedback::byType('general')->count(),
    ],
    'by_status' => [
        'new' => Feedback::byStatus('new')->count(),
        'read' => Feedback::byStatus('read')->count(),
        'in_progress' => Feedback::byStatus('in_progress')->count(),
        'resolved' => Feedback::byStatus('resolved')->count(),
    ],
    'avg_rating' => Feedback::whereNotNull('rating')->avg('rating'),
];
```

## Best Practices

### 1. Regular Review
- Check for new feedback daily
- Prioritize bugs over features
- Respond to critical issues quickly

### 2. Clear Communication
- Acknowledge receipt of feedback
- Explain what you're doing
- Set expectations for resolution

### 3. Categorization
- Use status field to track progress
- Update status as you work
- Mark resolved when complete

### 4. Follow-up
- Respond to feedback when resolved
- Explain what was done
- Thank users for their input

### 5. Documentation
- Keep notes on feedback trends
- Track common issues
- Identify feature requests

## Common Tasks

### Find All Unresolved Bugs
```php
Feedback::byType('bug')
    ->whereIn('status', ['new', 'read', 'in_progress'])
    ->orderBy('created_at', 'desc')
    ->get();
```

### Find High-Priority Feedback
```php
Feedback::where('rating', '<=', 2)
    ->orWhere('feedback_type', 'bug')
    ->orderBy('created_at', 'desc')
    ->get();
```

### Get Feedback from Specific User
```php
Feedback::where('user_id', $userId)
    ->orderBy('created_at', 'desc')
    ->get();
```

### Get Recent Feedback
```php
Feedback::where('created_at', '>=', now()->subDays(7))
    ->orderBy('created_at', 'desc')
    ->get();
```

## Database Queries

### View All Feedback
```sql
SELECT * FROM feedback ORDER BY created_at DESC;
```

### Count by Type
```sql
SELECT feedback_type, COUNT(*) as count 
FROM feedback 
GROUP BY feedback_type;
```

### Count by Status
```sql
SELECT status, COUNT(*) as count 
FROM feedback 
GROUP BY status;
```

### Average Rating
```sql
SELECT AVG(rating) as avg_rating 
FROM feedback 
WHERE rating IS NOT NULL;
```

### Unread Feedback
```sql
SELECT * FROM feedback 
WHERE status = 'new' 
ORDER BY created_at DESC;
```

## Security Notes

- Only admins can view all feedback
- Users can only view their own feedback
- Feedback data is protected
- Responses are tracked with timestamps
- All actions are logged

## Troubleshooting

### Can't Access Feedback
- Verify you have admin role
- Check authentication token
- Ensure token hasn't expired

### Feedback Not Showing
- Check database connection
- Verify feedback table exists
- Check migration status

### Can't Update Feedback
- Verify admin permissions
- Check database write access
- Review error logs

## Future Features

- [ ] Admin dashboard for feedback management
- [ ] Email notifications for new feedback
- [ ] Bulk feedback operations
- [ ] Feedback export functionality
- [ ] Advanced filtering and search
- [ ] Feedback analytics dashboard
- [ ] Automated responses
- [ ] Feedback categorization

## Support

For technical issues or questions:
1. Check this guide
2. Review API documentation
3. Check Laravel logs
4. Contact development team

