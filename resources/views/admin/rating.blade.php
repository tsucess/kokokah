@extends('layouts.dashboardtemp')

@section('content')
    <main>
        <section class="d-flex flex-column rating-container">
            <header class="m-4 d-flex flex-column rating-header">
                <h1>Reviews & Ratings</h1>
                <p class="rating-subtitle">
                    See what learners think about your courses.
                </p>
            </header>
            <section class="container-fluid">
                <div class="row g-4" id="coursesContainer">
                    <!-- Loading skeleton -->
                    <div class="col col-12">
                        <div class="alert alert-info" role="alert">
                            <i class="fa-solid fa-spinner fa-spin"></i>
                            Loading courses...
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </main>

    <script>
        // Get auth token from localStorage
        const token = localStorage.getItem('auth_token');

        // Load courses on page load
        document.addEventListener('DOMContentLoaded', loadCourses);

        async function loadCourses() {
            try {
                const response = await fetch('/api/ratings', {
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
                    renderCourses(data.data);
                } else {
                    showError('Failed to load courses');
                }
            } catch (error) {
                console.error('Error loading courses:', error);
                showError('Error loading courses: ' + error.message);
            }
        }

        function renderCourses(courses) {
            const container = document.getElementById('coursesContainer');

            if (!courses || courses.length === 0) {
                container.innerHTML = `
                    <div class="col col-12">
                        <div class="alert alert-info" role="alert">
                            <i class="fa-solid fa-info-circle"></i>
                            No courses found. Create a course to start receiving reviews.
                        </div>
                    </div>
                `;
                return;
            }

            container.innerHTML = courses.map(course => `
                <div class="col col-12 col-lg-6">
                    <div class="review-container">
                        <header class="d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column review-header-title-container">
                                <h4 class="review-header-title">${escapeHtml(course.title)}</h4>
                                <div class="d-flex gap align-items-center">
                                    <div class="d-flex align-items-center gap">
                                        ${renderStars(Math.round(course.average_rating))}
                                    </div>
                                    <span class="review-header-rating">${parseFloat(course.average_rating).toFixed(1)}</span>
                                </div>
                                <p class="review-header-subtitle">Based on ${course.total_reviews} reviews</p>
                            </div>
                            <a href="#" onclick="viewCourseDetails(${course.id}); return false;" class="review-btn">View Review</a>
                        </header>
                        <div class="d-flex flex-column gap-2">
                            <h5 class="review-distribution">Rating Distribution</h5>
                            <div class="d-flex flex-column gap-1">
                                ${renderRatingDistribution(course.rating_distribution, course.total_reviews)}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function renderStars(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                const color = i <= rating ? '#fdaf22' : '#e5e6e7';
                stars += `<i class="fa-solid fa-star" style="color: ${color}"></i>`;
            }
            return stars;
        }

        function renderRatingDistribution(distribution, total) {
            let html = '';
            for (let rating = 5; rating >= 1; rating--) {
                const count = distribution[rating] || 0;
                const percentage = total > 0 ? (count / total) * 100 : 0;
                const barWidth = Math.max(27, percentage * 2);

                html += `
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap">
                            ${renderStars(rating)}
                        </div>
                        <div class="progress-bar">
                            <span class="progress-bar-track" style="width: ${barWidth}px"></span>
                        </div>
                        <span class="review-header-rating">${count}</span>
                    </div>
                `;
            }
            return html;
        }

        function escapeHtml(text) {
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
            const container = document.getElementById('coursesContainer');
            container.innerHTML = `
                <div class="col col-12">
                    <div class="alert alert-danger" role="alert">
                        <i class="fa-solid fa-exclamation-circle"></i>
                        ${escapeHtml(message)}
                    </div>
                </div>
            `;
        }

        function viewCourseDetails(courseId) {
            // Navigate to the course details page
            window.location.href = `/rating-details?course_id=${courseId}`;
        }
    </script>
@endsection

