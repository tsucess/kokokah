<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('images/Kokokah_Logo.png') }}" />

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome - Local build to avoid CORS issues -->
    @vite(['resources/css/app.css'])
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
    <link rel="stylesheet" href="{{ asset('css/responsiveness.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}">
    <!-- Badge Congratulation Modal CSS -->
    <link rel="stylesheet" href="{{ asset('css/badgeCongratulationModal.css') }}">

    {{-- @vite(['resources/css/dashboard.css']) --}}

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

        .img {
            width: 30px;
            aspect-ratio: 1/1;
            object-fit: contain;
        }

        .point {
            font-size: 14px;
        }

        @media screen and (max-width:768px) {
            .img {
                width: 20px;
            }

            .point {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

    <!-- Overlay for mobile sidebar -->
    <div class="overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <img src="{{ asset('images/KOKOKAH Logo.svg') }}" alt="Kokokah Logo" class="img-fluid dashboard-logo">
        </div>

        <nav class="nav-group" id="sidebarNav">
            <a class="nav-item-link" href="/usersdashboard"><i class="fa-solid fa-gauge pe-2"></i> <span data-i18n="nav.dashboard">Dashboard</span></a>

            <a class="nav-item-link" href="/userclass"><i class="fa-solid fa-book-open me-2 pe-2"></i> <span data-i18n="nav.class">Class</span></a>

            <a class="nav-item-link" href="/usersubject"><i class="fa-solid fa-user me-2  pe-2"></i> <span data-i18n="nav.subject">Subject</span></a>

            <a class="nav-item-link" href="/userresult"><i class="fa-solid fa-chart-line me-2  pe-2"></i> <span data-i18n="nav.results">Results & Scoring</span></a>

            <a class="nav-item-link" href="/userkudikah"><i class="fa-solid fa-wallet me-2 pe-2"></i> <span data-i18n="nav.kudikah">Kudikah</span></a>
            <a class="nav-item-link" href="/usersubscriptionhistory"><i
                    class="fa-solid fa-money-bill-transfer me-2 pe-2"></i> <span data-i18n="nav.subscription_history">Subscription History</span></a>
            <a class="nav-item-link" href="/userleaderboard"><i
                    class="fa-solid fa-trophy me-2 pe-2"></i> <span data-i18n="nav.leaderboard">Leaderboard</span></a>
            {{-- <a class="nav-item-link" href="/userkoodies"><i class="fa-solid fa-robot me-2 pe-2"></i>Ai</a> --}}
            <a class="nav-item-link" href="/chatroom"><i class="fa-solid fa-comment me-2 pe-2"></i> <span data-i18n="nav.chatroom">Chatroom</span></a>

            <!-- Communication -->
            <a class="nav-item-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                href="#communication" role="button" aria-expanded="false" aria-controls="communication">
                <span><i class="fa-solid fa-comments me-2 pe-2"></i> <span data-i18n="nav.communication">Communication</span></span>
                <i class="fa-solid fa-chevron-down small"></i>
            </a>

            <!-- communication dropdowns -->
            <div class="collapse ps-4" id="communication">
                <a class="nav-item-link d-block" href="/userannouncement"><span data-i18n="nav.notifications">Notifications</span></a>
                <a class="nav-item-link d-block" href="/userfeedback"><span data-i18n="nav.feedback_surveys">Feedback / Surveys</span></a>
            </div>


        </nav>



        <div class="sidebar-footer mt-auto p-3">
            {{-- <a class="nav-item-link" href="#"><i class="fa-solid fa-gear pe-3"></i> <span data-i18n="nav.settings">Settings</span></a> --}}
            <div class="profile mt-5" id="profileSection">
                <img class="avatar" id="profileImage" src="{{ asset('images/default-avatar.png') }}" alt="user"
                    style="cursor: pointer; width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border: 2px solid #ff00;"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Profile">
                <div class="d-flex justify-content-between p-2 w-75 align-items-center">
                    <div id="profileInfo" style="cursor: pointer;" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Profile" class="w-50">
                        <h6 class="fw-semibold text-truncate" id="userName" style="text-transform: capitalize">
                            Culacino_</h6>
                        <p class="small text-muted" id="userRole" style="text-transform: capitalize">UX Designer</p>
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


        <div class="d-flex gap-2 shadow-sm rounded-pill align-items-center py-1 py-md-2 px-3  mx-1 mx-lg-3">
            <div class="d-flex align-items-center gap-1">
                <i class="fa-solid fa-trophy fa-xs" style="color: #EF4444

;"></i><span data-badges class="point">0</span>
            </div>

            <div class="ps-2 d-flex align-items-center gap-1" style="border-left: 1px solid #000000;"><i
                    class="fa-solid fa-coins fa-xs" style="color: #F59E0B

;"></i> <span data-points
                    class="point">0</span></div>

        </div>

        <div class="top-icons">
            <button class="icon-btn round-2 icon-btn-light" title="Notifications" id="notificationBellBtn"
                style="position: relative;">
                <i class="fa-regular fa-bell fa-2xs"></i>
            </button>
            {{-- <button class="icon-btn round-2 icon-btn-light" title="message"><i
                    class="fa-regular fa-envelope fa-2xs"></i></button> --}}
            <button class="icon-btn round-2 icon-btn-light" title="Help & FAQ"
                onclick="window.location.href='/help'">
                <i class="fa-solid fa-question fa-2xs"></i>
            </button>
        </div>
    </header>

    @yield('content')

    <!-- Footer -->
    {{-- <footer
        class="d-flex page-footer flex-column align-items-center gap-2 gap-md-0 flex-md-row justify-content-between mt-auto"
        style="background-color: inherit;">
        <p class="footer-link-text text-center">© Copyright Kokokah 2025. All rights reserved.</p>

        <div class ="d-flex flex-column gap-1 gap-md-3 align-items-center flex-md-row">
            <a href="#" class = "text-decoration-none  footer-link-text">License</a>
            <a href="#" class = "text-decoration-none  footer-link-text">More Themes</a>
            <a href="#" class = "text-decoration-none footer-link-text">Documentation</a>
            <a href="#" class = "text-decoration-none footer-link-text">Support</a>
        </div>


    </footer> --}}
    </div>
    </main>

    <!-- Chart.js (keep after body) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

    <!-- Axios (required for API calls) -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- API Clients (must load before any scripts that use them) -->
    <script src="{{ asset('js/api/baseApiClient.js') }}"></script>
    <script src="{{ asset('js/api/authClient.js') }}"></script>
    <script src="{{ asset('js/api/courseApiClient.js') }}"></script>
    <script src="{{ asset('js/api/adminApiClient.js') }}"></script>
    <script src="{{ asset('js/api/enrollmentApiClient.js') }}"></script>
    <script src="{{ asset('js/api/lessonApiClient.js') }}"></script>
    <script src="{{ asset('js/api/paymentApiClient.js') }}"></script>
    <script src="{{ asset('js/api/quizApiClient.js') }}"></script>
    <script src="{{ asset('js/api/topicApiClient.js') }}"></script>
    <script src="{{ asset('js/api/transactionApiClient.js') }}"></script>
    <script src="{{ asset('js/api/userApiClient.js') }}"></script>
    <script src="{{ asset('js/api/walletApiClient.js') }}"></script>
    <script src="{{ asset('js/api/badgeApiClient.js') }}"></script>
    <script src="{{ asset('js/api/subscriptionApiClient.js') }}"></script>
    <script src="{{ asset('js/api/pointsAndBadgesApiClient.js') }}"></script>
    <script src="{{ asset('js/utils/toastNotification.js') }}"></script>
    <script src="{{ asset('js/utils/uiHelpers.js') }}"></script>
    <script src="{{ asset('js/utils/notificationHelper.js') }}"></script>

    <!-- Dashboard Module -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
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
                // If this link toggles a collapse (dropdown), don't close sidebar
                if (link.hasAttribute('data-bs-toggle')) return;
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

        // Initialize communication dropdown toggle
        function initCommunicationDropdown() {
            const communicationToggle = document.querySelector('[href="#communication"]');
            const communicationCollapse = document.getElementById('communication');

            if (communicationToggle && communicationCollapse) {
                // Initialize Bootstrap Collapse instance
                const collapseInstance = new bootstrap.Collapse(communicationCollapse, {
                    toggle: false
                });

                // Handle toggle click
                communicationToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Toggle the collapse
                    if (communicationCollapse.classList.contains('show')) {
                        collapseInstance.hide();
                    } else {
                        collapseInstance.show();
                    }
                });

                // Update aria-expanded attribute when collapse changes
                communicationCollapse.addEventListener('show.bs.collapse', function() {
                    communicationToggle.setAttribute('aria-expanded', 'true');
                });

                communicationCollapse.addEventListener('hide.bs.collapse', function() {
                    communicationToggle.setAttribute('aria-expanded', 'false');
                });
            }
        }

        // Initialize on DOMContentLoaded or immediately if DOM is already loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCommunicationDropdown);
        } else {
            initCommunicationDropdown();
        }
    </script>

    <!-- Notification Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-bell" style="color: #ffffff; font-size: 20px;"></i>
                        <h5 class="modal-title" id="notificationModalLabel" data-i18n="notifications.title">Notifications</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Notifications List -->
                    <div id="notificationsList" class="notification-list">
                        <p class="text-muted text-center py-4" data-i18n="notifications.loading">Loading notifications...</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-i18n="notifications.close">
                        <i class="fa-solid fa-times" style="margin-right: 6px;"></i>Close
                    </button>
                    <button type="button" class="btn btn-primary" id="markAllReadBtn" data-i18n="notifications.mark_all_read">
                        <i class="fa-solid fa-check-double" style="margin-right: 6px;"></i>Mark All as Read
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <script src="{{ asset('js/utils/confirmationModal.js') }}"></script>

    <!-- Notification API Client -->
    <script src="{{ asset('js/api/notificationApiClient.js') }}"></script>

    <!-- Notification Modal Component -->
    <script src="{{ asset('js/components/notificationModal.js') }}"></script>

    <!-- Points Conversion Component -->
    <script src="{{ asset('js/components/pointsConversionComponent.js') }}"></script>

    <!-- Data Refresh Services -->
    <script src="{{ asset('js/services/dataRefreshService.js') }}"></script>
    <script src="{{ asset('js/services/dashboardRefreshManager.js') }}"></script>
    <script src="{{ asset('js/services/walletRefreshManager.js') }}"></script>
    <script src="{{ asset('js/services/activityRefreshManager.js') }}"></script>
    <script src="{{ asset('js/services/enrollmentEventEmitter.js') }}"></script>

    <!-- Badge Congratulation Modal Component -->
    <script src="{{ asset('js/components/badgeCongratulationModal.js') }}"></script>
    <!-- Badge Award Wrapper Service -->
    <script src="{{ asset('js/services/badgeAwardService.js') }}"></script>

    <!-- Initialize Badge Modal and Notification Modal -->
    <script>
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                // Initialize Badge Congratulation Modal
                if (window.BadgeCongratulationModal) {
                    window.BadgeCongratulationModal.init();
                }
                // Initialize Notification Modal
                if (window.NotificationModalComponent) {
                    window.NotificationModalComponent.init();
                }
            });
        } else {
            // Initialize Badge Congratulation Modal
            if (window.BadgeCongratulationModal) {
                window.BadgeCongratulationModal.init();
            }
            // Initialize Notification Modal
            if (window.NotificationModalComponent) {
                window.NotificationModalComponent.init();
            }
        }
    </script>

    <!-- Inactivity Timeout Manager - Auto logout after 30 minutes of inactivity -->
    <script src="{{ asset('js/utils/inactivityTimeout.js') }}"></script>

    <!-- Internationalization (i18n) Manager - Load translations and apply to DOM -->
    <script src="{{ asset('js/utils/i18n.js') }}"></script>
</body>

</html>
