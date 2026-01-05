# Rating & Review System - Technical Details

## Current Database Schema (INCOMPLETE)

```sql
CREATE TABLE course_reviews (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    course_id BIGINT NOT NULL FOREIGN KEY,
    user_id BIGINT NOT NULL FOREIGN KEY,
    rating TINYINT NOT NULL,
    review TEXT NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY (user_id, course_id)
);
```

## What the Controller Expects (MISSING FIELDS)

```php
// From ReviewController::store() - Line 114-123
$review = CourseReview::create([
    'course_id' => $courseId,
    'user_id' => $user->id,
    'rating' => $request->rating,
    'title' => $request->title,           // ❌ MISSING
    'comment' => $request->comment,       // ❌ MISSING
    'pros' => $request->pros,             // ❌ MISSING
    'cons' => $request->cons,             // ❌ MISSING
    'status' => 'pending'                 // ❌ MISSING
]);
```

## Missing ReviewHelpful Model

Referenced in ReviewController::markHelpful() (Line 284-298):

```php
$existingHelpful = \App\Models\ReviewHelpful::where('review_id', $id)
                                           ->where('user_id', $user->id)
                                           ->first();
```

**Required Schema:**
```sql
CREATE TABLE review_helpful (
    id BIGINT PRIMARY KEY,
    review_id BIGINT FOREIGN KEY,
    user_id BIGINT FOREIGN KEY,
    created_at TIMESTAMP,
    UNIQUE KEY (review_id, user_id)
);
```

## Missing Fields in CourseReview Model

The `$fillable` array (Line 12-17) only includes:
```php
protected $fillable = [
    'course_id',
    'user_id',
    'rating',
    'review'
];
```

**Should include:**
```php
protected $fillable = [
    'course_id',
    'user_id',
    'rating',
    'title',
    'comment',
    'pros',
    'cons',
    'status',
    'helpful_count',
    'moderated_by',
    'moderated_at',
    'rejection_reason'
];
```

## API Endpoints That Will FAIL

1. **POST /api/courses/{courseId}/reviews** - Create review
   - Error: Column 'title' doesn't exist
   
2. **PUT /api/reviews/{id}** - Update review
   - Error: Column 'comment' doesn't exist
   
3. **POST /api/reviews/{id}/helpful** - Mark helpful
   - Error: ReviewHelpful model not found
   
4. **POST /api/reviews/{id}/approve** - Approve review
   - Error: Column 'status' doesn't exist
   
5. **POST /api/reviews/{id}/reject** - Reject review
   - Error: Column 'status' doesn't exist
   
6. **GET /api/reviews/moderate** - Moderation queue
   - Error: Column 'status' doesn't exist

## Frontend Status

**No frontend implementation found** for:
- Review submission form
- Review display component
- Star rating widget
- Moderation interface

Only static HTML template exists in `storage/framework/views/b02e999eea46050c01e94503673c1620.php` with hardcoded review cards.

## Validation Rules (Implemented)

```php
// From ReviewController::store() - Line 98-104
$validator = Validator::make($request->all(), [
    'rating' => 'required|integer|min:1|max:5',
    'title' => 'required|string|max:255',
    'comment' => 'required|string|max:1000',
    'pros' => 'nullable|array',
    'cons' => 'nullable|array'
]);
```

## Analytics Features (Partially Broken)

The controller includes sophisticated analytics (Line 449-496):
- Rating distribution
- Average rating calculation
- Sentiment analysis (positive/neutral/negative)
- Monthly trends
- Keyword extraction
- Helpful reviews ranking

**Status:** Will fail due to missing `status` field filtering.

## Conclusion

The review system is **80% implemented** but **0% functional** due to database schema mismatch. All business logic exists, but the data layer is incomplete.

