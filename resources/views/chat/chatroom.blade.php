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
            max-width: 300px;
            margin-left: auto;
            word-break: break-word;
            overflow-wrap: break-word;

        }

        .chat-message.current-user-message .message-content .message-user {
            color: white;
            font-size: 0.9rem;
        }

        .chat-message.current-user-message .message-content p {
            color: white;
        }

        .chat-message.current-user-message .message-timestamp {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
        }

        /* Other users message styling */
        .chat-message:not(.current-user-message) .message-content {
            background-color: #e8e8e8;
            color: #333;
            border-radius: 12px;
            padding: 10px 15px;
            max-width: 300px;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .chat-message:not(.current-user-message) .message-timestamp {
            color: #666;
            font-size: 0.85rem;
        }

        /* Date separator styling */
        .message-date-separator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0 15px 0;
            gap: 10px;
        }

        .message-date-separator::before,
        .message-date-separator::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #ddd;
        }

        .message-date-separator span {
            color: #999;
            font-size: 0.9rem;
            font-weight: 500;
            white-space: nowrap;
        }

        /* Administrator badge styling */
        .admin-badge {
            display: inline-block;
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: #333;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 4px rgba(255, 165, 0, 0.3);
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
                top: 70px;
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
            .admin-badge{
                font-size: 0.65rem;
            }
        }

        .chatroom-item {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .chatroom-item:hover {
            background-color: #f5f5f5;
        }

        /* Active state for chatroom items - override sidebar-item styles */
        .chatroom-item.active {
            background-color: var(--bs-dark-teal) !important;
            color: white !important;
            border-left: 3px solid var(--bs-dark-teal);
            font-weight: 600;
        }

        /* Ensure badge icon is visible on active state */
        .chatroom-item.active .badge {
            background-color: rgba(255, 255, 255, 0.3) !important;
        }

        /* Level badge styling */
        .level-badge {
            background-color: #FDAF22;
            color: #000;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
            margin-left: 8px;
        }

        .chatroom-item.active .level-badge {
            background-color: rgba(253, 175, 34, 0.9);
            color: #000;
        }

        /* Message actions styling */
        .message-actions {
            display: flex;
            gap: 5px;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .message-actions .btn {
            padding: 4px 8px;
            font-size: 0.75rem;
        }

        .chat-message:hover .message-actions {
            display: flex !important;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .loading-spinner.show {
            display: block;
        }

        /* Message Context Menu Modal Styles */
        .message-context-menu {
            position: fixed;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 40px rgba(0, 0, 0, 0.16);
            z-index: 1050;
            min-width: 200px;
            overflow: hidden;
            display: none;
        }

        .message-context-menu.show {
            display: block;
        }

        .message-context-menu-item {
            padding: 12px 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-size: 0.95rem;
            color: #333;
            transition: background-color 0.2s ease;
        }

        .message-context-menu-item:hover {
            background-color: #f5f5f5;
        }

        .message-context-menu-item.danger {
            color: #dc3545;
        }

        .message-context-menu-item.danger:hover {
            background-color: #ffe5e5;
        }

        .message-context-menu-item i {
            width: 18px;
            text-align: center;
        }

        /* Edit Message Modal */
        .edit-message-modal {
            display: none;
            position: fixed;
            z-index: 1051;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.2s ease;
        }

        .edit-message-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .edit-message-modal-content {
            background-color: white;
            padding: 24px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 40px rgba(0, 0, 0, 0.16);
            animation: slideUp 0.3s ease;
        }

        .edit-message-modal-header {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 16px;
            color: #333;
        }

        .edit-message-modal-body {
            margin-bottom: 20px;
        }

        .edit-message-modal-body textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            resize: vertical;
            min-height: 100px;
        }

        .edit-message-modal-body textarea:focus {
            outline: none;
            border-color: var(--bs-dark-teal, #004A53);
            box-shadow: 0 0 0 3px rgba(0, 74, 83, 0.1);
        }

        .edit-message-modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .edit-message-modal-footer button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .edit-message-modal-footer .btn-cancel {
            background-color: #f0f0f0;
            color: #333;
        }

        .edit-message-modal-footer .btn-cancel:hover {
            background-color: #e0e0e0;
        }

        .edit-message-modal-footer .btn-save {
            background-color: var(--bs-dark-teal, #004A53);
            color: white;
        }

        .edit-message-modal-footer .btn-save:hover {
            background-color: #003339;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Long press indicator for mobile */
        .message-long-press-active {
            background-color: rgba(0, 74, 83, 0.1);
            border-radius: 12px;
        }

        /* Cursor pointer for interactive icons */
        .cursor-pointer {
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .cursor-pointer:hover {
            color: var(--bs-dark-teal, #004A53) !important;
        }

        /* Emoji Picker Styles */
        #emojiPickerContainer {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        #emojiPicker span {
            transition: transform 0.1s ease;
        }

        #emojiPicker span:hover {
            transform: scale(1.3);
        }

        /* Audio Recording Modal Styles */
        #audioRecordingModal {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            min-width: 300px;
        }

        #audioRecordingModal h5 {
            margin-bottom: 15px;
            font-weight: 600;
        }

        #audioRecordingStatus {
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        #recordingTime {
            color: #dc3545;
            font-weight: bold;
        }

        #audioPlaybackContainer {
            margin-bottom: 15px;
        }

        #audioPlayback {
            width: 100%;
            margin-bottom: 10px;
        }

        /* Image Preview Modal Styles */
        #imagePreviewModal {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            max-width: 500px;
        }

        #imagePreviewModal h5 {
            margin-bottom: 15px;
            font-weight: 600;
        }

        #imagePreviewImg {
            width: 100%;
            max-height: 400px;
            object-fit: contain;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        /* Modal Overlay */
        #modalOverlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1999;
        }

        /* Message display for different types */
        .message-image {
            max-width: 300px;
            border-radius: 8px;
            margin-top: 8px;
            display: block;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .message-image-container {
            max-width: 300px;
            overflow: hidden;
            word-break: break-word;
        }

        .message-image {
            cursor: pointer;
            transition: opacity 0.2s ease;
        }

        .message-image:hover {
            opacity: 0.8;
        }

        /* Full Image Viewer Modal */
        #imageViewerModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.95);
            z-index: 2001;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        #imageViewerModal.show {
            display: flex;
        }

        .image-viewer-container {
            position: relative !important;
            width: 100% !important;
            max-width: 90vw !important;
            max-height: 90vh !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .image-viewer-header {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 20px !important;
            background: linear-gradient(180deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%) !important;
            z-index: 10 !important;
        }

        .image-viewer-btn {
            background: rgba(255, 255, 255, 0.2) !important;
            border: none !important;
            color: white !important;
            width: 40px !important;
            height: 40px !important;
            border-radius: 50% !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 20px !important;
            transition: background 0.2s ease !important;
            padding: 0 !important;
            min-width: auto !important;
            white-space: normal !important;
        }

        .image-viewer-btn:hover {
            background: rgba(255, 255, 255, 0.3) !important;
        }

        .image-viewer-btn.delete {
            background: rgba(220, 53, 69, 0.3) !important;
        }

        .image-viewer-btn.delete:hover {
            background: rgba(220, 53, 69, 0.5) !important;
        }

        #viewerImage {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        .message-audio {
            margin-top: 8px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 12px;
            background-color: rgba(0, 74, 83, 0.1);
            border-radius: 12px;
            max-width: 300px;
        }

        .message-audio-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .message-audio-play-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
            color: #333;
            padding: 0;
            min-width: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease;
            flex-shrink: 0;
        }

        .message-audio-play-btn:hover {
            transform: scale(1.1);
        }

        .message-audio-play-btn.playing {
            color: #dc3545;
        }

        /* Play button white on teal background (current user messages) */
        .chat-message.current-user-message .message-audio-play-btn {
            color: white;
        }

        .chat-message.current-user-message .message-audio-play-btn.playing {
            color: #ff6b7a;
        }

        .message-audio-waveform {
            display: flex;
            align-items: center;
            gap: 3px;
            flex: 1;
            height: 24px;
        }

        .message-audio-bar {
            width: 3px;
            height: 100%;
            background-color: white;
            border-radius: 2px;
            opacity: 0.6;
            animation: audioWave 0.6s ease-in-out infinite;
        }

        .message-audio-bar:nth-child(1) { animation-delay: 0s; }
        .message-audio-bar:nth-child(2) { animation-delay: 0.1s; }
        .message-audio-bar:nth-child(3) { animation-delay: 0.2s; }
        .message-audio-bar:nth-child(4) { animation-delay: 0.3s; }
        .message-audio-bar:nth-child(5) { animation-delay: 0.4s; }

        @keyframes audioWave {
            0%, 100% { height: 8px; }
            50% { height: 20px; }
        }

        .message-audio-info {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #666;
        }

        .message-audio-progress-container {
            display: flex;
            align-items: center;
            gap: 6px;
            width: 100%;
        }

        .message-audio-progress {
            flex: 1;
            height: 4px;
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 2px;
            cursor: pointer;
            position: relative;
        }

        .chat-message.current-user-message .message-audio-progress {
            background-color: rgba(255, 255, 255, 0.3);
            max-width: 300px;
        }

        .message-audio-progress-bar {
            height: 100%;
            background-color: #333;
            border-radius: 2px;
            width: 0%;
            transition: width 0.1s linear;
        }

        .chat-message.current-user-message .message-audio-progress-bar {
            background-color: white;
            max-width: 300px;
        }

        .message-audio-time {
            font-size: 11px;
            color: #666;
            min-width: 35px;
            text-align: right;
            font-weight: 500;
        }

        .chat-message.current-user-message .message-audio-time {
            color: rgba(255, 255, 255, 0.8);
            max-width: 300px;
        }

        .message-file {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
            margin-top: 8px;
            text-decoration: none;
            color: inherit;
            max-width: 250px;
            overflow: hidden;
            min-width: 0;
            font-size: 0.85rem;
        }

        .message-file:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .message-file i {
            flex-shrink: 0;
            min-width: 14px;
            font-size: 0.85rem;
        }

        .message-file-name {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            min-width: 0;
            flex: 1;
            font-size: 0.75rem;
            word-break: break-all;
        }

        /* Audio Recording UI Styles */
        #audioRecordingUI {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #004A53 0%, #003339 100%);
            padding: 16px 20px;
            z-index: 1000;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
        }

        #audioRecordingUI.active {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .recording-time {
            font-size: 18px;
            font-weight: bold;
            color: #ff4444;
            min-width: 50px;
            font-variant-numeric: tabular-nums;
        }

        .recording-waveform {
            display: flex;
            align-items: center;
            gap: 3px;
            flex: 1;
            height: 32px;
        }

        .recording-bar {
            width: 3px;
            height: 8px;
            background: linear-gradient(180deg, #ffffff 0%, #e0e0e0 100%);
            border-radius: 2px;
            opacity: 1;
            transition: height 0.05s ease-out;
            box-shadow: 0 0 4px rgba(255, 255, 255, 0.6);
        }

        .recording-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .recording-delete-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: white;
            font-size: 20px;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease;
        }

        .recording-delete-btn:hover {
            transform: scale(1.1);
        }

        .recording-send-btn {
            background: white;
            border: none;
            cursor: pointer;
            color: #004A53;
            font-size: 20px;
            padding: 8px 12px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease;
            flex-shrink: 0;
        }

        .recording-send-btn:hover {
            transform: scale(1.1);
        }

        /* Mic icon active state */
        #audioBtn.recording {
            color: #ff4444;
            animation: micPulse 1s ease-in-out infinite;
        }

        @keyframes micPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        .send-text{
            display: none;
        }
        .send-btn{
            background-color: #FDAF22;
            padding: 10px 16px;
            gap: 8px;
            border-radius: 50%;
            color: #000F11;
            transition: background-color 0.2s ease;
        }
        .send-btn:hover{
            background-color: #F3A61C;
        }
        @media screen and (min-width:500px){
            .send-text{
                display: inline;
            }
            .send-btn{
                border-radius: 100px;
            }
        }

        /* Camera mirror mode styles */
        #cameraPreview.mirror-mode {
            transform: scaleX(-1);
        }

        /* Camera button visibility - ensure buttons are visible */
        #switchCameraBtn,
        #mirrorModeBtn,
        #capturePhotoBtn,
        #retakeCameraBtn,
        #sendPhotoBtn,
        #closeCameraBtn {
            display: none !important;
            min-width: 60px;
            white-space: nowrap;
        }

        #switchCameraBtn.visible,
        #mirrorModeBtn.visible,
        #capturePhotoBtn.visible,
        #retakeCameraBtn.visible,
        #sendPhotoBtn.visible,
        #closeCameraBtn.visible {
            display: inline-block !important;
        }

        /* Camera button theme styling */
        #switchCameraBtn,
        #mirrorModeBtn {
            background-color: #004a53 !important;
            color: #fff !important;
            border: none !important;
            padding: 8px 12px !important;
            border-radius: 6px !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
        }

        #switchCameraBtn:hover,
        #mirrorModeBtn:hover {
            background-color: #2b6870 !important;
            transform: scale(1.05) !important;
        }

        #capturePhotoBtn {
            background-color: #fdaf22 !important;
            color: #000 !important;
            border: none !important;
            padding: 8px 12px !important;
            border-radius: 6px !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
        }

        #capturePhotoBtn:hover {
            background-color: #ffc14e !important;
            transform: scale(1.05) !important;
        }

        #retakeCameraBtn {
            background-color: #338a8a !important;
            color: #fff !important;
            border: none !important;
            padding: 8px 12px !important;
            border-radius: 6px !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
        }

        #retakeCameraBtn:hover {
            background-color: #2b6870 !important;
            transform: scale(1.05) !important;
        }

        #sendPhotoBtn {
            background-color: #16b265 !important;
            color: #fff !important;
            border: none !important;
            padding: 8px 12px !important;
            border-radius: 6px !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
        }

        #sendPhotoBtn:hover {
            background-color: #0d8a4a !important;
            transform: scale(1.05) !important;
        }

        #closeCameraBtn {
            background-color: #dc3545 !important;
            color: #fff !important;
            border: none !important;
            padding: 8px 12px !important;
            border-radius: 6px !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
        }

        #closeCameraBtn:hover {
            background-color: #c82333 !important;
            transform: scale(1.05) !important;
        }

        /* Mobile: Hide button text, show only icons */
        @media (max-width: 768px) {
            #switchCameraBtn,
            #mirrorModeBtn,
            #capturePhotoBtn,
            #retakeCameraBtn,
            #sendPhotoBtn,
            #closeCameraBtn {
                padding: 8px 10px !important;
                font-size: 0 !important;
            }

            #switchCameraBtn i,
            #mirrorModeBtn i,
            #capturePhotoBtn i,
            #retakeCameraBtn i,
            #sendPhotoBtn i,
            #closeCameraBtn i {
                font-size: 1.25rem !important;
                margin: 0 !important;
            }

            /* Hide switch camera button on mobile - users typically have one camera */
            #switchCameraBtn {
                display: none !important;
            }
        }

        /* Desktop: Show button text with icons */
        @media (min-width: 769px) {
            #switchCameraBtn i,
            #mirrorModeBtn i,
            #capturePhotoBtn i,
            #retakeCameraBtn i,
            #sendPhotoBtn i,
            #closeCameraBtn i {
                margin-right: 6px !important;
                font-size: 1rem !important;
            }

            /* Hide switch camera button on desktop - users typically have one camera */
            #switchCameraBtn {
                display: none !important;
            }
        }
    </style>
    <main>
        <div class="container-fluid py-4">
            <div class="overlay" id="sidebarOverlay"></div>
            <div id="sidebar-mobile" class="sidebar-mobile d-lg-none">
                <div class="input-group mb-4">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" placeholder="Find a Conversation" id="searchChatrooms">
                </div>
                 <div class="d-flex align-items-center gap-2 justify-content-between">
                    <h6 class="text-muted text-uppercase small mb-2">Conversations</h6>
                    <button id='closeBtn'><i class="fa-regular fa-circle-xmark"></i></button>
                 </div>

                <div id="chatrooms-list-mobile" class="chatrooms-list"></div>
            </div>

            <div class="row g-0 chat-app-container">
                <div class="col-lg-4 d-none d-lg-block sidebar-left">
                    <div class="input-group mb-4">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Find a Conversation" id="searchChatroomsDesktop">
                    </div>

                    <h6 class="text-muted text-uppercase small mb-2">Conversations</h6>
                    <div id="chatrooms-list-desktop" class="chatrooms-list"></div>
                </div>

                <div class="col-12 col-lg-8 chat-panel-right">
                    <div class="chat-header">
                        <button class="btn btn-outline-secondary d-lg-none" id="toggleSidebar">
                            <i class="bi bi-list"></i>
                        </button>
                        <span id="current-room-name">#General</span>
                    </div>

                    <div class="chat-history" id="chat-history">
                        <div class="welcome-message mb-4">
                            <div class="welcome-icon mx-auto mb-3">
                                <i class="bi bi-hash text-white"></i>
                            </div>
                            <h4 class="fw-bold mb-1" style="color: var(--color-primary-button: #004A53);">Welcome to Chatroom</h4>
                            <p class="text-muted">Select a conversation to start chatting</p>
                        </div>
                        <div class="chat-messages p-2" id="chat-messages">

                        </div>
                    </div>

                    <div class="chat-input-area">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 d-flex align-items-center border rounded-pill py-2 px-3 me-3"
                                style="border-color: var(--bs-light-gray) !important;">
                                <i class="bi bi-paperclip me-2 text-muted cursor-pointer" id="attachmentBtn" title="Attach file"></i>
                                <input type="text" class="form-control border-0 p-0 shadow-none" id="messageInput"
                                    placeholder="Type a message..." style="height: auto;">
                            </div>

                            <div class="d-flex gap-3 text-secondary fs-5 me-3 d-none d-md-flex">
                                <i class="bi bi-mic-fill cursor-pointer" id="audioBtn" title="Record audio"></i>
                                <i class="bi bi-emoji-smile-fill cursor-pointer" id="emojiBtn" title="Add emoji"></i>
                            </div>
                            <div class="d-flex gap-3 text-secondary fs-5 me-3">
                                <i class="bi bi-camera-fill cursor-pointer" id="cameraBtn" title="Take picture"></i>
                            </div>
                            <button class="btn send-btn d-flex align-items-center" id="sendBtn">
                               <span class="send-text">Send</span>  <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Hidden file inputs -->
                    <input type="file" id="fileInput" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.webp,.zip,.rar,.7z" style="display: none;">
                    <video id="cameraStream" style="display: none; width: 100%; max-width: 400px;"></video>

                    <!-- Emoji Picker Container -->
                    <div id="emojiPickerContainer" style="display: none; position: absolute; bottom: 80px; right: 20px; z-index: 1000; background: white; border: 1px solid #ddd; border-radius: 8px; padding: 10px; max-height: 300px; overflow-y: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <div id="emojiPicker"></div>
                    </div>

                    <!-- Audio Recording UI (WhatsApp style) -->
                    <div id="audioRecordingUI">
                        <span class="recording-time" id="recordingTime">0:00</span>
                        <div class="recording-waveform" id="recordingWaveform">
                            <div class="recording-bar"></div>
                            <div class="recording-bar"></div>
                            <div class="recording-bar"></div>
                            <div class="recording-bar"></div>
                            <div class="recording-bar"></div>
                            <div class="recording-bar"></div>
                            <div class="recording-bar"></div>
                            <div class="recording-bar"></div>
                        </div>
                        <div class="recording-actions">
                            <button class="recording-delete-btn" id="deleteRecordingBtn" title="Delete recording">
                                <i class="bi bi-trash"></i>
                            </button>
                            <button class="recording-send-btn" id="sendRecordingBtn" title="Send recording">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Audio Recording Modal -->
                    <div id="audioRecordingModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; z-index: 2000; box-shadow: 0 4px 20px rgba(0,0,0,0.2); min-width: 300px;">
                        <h5 class="mb-3">Record Audio Message</h5>
                        <div id="audioRecordingStatus" class="mb-3">
                            <p>Click "Start Recording" to begin</p>
                            <p id="recordingTime" style="display: none; font-weight: bold;">Recording: <span id="recordingDuration">0:00</span></p>
                        </div>
                        <div id="audioPlaybackContainer" style="display: none; margin-bottom: 15px;">
                            <audio id="audioPlayback" controls style="width: 100%; margin-bottom: 10px;"></audio>
                        </div>
                        <div class="d-flex gap-2">
                            <button id="startRecordingBtn" class="btn btn-primary btn-sm">Start Recording</button>
                            <button id="stopRecordingBtn" class="btn btn-danger btn-sm" style="display: none;">Stop Recording</button>
                            <button id="sendAudioBtn" class="btn btn-success btn-sm" style="display: none;">Send Audio</button>
                            <button id="closeAudioModalBtn" class="btn btn-secondary btn-sm">Close</button>
                        </div>
                    </div>

                    <!-- Camera Capture Overlay -->
                    <div id="cameraOverlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.9); z-index: 2000; padding: 20px; flex-direction: column; align-items: center; justify-content: center;">
                        <div style="position: relative; width: 100%; max-width: 600px; background: #000; border-radius: 12px; overflow: visible;">
                            <!-- Camera Stream -->
                            <div id="cameraStreamContainer" style="display: none; position: relative; width: 100%; padding-bottom: 133.33%; background: #000;">
                                <video id="cameraPreview" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; background: #000;"></video>
                            </div>

                            <!-- Captured Photo -->
                            <div id="capturedPhotoContainer" style="display: none; position: relative; width: 100%; padding-bottom: 133.33%; background: #000;">
                                <img id="capturedPhoto" src="" alt="Captured" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain; background: #000;">
                            </div>

                            <!-- Controls -->
                            <div style="position: absolute; bottom: 20px; left: 20px; right: 20px; display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; z-index: 10;">
                                <button id="switchCameraBtn" style="display: none;" title="Switch camera">
                                    <i class="bi bi-arrow-repeat"></i> <span class="d-none d-md-inline">Switch</span>
                                </button>
                                <button id="mirrorModeBtn" style="display: none;" title="Toggle mirror mode">
                                    <i class="bi bi-arrow-left-right"></i> <span class="d-none d-md-inline">Mirror</span>
                                </button>
                                <button id="capturePhotoBtn" style="display: none;">
                                    <i class="bi bi-camera-fill"></i> <span class="d-none d-md-inline">Capture</span>
                                </button>
                                <button id="retakeCameraBtn" style="display: none;">
                                    <i class="bi bi-arrow-counterclockwise"></i> <span class="d-none d-md-inline">Retake</span>
                                </button>
                                <button id="sendPhotoBtn" style="display: none;">
                                    <i class="bi bi-check-circle-fill"></i> <span class="d-none d-md-inline">Send</span>
                                </button>
                                <button id="closeCameraBtn">
                                    <i class="bi bi-x-circle-fill"></i> <span class="d-none d-md-inline">Close</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- File Preview Modal -->
                    <div id="filePreviewModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; z-index: 2000; box-shadow: 0 4px 20px rgba(0,0,0,0.2); max-width: 500px;">
                        <h5 class="mb-3">File Preview</h5>
                        <div id="filePreviewContent" style="margin-bottom: 15px; padding: 15px; background: #f8f9fa; border-radius: 4px;">
                            <div id="filePreviewInfo"></div>
                        </div>
                        <div class="d-flex gap-2">
                            <button id="sendFileBtn" class="btn btn-success btn-sm">Send File</button>
                            <button id="closeFileModalBtn" class="btn btn-secondary btn-sm">Cancel</button>
                        </div>
                    </div>

                    <!-- Overlay for modals -->
                    <div id="modalOverlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1999;"></div>

                    <!-- Full Image Viewer Modal -->
                    <div id="imageViewerModal">
                        <div class="image-viewer-container">
                            <div class="image-viewer-header">
                                <button class="image-viewer-btn" id="closeImageViewerBtn" title="Back">
                                    <i class="bi bi-arrow-left"></i>
                                </button>
                                <button class="image-viewer-btn delete" id="deleteImageBtn" title="Delete image">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <img id="viewerImage" src="" alt="Full view">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Message Context Menu -->
    <div class="message-context-menu" id="messageContextMenu">
        <button class="message-context-menu-item" id="contextEditBtn" onclick="openEditModal()">
            <i class="fa-solid fa-edit"></i>
            <span>Edit</span>
        </button>
        <button class="message-context-menu-item danger" id="contextDeleteBtn" onclick="openDeleteConfirmModal()">
            <i class="fa-solid fa-trash"></i>
            <span>Delete</span>
        </button>
    </div>

    <!-- Edit Message Modal -->
    <div class="edit-message-modal" id="editMessageModal">
        <div class="edit-message-modal-content">
            <div class="edit-message-modal-header">Edit Message</div>
            <div class="edit-message-modal-body">
                <textarea id="editMessageInput" placeholder="Enter your message..."></textarea>
            </div>
            <div class="edit-message-modal-footer">
                <button class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                <button class="btn-save" onclick="saveEditMessage()">Save</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="edit-message-modal" id="deleteConfirmModal">
        <div class="edit-message-modal-content">
            <div class="edit-message-modal-header">Delete Message</div>
            <div class="edit-message-modal-body">
                <p style="color: #666; margin: 0;">Are you sure you want to delete this message? This action cannot be undone.</p>
            </div>
            <div class="edit-message-modal-footer">
                <button class="btn-cancel" onclick="closeDeleteConfirmModal()">Cancel</button>
                <button class="btn-save" style="background-color: #dc3545;" onclick="confirmDeleteMessage()">Delete</button>
            </div>
        </div>
    </div>

    <script>
        let currentChatroomId = null;
        const LAST_CHATROOM_KEY = 'last_selected_chatroom';

        // Load chatrooms on page load
        document.addEventListener('DOMContentLoaded', async () => {
            // Wait for API_BASE_URL to be defined
            if (typeof API_BASE_URL === 'undefined') {
                console.error('API_BASE_URL is not defined. Waiting for scripts to load...');
                setTimeout(() => {
                    loadChatrooms();
                }, 500);
                return;
            }

            // Check if user has a valid token
            let token = localStorage.getItem('auth_token');

            // If no token in localStorage, check if one was provided by the server
            @if(isset($token))
                if (!token) {
                    token = '{{ $token }}';
                    localStorage.setItem('auth_token', token);
                    console.log('Token set from server');
                }
            @endif

            if (!token) {
                console.warn('No auth token found. User may not be logged in.');
                alert('Please log in to access the chatroom');
                window.location.href = '/login';
                return;
            } else {
                console.log('Auth token found:', token.substring(0, 10) + '...');
            }

            await loadChatrooms();
        });

        // Load chatrooms from API
        async function loadChatrooms() {
            try {
                const token = localStorage.getItem('auth_token');
                console.log('Loading chatrooms with token:', token ? 'present' : 'missing');

                const response = await fetch(`${API_BASE_URL}/chatrooms`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                console.log('Chatrooms response status:', response.status);

                if (!response.ok) {
                    const errorData = await response.json();
                    console.error('Failed to load chatrooms:', errorData);
                    throw new Error(`Failed to load chatrooms: ${response.status}`);
                }

                const data = await response.json();
                console.log('Chatrooms loaded:', data);
                const chatrooms = data.data || data;

                renderChatrooms(chatrooms);

                // Load last selected chatroom or default to "General"
                const lastChatroomId = localStorage.getItem(LAST_CHATROOM_KEY);
                let chatroomToLoad = null;

                if (lastChatroomId) {
                    // Check if last selected chatroom still exists (convert to string for comparison)
                    chatroomToLoad = chatrooms.find(room => String(room.id) === String(lastChatroomId));
                }

                // If no last chatroom or it doesn't exist, find "General" chatroom
                if (!chatroomToLoad) {
                    chatroomToLoad = chatrooms.find(room => room.name.toLowerCase() === 'general');
                    console.log('No last chatroom, found General:', chatroomToLoad ? chatroomToLoad.name : 'NOT FOUND');
                }

                // If still no chatroom found, use the first one
                if (!chatroomToLoad && chatrooms.length > 0) {
                    chatroomToLoad = chatrooms[0];
                    console.log('No General chatroom, using first:', chatroomToLoad.name);
                }

                // Load the selected chatroom
                if (chatroomToLoad) {
                    console.log('About to load chatroom:', chatroomToLoad.id, chatroomToLoad.name, 'Type of ID:', typeof chatroomToLoad.id);
                    // Use setTimeout to ensure DOM is fully updated before applying active state
                    setTimeout(() => {
                        console.log('Timeout fired, calling selectChatroom');
                        selectChatroom(chatroomToLoad.id, chatroomToLoad.name);
                    }, 200);
                } else {
                    console.warn('No chatroom to load!');
                }
            } catch (error) {
                console.error('Error loading chatrooms:', error);
            }
        }

        // Render chatrooms in sidebar
        function renderChatrooms(chatrooms) {
            const html = chatrooms.map(room => {
                const roomId = String(room.id); // Ensure ID is a string
                const levelTag = room.level ? `<span class="level-badge">${room.level}</span>` : '';
                return `
                <a href="#" class="sidebar-item chatroom-item" data-room-id="${roomId}" data-room-name="${room.name}" onclick="selectChatroom('${roomId}', '${room.name}'); return false;">
                    <div class="d-flex align-items-center justify-content-between w-100">
                        <div class="d-flex align-items-center">
                            <span class="badge me-2 d-flex justify-content-center align-items-center"
                                style="background: #114243; border-radius:20px; width: 25px;">
                                <i class="bi bi-hash text-white"></i>
                            </span>
                            ${room.name}
                        </div>
                        ${levelTag}
                    </div>
                    ${room.unread_count ? `<span class="new-message-badge">${room.unread_count}</span>` : ''}
                </a>
            `;
            }).join('');

            document.getElementById('chatrooms-list-desktop').innerHTML = html;
            document.getElementById('chatrooms-list-mobile').innerHTML = html;
            console.log('Chatrooms rendered:', chatrooms.length, 'rooms');
        }

        // Select chatroom
        async function selectChatroom(roomId, roomName) {
            // Ensure roomId is a string for consistent selector matching
            roomId = String(roomId);
            console.log('selectChatroom called with ID:', roomId, 'Name:', roomName, 'Type:', typeof roomId);

            currentChatroomId = roomId;
            document.getElementById('current-room-name').textContent = `#${roomName}`;

            // Save selected chatroom to localStorage for persistence
            localStorage.setItem(LAST_CHATROOM_KEY, roomId);

            // Update active state - remove from all
            const allItems = document.querySelectorAll('.chatroom-item');
            console.log('Total chatroom items found:', allItems.length);

            allItems.forEach(item => {
                item.classList.remove('active');
            });

            // Add active to the selected one(s) - there may be multiple (desktop and mobile)
            const selector = `[data-room-id="${roomId}"]`;
            console.log('Looking for elements with selector:', selector);

            const activeElements = document.querySelectorAll(selector);
            console.log('Elements found:', activeElements.length);

            if (activeElements.length > 0) {
                activeElements.forEach(element => {
                    element.classList.add('active');
                    console.log('✓ Active class added to chatroom element');
                });
                console.log('✓ Active class added to', activeElements.length, 'chatroom element(s) for:', roomName, 'ID:', roomId);
            } else {
                console.warn('✗ Could not find chatroom element with ID:', roomId);
                console.log('Available data-room-ids:', Array.from(allItems).map(item => item.getAttribute('data-room-id')));
            }

            await loadMessages(roomId);
            closeSidebar();
        }

        // Load messages for chatroom
        async function loadMessages(roomId) {
            try {
                const token = localStorage.getItem('auth_token');
                console.log('=== LOAD MESSAGES START ===');
                console.log('Loading messages for room', roomId, 'with token:', token ? 'present' : 'missing');

                const response = await fetch(`${API_BASE_URL}/chatrooms/${roomId}/messages`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                console.log('Messages response status:', response.status);

                if (!response.ok) {
                    const errorData = await response.json();
                    console.error('Failed to load messages:', errorData);
                    throw new Error(`Failed to load messages: ${response.status}`);
                }

                const data = await response.json();
                console.log('API Response:', data);
                const messages = data.data || data;
                console.log('Messages to render:', messages.length, 'messages');

                // Log the first message to see if edited_content is present
                if (messages.length > 0) {
                    console.log('First message:', messages[0]);
                }

                renderMessages(messages);
                console.log('=== LOAD MESSAGES COMPLETE ===');
            } catch (error) {
                console.error('=== LOAD MESSAGES ERROR ===');
                console.error('Error loading messages:', error);
            }
        }

        // Render messages
        function renderMessages(messages) {
            console.log('=== RENDER MESSAGES START ===');
            console.log('renderMessages called with:', messages.length, 'messages');

            // Get current user ID and role from localStorage
            let currentUserId = null;
            let userRole = null;
            const authUserStr = localStorage.getItem('auth_user');
            if (authUserStr) {
                try {
                    const authUser = JSON.parse(authUserStr);
                    currentUserId = authUser?.id;
                    userRole = authUser?.role;
                } catch (e) {
                    console.error('Failed to parse auth_user:', e);
                }
            }
            console.log('Current user ID:', currentUserId, 'Role:', userRole);

            if (!messages || messages.length === 0) {
                console.log('No messages to render');
                document.getElementById('chat-messages').innerHTML = '<p class="text-muted text-center">No messages yet</p>';
                return;
            }

            // Sort messages by created_at date (oldest first, so newest appears at bottom)
            const sortedMessages = [...messages].sort((a, b) => {
                return new Date(a.created_at) - new Date(b.created_at);
            });

            // Build HTML with date separators
            let html = '';
            let lastDate = null;

            sortedMessages.forEach((msg, index) => {
                // Get the date of the message
                const messageDate = new Date(msg.created_at);
                const messageDateString = messageDate.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                // Add date separator if this is a new day
                if (lastDate !== messageDateString) {
                    html += `<div class="message-date-separator"><span>${messageDateString}</span></div>`;
                    lastDate = messageDateString;
                }

                // Now render the message
                const messageHtml = renderSingleMessage(msg, currentUserId, userRole, currentChatroomId);
                html += messageHtml;

                // Log edited messages
                if (msg.edited_content) {
                    console.log('Message', index, 'is edited:', {
                        id: msg.id,
                        content: msg.content,
                        edited_content: msg.edited_content,
                        edited_at: msg.edited_at
                    });
                }
            });

            console.log('Rendered HTML length:', html.length);
            document.getElementById('chat-messages').innerHTML = html;
            console.log('=== RENDER MESSAGES COMPLETE ===');

            // Load audio durations after rendering
            setTimeout(() => {
                loadAudioDurations();
            }, 100);

            // Auto-scroll to bottom to show latest messages
            const chatHistory = document.getElementById('chat-history');
            if (chatHistory) {
                setTimeout(() => {
                    chatHistory.scrollTop = chatHistory.scrollHeight;
                }, 0);
            }
        }

        // Render a single message
        function renderSingleMessage(msg, currentUserId, userRole, currentChatroomId) {
                const isCurrentUser = msg.user_id === currentUserId || msg.user_id == currentUserId;
                const isCurrentUserAdmin = ['admin', 'superadmin'].includes(userRole);
                const canEditDelete = isCurrentUser || isCurrentUserAdmin;
                const userFirstName = msg.user?.first_name || 'Unknown';
                const userLastName = msg.user?.last_name || 'User';
                const profilePhoto = msg.user?.profile_photo || '/images/default-avatar.png';
                const messageTime = new Date(msg.created_at).toLocaleTimeString();
                // Display edited_content if it exists, otherwise display original content
                const messageContent = msg.edited_content || msg.content || '';
                const isDeleted = msg.is_deleted;
                const isEdited = msg.edited_content && msg.edited_at;
                const messageType = msg.type || 'text';

                // Debug logging for edited messages
                if (msg.edited_content) {
                    console.log('Rendering edited message:', {
                        id: msg.id,
                        original: msg.content,
                        edited: msg.edited_content,
                        displaying: messageContent
                    });
                }

                // Check if the message sender is an admin or superadmin
                const senderRole = msg.user?.role || null;
                const isSenderAdmin = ['admin', 'superadmin'].includes(senderRole);
                const adminBadge = isSenderAdmin ? '<span class="admin-badge">Administrator</span>' : '';

                // Show deleted message indicator
                if (isDeleted) {
                    return `
                        <div class="chat-message ${isCurrentUser ? 'current-user-message' : ''}">
                            ${!isCurrentUser ? `<img src="${profilePhoto}" alt="Avatar" class="message-avatar" onerror="this.src='/images/default-avatar.png'">` : ''}
                            <div class="message-content">
                                ${!isCurrentUser ? `<span class="message-user">${userFirstName} ${userLastName}${adminBadge}</span>` : ''}
                                <span class="message-timestamp">${messageTime}</span>
                                <p class="mb-1 text-muted fst-italic">This message has been deleted</p>
                            </div>
                        </div>
                    `;
                }

                // Build context menu attributes for messages that can be edited/deleted
                // Only text messages can be edited
                // Audio, image, and file messages can only be deleted
                let contextMenuAttrs = '';
                if (canEditDelete) {
                    contextMenuAttrs = `
                        onclick="showMessageContextMenu(event, ${msg.id}, '${currentChatroomId}', '${messageContent.replace(/'/g, "\\'")}')"
                        data-message-type="${messageType}"
                        style="cursor: pointer; position: relative;"
                    `;
                }

                // Build edited indicator
                const editedIndicator = isEdited ? '<span class="message-edited-indicator" style="font-size: 0.75rem; color: #999; margin-left: 4px;">(edited)</span>' : '';

                // Build message content based on type
                let messageBody = '';
                if (messageType === 'image') {
                    const fileUrl = msg.metadata?.file_url || `/storage/${msg.metadata?.file_path || ''}`;
                    messageBody = `<div class="message-image-container"><img src="${fileUrl}" alt="Image" class="message-image" onclick="openImageViewer(event, '${fileUrl}', ${msg.id}, '${currentChatroomId}')" onerror="this.src='/images/default-avatar.png'" style="cursor: pointer;"></div>`;
                } else if (messageType === 'audio') {
                    const fileUrl = msg.metadata?.file_url || `/storage/${msg.metadata?.file_path || ''}`;
                    const audioId = `audio-${msg.id}`;
                    messageBody = `
                        <div class="message-audio" data-audio-id="${audioId}">
                            <div class="message-audio-controls">
                                <button class="message-audio-play-btn" onclick="toggleAudioPlayback('${audioId}', '${fileUrl}')">
                                    <i class="bi bi-play-fill"></i>
                                </button>
                                <div class="message-audio-progress-container">
                                    <div class="message-audio-progress" onclick="seekAudio(event, '${audioId}')">
                                        <div class="message-audio-progress-bar" id="progress-${audioId}"></div>
                                    </div>
                                    <span class="message-audio-time" id="time-${audioId}">0:00</span>
                                </div>
                            </div>
                            <audio id="${audioId}" style="display: none;" onended="onAudioEnded('${audioId}')">
                                <source src="${fileUrl}" type="audio/webm">
                            </audio>
                        </div>
                    `;
                } else if (messageType === 'file') {
                    const fileUrl = msg.metadata?.file_url || `/storage/${msg.metadata?.file_path || ''}`;
                    const fileName = msg.metadata?.file_name || 'Download File';
                    messageBody = `<a href="${fileUrl}" class="message-file" download><i class="bi bi-file-earmark"></i> <span class="message-file-name">${fileName}</span></a>`;
                } else {
                    messageBody = `<p class="mb-1">${messageContent}${editedIndicator}</p>`;
                }

                return `
                    <div class="chat-message ${isCurrentUser ? 'current-user-message' : ''}" data-message-id="${msg.id}">
                        ${!isCurrentUser ? `<img src="${profilePhoto}" alt="Avatar" class="message-avatar" onerror="this.src='/images/default-avatar.png'">` : ''}
                        <div class="message-content" ${contextMenuAttrs}>
                            ${!isCurrentUser ? `<span class="message-user">${userFirstName} ${userLastName}${adminBadge}</span>` : ''}
                            <span class="message-timestamp">${messageTime}</span>
                            ${messageBody}
                        </div>
                    </div>
                `;
        }

        // Context menu state
        let currentContextMessage = {
            id: null,
            roomId: null,
            content: null
        };

        // Show message context menu on click
        function showMessageContextMenu(event, messageId, roomId, messageContent) {
            event.stopPropagation();

            console.log('Context menu triggered:', {
                messageId: messageId,
                roomId: roomId,
                contentLength: messageContent.length
            });

            // Get message type from the clicked element
            const messageEl = event.currentTarget;
            const messageType = messageEl.getAttribute('data-message-type') || 'text';

            // Store current message info
            currentContextMessage = {
                id: messageId,
                roomId: roomId,
                content: messageContent,
                type: messageType
            };

            const contextMenu = document.getElementById('messageContextMenu');
            const editBtn = document.getElementById('contextEditBtn');

            // Only show edit button for text messages
            if (messageType === 'text') {
                editBtn.style.display = 'block';
            } else {
                editBtn.style.display = 'none';
            }

            contextMenu.classList.add('show');

            // Position the menu below the message
            const rect = messageEl.getBoundingClientRect();
            contextMenu.style.left = rect.left + 'px';
            contextMenu.style.top = (rect.bottom + 5) + 'px';

            console.log('Context menu positioned at:', rect.left, rect.bottom + 5);

            // Close menu when clicking elsewhere
            setTimeout(() => {
                document.addEventListener('click', closeContextMenu);
            }, 0);
        }



        // Close context menu
        function closeContextMenu() {
            const contextMenu = document.getElementById('messageContextMenu');
            contextMenu.classList.remove('show');
            document.removeEventListener('click', closeContextMenu);
        }

        // Open edit modal
        function openEditModal() {
            console.log('Opening edit modal for message:', currentContextMessage.id);
            closeContextMenu();
            const editInput = document.getElementById('editMessageInput');
            editInput.value = currentContextMessage.content;
            editInput.focus();
            editInput.select();

            const modal = document.getElementById('editMessageModal');
            modal.classList.add('show');
            console.log('Edit modal opened');
        }

        // Close edit modal
        function closeEditModal() {
            const modal = document.getElementById('editMessageModal');
            modal.classList.remove('show');
            console.log('Edit modal closed');
        }

        // Save edited message
        async function saveEditMessage() {
            const newContent = document.getElementById('editMessageInput').value.trim();

            if (!newContent) {
                alert('Message cannot be empty');
                return;
            }

            try {
                const token = localStorage.getItem('auth_token');
                const url = `${API_BASE_URL}/chatrooms/${currentContextMessage.roomId}/messages/${currentContextMessage.id}`;

                console.log('=== EDIT MESSAGE START ===');
                console.log('Editing message:', {
                    messageId: currentContextMessage.id,
                    roomId: currentContextMessage.roomId,
                    url: url,
                    newContent: newContent,
                    tokenPresent: !!token
                });

                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content: newContent })
                });

                console.log('Edit response status:', response.status);
                const responseData = await response.json();
                console.log('Edit response data:', responseData);

                if (!response.ok) {
                    console.error('Edit failed with status:', response.status);
                    alert('Failed to edit message: ' + (responseData.message || 'Unknown error'));
                    return;
                }

                console.log('Edit successful, closing modal and reloading messages...');
                closeEditModal();

                console.log('About to load messages for room:', currentContextMessage.roomId);
                await loadMessages(currentContextMessage.roomId);
                console.log('=== EDIT MESSAGE SUCCESS ===');
            } catch (error) {
                console.error('=== EDIT MESSAGE ERROR ===');
                console.error('Error editing message:', error);
                alert('Failed to edit message: ' + error.message);
            }
        }

        // Open delete confirmation modal
        function openDeleteConfirmModal() {
            console.log('Opening delete confirmation modal for message:', currentContextMessage.id);
            closeContextMenu();
            const modal = document.getElementById('deleteConfirmModal');
            modal.classList.add('show');
            console.log('Delete confirmation modal opened');
        }

        // Close delete confirmation modal
        function closeDeleteConfirmModal() {
            const modal = document.getElementById('deleteConfirmModal');
            modal.classList.remove('show');
            console.log('Delete confirmation modal closed');
        }

        // Confirm delete message
        async function confirmDeleteMessage() {
            try {
                const token = localStorage.getItem('auth_token');
                const roomId = currentContextMessage.roomId || currentChatroomId;
                const url = `${API_BASE_URL}/chatrooms/${roomId}/messages/${currentContextMessage.id}`;

                console.log('Deleting message:', {
                    messageId: currentContextMessage.id,
                    roomId: roomId,
                    currentChatroomId: currentChatroomId,
                    url: url,
                    tokenPresent: !!token
                });

                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                console.log('Delete response status:', response.status);
                const responseData = await response.json();
                console.log('Delete response data:', responseData);

                if (!response.ok) {
                    alert('Failed to delete message: ' + (responseData.message || 'Unknown error'));
                    return;
                }

                closeDeleteConfirmModal();
                console.log('About to reload messages for room:', roomId);
                // Small delay to ensure modal is closed before reloading
                setTimeout(async () => {
                    await loadMessages(roomId);
                    console.log('Message deleted successfully and chat reloaded');
                }, 100);
            } catch (error) {
                console.error('Error deleting message:', error);
                alert('Failed to delete message: ' + error.message);
            }
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            const editModal = document.getElementById('editMessageModal');
            const deleteModal = document.getElementById('deleteConfirmModal');

            if (event.target === editModal) {
                closeEditModal();
            }
            if (event.target === deleteModal) {
                closeDeleteConfirmModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeEditModal();
                closeDeleteConfirmModal();
                closeContextMenu();
            }
        });

        // Send message
        document.getElementById('sendBtn')?.addEventListener('click', async () => {
            if (!currentChatroomId) {
                alert('Please select a chatroom first');
                return;
            }

            const messageInput = document.getElementById('messageInput');
            const content = messageInput.value.trim();

            if (!content) return;

            try {
                const token = localStorage.getItem('auth_token');
                console.log('Sending message to room:', currentChatroomId);
                console.log('Token present:', token ? 'YES' : 'NO');

                const response = await fetch(`${API_BASE_URL}/chatrooms/${currentChatroomId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content })
                });

                console.log('Send message response status:', response.status);

                if (!response.ok) {
                    const errorData = await response.json();
                    console.error('Server error response:', errorData);
                    throw new Error(errorData.message || 'Failed to send message');
                }

                const responseData = await response.json();
                console.log('Message sent successfully:', responseData);

                messageInput.value = '';
                await loadMessages(currentChatroomId);
            } catch (error) {
                console.error('Error sending message:', error);
                alert('Failed to send message: ' + error.message);
            }
        });

        // Sidebar toggle
        const overlayMobile = document.getElementById('sidebarOverlay');
        const sidebarMobile = document.getElementById('sidebar-mobile');
        const toggleBtn = document.getElementById('toggleSidebar');
        const closeBtn = document.getElementById('closeBtn');

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
        closeBtn?.addEventListener('click', closeSidebar)
        toggleBtn?.addEventListener('click', openSidebar);
        overlayMobile.addEventListener('click', closeSidebar);

        // ===== ADMIN/SUPERADMIN SIDEBAR MANAGER =====
        // Check if user is admin or superadmin and load the admin sidebar manager
        document.addEventListener('DOMContentLoaded', function() {
            const userStr = localStorage.getItem('auth_user');
            if (userStr) {
                try {
                    const user = JSON.parse(userStr);
                    // If user is admin or superadmin, load the admin sidebar manager
                    if (['admin', 'superadmin'].includes(user.role)) {
                        // Load the admin sidebar manager script
                        const script = document.createElement('script');
                        script.src = "{{ asset('js/sidebarManager.js') }}";
                        script.onload = function() {
                            // Initialize the sidebar manager for admin users
                            if (typeof SidebarManager !== 'undefined') {
                                SidebarManager.init();
                            }
                        };
                        document.body.appendChild(script);
                    }
                } catch (e) {
                    console.error('Failed to parse user data:', e);
                }
            }
        });

        // ============ EMOJI FUNCTIONALITY ============
        const emojiBtn = document.getElementById('emojiBtn');
        const emojiPickerContainer = document.getElementById('emojiPickerContainer');
        const messageInput = document.getElementById('messageInput');

        // Common emojis for quick access
        const commonEmojis = ['😀', '😂', '😍', '🥰', '😎', '🤔', '👍', '👏', '🎉', '🔥', '💯', '✨', '😢', '😡', '🤷', '👋', '💪', '🙏', '❤️', '💔'];

        // Initialize emoji picker
        function initEmojiPicker() {
            const emojiPicker = document.getElementById('emojiPicker');
            emojiPicker.innerHTML = commonEmojis.map(emoji =>
                `<span style="cursor: pointer; font-size: 24px; padding: 5px; display: inline-block;" onclick="insertEmoji('${emoji}')">${emoji}</span>`
            ).join('');
        }

        // Insert emoji into message
        window.insertEmoji = function(emoji) {
            messageInput.value += emoji;
            messageInput.focus();
            emojiPickerContainer.style.display = 'none';
        };

        // Toggle emoji picker
        emojiBtn.addEventListener('click', () => {
            if (emojiPickerContainer.style.display === 'none') {
                initEmojiPicker();
                emojiPickerContainer.style.display = 'block';
            } else {
                emojiPickerContainer.style.display = 'none';
            }
        });

        // Close emoji picker when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#emojiBtn') && !e.target.closest('#emojiPickerContainer')) {
                emojiPickerContainer.style.display = 'none';
            }
        });



        // ============ CAMERA FUNCTIONALITY (Take Pictures) ============
        const cameraBtn = document.getElementById('cameraBtn');
        const cameraOverlay = document.getElementById('cameraOverlay');
        const cameraPreview = document.getElementById('cameraPreview');
        const cameraStreamContainer = document.getElementById('cameraStreamContainer');
        const capturedPhotoContainer = document.getElementById('capturedPhotoContainer');
        const capturedPhoto = document.getElementById('capturedPhoto');
        const capturePhotoBtn = document.getElementById('capturePhotoBtn');
        const retakeCameraBtn = document.getElementById('retakeCameraBtn');
        const sendPhotoBtn = document.getElementById('sendPhotoBtn');
        const closeCameraBtn = document.getElementById('closeCameraBtn');
        const switchCameraBtn = document.getElementById('switchCameraBtn');
        const mirrorModeBtn = document.getElementById('mirrorModeBtn');
        let cameraStream = null;
        let capturedPhotoBlob = null;
        let currentFacingMode = 'user'; // Track current camera (user = front, environment = back)
        let isMirrorMode = true; // Mirror mode enabled by default for front camera

        // Start camera immediately when button is clicked
        cameraBtn.addEventListener('click', async () => {
            try {
                console.log('Starting camera...');
                cameraOverlay.style.display = 'flex';

                // Request camera access
                cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user',
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    },
                    audio: false
                });

                console.log('Camera stream obtained:', cameraStream);

                // Set video source and ensure it plays
                cameraPreview.srcObject = cameraStream;
                cameraPreview.onloadedmetadata = () => {
                    console.log('Video metadata loaded');
                    cameraPreview.play().catch(err => console.error('Play error:', err));
                };

                cameraStreamContainer.style.display = 'block';
                capturedPhotoContainer.style.display = 'none';
                capturePhotoBtn.classList.add('visible');
                retakeCameraBtn.classList.remove('visible');
                sendPhotoBtn.classList.remove('visible');
                closeCameraBtn.classList.add('visible');

                console.log('Camera ready for capture');
                // Show mirror mode button and apply mirror effect for front camera
                mirrorModeBtn.classList.add('visible');
                if (currentFacingMode === 'user' && isMirrorMode) {
                    cameraPreview.classList.add('mirror-mode');
                } else {
                    cameraPreview.classList.remove('mirror-mode');
                }
            } catch (error) {
                console.error('Error accessing camera:', error);
                cameraOverlay.style.display = 'none';
                alert('Unable to access camera. Please check permissions and try again.');
            }
        });

        // Switch between front and back camera
        async function switchCamera() {
            try {
                console.log('Switching camera from', currentFacingMode, 'to', currentFacingMode === 'user' ? 'environment' : 'user');

                // Stop current camera stream
                if (cameraStream) {
                    cameraStream.getTracks().forEach(track => track.stop());
                    cameraStream = null;
                }

                // Toggle facing mode
                currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';

                // Request new camera with different facing mode
                cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: currentFacingMode,
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    },
                    audio: false
                });

                console.log('Camera switched to:', currentFacingMode);

                // Set new video source
                cameraPreview.srcObject = cameraStream;
                cameraPreview.onloadedmetadata = () => {
                    console.log('New camera stream loaded');
                    cameraPreview.play().catch(err => console.error('Play error:', err));
                };

                // Apply mirror mode only for front camera
                if (currentFacingMode === 'user' && isMirrorMode) {
                    cameraPreview.classList.add('mirror-mode');
                } else {
                    cameraPreview.classList.remove('mirror-mode');
                }

                // Ensure buttons remain visible after camera switch
                mirrorModeBtn.classList.add('visible');
            } catch (error) {
                console.error('Error switching camera:', error);
                alert('Unable to switch camera. Your device may not have multiple cameras.');
                // Reset facing mode on error
                currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
            }
        }

        switchCameraBtn.addEventListener('click', switchCamera);

        // Toggle mirror mode
        function toggleMirrorMode() {
            try {
                isMirrorMode = !isMirrorMode;
                console.log('Mirror mode toggled to:', isMirrorMode);

                if (isMirrorMode && currentFacingMode === 'user') {
                    cameraPreview.classList.add('mirror-mode');
                    console.log('Mirror mode enabled');
                } else {
                    cameraPreview.classList.remove('mirror-mode');
                    console.log('Mirror mode disabled');
                }

                // Update button appearance
                if (isMirrorMode) {
                    mirrorModeBtn.style.opacity = '1';
                } else {
                    mirrorModeBtn.style.opacity = '0.5';
                }
            } catch (error) {
                console.error('Error toggling mirror mode:', error);
            }
        }

        mirrorModeBtn.addEventListener('click', toggleMirrorMode);

        capturePhotoBtn.addEventListener('click', () => {
            try {
                console.log('Capturing photo...');
                const canvas = document.createElement('canvas');
                canvas.width = cameraPreview.videoWidth;
                canvas.height = cameraPreview.videoHeight;

                if (canvas.width === 0 || canvas.height === 0) {
                    console.error('Video dimensions not available');
                    alert('Camera not ready. Please wait a moment and try again.');
                    return;
                }

                const ctx = canvas.getContext('2d');
                ctx.drawImage(cameraPreview, 0, 0);

                canvas.toBlob((blob) => {
                    capturedPhotoBlob = blob;
                    capturedPhoto.src = canvas.toDataURL();
                    capturedPhotoContainer.style.display = 'block';
                    cameraStreamContainer.style.display = 'none';
                    capturePhotoBtn.classList.remove('visible');
                    retakeCameraBtn.classList.add('visible');
                    sendPhotoBtn.classList.add('visible');
                    console.log('Photo captured successfully');
                }, 'image/jpeg', 0.95);
            } catch (error) {
                console.error('Error capturing photo:', error);
                alert('Failed to capture photo. Please try again.');
            }
        });

        retakeCameraBtn.addEventListener('click', () => {
            console.log('Retaking photo...');
            capturedPhotoContainer.style.display = 'none';
            cameraStreamContainer.style.display = 'block';
            capturePhotoBtn.classList.add('visible');
            retakeCameraBtn.classList.remove('visible');
            sendPhotoBtn.classList.remove('visible');
            mirrorModeBtn.classList.add('visible');
            capturedPhotoBlob = null;
        });

        sendPhotoBtn.addEventListener('click', async () => {
            if (!capturedPhotoBlob || !currentChatroomId) return;

            try {
                console.log('Sending photo...');
                const formData = new FormData();
                formData.append('content', 'Sent a picture');
                formData.append('type', 'image');
                formData.append('file', capturedPhotoBlob, 'photo.jpg');

                const token = localStorage.getItem('auth_token');
                const response = await fetch(`${API_BASE_URL}/chatrooms/${currentChatroomId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to send photo');
                }

                console.log('Photo sent successfully');

                // Stop camera stream
                if (cameraStream) {
                    cameraStream.getTracks().forEach(track => track.stop());
                    cameraStream = null;
                }

                cameraOverlay.style.display = 'none';
                capturedPhotoBlob = null;
                cameraStreamContainer.style.display = 'none';
                capturedPhotoContainer.style.display = 'none';
                capturePhotoBtn.classList.remove('visible');
                retakeCameraBtn.classList.remove('visible');
                sendPhotoBtn.classList.remove('visible');
                switchCameraBtn.classList.remove('visible');
                mirrorModeBtn.classList.remove('visible');
                // Reset facing mode to front camera for next use
                currentFacingMode = 'user';
                // Reset mirror mode to enabled for next use
                isMirrorMode = true;
                cameraPreview.classList.remove('mirror-mode');
                await loadMessages(currentChatroomId);
            } catch (error) {
                console.error('Error sending photo:', error);
                alert('Failed to send photo: ' + error.message);
            }
        });

        closeCameraBtn.addEventListener('click', () => {
            console.log('Closing camera...');
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }
            cameraOverlay.style.display = 'none';
            capturedPhotoBlob = null;
            cameraStreamContainer.style.display = 'none';
            capturedPhotoContainer.style.display = 'none';
            capturePhotoBtn.classList.remove('visible');
            retakeCameraBtn.classList.remove('visible');
            sendPhotoBtn.classList.remove('visible');
            switchCameraBtn.classList.remove('visible');
            mirrorModeBtn.classList.remove('visible');
            // Reset facing mode to front camera for next use
            currentFacingMode = 'user';
            // Reset mirror mode to enabled for next use
            isMirrorMode = true;
            cameraPreview.classList.remove('mirror-mode');
        });

        // ============ FILE ATTACHMENT FUNCTIONALITY ============
        const attachmentBtn = document.getElementById('attachmentBtn');
        const fileInput = document.getElementById('fileInput');
        const filePreviewModal = document.getElementById('filePreviewModal');
        const filePreviewInfo = document.getElementById('filePreviewInfo');
        const sendFileBtn = document.getElementById('sendFileBtn');
        const closeFileModalBtn = document.getElementById('closeFileModalBtn');
        let selectedFile = null;

        attachmentBtn.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                selectedFile = file;
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                filePreviewInfo.innerHTML = `
                    <div style="text-align: center;">
                        <i class="bi bi-file-earmark" style="font-size: 48px; color: #666;"></i>
                        <p style="margin-top: 10px; margin-bottom: 5px;"><strong>${file.name}</strong></p>
                        <p style="color: #666; font-size: 0.9rem;">${fileSize} MB</p>
                        <p style="color: #999; font-size: 0.85rem;">${file.type || 'Unknown type'}</p>
                    </div>
                `;
                filePreviewModal.style.display = 'block';
                modalOverlay.style.display = 'block';
            }
        });

        closeFileModalBtn.addEventListener('click', () => {
            filePreviewModal.style.display = 'none';
            modalOverlay.style.display = 'none';
            selectedFile = null;
            fileInput.value = '';
        });

        sendFileBtn.addEventListener('click', async () => {
            if (!selectedFile || !currentChatroomId) return;

            try {
                const formData = new FormData();
                formData.append('content', `Sent a file: ${selectedFile.name}`);
                formData.append('type', 'file');
                formData.append('file', selectedFile);

                const token = localStorage.getItem('auth_token');
                const response = await fetch(`${API_BASE_URL}/chatrooms/${currentChatroomId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to send file');
                }

                filePreviewModal.style.display = 'none';
                modalOverlay.style.display = 'none';
                selectedFile = null;
                fileInput.value = '';
                await loadMessages(currentChatroomId);
            } catch (error) {
                console.error('Error sending file:', error);
                alert('Failed to send file: ' + error.message);
            }
        });

        // ============ AUDIO RECORDING FUNCTIONALITY (WhatsApp Style) ============
        const audioBtn = document.getElementById('audioBtn');
        const audioRecordingUI = document.getElementById('audioRecordingUI');
        const recordingTimeDisplay = document.getElementById('recordingTime');
        const deleteRecordingBtn = document.getElementById('deleteRecordingBtn');
        const sendRecordingBtn = document.getElementById('sendRecordingBtn');
        const recordingWaveform = document.getElementById('recordingWaveform');

        let mediaRecorder;
        let audioChunks = [];
        let recordingStartTime;
        let recordingInterval;
        let audioBlob;
        let isRecording = false;
        let audioContext;
        let analyser;
        let dataArray;
        let animationId;
        let audioStream;

        audioBtn.addEventListener('click', async () => {
            if (isRecording) {
                // Stop recording
                stopRecording();
            } else {
                // Start recording
                startRecording();
            }
        });

        async function startRecording() {
            try {
                audioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(audioStream);
                audioChunks = [];

                // Setup audio context for waveform visualization
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
                analyser = audioContext.createAnalyser();
                analyser.fftSize = 256;
                const source = audioContext.createMediaStreamSource(audioStream);
                source.connect(analyser);

                const bufferLength = analyser.frequencyBinCount;
                dataArray = new Uint8Array(bufferLength);

                mediaRecorder.ondataavailable = (event) => {
                    audioChunks.push(event.data);
                };

                mediaRecorder.onstop = () => {
                    audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                    clearInterval(recordingInterval);
                    cancelAnimationFrame(animationId);
                    // Stop all audio tracks
                    audioStream.getTracks().forEach(track => track.stop());
                };

                mediaRecorder.start();
                isRecording = true;
                audioBtn.classList.add('recording');
                audioRecordingUI.classList.add('active');
                recordingStartTime = Date.now();

                // Start waveform animation
                animateWaveform();

                recordingInterval = setInterval(() => {
                    const elapsed = Math.floor((Date.now() - recordingStartTime) / 1000);
                    const minutes = Math.floor(elapsed / 60);
                    const seconds = elapsed % 60;
                    recordingTimeDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                }, 100);
            } catch (error) {
                console.error('Error accessing microphone:', error);
                alert('Unable to access microphone. Please check permissions.');
            }
        }

        function animateWaveform() {
            analyser.getByteFrequencyData(dataArray);

            // Get the bars
            const bars = recordingWaveform.querySelectorAll('.recording-bar');

            // Update each bar height based on frequency data
            for (let i = 0; i < bars.length; i++) {
                const index = Math.floor((i / bars.length) * dataArray.length);
                const value = dataArray[index];
                // Map frequency value (0-255) to height (8px-28px)
                const height = 8 + (value / 255) * 20;
                bars[i].style.height = height + 'px';
            }

            animationId = requestAnimationFrame(animateWaveform);
        }

        function stopRecording() {
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
                isRecording = false;
                audioBtn.classList.remove('recording');
                clearInterval(recordingInterval);
                cancelAnimationFrame(animationId);
            }
        }

        deleteRecordingBtn.addEventListener('click', () => {
            stopRecording();
            audioChunks = [];
            audioBlob = null;
            audioRecordingUI.classList.remove('active');
            recordingTimeDisplay.textContent = '0:00';
            // Reset waveform bars
            const bars = recordingWaveform.querySelectorAll('.recording-bar');
            bars.forEach(bar => bar.style.height = '8px');
        });

        sendRecordingBtn.addEventListener('click', async () => {
            if (!currentChatroomId) return;

            try {
                // Stop recording first to finalize the blob
                if (isRecording) {
                    await new Promise((resolve) => {
                        const checkBlob = setInterval(() => {
                            if (audioBlob) {
                                clearInterval(checkBlob);
                                resolve();
                            }
                        }, 10);
                        stopRecording();
                    });
                }

                if (!audioBlob) {
                    alert('No audio recorded');
                    return;
                }

                const formData = new FormData();
                formData.append('content', 'Sent an audio message');
                formData.append('type', 'audio');
                formData.append('file', audioBlob, 'audio.webm');

                const token = localStorage.getItem('auth_token');
                const response = await fetch(`${API_BASE_URL}/chatrooms/${currentChatroomId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to send audio');
                }

                audioRecordingUI.classList.remove('active');
                audioChunks = [];
                audioBlob = null;
                recordingTimeDisplay.textContent = '0:00';
                // Reset waveform bars
                const bars = recordingWaveform.querySelectorAll('.recording-bar');
                bars.forEach(bar => bar.style.height = '8px');
                await loadMessages(currentChatroomId);
            } catch (error) {
                console.error('Error sending audio:', error);
                alert('Failed to send audio: ' + error.message);
            }
        });

        // Close modals when clicking overlay
        modalOverlay.addEventListener('click', () => {
            if (imagePreviewModal.style.display === 'block') {
                closeImageModalBtn.click();
            }
        });

        // ============ AUDIO PLAYBACK FUNCTIONALITY ============
        let currentPlayingAudioId = null;
        let audioTimeUpdateListeners = {};

        function formatTime(seconds) {
            if (isNaN(seconds) || !isFinite(seconds)) return '0:00';
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        }

        function updateAudioProgress(audioId) {
            const audioElement = document.getElementById(audioId);
            const progressBar = document.getElementById(`progress-${audioId}`);
            const timeDisplay = document.getElementById(`time-${audioId}`);

            if (audioElement && progressBar && timeDisplay) {
                const duration = audioElement.duration || 0;
                const currentTime = audioElement.currentTime || 0;
                const percentage = duration > 0 ? (currentTime / duration) * 100 : 0;

                progressBar.style.width = percentage + '%';
                // Show current time / total duration
                timeDisplay.textContent = `${formatTime(currentTime)} / ${formatTime(duration)}`;
            }
        }

        // Load audio duration when messages are rendered
        function loadAudioDurations() {
            const audioElements = document.querySelectorAll('audio[id^="audio-"]');
            audioElements.forEach(audioElement => {
                const audioId = audioElement.id;
                const timeDisplay = document.getElementById(`time-${audioId}`);

                // Function to update duration display
                const updateDuration = () => {
                    if (timeDisplay && audioElement.duration) {
                        const duration = audioElement.duration || 0;
                        timeDisplay.textContent = `0:00 / ${formatTime(duration)}`;
                    }
                };

                // If metadata is already loaded
                if (audioElement.readyState >= 1) {
                    updateDuration();
                } else {
                    // Wait for metadata to load
                    audioElement.addEventListener('loadedmetadata', updateDuration, { once: true });
                }
            });
        }

        function toggleAudioPlayback(audioId, audioUrl) {
            const audioElement = document.getElementById(audioId);
            const playBtn = document.querySelector(`[onclick*="toggleAudioPlayback('${audioId}"]`);
            const timeDisplay = document.getElementById(`time-${audioId}`);

            // Stop any currently playing audio
            if (currentPlayingAudioId && currentPlayingAudioId !== audioId) {
                const previousAudio = document.getElementById(currentPlayingAudioId);
                const previousBtn = document.querySelector(`[onclick*="toggleAudioPlayback('${currentPlayingAudioId}"]`);
                if (previousAudio) {
                    previousAudio.pause();
                }
                if (previousBtn) {
                    previousBtn.classList.remove('playing');
                    previousBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
                }
            }

            if (audioElement.paused) {
                // Play audio
                audioElement.play();
                playBtn.classList.add('playing');
                playBtn.innerHTML = '<i class="bi bi-pause-fill"></i>';
                currentPlayingAudioId = audioId;

                // Add time update listener if not already added
                if (!audioTimeUpdateListeners[audioId]) {
                    const updateProgress = () => {
                        updateAudioProgress(audioId);
                    };
                    audioElement.addEventListener('timeupdate', updateProgress);
                    audioTimeUpdateListeners[audioId] = updateProgress;
                }

                // Load metadata and display duration
                if (audioElement.readyState >= 1) {
                    updateAudioProgress(audioId);
                } else {
                    audioElement.addEventListener('loadedmetadata', () => {
                        updateAudioProgress(audioId);
                    }, { once: true });
                }
            } else {
                // Pause audio
                audioElement.pause();
                playBtn.classList.remove('playing');
                playBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
                currentPlayingAudioId = null;
            }
        }

        function seekAudio(event, audioId) {
            const audioElement = document.getElementById(audioId);
            if (!audioElement) return;

            const progressContainer = event.currentTarget;
            const rect = progressContainer.getBoundingClientRect();
            const clickX = event.clientX - rect.left;
            const percentage = Math.max(0, Math.min(clickX / rect.width, 1));

            // Wait for metadata to be loaded if needed
            if (audioElement.readyState >= 1) {
                const newTime = percentage * audioElement.duration;
                audioElement.currentTime = Math.max(0, Math.min(newTime, audioElement.duration));
                updateAudioProgress(audioId);
            } else {
                // If metadata not loaded yet, wait for it
                audioElement.addEventListener('loadedmetadata', () => {
                    const newTime = percentage * audioElement.duration;
                    audioElement.currentTime = Math.max(0, Math.min(newTime, audioElement.duration));
                    updateAudioProgress(audioId);
                }, { once: true });
            }
        }

        function onAudioEnded(audioId) {
            const playBtn = document.querySelector(`[onclick*="toggleAudioPlayback('${audioId}"]`);
            const audioElement = document.getElementById(audioId);

            if (playBtn) {
                playBtn.classList.remove('playing');
                playBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
            }

            // Reset to beginning
            if (audioElement) {
                audioElement.currentTime = 0;
                updateAudioProgress(audioId);
            }

            currentPlayingAudioId = null;
        }

        // ============ IMAGE VIEWER FUNCTIONALITY ============
        const imageViewerModal = document.getElementById('imageViewerModal');
        const viewerImage = document.getElementById('viewerImage');
        const closeImageViewerBtn = document.getElementById('closeImageViewerBtn');
        const deleteImageBtn = document.getElementById('deleteImageBtn');
        let currentViewingImageData = {
            url: null,
            messageId: null,
            roomId: null
        };

        function openImageViewer(event, imageUrl, messageId, roomId) {
            event.stopPropagation();
            console.log('Opening image viewer for message:', messageId);
            currentViewingImageData = {
                url: imageUrl,
                messageId: messageId,
                roomId: roomId
            };
            viewerImage.src = imageUrl;
            imageViewerModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeImageViewer() {
            console.log('Closing image viewer');
            imageViewerModal.classList.remove('show');
            document.body.style.overflow = 'auto';
            currentViewingImageData = {
                url: null,
                messageId: null,
                roomId: null
            };
        }

        function openImageContextMenu() {
            if (!currentViewingImageData.messageId || !currentViewingImageData.roomId) {
                alert('Error: Could not identify image');
                return;
            }

            // Set the current context message for the delete function
            currentContextMessage = {
                id: currentViewingImageData.messageId,
                roomId: currentViewingImageData.roomId,
                content: 'Image',
                type: 'image'
            };

            // Close the image viewer
            closeImageViewer();

            // Open the delete confirmation modal
            openDeleteConfirmModal();
        }

        // Event listeners for image viewer
        closeImageViewerBtn.addEventListener('click', closeImageViewer);
        deleteImageBtn.addEventListener('click', openImageContextMenu);

        // Close image viewer when clicking outside the image
        imageViewerModal.addEventListener('click', (e) => {
            if (e.target === imageViewerModal) {
                closeImageViewer();
            }
        });

        // Close image viewer with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && imageViewerModal.classList.contains('show')) {
                closeImageViewer();
            }
        });
    </script>
@endsection

