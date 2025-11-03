@extends('layouts.dashboardtemp')

@section('content')
    <main class="add-user-main">
        <div class="container-fluid px-5 py-4">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h1 class="fw-bold mb-2" style="font-size: 2.5rem; color: #004A53; font-family: 'Fredoka One', sans-serif;">Edit User</h1>
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
                    <div class="card border-0 shadow-sm rounded-4 mb-4"
                        style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-5">
                                <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1a1a1a;">Basic Information</h5>
                                <span class="text-danger ms-2" style="font-size: 1.2rem;">*</span>
                            </div>

                            <form id="createUserForm">
                                @csrf

                                <!-- First Name and Last Name Row -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label form-label-custom">Enter First Name</label>
                                            <input type="text" class="form-control form-input-custom" id="firstName"
                                                name="first_name" placeholder="Winner" required>
                                            <small class="text-danger d-none" id="firstNameError"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label form-label-custom">Enter Last Name</label>
                                            <input type="text" class="form-control form-input-custom" id="lastName"
                                                name="last_name" placeholder="Winner" required>
                                            <small class="text-danger d-none" id="lastNameError"></small>
                                        </div>
                                    </div>
                                </div>



                                
                                <!-- Date of Birth Row -->
                                <div class="row mb-4">
                                  <div class="col-md-6">
                                      <!-- Gender Row -->
                                      <div class="mb-4">
                                          <label class="form-label form-label-custom">Gender</label>
                                          <div class="d-flex gap-5">
                                              <div class="form-check">
                                                  <input class="form-check-input" type="radio" name="gender"
                                                      id="genderMale" value="male" checked
                                                      style="width: 1.2rem; height: 1.2rem; cursor: pointer;">
                                                  <label class="form-check-label" for="genderMale"
                                                      style="cursor: pointer; margin-left: 0.5rem; color: #333; font-weight: 500;">Male</label>
                                              </div>
                                              <div class="form-check">
                                                  <input class="form-check-input" type="radio" name="gender"
                                                      id="genderFemale" value="female"
                                                      style="width: 1.2rem; height: 1.2rem; cursor: pointer;">
                                                  <label class="form-check-label" for="genderFemale"
                                                      style="cursor: pointer; margin-left: 0.5rem; color: #333; font-weight: 500;">Female</label>
                                              </div>
                                          </div>
                                      </div>
                                        <div class="form-group">
                                            <label class="form-label form-label-custom">Enter Date of Birth</label>
                                            <input type="date" class="form-control form-input-custom" id="dateOfBirth"
                                                name="date_of_birth" placeholder="DD/MM/YYYY">
                                            <small class="text-danger d-none" id="dobError"></small>
                                        </div>
                                    </div>
                                     <!-- Profile Photo Upload Area -->
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label form-label-custom mb-1">Profile Photo (Optional)</label>
                                        <div class="border-2 border-dashed rounded-4 p-5 text-center"
                                            style="border-color: #004A53; cursor: pointer; background: white; transition: all 0.3s ease;"
                                            id="uploadArea">
                                            <div class="mb-3">
                                                <i class="fa-solid fa-file-lines fa-2x" style="color: #004A53;"></i>
                                            </div>
                                            <p class="fw-semibold mb-2" style="color: #333; font-size: 0.95rem;">Drop your files to upload</p>
                                            <small style="color: #666;">Select files</small>
                                            <input type="file" id="profilePhoto" name="profile_photo" class="d-none"
                                                accept="image/*">
                                        </div>
                                    </div>
                                </div>

                               
                            </form>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4"
                        style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-5" style="font-size: 1.1rem; color: #1a1a1a;">Contact Information</h5>

                            <form id="contactForm">
                                <!-- Phone Number -->
                                <div class="mb-4">
                                    <div class="form-group">
                                        <label class="form-label form-label-custom">Enter Phone Number</label>
                                        <input type="tel" class="form-control form-input-custom" id="phoneNumber"
                                            name="phone_number" placeholder="Winner">
                                        <small class="text-danger d-none" id="phoneError"></small>
                                    </div>
                                </div>

                                <!-- Home Address -->
                                <div class="mb-4">
                                    <div class="form-group">
                                        <label class="form-label form-label-custom">Enter Home Address</label>
                                        <input type="text" class="form-control form-input-custom" id="homeAddress"
                                            name="home_address" placeholder="Address">
                                        <small class="text-danger d-none" id="addressError"></small>
                                    </div>
                                </div>

                                <!-- State -->
                                <div class="mb-4">
                                    <div class="form-group">
                                        <label class="form-label form-label-custom">State</label>
                                        <input type="text" class="form-control form-input-custom" id="state"
                                            name="state" placeholder="Address">
                                        <small class="text-danger d-none" id="stateError"></small>
                                    </div>
                                </div>

                                <!-- Zipcode -->
                                <div class="mb-4">
                                    <div class="form-group">
                                        <label class="form-label form-label-custom">Zipcode</label>
                                        <input type="text" class="form-control form-input-custom" id="zipcode"
                                            name="zipcode" placeholder="Address">
                                        <small class="text-danger d-none" id="zipcodeError"></small>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Parent Details Section -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4"
                        style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-5" style="font-size: 1.1rem; color: #1a1a1a;">Parent Details</h5>

                            <form id="parentForm">
                                <!-- Parent First Name and Last Name Row -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label form-label-custom">Enter First Name</label>
                                            <input type="text" class="form-control form-input-custom" id="parentFirstName"
                                                name="parent_first_name" placeholder="Winner">
                                            <small class="text-danger d-none" id="parentFirstNameError"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label form-label-custom">Enter Last Name</label>
                                            <input type="text" class="form-control form-input-custom" id="parentLastName"
                                                name="parent_last_name" placeholder="Winner">
                                            <small class="text-danger d-none" id="parentLastNameError"></small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Parent Email and Phone Row -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label form-label-custom">Enter Parent Email Address</label>
                                            <input type="email" class="form-control form-input-custom" id="parentEmail"
                                                name="parent_email" placeholder="Winner">
                                            <small class="text-danger d-none" id="parentEmailError"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label form-label-custom">Enter Parent Phone Number</label>
                                            <input type="tel" class="form-control form-input-custom" id="parentPhone"
                                                name="parent_phone" placeholder="Winner">
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
                                        class="rounded-4"
                                        style="width: 100%; max-width: 280px; height: auto; object-fit: cover;">
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Login/Account Details Section -->
                    <div class="card border-0 shadow-sm rounded-4"
                        style="background: #f9f9f9; border: 1px solid #e8e8e8;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-5">
                                <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1a1a1a;">Login/Account Details
                                </h5>
                                <span class="text-danger ms-2" style="font-size: 1.2rem;">*</span>
                            </div>

                            <form id="loginForm">
                                <!-- Email Address -->
                                <div class="mb-4">
                                    <div class="form-group">
                                        <label class="form-label form-label-custom">Enter Email Address</label>
                                        <input type="email" class="form-control form-input-custom" id="email"
                                            name="email" placeholder="@gmail.com" required>
                                        <small class="text-danger d-none" id="emailError"></small>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="mb-4">
                                    <div class="form-group">
                                        <label class="form-label form-label-custom">Enter Password</label>
                                        <div class="password-input-wrapper position-relative">
                                            <input type="password" class="form-control form-input-custom" id="password"
                                                name="password" placeholder="••••••••" required>
                                            <button type="button"
                                                class="btn btn-link position-absolute end-0 top-50 translate-middle-y"
                                                id="togglePassword" style="border: none; padding: 0.5rem 1rem;">
                                                <i class="fa-solid fa-eye" style="color: #999;"></i>
                                            </button>
                                        </div>
                                        <small class="text-danger d-none" id="passwordError"></small>
                                    </div>
                                </div>

                                <!-- Role Selection -->
                                <div class="mb-4">
                                    <div class="form-group">
                                        <label class="form-label form-label-custom">Select Role</label>
                                        <select class="form-select form-input-custom" id="role" name="role" required>
                                            <option value="student">Student</option>
                                            <option value="instructor">Instructor</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                        <small class="text-danger d-none" id="roleError"></small>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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

        .form-label-custom {
            font-size: 0.95rem;
            font-weight: 600;
            color: #004A53;
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
    </style>

    <script>
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                         document.querySelector('input[name="_token"]')?.value;

        // File upload handling
        const uploadArea = document.getElementById('uploadArea');
        const profilePhoto = document.getElementById('profilePhoto');
        const profilePreview = document.getElementById('profilePreview');

        if (uploadArea) {
            uploadArea.addEventListener('click', () => profilePhoto.click());

            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.style.backgroundColor = '#f0f0f0';
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
                    handleFileSelect();
                }
            });

            profilePhoto.addEventListener('change', handleFileSelect);
        }

        function handleFileSelect() {
            const file = profilePhoto.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    profilePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Password toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (togglePassword) {
            togglePassword.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                togglePassword.innerHTML = type === 'password' ?
                    '<i class="fa-solid fa-eye text-muted"></i>' :
                    '<i class="fa-solid fa-eye-slash text-muted"></i>';
            });
        }

        // Form submission
        const createUserForm = document.getElementById('createUserForm');
        const saveBtn = document.getElementById('saveBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const resetBtn = document.getElementById('resetBtn');

        // Get user ID from URL query parameter
        const urlParams = new URLSearchParams(window.location.search);
        const userId = urlParams.get('id');
        const isEditMode = !!userId;

        // Load user data if in edit mode
        if (isEditMode) {
            loadUserData(userId);
        }

        async function loadUserData(userId) {
            try {
                const token = localStorage.getItem('auth_token');
                const response = await fetch(`/api/admin/users/${userId}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    showAlert('Failed to load user data', 'error');
                    return;
                }

                const data = await response.json();
                const user = data.data;

                // Populate form fields
                document.getElementById('firstName').value = user.first_name || '';
                document.getElementById('lastName').value = user.last_name || '';
                document.getElementById('email').value = user.email || '';
                document.getElementById('phoneNumber').value = user.contact || '';
                document.getElementById('homeAddress').value = user.address || '';
                document.getElementById('state').value = user.state || '';
                document.getElementById('zipcode').value = user.zipcode || '';
                document.getElementById('dateOfBirth').value = user.date_of_birth || '';
                document.getElementById('parentFirstName').value = user.parent_first_name || '';
                document.getElementById('parentLastName').value = user.parent_last_name || '';
                document.getElementById('parentEmail').value = user.parent_email || '';
                document.getElementById('parentPhone').value = user.parent_phone || '';
                document.getElementById('role').value = user.role || 'student';

                // Set gender
                if (user.gender) {
                    document.querySelector(`input[name="gender"][value="${user.gender}"]`).checked = true;
                }

                // Load profile photo
                if (user.profile_photo) {
                    profilePreview.src = `/storage/${user.profile_photo}`;
                }

                // Update page title
                document.querySelector('h1').textContent = `Edit User - ${user.first_name} ${user.last_name}`;
                document.querySelector('.btn-light').textContent = 'Back';
                saveBtn.textContent = 'Update';

            } catch (error) {
                console.error('Error loading user data:', error);
                showAlert('Error loading user data', 'error');
            }
        }

        if (saveBtn) {
            saveBtn.addEventListener('click', async (e) => {
                e.preventDefault();

                // Validate required fields
                const firstName = document.getElementById('firstName').value.trim();
                const lastName = document.getElementById('lastName').value.trim();
                const email = document.getElementById('email').value.trim();
                const role = document.getElementById('role').value;

                if (!firstName) {
                    showAlert('First name is required', 'error');
                    return;
                }
                if (!lastName) {
                    showAlert('Last name is required', 'error');
                    return;
                }
                if (!email) {
                    showAlert('Email is required', 'error');
                    return;
                }
                if (!role) {
                    showAlert('Role is required', 'error');
                    return;
                }

                // In edit mode, password is optional
                if (!isEditMode) {
                    const password = document.getElementById('password').value.trim();
                    if (!password) {
                        showAlert('Password is required', 'error');
                        return;
                    }
                }

                // Create FormData for file upload
                const formData = new FormData();

                // For PUT requests with FormData, we need to add _method field for Laravel method spoofing
                if (isEditMode) {
                    formData.append('_method', 'PUT');
                }

                formData.append('first_name', firstName);
                formData.append('last_name', lastName);
                formData.append('email', email);
                formData.append('role', role);
                formData.append('gender', document.querySelector('input[name="gender"]:checked')?.value || 'male');

                // Add password only if provided (required for create, optional for edit)
                const password = document.getElementById('password').value.trim();
                if (password) {
                    formData.append('password', password);
                }

                // Only append optional fields if they have values
                const dateOfBirth = document.getElementById('dateOfBirth').value;
                if (dateOfBirth) formData.append('date_of_birth', dateOfBirth);

                const phoneNumber = document.getElementById('phoneNumber').value;
                if (phoneNumber) formData.append('phone_number', phoneNumber);

                const homeAddress = document.getElementById('homeAddress').value;
                if (homeAddress) formData.append('home_address', homeAddress);

                const state = document.getElementById('state').value;
                if (state) formData.append('state', state);

                const zipcode = document.getElementById('zipcode').value;
                if (zipcode) formData.append('zipcode', zipcode);

                const parentFirstName = document.getElementById('parentFirstName').value;
                if (parentFirstName) formData.append('parent_first_name', parentFirstName);

                const parentLastName = document.getElementById('parentLastName').value;
                if (parentLastName) formData.append('parent_last_name', parentLastName);

                const parentEmail = document.getElementById('parentEmail').value;
                if (parentEmail) formData.append('parent_email', parentEmail);

                const parentPhone = document.getElementById('parentPhone').value;
                if (parentPhone) formData.append('parent_phone', parentPhone);

                // Add profile photo if selected
                if (profilePhoto.files.length > 0) {
                    formData.append('profile_photo', profilePhoto.files[0]);
                }

                // Debug: Log form data
                console.log('Sending update request to:', isEditMode ? `/api/admin/users/${userId}` : '/api/admin/users');
                console.log('Method:', isEditMode ? 'PUT' : 'POST');
                console.log('Form data entries:');
                for (let [key, value] of formData.entries()) {
                    console.log(`  ${key}:`, value);
                }

                try {
                    saveBtn.disabled = true;
                    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

                    // Get the auth token from localStorage
                    const token = localStorage.getItem('auth_token');

                    const headers = {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    };

                    // Add Authorization header if token exists
                    if (token) {
                        headers['Authorization'] = `Bearer ${token}`;
                    }

                    // Use POST for both create and edit (with _method for edit)
                    // This is required for FormData with file uploads
                    const endpoint = isEditMode ? `/api/admin/users/${userId}` : '/api/admin/users';

                    const response = await fetch(endpoint, {
                        method: 'POST',  // Always POST when using FormData
                        headers: headers,
                        body: formData,
                        cache: 'no-store'  // Prevent caching
                    });

                    const data = await response.json();

                    console.log('Response status:', response.status);
                    console.log('Response data:', data);
                    console.log('Updated user from API:', data.data);

                    if (response.ok) {
                        const message = isEditMode ? 'User updated successfully!' : 'User created successfully!';
                        console.log('Success! Updated user data:', data.data);
                        showAlert(message, 'success');

                        // Add a small delay to ensure database is fully committed
                        setTimeout(() => {
                            // Force reload to get fresh data from server
                            window.location.href = '/users?t=' + Date.now();
                        }, 1500);
                    } else {
                        // Log validation errors for debugging
                        console.error('Request failed with status:', response.status);
                        console.error('Response data:', data);

                        if (data.errors) {
                            // Show validation errors
                            let errorMessage = 'Validation errors:\n';
                            for (const [field, messages] of Object.entries(data.errors)) {
                                errorMessage += `${field}: ${messages.join(', ')}\n`;
                            }
                            console.error(errorMessage);
                            showAlert(errorMessage, 'error');
                        } else {
                            showAlert(data.message || 'Failed to save user', 'error');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    const errorMsg = isEditMode ? 'An error occurred while updating the user' : 'An error occurred while creating the user';
                    showAlert(errorMsg, 'error');
                } finally {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = isEditMode ? 'Update' : 'save';
                }
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => {
                window.location.href = '/users';
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                createUserForm.reset();
                profilePreview.src = 'images/winner-round.png';
            });
        }

        // Alert helper function
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alertHTML = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            alertContainer.innerHTML = alertHTML;

            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }
    </script>
@endsection
