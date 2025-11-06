@extends('layouts.dashboardtemp')

@section('content')
<main>
<div class = "container">

        <div class = "d-flex justify-content-between">

            <div>
            <h4 class = "fw-bold">Create New Subject</h4>
            <p>Here overview of your</p>
        </div>

        <div class = "d-flex gap-4">
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

        <div class = "d-flex gap-4 justify-content-space-between">
        <button  type = "button" class = "btn btn-light borderlessconnectorbutton">
            <i class="fa-solid fa-dot-circle me-2" ></i>
            Create New Subject &nbsp;
            <i class="fa fa-arrow-right me-2"></i>
        </button>


        <button class = "btn btn-light btn-outline-dark connectorbutton" type = "button" href = "/subjectmedia">
            <i class="fa-solid fa-dot-circle me-2"></i>
            Subject Media
            <i class="fa fa-arrow-right me-2"></i>
        </button>


        <button class = "btn btn-light borderlessconnectorbutton" type = "button">
            <i class="fa-solid fa-dot-circle me-2"></i>
            Curriculum
            <i class="fa fa-arrow-right me-2"></i>
        </button>

        <button class = "btn btn-light borderlessconnectorbutton" type = "button">
            <i class="fa-solid fa-dot-circle me-2"></i>
             Publish Subject
            <i class="fa fa-arrow-right me-2"></i>
        </button>


    </div>

</div>

    <div class = "container-fluid">
    <div class = "row">
        <h5>Course Media</h5>
        <p class = "text-muted">Intro Course Overview Provide type (Mp4, Youtube,etc)</p>


<div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12 col-sm-12">


                    <form id="uploadForm bg-primary">
                        <!-- File Name Display and Upload Button -->


<div class="d-flex mb-3 w-100">
    <input type="text" class="form-control file-name-input flex-grow-1" id="fileNameDisplay" placeholder="No file selected" readonly>
        <button class="btn btn-nav-primary" style="padding: 12px 120px;">Upload File</button>
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

<div class = "row mt-3">
<div class = "d-flex col-md-12 col-lg-12 justify-content-end ">
    <div class = "d-flex flex-column flex-lg-row gap-3 px-0">
          <button class="btn btn-nav-secondary" style="padding: 12px 120px;">Cancel</button>
          <button class="btn btn-nav-primary" style="padding: 12px 120px;">Continue</button>
        </div>
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
