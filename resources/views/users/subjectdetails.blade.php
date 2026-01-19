@extends('layouts.usertemplate')
@section('content')
    <!-- Get topic ID from route parameter -->
    @php
        $topicId = request()->route('topicId') ?? request()->query('topic_id');
    @endphp

    <!-- PDF.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        // Set up PDF.js worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    </script>

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
            pointer-events: auto;
        }

        .video-box iframe {
            pointer-events: auto;
            display: block;
        }

        .video-box video {
            pointer-events: auto;
            display: block;
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

        #lessonTitle {
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }
        @media screen and (max-width:768px){
            .nav-btn{
                font-size: 16px;
            }
            .mark-complete-btn{
                padding: 7px 10px;
            }
            .box-title{
                font-size: 18px;
            }

        }
    </style>
    <main class="py-4 px-3">
        <!-- Button trigger modal -->
        {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            Leave Review and Rating
        </button> --}}

        <!-- File Viewer Modal (handles PDFs, Images, Videos, and Documents) -->
        <div class="modal fade" id="fileViewerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="fileViewerLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 80vw;">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="fileViewerLabel">File Viewer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="fileViewerBody" style="max-height: 80vh; overflow-y: auto; padding: 2rem;">
                        <!-- Content will be dynamically loaded here -->
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>



        <section class="container-fluid d-flex flex-column gap-4">
            <h1 id="lessonTitle">Loading...</h1>
            <div class="row g-3">
                <div class="col-12">
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-2 justify-content-between box mb-4">
                        <h3 class="box-title" id="lessonProgress">Loading...</h3>
                        <div class="box-progress-bar">
                            <div class="progress-track" id="progressTrack"></div>
                        </div>
                    </div>
                    <div class="video-box mb-3" id="videoContainer" data-no-loader>
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
                        <button class="nav-btn" id="prevBtn" onclick="navigateToPreviousLesson()">Previous</button>
                        <button class="mark-complete-btn" id="markCompleteBtn" onclick="markLessonComplete()">Mark
                            Complete</button>
                        <button class="nav-btn" id="nextBtn" onclick="navigateToNextLesson()">Next</button>
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
        let isMarkingComplete = false; // Prevent double-click on mark complete button
        let isNavigating = false; // Prevent double-click on navigation buttons

        /**
         * Check if a URL is a YouTube URL
         */
        function isYouTubeUrl(url) {
            if (!url || typeof url !== 'string') return false;
            return /^(https?:\/\/)?(www\.)?(youtube|youtu|youtube-nocookie)\.(com|be)\//.test(url);
        }

        /**
         * Extract YouTube video ID from various YouTube URL formats
         */
        function extractYouTubeId(url) {
            if (!url || typeof url !== 'string') return null;

            // Handle different YouTube URL formats
            let videoId = null;

            // Format: https://www.youtube.com/watch?v=VIDEO_ID
            let match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/);
            if (match && match[1]) {
                videoId = match[1];
            }

            // Format: https://www.youtube.com/embed/VIDEO_ID
            if (!videoId) {
                match = url.match(/youtube\.com\/embed\/([^&\n?#]+)/);
                if (match && match[1]) {
                    videoId = match[1];
                }
            }

            // Format: https://youtu.be/VIDEO_ID
            if (!videoId) {
                match = url.match(/youtu\.be\/([^&\n?#]+)/);
                if (match && match[1]) {
                    videoId = match[1];
                }
            }

            return videoId;
        }

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
                    console.error('No lessons found for topic:', topicId);
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
                    const errorMsg = response.message || 'Failed to load lessons';
                    console.error('API returned error:', errorMsg);
                    console.error('Full response:', JSON.stringify(response, null, 2));
                    showError(errorMsg);
                }
            } catch (error) {
                console.error('Error loading topic lessons:', error);
                console.error('Error stack:', error.stack);
                showError('Error loading lessons: ' + error.message);
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
            const topicTitle = currentTopic?.title ? currentTopic.title[0].toUpperCase() + currentTopic.title.slice(1) : 'Topic';
            const lessonTitle = currentLesson?.title ? currentLesson.title[0].toUpperCase() + currentLesson.title.slice(1) : 'Lesson';
            document.getElementById('lessonTitle').textContent = `${topicTitle}: ${lessonTitle}`;

            // Clear and update video
            const videoContainer = document.getElementById('videoContainer');
            if (currentLesson.video_url) {
                const videoUrl = currentLesson.video_url;
                let embedHtml = '';

                // Check if it's a YouTube URL
                if (isYouTubeUrl(videoUrl)) {
                    const youtubeId = extractYouTubeId(videoUrl);
                    if (youtubeId) {
                        embedHtml = `
                            <iframe width="100%" height="400"
                                src="https://www.youtube.com/embed/${youtubeId}"
                                title="YouTube video player"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                                style="border-radius: 16px;">
                            </iframe>
                        `;
                    }
                } else {
                    // Treat as direct video file (MP4, WebM, etc.)
                    embedHtml = `
                        <video width="100%" height="400" controls style="border-radius: 16px;">
                            <source src="${videoUrl}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    `;
                }

                videoContainer.innerHTML = embedHtml;
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
            if (!fileName || typeof fileName !== 'string') return 'fa-file';
            const ext = fileName.split('.').pop().toLowerCase();
            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
            const videoExtensions = ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv', 'flv', 'wmv', 'm4v'];
            const audioExtensions = ['mp3', 'wav', 'ogg', 'aac', 'flac', 'm4a'];
            const pdfExtensions = ['pdf'];

            if (imageExtensions.includes(ext)) return 'fa-image';
            if (videoExtensions.includes(ext)) return 'fa-video';
            if (audioExtensions.includes(ext)) return 'fa-music';
            if (pdfExtensions.includes(ext)) return 'fa-file-pdf';
            return 'fa-file';
        }

        /**
         * Get file type category
         */
        function getFileType(fileName) {
            if (!fileName || typeof fileName !== 'string') return 'unsupported';
            const ext = fileName.split('.').pop().toLowerCase();
            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
            const videoExtensions = ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv', 'flv', 'wmv', 'm4v'];
            const audioExtensions = ['mp3', 'wav', 'ogg', 'aac', 'flac', 'm4a'];
            const pdfExtensions = ['pdf'];

            if (imageExtensions.includes(ext)) return 'image';
            if (videoExtensions.includes(ext)) return 'video';
            if (audioExtensions.includes(ext)) return 'audio';
            if (pdfExtensions.includes(ext)) return 'pdf';
            return 'unsupported';
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
                        const fileName = attachment.name || attachment.file_name || 'Attachment';
                        const filePath = attachment.url || attachment.file_path;
                        const fileIcon = getFileIcon(fileName);
                        btn.innerHTML = `
                            <i class="fa-solid ${fileIcon}"></i>
                            ${fileName}
                            <i class="fa-solid fa-eye"></i>
                        `;
                        btn.onclick = () => {
                            viewFile(filePath, fileName);
                        };
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
         * View file in modal (handles PDFs, Images, Videos, and Documents)
         */
        function viewFile(filePath, fileName) {
            const fileType = getFileType(fileName);
            const fileViewerBody = document.getElementById('fileViewerBody');
            const fileViewerLabel = document.getElementById('fileViewerLabel');

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
                img.onerror = () => {
                    fileViewerBody.innerHTML = '<div class="alert alert-danger">Error loading image</div>';
                };
                fileViewerBody.appendChild(img);
                fileViewerLabel.textContent = `Image: ${fileName}`;
            } else if (fileType === 'video') {
                // Display video player
                const videoContainer = document.createElement('div');
                videoContainer.style.width = '100%';
                videoContainer.style.borderRadius = '8px';
                videoContainer.style.overflow = 'hidden';
                videoContainer.style.backgroundColor = '#000';

                const video = document.createElement('video');
                video.controls = true;
                video.style.width = '100%';
                video.style.height = 'auto';
                video.style.maxHeight = '70vh';
                video.style.display = 'block';

                const source = document.createElement('source');
                source.src = filePath;

                // Determine video type based on extension
                const ext = fileName.split('.').pop().toLowerCase();
                const videoTypes = {
                    'mp4': 'video/mp4',
                    'webm': 'video/webm',
                    'ogg': 'video/ogg',
                    'mov': 'video/quicktime',
                    'avi': 'video/x-msvideo',
                    'mkv': 'video/x-matroska',
                    'flv': 'video/x-flv',
                    'wmv': 'video/x-ms-wmv',
                    'm4v': 'video/x-m4v'
                };

                source.type = videoTypes[ext] || 'video/mp4';
                video.appendChild(source);

                const fallbackText = document.createElement('p');
                fallbackText.textContent = 'Your browser does not support the video tag.';
                video.appendChild(fallbackText);

                video.onerror = (error) => {
                    console.error('Video error:', error);
                    fileViewerBody.innerHTML = `
                        <div class="alert alert-danger">
                            <div class="d-flex flex-column gap-3">
                                <div>
                                    <i class="fa-solid fa-video fa-3x" style="color: #dc3545;"></i>
                                </div>
                                <div>
                                    <h5>${fileName}</h5>
                                    <p class="text-muted mb-3">Error loading video. Please download the file to view it.</p>
                                    <div class="d-flex gap-2">
                                        <a href="${filePath}" download class="btn btn-primary">
                                            <i class="fa-solid fa-download"></i> Download Video
                                        </a>
                                        <a href="${filePath}" target="_blank" class="btn btn-outline-primary">
                                            <i class="fa-solid fa-external-link"></i> Open in New Tab
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                };

                video.onloadstart = () => {
                    // Video loading started
                };

                video.oncanplay = () => {
                    // Video can play
                };

                videoContainer.appendChild(video);
                fileViewerBody.appendChild(videoContainer);
                fileViewerLabel.textContent = `Video: ${fileName}`;
            } else if (fileType === 'pdf') {
                // Display PDF using PDF.js viewer
                fileViewerBody.innerHTML = '<div class="text-center py-5"><div class="spinner-border" role="status"><span class="sr-only">Loading PDF...</span></div></div>';
                fileViewerLabel.textContent = `PDF: ${fileName}`;

                // Create PDF viewer container
                const pdfContainer = document.createElement('div');
                pdfContainer.style.width = '100%';
                pdfContainer.style.height = '70vh';
                pdfContainer.style.backgroundColor = '#f5f5f5';
                pdfContainer.style.borderRadius = '8px';
                pdfContainer.style.overflow = 'auto';
                pdfContainer.id = 'pdfContainer_' + Date.now();

                fetch(filePath)
                    .then(response => {
                        if (!response.ok) throw new Error(`Failed to load PDF: ${response.status}`);
                        return response.arrayBuffer();
                    })
                    .then(arrayBuffer => {
                        // Load PDF with PDF.js
                        const pdf = pdfjsLib.getDocument({ data: arrayBuffer });

                        pdf.promise.then(pdfDoc => {

                            // Clear loading message
                            fileViewerBody.innerHTML = '';
                            fileViewerBody.appendChild(pdfContainer);

                            const container = document.getElementById(pdfContainer.id);

                            // Render all pages
                            const renderPages = async () => {
                                for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
                                    const page = await pdfDoc.getPage(pageNum);

                                    // Create canvas for each page
                                    const canvas = document.createElement('canvas');
                                    const context = canvas.getContext('2d');

                                    const viewport = page.getViewport({ scale: 1.5 });
                                    canvas.width = viewport.width;
                                    canvas.height = viewport.height;
                                    canvas.style.display = 'block';
                                    canvas.style.margin = '10px auto';
                                    canvas.style.border = '1px solid #ddd';
                                    canvas.style.borderRadius = '4px';

                                    const renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    };

                                    await page.render(renderContext).promise;
                                    container.appendChild(canvas);
                                }
                            };

                            renderPages().catch(error => {
                                console.error('Error rendering PDF pages:', error);
                                container.innerHTML = `
                                    <div class="alert alert-danger m-3">
                                        <p>Error rendering PDF pages</p>
                                    </div>
                                `;
                            });
                        }).catch(error => {
                            console.error('Error loading PDF document:', error);
                            fileViewerBody.innerHTML = `
                                <div class="alert alert-danger">
                                    <div class="d-flex flex-column gap-3">
                                        <div>
                                            <i class="fa-solid fa-file-pdf fa-3x" style="color: #dc3545;"></i>
                                        </div>
                                        <div>
                                            <h5>${fileName}</h5>
                                            <p class="text-muted mb-3">Error: ${error.message}</p>
                                            <p class="text-muted mb-3">The PDF viewer encountered an issue. Please download the file to view it.</p>
                                            <div class="d-flex gap-2">
                                                <a href="${filePath}" download class="btn btn-primary">
                                                    <i class="fa-solid fa-download"></i> Download PDF
                                                </a>
                                                <a href="${filePath}" target="_blank" class="btn btn-outline-primary">
                                                    <i class="fa-solid fa-external-link"></i> Open in New Tab
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching PDF:', error);
                        fileViewerBody.innerHTML = `
                            <div class="alert alert-danger">
                                <div class="d-flex flex-column gap-3">
                                    <div>
                                        <i class="fa-solid fa-file-pdf fa-3x" style="color: #dc3545;"></i>
                                    </div>
                                    <div>
                                        <h5>${fileName}</h5>
                                        <p class="text-muted mb-3">Error: ${error.message}</p>
                                        <p class="text-muted mb-3">Failed to fetch the PDF file. Please download the file to view it.</p>
                                        <div class="d-flex gap-2">
                                            <a href="${filePath}" download class="btn btn-primary">
                                                <i class="fa-solid fa-download"></i> Download PDF
                                            </a>
                                            <a href="${filePath}" target="_blank" class="btn btn-outline-primary">
                                                <i class="fa-solid fa-external-link"></i> Open in New Tab
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
            } else if (fileType === 'audio') {
                // Display audio player
                // Determine audio type based on extension
                const ext = fileName.split('.').pop().toLowerCase();
                const audioTypes = {
                    'mp3': 'audio/mpeg',
                    'wav': 'audio/wav',
                    'ogg': 'audio/ogg',
                    'aac': 'audio/aac',
                    'flac': 'audio/flac',
                    'm4a': 'audio/mp4'
                };

                const mimeType = audioTypes[ext] || 'audio/mpeg';

                // Create HTML for audio player
                fileViewerBody.innerHTML = `
                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 20px; padding: 30px 20px; background-color: #f5f5f5; border-radius: 8px; min-height: 150px;">
                        <div style="text-align: center;">
                            <div style="font-size: 48px; color: #004A53; margin-bottom: 10px;">
                                <i class="fa-solid fa-music"></i>
                            </div>
                            <div style="font-weight: 600; color: #333; margin-bottom: 5px;">${fileName}</div>
                            <div style="font-size: 12px; color: #666;">Audio File</div>
                        </div>
                        <audio controls style="width: 100%; min-width: 300px; height: 40px;" crossorigin="anonymous">
                            <source src="${filePath}" type="${mimeType}">
                            Your browser does not support the audio tag.
                        </audio>
                    </div>
                `;

                fileViewerLabel.textContent = `Audio: ${fileName}`;
            } else {
                // Display unsupported file type
                const unsupportedContainer = document.createElement('div');
                unsupportedContainer.className = 'alert alert-warning';
                unsupportedContainer.innerHTML = `
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <i class="fa-solid ${getFileIcon(fileName)} fa-3x" style="color: #ff9800;"></i>
                        </div>
                        <div>
                            <h5>${fileName}</h5>
                            <p class="text-muted mb-2">This file type is not supported for preview. Supported types: Image, Video, Audio, and PDF.</p>
                            <a href="${filePath}" download class="btn btn-sm btn-primary">
                                <i class="fa-solid fa-download"></i> Download File
                            </a>
                        </div>
                    </div>
                `;
                fileViewerBody.appendChild(unsupportedContainer);
                fileViewerLabel.textContent = `File: ${fileName}`;
            }

            // Show modal
            const fileViewerModalElement = document.getElementById('fileViewerModal');
            const modal = new bootstrap.Modal(fileViewerModalElement);

            // Stop video/audio playback when modal closes
            fileViewerModalElement.addEventListener('hidden.bs.modal', function() {
                // Stop all video and audio elements
                const videos = fileViewerBody.querySelectorAll('video');
                const audios = fileViewerBody.querySelectorAll('audio');

                videos.forEach(video => {
                    video.pause();
                    video.currentTime = 0;
                });

                audios.forEach(audio => {
                    audio.pause();
                    audio.currentTime = 0;
                });
            }, { once: true });

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
                    buttonClass += ' opacity-50 d-none';
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
                        } else if (input.tagName === 'TEXTAREA' && input.value.trim()) {
                            answerValue = input.value;
                        }
                    });

                    if (questionId && answerValue) {
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

                // Submit quiz with the current attempt number
                const response = await window.LessonApiClient.submitQuiz(quizId, {
                    attempt_number: attemptNumber,
                    answers: answers
                });

                if (response.success) {
                    showSuccess('Quiz submitted successfully!');

                    // Redirect to result page after a short delay
                    setTimeout(() => {
                        const resultUrl = `/userresult?quiz_id=${quizId}`;
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
            // Prevent double-click
            if (isMarkingComplete) {
                console.warn('Already marking lesson as complete, please wait...');
                return;
            }

            try {
                if (!currentLesson || !currentLesson.id) {
                    showError('Lesson data not loaded');
                    return;
                }

                // Set flag to prevent double-click
                isMarkingComplete = true;
                const markCompleteBtn = document.getElementById('markCompleteBtn');
                markCompleteBtn.disabled = true;

                const response = await window.LessonApiClient.markLessonComplete(currentLesson.id);

                if (response.success) {
                    // Update the lesson in allLessons array
                    const lessonIndex = allLessons.findIndex(lesson => lesson.id === currentLesson.id);
                    if (lessonIndex !== -1) {
                        allLessons[lessonIndex].is_completed = true;
                    }

                    // Update current lesson
                    currentLesson.is_completed = true;

                    markCompleteBtn.textContent = 'Lesson Completed ✓';
                    markCompleteBtn.style.opacity = '0.6';

                    // Update progress bar
                    const completedLessons = allLessons.filter(lesson => lesson.is_completed).length;
                    const progressPercentage = (completedLessons / allLessons.length) * 100;
                    const progressTrack = document.getElementById('progressTrack');
                    progressTrack.style.width = progressPercentage + '%';

                    showSuccess('Lesson marked as complete!');

                    // Emit event for global data refresh
                    if (window.DataRefreshService) {
                        await DataRefreshService.emit(DataRefreshService.EVENTS.LESSON_COMPLETED, {
                            lesson_id: currentLesson.id,
                            lesson_title: currentLesson.title,
                            progress: progressPercentage
                        });
                    }
                } else {
                    showError(response.message || 'Failed to mark lesson complete');
                    // Re-enable button on error
                    markCompleteBtn.disabled = false;
                }
            } catch (error) {
                console.error('Error marking lesson complete:', error);
                showError('Error marking lesson complete');
                // Re-enable button on error
                const markCompleteBtn = document.getElementById('markCompleteBtn');
                markCompleteBtn.disabled = false;
            } finally {
                // Reset flag after a short delay to allow for any UI updates
                setTimeout(() => {
                    isMarkingComplete = false;
                }, 500);
            }
        };

        /**
         * Navigate to previous lesson
         */
        window.navigateToPreviousLesson = async function() {
            // Prevent double-click
            if (isNavigating) {
                console.warn('Already navigating, please wait...');
                return;
            }

            if (currentLessonIndex > 0) {
                try {
                    isNavigating = true;
                    const prevBtn = document.getElementById('prevBtn');
                    prevBtn.disabled = true;

                    currentLessonIndex--;
                    await loadCurrentLesson();
                    await loadQuizzes();
                    window.scrollTo(0, 0);
                } catch (error) {
                    console.error('Error navigating to previous lesson:', error);
                    showError('Error loading previous lesson');
                    currentLessonIndex++; // Revert index on error
                } finally {
                    isNavigating = false;
                    const prevBtn = document.getElementById('prevBtn');
                    prevBtn.disabled = false;
                    updateButtonStates();
                }
            }
        };

        /**
         * Navigate to next lesson
         */
        window.navigateToNextLesson = async function() {
            // Prevent double-click
            if (isNavigating) {
                console.warn('Already navigating, please wait...');
                return;
            }

            if (currentLessonIndex < allLessons.length - 1) {
                try {
                    isNavigating = true;
                    const nextBtn = document.getElementById('nextBtn');
                    nextBtn.disabled = true;

                    currentLessonIndex++;
                    await loadCurrentLesson();
                    await loadQuizzes();
                    window.scrollTo(0, 0);
                } catch (error) {
                    console.error('Error navigating to next lesson:', error);
                    showError('Error loading next lesson');
                    currentLessonIndex--; // Revert index on error
                } finally {
                    isNavigating = false;
                    const nextBtn = document.getElementById('nextBtn');
                    nextBtn.disabled = false;
                    updateButtonStates();
                }
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
                // Get all quizzes for the current lesson
                if (!currentLesson || !currentLesson.id) {
                    showError('No lesson selected');
                    return;
                }

                const quizzesResponse = await window.LessonApiClient.getQuizzesByLesson(currentLesson.id);

                if (!quizzesResponse.success || !quizzesResponse.data) {
                    showError('Failed to load quizzes');
                    return;
                }

                const quizzes = quizzesResponse.data;

                // Fetch results for each quiz
                let allQuizResults = [];
                for (const quiz of quizzes) {
                    try {
                        const resultsResponse = await window.QuizApiClient.getQuizResults(quiz.id);

                        if (resultsResponse.success && resultsResponse.data && resultsResponse.data.results) {
                            allQuizResults.push({
                                quiz_id: quiz.id,
                                quiz_title: quiz.title,
                                results: resultsResponse.data.results
                            });
                        }
                    } catch (error) {
                        // Error fetching results for quiz
                    }
                }

                if (allQuizResults.length === 0) {
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

                if (!modalBody) {
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
