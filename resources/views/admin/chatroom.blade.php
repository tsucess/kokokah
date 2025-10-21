{{-- @extends('admin.usertemplate') --}}
@extends('users.usertemplate')

@section('content')
<main>
<div class="container-fluid py-4">
    <div class="row g-0 chat-app-container">

        <div class="col-lg-4 sidebar-left d-none d-lg-block">

            <div class="input-group mb-4">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control border-start-0 ps-0" placeholder="Find a Conversation">
            </div>

            <h6 class="text-muted text-uppercase small mb-2">Conservation</h6>

            <a href="#" class="sidebar-item active">
                <div class="d-flex align-items-center">
                    <span class="badge bg-white me-2" style = "color: var(--bs-dark-teal); border-radius:20px; width: 25px;">
                    <i class="bi bi-hash me-2"></i>
                    </span>
                    General
                </div>
            </a>

            <a href="#" class="sidebar-item">
                <div class="d-flex align-items-center">
                    <span class="badge me-2" style = "background: #114243; border-radius:20px; width: 25px;">
                    <i class="bi bi-hash me-2 text-white"></i>
                    </span>
                    Mathematics Help Corner
                </div>
                <span class="new-message-badge">1</span>
            </a>

            <a href="#" class="sidebar-item">
                <div class="d-flex align-items-center">
                    <span class="badge me-2" style = "background: #114243; border-radius:20px; width: 25px;">
                    <i class="bi bi-hash me-2 text-white"></i>
                    </span>
                    Science Discussions
                </div>
                <span class="new-message-badge">1</span>
            </a>

            <a href="#" class="sidebar-item">
                <div class="d-flex align-items-center">
                    <span class="badge me-2" style = "background: #114243; border-radius:20px; width: 25px;">
                    <i class="bi bi-hash me-2 text-white"></i>
                    </span>
                    English Literature & Writing
                </div>
            </a>

            <a href="#" class="sidebar-item">
                <div class="d-flex align-items-center">
                    <span class="badge me-2" style = "background: #114243; border-radius:20px; width: 25px;">
                    <i class="bi bi-hash me-2 text-white"></i>
                    </span>
                     History & Social Studies
                </div>
                <span class="new-message-badge">1</span>
            </a>

            <a href="#" class="sidebar-item">
                <div class="d-flex align-items-center">
                    <span class="badge me-2" style = "background: #114243; border-radius:20px; width: 25px;">
                    <i class="bi bi-hash me-2 text-white"></i>
                    </span>
                    ICT & Programming Chat
                </div>
            </a>

            <a href="#" class="sidebar-item">
                <div class="d-flex align-items-center">
                    <span class="badge me-2" style = "background: #114243; border-radius:20px; width: 25px;">
                    <i class="bi bi-hash me-2 text-white"></i>
                    </span>
                    Foreign Language Practice
                </div>
            </a>

        </div>

        <div class="col-12 col-lg-8 chat-panel-right">

            <div class="chat-header">
                #General
            </div>

            <div class="chat-history">

                <div class="welcome-message mb-5">
                    <div class="welcome-icon mx-auto mb-3">
                        <i class="bi bi-hash text-white"></i>
                    </div>
                    <h4 class="fw-bold mb-1" style="color: var(--color-primary-button: #004A53);">Welcome to #General</h4>
                    <p class="text-muted">This is the start of the #General Channel</p>
                </div>

                <div class="chat-message">
                    <img src="images/Lisa.png" alt="Avatar" class="message-avatar">
                    <div class="message-content">
                        <span class="message-user">Emery Schleifer</span>
                        <span class="message-timestamp">5m</span>
                        <p class="mb-1">You can't input the pixel without programming the open-source SMTP feed! You can't input the pixel without programming the open-source SMTP feed!</p>
                        <p class="mb-1">You can't input the pixel without programming the open-source SMTP feed!</p>
                    </div>
                </div>

                <div class="chat-message">
                    <img src="images/Lisa.png" alt="Avatar" class="message-avatar">
                    <div class="message-content">
                        <span class="message-user">Emery Schleifer</span>
                        <span class="message-timestamp">5m</span>
                        <p class="mb-1">You can't input the pixel without programming the open-source SMTP feed!</p>
                        <p class="mb-1">You can't input the pixel without programming the open-source SMTP feed!</p>
                    </div>
                </div>

                </div>

            <div class="chat-input-area">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 d-flex align-items-center border rounded-pill py-2 px-3 me-3" style="border-color: var(--bs-light-gray) !important;">
                        <i class="bi bi-paperclip me-2 text-muted"></i>
                        <input type="text" class="form-control border-0 p-0 shadow-none" placeholder="Message to koodi..." style="height: auto;">
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
@endsection
