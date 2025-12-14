<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Dashboard</title>

    <link rel="icon" type="image/x-icon" href="images/Kokokah_Logo.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka&family=Fredoka+One&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Inter font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

     <link rel="stylesheet" href="{{ asset('css/style_theme.css') }}">
     <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/access.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}">
    {{-- @vite(['resources/css/dashboard.css','resources/css/access.css']) --}}

    <!-- Include stylesheet quilljs -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />


</head>

<body>

    <!-- Loading Overlay -->
    <div id="loadingOverlay"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9998; justify-content: center; align-items: center;">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Overlay for mobile sidebar -->
    <div class="overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar" style="height: 100vh; overflow-y: auto; overflow-x: hidden;">
        <div class="brand p-3">
            <a href="/"><img src="images/Kokokah_Logo.png" alt="Kokokah Logo"
                    class="img-fluid dashboard-logo"></a>
        </div>

        <nav class="nav-group px-2" id="sidebarNav">
            <a class="nav-item-link" href="/dashboard" id="dashboardLink">
                <i class="fa-solid fa-gauge pe-2"></i> Dashboard
            </a>

            <!-- Users Management -->
            <a class="nav-item-link d-flex justify-content-between align-items-center nav-parent"
                data-bs-toggle="collapse" href="#usersMenu" role="button" aria-expanded="true"
                aria-controls="usersMenu">
                <span><i class="fa-solid fa-users pe-3"></i> Users Management</span>
                <i class="fa-solid fa-chevron-down chevron-icon"></i>
            </a>

            <!-- Dropdown items -->
            <div class="collapse ps-4" id="usersMenu">
                <a class="nav-item-link d-block nav-child" href="/users">All Users</a>
                <a class="nav-item-link d-block nav-child" href="/students">Students</a>
                <a class="nav-item-link d-block nav-child" href="/instructors">Instructors</a>
                <a class="nav-item-link d-block nav-child" href="/adduser">Add Users</a>
                <a class="nav-item-link d-block nav-child" href="/useractivity">Users Activity Log</a>
            </div>

            <!-- Subject Management -->
            <a class="nav-item-link d-flex justify-content-between align-items-center nav-parent"
                data-bs-toggle="collapse" href="#subjectsMenu" role="button" aria-expanded="true"
                aria-controls="subjectsMenu">
                <span><i class="fa-solid fa-book-open me-2 pe-2"></i> Course Management</span>
                <i class="fa-solid fa-chevron-down chevron-icon"></i>
            </a>


            <!-- Dropdown items -->
            <div class="collapse ps-4" id="subjectsMenu">
                <a class="nav-item-link d-block nav-child" href="/subjects">All Courses</a>
                <a class="nav-item-link d-block nav-child" href="/createsubject">Create New Course</a>
                <a class="nav-item-link d-block nav-child" href="/categories">Course Categories</a>
                <a class="nav-item-link d-block nav-child" href="/curriculum-categories">Curriculum Categories</a>
                <a class="nav-item-link d-block nav-child" href="/levels">Levels & Classes</a>
                <a class="nav-item-link d-block nav-child" href="/terms">Academic Terms</a>
                <a class="nav-item-link d-block nav-child" href="/rating">Course Reviews & Rating</a>
                <a class="nav-item-link d-block nav-child" href="#">Course Approval</a>
            </div>

            <a class="nav-item-link d-flex justify-content-between align-items-center nav-parent"
                data-bs-toggle="collapse" href="#paymentsMenu" role="button" aria-expanded="false"
                aria-controls="paymentsMenu">
                <span><i class="fa-solid fa-credit-card pe-3"></i> Payments & Transactions</span>

                <i class="fa-solid fa-chevron-down small chevron-icon"></i>
            </a>

            <div class="collapse ps-4" id="paymentsMenu">
                <a class="nav-item-link d-block nav-child" href="/transactions">Transactions</a>
                <a class="nav-item-link d-block nav-child" href="#">Payment History</a>
                <a class="nav-item-link d-block nav-child" href="#">Invoices</a>
            </div>

            <a class="nav-item-link d-flex justify-content-between align-items-center nav-parent"
                data-bs-toggle="collapse" href="#analyticsMenu" role="button" aria-expanded="false"
                aria-controls="analyticsMenu">
                <span><i class="fa-solid fa-chart-line pe-3"></i> Reports & Analytics</span>
                <i class="fa-solid fa-chevron-down small chevron-icon"></i>
            </a>

            <div class="collapse ps-4" id="analyticsMenu">
                <a class="nav-item-link d-block nav-child" href="#">Reports</a>
                <a class="nav-item-link d-block nav-child" href="#">Analytics</a>
            </div>

            <a class="nav-item-link d-flex justify-content-between align-items-center nav-parent"
                data-bs-toggle="collapse" href="#communicationMenu" role="button" aria-expanded="false"
                aria-controls="communicationMenu">
                <span><i class="fa-solid fa-comments pe-3"></i> Communication</span>
                <i class="fa-solid fa-chevron-down small chevron-icon"></i>
            </a>

            <div class="collapse ps-4" id="communicationMenu">
                <a class="nav-item-link d-block nav-child" href="#">Messages</a>
                <a class="nav-item-link d-block nav-child" href="/announcement">Notifications</a>
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
                        <a href="#" id="logoutBtn" title="Logout"><span><i
                                    class="fa-solid fa-arrow-right-from-bracket"></i></span></a>
                    </div>
                </div>
            </div>
        </div>
    </aside>


    <!-- Topbar -->
    <header class="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-light d-lg-none" id="hamburger"><i class="fa-solid fa-bars"></i></button>
            <div class="d-lg-none small text-muted">Welcome back,</div>
            <div class="d-lg-none fw-bold">Samuel (Admin)</div>
        </div>

        <div class="search-wrap mx-3">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input class="search-input" type="text" placeholder="Search">
        </div>

        <div class="top-icons">
            <button class="icon-btn round-2 icon-btn-light" title="bell"><i
                    class="fa-regular fa-bell"></i></button>
            <button class="icon-btn round-2 icon-btn-light" title="message"><i
                    class="fa-regular fa-envelope"></i></button>
            <button class="icon-btn round-2 icon-btn-light" title="question"><i
                    class="fa-solid fa-question"></i></button>
        </div>
    </header>

    <!-- Alert Container -->
    <div id="alertContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;"></div>

    @yield('content')



    <!-- Footer -->
    <div class="d-flex page-footer justify-content-between">
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
        import DashboardModule from '{{ asset('js/dashboard.js') }}'; // Initialize dashboard when DOM is ready

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                DashboardModule.init();
            });
        } else {
            DashboardModule.init();
        }
    </script>

    <script>
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

        // Close sidebar when clicking any sidebar nav link on mobile (except parent items)
        document.querySelectorAll('.nav-item-link:not(.nav-parent)').forEach(link => {
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

        // Active navigation state detection
        function setActiveNavigation() {
    const currentPath = window.location.pathname;

    // Clear previous active states
    document.querySelectorAll('.nav-item-link, .nav-child').forEach(link => {
        link.classList.remove('active');
    });

    // Loop each nav link
    document.querySelectorAll('.nav-item-link, .nav-child').forEach(link => {
        const href = link.getAttribute('href');

        // Skip non-URL links (e.g., parent toggles with href="#subjectsMenu")
        if (!href || href.startsWith('#')) return;

        // Exact match only
        if (href === currentPath) {
            link.classList.add('active');

            // If it's a child link, open parent dropdown & activate parent
            if (link.classList.contains('nav-child')) {
                const parentMenu = link.closest('.collapse');
                if (parentMenu) {
                    const parentToggle = document.querySelector(`[href="#${parentMenu.id}"]`);


                    const bsCollapse = new bootstrap.Collapse(parentMenu, {
                        toggle: false
                    });
                    bsCollapse.show();
                }
            }
        }
    });

    // Dashboard special case
    if (currentPath === '/dashboard' || currentPath === '/') {
        document.getElementById('dashboardLink')?.classList.add('active');
    }
}

        // Call on page load
        document.addEventListener('DOMContentLoaded', setActiveNavigation);

        // Also call when page changes (for SPAs)
        window.addEventListener('popstate', setActiveNavigation);




        // Toggling chevron icons and managing collapse state
        document.addEventListener('DOMContentLoaded', function() {
            // Select all collapsible parents
            document.querySelectorAll('.nav-parent').forEach(parent => {
                const targetId = parent.getAttribute('href');
                const target = document.querySelector(targetId);
                const icon = parent.querySelector('.chevron-icon');

                if (!target || !icon) return;

                // When collapse is shown
                target.addEventListener('show.bs.collapse', () => {
                    icon.classList.add('rotate');
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                });

                // When collapse is hidden
                target.addEventListener('hide.bs.collapse', () => {
                    icon.classList.remove('rotate');
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                });
            });

            // Close other dropdowns when one is opened, and toggle current dropdown
            document.querySelectorAll('.nav-parent').forEach(parent => {
                parent.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const target = document.querySelector(targetId);

                    if (!target) return;

                    // Check if current dropdown is already open
                    const isCurrentOpen = target.classList.contains('show');

                    // Close all other collapse menus
                    document.querySelectorAll('.collapse').forEach(collapse => {
                        if (collapse.id !== targetId && collapse.classList.contains('show')) {
                            const bsCollapse = bootstrap.Collapse.getInstance(collapse);
                            if (bsCollapse) {
                                bsCollapse.hide();
                            }
                        }
                    });

                    // Toggle current dropdown
                    const bsCollapse = bootstrap.Collapse.getInstance(target) || new bootstrap.Collapse(target, { toggle: false });
                    if (isCurrentOpen) {
                        bsCollapse.hide();
                    } else {
                        bsCollapse.show();
                    }
                });
            });
        });
    </script>

    <!-- Kokokah Logo Loader -->
    <script src="{{ asset('js/utils/kokokahLoader.js') }}"></script>

    <!-- Confirmation Modal -->
    <script src="{{ asset('js/utils/confirmationModal.js') }}"></script>

</body>

</html>
