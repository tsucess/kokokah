@extends('layouts.usertemplate')
@section('content')
    <style>
        body {
            background-color: #f9fafb;
        }

        .card {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
            background: #f9f9f9;
            border: 1px solid #e8e8e8;
            padding: 20px;
        }

        .line-divider {
            background-color: #BFBFBF;
            width: 100%;
            height: 1px;
            margin-bottom: 24px;
        }

        .profile-card {
            border: 1px solid #eee;
            border-radius: 15px;
            padding: 25px;
            background: white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .line-divider {
            background-color: #BFBFBF;
            width: 100%;
            height: 1px;
            margin-bottom: 24px;
        }

        .modal-label {
            background-color: #f9f9f9;
        }

        <style>@import url('https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap');

        .add-user-main {
            background-color: #ffffff;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .line-divider {
            background-color: #BFBFBF;
            width: 100%;
            height: 1px;
            margin-bottom: 24px;
        }

        .modal-label {
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

        .save-btn {
            background-color: #ffb100;
            border: none;
            padding: 12px 30px;
            border-radius: 4px;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            color: #000F11;
            transition: background-color 0.3s;
            margin-top: 300px;
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

        .fw-bold.text-primary {
            color: #0d6efd !important;
        }

        .account-deletion-title {
            color: #000000;
            font-size: 24px;
            font-weight: 600;
        }

        .account-deletion-text {
            color: #666666;
            font-size: 16px;
        }

        .account-deletion-btn {
            border: 1px solid #F56824;
            padding: 16px 20px;
            background-color: transparent;
            color: #F56824;
            font-size: 16px;
            font-weight: 600;
        }

        .zoom-label {
            font-size: 0.9rem;
            color: #666;
            min-width: 50px;
            margin-bottom: 0;
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
            height: 2.5rem;
        }

        #cropperSave {
            background-color: #FDAF22;
            border: none;
            color: white;
            font-weight: 500;
            font-size: 0.85rem;
        }
    </style>

    <main>
        <div class="container my-5">
            <h1 data-i18n="profile.my_profile">My Profile</h1>

            <!-- Tabs -->
            <ul class="nav nav-tabs mt-3" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details"
                        type="button" role="tab" data-i18n="profile.my_details">My details</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button"
                        role="tab" data-i18n="profile.login">Login</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button"
                        role="tab" data-i18n="profile.settings">Settings</button>
                </li>
                {{-- <li class="nav-item" role="presentation">
                    <button class="nav-link" id="wallet-tab" data-bs-toggle="tab" data-bs-target="#wallet" type="button" role="tab">Wallet</button>
                    </li> --}}
            </ul>

            <div class="tab-content mt-4" id='profileTabsContent'>
                <!-- My Details Tab -->
                <div class="tab-pane fade show active" id="details" role="tabpanel">
                    <div class="row g-4">
                        <!-- Left Column - Form Sections -->
                        <div class="col-lg-8">
                            <!-- Basic Information Section -->
                            <div class="card border-0 shadow-sm rounded-4 mb-5"
                                style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                                <div class="card-body p-4 d-flex flex-column gap-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1a1a1a;" data-i18n="profile.basic_information">Basic
                                            Information</h5>
                                        <span class="text-danger ms-2" style="font-size: 1.2rem;">*</span>
                                    </div>

                                    <form id="createUserForm" class="d-flex flex-column gap-3">
                                        @csrf
                                        <div class="line-divider"></div>

                                        <!-- First Name and Last Name Row -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="modal-form-input-border">
                                                        <label class="modal-label" data-i18n="profile.first_name">First Name</label>
                                                        <input type="text" class="modal-input" id="firstName"
                                                            name="first_name" placeholder="Winner" required>
                                                    </div>
                                                    <small class="text-danger d-none" id="firstNameError"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="modal-form-input-border">
                                                        <label class="modal-label" data-i18n="profile.last_name">Last Name</label>
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
                                                <div class="mb-4 d-flex flex-column gap-2">
                                                    <label class="form-label form-label-custom" data-i18n="profile.gender">Gender</label>
                                                    <div class="d-flex gap-4 mb-1">
                                                        <div class="form-check d-flex align-items-center gap-2">
                                                            <input class="form-check-input" type="radio" name="gender"
                                                                id="genderMale" value="male" checked
                                                                style="width: 1rem; height: 1rem; cursor: pointer;">
                                                            <label class="form-check-label" for="genderMale"
                                                                style="cursor: pointer;  color: #000000; font-weight: 500; font-size:1rem;" data-i18n="profile.male">Male</label>
                                                        </div>
                                                        <div class="form-check d-flex align-items-center gap-2">
                                                            <input class="form-check-input" type="radio" name="gender"
                                                                id="genderFemale" value="female"
                                                                style="width: 1rem; height: 1rem; cursor: pointer;">
                                                            <label class="form-check-label" for="genderFemale"
                                                                style="cursor: pointer;  color: #000000; font-weight: 500; font-size:1rem;" data-i18n="profile.female">Female</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="modal-form-input-border">
                                                        <label class="modal-label" data-i18n="profile.date_of_birth">Date of Birth</label>
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
                                                        <i class="fa-solid fa-file-lines fa-lg"
                                                            style="color: #004A53;"></i>
                                                    </div>
                                                    <p class="fw-semibold mb-2" style="color: #000000; font-size: 14px;" data-i18n="profile.drop_files_to_upload">
                                                        Drop your
                                                        files to upload</p>
                                                    <small
                                                        style="color: #000000; font-size:12px; padding:3px 20px; border: 1px solid #C4C4C4; border-radius:34px; " data-i18n="profile.edit">Edit</small>
                                                    <input type="file" id="profilePhoto" name="profile_photo"
                                                        class="d-none" accept="image/*">
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
                                    <h5 class="fw-bold " style="font-size: 1.1rem; color: #1a1a1a;" data-i18n="profile.parent_details">Parent Details</h5>
                                    <div class="line-divider"></div>

                                    <form id="parentForm" class=" d-flex flex-column gap-3">
                                        <!-- Parent First Name and Last Name Row -->
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="modal-form-input-border">
                                                        <label class="modal-label" data-i18n="profile.parent_first_name">First Name</label>
                                                        <input type="text" class="modal-input" id="parentFirstName"
                                                            name="parent_first_name" placeholder="Enter First Name">
                                                    </div>
                                                    <small class="text-danger d-none" id="parentFirstNameError"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="modal-form-input-border">
                                                        <label class="modal-label" data-i18n="profile.parent_last_name">Last Name</label>
                                                        <input type="text" class="modal-input" id="parentLastName"
                                                            name="parent_last_name" placeholder="Enter Last Name">
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
                                                        <label class="modal-label" data-i18n="profile.parent_email_address">Parent Email Address</label>
                                                        <input type="email" class="modal-input" id="parentEmail"
                                                            name="parent_email" placeholder="Enter Parent Email Address">
                                                    </div>
                                                    <small class="text-danger d-none" id="parentEmailError"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="modal-form-input-border">
                                                        <label class="modal-label" data-i18n="profile.parent_phone_number">Parent Phone Number</label>
                                                        <input type="tel" class="modal-input" id="parentPhone"
                                                            name="parent_phone" placeholder="Enter Parent Phone Number">
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
                                            <img id="profilePreview" src="" alt="Profile" class=""
                                                style="width: 100%; max-width: 280px; height: auto; object-fit: cover; border-radius:50%;">
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <button class="save-btn">Save</button>

                        </div>
                    </div>
                </div>

                <!-- login Tab -->
                <div class="tab-pane fade" id="login" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-8">
                            <div class="card d-flex flex-column gap-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #000;" data-i18n="profile.login">Login/Account Details
                                    </h5>
                                    <span class="text-danger ms-2" style="font-size: 1.2rem;">*</span>
                                </div>
                                <div class="line-divider"></div>
                                <form class='d-flex flex-column gap-4'>
                                    <div class="form-group">
                                        <div class="modal-form-input-border">
                                            <label class="modal-label" data-i18n="profile.email_address">Email Address</label>
                                            <input type="email" class="modal-input" id="email" name="email"
                                                placeholder="@gmail.com" readonly>
                                        </div>
                                        <small class="text-danger d-none" id="emailError"></small>
                                    </div>
                                    <div class="form-group">
                                        <div class="modal-form-input-border">
                                            <label class="modal-label" data-i18n="profile.current_password">Current Password</label>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <input type="password" class="modal-input" id="currentPassword"
                                                    name="password" placeholder="••••••••" required>
                                                <button type="button"
                                                    class="btn btn-link position-absolute end-0 top-50 translate-middle-y"
                                                    id="toggleCurrentPassword"
                                                    style="border: none; padding: 0.5rem 1rem;">
                                                    <i class="fa-solid fa-eye" style="color: #999;"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="text-danger d-none" id="passwordError"></small>
                                    </div>
                                    <div class="form-group">
                                        <div class="modal-form-input-border">
                                            <label class="modal-label" data-i18n="profile.new_password">New Password</label>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <input type="password" class="modal-input" id="newPassword"
                                                    name="password" placeholder="••••••••" required>
                                                <button type="button"
                                                    class="btn btn-link position-absolute end-0 top-50 translate-middle-y"
                                                    id="toggleNewPassword" style="border: none; padding: 0.5rem 1rem;">
                                                    <i class="fa-solid fa-eye" style="color: #999;"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="text-danger d-none" id="passwordError"></small>
                                    </div>
                                    <div class="form-group">
                                        <div class="modal-form-input-border">
                                            <label class="modal-label" data-i18n="profile.confirm_password">Confirm Password</label>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <input type="password" class="modal-input" id="confirmPassword"
                                                    name="password" placeholder="••••••••" required>
                                                <button type="button"
                                                    class="btn btn-link position-absolute end-0 top-50 translate-middle-y"
                                                    id="toggleConfirmPassword"
                                                    style="border: none; padding: 0.5rem 1rem;">
                                                    <i class="fa-solid fa-eye" style="color: #999;"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="text-danger d-none" id="passwordError"></small>
                                    </div>
                                    <div class="form-group">
                                        <div class="modal-form-input-border">
                                            <label class="modal-label" data-i18n="profile.role">Role</label>
                                            <input type="text" class="modal-input" id="role" name="role"
                                                readonly>
                                        </div>
                                        <small class="text-danger d-none" id="roleError"></small>
                                    </div>
                                    <button class="save-btn mt-0" data-i18n="profile.change_password">Change Password</button>
                                </form>
                            </div>

                        </div>
                        <div class="col-4">
                            <div class="card d-flex flex-column gap-4" style="background-color: #CCDBDD;">
                                <h3 class="account-deletion-title" data-i18n="profile.account_deletion">Account Deleting</h3>
                                <p class="account-deletion-text" data-i18n="profile.account_deletion_warning">Once your account is deleted, you will lose access to it
                                    and all your data. Information from your account is not duplicated and after deleting,
                                    your account will be lost forever without the possibility of recovery.</p>
                                <button class="account-deletion-btn" data-i18n="profile.delete_account">Delete Account</button>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Tab -->
                <div class="tab-pane fade" id="settings" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-8">
                            <div class="card d-flex flex-column gap-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #000;" data-i18n="profile.settings">Settings</h5>
                                    <span class="text-danger ms-2" style="font-size: 1.2rem;">*</span>
                                </div>
                                <div class="line-divider"></div>
                                <form id="settingsForm" class='d-flex flex-column gap-4'>
                                    @csrf
                                    <!-- Language Selection -->
                                    <div class="form-group">
                                        <div class="modal-form-input-border">
                                            <label class="modal-label" data-i18n="profile.language_preference">Language Preference</label>
                                            <select class="modal-input" id="languagePreference" name="language_preference" required>
                                                <option value="" data-i18n="profile.select_language">Select a language</option>
                                                <option value="en">English</option>
                                                <option value="fr">Français (French)</option>
                                                <option value="ar">العربية (Arabic)</option>
                                                <option value="yo">Yorùbá (Yoruba)</option>
                                                <option value="ha">Hausa</option>
                                                <option value="ig">Igbo</option>
                                            </select>
                                        </div>
                                        <small class="text-danger d-none" id="languageError"></small>
                                    </div>
                                    <button type="button" class="save-btn mt-0" id="saveSettingsBtn" data-i18n="profile.save_settings">Save Settings</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card d-flex flex-column gap-4" style="background-color: #E8F4F8;">
                                <h3 class="account-deletion-title" data-i18n="profile.language_settings">Language Settings</h3>
                                <p class="account-deletion-text" data-i18n="profile.language_settings_description">Choose your preferred language for the application interface. Your selection will be saved to your profile.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Cropper Modal - Bootstrap -->
                <div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-md">
                        <div class="modal-content">
                            <div class="modal-header border-bottom">
                                <h5 class="modal-title" id="cropperModalLabel" data-i18n="profile.crop_profile_photo">Crop Profile Photo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center mb-2 image-div">
                                    <img id="cropperImage" src="" alt="Crop Image">
                                </div>
                                <div class="zoom-container mb-2">
                                    <label for="zoomRange" class="form-label zoom-label">Zoom:</label>
                                    <input type="range" id="zoomRange" class="form-range" min="0.1"
                                        max="3" step="0.1" value="1">
                                </div>
                                <div class="controls-container">
                                    <button type="button" class="btn" id="rotateLeftBtn" data-i18n="profile.rotate_left">
                                        <i class="fa-solid fa-rotate-left"></i> <span class="d-none d-md-inline" data-i18n="profile.rotate_left">Rotate
                                            Left</span>
                                    </button>
                                    <button type="button" class="btn" id="rotateRightBtn" data-i18n="profile.rotate_right">
                                        <i class="fa-solid fa-rotate-right"></i> <span class="d-none d-md-inline" data-i18n="profile.rotate_right">Rotate
                                            Right</span>
                                    </button>
                                    <button type="button" class="btn" id="resetCropBtn" data-i18n="profile.reset">
                                        <i class="fa-solid fa-arrows-rotate"></i> <span
                                            class="d-none d-md-inline" data-i18n="profile.reset">Reset</span>
                                    </button>
                                </div>
                            </div>
                            <div class="modal-footer border-top">
                                <button type="button" class="btn btn-sm btn-secondary"
                                    data-bs-dismiss="modal" data-i18n="profile.cancel">Cancel</button>
                                <button type="button" class="btn btn-sm" id="cropperSave" data-i18n="profile.crop_and_save">Crop & Save</button>
                            </div>
                        </div>
                    </div>
                </div>



                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

                    <!-- API Clients -->
    <script>
// Load profile data on page load
                    document.addEventListener('DOMContentLoaded', async () => {
                        console.log('Profile page loaded, fetching user data...');

                        await loadProfileData();
                        setupEventListeners();
                        restoreActiveTab();
                    });

                    // Load profile data from API
                    async function loadProfileData() {
                        try {
                            console.log('Fetching profile data from API...');
                            console.log('Token:', localStorage.getItem('auth_token') ? 'Present' : 'Missing');

                            const response = await UserApiClient.getProfile();

                            if (response.success && response.data) {
                                const user = response.data;

                                // Populate basic information
                                const firstNameField = document.getElementById('firstName');
                                const lastNameField = document.getElementById('lastName');
                                const dobField = document.getElementById('dateOfBirth');

                                if (firstNameField) {
                                    firstNameField.value = user.first_name || '';
                                }
                                if (lastNameField) {
                                    lastNameField.value = user.last_name || '';
                                }
                                if (dobField && user.date_of_birth) {
                                    // Format date for date input (convert ISO 8601 to yyyy-MM-dd)
                                    const dateObj = new Date(user.date_of_birth);
                                    const formattedDate = dateObj.toISOString().split('T')[0];
                                    dobField.value = formattedDate;
                                }

                                // Set gender
                                if (user.gender) {
                                    const genderInput = document.querySelector(`input[name="gender"][value="${user.gender}"]`);
                                    if (genderInput) {
                                        genderInput.checked = true;
                                    }
                                }

                                // Populate parent details
                                const parentFirstField = document.getElementById('parentFirstName');
                                const parentLastField = document.getElementById('parentLastName');
                                const parentEmailField = document.getElementById('parentEmail');
                                const parentPhoneField = document.getElementById('parentPhone');

                                if (parentFirstField) {
                                    parentFirstField.value = user.parent_first_name || '';
                                }
                                if (parentLastField) {
                                    parentLastField.value = user.parent_last_name || '';
                                }
                                if (parentEmailField) {
                                    parentEmailField.value = user.parent_email || '';
                                }
                                if (parentPhoneField) {
                                    parentPhoneField.value = user.parent_phone || '';
                                }

                                // Set profile photo
                                if (user.profile_photo) {
                                    const photoPreview = document.getElementById('profilePreview');
                                    if (photoPreview) {
                                        photoPreview.src = user.profile_photo;
                                    }
                                }

                                // Populate login details
                                const emailField = document.getElementById('email');
                                if (emailField) {
                                    emailField.value = user.email || '';
                                }

                                // Populate role (readonly)
                                const roleField = document.getElementById('role');
                                if (roleField && user.role) {
                                    const roleText = user.role.charAt(0).toUpperCase() + user.role.slice(1);
                                    roleField.value = roleText;
                                }
                            } else if (response.data) {
                                // If response has data but success is false, still display it
                                const user = response.data;

                                // Populate basic information
                                const firstNameField = document.getElementById('firstName');
                                const lastNameField = document.getElementById('lastName');

                                if (firstNameField) {
                                    firstNameField.value = user.first_name || '';
                                }
                                if (lastNameField) {
                                    lastNameField.value = user.last_name || '';
                                }

                                console.log('Profile data loaded (success=false):', response.data);
                            } else {
                                console.error('❌ Failed to fetch profile:', response);
                                const errorMsg = response.message || response.error || 'Failed to load profile data';
                                ToastNotification.error(errorMsg);
                            }
                        } catch (error) {
                            console.error('❌ Error loading profile:', error);

                            // Check if it's a 401 error (unauthorized)
                            if (error.response?.status === 401 || error.status === 401) {
                                console.log('User not authenticated, redirecting to login...');
                                ToastNotification.error('Please log in to view your profile');
                                setTimeout(() => {
                                    window.location.href = '/login';
                                }, 2000);
                                return;
                            }

                            ToastNotification.error('Failed to load profile data. Please try again.');
                        }
                    }

                    // Cropper variables
                    let cropper = null;
                    let originalImageData = null;
                    let currentRotation = 0;
                    let cropperModalInstance = null;

                    // Initialize cropper modal
                    const cropperModalElement = document.getElementById('cropperModal');
                    if (cropperModalElement) {
                        cropperModalInstance = new bootstrap.Modal(cropperModalElement);
                    }

                    // Cropper helper functions
                    function handleFileSelect() {
                        const profilePhoto = document.getElementById('profilePhoto');
                        const file = profilePhoto.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                originalImageData = e.target.result;
                                openCropperModal(e.target.result);
                            };
                            reader.readAsDataURL(file);
                        }
                    }

                    function openCropperModal(imageSrc) {
                        const cropperImage = document.getElementById('cropperImage');
                        const zoomRange = document.getElementById('zoomRange');

                        currentRotation = 0;
                        zoomRange.value = 1;

                        // Show Bootstrap modal first
                        if (cropperModalInstance) {
                            cropperModalInstance.show();
                        }

                        // Destroy existing cropper
                        if (cropper) {
                            cropper.destroy();
                        }

                        // Set image source and wait for it to load
                        cropperImage.onload = function() {
                            // Wait for modal to fully render before initializing cropper
                            setTimeout(() => {
                                // Initialize cropper after image loads and modal is rendered
                                cropper = new Cropper(cropperImage, {
                                    aspectRatio: 1, // Square aspect ratio for profile photo
                                    viewMode: 0, // 0 = image can extend beyond container
                                    autoCropArea: 0.8,
                                    responsive: true,
                                    restore: true,
                                    guides: true,
                                    center: true,
                                    highlight: true,
                                    cropBoxMovable: true,
                                    cropBoxResizable: true,
                                    toggleDragModeOnDblclick: true,
                                });
                            }, 300);
                        };

                        // Set the image source (this will trigger onload)
                        cropperImage.src = imageSrc;
                    }

                    function closeCropperModal() {
                        if (cropperModalInstance) {
                            cropperModalInstance.hide();
                        }
                        if (cropper) {
                            cropper.destroy();
                            cropper = null;
                        }
                    }

                    // Setup event listeners
                    function setupEventListeners() {
                        console.log('Setting up event listeners...');

                        // Password toggle for current password
                        const toggleCurrentPassword = document.getElementById('toggleCurrentPassword');
                        const currentPasswordInput = document.getElementById('currentPassword');
                        if (toggleCurrentPassword && currentPasswordInput) {
                            toggleCurrentPassword.addEventListener('click', () => {
                                const type = currentPasswordInput.type === 'password' ? 'text' : 'password';
                                currentPasswordInput.type = type;
                                toggleCurrentPassword.innerHTML = type === 'password' ?
                                    '<i class="fa-solid fa-eye text-muted"></i>' :
                                    '<i class="fa-solid fa-eye-slash text-muted"></i>';
                            });
                        }

                        // Password toggle for new password
                        const toggleNewPassword = document.getElementById('toggleNewPassword');
                        const newPasswordInput = document.getElementById('newPassword');
                        if (toggleNewPassword && newPasswordInput) {
                            toggleNewPassword.addEventListener('click', () => {
                                const type = newPasswordInput.type === 'password' ? 'text' : 'password';
                                newPasswordInput.type = type;
                                toggleNewPassword.innerHTML = type === 'password' ?
                                    '<i class="fa-solid fa-eye text-muted"></i>' :
                                    '<i class="fa-solid fa-eye-slash text-muted"></i>';
                            });
                        }

                        // Password toggle for confirm password
                        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
                        const confirmPasswordInput = document.getElementById('confirmPassword');
                        if (toggleConfirmPassword && confirmPasswordInput) {
                            toggleConfirmPassword.addEventListener('click', () => {
                                const type = confirmPasswordInput.type === 'password' ? 'text' : 'password';
                                confirmPasswordInput.type = type;
                                toggleConfirmPassword.innerHTML = type === 'password' ?
                                    '<i class="fa-solid fa-eye text-muted"></i>' :
                                    '<i class="fa-solid fa-eye-slash text-muted"></i>';
                            });
                        }

                        // Profile photo upload
                        const uploadArea = document.getElementById('uploadArea');
                        const profilePhoto = document.getElementById('profilePhoto');

                        if (uploadArea && profilePhoto) {
                            uploadArea.addEventListener('click', () => profilePhoto.click());

                            // Drag and drop support
                            uploadArea.addEventListener('dragover', (e) => {
                                e.preventDefault();
                                uploadArea.style.backgroundColor = '#f0f8f9';
                            });

                            uploadArea.addEventListener('dragleave', () => {
                                uploadArea.style.backgroundColor = 'transparent';
                            });

                            uploadArea.addEventListener('drop', (e) => {
                                e.preventDefault();
                                uploadArea.style.backgroundColor = 'transparent';
                                const files = e.dataTransfer.files;
                                if (files.length > 0) {
                                    profilePhoto.files = files;
                                    handleFileSelectWithValidation();
                                }
                            });

                            profilePhoto.addEventListener('change', handleFileSelectWithValidation);
                        }

                        // Helper function to validate and handle file selection
                        function handleFileSelectWithValidation() {
                            const profilePhoto = document.getElementById('profilePhoto');
                            const file = profilePhoto.files[0];
                            if (file) {
                                // Validate file type
                                if (!file.type.startsWith('image/')) {
                                    ToastNotification.error('Please select a valid image file');
                                    profilePhoto.value = '';
                                    return;
                                }

                                // Validate file size (5MB max)
                                if (file.size > 5 * 1024 * 1024) {
                                    ToastNotification.error('File size must be less than 5MB');
                                    profilePhoto.value = '';
                                    return;
                                }

                                // Open cropper modal
                                handleFileSelect();
                            }
                        }

                        // Cropper button event listeners
                        const cropperImage = document.getElementById('cropperImage');
                        const cropperSave = document.getElementById('cropperSave');
                        const zoomRange = document.getElementById('zoomRange');
                        const rotateLeftBtn = document.getElementById('rotateLeftBtn');
                        const rotateRightBtn = document.getElementById('rotateRightBtn');
                        const resetCropBtn = document.getElementById('resetCropBtn');

                        if (cropperSave) {
                            cropperSave.addEventListener('click', () => {
                                if (cropper) {
                                    const canvas = cropper.getCroppedCanvas({
                                        maxWidth: 800,
                                        maxHeight: 800,
                                        fillColor: '#fff',
                                        imageSmoothingEnabled: true,
                                        imageSmoothingQuality: 'high',
                                    });

                                    // Convert canvas to blob and update file input
                                    // Use JPEG format with quality 0.8 for better compression
                                    canvas.toBlob((blob) => {
                                        const file = new File([blob], 'profile-photo-cropped.jpg', {
                                            type: 'image/jpeg'
                                        });
                                        const dataTransfer = new DataTransfer();
                                        dataTransfer.items.add(file);
                                        profilePhoto.files = dataTransfer.files;

                                        // Update preview
                                        const photoPreview = document.getElementById('profilePreview');
                                        if (photoPreview) {
                                            photoPreview.src = canvas.toDataURL();
                                        }
                                        closeCropperModal();
                                        ToastNotification.success('Image cropped successfully');
                                    }, 'image/jpeg', 0.8);
                                }
                            });
                        }

                        // Zoom control
                        if (zoomRange) {
                            zoomRange.addEventListener('input', (e) => {
                                if (cropper) {
                                    cropper.zoomTo(parseFloat(e.target.value));
                                }
                            });
                        }

                        // Rotate controls
                        if (rotateLeftBtn) {
                            rotateLeftBtn.addEventListener('click', () => {
                                if (cropper) {
                                    currentRotation -= 45;
                                    cropper.rotate(-45);
                                }
                            });
                        }

                        if (rotateRightBtn) {
                            rotateRightBtn.addEventListener('click', () => {
                                if (cropper) {
                                    currentRotation += 45;
                                    cropper.rotate(45);
                                }
                            });
                        }

                        // Reset button
                        if (resetCropBtn) {
                            resetCropBtn.addEventListener('click', () => {
                                if (cropper) {
                                    cropper.reset();
                                    currentRotation = 0;
                                    zoomRange.value = 1;
                                }
                            });
                        }

                        // Close modal when clicking outside
                        if (cropperModalElement) {
                            cropperModalElement.addEventListener('click', (e) => {
                                if (e.target === cropperModalElement) {
                                    closeCropperModal();
                                }
                            });
                        }

                        // Save button (for basic info)
                        const saveBtns = document.querySelectorAll('.save-btn');
                        saveBtns.forEach(btn => {
                            btn.addEventListener('click', async (e) => {
                                e.preventDefault();
                                const btnText = btn.textContent.trim();

                                if (btnText === 'Save') {
                                    await saveProfileData();
                                } else if (btnText === 'Change Password') {
                                    await changePasswordHandler();
                                }
                            });
                        });

                        // Delete account button
                        const deleteAccountBtn = document.querySelector('.account-deletion-btn');
                        if (deleteAccountBtn) {
                            deleteAccountBtn.addEventListener('click', async (e) => {
                                e.preventDefault();
                                await deleteAccountHandler();
                            });
                        }

                        // Save active tab to localStorage when tab changes
                        const profileTabs = document.querySelectorAll('#profileTabs button');
                        profileTabs.forEach(tab => {
                            tab.addEventListener('shown.bs.tab', (e) => {
                                const activeTabId = e.target.getAttribute('data-bs-target');
                                localStorage.setItem('activeProfileTab', activeTabId);
                                console.log('Active tab saved:', activeTabId);
                            });
                        });
                    }

                    // Change password handler
                    async function changePasswordHandler() {
                        try {
                            const currentPassword = document.getElementById('currentPassword').value.trim();
                            const newPassword = document.getElementById('newPassword').value.trim();
                            const confirmPassword = document.getElementById('confirmPassword').value.trim();

                            // Validation
                            if (!currentPassword || !newPassword || !confirmPassword) {
                                ToastNotification.error('Please fill in all password fields');
                                return;
                            }

                            if (newPassword !== confirmPassword) {
                                ToastNotification.error('New password and confirm password do not match');
                                return;
                            }

                            if (newPassword.length < 8) {
                                ToastNotification.error('New password must be at least 8 characters long');
                                return;
                            }

                            const response = await UserApiClient.changePassword(currentPassword, newPassword, confirmPassword);

                            if (response.success) {
                                ToastNotification.success('Password changed successfully!');
                                // Clear password fields
                                document.getElementById('currentPassword').value = '';
                                document.getElementById('newPassword').value = '';
                                document.getElementById('confirmPassword').value = '';
                            } else {
                                ToastNotification.error(response.message || 'Failed to change password');
                            }
                        } catch (error) {
                            console.error('Error changing password:', error);
                            ToastNotification.error('An error occurred while changing password. Please try again.');
                        }
                    }

                    // Delete account handler
                    async function deleteAccountHandler() {
                        try {
                            // Show confirmation modal
                            const confirmed = await window.confirmationModal.showAccountDeletionConfirmation();

                            if (!confirmed) {
                                return;
                            }

                            const response = await UserApiClient.deleteAccount();

                            if (response.success) {
                                ToastNotification.success('Account deleted successfully. Redirecting...');
                                // Redirect to login after 2 seconds
                                setTimeout(() => {
                                    localStorage.removeItem('auth_token');
                                    localStorage.removeItem('auth_user');
                                    window.location.href = '/login';
                                }, 2000);
                            } else {
                                ToastNotification.error(response.message || 'Failed to delete account');
                            }
                        } catch (error) {
                            console.error('Error deleting account:', error);
                            ToastNotification.error('An error occurred while deleting account. Please try again.');
                        }
                    }

                    // Save profile data
                    async function saveProfileData() {
                        try {
                            console.log('Saving profile data...');

                            // Validate required fields
                            const firstName = document.getElementById('firstName').value.trim();
                            const lastName = document.getElementById('lastName').value.trim();
                            const email = document.getElementById('email').value.trim();

                            if (!firstName || !lastName || !email) {
                                ToastNotification.error('Please fill in all required fields (First Name, Last Name, Email)');
                                return;
                            }

                            const formData = new FormData();

                            // Add basic information
                            formData.append('first_name', firstName);
                            formData.append('last_name', lastName);

                            const dobField = document.getElementById('dateOfBirth');
                            if (dobField && dobField.value) {
                                // Ensure date is in yyyy-MM-dd format
                                const dateObj = new Date(dobField.value);
                                const formattedDate = dateObj.toISOString().split('T')[0];
                                formData.append('date_of_birth', formattedDate);
                            }

                            const genderInput = document.querySelector('input[name="gender"]:checked');
                            if (genderInput) {
                                formData.append('gender', genderInput.value);
                            }

                            // Add parent details
                            const parentFirstField = document.getElementById('parentFirstName');
                            const parentLastField = document.getElementById('parentLastName');
                            const parentEmailField = document.getElementById('parentEmail');
                            const parentPhoneField = document.getElementById('parentPhone');

                            if (parentFirstField && parentFirstField.value) {
                                formData.append('parent_first_name', parentFirstField.value);
                            }
                            if (parentLastField && parentLastField.value) {
                                formData.append('parent_last_name', parentLastField.value);
                            }
                            if (parentEmailField && parentEmailField.value) {
                                formData.append('parent_email', parentEmailField.value);
                            }
                            if (parentPhoneField && parentPhoneField.value) {
                                formData.append('parent_phone', parentPhoneField.value);
                            }

                            // Add email
                            formData.append('email', email);

                            // Add profile photo if selected
                            const profilePhoto = document.getElementById('profilePhoto');
                            if (profilePhoto && profilePhoto.files.length > 0) {
                                formData.append('avatar', profilePhoto.files[0]);
                            }

                            const response = await UserApiClient.updateProfile(formData);

                            if (response.success) {
                                ToastNotification.success('Profile updated successfully!');

                                // Update localStorage with new user data
                                if (response.data) {
                                    const updatedUser = response.data;
                                    localStorage.setItem('auth_user', JSON.stringify(updatedUser));

                                    // Update sidebar profile image if it exists
                                    const sidebarProfileImage = document.getElementById('profileImage');
                                    if (sidebarProfileImage && updatedUser.profile_photo) {
                                        // Handle both full URLs and relative paths
                                        if (updatedUser.profile_photo.startsWith('/')) {
                                            sidebarProfileImage.src = updatedUser.profile_photo;
                                        } else {
                                            sidebarProfileImage.src = `/storage/${updatedUser.profile_photo}`;
                                        }
                                    }

                                    // Update sidebar user name and role
                                    const userName = document.getElementById('userName');
                                    if (userName && updatedUser.first_name) {
                                        userName.textContent = updatedUser.first_name + (updatedUser.last_name ? ' ' + updatedUser
                                            .last_name : '');
                                    }

                                    const userRole = document.getElementById('userRole');
                                    if (userRole && updatedUser.role) {
                                        const roleText = updatedUser.role.charAt(0).toUpperCase() + updatedUser.role.slice(1);
                                        userRole.textContent = roleText;
                                    }
                                }

                                // Clear file input
                                if (profilePhoto) profilePhoto.value = '';
                                // Reload profile data
                                await loadProfileData();
                            } else {
                                // Log validation errors for debugging
                                console.error('Profile update failed:', response);
                                if (response.errors) {
                                    console.error('Validation errors:', response.errors);
                                    const errorMessages = Object.values(response.errors).flat().join(', ');
                                    ToastNotification.error('Validation error: ' + errorMessages);
                                } else {
                                    ToastNotification.error(response.message || 'Failed to update profile');
                                }
                            }
                        } catch (error) {
                            console.error('Error saving profile:', error);
                            ToastNotification.error('An error occurred while saving profile. Please try again.');
                        }
                    }

                    // Restore active tab from localStorage
                    function restoreActiveTab() {
                        const activeTabId = localStorage.getItem('activeProfileTab');

                        if (activeTabId) {
                            // Find the tab button that corresponds to this tab ID
                            const tabButton = document.querySelector(`button[data-bs-target="${activeTabId}"]`);

                            if (tabButton) {
                                // Use Bootstrap's tab method to show the tab
                                const tab = new bootstrap.Tab(tabButton);
                                tab.show();
                                console.log('Active tab restored:', activeTabId);
                            }
                        }
                    }

                    // Load settings data on page load
                    async function loadSettingsData() {
                        try {
                            console.log('Loading settings data...');
                            const response = await UserApiClient.getProfile();

                            if (response.success && response.data) {
                                const user = response.data;
                                const languageSelect = document.getElementById('languagePreference');

                                if (languageSelect && user.language_preference) {
                                    languageSelect.value = user.language_preference;
                                    console.log('Language preference loaded:', user.language_preference);
                                }
                            }
                        } catch (error) {
                            console.error('Error loading settings:', error);
                        }
                    }

                    // Save settings
                    async function saveSettings() {
                        try {
                            const languageSelect = document.getElementById('languagePreference');
                            const language = languageSelect.value;

                            if (!language) {
                                ToastNotification.error('Please select a language');
                                return;
                            }

                            console.log('Saving settings with language:', language);

                            // Call the API to update language preference
                            const response = await fetch('/api/language/user/set', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || ''
                                },
                                body: JSON.stringify({
                                    locale: language
                                })
                            });

                            const data = await response.json();

                            if (data.success) {
                                ToastNotification.success('Settings saved successfully!');
                                console.log('Settings saved:', data.data);

                                // Update localStorage
                                const user = JSON.parse(localStorage.getItem('auth_user') || '{}');
                                user.language_preference = language;
                                localStorage.setItem('auth_user', JSON.stringify(user));

                                // Wait a moment then reload the page to apply the new language
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                ToastNotification.error(data.message || 'Failed to save settings');
                            }
                        } catch (error) {
                            console.error('Error saving settings:', error);
                            ToastNotification.error('An error occurred while saving settings');
                        }
                    }

                    // Setup settings event listeners
                    function setupSettingsEventListeners() {
                        const saveSettingsBtn = document.getElementById('saveSettingsBtn');
                        if (saveSettingsBtn) {
                            saveSettingsBtn.addEventListener('click', async (e) => {
                                e.preventDefault();
                                await saveSettings();
                            });
                        }
                    }

                    // Update the initial DOMContentLoaded to include settings
                    const originalDOMContentLoaded = document.addEventListener;
                    document.addEventListener('DOMContentLoaded', async () => {
                        console.log('Profile page loaded, fetching user data...');

                        await loadProfileData();
                        await loadSettingsData();
                        setupEventListeners();
                        setupSettingsEventListeners();
                        restoreActiveTab();
                    });

                    // Expose functions to window for debugging
                    window.loadProfileData = loadProfileData;
                    window.saveProfileData = saveProfileData;
                    window.loadSettingsData = loadSettingsData;
                    window.saveSettings = saveSettings;    </script>
    </main>
@endsection
