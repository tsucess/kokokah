{{-- @extends('admin.usertemplate') --}}
@extends('layouts.usertemplate')

@section('content')
    <style>
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, min(100%, 300px)));
            gap: 1rem;
            position: relative;
            z-index: 10;
            justify-content: center;
        }

        .card-item-class {
            background-color: #FDAF22;
            padding: 4px 28px;
            border-radius: 5px;
            color: #000F11;
            font-size: 12px;
        }

        .enroll-btn {
            border: 1px solid #004A53;
            border-radius: 4px;
            padding: 16px 20px;
            color: #004A53;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.2s ease,
              color 0.2s ease,
              border-color 0.2s ease,
              transform 0.15s ease;
        }

        .enroll-btn:hover {
            background-color: #004A53;
            color: #ffffff;
            border-color: #004A53;
            transform: translateY(-1px);
        }


        @media screen and (max-width:500px) {
            .enroll-btn {
                padding-block: 10px;
            }
        }
    </style>
    <main>


        <!-- Header -->
        <div
            class="header-section container-fluid align-items-center d-flex justify-content-center justify-content-lg-start">
            <div class="d-flex flex-column gap-2 align-items-center align-items-lg-start ">
                <h2 data-i18n="subjects.class">Class</h2>
                <p data-i18n="subjects.lets_learn">Let’s learn something new today!</p>
            </div>

        </div>

        <div class  = "container-fluid position-relative mb-5" style="margin-top: -70px; min-height:200px;">
            <div class = "card-container" id="coursesContainer">
                <!-- Classes will be loaded here dynamically -->
                <div class="text-center w-100" id="loadingMessage">
                    <p class="text-muted" data-i18n="subjects.loading_classes">Loading classes...</p>
                </div>
            </div>

        </div>

        </div>

        </div>

        {{-- <div class="chat-btn-circle">
            <i class="fa-solid fa-comment"></i>
        </div> --}}

    </main>
    <!-- API Clients -->
    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            await loadClasses();
        });

        /**
         * Load classes (levels) from API and display them
         */
        async function loadClasses() {
            try {
                const container = document.getElementById('coursesContainer');
                const loadingMessage = document.getElementById('loadingMessage');

                // Fetch levels/classes from API
                const result = await CourseApiClient.getLevels();

                if (result.success && result.data && result.data.length > 0) {
                    // Clear loading message
                    loadingMessage.remove();

                    // Generate HTML for each class/level
                    const classText = window.i18nManager ? window.i18nManager.translate('subjects.class') : 'Class';
                    const exploreText = window.i18nManager ? window.i18nManager.translate('subjects.explore_this_class') : 'Explore this class';
                    const enrollText = window.i18nManager ? window.i18nManager.translate('subjects.enroll') : 'Enroll';

                    const classesHtml = result.data.map((classItem, index) => `
                    <div class="p-3 rounded-4 bg-white mysubject d-flex flex-column gap-3 w-100">
                        <div class="border border-dark p-2 d-flex justify-content-center" style="border-radius: 10px;">
                            <img src="images/KOKOKAH Logo.svg"
                                 class="img-fluid userdasboard-card-img"
                                 alt="${classItem.name}"
                                 style="max-height: 50px; object-fit: cover;" />
                        </div>
                        <div class="card-item-class align-self-start">${classItem.curriculum_category?.title || classText}</div>
                        <h5 class="subjects">${classItem.name}</h5>
                        <p class="text-muted small" style="margin: 0;">${classItem.description ? classItem.description.substring(0, 80) + '...' : exploreText}</p>
                        <button class="enroll-btn" data-level-id="${classItem.id}" data-i18n="subjects.enroll">${enrollText}</button>
                    </div>
                `).join('');

                    container.innerHTML = classesHtml;

                    // Attach event listeners to enroll buttons
                    attachEnrollListeners();
                } else {
                    // No classes found
                    const noClassesText = window.i18nManager ? window.i18nManager.translate('subjects.no_classes') : 'No classes available at the moment.';
                    loadingMessage.innerHTML = `<p class="text-muted">${noClassesText}</p>`;
                }
            } catch (error) {
                console.error('Error loading classes:', error);
                const loadingMessage = document.getElementById('loadingMessage');
                loadingMessage.innerHTML = '<p class="text-danger">Failed to load classes. Please try again later.</p>';
            }
        }

        /**
         * Attach event listeners to enroll buttons
         */
        function attachEnrollListeners() {
            const enrollBtns = document.querySelectorAll('button.enroll-btn');

            enrollBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const levelId = this.getAttribute('data-level-id');

                    // Navigate to enrollment page with level ID
                    window.location.href = `/userenroll?level_id=${levelId}`;
                });
            });
        }
    </script>
@endsection
