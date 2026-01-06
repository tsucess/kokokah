@extends('layouts.dashboardtemp')

@section('content')
    <style>
        p {
            margin-bottom: 0;
        }

        .feedback-card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .feedback-card {
            border: 1px solid #8D8E8E;
            border-radius: 20px;
            padding: 24px;
        }

        .feedback-card-img {
            width: 47px;
            height: 47px;
            border-radius: 50%;
            object-fit: fill;
            object-position: center;
        }

        .feedback-card-name {
            color: #004A53;
            font-size: 18px;
            font-family: 'Fredoka One';
            font-weight: 500;
        }

        .email {
            color: #737373;
            font-size: 14px;
        }

        .divider {
            width: 100%;
            height: 1px;
            background-color: #8D8E8E;
        }

        .feature-card-title {
            color: #1C1D1D;
            font-size: 16px;
            font-weight: 600px;
            margin-bottom: 0px;
        }

        .feature-type {
            background-color: #E6E8FF;
            padding: 4px 30px;
            border-radius: 20px;
            color: #1C1D1D;
            font-weight: 600;
            font-size: 15px;
        }

        .feature-text {
            color: #1C1D1D;
            font-size: 16px;
        }
    </style>
    <main>
        <div class="container-fluid px-5 py-4">

            <div class="mb-4 d-flex justify-content-between gap-3 align-items-center">
                <div class="d-flex flex-column gap-2">
                    <h4 class="fw-bold">Feedback Details</h4>
                    <p>Your feedback helps us improve your learning experience.</p>
                </div>
                <select class="custom-select" id="filterSelect"
                                >
                                <option value="" style="">Report Bug</option>
                                <option value="course">Request Feature</option>
                                <option value="category">General Feedback</option>
                                <option value="category">We Listen</option>
                            </select>

            </div>

            <div class="feedback-card-container">

                <div class="feedback-card d-flex flex-column align-items-start">
                    <div class="d-flex align-items-center gap-2">
                        <img src='./images/feedback-img.jpg' class="feedback-card-img" />
                        <div class="d-flex flex-column gap-1">
                            <h3 class="feedback-card-name">Winne Harper</h3>
                            <h4 class="email">Winharper@example.com</h4>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-2 align-items-center">
                            <p class="feature-card-title">Feedback Type:</p>
                            <div class="feature-type">Bug Report</div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <p class="feature-card-title">Rating:</p>
                            <div class="d-flex gap-1">
                                <i class="fa-solid fa-star fa-sm" style="color:#FDAF22"></i>
                                <i class="fa-solid fa-star fa-sm" style="color:#FDAF22"></i>
                                <i class="fa-solid fa-star fa-sm" style="color:#FDAF22"></i>
                                <i class="fa-solid fa-star fa-sm" style="color:#E5E6E7"></i>
                                <i class="fa-solid fa-star fa-sm" style="color:#E5E6E7"></i>
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <p class="feature-card-title">Subject:</p>
                            <p class="feature-card-title">Issue with registering for subject</p>
                        </div>
                        <p class="feature-text">Hi, I’m experiencing an issue where I’m unable to register for a subject due
                            to an error message. Please look into this and let me know when it’s fixed. Thank you.</p>
                    </div>
                    <div class="divider"></div>
                    <div class="d-flex align-items-center gap-1">
                        <p>Submitted on:</p>
                        <p>April 24, 2024 at 10:30 AM</p>
                    </div>

                </div>

                <div class="feedback-card d-flex flex-column align-items-start">
                    <div class="d-flex align-items-center gap-2">
                        <img src='./images/feedback-img.jpg' class="feedback-card-img" />
                        <div class="d-flex flex-column gap-1">
                            <h3 class="feedback-card-name">Winne Harper</h3>
                            <h4 class="email">Winharper@example.com</h4>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-2 align-items-center">
                            <p class="feature-card-title">Feedback Type:</p>
                            <div class="feature-type">Bug Report</div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <p class="feature-card-title">Rating:</p>
                            <div class="d-flex gap-1">
                                <i class="fa-solid fa-star fa-sm" style="color:#FDAF22"></i>
                                <i class="fa-solid fa-star fa-sm" style="color:#FDAF22"></i>
                                <i class="fa-solid fa-star fa-sm" style="color:#FDAF22"></i>
                                <i class="fa-solid fa-star fa-sm" style="color:#E5E6E7"></i>
                                <i class="fa-solid fa-star fa-sm" style="color:#E5E6E7"></i>
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <p class="feature-card-title">Subject:</p>
                            <p class="feature-card-title">Issue with registering for subject</p>
                        </div>
                        <p class="feature-text">Hi, I’m experiencing an issue where I’m unable to register for a subject due
                            to an error message. Please look into this and let me know when it’s fixed. Thank you.</p>
                    </div>
                    <div class="divider"></div>
                    <div class="d-flex align-items-center gap-1">
                        <p>Submitted on:</p>
                        <p>April 24, 2024 at 10:30 AM</p>
                    </div>

                </div>

                <div class="feedback-card d-flex flex-column align-items-start">
                    <div class="d-flex align-items-center gap-2">
                        <img src='./images/feedback-img.jpg' class="feedback-card-img" />
                        <div class="d-flex flex-column gap-1">
                            <h3 class="feedback-card-name">Winne Harper</h3>
                            <h4 class="email">Winharper@example.com</h4>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-2 align-items-center">
                            <p class="feature-card-title">Feedback Type:</p>
                            <div class="feature-type">Bug Report</div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <p class="feature-card-title">Rating:</p>
                            <div class="d-flex gap-1">
                                <i class="fa-solid fa-star fa-sm" style="color:#FDAF22"></i>
                                <i class="fa-solid fa-star fa-sm" style="color:#FDAF22"></i>
                                <i class="fa-solid fa-star fa-sm" style="color:#FDAF22"></i>
                                <i class="fa-solid fa-star fa-sm" style="color:#E5E6E7"></i>
                                <i class="fa-solid fa-star fa-sm" style="color:#E5E6E7"></i>
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <p class="feature-card-title">Subject:</p>
                            <p class="feature-card-title">Issue with registering for subject</p>
                        </div>
                        <p class="feature-text">Hi, I’m experiencing an issue where I’m unable to register for a subject due
                            to an error message. Please look into this and let me know when it’s fixed. Thank you.</p>
                    </div>
                    <div class="divider"></div>
                    <div class="d-flex align-items-center gap-1">
                        <p>Submitted on:</p>
                        <p>April 24, 2024 at 10:30 AM</p>
                    </div>

                </div>

                <div class="feedback-card d-flex flex-column align-items-start">
                    <div class="d-flex align-items-center gap-2">
                        <img src='./images/feedback-img.jpg' class="feedback-card-img" />
                        <div class="d-flex flex-column gap-1">
                            <h3 class="feedback-card-name">Winne Harper</h3>
                            <h4 class="email">Winharper@example.com</h4>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-2 align-items-center">
                            <p class="feature-card-title">Feedback Type:</p>
                            <div class="feature-type">Bug Report</div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <p class="feature-card-title">Rating:</p>
                            <div class="d-flex gap-1">
                                <i class="fa-solid fa-star fa-sm" style="color:#FDAF22"></i>
                                <i class="fa-solid fa-star fa-sm" style="color:#FDAF22"></i>
                                <i class="fa-solid fa-star fa-sm" style="color:#FDAF22"></i>
                                <i class="fa-solid fa-star fa-sm" style="color:#E5E6E7"></i>
                                <i class="fa-solid fa-star fa-sm" style="color:#E5E6E7"></i>
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <p class="feature-card-title">Subject:</p>
                            <p class="feature-card-title">Issue with registering for subject</p>
                        </div>
                        <p class="feature-text">Hi, I’m experiencing an issue where I’m unable to register for a subject
                            due to an error message. Please look into this and let me know when it’s fixed. Thank you.</p>
                    </div>
                    <div class="divider"></div>
                    <div class="d-flex align-items-center gap-1">
                        <p>Submitted on:</p>
                        <p>April 24, 2024 at 10:30 AM</p>
                    </div>

                </div>
            </div>

        </div>
    </main>
@endsection
