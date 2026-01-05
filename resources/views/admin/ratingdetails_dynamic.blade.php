@extends('layouts.dashboardtemp')

@section('content')
<style>
    .review-header-title { font-size: 32px; font-family: "Fredoka", sans-serif; font-weight: 600; color: #004A53; }
    .rating-text { color: #004A53; font-size: 16px; }
    .rating-header-subtitle { color: #1C1D1D; font-size: 18px; }
    .rating-distribution-container { max-width: 682px; width: 100%; gap: 16px; }
    .rating-distribution-title { color: #000000; font-size: 20px; font-weight: 700; }
    .rating-distribution-rating-container { gap: 14px; }
    .progress-bar { width: 198px; height: 6px; border-radius: 3px; background-color: #78787833; }
    .progress-bar-track { background-color: #004a53; height: 6px; border-radius: 3px; }
    .reviews-container { gap: 16px; }
    .reviews-header-title { color: #000000; font-size: 20px; }
    .reviews-btn { color: #777777; font-size: 12px; }
    button { background-color: transparent; border: none; }
    .most-recent-container { width: 107px; height: 27px; background-color: #F5F4F9; border-radius: 6px; font-size: 12px; color: #777777; outline: none; border: none; padding: 1px; }
    .reviews-card { border-radius: 10px; padding: 24px; gap: 16px; box-shadow: 0 11px 24px 0 rgba(0, 0, 0, 0.11); }
    .reviews-card-date { color: #999999; font-size: 12px; }
    .reviews-card-img { width: 32px; height: 32px; border-radius: 50%; object-fit: fill; }
    .reviews-card-name { font-size: 14px; color: #333333; font-weight: 700; }
    .reviews-card-review { color: #555555; font-size: 14px; }
    .reviews-card-helpful { color: #999999; font-size: 12px; }
    .footer-pagecount { color: #CBCCCD; font-size: 12.9px; }
    .footer-btn { border-radius: 6px; border: 1px solid #DEDEDE; color: #404040; font-weight: 500; width: 63px; height: 21px; font-size: 10px; }
    .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; }
    .status-pending { background-color: #fff3cd; color: #856404; }
    .status-approved { background-color: #d4edda; color: #155724; }
    .status-rejected { background-color: #f8d7da; color: #721c24; }
</style>
<main class="">
    <section class="container-fluid p-4 d-flex gap-5 flex-column">
        <header class="d-flex gap-1 flex-column">
            <h2 class="review-header-title">{{ $course->title }}</h2>
            <div class="d-flex gap-1 align-items-center">
                <div class="d-flex gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-solid fa-star" style="color: {{ $i <= round($statistics['average_rating']) ? '#FDAF22' : '#E5E6E7' }}"></i>
                    @endfor
                </div>
                <span class="rating-text">{{ number_format($statistics['average_rating'], 1) }}</span>
            </div>
            <p class="rating-header-subtitle">Based on {{ $statistics['total_reviews'] }} reviews</p>
        </header>
        <section class="rating-distribution-container">
            <h4 class="d-flex flex-column rating-distribution-title">Rating Distribution</h4>
            <div class="d-flex flex-column rating-distribution-rating-container">
                @for($rating = 5; $rating >= 1; $rating--)
                    @php
                        $count = $statistics['rating_distribution'][$rating] ?? 0;
                        $total = $statistics['total_reviews'] ?: 1;
                        $percentage = ($count / $total) * 100;
                        $barWidth = max(30, $percentage * 2);
                    @endphp
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star" style="color: {{ $i <= $rating ? '#fdaf22' : '#e5e6e7' }}"></i>
                            @endfor
                        </div>
                        <div class="progress-bar">
                            <span class="progress-bar-track" style="width: {{ $barWidth }}px"></span>
                        </div>
                        <span class="rating-text">{{ $count }}</span>
                    </div>
                @endfor
            </div>
        </section>
        <section class="d-flex flex-column reviews-container">
            <header class="d-flex gap-4 align-items-center justify-content-between">
                <h4 class="reviews-header-title">Reviews ({{ $statistics['total_reviews'] }})</h4>
                <div class="d-flex gap-1 align-items-center">
                    <button class="reviews-btn">Filter by</button>
                    <select class="most-recent-container" onchange="filterByStatus(this.value)">
                        <option value="approved" {{ $currentFilter === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="pending" {{ $currentFilter === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ $currentFilter === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
            </header>
            @forelse($reviews as $review)
            <article class="d-flex flex-column reviews-card">
                <div class="d-flex gap-3 justify-content-between">
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex gap-1 align-items-center">
                            <img src="{{ $review->user->avatar ?? 'https://via.placeholder.com/32' }}" alt="{{ $review->user->name }}" class="reviews-card-img">
                            <h5 class="reviews-card-name">{{ $review->user->name }}</h5>
                            <span class="status-badge status-{{ $review->status }}">{{ ucfirst($review->status) }}</span>
                        </div>
                        <div class="d-flex align-items-center gap">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star" style="color: {{ $i <= $review->rating ? '#fdaf22' : '#9B9B9B' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="align-self-end reviews-card-date mb-0">{{ $review->created_at->format('M d, Y') }}</p>
                </div>
                <div class="d-flex flex-column gap-3">
                    @if($review->title)
                        <h6 style="color: #333; font-weight: 600;">{{ $review->title }}</h6>
                    @endif
                    <p class="reviews-card-review">{{ $review->comment ?? $review->review }}</p>
                    <div class="d-flex gap-3 align-self-end align-items-center">
                        <span class="reviews-card-helpful">{{ $review->helpful_count }} helpful</span>
                        <button onclick="markHelpful({{ $review->id }})"><i class="fa-solid fa-thumbs-up" style="color: #999999;"></i></button>
                    </div>
                </div>
            </article>
            @empty
            <div class="alert alert-info">No reviews found.</div>
            @endforelse
        </section>
        <div class="d-flex gap-2 align-items-center justify-content-between">
            @if($reviews->onFirstPage())
                <button class="footer-btn" disabled>Previous</button>
            @else
                <a href="{{ $reviews->previousPageUrl() }}" class="footer-btn">Previous</a>
            @endif
            <p class="footer-pagecount">Page {{ $reviews->currentPage() }} of {{ $reviews->lastPage() }}</p>
            @if($reviews->hasMorePages())
                <a href="{{ $reviews->nextPageUrl() }}" class="footer-btn">Next</a>
            @else
                <button class="footer-btn" disabled>Next</button>
            @endif
        </div>
    </section>
</main>
<script>
    function filterByStatus(status) {
        window.location.href = '{{ route("admin.rating.show", $course->id) }}?status=' + status;
    }
    function markHelpful(reviewId) {
        fetch(`/api/reviews/${reviewId}/helpful`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
            .then(r => r.json()).then(d => { if(d.success) location.reload(); });
    }
</script>
@endsection

