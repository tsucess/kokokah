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
                <button type = "button" class = "btn btn-outline-dark border-1 rounded me-4"
                    style = "background: #004A53; color: #fff; width: 270px;">
                    <i class="fa-solid fa-dot-circle me-2"></i>
                    Create New Subject
                    <i class="fa fa-arrow-right me-2"></i>
                </button>


                <button class = "btn btn-light rounded w-25 me-4" href = "/subjectmedia" type = "button">
                    <i class="fa-solid fa-dot-circle me-2"></i>
                    Subject Media
                    <i class="fa fa-arrow-right me-2"></i>
                </button>


                <button type = "button" class = "btn btn-outline-dark border-1 rounded w-25 me-4">
                    <i class="fa-solid fa-dot-circle me-2"></i>
                    Curriculum
                    <i class="fa fa-arrow-right me-2"></i>
                </button>

                <button type = "button" class = "btn btn-outline-dark border-1 rounded w-25 me-4">
                    <i class="fa-solid fa-dot-circle me-2"></i>
                    Additional Information
                    <i class="fa fa-arrow-right me-2"></i>
                </button>


            </div>

        </div>

        <div class = "container">

            <div class = "row">
                <div class = "mb-2  border border-bottom-1 border-top-0 border-start-0 border-end-0">
                    <h5>
                        Course Details
                    </h5>
                </div>
            </div>

            <form>
                <div class="row mt-3">

        <div class = "d-flex gap-4 justify-content-space-between">
        <button  type = "button" class = "btn btn-light btn-outline-dark border-1 rounded me-3 connectorbutton" data-section="details">
            <i class="fa-solid fa-dot-circle me-2" ></i>
            Create New Subject &nbsp;
            <i class="fa fa-arrow-right me-2"></i>
        </button>

                <div class = "row">
                    <div class="col">
                        <label for = "course-category"><b>Course Category</b></label>
                        <select class="form-select form-select-sm" id = "course-category" aria-label="Small select example">
                            <option selected>select course category</option>
                            <option value="1">Category One</option>
                            <option value="2">Category Two</option>
                            <option value="3">Category Three</option>
                        </select>
                    </div>

                    <div class="col">
                        <label for = "course-level"><b>Course level</b></label>
                        <select class="form-select form-select-sm " id = "course-level" aria-label="Small select example">
                            <option selected>select course level</option>
                            <option value="1">Level One</option>
                            <option value="2">Level Two</option>
                            <option value="3">Level Three</option>
                        </select>
                    </div>

                </div>

                <div class = "row mt-2">
                    <div class="col">
                        <label for="exampleDateInput" class="form-label"><b>Select Date</b></label>
                        <input type="date" class="form-control" id="exampleDateInput">
                    </div>

                    <div class="col">
                        <label for="lesson" class="form-label"><b>Total Lesson</b></label>
                        <input type="number" class="form-control" id="lesson">
                    </div>

                </div>

        </div>
        </form>

        </div>


<!-- New Course Details Section -->
<div class = "container content-section" id="details">

<div class = "row">
<div class = "mb-2  border border-bottom-1 border-top-0 border-start-0 border-end-0">
<h5>
Course Details
</h5>
</div>
</div>




  <div class="row mt-3">


<form>

<div class="custom-form-group">

                    <label for="coursetitle" class="custom-label">Subject Title</label>

                    <input type="text" class="form-control-custom" id="subjectTitle" name="subjectTitle" placeholder="Enter Subject Title" aria-label="Subject Title" autocomplete="subject" required>
</div>

<div class = "d-flex gap-2">
<div class="w-50 custom-form-group">
              <label for="role" class="custom-label">Subject Category</label>
              <select class="form-control-custom" id="role" name="role" aria-label="User Role" required>
                <option value="">Subject Category</option>
                <option value="student">Science</option>
                <option value="instructor">Art</option>
                <option value="instructor">Commercial</option>
              </select>
</div>


<div class="w-50 custom-form-group">
              <label for="role" class="custom-label">Subject Level</label>
              <select class="form-control-custom" id="role" name="role" aria-label="User Role" required>
                <option value="">JSS 1</option>
                <option value="student">JSS 2</option>
                <option value="instructor">JSS 3</option>
                <option value="instructor">SS 1</option>
                <option value="instructor">SS 2</option>
                <option value="instructor">SS 3</option>
              </select>
    </div>
</div>

<div class = "d-flex gap-2">
<div class="w-50 custom-form-group">

                    <label for="subjecttime" class="custom-label">Subject Time</label>

                    <input type="text" class="form-control-custom" id="subjectTime" name="subjectTime" placeholder="Enter Subject Time" aria-label="Subject Time" autocomplete="subject time" required>
</div>

<div class="w-50 custom-form-group">

                    <label for="totallesson" class="custom-label">Total Lesson</label>

                    <input type="text" class="form-control-custom" id="totallesson" name="totallesson" placeholder="Enter Total Lesson" aria-label="Total Lesson" autocomplete="total lesson" required>
</div>
</div>

</form>
</div>

<div class = "row">
    <div class = "col col-md-12 col-lg-12">
        <p><b>Subject Description</b></p>

        <div class="dropdown">
  <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    15
  </button>
 <span><i class="fa-solid fa-bold"></i></span>
 <span><i class="ms-2 fa-solid fa-italic"></i></span>
 <span><i class="ms-2 fa-solid fa-underline"></i></span>
 <span><i class="ms-2 fa-solid fa-strikethrough"></i></span>
 <span><i class="ms-2 fa-solid fa-file-arrow-up"></i></span>

    <div class="mt-2 mb-2">
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="6" placeholder="Write subject description here..."></textarea>
</div>

    </div>
</div>
</div>



<div class = "row mt-3">
<div class = "d-flex col-md-12 col-lg-12 justify-content-end">
    <div class = "d-flex flex-column flex-lg-row gap-3 px-0">
          <button class="btn btn-nav-secondary" style="padding: 12px 120px;">Cancel</button>
          <button class="btn btn-nav-primary" style="padding: 12px 120px;">Continue</button>
        </div>
</div>
</div>

</div>



<!-- New Subject Media Section -->
 <div class = "container-fluid" id="media" class="content-section d-none">
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
    </div>
    </div>


    <!-- Curriculum Section -->
        <div class = "container content-section d-none" id="curriculum">
        <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h5 class="fw-bold">Curriculum</h5>
      <p class="text-muted mb-0">Intro Course Overview Provide type (Mp4, Youtube,etc)</p>
    </div>

        <button class="btn btn-nav-primary"><i class="fa-solid fa-plus me-2"></i> Add New Topic</button>
    </div>

  </div>
    </div>

    <div class = "container">

 <div class="accordion" id="accordionExample">
  <div class="accordion-item">
    <h5 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="border: none;">
       <i class="fa-solid fa-book-open me-2"></i>  Parts of Speech
      </button>
    </h5>
    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <strong>This is the first item’s accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element.
      </div>
    </div>
  </div>

        </div>

        <div class = "d-flex justify-content-between border border-2 rounded p-3 mt-3 mb-2">

            <div>
                <i class="fa-solid fa-circle-play"></i> Nouns
            </div>

            <div>
                <i class="fa-solid fa-pen-to-square"></i>
                <i class="fa-solid fa-trash"></i>
            </div>
        </div>

                <div class = "d-flex justify-content-between border border-2 rounded p-3 mt-3 mb-2">

            <div>
                <i class="fa-solid fa-circle-play"></i> Pronoun
            </div>

            <div>
                <i class="fa-solid fa-pen-to-square"></i>
                <i class="fa-solid fa-trash"></i>
            </div>
        </div>

        <button type="button" class="btn  btn-outline-dark btn-light" style="width: 150px;">
            <i class="fa-solid fa-plus"></i>
            Add Lesson
        </button>

    </div>




<div class = "row mt-3">
<div class = "d-flex col-md-12 col-lg-12 justify-content-end ">
    <div class = "d-flex flex-column flex-lg-row gap-3 px-0">
          <button class="btn btn-nav-secondary" style="padding: 12px 120px;">Previous</button>
          <button class="btn btn-nav-primary" style="padding: 12px 120px;">Continue</button>
        </div>
</div>
</div>



    <div class = "container content-section d-none" id="publish">
 <section class="subject-overview-card">
        <h3 class="subject-overview-title">Subject Overview </h3>
        <div class="d-flex flex-column gap-3">
            <section class="d-flex flex-column gap">
                <header class="d-flex justify-content-between gap-2 align-items-center ">
                    <div class="d-flex flex-column gap-3">
                        <h4 class="subject-text">English Language</h4>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="d-flex aling-items-center gap-1"><i class="fa-solid fa-book" style="color: #000000;"></i> <span class="subject-subtext">11 Topics</span></div>
                            <div class="d-flex aling-items-center gap-1"><i class="fa-solid fa-circle-play" style="color: #000000;"></i> <span class="subject-subtext">11 Lesson</span></div>
                            <div class="d-flex aling-items-center gap-1"><i class="fa-solid fa-question" style="color: #000000;"></i> <span class="subject-subtext">10 Quiz</span></div>
                            <div class="d-flex aling-items-center gap-1"><i class="fa-solid fa-clock" style="color: #000000;"></i> <span class="subject-subtext">03:50:00 Hours</span></div>
                            <div class="d-flex aling-items-center gap-1"><i class="fa-solid fa-landmark" style="color: #000000;"></i> <span class="subject-subtext">Jss 1</span></div>
                        </div>

                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <button><i class="fa-solid fa-circle-check"></i></button>
                        <button><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    </div>
                </header>
                <div><img src="images/publish.png" alt="" class="subject-overview-img"></div>
            </section>
            <article class="d-flex flex-column subject-overview-description-container">
                <h4 class="subject-overview-description-title">Subject Description</h4>
                <div class="d-flex gap-2 flex-column">
                    <p class="subject-overview-description-text">English Language is designed to equip learners with the skills needed to communicate effectively in both spoken and written forms. The subject focuses on grammar, vocabulary, comprehension, writing, and oral communication. Learners
                        will develop the ability to read with understanding, write clearly and creatively, listen attentively, and speak confidently in different contexts. Through interactive lessons, practice exercises, and assessments, students will
                        build a strong foundation in grammar and vocabulary, improve their reading and writing skills, and learn to express themselves fluently and accurately. By the end of the course, learners will not only master the technical aspects
                        of English but also gain the confidence to apply the language in academic, professional, and everyday life. </p>

                    <div>
                        <p class="subject-overview-study-list">Key Areas of Study:</p>
                        <ul>
                            <li>Grammar and Structure </li>
                            <li>Vocabulary Development</li>
                            <li>Reading Comprehension</li>
                            <li>Writing Skills (letters, essays, reports, creative writing)</li>
                            <li>Speaking and Listening Skills </li>
                            <li>Literature Appreciation (poetry, prose, drama)</li>
                        </ul>
                    </div>

                </div>

            </article>
            <section class="d-flex flex-column curriculum-container">
                <h4 class="subject-overview-description-title">Curriculum</h4>
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between align-items-center gap-2 speech-card">
                        <div class="d-flex gap-3 align-items-center">
                            <i class="fa-solid fa-book-open-reader"></i>
                            <div class="d-flex flex-column gap-1">
                                <h5 class="speech-card-title">Parts of Speech</h5>
                                <div class="d-flex gap-1 align-items-center">
                                    <div class="d-flex aling-items-center gap-1"><i class="fa-solid fa-circle-play" style="color: #000000;"></i> <span class="subject-subtext">11 Lesson</span></div>
                                    <div class="d-flex aling-items-center gap-1"><i class="fa-solid fa-question" style="color: #000000;"></i> <span class="subject-subtext">10 Quiz</span></div>

                                </div>
                            </div>
                        </div>
                        <i class="fa-solid fa-circle-check" style="color: #004A53;"></i>
                    </div>
                </div>

            </section>
        </div>
    </section>

</div>

<div class = "row  mt-4">
<div class = "d-flex col-md-12 col-lg-12 justify-content-end ">
    <div class = "d-flex flex-column flex-lg-row gap-3 px-0">
          <button class="btn btn-nav-secondary" style="padding: 12px 120px;">Back</button>
          <button class="btn btn-nav-primary" style="padding: 12px 120px;">Publish Now</button>
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


        <!-- Navigating across the connectors -->
        document.querySelectorAll('[data-section]').forEach(btn => {
    btn.addEventListener('click', function() {
      // Remove active state from all buttons
      document.querySelectorAll('[data-section]').forEach(b => b.classList.remove('connectorbutton'));

      // Add active to clicked one
      this.classList.add('connectorbutton');

      // Hide all content sections
      document.querySelectorAll('.content-section').forEach(sec => sec.classList.add('d-none'));

      // Show selected section
      document.getElementById(this.dataset.section).classList.remove('d-none');
    });
  });
    </script>
</main>
@endsection
