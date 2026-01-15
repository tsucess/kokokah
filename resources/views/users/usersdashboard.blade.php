@extends('layouts.usertemplate')
@section('content')
<main>
    <!-- Authentication and Role Check Script -->
    <script>
        // Check if user is authenticated via localStorage token and has correct role
        (function() {
            const token = localStorage.getItem('auth_token') || localStorage.getItem('token');
            const userStr = localStorage.getItem('auth_user');

            // If no token or user data, redirect to login
            if (!token || !userStr) {
                console.warn('No authentication token found. Redirecting to login...');
                window.location.href = '/login';
                return;
            }

            // Parse user data
            let user = null;
            try {
                user = JSON.parse(userStr);
            } catch (e) {
                console.error('Failed to parse user data:', e);
                window.location.href = '/login';
                return;
            }

            // Check user role - only students and instructors should access user dashboard
            if (user && !['student', 'instructor'].includes(user.role)) {
                console.warn('User role is ' + user.role + '. Redirecting to admin dashboard...');
                window.location.href = '/dashboard';
                return;
            }

            // Token exists and user has student/instructor role, can access dashboard
            console.log('User authenticated. Token found. Role:', user?.role);
        })();
    </script>
 <style>
    .stats-card {
      border: none;
      border-radius: 20px;
      background: #fff;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
      transition: transform 0.3s ease;
      transform: translateY(-5px);
    }

    .stats-card:hover {
      transform: translateY(-10px);
    }

    .icon-circle img {
      width: 48px;
      height: 48px;
    }
    .card-container{
        display: flex;
        gap: 1rem;
        position: relative;
        z-index: 10;
        overflow-x: auto;
        overflow-y: hidden;
        scroll-behavior: smooth;
        padding-bottom: 10px;
        /* Hide scrollbar but keep functionality */
        scrollbar-width: none;
    }

    .card-container::-webkit-scrollbar {
        display: none;
    }

    .card-container > * {
        flex: 0 0 calc(33.333% - 0.67rem);
        min-width: 280px;
    }

    @media (max-width: 1024px) {
        .card-container > * {
            flex: 0 0 calc(50% - 0.5rem);
            min-width: 250px;
        }
    }

    @media (max-width: 768px) {
        .card-container > * {
            flex: 0 0 calc(100% - 0rem);
            min-width: 100%;
        }
    }
    .card-item-class{
background-color: #FDAF22;
padding: 4px 28px;
border-radius: 5px;
color: #000F11;
font-size: 12px;
    }
    .view-btn{
        border:1px solid #004A53;
        border-radius: 4px;
        padding: 16px 20px;
        color:#004A53 ;
        font-size: 16px;
        font-weight: 600;
        z-index: 9999;
        position: relative;
    }

    .slider-controls {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .slider-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border-radius: 50%;
    }

    .slider-btn:hover {
        background-color: #f0f0f0;
        transform: scale(1.1);
    }

    .slider-btn:active {
        transform: scale(0.95);
    }

    .slider-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .slider-btn:disabled:hover {
        background-color: transparent;
        transform: scale(1);
    }

    @media (max-width: 768px) {
        .view-btn{
            padding-block: 10px;
        }
        .slider-btn {
            padding: 3px;
        }
        .slider-btn i {
            font-size: 18px !important;
        }
      .header-section {
        text-align: center;
      }
      .header-image {
        margin-top: 1rem;
        width: 150px;
      }
    }
  </style>


    {{-- <div class="container m-2">
<div class="row">
        <div>
          <h4>Hello Samuel
            <i class="fa-solid fa-hands-clapping text-warning"></i>
          </h4>
          <p>Let`s learn something new today</p>
        </div>

      </div>

    </div> --}}


    {{-- <div class = "row">
        <div class = "d-flex justify-content-between">

            <div class = "col-12 col-md-6 col-lg-6">
                <img src="images/celebration.png" class="img-fluid" width = 50 height = 50>
                <span>24</span><br>
                <span>Completed Subject</span>

            </div>


            <div class = "col-12 col-md-6 col-lg-6">
                <img src="images/celebration.png" class="img-fluid">
                <span>24<br>
                <span>Completed Subject</span>
            </span>
            </div>

        </div>
    </div> --}}

 <!-- Header -->
  <div class="header-section position-relative container-fluid">
    <div class="container">

        <div class="d-flex flex-column gap-2 align-items-start pt-4 pt-md-5 ">
          <h3 id="userGreeting">Hello 👋</h3>
          <p>Let’s learn something new today!</p>
        </div>

          <img src="{{ asset('images/mydashboard.png') }}" alt="Robot"  class="header-image w-100 position-absolute "
             style="max-height: 300px;">

      </div>
    </div>


  <!-- Stats Cards -->
  <div class="container" style="margin-top: -40px;">
    <div class="row g-3">

      <div class="col-12 col-md-6">
        <div class="d-flex align-items-center justify-content-between p-4 stats-card h-100">

          <div class="d-flex align-items-center gap-1">
            <div class="icon-circle">
              <img src="{{ asset('images/celebration.png') }}" class="img-fluid" alt="Completed">
            </div>
            <div class="d-flex flex-column gap-1">
              <strong class="fs-2 lh-1 d-block text-dark" id="completedCount">0</strong>
              <small class="text-nowrap header-card-text" >Completed Subject</small>
            </div>
          </div>

          <div class="text-success d-flex flex-column align-items-center ms-auto">
            <i class="fa-solid fa-arrow-trend-up text-success"></i>
            <small class="fw-bold" id="completedTrend">0%</small>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6">
        <div class="d-flex align-items-center justify-content-between p-4 stats-card h-100">

          <div class="d-flex align-items-center">
            <div class="icon-circle me-3">
              <img src="{{ asset('images/note.png') }}" class="img-fluid" alt="Pending">
            </div>
            <div>
              <strong class="fs-2 lh-1 d-block text-dark" id="ongoingCount">0</strong>
              <small class="header-card-text text-nowrap">Ongoing Subject</small>
            </div>
          </div>

          <div class="text-danger d-flex flex-column align-items-center ms-auto">
            <i class="fa-solid fa-arrow-trend-up text-danger"></i>
            <small class="fw-bold" id="ongoingTrend">0%</small>
          </div>
        </div>
      </div>

    </div>
  </div>

    <div class  = "container d-flex flex-column gap-3" style="min-height: 200px;">
        <div class ="d-flex justify-content-between align-items-center">
            <div>
            <p class = "usersparagraph">
                Continue reading
            </p>
            </div>

        <div class="slider-controls">
            <button id="sliderPrevBtn" class="slider-btn" type="button" title="Previous">
                <i class="fa-solid fa-circle-chevron-left" style="color: #9E9E9E; font-size: 24px;"></i>
            </button>
            <button id="sliderNextBtn" class="slider-btn" type="button" title="Next">
                <i class="fa-solid fa-circle-chevron-right" style="color: #9E9E9E; font-size: 24px;"></i>
            </button>
        </div>

        </div>

        <div class = "card-container" id="coursesContainer">
            <!-- Courses will be loaded here dynamically -->
        </div>

        <!-- Course Card Template (hidden) -->
        <template id="courseCardTemplate">
            <div class = "p-3 bg-white mysubject d-flex flex-column gap-3 w-100 rounded-4">
                <div class = "border border-dark p-2 text-center" style="border-radius: 10px;">
                    <img src="{{ asset('images/Kokokah_Logo.png') }}" class = "img-fluid userdasboard-card-img" alt="Course" />
                </div>
                <div class = "card-item-class align-self-start course-level">JSS 1</div>
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class = "subjects course-name">Computer Science</h5>
                    <h5 class = "subjects course-progress">0%</h5>
                </div>

                <div class="progress " style = "height:6px; background-color:#D9D9D9;">
                    <div class="progress-bar course-progress-bar" style="width:0%; background:#F56824; height:100%;"></div>
                </div>
                <button class="view-btn view-course-btn" type="button" data-course-id="">View Subjects</button>
            </div>
        </template>
    </div>

    </div>

    <div class="chat-btn-circle">
        <i class="fa-solid fa-comment"></i>
    </div>

</main>
    <!-- API Clients -->
    <script>
document.addEventListener('DOMContentLoaded', async () => {
    // Load user data
    const user = AuthApiClient.getUser();
    if (user) {
        document.getElementById('userGreeting').textContent = `Hello ${user.first_name[0].toUpperCase() + user.first_name.slice(1)} 👋`;
    }

    // Load user's enrolled courses
    await loadUserCourses();

    // Setup slider controls
    setupSliderControls();

    // Handle view course button clicks
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('button.view-course-btn');
        if (!btn) return;

        const courseId = btn.getAttribute('data-course-id');
        if (courseId) {
            window.location.href = `/termsubject?course_id=${courseId}`;
        }
    });
});

async function loadUserCourses() {
    try {
        const response = await CourseApiClient.getMyCourses({ per_page: 12 });

        if (!response.success) {
            console.error('Failed to load courses:', response.message);
            ToastNotification.error('Error', 'Failed to load your courses');
            return;
        }

        const courses = response.data?.courses || [];
        const container = document.getElementById('coursesContainer');
        const template = document.getElementById('courseCardTemplate');

        // Clear container
        container.innerHTML = '';

        if (courses.length === 0) {
            container.innerHTML = '<p class="text-center text-muted">No courses enrolled yet</p>';
            updateStats(0, 0);
            return;
        }

        // Calculate stats
        let completedCount = 0;
        let ongoingCount = 0;

        // Render each course
        courses.forEach(enrollment => {
            const course = enrollment.course;
            if (!course) return;

            // Clone template
            const card = template.content.cloneNode(true);

            // Update course data
            card.querySelector('.course-name').textContent = course.title || 'Untitled Course';
            card.querySelector('.course-level').textContent = course.level?.name || 'Level';

            // Calculate progress
            const progress = enrollment.progress || 0;
            card.querySelector('.course-progress').textContent = `${Math.round(progress)}%`;
            card.querySelector('.course-progress-bar').style.width = `${progress}%`;

            // Set course ID for navigation
            card.querySelector('.view-course-btn').setAttribute('data-course-id', course.id);

            // Update stats
            if (enrollment.status === 'completed') {
                completedCount++;
            } else if (enrollment.status === 'in_progress') {
                ongoingCount++;
            }

            container.appendChild(card);
        });

        updateStats(completedCount, ongoingCount);

    } catch (error) {
        console.error('Error loading courses:', error);
        ToastNotification.error('Error', 'An error occurred while loading courses');
    }
}

function updateStats(completed, ongoing) {
    document.getElementById('completedCount').textContent = completed;
    document.getElementById('ongoingCount').textContent = ongoing;

    // Calculate trends (you can enhance this with actual data)
    const completedTrend = completed > 0 ? '↑ 1.3%' : '0%';
    const ongoingTrend = ongoing > 0 ? '↑ 1.3%' : '0%';

    document.getElementById('completedTrend').textContent = completedTrend;
    document.getElementById('ongoingTrend').textContent = ongoingTrend;
}

function setupSliderControls() {
    const container = document.getElementById('coursesContainer');
    const prevBtn = document.getElementById('sliderPrevBtn');
    const nextBtn = document.getElementById('sliderNextBtn');

    if (!container || !prevBtn || !nextBtn) return;

    // Scroll amount per click (in pixels)
    const scrollAmount = 320; // Approximate card width + gap

    prevBtn.addEventListener('click', () => {
        container.scrollBy({
            left: -scrollAmount,
            behavior: 'smooth'
        });
        updateSliderButtonStates();
    });

    nextBtn.addEventListener('click', () => {
        container.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
        updateSliderButtonStates();
    });

    // Update button states on scroll
    container.addEventListener('scroll', updateSliderButtonStates);

    // Initial button state
    updateSliderButtonStates();
}

function updateSliderButtonStates() {
    const container = document.getElementById('coursesContainer');
    const prevBtn = document.getElementById('sliderPrevBtn');
    const nextBtn = document.getElementById('sliderNextBtn');

    if (!container || !prevBtn || !nextBtn) return;

    // Check if at start
    const isAtStart = container.scrollLeft <= 0;
    prevBtn.disabled = isAtStart;

    // Check if at end
    const isAtEnd = container.scrollLeft >= (container.scrollWidth - container.clientWidth - 10);
    nextBtn.disabled = isAtEnd;
}    </script>

@endsection
