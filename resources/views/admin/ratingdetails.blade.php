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
    .loading-spinner { text-align: center; padding: 20px; }
</style>
<main class="">
    <section class="container-fluid p-4 d-flex gap-5 flex-column" id="ratingDetailsContainer">
        <!-- Loading state -->
        <div class="loading-spinner">
            <i class="fa-solid fa-spinner fa-spin"></i>
            Loading rating details...
        </div>
    </section>
</main>

<script>
    const token = localStorage.getItem('auth_token');
    const urlParams = new URLSearchParams(window.location.search);
    const courseId = urlParams.get('course_id');
    const currentFilter = urlParams.get('status') || 'approved';

    document.addEventListener('DOMContentLoaded', loadRatingDetails);

    async function loadRatingDetails() {
        try {
            const response = await fetch(`/api/ratings/${courseId}?status=${currentFilter}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success && data.data) {
                renderRatingDetails(data.data);
            } else {
                showError('Failed to load rating details');
            }
        } catch (error) {
            console.error('Error loading rating details:', error);
            showError('Error loading rating details: ' + error.message);
        }
    }

    function renderRatingDetails(data) {
        const { course, reviews, statistics } = data;
        const container = document.getElementById('ratingDetailsContainer');

        const starsHtml = renderStars(Math.round(statistics.average_rating));
        const distributionHtml = renderDistribution(statistics.rating_distribution, statistics.total_reviews);
        const reviewsHtml = renderReviews(reviews.data || reviews);

        container.innerHTML = `
            <header class="d-flex gap-1 flex-column">
                <h2 class="review-header-title mb-3">${escapeHtml(course.title)}</h2>
                <div class="d-flex gap-1 align-items-center">
                    <div class="d-flex gap-1">
                        ${starsHtml}
                    </div>
                    <span class="rating-text">${parseFloat(statistics.average_rating).toFixed(1)}</span>
                </div>
                <p class="rating-header-subtitle">Based on ${statistics.total_reviews} reviews</p>
            </header>
            <section class="rating-distribution-container">
                <h4 class="d-flex flex-column rating-distribution-title mb-3">Rating Distribution</h4>
                <div class="d-flex flex-column rating-distribution-rating-container">
                    ${distributionHtml}
                </div>
            </section>
            <section class="d-flex flex-column reviews-container">
                <header class="d-flex gap-4 align-items-center justify-content-between">
                    <h4 class="reviews-header-title">Reviews (${statistics.total_reviews})</h4>
                </header>
                ${reviewsHtml}
            </section>
            <div class="d-flex gap-2 align-items-center justify-content-between">
                <button class="footer-btn" onclick="previousPage()" ${!reviews.prev_page_url ? 'disabled' : ''}>Previous</button>
                <p class="footer-pagecount">Page ${reviews.current_page} of ${reviews.last_page}</p>
                <button class="footer-btn" onclick="nextPage()" ${!reviews.next_page_url ? 'disabled' : ''}>Next</button>
            </div>
        `;
    }

    function renderStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            const color = i <= rating ? '#fdaf22' : '#e5e6e7';
            stars += `<i class="fa-solid fa-star" style="color: ${color}"></i>`;
        }
        return stars;
    }

    function renderDistribution(distribution, total) {
        let html = '';
        for (let rating = 5; rating >= 1; rating--) {
            const count = distribution[rating] || 0;
            const percentage = total > 0 ? (count / total) * 100 : 0;
            const barWidth = Math.max(30, percentage * 2);

            html += `
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap">
                        ${renderStars(rating)}
                    </div>
                    <div class="progress-bar">
                        <span class="progress-bar-track" style="width: ${barWidth}px"></span>
                    </div>
                    <span class="rating-text">${count}</span>
                </div>
            `;
        }
        return html;
    }

    function renderReviews(reviews) {
        if (!reviews || reviews.length === 0) {
            return '<div class="alert alert-info">No reviews found.</div>';
        }

        return reviews.map(review => {
            // Safely access nested properties
            const userName = review.user?.name || 'Anonymous';
            const userAvatar = review.user?.avatar || 'https://via.placeholder.com/32';
            const status = review.status || 'pending';
            const rating = review.rating || 0;
            const createdAt = review.created_at || new Date().toISOString();
            const title = review.title || '';
            const comment = review.comment || review.review || '';
            const helpfulCount = review.helpful_count || 0;
            const reviewId = review.id || 0;

            return `
            <article class="d-flex flex-column reviews-card">
                <div class="d-flex gap-3 justify-content-between">
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex gap-1 align-items-center">
                            <img src="${userAvatar}" alt="${escapeHtml(userName)}" class="reviews-card-img">
                            <h5 class="reviews-card-name">${escapeHtml(userName)}</h5>
                            <span class="status-badge status-${status}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>
                        </div>
                        <div class="d-flex align-items-center gap">
                            ${renderStars(rating)}
                        </div>
                    </div>
                    <p class="align-self-end reviews-card-date mb-0">${formatDate(createdAt)}</p>
                </div>
                <div class="d-flex flex-column gap-3">
                    ${title ? `<h6 style="color: #333; font-weight: 600;">${escapeHtml(title)}</h6>` : ''}
                    <p class="reviews-card-review">${escapeHtml(comment)}</p>
                    <div class="d-flex gap-3 align-self-end align-items-center">
                        <span class="reviews-card-helpful">${helpfulCount} helpful</span>
                        <button onclick="markHelpful(${reviewId})"><i class="fa-solid fa-thumbs-up" style="color: #999999;"></i></button>
                    </div>
                </div>
            </article>
        `;
        }).join('');
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    }

    function escapeHtml(text) {
        // Handle null, undefined, or non-string values
        if (!text || typeof text !== 'string') {
            return '';
        }
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    function showError(message) {
        const container = document.getElementById('ratingDetailsContainer');
        container.innerHTML = `
            <div class="alert alert-danger" role="alert">
                <i class="fa-solid fa-exclamation-circle"></i>
                ${escapeHtml(message)}
            </div>
        `;
    }

    function filterByStatus(status) {
        const params = new URLSearchParams(window.location.search);
        params.set('status', status);
        window.location.href = window.location.pathname + '?' + params.toString();
    }

    function markHelpful(reviewId) {
        fetch(`/api/reviews/${reviewId}/helpful`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                loadRatingDetails();
            }
        })
        .catch(error => console.error('Error marking helpful:', error));
    }

    function previousPage() {
        // Implement pagination if needed
        console.log('Previous page clicked');
    }

    function nextPage() {
        // Implement pagination if needed
        console.log('Next page clicked');
    }
</script>
@endsection

