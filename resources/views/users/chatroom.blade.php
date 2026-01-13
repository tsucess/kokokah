@extends('layouts.usertemplate')

@section('content')
    <style>
        /* Current user message styling */
        .chat-message.current-user-message {
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .chat-message.current-user-message .message-content {
            background-color: var(--bs-dark-teal, #004A53);
            color: white;
            border-radius: 12px;
            padding: 10px 15px;
            max-width: 70%;
            margin-left: auto;
        }

        .chat-message.current-user-message .message-timestamp {
            color: #999;
            font-size: 0.85rem;
        }

        /* Mobile sidebar hidden by default */
        @media (max-width: 991.98px) {
            .chat-app-container {
                overflow: visible !important;
            }

            .overlay.show {
                display: block;
            }

            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.28);
                z-index: 946;
            }

            .sidebar-mobile {
                position: fixed;
                top: 0;
                left: -100%;
                height: 100vh;
                width: 80%;
                max-width: 320px;
                background: #fff;
                z-index: 950;
                padding: 1rem;
                overflow-y: auto;
                transition: left 0.3s ease-in-out;
                box-shadow: 2px 0 15px rgba(0, 0, 0, 0.15);
            }

            .sidebar-mobile.show {
                left: 0;
            }

            /* Prevent chat from being covered */
            .chat-panel-right {
                width: 100%;
            }
        }
    </style>
    <main>

        <div class="container-fluid py-4">
            <div class="overlay" id="sidebarOverlay"></div>
            <div id="sidebar-mobile" class="sidebar-mobile d-lg-none">

                <div class="input-group mb-4">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" placeholder="Find a Conversation HELLO">
                </div>

                <h6 class="text-muted text-uppercase small mb-2"> Conservation</h6>

                <a href="#" class="sidebar-item active">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-white me-2 d-flex justify-content-center align-items-center"
                            style = "color: var(--bs-dark-teal); border-radius:20px; width: 25px;">
                            <i class="bi bi-hash "></i>
                        </span>
                        General
                    </div>
                </a>

                <a href="#" class="sidebar-item">
                    <div class="d-flex align-items-center">
                        <span class="badge me-2 d-flex justify-content-center align-items-center"
                            style = "background: #114243; border-radius:20px; width: 25px;">
                            <i class="bi bi-hash  text-white"></i>
                        </span>
                        Mathematics Help Corner
                    </div>
                    <span class="new-message-badge">1</span>
                </a>

                <a href="#" class="sidebar-item">
                    <div class="d-flex align-items-center">
                        <span class="badge me-2 d-flex justify-content-center align-items-center"
                            style = "background: #114243; border-radius:20px; width: 25px;">
                            <i class="bi bi-hash  text-white"></i>
                        </span>
                        Science Discussions
                    </div>
                    <span class="new-message-badge">1</span>
                </a>

                <a href="#" class="sidebar-item">
                    <div class="d-flex align-items-center">
                        <span class="badge me-2 d-flex justify-content-center align-items-center"
                            style = "background: #114243; border-radius:20px; width: 25px;">
                            <i class="bi bi-hash  text-white"></i>
                        </span>
                        English Literature & Writing
                    </div>
                </a>

                <a href="#" class="sidebar-item">
                    <div class="d-flex align-items-center">
                        <span class="badge me-2 d-flex justify-content-center align-items-center"
                            style = "background: #114243; border-radius:20px; width: 25px;">
                            <i class="bi bi-hash text-white"></i>
                        </span>
                        History & Social Studies
                    </div>
                    <span class="new-message-badge">1</span>
                </a>

                <a href="#" class="sidebar-item">
                    <div class="d-flex align-items-center">
                        <span class="badge me-2 d-flex justify-content-center align-items-center"
                            style = "background: #114243; border-radius:20px; width: 25px;">
                            <i class="bi bi-hash  text-white"></i>
                        </span>
                        ICT & Programming Chat
                    </div>
                </a>

                <a href="#" class="sidebar-item">
                    <div class="d-flex align-items-center">
                        <span class="badge me-2 d-flex justify-content-center align-items-center"
                            style = "background: #114243; border-radius:20px; width: 25px;">
                            <i class="bi bi-hash text-white"></i>
                        </span>
                        Foreign Language Practice
                    </div>
                </a>

            </div>


            <div class="row g-0 chat-app-container">

                <div class="col-lg-4 d-none d-lg-block sidebar-left">

                    <div class="input-group mb-4">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Find a Conversation">
                    </div>

                    <h6 class="text-muted text-uppercase small mb-2">Conservation</h6>

                    <a href="#" class="sidebar-item active">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-white me-2 d-flex justify-content-center align-items-center"
                                style = "color: var(--bs-dark-teal); border-radius:20px; width: 25px;">
                                <i class="bi bi-hash "></i>
                            </span>
                            General
                        </div>
                    </a>

                    <a href="#" class="sidebar-item">
                        <div class="d-flex align-items-center">
                            <span class="badge me-2 d-flex justify-content-center align-items-center"
                                style = "background: #114243; border-radius:20px; width: 25px;">
                                <i class="bi bi-hash  text-white"></i>
                            </span>
                            Mathematics Help Corner
                        </div>
                        <span class="new-message-badge">1</span>
                    </a>

                    <a href="#" class="sidebar-item">
                        <div class="d-flex align-items-center">
                            <span class="badge me-2 d-flex justify-content-center align-items-center"
                                style = "background: #114243; border-radius:20px; width: 25px;">
                                <i class="bi bi-hash  text-white"></i>
                            </span>
                            Science Discussions
                        </div>
                        <span class="new-message-badge">1</span>
                    </a>

                    <a href="#" class="sidebar-item">
                        <div class="d-flex align-items-center">
                            <span class="badge me-2 d-flex justify-content-center align-items-center"
                                style = "background: #114243; border-radius:20px; width: 25px;">
                                <i class="bi bi-hash  text-white"></i>
                            </span>
                            English Literature & Writing
                        </div>
                    </a>

                    <a href="#" class="sidebar-item">
                        <div class="d-flex align-items-center">
                            <span class="badge me-2 d-flex justify-content-center align-items-center"
                                style = "background: #114243; border-radius:20px; width: 25px;">
                                <i class="bi bi-hash text-white"></i>
                            </span>
                            History & Social Studies
                        </div>
                        <span class="new-message-badge">1</span>
                    </a>

                    <a href="#" class="sidebar-item">
                        <div class="d-flex align-items-center">
                            <span class="badge me-2 d-flex justify-content-center align-items-center"
                                style = "background: #114243; border-radius:20px; width: 25px;">
                                <i class="bi bi-hash  text-white"></i>
                            </span>
                            ICT & Programming Chat
                        </div>
                    </a>

                    <a href="#" class="sidebar-item">
                        <div class="d-flex align-items-center">
                            <span class="badge me-2 d-flex justify-content-center align-items-center"
                                style = "background: #114243; border-radius:20px; width: 25px;">
                                <i class="bi bi-hash text-white"></i>
                            </span>
                            Foreign Language Practice
                        </div>
                    </a>

                </div>

                <div class="col-12 col-lg-8 chat-panel-right">


                    <div class="chat-header">
                        <!-- Toggle button: visible only on small screens -->
                        <button class="btn btn-outline-secondary d-lg-none" id="toggleSidebar">
                            <i class="bi bi-list"></i>
                        </button>
                        #General
                    </div>

                    <div class="chat-history">

                        <div class="welcome-message mb-5">
                            <div class="welcome-icon mx-auto mb-3">
                                <i class="bi bi-hash text-white"></i>
                            </div>
                            <h4 class="fw-bold mb-1" style="color: var(--color-primary-button: #004A53);">Welcome to
                                #General</h4>
                            <p class="text-muted">This is the start of the #General Channel</p>
                        </div>

                        <div class="chat-message">
                            <img src="images/Lisa.png" alt="Avatar" class="message-avatar">
                            <div class="message-content">
                                <span class="message-user">Emery Schleifer</span>
                                <span class="message-timestamp">7m</span>
                                <p class="mb-1">You can't input the pixel without programming the open-source SMTP feed!
                                    You can't input the pixel without programming the open-source SMTP feed!</p>
                            </div>
                        </div>

                        <div class="chat-message">
                            <img src="images/Lisa.png" alt="Avatar" class="message-avatar">
                            <div class="message-content">
                                <span class="message-user">Ogunsanya Taofeeq</span>
                                <span class="message-timestamp">5m</span>
                                <p class="mb-1">You can't input the pixel without programming the open-source SMTP feed!
                                </p>
                            </div>
                        </div>


                        {{-- Current User Chat Message  --}}
                        <div class="chat-message current-user-message">
                            <div class="message-content">
                                <span class="mb-1 fs-6">Hi I am Taofeeq! hope you are all doing well in this chat room.</span>
                                <span class="message-timestamp">1m</span>
                            </div>
                        </div>
                    </div>

                    <div class="chat-input-area">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 d-flex align-items-center border rounded-pill py-2 px-3 me-3"
                                style="border-color: var(--bs-light-gray) !important;">
                                <i class="bi bi-paperclip me-2 text-muted"></i>
                                <input type="text" class="form-control border-0 p-0 shadow-none"
                                    placeholder="Message to koodi..." style="height: auto;">
                            </div>

                            <div class="d-flex gap-3 text-secondary fs-5 me-3 d-none d-md-flex">
                                <i class="bi bi-mic-fill"></i>
                                <i class="bi bi-emoji-smile-fill"></i>
                                <i class="bi bi-camera-fill"></i>
                            </div>
                            <button class="btn btn-send d-flex align-items-center">
                                Send <i class="bi bi-send-fill ms-2"></i>
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </main>
    <script>
        const overlayMobile = document.getElementById('sidebarOverlay');
        const sidebarMobile = document.getElementById('sidebar-mobile');
        const toggleBtn = document.getElementById('toggleSidebar');

        function openSidebar() {
            sidebarMobile.classList.add('show');
            overlayMobile.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebarMobile.classList.remove('show');
            overlayMobile.classList.remove('show');
            document.body.style.overflow = '';
        }

        toggleBtn?.addEventListener('click', openSidebar);
        overlayMobile.addEventListener('click', closeSidebar);

        document.querySelectorAll('.sidebar-item').forEach(item => {
            item.addEventListener('click', closeSidebar);
        });
    </script>
@endsection
