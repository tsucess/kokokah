<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('images/Kokokah_Logo.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka&family=Fredoka+One&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome - Local build to avoid CORS issues -->
    @vite(['resources/css/app.css'])

    <!-- Inter font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/style_theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsiveness.css') }}">
    <link rel="stylesheet" href="{{ asset('css/access.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}">
    {{-- @vite(['resources/css/dashboard.css','resources/css/access.css']) --}}

    <!-- Include stylesheet quilljs -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />

    <!-- Kokokah Loader Script - Load early in head -->
    <script src="{{ asset('js/utils/kokokahLoader.js') }}"></script>

    <!-- Inline loader HTML to show immediately -->
    <style>
        .kokokah-loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.95);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 1;
            visibility: visible;
        }
    </style>

</head>

<body>

    <!-- Overlay for mobile sidebar -->
    <div class="overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar" style="height: 100vh; overflow-y: auto; overflow-x: hidden;">
        <div class="brand p-3">
            <a href="/"><img src="{{ asset('images/KOKOKAH Logo.svg') }}" alt="Kokokah Logo"
                    class="img-fluid dashboard-logo"></a>
        </div>

        <nav class="nav-group px-2" id="sidebarNav">
            <!-- Dashboard link (always visible) -->
            <a class="nav-item-link d-flex align-items-center gap-3" href="/dashboard" id="dashboardLink">
                <i class="fa-solid fa-gauge nav-icon"></i> <span>Dashboard</span>
            </a>
            <!-- Additional menu items will be rendered by sidebarManager.js based on user role -->
        </nav>

        <div class="sidebar-footer mt-auto p-3">
            <!-- Settings link (Superadmin only - rendered by sidebarManager.js) -->
            {{-- <a class="nav-item-link" href="#" id="settingsLink" style="display: none;"><i class="fa-solid fa-gear pe-3"></i> Settings</a> --}}

            <div class="profile mt-3 d-flex align-items-center" id="profileSection">
                <img class="avatar" id="profileImage" src="{{ asset('images/default-avatar.png') }}" alt="user"
                    style="cursor: pointer; width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border: 2px solid #ff00;"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Profile">
                <div class="d-flex justify-content-between p-2 w-75 align-items-center">
                    <div id="profileInfo" style="cursor: pointer;" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Profile" class="w-50">
                        <h6 class="fw-semibold text-truncate" id="userName" style='text-transform: capitalize;'>Culacino_</h6>
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

    <!-- API Clients -->
    <script src="/js/api/baseApiClient.js"></script>
    <script src="/js/api/authClient.js"></script>
    <script src="/js/api/pointsAndBadgesApiClient.js"></script>
    <script src="/js/api/courseApiClient.js"></script>
    <script src="/js/api/lessonApiClient.js"></script>
    <script src="/js/api/quizApiClient.js"></script>
    <script src="/js/api/userApiClient.js"></script>
    <script src="/js/api/enrollmentApiClient.js"></script>
    <script src="/js/api/badgeApiClient.js"></script>
    <script src="/js/api/transactionApiClient.js"></script>
    <script src="/js/api/walletApiClient.js"></script>
    <script src="/js/api/paymentApiClient.js"></script>
    <script src="/js/api/topicApiClient.js"></script>
    <script src="/js/api/adminApiClient.js"></script>
    <script src="/js/api/notificationApiClient.js"></script>
    <script src="/js/utils/uiHelpers.js"></script>
    <script src="/js/utils/toastNotification.js"></script>
    <script src="/js/utils/notificationHelper.js"></script>

    <!-- Dashboard Module -->
    <script src="/js/dashboard.js"></script>
    <script>
        // Initialize dashboard when DOM is ready
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

            // Dashboard special case
            if (currentPath === '/dashboard' || currentPath === '/') {
                document.getElementById('dashboardLink')?.classList.add('active');
            } else {
                // Loop each nav link for other pages
                document.querySelectorAll('.nav-item-link, .nav-child').forEach(link => {
                    const href = link.getAttribute('href');

                    // Skip non-URL links (e.g., parent toggles with href="#subjectsMenu")
                    if (!href || href.startsWith('#')) return;

                    // Exact match only
                    if (href === currentPath) {
                        link.classList.add('active');

                        // If it's a child link, open parent dropdown
                        if (link.classList.contains('nav-child')) {
                            const parentMenu = link.closest('.collapse');
                            if (parentMenu && parentMenu.id) {
                                // Safely escape the ID for querySelector
                                const escapedId = CSS.escape(parentMenu.id);
                                const parentToggle = document.querySelector(`[href="#${escapedId}"]`);

                                // Open the parent dropdown without activating it
                                const bsCollapse = new bootstrap.Collapse(parentMenu, {
                                    toggle: false
                                });
                                bsCollapse.show();
                            }
                        }
                    }
                });
            }
        }

        // Call on page load with a slight delay to ensure sidebar is rendered
        document.addEventListener('DOMContentLoaded', () => {
            // Use setTimeout to ensure sidebar menu is fully rendered
            setTimeout(setActiveNavigation, 100);
        });

        // Also call when page changes (for SPAs)
        window.addEventListener('popstate', setActiveNavigation);




        // Toggling chevron icons and managing collapse state
        document.addEventListener('DOMContentLoaded', function() {
            // Select all collapsible parents
            document.querySelectorAll('.nav-parent').forEach(parent => {
                const targetId = parent.getAttribute('href');

                // Validate that targetId is a valid selector (should start with # or .)
                if (!targetId || (targetId[0] !== '#' && targetId[0] !== '.')) {
                    console.warn('Invalid target ID for nav-parent:', targetId);
                    return;
                }

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

                    // Validate that targetId is a valid selector (should start with # or .)
                    if (!targetId || (targetId[0] !== '#' && targetId[0] !== '.')) {
                        console.warn('Invalid target ID:', targetId);
                        return;
                    }

                    const target = document.querySelector(targetId);

                    if (!target) return;

                    // Check if current dropdown is already open
                    const isCurrentOpen = target.classList.contains('show');

                    // Close all other collapse menus
                    document.querySelectorAll('.collapse').forEach(collapse => {
                        if (collapse.id !== targetId && collapse.classList.contains(
                                'show')) {
                            const bsCollapse = bootstrap.Collapse.getInstance(collapse);
                            if (bsCollapse) {
                                bsCollapse.hide();
                            }
                        }
                    });

                    // Toggle current dropdown
                    const bsCollapse = bootstrap.Collapse.getInstance(target) || new bootstrap
                        .Collapse(target, {
                            toggle: false
                        });
                    if (isCurrentOpen) {
                        bsCollapse.hide();
                    } else {
                        bsCollapse.show();
                    }
                });
            });
        });
    </script>

    <!-- Confirmation Modal -->
    <script src="{{ asset('js/utils/confirmationModal.js') }}"></script>

    <!-- Sidebar Manager - Renders menu items based on user role from localStorage -->
    <script src="{{ asset('js/sidebarManager.js') }}"></script>

    <!-- Inactivity Timeout Manager - Auto logout after 30 minutes of inactivity -->
    <script src="{{ asset('js/utils/inactivityTimeout.js') }}"></script>

</body>

</html>
