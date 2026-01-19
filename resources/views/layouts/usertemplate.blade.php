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
            width: 15px;
            aspect-ratio:1/1;
        }
        .point{
            font-size: 14px;
        }
        @media screen and (max-width:768px){
            .img{
                width: 13px;
            }
            .point{
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
            <a class="nav-item-link" href="/usersdashboard"><i class="fa-solid fa-gauge pe-3"></i> Dashboard</a>

            <a class="nav-item-link" href="/userclass"><i class="fa-solid fa-book-open me-2  pe-1"></i> Class</a>

            <a class="nav-item-link" href="/usersubject"><i class="fa-solid fa-user me-2  pe-2"></i> Subject</a>

            <a class="nav-item-link" href="/userresult"><i class="fa-solid fa-chart-line me-2  pe-2"></i> Results &
                Scoring</a>

            <a class="nav-item-link" href="/userkudikah"><i class="fa-solid fa-wallet me-2 pe-2"></i>Kudikah</a>
            <a class="nav-item-link" href="/usersubscriptionhistory"><i
                    class="fa-solid fa-money-bill-transfer me-2 pe-2"></i></i>Subscription History</a>
            <a class="nav-item-link" href="/userleaderboard"><i
                    class="fa-solid fa-trophy me-2 pe-2"></i></i>Leaderboard</a>
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
                <a class="nav-item-link d-block" href="/userannouncement">Notifications</a>
                <a class="nav-item-link d-block" href="/userfeedback">Feedback / Surveys</a>
            </div>


        </nav>



        <div class="sidebar-footer mt-auto p-3">
            <a class="nav-item-link" href="#"><i class="fa-solid fa-gear pe-3"></i> Settings</a>
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
            <div class="d-flex align-items-center gap-1"><img src="./images/leaderboard-award-icon.png"
                    alt="" class="img"> <span data-badges class="point">0</span></div>

            <div class="ps-2 d-flex align-items-center gap-1" style="border-left: 1px solid #000000;"><img
                    src="./images/point-icon.png" alt="" class="img"> <span data-points class="point">0</span></div>

        </div>

        <div class="top-icons">
            <button class="icon-btn round-2 icon-btn-light" title="Notifications" id="notificationBellBtn"
                style="position: relative;">
                <i class="fa-regular fa-bell fa-2xs"></i>
            </button>
            <button class="icon-btn round-2 icon-btn-light" title="message"><i
                    class="fa-regular fa-envelope fa-2xs"></i></button>
            <button class="icon-btn round-2 icon-btn-light" title="Help & FAQ"
                onclick="window.location.href='/help'">
                <i class="fa-solid fa-question fa-2xs"></i>
            </button>
        </div>
    </header>

    @yield('content')

    <!-- Footer -->
    <footer
        class="d-flex page-footer flex-column align-items-center gap-2 gap-md-0 flex-md-row justify-content-between mt-auto"
        style="background-color: inherit;">
        <p class="footer-link-text text-center">© Copyright Kokokah 2025. All rights reserved.</p>

        <div class ="d-flex flex-column gap-1 gap-md-3 align-items-center flex-md-row">
            <a href="#" class = "text-decoration-none  footer-link-text">License</a>
            <a href="#" class = "text-decoration-none  footer-link-text">More Themes</a>
            <a href="#" class = "text-decoration-none footer-link-text">Documentation</a>
            <a href="#" class = "text-decoration-none footer-link-text">Support</a>
        </div>


    </footer>
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
    </script>

    <!-- Notification Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="announcements-tab" data-bs-toggle="tab"
                                href="#announcements" role="tab" aria-controls="announcements"
                                aria-selected="true">
                                Announcements
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="messages-tab" data-bs-toggle="tab" href="#messages"
                                role="tab" aria-controls="messages" aria-selected="false">
                                Messages
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="notifications-tab" data-bs-toggle="tab" href="#notifications"
                                role="tab" aria-controls="notifications" aria-selected="false">
                                Notifications
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Announcements Tab -->
                        <div id="announcements" class="tab-pane fade show active" role="tabpanel"
                            aria-labelledby="announcements-tab">
                            <div id="announcementsList" class="notification-list">
                                <p class="text-muted text-center py-4">Loading announcements...</p>
                            </div>
                        </div>

                        <!-- Messages Tab -->
                        <div id="messages" class="tab-pane fade" role="tabpanel" aria-labelledby="messages-tab">
                            <div id="messagesList" class="notification-list">
                                <p class="text-muted text-center py-4">Loading messages...</p>
                            </div>
                        </div>

                        <!-- Notifications Tab -->
                        <div id="notifications" class="tab-pane fade" role="tabpanel"
                            aria-labelledby="notifications-tab">
                            <div id="notificationsList" class="notification-list">
                                <p class="text-muted text-center py-4">Loading notifications...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary" id="markAllReadBtn">
                        Mark All as Read
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

    <!-- Initialize Notification Modal -->
    <script>
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                if (window.NotificationModalComponent) {
                    window.NotificationModalComponent.init();
                }
            });
        } else {
            if (window.NotificationModalComponent) {
                window.NotificationModalComponent.init();
            }
        }
    </script>
</body>

</html>
