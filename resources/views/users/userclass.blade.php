{{-- @extends('admin.usertemplate') --}}
@extends('layouts.usertemplate')

@section('content')
<style>
    .card-container{
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
        position: relative;
        z-index: 10;
    }
    .card-item-class{
        background-color: #FDAF22;
        padding: 4px 28px;
        border-radius: 5px;
        color: #000F11;
        font-size: 12px;
    }
    .enroll-btn{
        border:1px solid #004A53;
        border-radius: 4px;
        padding: 16px 20px;
        color:#004A53 ;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        background: none;
    }
    .enroll-btn:hover {
        background-color: #004A53;
        color: white;
    }
    .enroll-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background-color: #ccc;
        color: #666;
        border-color: #ccc;
    }
    .enrolled-badge {
        background-color: #4CAF50;
        color: white;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #004A53;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }
    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    @media screen and (max-width:500px){
        .enroll-btn{
            padding-block: 10px;
        }
    }
</style>
<main>


    <!-- Header -->
  <div class="header-section container-fluid align-items-center d-flex justify-content-center justify-content-lg-start" style="height:200px;">
        <div class="d-flex flex-column gap-2 align-items-center align-items-lg-start">
          <h3>Class</h3>
          <p>Let’s learn something new today!</p>
        </div>

  </div>

    <div class="container position-relative" style="margin-top: -70px;">
        <div class="card-container" id="coursesContainer">
            <!-- Courses will be loaded here dynamically -->
        </div>

        <!-- Class Level Card Template (hidden) -->
        <template id="courseCardTemplate">
            <div class="p-3 rounded-4 bg-white mysubject d-flex flex-column gap-3 w-100">
                <div class="border border-dark p-2 text-center" style="border-radius: 10px; background-color: #f8f9fa;">
                    <div class="course-level" style="font-size: 32px; font-weight: bold; color: #004A53; margin: 0;">
                        📚
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-item-class align-self-start course-level">Level</div>
                    <div class="enrolled-badge" style="display: none;">Enrolled</div>
                </div>
                <h5 class="subjects course-name">Class Name</h5>
                <p class="course-description" style="font-size: 14px; color: #666; margin: 0;">Course count</p>
                <button class="enroll-btn view-level-btn" type="button" data-level-id="">View Courses</button>
            </div>
        </template>
    </div>

    </div>

    <div class="chat-btn-circle">
        <i class="fa-solid fa-comment"></i>
    </div>

</main>
<script type="module">
import ToastNotification from '{{ asset("js/utils/toastNotification.js") }}';

document.addEventListener('DOMContentLoaded', async () => {
    // Load all available class levels
    await loadClassLevels();

    // Handle class level card clicks
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('button.view-level-btn');
        if (!btn) return;

        const levelId = btn.getAttribute('data-level-id');
        const levelName = btn.getAttribute('data-level-name');
        if (levelId) {
            // Navigate to courses for this level
            window.location.href = `/usersubject?level_id=${levelId}&level_name=${encodeURIComponent(levelName)}`;
        }
    });
});

async function loadClassLevels() {
    try {
        // Fetch levels from API
        const response = await fetch('/api/level', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            console.error('Failed to load levels:', response.statusText);
            ToastNotification.error('Error', 'Failed to load class levels');
            showEmptyState();
            return;
        }

        const data = await response.json();
        const levels = Array.isArray(data) ? data : data.data || data.levels || [];

        const container = document.getElementById('coursesContainer');
        const template = document.getElementById('courseCardTemplate');

        container.innerHTML = '';

        if (levels.length === 0) {
            showEmptyState();
            return;
        }

        // Render each level
        levels.forEach(level => {
            const card = template.content.cloneNode(true);

            // Update level data
            card.querySelector('.course-name').textContent = level.name || 'Untitled Level';
            card.querySelector('.course-level').textContent = level.name || 'Level';

            // Show course count if available
            const courseCount = level.courses ? level.courses.length : 0;
            card.querySelector('.course-description').textContent = `${courseCount} course${courseCount !== 1 ? 's' : ''} available`;

            // Set level ID for navigation
            const viewBtn = card.querySelector('.enroll-course-btn');
            viewBtn.setAttribute('data-level-id', level.id);
            viewBtn.setAttribute('data-level-name', level.name);
            viewBtn.textContent = 'View Courses';
            viewBtn.classList.remove('enroll-course-btn');
            viewBtn.classList.add('view-level-btn');

            // Hide enrolled badge for levels
            card.querySelector('.enrolled-badge').style.display = 'none';

            container.appendChild(card);
        });

    } catch (error) {
        console.error('Error loading levels:', error);
        ToastNotification.error('Error', 'An error occurred while loading class levels');
        showEmptyState();
    }
}

function showEmptyState() {
    const container = document.getElementById('coursesContainer');
    container.innerHTML = `
        <div class="empty-state" style="grid-column: 1 / -1;">
            <div class="empty-state-icon">📚</div>
            <h4>No Class Levels Available</h4>
            <p>Check back later for new class levels!</p>
        </div>
    `;
}
</script>
@endsection
