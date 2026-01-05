# Rating & Review System - Implementation Roadmap

## Current Status: ⚠️ INCOMPLETE (80% Code, 0% Functional)

---

## Phase 1: Database Schema Completion (CRITICAL)

### Step 1.1: Create Migration for course_reviews Enhancement
**File:** `database/migrations/2026_01_XX_XXXXXX_enhance_course_reviews_table.php`

```php
Schema::table('course_reviews', function (Blueprint $table) {
    // Add review content fields
    $table->string('title')->after('rating');
    $table->text('comment')->after('title');
    $table->json('pros')->nullable()->after('comment');
    $table->json('cons')->nullable()->after('pros');
    
    // Add moderation fields
    $table->enum('status', ['pending', 'approved', 'rejected'])
          ->default('pending')->after('cons');
    $table->integer('helpful_count')->default(0)->after('status');
    $table->unsignedBigInteger('moderated_by')->nullable()->after('helpful_count');
    $table->timestamp('moderated_at')->nullable()->after('moderated_by');
    $table->text('rejection_reason')->nullable()->after('moderated_at');
    
    // Add indexes
    $table->index('status');
    $table->index(['course_id', 'status']);
    $table->foreign('moderated_by')->references('id')->on('users');
});
```

### Step 1.2: Create review_helpful Table
**File:** `database/migrations/2026_01_XX_XXXXXX_create_review_helpful_table.php`

```php
Schema::create('review_helpful', function (Blueprint $table) {
    $table->id();
    $table->foreignId('review_id')->constrained('course_reviews')->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    $table->unique(['review_id', 'user_id']);
});
```

---

## Phase 2: Model Updates

### Step 2.1: Update CourseReview Model
```php
protected $fillable = [
    'course_id', 'user_id', 'rating', 'title', 'comment',
    'pros', 'cons', 'status', 'helpful_count', 'moderated_by',
    'moderated_at', 'rejection_reason'
];

protected $casts = [
    'rating' => 'integer',
    'pros' => 'array',
    'cons' => 'array',
    'moderated_at' => 'datetime',
];

public function helpfulMarks() {
    return $this->hasMany(ReviewHelpful::class, 'review_id');
}

public function moderator() {
    return $this->belongsTo(User::class, 'moderated_by');
}
```

### Step 2.2: Create ReviewHelpful Model
**File:** `app/Models/ReviewHelpful.php`

```php
class ReviewHelpful extends Model {
    protected $table = 'review_helpful';
    protected $fillable = ['review_id', 'user_id'];
    
    public function review() {
        return $this->belongsTo(CourseReview::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}
```

---

## Phase 3: Frontend Implementation

### Step 3.1: Review Submission Form
- Create modal/page for review submission
- Implement star rating widget (1-5 stars)
- Add title, comment, pros/cons fields
- Validate enrollment before showing form
- Prevent duplicate reviews

### Step 3.2: Review Display Component
- Show approved reviews with ratings
- Display helpful count
- Allow marking reviews as helpful
- Show reviewer name and date
- Filter by rating

### Step 3.3: Moderation Interface
- Admin/instructor dashboard for pending reviews
- Approve/reject buttons
- Rejection reason input
- Bulk moderation actions

---

## Phase 4: Testing & Validation

### Unit Tests
- Review creation with all fields
- Moderation workflow
- Helpful marking
- Analytics calculations

### Integration Tests
- Full review lifecycle
- Permission checks
- Enrollment validation

### Manual Testing
- User review submission
- Admin moderation
- Analytics dashboard

---

## Estimated Effort

| Phase | Effort | Time |
|-------|--------|------|
| Database | 1-2 hours | Low |
| Models | 1 hour | Low |
| Frontend | 4-6 hours | Medium |
| Testing | 2-3 hours | Medium |
| **Total** | **8-12 hours** | **1-2 days** |

---

## Success Criteria

✅ All API endpoints functional
✅ Database schema complete
✅ Frontend review submission working
✅ Moderation workflow operational
✅ Analytics displaying correctly
✅ All tests passing

---

## Risk Assessment

**High Risk:** Database migration (data loss if not careful)
**Medium Risk:** Frontend implementation (UX complexity)
**Low Risk:** Model updates (straightforward)

**Mitigation:** Backup database before migration, test on staging first.

