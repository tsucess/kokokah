# Review & Rating System - Integration Guide

## 🔗 How to Integrate into Your Application

### 1. Add Review Form to Course Details Page

**Location:** `resources/views/users/subjectdetails.blade.php` (or your course detail page)

**Add this code where you want the review form to appear:**
```blade
<!-- Review Section -->
<section class="review-section mt-5">
    <h3>Leave a Review</h3>
    @auth
        @include('components.review-form')
    @else
        <p>Please <a href="{{ route('login') }}">login</a> to leave a review.</p>
    @endauth
</section>
```

**Pass course ID to the form:**
```blade
<div data-course-id="{{ $course->id }}">
    @include('components.review-form')
</div>
```

### 2. Display Reviews on Course Page

**Add this JavaScript to fetch and display reviews:**
```javascript
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const courseId = document.querySelector('[data-course-id]')?.getAttribute('data-course-id');
    if (!courseId) return;

    try {
        const response = await fetch(`/api/courses/${courseId}/reviews`);
        const result = await response.json();
        
        if (result.success) {
            displayReviews(result.data.reviews);
            displayStatistics(result.data.statistics);
        }
    } catch (error) {
        console.error('Error loading reviews:', error);
    }
});

function displayReviews(reviews) {
    const container = document.getElementById('reviews-container');
    reviews.forEach(review => {
        const html = `
            <div class="review-card">
                <div class="review-header">
                    <strong>${review.user.name}</strong>
                    <span class="rating">${'⭐'.repeat(review.rating)}</span>
                </div>
                <h4>${review.title}</h4>
                <p>${review.comment}</p>
                <button onclick="markHelpful(${review.id})">
                    👍 Helpful (${review.helpful_count})
                </button>
            </div>
        `;
        container.innerHTML += html;
    });
}

function displayStatistics(stats) {
    document.getElementById('avg-rating').textContent = stats.average_rating.toFixed(1);
    document.getElementById('total-reviews').textContent = stats.total_reviews;
}

function markHelpful(reviewId) {
    fetch(`/api/reviews/${reviewId}/helpful`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').value
        }
    }).then(r => r.json()).then(d => {
        if (d.success) location.reload();
    });
}
</script>
```

### 3. Add Rating Link to Admin Dashboard

**Location:** `resources/views/admin/dashboard.blade.php`

**Add this link:**
```blade
<a href="{{ route('admin.rating.index') }}" class="dashboard-link">
    <i class="fa-solid fa-star"></i>
    Reviews & Ratings
</a>
```

### 4. Add to Admin Navigation Menu

**Location:** `resources/views/layouts/dashboardtemp.blade.php` (or your admin layout)

**Add this menu item:**
```blade
<li class="nav-item">
    <a href="{{ route('admin.rating.index') }}" class="nav-link">
        <i class="fa-solid fa-star"></i>
        <span>Reviews</span>
    </a>
</li>
```

### 5. Display Course Rating in Course Cards

**Add this to course listing pages:**
```blade
<div class="course-rating">
    @php
        $avgRating = $course->reviews->avg('rating') ?? 0;
        $totalReviews = $course->reviews->count();
    @endphp
    
    <div class="stars">
        @for($i = 1; $i <= 5; $i++)
            <i class="fa-solid fa-star" style="color: {{ $i <= round($avgRating) ? '#fdaf22' : '#e5e6e7' }}"></i>
        @endfor
    </div>
    <span class="rating-text">{{ number_format($avgRating, 1) }} ({{ $totalReviews }} reviews)</span>
</div>
```

### 6. Add Review Count Badge

**Show pending reviews count for instructors:**
```blade
@if(auth()->user()->hasRole('instructor'))
    @php
        $pendingCount = \App\Models\CourseReview::whereHas('course', function($q) {
            $q->where('instructor_id', auth()->id());
        })->where('status', 'pending')->count();
    @endphp
    
    @if($pendingCount > 0)
        <span class="badge badge-warning">{{ $pendingCount }} pending reviews</span>
    @endif
@endif
```

### 7. Add Review Analytics Widget

**Add to admin dashboard:**
```blade
<div class="analytics-widget">
    <h4>Review Statistics</h4>
    <div id="review-stats">
        <p>Total Reviews: <strong id="total-reviews">0</strong></p>
        <p>Average Rating: <strong id="avg-rating">0</strong></p>
        <p>Pending Approval: <strong id="pending-reviews">0</strong></p>
    </div>
</div>

<script>
fetch('/api/reviews/moderate')
    .then(r => r.json())
    .then(d => {
        document.getElementById('pending-reviews').textContent = d.data.length;
    });
</script>
```

## 🎨 Styling

**Add to your CSS file:**
```css
.review-form-container {
    background: #f9f9f9;
    padding: 24px;
    border-radius: 10px;
    max-width: 600px;
}

.review-card {
    border: 1px solid #ddd;
    padding: 16px;
    margin: 12px 0;
    border-radius: 8px;
}

.review-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}

.rating {
    color: #fdaf22;
    font-size: 14px;
}

.stars {
    display: flex;
    gap: 4px;
}

.badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.badge-warning {
    background-color: #fff3cd;
    color: #856404;
}
```

## 🔐 Permissions Setup

**Ensure user roles are properly configured:**
```php
// In your User model or role seeder
$user->assignRole('student');      // Can review
$user->assignRole('instructor');   // Can moderate own courses
$user->assignRole('admin');        // Can moderate all
```

## 📱 Mobile Responsive

The review form and views are already responsive. Ensure your layout includes:
```html
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```

## 🧪 Test Integration

1. Navigate to a course detail page
2. Scroll to review section
3. Submit a test review
4. Check admin dashboard for pending reviews
5. Approve/reject the review
6. Verify it appears on course page

## 🚀 Deployment

1. Run migrations: `php artisan migrate`
2. Clear cache: `php artisan cache:clear`
3. Test all endpoints
4. Deploy to production

---

**Integration Complete!** Your review system is ready to use.

