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
                    <div class="d-flex overflow-auto py-2 border-bottom border-top" id="lessonsProgressContainer">
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

                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">This Subject Includes</h6>

                            <ul class="list-unstyled small" id="subjectIncludesList">
                                <!-- Subject details will be loaded dynamically -->
                            </ul>
                        </div>
                    </div>

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
                    allTerms = response.data;
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
                        <div>
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
                { icon: 'images/celebrate.png', text: 'Certification of completion' }
            ];

            includes.forEach(item => {
                const li = document.createElement('li');
                li.className = 'mb-2';
                li.innerHTML = `<img src="${item.icon}" class="img-fluid" style="width:15px; height:15px;"> ${item.text}`;
                list.appendChild(li);
            });
        }    </script>
@endsection
