@extends('layouts.usertemplate')
@section('content')
    <!-- Get topic ID from route parameter -->
    @php
        $topicId = request()->route('topicId') ?? request()->query('topic_id');
    @endphp

    <style>
        .box {
            border: 1px solid #CCDBDD;
            padding: 20px;
        }

        .box-title {
            color: #000F11;
            font-size: 20px;
            font-weight: 600;
        }

        .box-progress-bar {
            background-color: #D9D9D9;
            border-radius: 4px;
            height: 6px;
            width: 208px;
        }

        .progress-track {
            background-color: #F56824;
            border-radius: 4px;
            height: 6px;
            width: 108px;
        }

        .video-box {
            border: 1px solid #CFD0D1;
            border-radius: 16px;
        }

        .lecture-box {
            border: 1px solid #000000;
            border-radius: 20px;
            padding: 20px
        }

        .lecture-download-btn {
            background-color: #D9D9D9;
            border-radius: 15px;
            padding: 19px 27px;
            color: #1C1D1D;
            font-size: 18px;
        }

        .lecture-text {
            font-size: 16px;
            color: #7F8285;
        }

        .mark-complete-btn {
            background-color: #3BA0AC;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 16px;
            color: #FFFFFF;
            font-weight: 600;
        }

        .nav-btn {
            color: #000F11;
            font-size: 20px;
        }

        .divider {
            height: 1px;
            width: 100%;
            background-color: #000000;
        }

        .mark-lesson-btn {
            color: #1C1D1D;
            font-size: 16px;
        }

        .message-box-container {
            border: 1px solid #004A53;
            border-radius: 15px;
            padding: 20px;
            min-height: 256px;
        }

        .message-box {
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 6px -2px #10182808, 0 12px 16px -4px #10182814;
            border-radius: 24px;
            padding: 16px;
            height: 144px;
            margin-top: auto;
        }

        .message-input {
            color: #475569;
            border: none;
            background-color: transparent;
            font-size: 16px;
        }

        .emoji {
            border: 1px solid #CBD5E1;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            padding: 16px
        }

        .send-message-btn {
            background-color: #FDAF22;
            padding: 10px 16px;
            border-radius: 50px;
            color: #000F11;
            font-size: 14px;
            font-weight: 700;
        }

        .star-rating {
            display: flex;
            gap: 8px;
            cursor: pointer;
        }

        .star-rating i {
            font-size: 30px;
            color: #d3d3d3;
            /* default gray */
            transition: color 0.2s;
        }

        .star-rating i.active {
            color: #f5b50a;
            /* gold */
        }

        .modal-title {
            color: #1C1D1D;
            font-size: 24px;
        }

        .modal-course {
            color: #1C1D1D;
            font-size: 16px;
        }

        .modal-review-btn {
            background-color: #FDAF22;
            padding: 16px 20px;
            border-radius: 4px;
            font-size: 16px;
            border: none;
            color: #000F11;
        }

        .modal-cancel-btn {
            background-color: transparent;
            padding: 16px 20px;
            border-radius: 4px;
            font-size: 16px;
            border: 1px solid #004A53;
            color: #004A53;
        }

        .submit-btn {
            border: 1px solid #004A53;
            padding: 16px 20px;
            color: #004A53;
            font-size: 16px;
            border-radius: 4px;
        }
    </style>
    <main class="py-4 px-3">
        <!-- Button trigger modal -->
        {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            Leave Review and Rating
        </button> --}}

        <!-- PDF Viewer Modal -->
        <div class="modal fade" id="pdfViewerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="pdfViewerLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pdfViewerLabel">PDF Viewer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <iframe id="pdfFrame" style="width: 100%; height: 600px; border: none;"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content d-flex flex-column gap-4">

                    <div class="modal-body d-flex flex-column gap-3 px-0">
                        <div class="d-flex flex-column gap-5">
                            <div class="d-flex flex-column gap-2 align-items-center">
                                <h2 class="modal-title">Rate this Subject</h2>
                                <h3 class="modal-course">English Language</h3>
                            </div>
                            <div class="d-flex align-items-center gap-3 justify-content-center">
                                <div class="star-rating">
                                    <i class="fa-solid fa-star" data-value="1"></i>
                                    <i class="fa-solid fa-star" data-value="2"></i>
                                    <i class="fa-solid fa-star" data-value="3"></i>
                                    <i class="fa-solid fa-star" data-value="4"></i>
                                    <i class="fa-solid fa-star" data-value="5"></i>
                                </div>
                                <p id="ratingOutput"></p>
                            </div>
                        </div>

                        <div class="modal-form-input-border">
                            <label for="exampleFormControlInput1" class="modal-label">Write your review</label>
                            <textarea name="" id="exampleFormControlInput1" class="modal-input" placeholder="your message..."></textarea>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-5">
                        <button type="button" class="modal-cancel-btn flex-fill w-100"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="modal-review-btn flex-fill w-100">Subject Review</button>
                    </div>
                </div>
            </div>
        </div>

        <section class="container-fluid d-flex flex-column gap-4">
            <h1 id="lessonTitle">Loading...</h1>
            <div class="row g-3">
                <div class="col-12">
                    <div class="d-flex align-items-center gap-2 justify-content-between box mb-4">
                        <h3 class="box-title" id="lessonProgress">Loading...</h3>
                        <div class="box-progress-bar">
                            <div class="progress-track" id="progressTrack"></div>
                        </div>
                    </div>
                    <div class="video-box mb-3" id="videoContainer">
                        <div class="d-flex justify-content-center align-items-center" style="height: 400px; background-color: #f0f0f0;">
                            <p>Loading video...</p>
                        </div>
                    </div>

                    <ul class="nav nav-underline nav-fill mb-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#" data-tab="material">Material &
                                Links</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-tab="quiz">Quiz</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-tab="ai-chat">Ai Chat</a>
                        </li>
                    </ul>
                    <div id="material" class="tab-content-section ">
                        <div class="lecture-box d-flex flex-column gap-3 mb-4">
                            <p class="lecture-text" id="lessonContent">Loading content...</p>
                            <div id="attachmentsContainer"></div>
                        </div>
                    </div>
                    <div id="quiz" class="tab-content-section d-none">
                        <div class="quiz-box lecture-box d-flex flex-column gap-3 mb-4" id="quizContainer">
                            <p class="text-center">Loading quizzes...</p>
                        </div>
                    </div>

                    <div id="ai-chat" class="tab-content-section d-none">
                            <div class="message-box-container d-flex align-items-end mb-4">
                                <div class="message-box w-100 d-flex flex-column gap-1">
                                    <div class="d-flex gap-2 align-items-start"><i class="fa-solid fa-paperclip"
                                            style="color:#94A3B8;"></i>
                                        <textarea class="message-input flex-fill" name="" id="" cols="" rows=""
                                            placeholder="Message to kodie..."></textarea>
                                    </div>

                                    <div class="d-flex align-items-center gap-3 justify-content-end mt-auto">
                                        <div class="emoji d-flex justify-content-center align-items-center"><i
                                                class="fa-solid fa-face-smile"></i></div>
                                        <button class="send-message-btn">Send</button>
                                    </div>

                                </div>
                        </div>

                    </div>
                    <div class="d-flex align-items-center gap-2 justify-content-between">
                        <button class="nav-btn" id="prevBtn" onclick="navigateToPreviousLesson()">Previous Lesson</button>
                        <button class="mark-complete-btn" id="markCompleteBtn" onclick="markLessonComplete()">Mark Lesson Complete</button>
                        <button class="nav-btn" id="nextBtn" onclick="navigateToNextLesson()">Next Lesson</button>
                    </div>

                </div>
                {{-- <div class="col-12 col-lg-6">
                    <div class="box d-flex flex-column gap-3 mb-4">
                        <h3 class="box-title">Lesson 2 of 15</h3>
                        <div class="box-progress-bar">
                            <div class="progress-track"></div>
                        </div>
                        <h4 class="box-title">25% completed</h4>
                        <div class="divider"></div>
                        <button class="d-flex gap-2 align-items-center mark-lesson-btn"><i
                                class="fa-solid fa-book"></i>Mark
                            Lesson Complete</button>
                    </div>

                </div> --}}
            </div>

        </section>

    </main>
    <script type="module">
        import LessonApiClient from '/js/api/lessonApiClient.js';
        import ToastNotification from '/js/utils/toastNotification.js';

        // Global variables
        let currentLesson = null;
        let currentTopic = null;
        let topicId = @if($topicId) {{ $topicId }} @else null @endif;
        let allLessons = [];
        let currentLessonIndex = 0;

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', async () => {
            // Get topic ID from URL if not set
            if (!topicId) {
                const urlParams = new URLSearchParams(window.location.search);
                topicId = urlParams.get('topic_id');
            }

            if (topicId) {
                await loadTopicLessons();
                if (allLessons.length > 0) {
                    currentLessonIndex = 0;
                    await loadCurrentLesson();
                    await loadQuizzes();
                    setupTabNavigation();
                    setupStarRating();
                } else {
                    showError('No lessons found for this topic');
                }
            } else {
                showError('No topic ID provided');
            }
        });

        /**
         * Load all lessons for the topic
         */
        async function loadTopicLessons() {
            try {
                const response = await LessonApiClient.getLessonsByTopic(topicId);

                if (response.success && response.data) {
                    allLessons = Array.isArray(response.data) ? response.data : [response.data];
                } else {
                    showError(response.message || 'Failed to load lessons');
                }
            } catch (error) {
                console.error('Error loading topic lessons:', error);
                showError('Error loading lessons');
            }
        }

        /**
         * Load current lesson data
         */
        async function loadCurrentLesson() {
            if (currentLessonIndex >= 0 && currentLessonIndex < allLessons.length) {
                currentLesson = allLessons[currentLessonIndex];
                currentTopic = currentLesson.topic;
                updateLessonUI();
                await loadLessonProgress();
            }
        }



        /**
         * Update lesson UI with data
         */
        function updateLessonUI() {
            // Update title
            document.getElementById('lessonTitle').textContent = `${currentTopic?.title || 'Topic'}: ${currentLesson.title}`;

            // Clear and update video
            const videoContainer = document.getElementById('videoContainer');
            if (currentLesson.video_url) {
                videoContainer.innerHTML = `
                    <video width="100%" height="400" controls style="border-radius: 16px;">
                        <source src="${currentLesson.video_url}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                `;
            } else {
                videoContainer.innerHTML = '<div class="d-flex justify-content-center align-items-center" style="height: 400px; background-color: #f0f0f0;"><p>No video available for this lesson</p></div>';
            }

            // Update content
            if (currentLesson.content) {
                document.getElementById('lessonContent').textContent = currentLesson.content;
            } else {
                document.getElementById('lessonContent').textContent = 'No content available for this lesson';
            }

            // Clear attachments container before loading new ones
            document.getElementById('attachmentsContainer').innerHTML = '';

            // Load attachments
            loadAttachments();

            // Update button states
            updateButtonStates();
        }

        /**
         * Load lesson progress
         */
        async function loadLessonProgress() {
            try {
                if (!currentLesson || !currentLesson.id) return;

                const response = await LessonApiClient.getLessonProgress(currentLesson.id);

                if (response.success) {
                    const progress = response.data;

                    // Update progress bar - calculate based on completed lessons
                    const completedLessons = allLessons.filter(lesson => lesson.is_completed).length;
                    const progressPercentage = (completedLessons / allLessons.length) * 100;
                    const progressTrack = document.getElementById('progressTrack');
                    progressTrack.style.width = progressPercentage + '%';

                    // Update lesson progress indicator
                    const lessonNumber = currentLessonIndex + 1;
                    const totalLessons = allLessons.length;
                    document.getElementById('lessonProgress').textContent =
                        `Lesson ${lessonNumber} of ${totalLessons}`;

                    // Update mark complete button
                    const markCompleteBtn = document.getElementById('markCompleteBtn');
                    if (progress.is_completed) {
                        markCompleteBtn.disabled = true;
                        markCompleteBtn.textContent = 'Lesson Completed ✓';
                        markCompleteBtn.style.opacity = '0.6';
                    } else {
                        markCompleteBtn.disabled = false;
                        markCompleteBtn.textContent = 'Mark Lesson Complete';
                        markCompleteBtn.style.opacity = '1';
                    }
                }
            } catch (error) {
                console.error('Error loading progress:', error);
            }
        }

        /**
         * Load attachments for the lesson
         */
        async function loadAttachments() {
            try {
                if (!currentLesson || !currentLesson.id) return;

                const attachmentsContainer = document.getElementById('attachmentsContainer');
                // Clear previous attachments
                attachmentsContainer.innerHTML = '';

                const response = await LessonApiClient.getLessonAttachments(currentLesson.id);

                if (response.success && response.data && response.data.length > 0) {
                    response.data.forEach(attachment => {
                        const btn = document.createElement('button');
                        btn.className = 'd-flex gap-3 align-items-center align-self-start lecture-download-btn';
                        btn.type = 'button';
                        btn.innerHTML = `
                            <i class="fa-solid fa-file-pdf"></i>
                            ${attachment.file_name || 'Attachment'}
                            <i class="fa-solid fa-eye"></i>
                        `;
                        btn.onclick = () => viewPDF(attachment.file_path, attachment.file_name);
                        attachmentsContainer.appendChild(btn);
                    });
                }
            } catch (error) {
                console.error('Error loading attachments:', error);
            }
        }

        /**
         * Update button states based on lesson position
         */
        function updateButtonStates() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            // Disable previous button on first lesson
            if (currentLessonIndex === 0) {
                prevBtn.disabled = true;
                prevBtn.style.opacity = '0.5';
                prevBtn.style.cursor = 'not-allowed';
            } else {
                prevBtn.disabled = false;
                prevBtn.style.opacity = '1';
                prevBtn.style.cursor = 'pointer';
            }

            // Disable next button on last lesson
            if (currentLessonIndex === allLessons.length - 1) {
                nextBtn.disabled = true;
                nextBtn.style.opacity = '0.5';
                nextBtn.style.cursor = 'not-allowed';
            } else {
                nextBtn.disabled = false;
                nextBtn.style.opacity = '1';
                nextBtn.style.cursor = 'pointer';
            }
        }

        /**
         * View PDF in modal
         */
        function viewPDF(filePath, fileName) {
            const pdfFrame = document.getElementById('pdfFrame');
            pdfFrame.src = filePath;
            document.getElementById('pdfViewerLabel').textContent = `PDF: ${fileName}`;
            const modal = new bootstrap.Modal(document.getElementById('pdfViewerModal'));
            modal.show();
        }

        /**
         * Load quizzes for the lesson
         */
        async function loadQuizzes() {
            try {
                if (!currentLesson || !currentLesson.id) {
                    document.getElementById('quizContainer').innerHTML =
                        '<p class="text-center text-muted">No lesson selected</p>';
                    return;
                }

                const response = await LessonApiClient.getQuizzesByLesson(currentLesson.id);

                if (response.success && response.data && response.data.length > 0) {
                    displayQuizzes(response.data);
                } else {
                    document.getElementById('quizContainer').innerHTML =
                        '<p class="text-center text-muted">No quizzes available for this lesson</p>';
                }
            } catch (error) {
                console.error('Error loading quizzes:', error);
                document.getElementById('quizContainer').innerHTML =
                    '<p class="text-center text-danger">Error loading quizzes</p>';
            }
        }

        /**
         * Display quizzes
         */
        function displayQuizzes(quizzes) {
            const quizContainer = document.getElementById('quizContainer');
            quizContainer.innerHTML = '';

            quizzes.forEach(quiz => {
                const quizDiv = document.createElement('div');
                quizDiv.className = 'd-flex flex-column gap-3 mb-4';
                quizDiv.innerHTML = `
                    <h4>${quiz.title}</h4>
                    <p>${quiz.description || 'No description'}</p>
                    <button class="submit-btn align-self-end" onclick="startQuiz(${quiz.id})">
                        Start Quiz
                    </button>
                `;
                quizContainer.appendChild(quizDiv);
            });
        }

        /**
         * Start quiz
         */
        window.startQuiz = async function(quizId) {
            try {
                const response = await LessonApiClient.startQuizAttempt(quizId);
                if (response.success) {
                    // Redirect to quiz page or open quiz modal
                    window.location.href = `/quiz/${quizId}`;
                } else {
                    showError(response.message || 'Failed to start quiz');
                }
            } catch (error) {
                console.error('Error starting quiz:', error);
                showError('Error starting quiz');
            }
        };

        /**
         * Mark lesson as complete
         */
        window.markLessonComplete = async function() {
            try {
                if (!currentLesson || !currentLesson.id) {
                    showError('Lesson data not loaded');
                    return;
                }

                const response = await LessonApiClient.markLessonComplete(currentLesson.id);

                if (response.success) {
                    // Update the lesson in allLessons array
                    const lessonIndex = allLessons.findIndex(lesson => lesson.id === currentLesson.id);
                    if (lessonIndex !== -1) {
                        allLessons[lessonIndex].is_completed = true;
                    }

                    // Update current lesson
                    currentLesson.is_completed = true;

                    const markCompleteBtn = document.getElementById('markCompleteBtn');
                    markCompleteBtn.disabled = true;
                    markCompleteBtn.textContent = 'Lesson Completed ✓';
                    markCompleteBtn.style.opacity = '0.6';

                    // Update progress bar
                    const completedLessons = allLessons.filter(lesson => lesson.is_completed).length;
                    const progressPercentage = (completedLessons / allLessons.length) * 100;
                    const progressTrack = document.getElementById('progressTrack');
                    progressTrack.style.width = progressPercentage + '%';

                    showSuccess('Lesson marked as complete!');
                } else {
                    showError(response.message || 'Failed to mark lesson complete');
                }
            } catch (error) {
                console.error('Error marking lesson complete:', error);
                showError('Error marking lesson complete');
            }
        };

        /**
         * Navigate to previous lesson
         */
        window.navigateToPreviousLesson = async function() {
            if (currentLessonIndex > 0) {
                currentLessonIndex--;
                await loadCurrentLesson();
                await loadQuizzes();
                window.scrollTo(0, 0);
            }
        };

        /**
         * Navigate to next lesson
         */
        window.navigateToNextLesson = async function() {
            if (currentLessonIndex < allLessons.length - 1) {
                currentLessonIndex++;
                await loadCurrentLesson();
                await loadQuizzes();
                window.scrollTo(0, 0);
            }
        };

        /**
         * Setup tab navigation
         */
        function setupTabNavigation() {
            const tabLinks = document.querySelectorAll('.nav-link');
            const tabSections = document.querySelectorAll('.tab-content-section');

            tabLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    tabLinks.forEach(l => l.classList.remove('active'));
                    link.classList.add('active');
                    tabSections.forEach(section => section.classList.add('d-none'));
                    const target = document.getElementById(link.dataset.tab);
                    if (target) target.classList.remove('d-none');
                });
            });
        }

        /**
         * Setup star rating
         */
        function setupStarRating() {
            const stars = document.querySelectorAll('.star-rating i');
            const output = document.getElementById('ratingOutput');
            let currentRating = 0;

            stars.forEach(star => {
                star.addEventListener('mouseover', () => {
                    const value = star.dataset.value;
                    highlightStars(value);
                });

                star.addEventListener('mouseout', () => {
                    highlightStars(currentRating);
                });

                star.addEventListener('click', () => {
                    currentRating = star.dataset.value;
                    highlightStars(currentRating);
                    output.textContent = `Rating: ${currentRating} / 5`;
                });
            });

            function highlightStars(limit) {
                stars.forEach(star => {
                    star.classList.toggle('active', star.dataset.value <= limit);
                });
            }
        }

        /**
         * Show error notification
         */
        window.showError = function(message) {
            ToastNotification.error('Error', message);
        };

        /**
         * Show success notification
         */
        window.showSuccess = function(message) {
            ToastNotification.success('Success', message);
        };
    </script>
@endsection
