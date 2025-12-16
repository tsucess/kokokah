{{-- @extends('admin.usertemplate') --}}
@extends('layouts.usertemplate')

@section('content')
    <style>
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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

        .view-btn {
            border: 1px solid #004A53;
            border-radius: 4px;
            padding: 16px 20px;
            color: #004A53;
            font-size: 16px;
            font-weight: 600;
            z-index: 9999;
        }
    </style>
    <main>
        <div class="container m-2">
            <div class="row">
                <div>
                    <h4 id="userGreeting">Hello
                        <i class="fa-solid fa-hands-clapping text-warning"></i>
                    </h4>
                    <p>Let`s learn something new today</p>
                </div>

            </div>

        </div>

        <div class="container">
            <div class="card-container" id="coursesContainer">
                <!-- Courses will be loaded dynamically -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="chat-btn-circle">
            <i class="fa-solid fa-comment"></i>
        </div>

    </main>
    <script type="module">
        import CourseApiClient from '{{ asset("js/api/courseApiClient.js") }}';
        import UserApiClient from '{{ asset("js/api/userApiClient.js") }}';
        import ToastNotification from '{{ asset("js/utils/toastNotification.js") }}';

        let userCourses = [];

        // Initialize page
        document.addEventListener('DOMContentLoaded', async () => {
            // Check if redirected from successful payment
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('payment_success') === 'true') {
                ToastNotification.success('Payment Successful', 'Your course has been enrolled successfully!');
                // Clean up URL
                window.history.replaceState({}, document.title, '/usersubject');
            }

            await loadUserData();
            await loadUserCourses();
        });

        // Load user profile data
        async function loadUserData() {
            try {
                const response = await UserApiClient.getProfile();
                if (response.success && response.data) {
                    const user = response.data;
                    const firstName = user.first_name || 'User';
                    document.getElementById('userGreeting').textContent = `Hello ${firstName} `;
                    const icon = document.createElement('i');
                    icon.className = 'fa-solid fa-hands-clapping text-warning';
                    document.getElementById('userGreeting').appendChild(icon);
                }
            } catch (error) {
                console.error('Error loading user profile:', error);
            }
        }

        // Load user's enrolled courses
        async function loadUserCourses() {
            try {
                const response = await CourseApiClient.getMyCourses();
                console.log('API Response:', response);

                if (response.success && response.data) {
                    // Handle response structure: { courses: [...], total: ... }
                    userCourses = response.data.courses || response.data.data || response.data;

                    console.log('Courses extracted:', userCourses);

                    // Ensure userCourses is an array
                    if (!Array.isArray(userCourses)) {
                        userCourses = [];
                    }

                    console.log('Final courses array:', userCourses);
                    renderCourses(userCourses);
                } else {
                    console.log('No success or data in response');
                    showNoCourses();
                }
            } catch (error) {
                console.error('Error loading courses:', error);
                ToastNotification.error('Failed to load courses');
                showNoCourses();
            }
        }

        // Render courses
        function renderCourses(courses) {
            const container = document.getElementById('coursesContainer');
            container.innerHTML = '';

            if (!courses || courses.length === 0) {
                showNoCourses();
                return;
            }

            courses.forEach((course) => {
                const courseCard = createCourseCard(course);
                container.appendChild(courseCard);
            });
        }

        // Create course card element
        function createCourseCard(enrollment) {
            const card = document.createElement('div');
            card.className = 'p-3 bg-white mysubject d-flex flex-column gap-3 w-100';

            // Handle both direct course object and enrollment with nested course
            const course = enrollment.course || enrollment;
            const courseId = course.id || enrollment.course_id;

            // Get course image or use default
            const courseImage = course.thumbnail_url || course.image_url || 'images/Kokokah_Logo.png';

            // Get course level/class
            const courseLevel = course.level?.name || course.class_name || 'General';

            // Get course progress from enrollment or course
            const progress = enrollment.progress || course.progress || 0;

            card.innerHTML = `
                <div class="border border-dark p-3" style="height: 200px; border-radius: 10px; overflow: hidden; text-align: center; align-items: center; justify-content: center; display: flex;">
                    <img src="${courseImage}" class="img-fluid" style="max-height: 100%; object-fit: contain;" alt="${course.title}" />
                </div>
                <div class="card-item-class align-self-start">${courseLevel}</div>
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="subjects">${course.title}</h5>
                    <h5 class="subjects">${progress}%</h5>
                </div>

                <div class="progress" style="height:6px; background-color:#D9D9D9;">
                    <div class="progress-bar" style="width:${progress}%; background:#004A53; height:100%;"></div>
                </div>
                <button class="view-btn" type="button" data-course-id="${courseId}">View Subjects</button>
            `;

            // Add click event listener
            const viewBtn = card.querySelector('.view-btn');
            viewBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                window.location.href = `/termsubject?course_id=${courseId}`;
            });

            return card;
        }

        // Show no courses message
        function showNoCourses() {
            const container = document.getElementById('coursesContainer');
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No courses enrolled yet. <a href="/userclass">Browse courses</a></p>
                </div>
            `;
        }
    </script>
@endsection
