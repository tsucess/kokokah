@extends('admin.dashboardtemp')

@section('content')
<main>
  <div class="container">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold text-success">Add New User</h4>
        <small class="text-muted">Have overview of user</small>
      </div>
      <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary">Cancel</button>
        <button class="btn btn-outline-dark">Reset</button>
        <button class="btn btn-success">Save</button>
      </div>
    </div>


    <div class="row g-4">
      <!-- Basic Information -->
      <div class="col-lg-6">
        <div class="card p-4">
          <h6 class="fw-bold mb-3">Basic Information</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <input type="text" class="form-control" placeholder="Enter First Name">
            </div>
            <div class="col-md-6">
              <input type="text" class="form-control" placeholder="Enter Last Name">
            </div>

            <!-- Gender -->
            <div class="col-12">
              <label class="form-label">Gender</label>
              <div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="gender" id="male" value="Male">
                  <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="gender" id="female" value="Female">
                  <label class="form-check-label" for="female">Female</label>
                </div>
              </div>
            </div>

            <!-- DOB -->
            <div class="col-md-6">
              <input type="date" class="form-control" placeholder="Enter Date of Birth">
            </div>

            <!-- Upload -->
            <div class="col-md-6">
              <div class="upload-box" onclick="document.getElementById('fileUpload').click()">
                <i class="fa-solid fa-cloud-arrow-up fa-2x mb-2"></i>
                <p class="mb-0">Drop your files to upload</p>
              </div>
              <!-- Hidden file input -->
              <input type="file" id="fileUpload" class="d-none">
            </div>
          </div>
        </div>
      </div>

      <!-- Login/Account Details -->
      <div class="col-lg-6">
        <div class="card p-4">
          <h6 class="fw-bold mb-3">Login/Account Details</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <input type="text" class="form-control" placeholder="Enter User Name">
            </div>
            <div class="col-md-6">
              <input type="password" class="form-control" placeholder="Enter Password">
            </div>
            <div class="col-md-12">
              <select class="form-select">
                <option selected disabled>Select Role</option>
                <option>Admin</option>
                <option>Instructor</option>
                <option>Student</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Parent Details -->
      <div class="col-lg-6">
        <div class="card p-4">
          <h6 class="fw-bold mb-3">Parent Details</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <input type="text" class="form-control" placeholder="Enter First Name">
            </div>
            <div class="col-md-6">
              <input type="text" class="form-control" placeholder="Enter Last Name">
            </div>
            <div class="col-md-6">
              <input type="text" class="form-control" placeholder="Enter First Name">
            </div>
            <div class="col-md-6">
              <input type="text" class="form-control" placeholder="Enter Last Name">
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Information -->
      <div class="col-lg-6">
        <div class="card p-4">
          <h6 class="fw-bold mb-3">Contact Information</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <input type="text" class="form-control" placeholder="Phone Number">
            </div>
            <div class="col-md-6">
              <input type="email" class="form-control" placeholder="Email Address">
            </div>
            <div class="col-md-12">
              <input type="text" class="form-control" placeholder="Home Address">
            </div>
            <div class="col-md-4">
              <input type="text" class="form-control" placeholder="Location">
            </div>
            <div class="col-md-4">
              <input type="text" class="form-control" placeholder="State">
            </div>
            <div class="col-md-4">
              <input type="text" class="form-control" placeholder="Zipcode">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}
  <script>
    // Optional: Show selected file name in the upload box
    const fileInput = document.getElementById("fileUpload");
    const uploadBox = document.querySelector(".upload-box");

    fileInput.addEventListener("change", function() {
      if (this.files && this.files[0]) {
        uploadBox.innerHTML = `<i class="fa-solid fa-file fa-2x mb-2"></i><p class="mb-0">${this.files[0].name}</p>`;
      }
    });
  </script>
  </main>
  @endsection
