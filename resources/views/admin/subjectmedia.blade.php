@extends('layouts.dashboardtemp')

@section('content')
<main>
    <div class = "container">

        <div class = "d-flex justify-content-between">

            <div>
            <h3>Create New Course</h3>
            <p>Here overview of your</p>
        </div>

        <div>
        <button class = "btn rounded coursedraft">
        Save As Draft
        </button>

        <button class = "btn rounded publishcourse">
        Publish Course
        </button>

        </div>

    </div>
    </div>


    <div class = "container">

        <div class = "d-flex">
        <button  type = "button" class = "btn btn-outline-dark border-1 rounded w-25 me-4">
            <i class="fa-solid fa-dot-circle me-2"></i>
            Course Media
            <i class="fa fa-arrow-right me-2"></i>
        </button>


        <button class = "btn btn-light rounded w-25 me-4">
            <i class="fa-solid fa-dot-circle me-2"></i>
            Course Media
            <i class="fa fa-arrow-right me-2"></i>
        </button>

        <button  type = "button" class = "btn btn-outline-dark border-1 rounded w-25 me-4" style = "background: #004A53; color: #fff;">
            <i class="fa-solid fa-dot-circle me-2"></i>
            Curriculum
            <i class="fa fa-arrow-right me-2"></i>
        </button>

        <button  type = "button" class = "btn btn-outline-dark border-1 rounded w-25 me-4">
            <i class="fa-solid fa-dot-circle me-2"></i>
            Additional Information
            <i class="fa fa-arrow-right me-2"></i>
        </button>


    </div>
    </div>

    <div class = "container-fluid">
    <div class = "row">
        <h5>Course Media</h5>
        <p class = "text-muted">Intro Course Overview Provide type (Mp4, Youtube,etc)</p>

    {{-- <div>
    <label for="formFileLg" class="form-label">Course Thumbnail(required)
    <input class="form-control form-control-lg required" id="formFileLg" type="file">
  </label>
</div> --}}




{{-- <div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card p-4 custom-upload-card">
        <div class="card-body text-center">
          <label for="fileInput" class="w-100 h-100 d-flex flex-column align-items-center justify-content-center custom-file-dropzone">
            <i class="bi bi-cloud-upload display-4"></i>
            <p class="mt-2 mb-0 text-muted">Click or drag a file to upload</p>
          </label>

          <input type="file" id="fileInput" name="file" class="d-none">
        </div>
      </div>

      <div class="input-group mt-3">
        <input type="text" id="fileNameDisplay" class="form-control" placeholder="No file selected..." aria-label="File name" readonly>
        <button class="btn btn-primary" type="button" id="uploadButton">
          Upload File
        </button>
      </div>
    </div>
  </div>
</div> --}}



<div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12 col-sm-12">


                    <form id="uploadForm bg-primary">
                        <!-- File Name Display and Upload Button -->


<div class="d-flex mb-3 w-100">
    <input type="text" class="form-control file-name-input flex-grow-1" id="fileNameDisplay" placeholder="No file selected" readonly>
    <button class="btn btn-success upload-btn w-25" type="button" id="uploadButton">
        Upload File
    </button>
</div>


                        <!-- File Upload Area -->
                        <div class="mb-3">
                            <label for="fileInput" class="upload-area">
                                <i class="fas fa-file-circle-check "></i>
                                <h5>Upload Image</h5>
                                <p>PNG, JPEG, GIF (max 2mb size)</p>
                            </label>
                            <input type="file" id="fileInput" name="file" class="d-none">
                        </div>


                    </form>
                    <div id="message" class="text-center text-success fw-bold"></div>

            </div>
        </div>
    </div>

<div class = "container">
    <div class = "d-flex justify-content-end gap-2">
    <button class = "btn btn-outline-success  w-25 text-success" >Cancel</button>
    <button class = "btn text-white p-3 w-25 navButton" style = "background:#004A53;">Continue</button>
</div>
    </div>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('fileInput');
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            const uploadButton = document.getElementById('uploadButton');
            const messageElement = document.getElementById('message');

            // Listen for changes on the hidden file input
            fileInput.addEventListener('change', function(e) {
                if (this.files && this.files.length > 0) {
                    fileNameDisplay.value = this.files[0].name;
                    messageElement.textContent = '';
                } else {
                    fileNameDisplay.value = 'No file selected...';
                }
            });

            // Handle the upload button click
            uploadButton.addEventListener('click', function() {
                if (fileInput.files.length > 0) {
                    // Placeholder for actual upload logic
                    messageElement.textContent = 'File "' + fileInput.files[0].name + '" is ready for upload!';
                } else {
                    messageElement.textContent = 'Please select a file first.';
                }
            });
        });
    </script>

    </div>
    </div>


</main>
@endsection
