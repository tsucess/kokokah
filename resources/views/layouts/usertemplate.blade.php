<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Dashboard</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('images/Kokokah_Logo.png') }}" />

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- chartjs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style_theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    {{-- @vite(['resources/css/dashboard.css']) --}}
</head>

<body>

    <!-- Overlay for mobile sidebar -->
    <div class="overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <img src="{{ asset('images/Kokokah_Logo.png') }}" alt="Kokokah Logo" class="img-fluid dashboard-logo">
        </div>

        <nav class="nav-group" id="sidebarNav">
            <a class="nav-item-link" href="/usersdashboard"><i class="fa-solid fa-gauge pe-3"></i> Dashboard</a>

            <a class="nav-item-link" href="/userclass"><i class="fa-solid fa-landmark me-2 pe-2"></i></i> Class</a>

            <a class="nav-item-link" href="/usersubject"><i class="fa-solid fa-book-open me-2 pe-2"></i> Subject</a>

            <a class="nav-item-link" href="/userresult"><i class="fa-solid fa-clipboard-list me-2 pe-2"></i> Results &
                Scoring</a>

            <a class="nav-item-link" href="/userkudikah"><i class="fa-solid fa-wallet me-2 pe-2"></i>Kudikah</a>

            <a class="nav-item-link" href="/userleaderboard"><i class="fa-solid fa-trophy me-2 pe-2"></i></i>Leaderboard</a>
            <a class="nav-item-link" href="/userkoodies"><i class="fa-solid fa-robot me-2 pe-2"></i>Ai</a>
            <a class="nav-item-link" href="/chatroom"><i class="fa-solid fa-comment me-2 pe-2"></i>Chatroom</a>

            <!-- Communication -->
            <a class="nav-item-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                href="#communication" role="button" aria-expanded="false" aria-controls="communication">
                <span><i class="fa-solid fa-comments me-2 pe-2"></i> Communication</span>
                <i class="fa-solid fa-chevron-down small"></i>
            </a>

            <!-- communication dropdowns -->
            <div class="collapse ps-4" id="communication">
                <a class="nav-item-link d-block" href="/userannouncement">Announcement</a>
                {{-- <a class="nav-item-link d-block" href="#">Email / Messaging Center</a> --}}
                <a class="nav-item-link d-block" href="/userfeedback">Feedback / Surveys</a>
            </div>


        </nav>



        <div class="sidebar-footer">
            <a class="nav-item-link" href="/userprofile"><i class="fa-solid fa-gear pe-3"></i> Settings</a>
            <div class="profile mt-3" id="profileSection">
                <img class="avatar" id="profileImage" src="{{ asset('images/winner-round.png') }}" alt="user"
                    style="cursor: pointer; width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border: 2px solid #ff00;"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Profile">
                <div>
                    <div class="fw-bold" id="userName">Loading...</div>
                    <div class="text-muted small" id="userRole">Student</div>
                </div>
            </div>
            <a class="nav-item-link text-danger" href="#" id="logoutBtn"><i class="fa-solid fa-sign-out-alt pe-3"></i> Logout</a>
        </div>
    </aside>

    <!-- Topbar -->
    <header class="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-light d-lg-none" id="hamburger"><i class="fa-solid fa-bars"></i></button>
            {{-- <div class="d-lg-none small text-muted">Welcome back,</div>
      <div class="d-lg-none fw-bold">Samuel (Admin)</div> --}}
        </div>

        <div class="search-wrap mx-3">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input class="search-input" type="text" placeholder="Search">
        </div>

        <div class="top-icons">
            <button class="icon-btn round-2 icon-btn-light" title="bell"><i class="fa-regular fa-bell"></i></button>
            <button class="icon-btn round-2 icon-btn-light" title="message"><i
                    class="fa-regular fa-envelope"></i></button>
            <button class="icon-btn round-2 icon-btn-light" title="question"><i
                    class="fa-solid fa-question"></i></button>
        </div>
    </header>

    @yield('content')

    <!-- Footer -->
    <div
        class="d-flex flex-column align-items-center gap-1 px-3 py-md-4 flex-md-row page-footer justify-content-between justify-content-md-start gap-4">
        <div class="text-center page-footer-link">© Copyright Kokokah 2025. All rights reserved.</div>

        <div class = "d-flex flex-column align-items-center align-items-md-start flex-md-row gap-md-3">
            <a href="#" class = "text-decoration-none page-footer-link">License</a>
            <a href="#" class = "text-decoration-none page-footer-link">More Themes</a>
            <a href="#" class = "text-decoration-none page-footer-link">Documentation</a>
            <a href="#" class = "text-decoration-none page-footer-link">Support</a>
        </div>


    </div>
    </div>
    </main>

    <!-- Chart.js (keep after body) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

    <script type="module">
        import UserApiClient from '{{ asset("js/api/userApiClient.js") }}';
        import AuthApiClient from '{{ asset("js/api/authClient.js") }}';
        import ToastNotification from '{{ asset("js/utils/toastNotification.js") }}';

        // Mobile sidebar toggle behavior
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const hamburger = document.getElementById('hamburger');

        function openSidebar() {
            sidebar.classList.add('show');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        hamburger.addEventListener('click', (e) => {
            openSidebar();
        });
        overlay.addEventListener('click', () => closeSidebar());

        // Close sidebar when clicking any sidebar nav link on mobile
        document.querySelectorAll('.nav-item-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) closeSidebar();
            });
        });

        // Ensure overlay/ sidebar state resets on window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) {
                // desktop: ensure overlay hidden and sidebar visible
                overlay.classList.remove('show');
                sidebar.classList.remove('show');
                document.body.style.overflow = '';
            }
        });

        // Load user profile data from localStorage
        function loadUserProfile() {
            const user = AuthApiClient.getUser();

            if (!user) {
                console.log('No user data found in localStorage');
                return;
            }

            // Update user name
            const userName = document.getElementById('userName');
            if (userName && user.first_name && user.last_name) {
                userName.textContent = `${user.first_name} ${user.last_name}`;
            }

            // Update user role
            const userRole = document.getElementById('userRole');
            if (userRole && user.role) {
                const roleText = user.role.charAt(0).toUpperCase() + user.role.slice(1);
                userRole.textContent = roleText;
            }

            // Update profile image if available
            const profileImage = document.getElementById('profileImage');
            if (profileImage) {
                if (user.profile_photo) {
                    // Check if profile_photo is already a full URL (starts with /)
                    if (user.profile_photo.startsWith('/')) {
                        profileImage.src = user.profile_photo;
                        console.log('Profile photo is a full URL:', user.profile_photo);
                    } else {
                        // Otherwise, add /storage/ prefix
                        profileImage.src = `/storage/${user.profile_photo}`;
                        console.log('Profile photo is a relative path, added /storage/ prefix:', profileImage.src);
                    }
                } else {
                    // Use default avatar if no profile photo
                    profileImage.src = '{{ asset("images/winner-round.png") }}';
                    console.log('No profile photo, using default avatar');
                }
            }
        }

        // Handle logout
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                try {
                    const result = await AuthApiClient.logout();
                    ToastNotification.success('Logged Out', 'You have been successfully logged out.');
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 1500);
                } catch (error) {
                    console.error('Logout error:', error);
                    ToastNotification.error('Logout Failed', 'An error occurred while logging out.');
                }
            });
        }

        // Load user profile on page load
        document.addEventListener('DOMContentLoaded', loadUserProfile);
    </script>
</body>

</html>
