@extends('layouts.usertemplate')

@section('content')
    <style>
        /* CSS from the base layout template */
        /* :root {
            --bs-dark-teal: #114243;
            --bs-light-gray: #e9ecef;
            --bs-main-green: #20c997; /* Used for subtle accents */
            /* --sidebar-width: 250px;
            --right-panel-width: 300px; /* Width of the History panel */
            /* --topbar-height: 60px; */ */
        } */

        /* Base Layout CSS */
        /* body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .sidebar {
            width: var(--sidebar-width);
            background-color: #fff;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1050;
            overflow-y: auto;
            transition: transform 0.3s ease;
            transform: translateX(0);
        } */
        /* .topbar {
            position: sticky;
            top: 0;
            height: var(--topbar-height);
            background-color: #fff;
            z-index: 1040;
            margin-left: var(--sidebar-width);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        } */
        /* MAIN content area - pushed by left sidebar and constrained by right panel */
        /* main {
            margin-left: var(--sidebar-width);
            margin-right: var(--right-panel-width);
            padding: 1.5rem;
            padding-bottom: 50px;
            min-height: calc(100vh - var(--topbar-height));
            display: flex;
            flex-direction: column;
            transition: margin-right 0.3s ease, margin-left 0.3s ease;
        } */

        /* Right History Panel */
        .history-panel {
            width: var(--right-panel-width);
            background-color: #fff;
            position: fixed;
            top: var(--topbar-height);
            bottom: 30px; /* Above the fixed footer */
            right: 0;
            z-index: 700;
            border-left: 1px solid var(--bs-light-gray);
            overflow-y: auto;
            padding: 1.5rem;
        }
        /* .page-footer {
            position: fixed;
            bottom: 0;
            left: var(--sidebar-width);
            right: 0;
            height: 30px;
            background-color: #fff;
            border-top: 1px solid var(--bs-light-gray);
            z-index: 1000;
            font-size: 0.75rem;
            padding: 0 1rem;
            display: flex;
            align-items: center;
        } */
        /* Base Nav/Sidebar styles */
        .nav-item-link {
            display: flex;
            align-items: center;
            padding: 0.65rem 1rem;
            color: #6c757d;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        .nav-item-link:hover, .nav-item-link.active {
            background-color: var(--bs-light-gray);
            color: var(--bs-dark-teal);
        }
        .nav-item-link.active {
            font-weight: bold;
            background-color: var(--bs-dark-teal);
            color: white;
        }
        /* .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid var(--bs-light-gray);
            position: sticky;
            bottom: 0;
            width: 100%;
            background-color: #fff;
        } */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s, visibility 0.3s;
        }
        .overlay.show {
            visibility: visible;
            opacity: 1;
        }

        /* Mobile adjustments (<= 991.98px) */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: none;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            /* Main content takes full width on mobile/tablet */
            .topbar, main, .page-footer {
                margin-left: 0;
            }
            /* Hide the right panel and reset main content margin */
            main {
                margin-right: 0;
            }
            .history-panel {
                display: none;
            }
            /* .page-footer {
                left: 0;
            } */
        }

        /* CONTENT SPECIFIC STYLING (AI/Messaging Center) */
        .ai-center-container {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding-bottom: 2rem;
            text-align: center;
        }
        .suggestion-card {
            border: 1px solid var(--bs-light-gray);
            border-radius: 0.75rem;
            padding: 1rem;
            min-height: 120px;
            cursor: pointer;
            text-align: left;
            transition: box-shadow 0.2s;
            background-color: #fff;
        }
        .suggestion-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        .suggestion-icon-container {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: var(--bs-light-gray);
            color: var(--bs-dark-teal);
            margin-bottom: 0.75rem;
        }
        .chat-input-wrapper {
            background-color: #f8f9fa; /* Match body background */
            padding: 1.5rem;
            width: 100%;
        }
        .chat-input-box {
            background-color: #fff;
            border: 1px solid var(--bs-light-gray);
            border-radius: 0.75rem;
            padding: 0.5rem 1rem;
        }
        .btn-send {
            background-color: var(--bs-dark-teal);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            margin-left: 0.5rem;
        }
    </style>

    <main>

        <div class="ai-center-container container-fluid">

            <h1 class="fw-bold mb-3" style="color: var(--bs-dark-teal);">What can I help with?</h1>
            <p class="text-muted mb-5">
                Here to help with your overall academic questions, ranging from English, Mathematic and Physic<br>
                What's on your mind today?
            </p>

            <div class="row g-4 justify-content-center mb-5" style="max-width: 900px;">

                <div class="col-12 col-md-4">
                    <div class="suggestion-card">
                        <div class="suggestion-icon-container"><i class="fa-solid fa-file-lines"></i></div>
                        <p class="small fw-bold mb-0" style="color: var(--bs-dark-teal);">Give me a concise summary of this meeting transcript</p>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="suggestion-card">
                        <div class="suggestion-icon-container"><i class="fa-solid fa-pen-to-square"></i></div>
                        <p class="small fw-bold mb-0" style="color: var(--bs-dark-teal);">Write a product description for a miniature smartwatch</p>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="suggestion-card">
                        <div class="suggestion-icon-container" style="background-color: var(--bs-main-green); color: white;"><i class="fa-solid fa-star"></i></div>
                        <p class="small fw-bold mb-0" style="color: var(--bs-dark-teal);">Provide a polite response to a customer asking for a refund</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="chat-input-wrapper">
            <div class="d-flex align-items-center justify-content-center">
                <div class="chat-input-box flex-grow-1 d-flex align-items-center" style="max-width: 800px;">
                    <input type="text" class="form-control border-0 shadow-none" placeholder="Message to koodie...">
                    <i class="fa-solid fa-microphone me-3 text-muted"></i>
                    <i class="fa-regular fa-face-smile me-3 text-muted"></i>
                </div>
                <button class="btn btn-send d-flex align-items-center">
                    Send <i class="fa-solid fa-chevron-right ms-2 small"></i>
                </button>
            </div>
        </div>


    <div class="history-panel d-none d-lg-block">
        <h6 class="fw-bold" style="color: var(--bs-dark-teal);">History</h6>
        <div class="py-2 small border-bottom">
            <p class="mb-0 fw-bold">Summarized meeting transcript</p>
            <span class="text-muted">5 minutes ago</span>
        </div>
        <div class="py-2 small border-bottom">
            <p class="mb-0 fw-bold">Drafted product description</p>
            <span class="text-muted">1 hour ago</span>
        </div>
        <div class="py-2 small border-bottom">
            <p class="mb-0 fw-bold">Wrote refund response</p>
            <span class="text-muted">2 hours ago</span>
        </div>
    </div>
    </main>





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

        document.querySelectorAll('.nav-item-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) closeSidebar();
            });
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) {
                overlay.classList.remove('show');
                sidebar.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    </script>

@endsection
