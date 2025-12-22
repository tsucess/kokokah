<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Dashboard</title>

    <link rel="icon" type="image/x-icon" href="images/Kokokah_Logo.png" />

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

    <!-- Axios (required for API calls) -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

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
            <img src="images/Kokokah_Logo.png" alt="Kokokah Logo" class="img-fluid dashboard-logo">
        </div>

        <nav class="nav-group" id="sidebarNav">
            <a class="nav-item-link" href="/usersdashboard"><i class="fa-solid fa-gauge pe-3"></i> Dashboard</a>

            <a class="nav-item-link" href="/userclass"><i class="fa-solid fa-book-open me-2  pe-1"></i> Class</a>

            <a class="nav-item-link" href="/usersubject"><i class="fa-solid fa-user me-2  pe-2"></i> Subject</a>

            <a class="nav-item-link" href="/userresult"><i class="fa-solid fa-user me-2  pe-2"></i> Results &
                Scoring</a>

            <a class="nav-item-link" href="/userkudikah"><i class="fa-solid fa-wallet me-2 pe-2"></i>Kudikah</a>
            <a class="nav-item-link" href="/usersubscriptionhistory"><i class="fa-solid fa-money-bill-transfer me-2 pe-2"></i></i>Subscription History</a>
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
                <a class="nav-item-link d-block" href="#">Announcement</a>
                <a class="nav-item-link d-block" href="#">Email / Messaging Center</a>
                <a class="nav-item-link d-block" href="/userfeedback">Feedback / Surveys</a>
            </div>


        </nav>



        <div class="sidebar-footer mt-auto p-3">
            <a class="nav-item-link" href="#"><i class="fa-solid fa-gear pe-3"></i> Settings</a>
            <div class="profile mt-3" id="profileSection">
                <img class="avatar" id="profileImage" src="images/winner-round.png" alt="user"
                    style="cursor: pointer; width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border: 2px solid #ff00;"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Profile">
                <div class="d-flex justify-content-between mt-4 p-2 w-100 align-items-center">
                    <div id="profileInfo" style="cursor: pointer;" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Profile" class="w-50">
                        <h6 class="fw-semibold text-truncate" id="userName">Culacino_</h6>
                        <p class="small text-muted" id="userRole">UX Designer</p>
                    </div>
                    <div class="logout">
                        <a href="#" id="logoutBtn" title="Logout">
                            <span>
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Topbar -->
    <header class="topbar">

        <button class="btn btn-light d-lg-none" id="hamburger"><i class="fa-solid fa-bars"></i></button>


        <div class="d-flex gap-2 shadow-sm rounded-pill align-items-center py-2 px-3  mx-1 mx-lg-3">
            <div><img src="./images/leaderboard-award-icon.png" alt=""> <span data-badges>0</span></div>
            <div></div>
            <div class="ps-2" style="border-left: 1px solid #000000;"><img src="./images/point-icon.png"
                    alt=""> <span data-points>0</span></div>

        </div>

        <div class="top-icons">
            <button class="icon-btn round-2 icon-btn-light" title="bell"><i
                    class="fa-regular fa-bell fa-xs"></i></button>
            <button class="icon-btn round-2 icon-btn-light" title="message"><i
                    class="fa-regular fa-envelope fa-xs"></i></button>
            <button class="icon-btn round-2 icon-btn-light" title="question"><i
                    class="fa-solid fa-question fa-xs"></i></button>
        </div>
    </header>

    @yield('content')

    <!-- Footer -->
    <div class="d-flex  page-footer flex-column align-items-center gap-2 gap-md-0 flex-md-row justify-content-between">
        <div class="small text-muted">© Copyright Kokokah 2025. All rights reserved.</div>

        <div class = "small text-muted">
            <a href="#" class = "text-decoration-none text-muted">License</a>&nbsp;
            <a href="#" class = "text-decoration-none text-muted">More Themes</a>&nbsp;
            <a href="#" class = "text-decoration-none text-muted">Documentation</a>&nbsp;
            <a href="#" class = "text-decoration-none text-muted">Support</a>
        </div>


    </div>
    </div>
    </main>

    <!-- Chart.js (keep after body) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

    <!-- Axios (required for API calls) -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Dashboard Module -->
    <script type="module">
        import DashboardModule from '/js/dashboard.js'; // Initialize dashboard when DOM is ready

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                DashboardModule.init();
            });
        } else {
            DashboardModule.init();
        }
    </script>

    <!-- Mobile sidebar toggle behavior -->
    <script>
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
    </script>

    <!-- Confirmation Modal -->
    <script src="{{ asset('js/utils/confirmationModal.js') }}"></script>
</body>

</html>
