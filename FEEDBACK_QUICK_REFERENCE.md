# Feedback System - Quick Reference Guide

## Quick Start

### For Users
Visit `/userfeedback` to submit feedback. No authentication required.

### For Developers

#### Test the API
```bash
# Submit feedback
curl -X POST http://localhost:8000/api/feedback/submit \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "feedback_type": "bug",
    "rating": 4,
    "subject": "Test",
    "message": "This is a test feedback message with sufficient length."
  }'

# Get user feedback (authenticated)
curl -X GET http://localhost:8000/api/feedback/my-feedback \
  -H "Authorization: Bearer YOUR_TOKEN"

# Get all feedback (admin only)
curl -X GET http://localhost:8000/api/feedback \
  -H "Authorization: Bearer ADMIN_TOKEN"

# Get single feedback (admin only)
curl -X GET http://localhost:8000/api/feedback/1 \
  -H "Authorization: Bearer ADMIN_TOKEN"
```

## File Structure

```
app/
├── Models/
│   └── Feedback.php
└── Http/Controllers/
    └── FeedbackController.php

database/
└── migrations/
    └── 2026_01_02_000001_create_feedback_table.php

resources/views/users/
└── userfeedback.blade.php

routes/
└── api.php (updated)

tests/Feature/
└── FeedbackTest.php
```

## Key Classes

### Feedback Model
```php
use App\Models\Feedback;

// Create feedback
$feedback = Feedback::create([
    'first_name' => 'John',
    'last_name' => 'Doe',
    'feedback_type' => 'bug',
    'message' => 'Bug description',
]);

// Query feedback
$unread = Feedback::unread()->get();
$bugs = Feedback::byType('bug')->get();
$resolved = Feedback::byStatus('resolved')->get();

// Mark as read
$feedback->markAsRead();

// Add response
$feedback->addResponse('Thank you for your feedback!');
```

### FeedbackController Methods
```php
// Store feedback (public)
POST /api/feedback/submit

// Get user feedback (authenticated)
GET /api/feedback/my-feedback

// Get all feedback (admin)
GET /api/feedback

// Get single feedback (admin)
GET /api/feedback/{id}
```

## Database Schema

```sql
CREATE TABLE feedback (
  id BIGINT PRIMARY KEY,
  user_id BIGINT NULLABLE,
  first_name VARCHAR(255),
  last_name VARCHAR(255),
  feedback_type ENUM('bug', 'feature_request', 'general', 'other'),
  rating INT NULLABLE,
  subject VARCHAR(255) NULLABLE,
  message LONGTEXT,
  status ENUM('new', 'read', 'in_progress', 'resolved'),
  admin_response TEXT NULLABLE,
  responded_at TIMESTAMP NULLABLE,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

## Frontend Integration

### Form ID
`#feedbackForm`

### Input Fields
- `first_name` - First name input
- `last_name` - Last name input
- `feedback_type` - Feedback type select
- `rating` - Hidden input for rating
- `subject` - Subject input
- `message` - Message textarea

### Star Rating
- Click stars to rate (1-5)
- Hover effect shows preview
- Selected rating stored in `#ratingInput`

### Success/Error Messages
- Success message: `#successMessage`
- Error messages: `#{fieldName}Error`
- Loading spinner: `#loadingSpinner`

## Validation

### Frontend
- Real-time error display
- Required field indicators (*)
- Message length validation (10-5000 chars)

### Backend
```php
'first_name' => 'required|string|max:255',
'last_name' => 'required|string|max:255',
'feedback_type' => 'required|in:bug,feature_request,general,other',
'rating' => 'nullable|integer|min:1|max:5',
'subject' => 'nullable|string|max:255',
'message' => 'required|string|min:10|max:5000',
```

## Common Tasks

### Get feedback statistics
```php
$total = Feedback::count();
$bugs = Feedback::byType('bug')->count();
$unread = Feedback::unread()->count();
```

### Filter feedback
```php
$recent = Feedback::orderBy('created_at', 'desc')->limit(10)->get();
$userFeedback = Feedback::where('user_id', $userId)->get();
```

### Respond to feedback
```php
$feedback = Feedback::find($id);
$feedback->addResponse('Thank you for your feedback!');
```

## Troubleshooting

### Form not submitting
- Check CSRF token is present
- Verify API endpoint is accessible
- Check browser console for errors

### Validation errors
- Ensure all required fields are filled
- Message must be 10+ characters
- Feedback type must be valid

### Database issues
- Run migration: `php artisan migrate`
- Check migration status: `php artisan migrate:status`

## Performance

### Indexes
- `user_id, created_at` - For user feedback queries
- `feedback_type, status` - For filtering
- `status, created_at` - For status-based queries

### Pagination
- Admin endpoint returns 20 items per page
- Use `?page=2` to get next page

## Security

- CSRF protection on form
- Input validation on backend
- SQL injection prevention via Eloquent
- XSS protection via Laravel escaping
- Role-based access control

