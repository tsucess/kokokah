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

        <!-- File Viewer Modal (handles PDFs, Images, and Documents) -->
        <div class="modal fade" id="fileViewerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="fileViewerLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fileViewerLabel">File Viewer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="fileViewerBody" style="max-height: 70vh; overflow-y: auto;">
                        <!-- Content will be dynamically loaded here -->
                    </div>
                    <div class="modal-footer">
                        <a id="downloadFileBtn" href="#" download class="btn btn-primary">
                            <i class="fa-solid fa-download"></i> Download
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                        <div class="d-flex justify-content-center align-items-center"
                            style="height: 400px; background-color: #f0f0f0;">
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

                    <!-- Quiz Results Modal -->
                    <div class="modal fade" id="quizResultsModal" tabindex="-1" role="dialog"
                        aria-labelledby="quizResultsModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-light">
                                    <h5 class="modal-title" id="quizResultsModalLabel">📊 Quiz Results</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="quizResultsModalBody">
                                    <!-- Results will be populated here -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    {{-- <button type="button" class="btn btn-primary"
                                        onclick="window.retakeQuizFromModal()">Retake Quiz</button> --}}
                                </div>
                            </div>
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
                        <button class="nav-btn" id="prevBtn" onclick="navigateToPreviousLesson()">Previous
                            Lesson</button>
                        <button class="mark-complete-btn" id="markCompleteBtn" onclick="markLessonComplete()">Mark Lesson
                            Complete</button>
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


    <script>
        // Global variables
        let currentLesson = null;
        let currentTopic = null;
        let topicId =
            @if ($topicId)
                {{ $topicId }}
            @else
                null
            @endif ;
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
                const response = await window.LessonApiClient.getLessonsByTopic(topicId);

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
            document.getElementById('lessonTitle').textContent =
                `${currentTopic?.title[0].toUpperCase()+ currentTopic?.title.slice(1)|| 'Topic'}: ${currentLesson.title[0].toUpperCase()+ currentTopic?.title.slice(1)}`;

            // Clear and update video
            const videoContainer = document.getElementById('videoContainer');
            if (currentLesson.video_url) {
                videoContainer.innerHTML = `
                    <video width="100%" height="400" controls style="border-radius: 16px;">
                        <source src="${currentLesson.video_url}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                `;
                videoContainer.style.display = 'block';
            } else {
                videoContainer.style.display = 'none';
            }

            // Update content
            if (currentLesson.content) {
                document.getElementById('lessonContent').innerHTML = currentLesson.content;
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

                const response = await window.LessonApiClient.getLessonProgress(currentLesson.id);

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
         * Get file type icon based on file extension
         */
        function getFileIcon(fileName) {
            const ext = fileName.split('.').pop().toLowerCase();
            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
            const pdfExtensions = ['pdf'];
            const docExtensions = ['doc', 'docx', 'txt', 'rtf'];
            const sheetExtensions = ['xls', 'xlsx', 'csv'];
            const presentationExtensions = ['ppt', 'pptx'];

            if (imageExtensions.includes(ext)) return 'fa-image';
            if (pdfExtensions.includes(ext)) return 'fa-file-pdf';
            if (docExtensions.includes(ext)) return 'fa-file-word';
            if (sheetExtensions.includes(ext)) return 'fa-file-excel';
            if (presentationExtensions.includes(ext)) return 'fa-file-powerpoint';
            return 'fa-file';
        }

        /**
         * Get file type category
         */
        function getFileType(fileName) {
            const ext = fileName.split('.').pop().toLowerCase();
            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
            const pdfExtensions = ['pdf'];

            if (imageExtensions.includes(ext)) return 'image';
            if (pdfExtensions.includes(ext)) return 'pdf';
            return 'document';
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

                const response = await window.LessonApiClient.getLessonAttachments(currentLesson.id);

                if (response.success && response.data && response.data.length > 0) {
                    response.data.forEach(attachment => {
                        const btn = document.createElement('button');
                        btn.className = 'd-flex gap-3 align-items-center align-self-start lecture-download-btn';
                        btn.type = 'button';
                        const fileIcon = getFileIcon(attachment.file_name);
                        btn.innerHTML = `
                            <i class="fa-solid ${fileIcon}"></i>
                            ${attachment.file_name || 'Attachment'}
                            <i class="fa-solid fa-eye"></i>
                        `;
                        btn.onclick = () => viewFile(attachment.file_path, attachment.file_name);
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
         * View file in modal (handles PDFs, Images, and Documents)
         */
        function viewFile(filePath, fileName) {
            const fileType = getFileType(fileName);
            const fileViewerBody = document.getElementById('fileViewerBody');
            const fileViewerLabel = document.getElementById('fileViewerLabel');
            const downloadBtn = document.getElementById('downloadFileBtn');

            // Set download button
            downloadBtn.href = filePath;
            downloadBtn.download = fileName;

            // Clear previous content
            fileViewerBody.innerHTML = '';

            // Display content based on file type
            if (fileType === 'image') {
                // Display image
                const img = document.createElement('img');
                img.src = filePath;
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
                img.style.borderRadius = '8px';
                img.alt = fileName;
                fileViewerBody.appendChild(img);
                fileViewerLabel.textContent = `Image: ${fileName}`;
            } else if (fileType === 'pdf') {
                // Display PDF using iframe
                const iframe = document.createElement('iframe');
                iframe.src = filePath;
                iframe.style.width = '100%';
                iframe.style.height = '600px';
                iframe.style.border = 'none';
                fileViewerBody.appendChild(iframe);
                fileViewerLabel.textContent = `PDF: ${fileName}`;
            } else {
                // Display document (fallback for other document types)
                const docContainer = document.createElement('div');
                docContainer.className = 'alert alert-info';
                docContainer.innerHTML = `
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <i class="fa-solid ${getFileIcon(fileName)} fa-3x" style="color: #004A53;"></i>
                        </div>
                        <div>
                            <h5>${fileName}</h5>
                            <p class="text-muted mb-0">This document type cannot be previewed in the browser.</p>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Click the "Download" button below to download and view this file.</p>
                        </div>
                    </div>
                `;
                fileViewerBody.appendChild(docContainer);
                fileViewerLabel.textContent = `Document: ${fileName}`;
            }

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('fileViewerModal'));
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

                const quizContainer = document.getElementById('quizContainer');
                quizContainer.innerHTML = '<p class="text-center text-muted">Loading quizzes...</p>';

                const response = await window.LessonApiClient.getQuizzesByLesson(currentLesson.id);

                if (response.success && response.data && response.data.length > 0) {
                    // Load previous answers for each quiz
                    const previousAnswersMap = {};
                    const answeredQuizzes = new Set();

                    for (const quiz of response.data) {
                        try {
                            const resultsResponse = await window.QuizApiClient.getQuizResults(quiz.id);

                            // Check if response has results
                            if (resultsResponse.success && resultsResponse.data && resultsResponse.data.results) {
                                const results = resultsResponse.data.results;

                                if (Array.isArray(results) && results.length > 0) {
                                    // Get the latest attempt's answers
                                    const latestAttempt = results[results.length - 1];

                                    if (latestAttempt && latestAttempt.answers && latestAttempt.answers.length > 0) {
                                        previousAnswersMap[quiz.id] = {};
                                        latestAttempt.answers.forEach(answer => {
                                            // Use user_answer from the API response
                                            previousAnswersMap[quiz.id][answer.question_id] = answer
                                            .user_answer;
                                        });
                                        // Mark this quiz as answered
                                        answeredQuizzes.add(quiz.id);
                                    }
                                }
                            }
                        } catch (error) {
                            console.error('Error loading quiz results for quiz ' + quiz.id + ':', error);
                        }
                    }

                    displayQuizzes(response.data, previousAnswersMap, answeredQuizzes);
                } else {
                    quizContainer.innerHTML =
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
        function displayQuizzes(quizzes, previousAnswersMap = {}, answeredQuizzes = new Set(), retakingQuizId = null) {
            const quizContainer = document.getElementById('quizContainer');
            quizContainer.innerHTML = '';

            if (!quizzes || quizzes.length === 0) {
                quizContainer.innerHTML = '<p class="text-center text-muted">No quizzes available for this lesson</p>';
                return;
            }

            quizzes.forEach((quiz, quizIndex) => {
                const quizDiv = document.createElement('div');
                quizDiv.className = 'd-flex flex-column gap-4 mb-5 p-4 border rounded';
                quizDiv.style.backgroundColor = '#f9f9f9';
                quizDiv.setAttribute('data-quiz-id', quiz.id);

                // Calculate next attempt number
                const currentAttempts = quiz.attempts || 0;
                const maxAttempts = quiz.max_attempts || 3;
                const nextAttemptNumber = currentAttempts + 1;
                const canAttempt = quiz.can_attempt !== false;

                quizDiv.setAttribute('data-attempt-number', nextAttemptNumber);
                quizDiv.setAttribute('data-max-attempts', maxAttempts);
                quizDiv.setAttribute('data-current-attempts', currentAttempts);
                quizDiv.setAttribute('data-can-attempt', canAttempt ? 'true' : 'false');
                quizDiv.setAttribute('data-answered', answeredQuizzes.has(quiz.id) ? 'true' : 'false');

                // Quiz header with attempt info
                let quizHTML = `
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h4 class="mb-2">${quiz.title || 'Untitled Quiz'}</h4>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block">Attempts: <strong>${currentAttempts}/${maxAttempts}</strong></small>
                                ${!canAttempt ? '<small class="text-danger d-block">Max attempts reached</small>' : ''}
                            </div>
                        </div>
                    </div>
                `;

                // Display questions
                if (quiz.questions && quiz.questions.length > 0) {
                    quizHTML += '<div class="questions-container">';

                    quiz.questions.forEach((question, questionIndex) => {
                        const questionId = `question_${quiz.id}_${question.id}`;
                        const quizAnswers = previousAnswersMap[quiz.id] || {};
                        const previousAnswer = quizAnswers[question.id];
                        const isAnswered = answeredQuizzes.has(quiz.id);
                        // If this quiz is being retaken, don't disable it and don't pre-populate answers
                        const isRetaking = retakingQuizId === quiz.id;
                        const disabledAttr = (isAnswered && !isRetaking) ? 'disabled' : '';
                        const shouldPrePopulate = !isRetaking;

                        quizHTML += `
                            <div class="question-item mb-4 p-3 bg-white rounded" data-question-id="${question.id}">
                                <h6 class="mb-3">
                                    <strong>Question ${questionIndex + 1}:</strong> ${question.question_text || 'Untitled Question'}
                                </h6>
                        `;

                        // Display options based on question type
                        if (question.type === 'mcq' || question.type === 'multiple_choice' || question
                            .type === 'alternate') {
                            // Handle options - they might be a string (JSON) or already an array
                            let options = question.options;
                            if (typeof options === 'string') {
                                try {
                                    options = JSON.parse(options);
                                } catch (e) {
                                    console.error('Failed to parse options:', options);
                                    options = null;
                                }
                            }

                            if (options && Array.isArray(options) && options.length > 0) {
                                quizHTML += '<div class="options-container">';
                                options.forEach((option, optionIndex) => {
                                    const optionId = `${questionId}_option_${optionIndex}`;
                                    const isChecked = shouldPrePopulate && previousAnswer &&
                                        previousAnswer === option ? 'checked' : '';
                                    quizHTML += `
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="${questionId}" id="${optionId}" value="${option}" ${isChecked} ${disabledAttr}>
                                            <label class="form-check-label" for="${optionId}">
                                                ${option}
                                            </label>
                                        </div>
                                    `;
                                });
                                quizHTML += '</div>';
                            } else {
                                quizHTML +=
                                    '<p class="text-muted">No options available for this question</p>';
                            }
                        } else if (question.type === 'true_false') {
                            const isTrueChecked = shouldPrePopulate && previousAnswer && previousAnswer ===
                                'true' ? 'checked' : '';
                            const isFalseChecked = shouldPrePopulate && previousAnswer && previousAnswer ===
                                'false' ? 'checked' : '';
                            quizHTML += `
                                <div class="options-container">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="${questionId}" id="${questionId}_true" value="true" ${isTrueChecked} ${disabledAttr}>
                                        <label class="form-check-label" for="${questionId}_true">
                                            True
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="${questionId}" id="${questionId}_false" value="false" ${isFalseChecked} ${disabledAttr}>
                                        <label class="form-check-label" for="${questionId}_false">
                                            False
                                        </label>
                                    </div>
                                </div>
                            `;
                        } else {
                            // Fallback for unknown types - treat as essay
                            quizHTML += `
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="${questionId}" id="${optionId}" value="${option}" ${disabledAttr}>
                                            <label class="form-check-label" for="${optionId}">
                                                ${option}
                                            </label>
                                        </div>
                                    `;
                        }

                        quizHTML += '</div>';
                    });

                    quizHTML += '</div>';
                } else {
                    quizHTML += '<p class="text-muted">No questions available for this quiz</p>';
                }

                // Submit button logic
                const isAnswered = answeredQuizzes.has(quiz.id);
                const canAttemptQuiz = quiz.can_attempt !== false;
                const maxAttemptsReached = !canAttemptQuiz;
                const isRetaking = retakingQuizId === quiz.id;

                let buttonDisabled = '';
                let buttonClass = 'submit-btn align-self-end';
                let buttonText = 'Submit Quiz';

                if (maxAttemptsReached) {
                    buttonDisabled = 'disabled';
                    buttonClass += ' opacity-50';
                    buttonText = 'Max Attempts Reached';
                } else if (isAnswered && !isRetaking) {
                    buttonDisabled = 'disabled';
                    buttonClass += ' opacity-50';
                    buttonText = 'Quiz Already Submitted';
                }

                // Check if this is the last quiz
                const isLastQuiz = quizIndex === quizzes.length - 1;

                quizHTML += `
                    <div class="d-flex gap-2 align-self-end">
                        <button class="${buttonClass}" onclick="window.submitQuiz(${quiz.id})" ${buttonDisabled}>
                            ${buttonText}
                        </button>
                        ${isLastQuiz && isAnswered && !isRetaking ? `
                                <button class="btn btn-success" onclick="window.showAllQuizzesResultsModal()">
                                    📊 Show Results
                                </button>
                            ` : ''}
                    </div>
                `;

                // ${
                //  isAnswered && canAttemptQuiz && !isRetaking ? `
            //     <button class="btn btn-warning" onclick="window.retakeQuiz(${quiz.id})">
            //         🔄 Retake Quiz
            //     </button>
            // ` : ''
                // }

                quizDiv.innerHTML = quizHTML;
                quizContainer.appendChild(quizDiv);
            });
        }

        /**
         * Submit quiz answers
         */
        window.submitQuiz = async function(quizId) {
            try {
                // Get all questions for this quiz
                const quizContainer = document.getElementById('quizContainer');
                const questionElements = quizContainer.querySelectorAll('.question-item');

                // Validate all questions are answered
                let allAnswered = true;
                questionElements.forEach((questionEl) => {
                    const inputs = questionEl.querySelectorAll('input[type="radio"], textarea');
                    let answered = false;

                    inputs.forEach(input => {
                        if (input.type === 'radio' && input.checked) {
                            answered = true;
                        } else if (input.tagName === 'TEXTAREA' && input.value.trim()) {
                            answered = true;
                        }
                    });

                    if (!answered) {
                        allAnswered = false;
                    }
                });

                if (!allAnswered) {
                    showError('Please answer all questions before submitting');
                    return;
                }

                // Collect answers in the format expected by backend
                const answers = [];
                questionElements.forEach((questionEl) => {
                    // Get question ID from data attribute
                    const questionId = parseInt(questionEl.getAttribute('data-question-id'));
                    const inputs = questionEl.querySelectorAll('input[type="radio"], textarea');
                    let answerValue = null;

                    inputs.forEach(input => {
                        // Get the answer value
                        if (input.type === 'radio' && input.checked) {
                            answerValue = input.value;
                            console.log('Found checked radio for question', questionId, ':',
                                answerValue);
                        } else if (input.tagName === 'TEXTAREA' && input.value.trim()) {
                            answerValue = input.value;
                        }
                    });

                    if (questionId && answerValue) {
                        console.log('Adding answer - questionId:', questionId, 'answerValue:', answerValue,
                            'type:', typeof answerValue);
                        answers.push({
                            question_id: questionId,
                            answer: answerValue
                        });
                    }
                });

                // Get the current attempt number from the quiz div
                const quizDiv = quizContainer.querySelector(`[data-quiz-id="${quizId}"]`);
                let attemptNumber = 1;
                let maxAttempts = 3;
                let currentAttempts = 0;

                if (quizDiv) {
                    attemptNumber = parseInt(quizDiv.getAttribute('data-attempt-number')) || 1;
                    maxAttempts = parseInt(quizDiv.getAttribute('data-max-attempts')) || 3;
                    currentAttempts = parseInt(quizDiv.getAttribute('data-current-attempts')) || 0;
                }

                // Get quiz title
                const quizTitle = quizDiv ? quizDiv.querySelector('h4')?.textContent || 'Quiz' : 'Quiz';

                console.log('=== QUIZ SUBMISSION INFO ===');
                console.log('quizId:', quizId, 'attemptNumber:', attemptNumber, 'currentAttempts:', currentAttempts,
                    'maxAttempts:', maxAttempts);

                // Submit quiz with the current attempt number
                console.log('=== SUBMITTING QUIZ ===');
                console.log('quizId:', quizId, 'attemptNumber:', attemptNumber, 'answers:', answers);
                const response = await window.LessonApiClient.submitQuiz(quizId, {
                    attempt_number: attemptNumber,
                    answers: answers
                });

                console.log('=== QUIZ SUBMISSION RESPONSE ===');
                console.log('response:', response);
                console.log('response.success:', response.success);

                if (response.success) {
                    showSuccess('Quiz submitted successfully!');
                    console.log('Quiz submitted - quizId:', quizId, 'response.data:', response.data);

                    // Redirect to result page after a short delay
                    setTimeout(() => {
                        const resultUrl = `/userresult?quiz_id=${quizId}`;
                        console.log('Redirecting to:', resultUrl);
                        window.location.href = resultUrl;
                    }, 1500);
                } else {
                    console.error('Quiz submission failed:', response.message);
                    showError(response.message || 'Failed to submit quiz');
                }
            } catch (error) {
                console.error('Error submitting quiz:', error);
                showError('Error submitting quiz');
            }
        };



        /**
         * Retake quiz - reload quiz for another attempt
         */
        window.retakeQuiz = async function(quizId) {
            try {
                const quizContainer = document.getElementById('quizContainer');
                const quizDiv = quizContainer.querySelector(`[data-quiz-id="${quizId}"]`);

                if (!quizDiv) {
                    showError('Quiz not found');
                    return;
                }

                // Get attempt info
                const maxAttempts = parseInt(quizDiv.getAttribute('data-max-attempts')) || 3;
                const currentAttempts = parseInt(quizDiv.getAttribute('data-current-attempts')) || 0;
                const nextAttemptNumber = currentAttempts + 1;

                // Check if user can attempt
                if (nextAttemptNumber > maxAttempts) {
                    showError(`You have reached the maximum number of attempts (${maxAttempts})`);
                    return;
                }

                console.log('Retaking quiz:', quizId, 'Attempt:', nextAttemptNumber, 'of', maxAttempts);

                // Clear the results and reload the quiz
                quizDiv.innerHTML =
                    '<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>';

                // Reload quizzes with the retaking quiz ID
                await loadQuizzesForRetake(quizId);

                // Scroll to the quiz after reload
                setTimeout(() => {
                    const updatedQuizDiv = quizContainer.querySelector(`[data-quiz-id="${quizId}"]`);
                    if (updatedQuizDiv) {
                        updatedQuizDiv.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }, 300);

                showSuccess(`Quiz reloaded. Attempt ${nextAttemptNumber} of ${maxAttempts}`);
            } catch (error) {
                console.error('Error retaking quiz:', error);
                showError('Error reloading quiz');
            }
        };

        /**
         * Load quizzes for retake - passes the retaking quiz ID to displayQuizzes
         */
        async function loadQuizzesForRetake(retakingQuizId) {
            try {
                if (!currentLesson || !currentLesson.id) {
                    console.error('No lesson selected');
                    return;
                }

                const response = await window.LessonApiClient.getQuizzesByLesson(currentLesson.id);

                if (response.success && response.data) {
                    // Load previous answers for all quizzes
                    const previousAnswersMap = {};
                    const answeredQuizzes = new Set();

                    for (const quiz of response.data) {
                        try {
                            const resultsResponse = await window.QuizApiClient.getQuizResults(quiz.id);

                            if (resultsResponse.success && resultsResponse.data && resultsResponse.data.results) {
                                const results = resultsResponse.data.results;

                                if (Array.isArray(results) && results.length > 0) {
                                    const latestAttempt = results[results.length - 1];

                                    if (latestAttempt && latestAttempt.answers && latestAttempt.answers.length > 0) {
                                        previousAnswersMap[quiz.id] = {};
                                        latestAttempt.answers.forEach(answer => {
                                            previousAnswersMap[quiz.id][answer.question_id] = answer
                                            .user_answer;
                                        });
                                        answeredQuizzes.add(quiz.id);
                                    }
                                }
                            }
                        } catch (error) {
                            console.error('Error loading quiz results for quiz ' + quiz.id + ':', error);
                        }
                    }

                    // Display quizzes with the retaking quiz ID
                    displayQuizzes(response.data, previousAnswersMap, answeredQuizzes, retakingQuizId);
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
         * Mark lesson as complete
         */
        window.markLessonComplete = async function() {
            try {
                if (!currentLesson || !currentLesson.id) {
                    showError('Lesson data not loaded');
                    return;
                }

                const response = await window.LessonApiClient.markLessonComplete(currentLesson.id);

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
         * Show error notification
         */
        window.showError = function(message) {
            window.ToastNotification.error('Error', message);
        };

        /**
         * Show success notification
         */
        window.showSuccess = function(message) {
            window.ToastNotification.success('Success', message);
        };

        /**
         * Store quiz results for modal display (initialized at module level)
         */

        /**
         * Show all quizzes results combined in modal
         */
        window.showAllQuizzesResultsModal = async function() {
            try {
                console.log('=== SHOWING ALL QUIZZES RESULTS MODAL ===');
                console.log('currentLesson:', currentLesson);

                // Get all quizzes for the current lesson
                if (!currentLesson || !currentLesson.id) {
                    console.error('No lesson selected. currentLesson:', currentLesson);
                    showError('No lesson selected');
                    return;
                }

                console.log('Fetching quizzes for lesson:', currentLesson.id);
                const quizzesResponse = await window.LessonApiClient.getQuizzesByLesson(currentLesson.id);
                console.log('Quizzes response:', quizzesResponse);

                if (!quizzesResponse.success || !quizzesResponse.data) {
                    console.error('Failed to load quizzes:', quizzesResponse);
                    showError('Failed to load quizzes');
                    return;
                }

                const quizzes = quizzesResponse.data;
                console.log('Loaded quizzes:', quizzes);

                // Fetch results for each quiz
                let allQuizResults = [];
                for (const quiz of quizzes) {
                    try {
                        console.log('Fetching results for quiz:', quiz.id);
                        const resultsResponse = await window.QuizApiClient.getQuizResults(quiz.id);
                        console.log('Results response for quiz ' + quiz.id + ':', resultsResponse);

                        if (resultsResponse.success && resultsResponse.data && resultsResponse.data.results) {
                            allQuizResults.push({
                                quiz_id: quiz.id,
                                quiz_title: quiz.title,
                                results: resultsResponse.data.results
                            });
                        }
                    } catch (error) {
                        console.error('Error fetching results for quiz ' + quiz.id + ':', error);
                    }
                }

                console.log('All quiz results:', allQuizResults);

                if (allQuizResults.length === 0) {
                    console.error('=== NO QUIZ RESULTS FOUND ===');
                    showError('No quiz results found. Please submit all quizzes first.');
                    return;
                }

                // Calculate combined results from fetched data
                let totalScore = 0;
                let totalMaxScore = 0;
                let allResults = [];
                let quizTitles = [];

                allQuizResults.forEach(quizResult => {
                    quizTitles.push(quizResult.quiz_title);

                    // Get the latest attempt results
                    if (quizResult.results && quizResult.results.length > 0) {
                        const latestAttempt = quizResult.results[quizResult.results.length - 1];
                        if (latestAttempt && latestAttempt.answers) {
                            latestAttempt.answers.forEach(answer => {
                                totalScore += answer.points_earned || 0;
                                totalMaxScore += answer.points_possible || 0;
                                allResults.push({
                                    quiz_title: quizResult.quiz_title,
                                    question_id: answer.question_id,
                                    question_text: answer.question_text,
                                    user_answer: answer.user_answer,
                                    correct_answer: answer.correct_answer,
                                    points_earned: answer.points_earned,
                                    points_possible: answer.points_possible,
                                    is_correct: answer.is_correct,
                                    explanation: answer.explanation
                                });
                            });
                        }
                    }
                });

                const overallPercentage = totalMaxScore > 0 ? Math.round((totalScore / totalMaxScore) * 100) : 0;
                const passed = overallPercentage >= 60; // Assuming 60% is passing

                const modalBody = document.getElementById('quizResultsModalBody');
                const resultsModalElement = document.getElementById('quizResultsModal');
                console.log('Modal body element:', modalBody);

                if (!modalBody) {
                    console.error('Modal body element not found');
                    showError('Error: Modal body not found');
                    return;
                }

                // Store quiz IDs in the modal for retake functionality
                const quizIds = allQuizResults.map(qr => qr.quiz_id);
                if (resultsModalElement) {
                    resultsModalElement.setAttribute('data-quiz-ids', JSON.stringify(quizIds));
                }

                let resultsHTML = `
                    <div class="quiz-results-modal">
                        <div class="p-3 border rounded bg-light mb-4">
                            <h5 class="mb-3">📊 Complete Quiz Results</h5>
                            <p class="text-muted mb-3"><small>Quizzes: ${quizTitles.join(', ')}</small></p>

                            <div class="results-summary">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="result-card text-center p-3 bg-white rounded border">
                                            <h6 class="text-muted mb-2">Total Score</h6>
                                            <h3 class="text-primary mb-0">${totalScore}/${totalMaxScore}</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="result-card text-center p-3 bg-white rounded border">
                                            <h6 class="text-muted mb-2">Overall Percentage</h6>
                                            <h3 class="text-primary mb-0">${overallPercentage}%</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="result-card text-center p-3 bg-white rounded border">
                                            <h6 class="text-muted mb-2">Status</h6>
                                            <h3 class="mb-0" style="color: ${passed ? '#28a745' : '#dc3545'}">
                                                ${passed ? '✓ PASSED' : '✗ FAILED'}
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="result-card text-center p-3 bg-white rounded border">
                                            <h6 class="text-muted mb-2">Total Questions</h6>
                                            <h3 class="text-primary mb-0">${allResults.length}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6 class="mb-3 font-weight-bold">📝 All Questions & Answers</h6>
                        <div class="results-details" style="max-height: 500px; overflow-y: auto;">
                `;

                // Display each question with answers
                allResults.forEach((result, index) => {
                    const resultClass = result.is_correct ? 'text-success' : 'text-danger';
                    const resultIcon = result.is_correct ? '✓' : '✗';

                    resultsHTML += `
                        <div class="question-item mb-3 p-3 bg-light rounded" style="border-left: 4px solid ${result.is_correct ? '#28a745' : '#dc3545'}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1">
                                        <strong>Q${index + 1}:</strong> ${result.question_text || ''}
                                    </h6>
                                    <small class="text-muted">${result.quiz_title}</small>
                                </div>
                                <span class="${resultClass} font-weight-bold">${resultIcon} ${result.points_earned}/${result.points_possible} pts</span>
                            </div>

                            <div class="mb-2">
                                <small class="text-muted"><strong>Your Answer:</strong></small>
                                <div class="p-2 bg-white rounded mt-1" style="border-left: 3px solid ${result.is_correct ? '#28a745' : '#dc3545'}">
                                    <small style="color: ${result.is_correct ? '#28a745' : '#dc3545'}; font-weight: bold;">
                                        ${result.user_answer || 'No answer'}
                                    </small>
                                </div>
                            </div>

                            ${!result.is_correct ? `
                                    <div class="mb-2">
                                        <small class="text-muted"><strong>✓ Correct Answer:</strong></small>
                                        <div class="p-2 bg-white rounded mt-1" style="border-left: 3px solid #28a745">
                                            <small style="color: #28a745; font-weight: bold;">
                                                ${result.correct_answer}
                                            </small>
                                        </div>
                                    </div>
                                ` : ''}

                            ${result.explanation ? `
                                    <div class="mt-2 p-2 bg-light rounded">
                                        <small><strong>💡 Explanation:</strong></small>
                                        <p class="mb-0 text-muted mt-1" style="font-size: 0.85rem;">${result.explanation}</p>
                                    </div>
                                ` : ''}
                        </div>
                    `;
                });

                resultsHTML += `
                        </div>
                    </div>
                `;

                modalBody.innerHTML = resultsHTML;

                // Show modal using Bootstrap's modal API
                if (resultsModalElement) {
                    const modal = new bootstrap.Modal(resultsModalElement);
                    modal.show();
                } else {
                    console.error('Modal element not found');
                    showError('Error displaying modal');
                }
            } catch (error) {
                console.error('Error showing all quizzes results modal:', error);
                showError('Error displaying quiz results');
            }
        };

        /**
         * Retake quiz from modal
         */
        window.retakeQuizFromModal = function() {
            // Get the modal element
            const modalElement = document.getElementById('quizResultsModal');

            if (!modalElement) {
                showError('Modal not found');
                return;
            }

            // Close the modal first
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }

            // Get the first quiz ID to retake (for now, retake all quizzes)
            // In the future, you might want to show a dialog to select which quiz to retake
            const quizIdsStr = modalElement.getAttribute('data-quiz-ids');
            let quizIds = [];
            try {
                quizIds = JSON.parse(quizIdsStr);
            } catch (e) {
                console.error('Error parsing quiz IDs:', e);
            }

            if (quizIds.length === 0) {
                showError('No quizzes found to retake');
                return;
            }

            // Reload quizzes to allow retake - pass the first quiz ID as the retaking quiz
            loadQuizzesForRetake(quizIds[0]).then(() => {
                showSuccess('Quizzes reloaded. You can now retake them!');
            }).catch(error => {
                console.error('Error reloading quizzes:', error);
                showError('Error reloading quizzes');
            });
        };


    </script>
@endsection
