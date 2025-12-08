@extends('layouts.dashboardtemp')
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap');

        .add-user-main {
            background-color: #ffffff;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .line-divider{
            background-color: #BFBFBF;
            width: 100%;
            height: 1px;
            margin-bottom: 24px;
        }
        .modal-label{
            background-color: #f9f9f9;
        }

        /* Cropper button hover effects */
        #rotateLeftBtn:hover,
        #rotateRightBtn:hover,
        #resetCropBtn:hover {
            background-color: #004A53 !important;
            color: white !important;
        }

        /* Cropper Modal Styles */
        .modal-dialog {
            margin: auto !important;
        }

        .modal-header {
            background-color: #f9f9f9;
        }

        .modal-header .modal-title {
            color: #004A53;
            font-weight: 600;
        }

        .modal-body {
            height: 360px;
            overflow-y: auto;
            padding: 0.75rem;
        }

        .image-div {
            max-width: 100%;
            height: 250px;
        }

        #cropperImage {
            max-width: 100%;
            max-height: 500px;
        }

        .zoom-container {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .zoom-label {
            font-size: 0.9rem;
            color: #666;
            min-width: 50px;
            margin-bottom: 0;
        }

        #zoomRange {
            flex: 1;
        }

        .controls-container {
            display: flex;
            justify-content: center;
            gap: 0.25rem;
        }

        #rotateLeftBtn,
        #rotateRightBtn,
        #resetCropBtn {
            background-color: white;
            border: 1px solid #004A53;
            color: #004A53;
            font-size: 0.8rem;
            padding: 0.15rem 0.8rem;
            line-height: 1;
        }

        .modal-footer {
            background-color: #f9f9f9;
        }

        #cropperSave {
            background-color: #FDAF22;
            border: none;
            color: white;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .form-label-custom {
            font-size: 0.8rem;
            font-weight: 600;
            color: #000000;
            display: block;
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.3px;
            margin-bottom: 0;
        }

        .form-input-custom {
            padding: 0.875rem 1.25rem;
            font-size: 0.95rem;
            border: 2px solid #004A53;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            background-color: white;
            color: #333;
        }

        .form-input-custom::placeholder {
            color: #999;
        }

        .form-input-custom:focus {
            border-color: #004A53;
            box-shadow: 0 0 0 0.2rem rgba(0, 74, 83, 0.15);
            background-color: white;
            color: #333;
        }

        .form-input-custom:hover {
            border-color: #004A53;
        }

        .form-check-input {
            border: 2px solid #004A53;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #004A53;
            border-color: #004A53;
        }

        .form-check-input:focus {
            border-color: #004A53;
            box-shadow: 0 0 0 0.2rem rgba(0, 74, 83, 0.25);
        }

        .password-input-wrapper {
            position: relative;
        }

        .password-input-wrapper .btn-link {
            border: none;
            padding: 0.5rem 1rem;
            background: none;
        }

        .password-input-wrapper .btn-link:hover {
            background: none;
        }

        #uploadArea {
            transition: all 0.3s ease;
        }

        #uploadArea:hover {
            background-color: #f0f8f9 !important;
            border-color: #004A53 !important;
        }

        .card {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }
        .modal-form-input-border {
                    padding: 0px 15px 15px;
                }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            h1 {
                font-size: 1.75rem !important;
            }

            .d-flex.justify-content-between {
                flex-direction: column !important;
                gap: 1rem !important;
            }

            .d-flex.gap-3 {
                flex-wrap: wrap !important;
                gap: 0.5rem !important;
            }

            .btn {
                font-size: 0.85rem !important;
                padding: 0.5rem 1rem !important;
            }

            .row.g-4 {
                gap: 1.5rem !important;
            }

            .col-lg-8,
            .col-lg-4 {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }

            .card-body {
                padding: 1.5rem !important;
            }

            .form-label-custom {
                font-size: 0.85rem !important;
            }

            .form-input-custom {
                padding: 0.7rem 1rem !important;
                font-size: 0.9rem !important;
            }

            .modal-body {
                height: 320px !important;
                padding: 0.75rem !important;
            }

            .modal-body>.image-div {
                height: 200px !important;
            }

            #cropperImage {
                max-height: 400px !important;
            }

            .d-flex.flex-wrap.gap-2 {
                flex-direction: row !important;
                gap: 0.3rem !important;
                justify-content: center !important;
            }

            .d-flex.flex-wrap.gap-2>div {
                width: 100% !important;
                margin-bottom: 0.5rem !important;
            }

            .d-flex.flex-wrap.gap-2>button {
                flex: 1 1 auto !important;
                min-width: 40px !important;
                padding: 0.3rem 0.4rem !important;
            }

            .modal-footer {
                flex-wrap: wrap !important;
                gap: 0.5rem !important;
            }

            .modal-footer button {
                /* flex: 1 1 calc(50% - 0.25rem) !important; */
                font-size: 0.75rem !important;
                padding: 0.4rem 0.5rem !important;
            }
        }

        @media (max-width: 576px) {
            .container-fluid {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }

            h1 {
                font-size: 1.5rem !important;
            }

            .btn {
                font-size: 0.75rem !important;
                padding: 0.4rem 0.75rem !important;
            }

            .card-body {
                padding: 1rem !important;
            }

            .form-label-custom {
                font-size: 0.8rem !important;
            }

            .form-input-custom {
                padding: 0.6rem 0.9rem !important;
                font-size: 0.85rem !important;
            }

            .modal-body {
                height: 300px !important;
                padding: 0.5rem !important;
            }

            .modal-body>.image-div {
                height: 200px !important;
            }

            #cropperImage {
                max-height: 350px !important;
            }

            .modal-footer button {
                /* flex: 1 1 50% !important; */
                font-size: 0.7rem !important;
                padding: 0.35rem 0.4rem !important;
            }

            .d-flex.gap-5 {
                gap: 1rem !important;
            }
        }
    </style>
    <main class="add-user-main">
        <div class="container-fluid px-5 py-4">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h1 class="fw-bold mb-2"
                        style=" color: #004A53; font-family: 'Fredoka One', sans-serif;">My Profile</h1>
                    <p class="text-muted" style="font-size: 0.95rem;">Here overview of your</p>
                </div>
                <div class="d-flex gap-3">
                    <button type="button" class="btn btn-light px-4 py-2" id="cancelBtn"
                        style="border: 1px solid #ddd; color: #333; font-weight: 500;">cancel</button>
                    <button type="button" class="btn px-4 py-2 fw-semibold" id="saveBtn"
                        style="background-color: #FDAF22; border: none; color: white;">Update</button>
                </div>
            </div>

            <!-- Alert Container -->
            <div id="alertContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
            </div>

            <div class="row g-4">
                <!-- Left Column - Form Sections -->
                <div class="col-lg-8">
                    <!-- Basic Information Section -->
                    <div class="card border-0 shadow-sm rounded-4 mb-5"
                        style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                        <div class="card-body p-4 d-flex flex-column gap-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1a1a1a;">Basic Information</h5>
                                <span class="text-danger ms-2" style="font-size: 1.2rem;">*</span>
                            </div>

                            <form id="createUserForm" class="d-flex flex-column gap-3">
                                @csrf
                                <div class="line-divider"></div>

                                <!-- First Name and Last Name Row -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="modal-form-input-border">
                                            <label class="modal-label">Enter First Name</label>
                                            <input type="text" class="modal-input" id="firstName"
                                                name="first_name" placeholder="Winner" required>
                                                </div>
                                            <small class="text-danger d-none" id="firstNameError"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="modal-form-input-border">
                                            <label class="modal-label">Enter Last Name</label>
                                            <input type="text" class="modal-input" id="lastName"
                                                name="last_name" placeholder="Winner" required>
                                                </div>
                                            <small class="text-danger d-none" id="lastNameError"></small>
                                        </div>
                                    </div>
                                </div>




                                <!-- Date of Birth Row -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <!-- Gender Row -->
                                        <div class="mb-5 d-flex flex-column gap-2">
                                            <label class="form-label form-label-custom">Gender</label>
                                            <div class="d-flex gap-5">
                                                <div class="form-check d-flex align-items-center gap-3">
                                                    <input class="form-check-input" type="radio" name="gender"
                                                        id="genderMale" value="male" checked
                                                        style="width: 1rem; height: 1rem; cursor: pointer;">
                                                    <label class="form-check-label" for="genderMale"
                                                        style="cursor: pointer;  color: #000000; font-weight: 500; font-size:1rem;">Male</label>
                                                </div>
                                                <div class="form-check d-flex align-items-center gap-3">
                                                    <input class="form-check-input" type="radio" name="gender"
                                                        id="genderFemale" value="female"
                                                        style="width: 1rem; height: 1rem; cursor: pointer;">
                                                    <label class="form-check-label" for="genderFemale"
                                                        style="cursor: pointer;  color: #000000; font-weight: 500; font-size:1rem;">Female</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="modal-form-input-border">
                                            <label class="modal-label">Enter Date of Birth</label>
                                            <input type="date" class="modal-input" id="dateOfBirth"
                                                name="date_of_birth" placeholder="DD/MM/YYYY">
                                                </div>
                                            <small class="text-danger d-none" id="dobError"></small>
                                        </div>
                                    </div>
                                    <!-- Profile Photo Upload Area -->
                                    <div class="col-md-6 ">
                                        <div class="border-2  rounded-4 px-5 py-4 text-center"
                                            style="border-color: #8E8E8E; border-style:dashed; cursor: pointer; background: white; transition: all 0.3s ease;"
                                            id="uploadArea">
                                            <div class="mb-3">
                                                <i class="fa-solid fa-file-lines fa-lg" style="color: #004A53;"></i>
                                            </div>
                                            <p class="fw-semibold mb-2" style="color: #000000; font-size: 14px;">Drop your
                                                files to upload</p>
                                            <small style="color: #000000; font-size:12px; padding:3px 20px; border: 1px solid #C4C4C4; border-radius:34px; ">Edit</small>
                                            <input type="file" id="profilePhoto" name="profile_photo" class="d-none"
                                                accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>



                    <!-- Parent Details Section -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4"
                        style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                        <div class="card-body p-4 d-flex flex-column gap-4">
                            <h5 class="fw-bold " style="font-size: 1.1rem; color: #1a1a1a;">Parent Details</h5>
                            <div class="line-divider"></div>

                            <form id="parentForm" class=" d-flex flex-column gap-3">
                                <!-- Parent First Name and Last Name Row -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="modal-form-input-border">
                                            <label class="modal-label">Enter First Name</label>
                                            <input type="text" class="modal-input"
                                                id="parentFirstName" name="parent_first_name" placeholder="Winner">
                                                </div>
                                            <small class="text-danger d-none" id="parentFirstNameError"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="modal-form-input-border">
                                            <label class="modal-label">Enter Last Name</label>
                                            <input type="text" class="modal-input"
                                                id="parentLastName" name="parent_last_name" placeholder="Winner">
                                            </div>
                                            <small class="text-danger d-none" id="parentLastNameError"></small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Parent Email and Phone Row -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="modal-form-input-border">
                                            <label class="modal-label">Enter Parent Email Address</label>
                                            <input type="email" class="modal-input" id="parentEmail"
                                                name="parent_email" placeholder="Winner">
                                            </div>
                                            <small class="text-danger d-none" id="parentEmailError"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="modal-form-input-border">
                                            <label class="modal-label">Enter Parent Phone Number</label>
                                            <input type="tel" class="modal-input" id="parentPhone"
                                                name="parent_phone" placeholder="Winner">
                                            </div>
                                            <small class="text-danger d-none" id="parentPhoneError"></small>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Profile Photo and Login Details -->
                <div class="col-lg-4">
                    <!-- Profile Photo Section -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4"
                        style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                        <div class="card-body p-4">
                            <div class="text-center">
                                <div class="mb-4">
                                    <img id="profilePreview" src="images/winner-round.png" alt="Profile"
                                        class=""
                                        style="width: 100%; max-width: 280px; height: auto; object-fit: cover; border-radius:50%;">
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- Image Cropper Modal - Bootstrap -->
    <div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="cropperModalLabel">Crop Profile Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-2 image-div">
                        <img id="cropperImage" src="" alt="Crop Image">
                    </div>
                    <div class="zoom-container mb-2">
                        <label for="zoomRange" class="form-label zoom-label">Zoom:</label>
                        <input type="range" id="zoomRange" class="form-range" min="0.1" max="3"
                            step="0.1" value="1">
                    </div>
                    <div class="controls-container">
                        <button type="button" class="btn" id="rotateLeftBtn">
                            <i class="fa-solid fa-rotate-left"></i> <span class="d-none d-md-inline">Rotate Left</span>
                        </button>
                        <button type="button" class="btn" id="rotateRightBtn">
                            <i class="fa-solid fa-rotate-right"></i> <span class="d-none d-md-inline">Rotate Right</span>
                        </button>
                        <button type="button" class="btn" id="resetCropBtn">
                            <i class="fa-solid fa-arrows-rotate"></i> <span class="d-none d-md-inline">Reset</span>
                        </button>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm" id="cropperSave">Crop & Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection
