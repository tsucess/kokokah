{{-- @extends('admin.usertemplate') --}}
@extends('layouts.usertemplate')

@section('content')
    <style>
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, min(100%, 310px)));
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

        <div class="container-fluid mb-5">
            <div class="card-container" id="coursesContainer">
                <!-- Courses will be loaded dynamically -->
            </div>

        </div>

        {{-- <div class="chat-btn-circle">
            <i class="fa-solid fa-comment"></i>
        </div> --}}

    </main>
        <!-- API Clients -->
    <script>
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
                    document.getElementById('userGreeting').textContent = `Hello ${firstName.charAt(0).toUpperCase() + firstName.slice(1)} `;
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
                console.log('API Response Data:', response.data);

                if (response.success && response.data) {
                    // Handle response structure: { courses: [...], total: ... }
                    userCourses = response.data.courses || response.data.data || response.data;

                    console.log('Courses extracted:', userCourses);
                    console.log('Number of courses:', userCourses.length);

                    // Log each course's access_type
                    if (Array.isArray(userCourses)) {
                        userCourses.forEach((course, index) => {
                            console.log(`Course ${index}:`, {
                                title: course.course?.title || 'Unknown',
                                access_type: course.access_type,
                                course_id: course.course_id
                            });
                        });
                    }

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

            // Determine access type badge
            const accessType = enrollment.access_type || 'enrolled';
            let accessBadge = '';
            let badgeColor = '#004A53'; // Default color

            if (accessType === 'free_subscription') {
                accessBadge = 'FREE';
                badgeColor = '#4CAF50'; // Green for free
            } else if (accessType === 'subscription') {
                accessBadge = 'SUBSCRIPTION';
                badgeColor = '#2196F3'; // Blue for subscription
            } else if (accessType === 'enrolled') {
                accessBadge = 'ENROLLED';
                badgeColor = '#FF9800'; // Orange for enrolled
            }

            card.innerHTML = `
                <div style="position: relative;">
                    <div class="border border-dark" style="height: 200px; border-radius: 10px; overflow: hidden; text-align: center; align-items: center; justify-content: center; display: flex;">
                        <img src="${courseImage}" class="img-fluid" style=" width:100%; " alt="${course.title}" />
                    </div>
                    ${accessBadge ? `<div style="position: absolute; top: 10px; right: 10px; background-color: ${badgeColor}; color: white; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600;">${accessBadge}</div>` : ''}
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
                <div class="col-12 w-100 py-5">
                    <p class="text-muted text-center">No courses available yet. <a href="/userclass">Browse all courses</a> to get started with free or premium courses.</p>
                </div>
            `;
        }    </script>
@endsection
