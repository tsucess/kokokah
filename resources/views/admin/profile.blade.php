@extends('layouts.dashboardtemp')
<style>
 body {
      background-color: #f9fafb;
    }

    .profile-card {
      border: 1px solid #eee;
      border-radius: 15px;
      padding: 25px;
      background: white;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .upload-box {
      border: 2px dashed #d2d6dc;
      border-radius: 10px;
      text-align: center;
      padding: 35px 10px;
      cursor: pointer;
      transition: border-color 0.3s ease;
      position: relative;
    }

    .upload-box:hover {
      border-color: #0d6efd;
      background-color: #f9fafc;
    }

    .upload-box input {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      opacity: 0;
      cursor: pointer;
    }

    .upload-icon {
      font-size: 30px;
      color: #6c757d;
    }

    .preview-image {
      width: 100%;
      max-width: 240px;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.15);
      object-fit: cover;
    }

    .save-btn {
      background-color: #ffb100;
      border: none;
      padding: 12px 30px;
      border-radius: 8px;
      color: white;
      font-weight: 600;
      font-size: 1.1rem;
      transition: background-color 0.3s;
    }

    .save-btn:hover {
      background-color: #e6a000;
    }

    .form-floating > label {
      padding-left: 0.75rem;
    }

    .fw-bold.text-primary {
      color: #0d6efd !important;
    }
</style>

<main>
<div class="container my-5">
  <h4 class="fw-bold" style = "font-size:36px;">My Profile</h4>

  <!-- Tabs -->
  <ul class="nav nav-tabs mt-3" id="profileTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">My details</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="safety-tab" data-bs-toggle="tab" data-bs-target="#safety" type="button" role="tab">Safety</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="wallet-tab" data-bs-toggle="tab" data-bs-target="#wallet" type="button" role="tab">Wallet</button>
    </li>
  </ul>

  <!-- Tab content -->
  <div class="tab-content mt-4" id="profileTabsContent">

    <!-- My Details Tab -->
    <div class="tab-pane fade show active" id="details" role="tabpanel">
      <div class="row g-4">
        <div class="col-lg-8">
          <!-- Basic Info -->
          <div class="profile-card mb-4">
            <h5 class="fw-bold mb-3">Basic Information</h5>
            <form id="profileForm">
              <div class="row g-3">
                {{-- <div class="col-md-6 form-floating">
                  <input type="text" class="form-control" id="firstName" placeholder="Enter First Name" required>
                  <label for="firstName">Enter First Name</label>
                </div> --}}
                <div class="w-50 custom-form-group">

                    <label for="fname" class="custom-label">Enter First Name</label>
                    <input type="text" class="form-control-custom" id="fname" name="fname" placeholder="Winner" aria-label="Email Address"  required>

                </div>

                <div class="w-50 custom-form-group">

                    <label for="lname" class="custom-label">Enter Last Name</label>
                    <input type="text" class="form-control-custom" id="lname" name="lname" placeholder="Effiong" aria-label="Email Address"  required>

                </div>
                {{-- <div class="col-md-6 form-floating">
                  <input type="text" class="form-control" id="lastName" placeholder="Enter Last Name" required>
                  <label for="lastName">Enter Last Name</label>
                </div> --}}

                <div class="col-md-12 d-flex align-items-center mt-2">
                  <label class="me-3 fw-semibold">Gender:</label>
                  <div class="form-check me-3">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="Male" checked>
                    <label class="form-check-label" for="male">Male</label>
                  </div>

                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="Female">
                    <label class="form-check-label" for="female">Female</label>
                  </div>
                </div>

                {{-- <div class="col-md-6 form-floating">
                  <input type="date" class="form-control" id="dob" placeholder="Enter Date of Birth">
                  <label for="dob">Enter Date of Birth</label>
                </div> --}}

                <div class="w-50 custom-form-group">

                    <label for="date" class="custom-label">Enter Date of Birth</label>
                    <input type="date" class="form-control-custom" id="date" name="date" placeholder="Effiong" aria-label="Email Address"  required>
                </div>

                <div class="col-md-6">
                  <div class="upload-box" id="uploadBox">
                    <i class="bi bi-upload upload-icon"></i>
                    <p class="mt-2 mb-1 fw-semibold">Drop your files to upload</p>
                    <input type="file" id="fileInput" accept="image/*">
                    <small id="fileName" class="text-muted"></small>
                  </div>
                </div>

              </div>
            </form>
          </div>

          <!-- Parent Details -->
          <div class="profile-card">
            <h5 class="fw-bold mb-3">Parent Details</h5>
            <div class="row g-3">
              {{-- <div class="col-md-6 form-floating">
                <input type="text" class="form-control" id="parentFirst" placeholder="Enter First Name">
                <label for="parentFirst">Enter First Name</label>
              </div>
              <div class="col-md-6 form-floating">
                <input type="text" class="form-control" id="parentLast" placeholder="Enter Last Name">
                <label for="parentLast">Enter Last Name</label>
              </div> --}}


              <div class="w-50 custom-form-group">

                    <label for="fname" class="custom-label">Enter First Name</label>
                    <input type="text" class="form-control-custom" id="fname" name="fname" placeholder="Winner" aria-label="Email Address"  required>

                </div>

                <div class="w-50 custom-form-group">

                    <label for="lname" class="custom-label">Enter Last Name</label>
                    <input type="text" class="form-control-custom" id="lname" name="lname" placeholder="Effiong" aria-label="Email Address"  required>

                </div>

              {{-- <div class="col-md-6 form-floating">
                <input type="email" class="form-control" id="parentEmail" placeholder="Enter Parent Email Address">
                <label for="parentEmail">Enter Parent Email Address</label>
              </div>
              <div class="col-md-6 form-floating">
                <input type="tel" class="form-control" id="parentPhone" placeholder="Enter Parent Phone Number">
                <label for="parentPhone">Enter Parent Phone Number</label>
              </div> --}}


              <div class="w-50 custom-form-group">

                    <label for="email" class="custom-label">Enter Parent Email Address</label>
                    <input type="email" class="form-control-custom" id="email" name="email" placeholder="Winner" aria-label="Email Address"  required>

                </div>

                <div class="w-50 custom-form-group">

                    <label for="phone" class="custom-label">Enter Parent Phone Number</label>
                    <input type="tel" class="form-control-custom" id="phone" name="phone" placeholder="Effiong" aria-label="Email Address"  required>

                </div>


            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4 d-flex flex-column align-items-center justify-content-start">
          <img src="https://via.placeholder.com/200x200.png?text=Profile+Preview" id="previewImage" class="preview-image mb-4" alt="Profile Preview">
          <button id="saveBtn" class="save-btn w-75">Save</button>
        </div>
      </div>
    </div>

    <!-- Safety Tab -->
    <div class="tab-pane fade" id="safety" role="tabpanel">
      <div class="profile-card">
        <h5 class="fw-bold">Safety Settings</h5>
        <p>Content for Safety tab goes here...</p>
      </div>
    </div>

    <!-- Wallet Tab -->
    <div class="tab-pane fade" id="wallet" role="tabpanel">
      <div class="profile-card">
        <h5 class="fw-bold">Wallet Information</h5>
        <p>Content for Wallet tab goes here...</p>
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
