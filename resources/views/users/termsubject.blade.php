@extends('layouts.usertemplate')

@section('content')
    <main>
        <div class="container-fluid pt-4 pb-5" style="max-width: 1400px;">

            <div class="row mb-4">
                <div class="col-12">
                    <p id='back-btn' class="text-muted mb-4" style="cursor: pointer;"><i class="fa-solid fa-chevron-left me-2"></i> Back</p>
                </div>

                <div class="col-12 d-flex flex-column flex-md-row gap-3 mb-4" id="termButtonsContainer">
                    <!-- Term buttons will be loaded dynamically -->
                </div>

                <div class="col-12 mb-5">
                    <div class="d-flex overflow-auto py-2" id="lessonsProgressContainer">
                        <!-- Lesson progress buttons will be loaded dynamically -->
                    </div>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-12 col-lg-8" id="lessonsContainer">
                    <!-- Lessons will be loaded dynamically -->
                </div>

                <div class="col-12 col-lg-4">

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small text-muted">
                                    <span class = "text-dark">Rating In The Group</span>
                                    <span id="groupRating">-
                                        <img src = "images/celebrate.png" class = "img-fluid"
                                            style = "width:15px; height:15px;">
                                    </span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" id="groupRatingBar" role="progressbar" style="width: 0%; background-color: #114243;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between small text-muted">
                                    <span>Average Point</span>
                                    <span id="averagePoint">-</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" id="averagePointBar" role="progressbar"
                                        style="width: 0%; background-color: #114243;" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between small text-muted">
                                    <span>Subject Completed</span>
                                    <span id="subjectCompleted">-</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" id="subjectCompletedBar" role="progressbar"
                                        style="width: 0%; background-color: #114243;" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">This Subject Includes</h6>

                            <ul class="list-unstyled small" id="subjectIncludesList">
                                <!-- Subject details will be loaded dynamically -->
                            </ul>
                        </div>
                    </div>

                    <!-- Course Reviews Section -->
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Course Reviews</h6>

                            <!-- Review Statistics -->
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div id="ratingStars" class="d-flex gap-1">
                                        <!-- Stars will be populated here -->
                                    </div>
                                    <span id="averageRating" class="fw-bold" style="font-size: 18px;">0.0</span>
                                </div>
                                <span id="totalReviews" class="text-muted">0 reviews</span>
                            </div>

                            <!-- Review Form -->
                            <div id="reviewFormContainer" class="mb-4" data-course-id="{{ request()->query('course_id') }}">
                                <!-- Will be populated by JavaScript after checking token -->
                            </div>

                            <!-- Reviews List -->
                            <div id="reviewsContainer" class="d-flex flex-column gap-3">
                                {{-- <p class="text-muted text-center">Loading reviews...</p> --}}
                            </div>
                        </div>
                    </div>

                    <style>
                        /* Review Form Styles */
                        .review-form-container {
                            background: #f9f9f9;
                            padding: 16px;
                            border-radius: 10px;
                        }

                        .form-group {
                            display: flex;
                            flex-direction: column;
                            gap: 8px;
                        }

                        .form-label {
                            font-weight: 600;
                            color: #333;
                            font-size: 14px;
                        }

                        .rating-selector {
                            display: flex;
                            gap: 12px;
                            flex-direction: row;
                            justify-content: flex-start;
                        }

                        .rating-input {
                            display: none;
                        }

                        .rating-label {
                            cursor: pointer;
                            font-size: 24px;
                            color: #e5e6e7;
                            transition: color 0.2s;
                        }

                        .form-control {
                            padding: 10px 12px;
                            border: 1px solid #ddd;
                            border-radius: 6px;
                            font-size: 14px;
                            font-family: inherit;
                        }

                        .form-control:focus {
                            outline: none;
                            border-color: #004A53;
                            box-shadow: 0 0 0 3px rgba(0, 74, 83, 0.1);
                        }

                        .btn {
                            padding: 10px 16px;
                            border-radius: 6px;
                            font-weight: 500;
                            cursor: pointer;
                            border: none;
                            transition: all 0.2s;
                        }

                        .btn-primary {
                            background-color: #004A53;
                            color: white;
                        }

                        .btn-primary:hover {
                            background-color: #003a41;
                        }

                        /* Review Card Styles */
                        .review-card {
                            border: 1px solid #CCDBDD;
                            border-radius: 10px;
                            padding: 16px;
                            background-color: #FAFAFA;
                            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                        }

                        .review-card-header {
                            display: flex;
                            justify-content: space-between;
                            align-items: flex-start;
                            margin-bottom: 12px;
                        }

                        .review-card-user {
                            display: flex;
                            align-items: center;
                            gap: 8px;
                        }

                        .review-card-avatar {
                            width: 32px;
                            height: 32px;
                            border-radius: 50%;
                            object-fit: cover;
                        }

                        .review-card-name {
                            font-size: 14px;
                            color: #333333;
                            font-weight: 700;
                        }

                        .review-card-date {
                            color: #999999;
                            font-size: 12px;
                        }

                        .review-card-title {
                            margin: 8px 0;
                            color: #000F11;
                            font-weight: 600;
                        }

                        .review-card-comment {
                            color: #475569;
                            margin: 8px 0;
                            line-height: 1.5;
                            font-size: 14px;
                        }

                        .review-pros {
                            margin: 12px 0;
                            padding: 8px;
                            background-color: #E8F5E9;
                            border-radius: 4px;
                        }

                        .review-pros strong {
                            color: #2E7D32;
                            font-size: 14px;
                        }

                        .review-pros ul {
                            margin: 4px 0 0 20px;
                            padding: 0;
                            color: #2E7D32;
                            font-size: 14px;
                        }

                        .review-cons {
                            margin: 12px 0;
                            padding: 8px;
                            background-color: #FFEBEE;
                            border-radius: 4px;
                        }

                        .review-cons strong {
                            color: #C62828;
                            font-size: 14px;
                        }

                        .review-cons ul {
                            margin: 4px 0 0 20px;
                            padding: 0;
                            color: #C62828;
                            font-size: 14px;
                        }

                        .review-card-footer {
                            display: flex;
                            align-items: center;
                            gap: 12px;
                            margin-top: 12px;
                            padding-top: 12px;
                            border-top: 1px solid #CCDBDD;
                        }

                        .review-helpful-btn {
                            background: none;
                            border: none;
                            cursor: pointer;
                            color: #3BA0AC;
                            font-size: 14px;
                            padding: 0;
                            transition: color 0.2s;
                        }

                        .review-helpful-btn:hover {
                            color: #004A53;
                        }

                        .status-badge {
                            padding: 4px 8px;
                            border-radius: 4px;
                            font-size: 11px;
                            font-weight: 600;
                            margin-left: 8px;
                        }

                        .status-pending {
                            background-color: #fff3cd;
                            color: #856404;
                        }

                        .status-approved {
                            background-color: #d4edda;
                            color: #155724;
                        }

                        .status-rejected {
                            background-color: #f8d7da;
                            color: #721c24;
                        }
                    </style>

                </div>

            </div>

            <div class="chat-btn-circle">
                <i class="fa-solid fa-comment"></i>
            </div>

        </div>
    </main>
        <!-- API Clients -->
    <script>
let currentCourseId = null;
        let currentTermId = null;
        let currentTopicId = null;
        let allLessons = [];
        let allTerms = [];
        let allTopics = [];
        let topicsByTerm = {};

        /**
         * Show success notification
         */
        window.showSuccess = function(message) {
            if (window.ToastNotification) {
                window.ToastNotification.success('Success', message);
            } else {
                alert('Success: ' + message);
            }
        };

        /**
         * Show error notification
         */
        window.showError = function(message) {
            if (window.ToastNotification) {
                window.ToastNotification.error('Error', message);
            } else {
                alert('Error: ' + message);
            }
        };

        // Initialize page
        document.addEventListener('DOMContentLoaded', async () => {
            // Back button
            document.getElementById('back-btn').addEventListener('click', () => {
                window.history.back()
            });

            // Get course ID from URL or session
            const urlParams = new URLSearchParams(window.location.search);
            currentCourseId = urlParams.get('course_id') || sessionStorage.getItem('selectedCourseId');

            if (!currentCourseId) {
                ToastNotification.error('Course not found');
                return;
            }

            // Load course data and lessons
            await loadCourseData();
            await loadTerms();
            await loadTopics();
            await loadAllLessons();
            await loadLessons();
            await loadUserStats();
        });

        // Load course details
        async function loadCourseData() {
            try {
                const response = await CourseApiClient.getCourse(currentCourseId);
                if (response.success) {
                    const course = response.data;
                    document.title = course.title;

                    // Load subject includes
                    loadSubjectIncludes(course);
                }
            } catch (error) {
                console.error('Error loading course:', error);
                ToastNotification.error('Failed to load course data');
            }
        }

        // Load terms
        async function loadTerms() {
            try {
                const response = await CourseApiClient.getTerms();
                if (response.success && response.data) {
                    // Sort terms by order field
                    allTerms = response.data.sort((a, b) => (a.order || 0) - (b.order || 0));
                    renderTermButtons();

                    // Set first term as default
                    if (allTerms.length > 0) {
                        currentTermId = allTerms[0].id;
                    }
                }
            } catch (error) {
                console.error('Error loading terms:', error);
            }
        }

        // Load topics for the course
        async function loadTopics() {
            try {
                const response = await TopicApiClient.getTopicsByCourse(currentCourseId);
                if (response.success && response.data) {
                    allTopics = response.data;

                    // Group topics by term
                    topicsByTerm = {};
                    allTopics.forEach(topic => {
                        const termId = topic.term_id || 'no-term';
                        if (!topicsByTerm[termId]) {
                            topicsByTerm[termId] = [];
                        }
                        topicsByTerm[termId].push(topic);
                    });
                }
            } catch (error) {
                console.error('Error loading topics:', error);
            }
        }

        // Render term buttons
        function renderTermButtons() {
            const container = document.getElementById('termButtonsContainer');
            container.innerHTML = '';

            allTerms.forEach((term, index) => {
                const btn = document.createElement('button');
                btn.className = `term-btn ${index === 0 ? 'active' : ''}`;
                btn.textContent = term.name;
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.term-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    currentTermId = term.id;
                    // renderTopicButtons();
                    loadLessons();
                });
                container.appendChild(btn);
            });

            // Render topics for first term
            // renderTopicButtons();
        }

        // Render topic buttons for selected term
        function renderTopicButtons() {
            const container = document.getElementById('lessonsProgressContainer');
            container.innerHTML = '';

            const topicsForTerm = topicsByTerm[currentTermId] || [];

            if (topicsForTerm.length === 0) {
                container.innerHTML = '<p class="text-muted">No topics available for this term.</p>';
                return;
            }

            topicsForTerm.forEach((topic, index) => {
                const btn = document.createElement('button');
                btn.className = `btn btn-sm ${index === 0 ? 'btn-primary' : 'btn-outline-primary'} me-2`;
                btn.textContent = topic.title;
                btn.addEventListener('click', () => {
                    document.querySelectorAll('#lessonsProgressContainer button').forEach(b => {
                        b.classList.remove('btn-primary');
                        b.classList.add('btn-outline-primary');
                    });
                    btn.classList.remove('btn-outline-primary');
                    btn.classList.add('btn-primary');
                    currentTopicId = topic.id;
                    loadLessons();
                });
                container.appendChild(btn);
            });

            // Set first topic as default
            if (topicsForTerm.length > 0) {
                currentTopicId = topicsForTerm[0].id;
            }
        }

        // Load all lessons for the course
        async function loadAllLessons() {
            try {
                const response = await LessonApiClient.getLessonsByCourse(currentCourseId);
                if (response.success && response.data) {
                    allLessons = response.data;
                }
            } catch (error) {
                console.error('Error loading lessons:', error);
            }
        }

        // Load topics for selected term
        async function loadLessons() {
            try {
                // Get topics for the selected term
                const topicsForTerm = topicsByTerm[currentTermId] || [];
                renderLessons(topicsForTerm);
            } catch (error) {
                console.error('Error loading topics:', error);
                ToastNotification.error('Failed to load topics');
            }
        }

        // Load lessons for selected topic
        async function loadLessonsForTopic() {
            try {
                const response = await LessonApiClient.getLessonsByCourse(currentCourseId);
                if (response.success && response.data) {
                    allLessons = response.data;

                    // Filter by topic
                    const filteredLessons = currentTopicId
                        ? allLessons.filter(lesson => lesson.topic_id === currentTopicId)
                        : allLessons;

                    renderTopicLessons(filteredLessons);
                    renderLessonProgress(filteredLessons);
                }
            } catch (error) {
                console.error('Error loading lessons:', error);
                ToastNotification.error('Failed to load lessons');
            }
        }

        // Render topics for selected term
        function renderLessons(topics) {
            const container = document.getElementById('lessonsContainer');
            container.innerHTML = '';

            if (topics.length === 0) {
                container.innerHTML = '<p class="text-muted">No topics available for this term.</p>';
                return;
            }

            topics.forEach((topic, index) => {
                const topicEl = document.createElement('div');
                topicEl.className = 'lesson-item' + (index > 0 ? ' bg-white' : '');

                // Count lessons for this topic
                const lessonsCount = allLessons.filter(lesson => lesson.topic_id === topic.id).length;
                const lessonText = lessonsCount === 1 ? 'lesson' : 'lessons';

                topicEl.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class='w-100'>
                            <h5 class="${index > 0 ? 'text-dark' : ''}">Topic ${index + 1}. ${topic.title}
                            </h5>
                            <p class="${index > 0 ? 'text-dark' : ''}">
                                ${lessonsCount} ${lessonText} available
                            </p>
                            <hr style="border: 1px solid black">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div></div>
                        <button class="btn-lesson topic-btn" data-topic-id="${topic.id}"
                            style="${index > 0 ? 'background: #A3D8DF; color:#fff;' : ''}">
                            Go to Lessons
                        </button>
                    </div>
                `;

                container.appendChild(topicEl);
            });

            // Add event listeners to topic buttons
            document.querySelectorAll('.topic-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const topicId = e.target.dataset.topicId;
                    // Navigate to subjectdetails page for this topic
                    window.location.href = `/userlessondetails?topic_id=${topicId}`;
                });
            });
        }

        // Render lessons for selected topic
        function renderTopicLessons(lessons) {
            const container = document.getElementById('lessonsContainer');
            container.innerHTML = '';

            if (lessons.length === 0) {
                container.innerHTML = '<p class="text-muted">No lessons available for this topic.</p>';
                return;
            }

            lessons.forEach((lesson, index) => {
                const lessonEl = document.createElement('div');
                lessonEl.className = 'lesson-item' + (index > 0 ? ' bg-white' : '');

                const isCompleted = lesson.is_completed || false;
                const completedHomework = lesson.completed_assignments || 0;
                const totalHomework = lesson.total_assignments || 1;

                lessonEl.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="${index > 0 ? 'text-dark' : ''}">Lesson ${index + 1}. ${lesson.title}
                                ${isCompleted ? '<span class="float-end"><input type="checkbox" checked disabled></span>' : ''}
                            </h5>
                            <p class="${index > 0 ? 'text-dark' : ''}">
                                ${lesson.description || 'No description available'}
                            </p>
                            <hr style="border: 1px solid black">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-2">Completed Homework</span>
                            <span class="completed-badge ${index > 0 ? 'bg-dark text-white' : ''}">${completedHomework}/${totalHomework}</span>
                        </div>
                        <button class="btn-lesson lesson-btn" data-lesson-id="${lesson.id}"
                            style="${index > 0 ? 'background: #A3D8DF; color:#fff;' : ''}">
                            ${isCompleted ? 'Review Lesson' : 'Go to Lesson'}
                        </button>
                    </div>
                `;

                container.appendChild(lessonEl);
            });

            // Add event listeners to lesson buttons
            document.querySelectorAll('.lesson-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const lessonId = e.target.dataset.lessonId;
                    window.location.href = `/subjectdetails?lesson_id=${lessonId}`;
                });
            });
        }

        // Render lesson progress buttons
        function renderLessonProgress(lessons) {
            const container = document.getElementById('lessonsProgressContainer');
            container.innerHTML = '';

            lessons.forEach((lesson, index) => {
                const btn = document.createElement('button');
                const isCompleted = lesson.is_completed || false;
                btn.className = `btn btn-sm mx-1 text-nowrap rounded-0 ${isCompleted ? 'answered' : 'btn-outline-secondary unanswered'}`;
                btn.textContent = (index + 1).toString();
                btn.addEventListener('click', () => {
                    window.location.href = `/lessondetails?lesson_id=${lesson.id}`;
                });
                container.appendChild(btn);
            });
        }

        // Load user statistics
        async function loadUserStats() {
            try {
                const response = await UserApiClient.getLearningStats();
                if (response.success && response.data) {
                    const stats = response.data;

                    // Update rating in group
                    const groupRating = stats.group_ranking || 0;
                    document.getElementById('groupRating').textContent = groupRating + ' ';
                    document.getElementById('groupRatingBar').style.width = Math.min(groupRating * 10, 100) + '%';
                    document.getElementById('groupRatingBar').setAttribute('aria-valuenow', Math.min(groupRating * 10, 100));

                    // Update average point
                    const avgPoint = stats.average_score || 0;
                    document.getElementById('averagePoint').textContent = avgPoint + '%';
                    document.getElementById('averagePointBar').style.width = avgPoint + '%';
                    document.getElementById('averagePointBar').setAttribute('aria-valuenow', avgPoint);

                    // Update subject completed
                    const completed = stats.completion_percentage || 0;
                    document.getElementById('subjectCompleted').textContent = completed + '%';
                    document.getElementById('subjectCompletedBar').style.width = completed + '%';
                    document.getElementById('subjectCompletedBar').setAttribute('aria-valuenow', completed);
                }
            } catch (error) {
                console.error('Error loading user stats:', error);
            }
        }

        // Load subject includes
        function loadSubjectIncludes(course) {
            const list = document.getElementById('subjectIncludesList');
            list.innerHTML = '';

            const includes = [
                { icon: 'images/celebrate.png', text: `${course.duration_hours || 0} hours on demand videos` },
                { icon: 'images/celebrate.png', text: `${course.total_lessons || 0} lessons` },
                { icon: 'images/celebrate.png', text: 'Access on mobile and laptop' },
                { icon: 'images/celebrate.png', text: `${course.total_students || 0} students enrolled` },
            ];

            includes.forEach(item => {
                const li = document.createElement('li');
                li.className = 'mb-2 d-flex align-items-center gap-1';
                li.innerHTML = `<img src="${item.icon}" class="img-fluid" style="width:15px; aspect-ratio:1/1;"> ${item.text}`;
                list.appendChild(li);
            });

            // Load reviews after course data is loaded
            loadCourseReviews();

            // Check authentication and load review form
            checkAuthAndLoadReviewForm();
        }

        /**
         * Check if user is authenticated and load review form accordingly
         */
        function checkAuthAndLoadReviewForm() {
            const token = localStorage.getItem('auth_token');
            const container = document.getElementById('reviewFormContainer');

            if (token) {
                // User is authenticated, check if they have already reviewed
                checkUserReview();
            } else {
                // User is not authenticated
                container.innerHTML = `
                    <div class="alert alert-info mb-4">
                        <i class="fa-solid fa-info-circle"></i>
                        Please <a href="{{ route('login') }}">login</a> to leave a review.
                    </div>
                `;
            }
        }

        /**
         * Check if user has already reviewed this course
         */
        async function checkUserReview() {
            try {
                const token = localStorage.getItem('auth_token');
                const container = document.getElementById('reviewFormContainer');

                // If no token, don't make the API call
                if (!token) {
                    console.warn('No auth token found, skipping user review check');
                    loadReviewForm();
                    return;
                }

                const response = await fetch(`/api/reviews/my-reviews?course_id=${currentCourseId}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                // Handle non-200 responses
                if (!response.ok) {
                    console.warn('Failed to fetch user reviews:', response.status, response.statusText);
                    loadReviewForm();
                    return;
                }

                const result = await response.json();

                if (result.success && result.data && result.data.length > 0) {
                    // User has already reviewed this course
                    const userReview = result.data[0];
                    displayUserReview(userReview);
                } else {
                    // User hasn't reviewed yet, show the form
                    loadReviewForm();
                }
            } catch (error) {
                console.error('Error checking user review:', error);
                // If error, show the form anyway
                loadReviewForm();
            }
        }

        /**
         * Load and display the review form
         */
        function loadReviewForm() {
            const container = document.getElementById('reviewFormContainer');
            container.innerHTML = `
                <div class="review-form-container">
                    <form id="reviewForm" class="d-flex flex-column gap-3" data-ajax data-no-loader>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <!-- Rating Selection -->
                        <div class="form-group">
                            <label class="form-label">Rating</label>
                            <div class="rating-selector">
                                <input type="radio" name="rating" value="1" id="rating1" class="rating-input" required>
                                <label for="rating1" class="rating-label">
                                    <i class="fa-solid fa-star"></i>
                                </label>
                                <input type="radio" name="rating" value="2" id="rating2" class="rating-input" required>
                                <label for="rating2" class="rating-label">
                                    <i class="fa-solid fa-star"></i>
                                </label>
                                <input type="radio" name="rating" value="3" id="rating3" class="rating-input" required>
                                <label for="rating3" class="rating-label">
                                    <i class="fa-solid fa-star"></i>
                                </label>
                                <input type="radio" name="rating" value="4" id="rating4" class="rating-input" required>
                                <label for="rating4" class="rating-label">
                                    <i class="fa-solid fa-star"></i>
                                </label>
                                <input type="radio" name="rating" value="5" id="rating5" class="rating-input" required>
                                <label for="rating5" class="rating-label">
                                    <i class="fa-solid fa-star"></i>
                                </label>
                            </div>
                        </div>

                        <!-- Review Comment -->
                        <div class="form-group">
                            <label for="reviewComment" class="form-label">Your Review</label>
                            <textarea id="reviewComment" name="comment" class="form-control" placeholder="Share your thoughts about this course..." rows="4" maxlength="1000" required></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                </div>
            `;

            // Attach form submission handler and rating selection handler
            setTimeout(() => {
                const form = document.getElementById('reviewForm');
                if (form) {
                    form.addEventListener('submit', handleReviewFormSubmit);

                    // Add rating selection handlers
                    const ratingInputs = form.querySelectorAll('.rating-input');
                    const ratingLabels = form.querySelectorAll('.rating-label');

                    ratingLabels.forEach((label, index) => {
                        label.addEventListener('click', (e) => {
                            e.preventDefault();
                            const rating = index + 1;
                            ratingInputs[index].checked = true;
                            updateRatingVisuals(ratingInputs, rating);
                        });

                        label.addEventListener('mouseenter', () => {
                            const rating = index + 1;
                            updateRatingVisuals(ratingInputs, rating, true);
                        });
                    });

                    form.addEventListener('mouseleave', () => {
                        const checkedInput = form.querySelector('.rating-input:checked');
                        if (checkedInput) {
                            const rating = Array.from(ratingInputs).indexOf(checkedInput) + 1;
                            updateRatingVisuals(ratingInputs, rating);
                        } else {
                            ratingLabels.forEach(label => label.style.color = '#e5e6e7');
                        }
                    });
                }
            }, 100);
        }

        /**
         * Update rating visuals
         */
        function updateRatingVisuals(ratingInputs, rating, isHover = false) {
            const ratingLabels = Array.from(ratingInputs).map(input => input.nextElementSibling);
            ratingLabels.forEach((label, index) => {
                label.style.color = index < rating ? '#fdaf22' : '#e5e6e7';
            });
        }

        /**
         * Display user's existing review
         */
        function displayUserReview(review) {
            const container = document.getElementById('reviewFormContainer');
            const statusClass = `status-${review.status}`;
            const statusText = review.status.charAt(0).toUpperCase() + review.status.slice(1);

            container.innerHTML = `
                <div class="review-form-container">
                    <div class="alert alert-info mb-3">
                        <i class="fa-solid fa-check-circle"></i>
                        You have already reviewed this course
                    </div>
                    <div class="review-card">
                        <div class="review-card-header">
                            <div class="review-card-user">
                                <span class="review-card-name">Your Review</span>

                            </div>
                            <p class="review-card-date">${formatDate(review.created_at)}</p>
                        </div>
                        <div class="d-flex align-items-center gap-1 mb-2">
                            ${renderStars(review.rating)}
                        </div>
                        <p class="review-card-comment">${escapeHtml(review.comment || review.review)}</p>
                    </div>
                </div>
            `;
        }

        /**
         * Handle review form submission
         */
        async function handleReviewFormSubmit(e) {
            e.preventDefault();

            const courseId = document.querySelector('[data-course-id]')?.getAttribute('data-course-id');
            if (!courseId) {
                showError('Course ID not found');
                return;
            }

            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            const formData = new FormData(e.target);
            const data = {
                rating: formData.get('rating'),
                comment: formData.get('comment')
            };

            const token = localStorage.getItem('auth_token');

            try {
                const response = await fetch(`/api/courses/${courseId}/reviews`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (result.success) {
                    showSuccess('Review submitted successfully!');

                    // Force hide the loader since this is an AJAX request
                    if (window.kokokahLoader) {
                        window.kokokahLoader.forceHide();
                    }

                    // Reload the review form/display after a short delay
                    setTimeout(() => {
                        checkUserReview();
                        loadCourseReviews();
                    }, 1000);
                } else {
                    showError(result.message || 'Failed to submit review');
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;

                    // Force hide the loader on error
                    if (window.kokokahLoader) {
                        window.kokokahLoader.forceHide();
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Failed to submit review');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;

                // Force hide the loader on error
                if (window.kokokahLoader) {
                    window.kokokahLoader.forceHide();
                }
            }
        }

        /**
         * Load and display course reviews
         */
        async function loadCourseReviews() {
            try {
                if (!currentCourseId) {
                    console.warn('Course ID not found');
                    return;
                }

                const token = localStorage.getItem('auth_token');
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                };

                // Only add Authorization header if token exists
                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                // Fetch reviews
                const response = await fetch(`/api/courses/${currentCourseId}/reviews`, {
                    method: 'GET',
                    headers: headers
                });

                // Handle non-200 responses
                if (!response.ok) {
                    console.warn('Failed to load reviews:', response.status, response.statusText);
                    // Still try to display empty reviews
                    updateRatingDisplay({});
                    // displayReviews([]);
                    return;
                }

                const result = await response.json();

                if (!result.success) {
                    console.error('Failed to load reviews:', result.message);
                    updateRatingDisplay({});
                    // displayReviews([]);
                    return;
                }

                const data = result.data;
                const reviews = data.reviews.data || [];
                const stats = data.statistics || {};

                // Update rating display
                updateRatingDisplay(stats);

                // Display reviews
                // displayReviews(reviews);
            } catch (error) {
                console.error('Error loading reviews:', error);
                // Show empty state on error
                updateRatingDisplay({});
                // displayReviews([]);
            }
        }

        /**
         * Update rating statistics display
         */
        function updateRatingDisplay(stats) {
            const avgRating = stats.average_rating !== null && stats.average_rating !== undefined ? parseFloat(stats.average_rating) : 0;
            const totalReviews = stats.total_reviews || 0;

            // Update average rating
            document.getElementById('averageRating').textContent = avgRating.toFixed(1);
            document.getElementById('totalReviews').textContent = `${totalReviews} review${totalReviews !== 1 ? 's' : ''}`;

            // Update stars
            const starsContainer = document.getElementById('ratingStars');
            starsContainer.innerHTML = '';
            for (let i = 1; i <= 5; i++) {
                const star = document.createElement('i');
                star.className = i <= Math.round(avgRating) ? 'fa-solid fa-star' : 'fa-regular fa-star';
                star.style.color = i <= Math.round(avgRating) ? '#FFC107' : '#DDD';
                starsContainer.appendChild(star);
            }
        }

        /**
         * Display reviews in the container
         */
        function displayReviews(reviews) {
            const container = document.getElementById('reviewsContainer');

            if (reviews.length === 0) {
                container.innerHTML = '<p class="text-muted text-center">No reviews yet. Be the first to review!</p>';
                return;
            }

            container.innerHTML = reviews.map(review => {
                const userName = review.user?.name || 'Anonymous';
                const userAvatar = review.user?.avatar || 'https://via.placeholder.com/32';

                return `
                <article class="review-card">
                    <div class="review-card-header">
                        <div class="review-card-user">
                            <img src="${userAvatar}" alt="${escapeHtml(userName)}" class="review-card-avatar">
                            <span class="review-card-name">${escapeHtml(userName)}</span>

                        </div>
                        <p class="review-card-date">${formatDate(review.created_at)}</p>
                    </div>
                    <div class="d-flex align-items-center gap-1 mb-2">
                        ${renderStars(review.rating)}
                    </div>
                    ${review.title ? `<h6 class="review-card-title">${escapeHtml(review.title)}</h6>` : ''}
                    <p class="review-card-comment">${escapeHtml(review.comment || review.review)}</p>

                    ${review.pros && review.pros.length > 0 ? `
                        <div class="review-pros">
                            <strong>✓ Pros:</strong>
                            <ul>
                                ${review.pros.map(pro => `<li>${escapeHtml(pro)}</li>`).join('')}
                            </ul>
                        </div>
                    ` : ''}

                    ${review.cons && review.cons.length > 0 ? `
                        <div class="review-cons">
                            <strong>✗ Cons:</strong>
                            <ul>
                                ${review.cons.map(con => `<li>${escapeHtml(con)}</li>`).join('')}
                            </ul>
                        </div>
                    ` : ''}

                    <div class="review-card-footer">
                        <button class="review-helpful-btn" onclick="markReviewHelpful(${review.id})">
                            👍 Helpful (${review.helpful_count || 0})
                        </button>
                    </div>
                </article>
            `;
            }).join('');
        }

        /**
         * Escape HTML to prevent XSS
         */
        function escapeHtml(text) {
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

        /**
         * Render stars for a rating
         */
        function renderStars(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                const color = i <= rating ? '#fdaf22' : '#e5e6e7';
                stars += `<i class="fa-solid fa-star" style="color: ${color}; font-size: 14px;"></i>`;
            }
            return stars;
        }

        /**
         * Format date to readable format
         */
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        }

        /**
         * Mark a review as helpful
         */
        async function markReviewHelpful(reviewId) {
            try {
                const token = localStorage.getItem('auth_token');

                const response = await fetch(`/api/reviews/${reviewId}/helpful`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    showSuccess('Thank you for marking this review as helpful!');
                    loadCourseReviews(); // Reload reviews
                } else {
                    showError(result.message || 'Failed to mark review as helpful');
                }
            } catch (error) {
                console.error('Error marking review as helpful:', error);
                showError('Error marking review as helpful');
            }
        }

        // Reload reviews when a new review is submitted
        window.addEventListener('reviewSubmitted', () => {
            loadCourseReviews();
        });
    </script>
@endsection
