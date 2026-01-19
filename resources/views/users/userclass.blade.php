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
                <h3>Class</h3>
                <p>Let’s learn something new today!</p>
            </div>

        </div>

        <div class  = "container position-relative " style="margin-top: -70px; min-height:200px;">
            <div class = "card-container" id="coursesContainer">
                <!-- Classes will be loaded here dynamically -->
                <div class="text-center w-100" id="loadingMessage">
                    <p class="text-muted">Loading classes...</p>
                </div>
            </div>

        </div>

        </div>

        </div>

        <div class="chat-btn-circle">
            <i class="fa-solid fa-comment"></i>
        </div>

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
                    const classesHtml = result.data.map((classItem, index) => `
                    <div class="p-3 rounded-4 bg-white mysubject d-flex flex-column gap-3 w-100">
                        <div class="border border-dark p-2 d-flex justify-content-center" style="border-radius: 10px;">
                            <img src="images/KOKOKAH Logo.svg"
                                 class="img-fluid userdasboard-card-img"
                                 alt="${classItem.name}"
                                 style="max-height: 50px; object-fit: cover;" />
                        </div>
                        <div class="card-item-class align-self-start">${classItem.curriculum_category?.title || 'Class'}</div>
                        <h5 class="subjects">${classItem.name}</h5>
                        <p class="text-muted small" style="margin: 0;">${classItem.description ? classItem.description.substring(0, 80) + '...' : 'Explore this class'}</p>
                        <button class="enroll-btn" data-level-id="${classItem.id}">Enroll</button>
                    </div>
                `).join('');

                    container.innerHTML = classesHtml;

                    // Attach event listeners to enroll buttons
                    attachEnrollListeners();
                } else {
                    // No classes found
                    loadingMessage.innerHTML = '<p class="text-muted">No classes available at the moment.</p>';
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
