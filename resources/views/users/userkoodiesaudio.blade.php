<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Koodies AI - Chat Interface</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        /* CSS Variables for consistent theming */
        :root {
            --bs-dark-teal: #114243; /* Main brand color for Koodies AI */
            --bs-light-gray: #e9ecef; /* Light background for some elements */
            --bs-main-green: #30B0A0; /* Green for send button, etc. */
            --user-bubble-color: #5d3bb2; /* Deep purple/blue for user message/link card background */
            --bg-body: #f8f9fa; /* Overall background color */

            --sidebar-width: 250px;
            --history-panel-width: 300px;
            --topbar-height: 60px;
            --footer-height: 30px;
        }

        /* Base Body and Layout Structure */
        body {
            font-family: 'Inter', sans-serif; /* Assuming Inter font from similar designs */
            background-color: var(--bg-body);
            min-height: 100vh;
            display: flex;
            flex-direction: column; /* Allows footer to stick to bottom if content is short */
            margin: 0; /* Remove default body margin */
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background-color: #fff;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1050;
            overflow-y: auto;
            border-right: 1px solid var(--bs-light-gray); /* Subtle separator */
            transition: transform 0.3s ease;
            transform: translateX(0); /* Always visible on desktop */
        }
        .sidebar .brand {
            padding: 1.5rem 1rem;
        }
        .sidebar .nav-item-link {
            display: flex;
            align-items: center;
            padding: 0.65rem 1rem;
            color: #6c757d;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s, color 0.2s;
        }
        .sidebar .nav-item-link i {
            width: 24px; /* Fixed width for icons for alignment */
            text-align: center;
            margin-right: 0.75rem;
        }
        .sidebar .nav-item-link:hover {
            background-color: var(--bs-light-gray);
            color: var(--bs-dark-teal);
        }
        .sidebar .nav-item-link.active {
            font-weight: bold;
            background-color: var(--bs-dark-teal);
            color: white;
            border-radius: 0.5rem; /* Matches image more closely */
            margin: 0 0.75rem;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        /* Adjust active link icon color */
        .sidebar .nav-item-link.active i {
            color: white;
        }
        /* Collapse for sub-menus */
        .sidebar .collapse .nav-item-link {
            padding-left: 2.75rem; /* Indent sub-items */
            font-size: 0.95rem;
        }
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid var(--bs-light-gray);
            position: sticky; /* Make it sticky at the bottom of the sidebar scroll */
            bottom: 0;
            width: 100%;
            background-color: #fff;
            z-index: 1060; /* Ensure it's above other elements if scrolling */
        }
        .sidebar-footer .profile img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border: 1px solid var(--bs-light-gray);
        }

        /* Topbar Styling */
        .topbar {
            position: fixed; /* Fixed to top */
            top: 0;
            left: var(--sidebar-width); /* Starts after sidebar */
            right: var(--history-panel-width); /* Ends before history panel */
            height: var(--topbar-height);
            background-color: #fff;
            z-index: 1040;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            transition: left 0.3s ease, right 0.3s ease;
        }
        .topbar .search-wrap .input-group {
            border: 1px solid var(--bs-light-gray);
            border-radius: 0.75rem;
            background-color: var(--bs-light-gray);
            overflow: hidden; /* Contains children border radius */
        }
        .topbar .search-wrap .input-group-text {
            background-color: var(--bs-light-gray);
            border: none;
            padding-left: 1rem;
            padding-right: 0.5rem;
        }
        .topbar .search-wrap .form-control {
            background-color: var(--bs-light-gray);
            border: none;
            padding-left: 0;
            box-shadow: none;
        }
        .topbar .icon-btn {
            background-color: var(--bs-light-gray);
            color: var(--bs-dark-teal);
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }
        .topbar .top-icons .fa-plus {
             background-color: var(--bs-main-green); /* Green plus icon */
             color: white;
        }


        /* Main Content Area (Chat + Input) */
        main {
            margin-left: var(--sidebar-width);
            margin-right: var(--history-panel-width);
            padding-top: var(--topbar-height); /* Space for fixed topbar */
            padding-bottom: var(--footer-height); /* Space for fixed footer */
            flex-grow: 1; /* Allows it to take available space */
            display: flex;
            flex-direction: column;
            background-color: var(--bg-body); /* Set main background */
            overflow: hidden; /* Prevent horizontal scroll from chat bubbles */
            transition: margin-left 0.3s ease, margin-right 0.3s ease;
        }

        /* Chat Container (Scrollable message area) */
        .chat-container {
            flex-grow: 1;
            overflow-y: auto; /* Enable vertical scrolling for messages */
            padding: 1.5rem 2rem; /* Padding around chat bubbles */
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        .chat-container::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }

        /* Message Row */
        .message-row {
            display: flex;
            align-items: flex-start; /* Align avatar and text to the top */
            margin-bottom: 1.5rem;
            width: 100%; /* Take full width of parent */
        }
        .ai-message {
            justify-content: flex-start;
        }
        .user-message {
            justify-content: flex-end;
        }
        .message-row .avatar-label {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--bs-dark-teal);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
            flex-shrink: 0; /* Prevent shrinking */
            margin-right: 0.75rem;
            margin-top: 0.2rem; /* Slight adjustment to align with text */
        }
        .user-message .avatar-label { /* No avatar for user messages in design */
            display: none;
        }
        .user-message .message-content-wrapper { /* Adjust alignment when no avatar */
             margin-left: 0;
        }
        .message-content-wrapper {
            display: flex;
            flex-direction: column;
            max-width: 65%; /* Constrain message bubble width */
        }
        .user-message .message-content-wrapper {
            align-items: flex-end; /* Align user messages to the right */
        }
        .message-content {
            background-color: #fff;
            border: 1px solid var(--bs-light-gray);
            border-radius: 0.75rem;
            padding: 0.8rem 1.1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            font-size: 0.9rem;
            line-height: 1.4;
        }
        .user-message .message-content {
            background-color: var(--user-bubble-color);
            color: white;
            border-color: var(--user-bubble-color);
        }
        .message-info {
            font-size: 0.7rem;
            color: #999;
            margin-top: 0.25rem;
            text-align: right; /* Time aligns right in all messages */
        }
        .user-message .message-info {
             color: #888; /* Slightly darker time for user messages for readability */
        }

        /* Audio Message Specifics */
        .audio-message-box {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 250px; /* Fixed width for audio player */
        }
        .audio-play-btn {
            background-color: var(--bs-dark-teal);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.7rem;
        }
        .wave-visual {
            height: 25px;
            width: 100%;
            background: repeating-linear-gradient(90deg,
                var(--bs-dark-teal) 0%, var(--bs-dark-teal) 4%, transparent 4%, transparent 8%);
            background-size: 25px 100%; /* Adjust size for more waves if needed */
            opacity: 0.6;
            filter: blur(0.5px);
            border-radius: 0.5rem;
        }

        /* Link Card Specifics */
        .link-card {
            background-color: var(--user-bubble-color); /* Matches user bubble in design */
            color: white;
            border-radius: 0.75rem;
            padding: 1rem;
            width: 280px; /* Fixed width for link card */
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .link-card .link-title {
            font-weight: bold;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }
        .link-card .link-url {
            opacity: 0.7;
            font-size: 0.8rem;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .link-card i.fa-link {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.25rem;
            opacity: 0.8;
        }

        /* Message Controls (thumbs up/down/copy) */
        .message-controls {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
            align-self: flex-start; /* Align to left of AI messages */
        }
        .message-controls button {
            background: none;
            border: none;
            color: #6c757d;
            font-size: 1rem;
            transition: color 0.2s;
            padding: 0; /* Remove default button padding */
        }
        .message-controls button:hover {
            color: var(--bs-dark-teal);
        }

        /* Chat Input Area */
        .chat-input-wrapper {
            flex-shrink: 0; /* Prevents it from shrinking */
            background-color: var(--bg-body);
            padding: 1.5rem 2rem; /* Matches chat container padding */
            border-top: 1px solid var(--bs-light-gray); /* Separator line */
        }
        .chat-input-box {
            background-color: #fff;
            border: 1px solid var(--bs-light-gray);
            border-radius: 0.75rem;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            max-width: 800px; /* Constrain input width */
            margin: 0 auto; /* Center the input box */
        }
        .chat-input-box input {
            border: none;
            box-shadow: none;
            padding: 0.5rem 0.75rem;
        }
        .chat-input-box .input-icon-btn {
            color: #6c757d;
            font-size: 1.25rem;
            cursor: pointer;
            transition: color 0.2s;
            margin-left: 0.75rem;
        }
        .chat-input-box .input-icon-btn:hover {
            color: var(--bs-dark-teal);
        }
        .btn-send {
            background-color: var(--bs-main-green);
            color: white;
            padding: 0.75rem 1.5rem; /* Larger padding for the button */
            border-radius: 0.5rem;
            margin-left: 0.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-send i {
            font-size: 0.8rem;
        }

        /* History Panel */
        .history-panel {
            width: var(--history-panel-width);
            background-color: #fff;
            position: fixed;
            top: 0;
            bottom: var(--footer-height); /* Above the fixed footer */
            right: 0;
            z-index: 1020;
            border-left: 1px solid var(--bs-light-gray);
            overflow-y: auto;
            padding: 1.5rem;
            padding-top: var(--topbar-height); /* Push content below topbar */
            transition: right 0.3s ease;
        }
        .history-panel .history-title {
            color: var(--bs-dark-teal);
            font-weight: 600;
            margin-bottom: 1.5rem; /* More space below title */
        }
        .history-panel .recent-chats-label {
            color: #6c757d;
            font-size: 0.85rem;
            margin-bottom: 0.75rem;
            display: block;
        }
        .history-item {
            padding: 0.75rem 0.5rem; /* Padding for click area */
            border-bottom: 1px solid var(--bs-light-gray);
            cursor: pointer;
            transition: background-color 0.1s;
            margin: 0 -0.5rem; /* Negative margin to extend padding effect */
        }
        .history-item:last-child {
            border-bottom: none;
        }
        .history-item:hover {
            background-color: var(--bs-light-gray);
        }
        .history-item.active {
            background-color: var(--bs-light-gray);
            border-radius: 0.5rem;
        }
        .history-item .chat-title {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--bs-dark-teal);
        }
        .history-item .timestamp {
            font-size: 0.75rem;
            color: #999;
        }
        .history-item .delete-icon {
            color: #dc3545;
            margin-left: 0.5rem;
            cursor: pointer;
            visibility: hidden; /* Hidden by default */
        }
        .history-item:hover .delete-icon {
            visibility: visible; /* Visible on hover */
        }

        /* Page Footer Styling */
        .page-footer {
            position: fixed;
            bottom: 0;
            left: var(--sidebar-width);
            right: var(--history-panel-width);
            height: var(--footer-height);
            background-color: #fff;
            border-top: 1px solid var(--bs-light-gray);
            z-index: 1000;
            font-size: 0.75rem;
            padding: 0 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #6c757d;
            transition: left 0.3s ease, right 0.3s ease;
        }
        .page-footer a {
            color: #6c757d;
            text-decoration: none;
            margin-left: 1rem;
        }
        .page-footer a:hover {
            text-decoration: underline;
        }


        /* RESPONSIVENESS */
        @media (max-width: 1200px) { /* Adjust history panel for smaller desktops/large tablets */
            :root {
                --history-panel-width: 250px;
            }
        }

        @media (max-width: 991.98px) { /* Tablet and below */
            /* Hide Sidebar by default, show overlay */
            .sidebar {
                transform: translateX(-100%);
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .topbar, main, .page-footer {
                margin-left: 0; /* Take full width */
            }
            .topbar {
                left: 0; /* Aligned to left edge */
                right: 0; /* Aligned to right edge */
                padding-left: 1rem;
                padding-right: 1rem;
                justify-content: space-between; /* Ensure hamburger button and icons are spaced */
            }
            .topbar .search-wrap { /* Hide search on smaller screens to save space */
                display: none !important;
            }
            .topbar .d-lg-none { /* Show hamburger and Koodies AI text */
                display: flex !important;
                align-items: center;
                gap: 0.75rem;
            }
            .topbar .d-lg-none .fa-bars {
                 font-size: 1.2rem;
            }

            /* Hide History Panel */
            main {
                margin-right: 0; /* Take full width */
            }
            .history-panel {
                display: none;
            }
            .page-footer {
                left: 0; /* Footer also takes full width */
                right: 0;
            }
            .message-content-wrapper {
                max-width: 75%; /* Allow bubbles to be wider on smaller screens */
            }
            .chat-input-box {
                max-width: 100%; /* Allow input to stretch */
            }
            .audio-message-box, .link-card {
                width: 100%; /* Allow these to be fluid */
            }
        }

        /* Overlay for Mobile Sidebar */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1045; /* Above main content, below sidebar */
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s, visibility 0.3s;
        }
        .overlay.show {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>
<body>

    <div class="overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <h4 class="fw-bold" style="color: var(--bs-dark-teal);">Koodies AI</h4>
        </div>

        <nav class="nav-group" id="sidebarNav">
            <a class="nav-item-link" href="#"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a class="nav-item-link" href="#"><i class="fa-solid fa-book-open"></i> Subjects</a>
            <a class="nav-item-link" href="#"><i class="fa-solid fa-chart-line"></i> Results & Scoring</a>
            <a class="nav-item-link active" href="#"><i class="fa-solid fa-graduation-cap"></i> Koodies AI</a>
            <a class="nav-item-link" href="#"><i class="fa-solid fa-wallet"></i> Wallet</a>
            <a class="nav-item-link" href="#"><i class="fa-solid fa-bell"></i> Notification</a>

            <a class="nav-item-link d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#communication" role="button" aria-expanded="true" aria-controls="communication">
                <span><i class="fa-solid fa-comments"></i> Communication</span>
                <i class="fa-solid fa-chevron-down small"></i>
            </a>
            <div class="collapse show" id="communication">
                <a class="nav-item-link d-block" href="#">Announcement</a>
                <a class="nav-item-link d-block" href="#">Email / Messaging Center</a>
                <a class="nav-item-link d-block" href="#">Feedback / Surveys</a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <a class="nav-item-link" href="#"><i class="fa-solid fa-gear"></i> Settings</a>
            <div class="profile mt-3 d-flex align-items-center">
                <img class="avatar rounded-circle me-3" src="https://dummyimage.com/72x72/114243/ffffff.png&text=C" alt="user profile">
                <div>
                    <div class="fw-bold small">Culacino_</div>
                    <div class="text-muted small">UI Designer</div>
                </div>
            </div>
        </div>
    </aside>

    <header class="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-light d-lg-none" id="hamburger"><i class="fa-solid fa-bars"></i></button>
            <span class="fw-bold d-lg-none" style="color: var(--bs-dark-teal);">Koodies AI</span>
        </div>

        <div class="search-wrap mx-3 flex-grow-1 d-none d-md-flex justify-content-center">
            <div class="input-group" style="max-width: 400px;">
                <span class="input-group-text"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input class="form-control" type="text" placeholder="Search">
            </div>
        </div>

        <div class="top-icons d-flex align-items-center gap-3">
            <button class="icon-btn btn rounded-circle" title="Notifications"><i class="fa-regular fa-bell"></i></button>
            <button class="icon-btn btn rounded-circle" title="New Chat"><i class="fa-solid fa-plus"></i></button>
        </div>
    </header>

    <main>
        <div class="chat-container">

            <div class="message-row ai-message">
                <div class="avatar-label">SL</div>
                <div class="message-content-wrapper">
                    <div class="message-content">
                         <p class="mb-0 small">Hello! I'm your personal AI Assistant KoodiesAI</p>
                    </div>
                    <div class="message-info">10:25</div>
                </div>
            </div>

            <div class="message-row ai-message">
                <div class="avatar-label">SL</div>
                <div class="message-content-wrapper">
                    <div class="message-content">
                        <div class="audio-message-box">
                            <div class="audio-play-btn"><i class="fa-solid fa-play small"></i></div>
                            <div class="wave-visual"></div>
                            <span class="small">02:13</span>
                        </div>
                    </div>
                    <div class="message-info">11:15</div>
                </div>
            </div>

            <div class="message-row ai-message">
                <div class="avatar-label">SL</div>
                <div class="message-content-wrapper">
                    <div class="message-content">
                        <p class="mb-0 small">The English Language is a West Germanic language that originated in medieval England. It developed from the fusion of Germanic, tribes such as the Angles, Saxons, and Jutes, and over time it absorbed various words and influences from Latin, French, and other languages. This rich mixture has made English one of the most flexible and widely used languages in history.</p>
                    </div>
                    <div class="message-info">12:25</div>
                </div>
            </div>

            <div class="message-row ai-message">
                <div class="avatar-label">SL</div>
                <div class="message-content-wrapper">
                    <div class="link-card d-flex flex-column justify-content-between">
                        <div>
                            <i class="fa-solid fa-link"></i>
                            <div class="link-title">External Link Title</div>
                            <div class="text-muted small">External link description</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-end mt-3">
                            <a href="#" class="link-url text-decoration-none text-white small">https://www.externallink.com</a>
                            <span class="small opacity-75">01:25</span>
                        </div>
                    </div>
                    <div class="message-info">01:25</div>
                </div>
            </div>

            <div class="message-row ai-message">
                <div class="avatar-label">SL</div>
                <div class="message-content-wrapper">
                    <div class="message-content">
                        <p class="mb-0 small">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2 w-100">
                        <div class="message-controls">
                            <button title="Like"><i class="fa-regular fa-thumbs-up"></i></button>
                            <button title="Dislike"><i class="fa-regular fa-thumbs-down"></i></button>
                            <button title="Copy"><i class="fa-regular fa-copy"></i></button>
                        </div>
                        <div class="message-info">02:25</div>
                    </div>
                </div>
            </div>

            <div class="message-row user-message">
                <div class="message-content-wrapper">
                    <div class="message-content">
                        <p class="mb-0 small">Message to koodie</p>
                    </div>
                    <div class="message-info">02:26</div>
                </div>
            </div>

        </div>

        <div class="chat-input-wrapper">
            <div class="d-flex align-items-center">
                <div class="chat-input-box flex-grow-1">
                    <input type="text" class="form-control" placeholder="Message to koodie...">
                    <i class="fa-solid fa-microphone input-icon-btn"></i>
                    <i class="fa-regular fa-face-smile input-icon-btn"></i>
                    <i class="fa-solid fa-paperclip input-icon-btn"></i>
                </div>
                <button class="btn btn-send">
                    Send <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>

    </main>

    <div class="history-panel d-none d-lg-block">
        <h6 class="history-title">History</h6>
        <div class="recent-chats-label">Recent Chats</div>

        <div class="d-flex flex-column">
            <div class="history-item active">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="chat-title text-truncate me-2">How to get fit without doing any fi...</div>
                    <span class="timestamp flex-shrink-0">2m ago</span>
                </div>
            </div>
            <div class="history-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="chat-title text-truncate me-2">Hacking FBI Server with raspb...</div>
                    <div class="d-flex align-items-center flex-shrink-0">
                        <i class="fa-solid fa-trash-can delete-icon me-2"></i>
                        <span class="timestamp">2m ago</span>
                    </div>
                </div>
            </div>
            <div class="history-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="chat-title text-truncate me-2">CompSci S!CP Tutorial course</div>
                    <span class="timestamp flex-shrink-0">2m ago</span>
                </div>
            </div>
            <div class="history-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="chat-title text-truncate me-2">Proxy failure troubleshooting</div>
                    <span class="timestamp flex-shrink-0">2m ago</span>
                </div>
            </div>
             <div class="history-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="chat-title text-truncate me-2">Wake me up when september ends</div>
                    <span class="timestamp flex-shrink-0">2m ago</span>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex page-footer justify-content-between">
        <div class="small">© Copyright Kokokah 2025. All rights reserved.</div>
        <div class="small d-none d-md-block">
            <a href="#">License</a>
            <a href="#">More Themes</a>
            <a href="#">Documentation</a>
            <a href="#">Support</a>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const hamburger = document.getElementById('hamburger');

        // Function to open sidebar
        function openSidebar() {
            sidebar.classList.add('show');
            sidebarOverlay.classList.add('show');
            document.body.style.overflow = 'hidden'; // Prevent body scroll
        }

        // Function to close sidebar
        function closeSidebar() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
            document.body.style.overflow = ''; // Restore body scroll
        }

        // Hamburger click to toggle sidebar
        hamburger?.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent immediate closing from body click
            openSidebar();
        });

        // Close sidebar when clicking outside (overlay)
        sidebarOverlay?.addEventListener('click', () => closeSidebar());

        // Close sidebar if a nav item is clicked on mobile
        document.querySelectorAll('#sidebarNav .nav-item-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) { // Only close on mobile sizes
                    closeSidebar();
                }
            });
        });

        // Ensure sidebar is closed if window resizes to desktop from mobile
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992 && sidebar.classList.contains('show')) {
                closeSidebar();
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
