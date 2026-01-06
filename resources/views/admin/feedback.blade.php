@extends('layouts.dashboardtemp')

@section('content')
    <style>
        p { margin-bottom: 0; }
        .feedback-card-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; }
        .feedback-card { border: 1px solid #8D8E8E; border-radius: 20px; padding: 24px; transition: all 0.3s ease; }
        .feedback-card.hidden { display: none !important; }
        .feedback-card.visible { display: flex !important; }
        .feedback-card-img { width: 47px; height: 47px; border-radius: 50%; object-fit: fill; object-position: center; }
        .feedback-card-name { color: #004A53; font-size: 18px; font-family: 'Fredoka One'; font-weight: 500; }
        .email { color: #737373; font-size: 14px; }
        .divider { width: 100%; height: 1px; background-color: #8D8E8E; }
        .feature-card-title { color: #1C1D1D; font-size: 16px; font-weight: 600; margin-bottom: 0; }
        .feature-type { background-color: #E6E8FF; padding: 4px 30px; border-radius: 20px; color: #1C1D1D; font-weight: 600; font-size: 15px; }
        .feature-text { color: #1C1D1D; font-size: 16px; }
        .loading { text-align: center; padding: 40px; }
        .spinner { border: 4px solid #f3f3f3; border-top: 4px solid #004A53; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .no-results { text-align: center; padding: 40px; grid-column: 1 / -1; }
        .custom-select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; cursor: pointer; }
    </style>
    <main>
        <div class="container-fluid px-5 py-4">
            <div class="mb-4 d-flex justify-content-between gap-3 align-items-center">
                <div class="d-flex flex-column gap-2">
                    <h4 class="fw-bold">Feedback Details</h4>
                    <p>Your feedback helps us improve your learning experience.</p>
                </div>
                <select class="custom-select" id="filterSelect">
                    <option value="">All Feedback</option>
                    <option value="bug">Bug Report</option>
                    <option value="feature_request">Request Feature</option>
                    <option value="general">General Feedback</option>
                    <option value="other">We Listen</option>
                </select>
            </div>

            <div id="errorContainer"></div>
            <div id="loadingContainer" class="loading">
                <div class="spinner"></div>
                <p>Loading feedback...</p>
            </div>
            <div class="feedback-card-container" id="feedbackContainer" style="display: none;"></div>
        </div>
    </main>

    <script>
        const feedbackTypeLabels = {
            'bug': 'Bug Report',
            'feature_request': 'Request Feature',
            'general': 'General Feedback',
            'other': 'We Listen'
        };

        function renderStars(rating) {
            let html = '<div class="d-flex gap-1">';
            for (let i = 1; i <= 5; i++) {
                const color = i <= rating ? '#FDAF22' : '#E5E6E7';
                html += `<i class="fa-solid fa-star fa-sm" style="color:${color}"></i>`;
            }
            html += '</div>';
            return html;
        }

        function getFeedbackTypeLabel(type) {
            return feedbackTypeLabels[type] || type.replace(/_/g, ' ').charAt(0).toUpperCase() + type.slice(1);
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function createFeedbackCard(item) {
            const ratingHtml = item.rating ? `
                <div class="d-flex gap-2 align-items-center">
                    <p class="feature-card-title">Rating:</p>
                    ${renderStars(item.rating)}
                </div>
            ` : '';

            const subjectHtml = item.subject ? `
                <div class="d-flex gap-2 align-items-center">
                    <p class="feature-card-title">Subject:</p>
                    <p class="feature-card-title">${escapeHtml(item.subject)}</p>
                </div>
            ` : '';

            return `
                <div class="feedback-card d-flex flex-column align-items-start" data-feedback-type="${item.feedback_type}">
                    <div class="d-flex align-items-center gap-2">
                        <img src='./images/feedback-img.jpg' class="feedback-card-img" alt="${escapeHtml(item.first_name)}" />
                        <div class="d-flex flex-column gap-1">
                            <h3 class="feedback-card-name">${escapeHtml(item.first_name)} ${escapeHtml(item.last_name)}</h3>
                            <h4 class="email">${item.user && item.user.email ? escapeHtml(item.user.email) : 'N/A'}</h4>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-2 align-items-center">
                            <p class="feature-card-title">Feedback Type:</p>
                            <div class="feature-type">${getFeedbackTypeLabel(item.feedback_type)}</div>
                        </div>
                        ${ratingHtml}
                        ${subjectHtml}
                        <p class="feature-text">${escapeHtml(item.message)}</p>
                    </div>
                    <div class="divider"></div>
                    <div class="d-flex align-items-center gap-1">
                        <p>Submitted on:</p>
                        <p>${formatDate(item.created_at)}</p>
                    </div>
                </div>
            `;
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

        function loadFeedback() {
            const token = localStorage.getItem('auth_token');

            fetch('/api/feedback/', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const loadingContainer = document.getElementById('loadingContainer');
                const feedbackContainer = document.getElementById('feedbackContainer');
                const errorContainer = document.getElementById('errorContainer');

                loadingContainer.style.display = 'none';

                // Handle paginated response from Laravel
                let feedbackData = [];
                if (data.success && data.data) {
                    // If data.data is an array, use it directly
                    if (Array.isArray(data.data)) {
                        feedbackData = data.data;
                    }
                    // If data.data is an object with a 'data' property (paginated), use that
                    else if (data.data.data && Array.isArray(data.data.data)) {
                        feedbackData = data.data.data;
                    }
                }

                if (feedbackData.length === 0) {
                    errorContainer.innerHTML = '<div class="alert alert-info">No feedback found. Check back later!</div>';
                    return;
                }

                feedbackData.forEach(item => {
                    feedbackContainer.innerHTML += createFeedbackCard(item);
                });
                feedbackContainer.style.display = 'grid';
                setupFilterListener();
            })
            .catch(error => {
                console.error('Error loading feedback:', error);
                const loadingContainer = document.getElementById('loadingContainer');
                const errorContainer = document.getElementById('errorContainer');
                loadingContainer.style.display = 'none';
                errorContainer.innerHTML = `<div class="alert alert-danger">Failed to load feedback: ${escapeHtml(error.message)}</div>`;
            });
        }

        function setupFilterListener() {
            const filterSelect = document.getElementById('filterSelect');
            const feedbackContainer = document.getElementById('feedbackContainer');

            filterSelect.addEventListener('change', function() {
                const selectedType = this.value;
                const feedbackCards = feedbackContainer.querySelectorAll('.feedback-card');
                let visibleCount = 0;

                feedbackCards.forEach(card => {
                    const cardType = card.getAttribute('data-feedback-type');
                    const shouldShow = selectedType === '' || cardType === selectedType;

                    if (shouldShow) {
                        card.classList.remove('hidden');
                        card.classList.add('visible');
                        visibleCount++;
                    } else {
                        card.classList.remove('visible');
                        card.classList.add('hidden');
                    }
                });

                // Show "no results" message if no cards are visible
                let noResultsMsg = feedbackContainer.querySelector('.no-results');
                if (visibleCount === 0) {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.className = 'no-results';
                        noResultsMsg.innerHTML = '<p class="text-muted">No feedback found for this type.</p>';
                        feedbackContainer.appendChild(noResultsMsg);
                    }
                    noResultsMsg.style.display = 'block';
                } else {
                    if (noResultsMsg) {
                        noResultsMsg.style.display = 'none';
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadFeedback();
        });
    </script>
@endsection

