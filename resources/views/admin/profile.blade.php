@extends('layouts.dashboardtemp')
@section('content')
<style>
 body {
      background-color: #f9fafb;
    }
.card {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
            background: #f9f9f9; border: 1px solid #e8e8e8;
            padding: 20px;
        }
         .line-divider{
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
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
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
    .account-deletion-title{
        color: #000000;
        font-size: 24px;
        font-weight: 600;
    }
    .account-deletion-text{
        color: #666666;
        font-size: 16px;
    }
    .account-deletion-btn{
        border: 1px solid #F56824;
        padding: 16px 20px ;
        background-color: transparent;
        color: #F56824;
        font-size: 16px;
        font-weight: 600;
    }
</style>

<main>
<div class="container my-5">
  <h1>My Profile</h1>

  <!-- Tabs -->
  <ul class="nav nav-tabs mt-3" id="profileTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">My details</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">Login</button>
    </li>
    {{-- <li class="nav-item" role="presentation">
      <button class="nav-link" id="wallet-tab" data-bs-toggle="tab" data-bs-target="#wallet" type="button" role="tab">Wallet</button>
    </li> --}}
  </ul>

        .zoom-label {
            font-size: 0.9rem;
            color: #666;
            min-width: 50px;
            margin-bottom: 0;
        }

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
                                <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #000;">Login/Account Details</h5>
                                <span class="text-danger ms-2" style="font-size: 1.2rem;">*</span>
                            </div>
                            <div class="line-divider"></div>
                            <form class='d-flex flex-column gap-4'>
                                <div class="form-group">
                                        <div class="modal-form-input-border">
                                        <label class="modal-label">Enter Email Address</label>
                                        <input type="email" class="modal-input" id="email"
                                            name="email" placeholder="@gmail.com">
                                        </div>
                                        <small class="text-danger d-none" id="emailError"></small>
                                    </div>
                                     <div class="form-group">
                                        <div class="modal-form-input-border">
                                        <label class="modal-label">Password</label>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <input type="password" class="modal-input" id="password"
                                                name="password" placeholder="••••••••" required>
                                            <button type="button"
                                                class="btn btn-link position-absolute end-0 top-50 translate-middle-y"
                                                id="togglePassword" style="border: none; padding: 0.5rem 1rem;">
                                                <i class="fa-solid fa-eye" style="color: #999;"></i>
                                            </button>
                                        </div>
                                        </div>
                                        <small class="text-danger d-none" id="passwordError"></small>
                                    </div>
                                     <div class="form-group">
                                        <div class="modal-form-input-border">
                                        <label class="modal-label">Enter New Password</label>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <input type="password" class="modal-input" id="password"
                                                name="password" placeholder="••••••••" required>
                                            <button type="button"
                                                class="btn btn-link position-absolute end-0 top-50 translate-middle-y"
                                                id="togglePassword" style="border: none; padding: 0.5rem 1rem;">
                                                <i class="fa-solid fa-eye" style="color: #999;"></i>
                                            </button>
                                        </div>
                                        </div>
                                        <small class="text-danger d-none" id="passwordError"></small>
                                    </div>
                                     <div class="form-group">
                                        <div class="modal-form-input-border">
                                        <label class="modal-label">Confirm Password</label>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <input type="password" class="modal-input" id="password"
                                                name="password" placeholder="••••••••" required>
                                            <button type="button"
                                                class="btn btn-link position-absolute end-0 top-50 translate-middle-y"
                                                id="togglePassword" style="border: none; padding: 0.5rem 1rem;">
                                                <i class="fa-solid fa-eye" style="color: #999;"></i>
                                            </button>
                                        </div>
                                        </div>
                                        <small class="text-danger d-none" id="passwordError"></small>
                                    </div>
                                     <div class="form-group">
                                        <div class="modal-form-input-border">
                                        <label class="modal-label">Select Role</label>
                                        <select class="modal-input" id="role" name="role"
                                            required>
                                            <option value="">Select Role</option>
                                            <option value="student">Student</option>
                                            <option value="instructor">Instructor</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                        </div>
                                        <small class="text-danger d-none" id="roleError"></small>
                                    </div>
                                    <button class="save-btn mt-0">Change Password</button>
                            </form>
            </div>

        </div>
        <div class="col-4">
            <div class="card d-flex flex-column gap-4" style="background-color: #CCDBDD;">
                <h3 class="account-deletion-title">Account Deleting</h3>
                <p class="account-deletion-text">Once your account is deleted, you will lose access to it and all your data. Information from your account is not duplicated and after deleting, your account will be lost forever without the possibility of recovery.</p>
                <button class="account-deletion-btn">Account Delect</button>

            </div>
        </div>
    </main>

      </div>
    </div>


  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  const fileInput = document.getElementById('fileInput');
  const uploadBox = document.getElementById('uploadBox');
  const fileName = document.getElementById('fileName');
  const previewImage = document.getElementById('previewImage');
  const saveBtn = document.getElementById('saveBtn');

  // Password toggle
        const toggles = document.querySelectorAll('#togglePassword');
const inputs = document.querySelectorAll('#password');

toggles.forEach((toggle, index) => {
    toggle.addEventListener('click', () => {
        const input = inputs[index];

        const type = input.type === 'password' ? 'text' : 'password';
        input.type = type;

        toggle.innerHTML = type === 'password'
            ? '<i class="fa-solid fa-eye text-muted"></i>'
            : '<i class="fa-solid fa-eye-slash text-muted"></i>';
    });
});


  // Make the upload box clickable
  uploadBox.addEventListener('click', () => fileInput.click());

  // Show filename and preview
  fileInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
      fileName.textContent = file.name;
      const reader = new FileReader();
      reader.onload = (event) => {
        previewImage.src = event.target.result;
      };
      reader.readAsDataURL(file);
    }
  });

  // Simulated save action
  saveBtn.addEventListener('click', (e) => {
    e.preventDefault();
    const formData = {
      firstName: document.getElementById('firstName').value,
      lastName: document.getElementById('lastName').value,
      gender: document.querySelector('input[name="gender"]:checked').value,
      dob: document.getElementById('dob').value,
      parentFirst: document.getElementById('parentFirst').value,
      parentLast: document.getElementById('parentLast').value,
      parentEmail: document.getElementById('parentEmail').value,
      parentPhone: document.getElementById('parentPhone').value
    };
    alert('✅ Information saved successfully!\n\n' + JSON.stringify(formData, null, 2));
  });
</script>
</main>
@endsection
