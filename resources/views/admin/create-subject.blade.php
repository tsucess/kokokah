@extends('layouts.dashboardtemp')

<!-- Optional styling -->
<style>
  .active-tab {
    background-color: #FFC107;
    color: #000;
    border-color: #FFC107;
  }
</style>


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
        <button  type = "button" class = "btn btn-light btn-outline-dark border-1 rounded me-3 connectorbutton">
            <i class="fa-solid fa-dot-circle me-2" ></i>
            Create New Subject &nbsp;
            <i class="fa fa-arrow-right me-2"></i>
        </button>


        <button class = "btn btn-light borderlessconnectorbutton" type = "button" href = "/subjectmedia">
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


<!-- bringing in the new code gotten -->

<div class="container py-4">
  <h3>Create New Subject</h3>
  <p>Here overview of your</p>

  <!-- Step Navigation -->
  <div class="d-flex gap-3 mb-4">
    <button class="btn btn-outline-dark active-tab" data-section="details">Create New Subject</button>
    <button class="btn btn-outline-dark" data-section="media">Subject Media</button>
    <button class="btn btn-outline-dark" data-section="curriculum">Curriculum</button>
    <button class="btn btn-outline-dark" data-section="publish">Publish Subject</button>
  </div>

  <!-- SECTION CONTENT -->
  <div id="section-content">
    <!-- Course Details -->
    <div id="details" class="content-section">
      <h5>Course Details</h5>
      <form>
        <div class="row mb-3">
          <div class="col-md-6">
            <label>Subject Title</label>
            <input type="text" class="form-control">
          </div>
          <div class="col-md-6">
            <label>Subject Level</label>
            <select class="form-select">
              <option>Jss1</option>
              <option>Jss2</option>
            </select>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label>Course Time</label>
            <input type="text" class="form-control">
          </div>
          <div class="col-md-6">
            <label>Total Lesson</label>
            <input type="number" class="form-control">
          </div>
        </div>
        <div class="mb-3">
          <label>Subject Description</label>
          <textarea class="form-control" rows="4"></textarea>
        </div>
        <div class="d-flex justify-content-end gap-2">
          <button class="btn btn-outline-dark">Cancel</button>
          <button class="btn btn-warning">Continue</button>
        </div>
      </form>
    </div>

    <!-- Subject Media -->
    <div id="media" class="content-section d-none">
      <h5>Subject Media</h5>
      <p>Upload course image, banner, and video preview here.</p>
      <input type="file" class="form-control w-50 mb-3">
      <button class="btn btn-warning">Upload</button>
    </div>

    <!-- Curriculum -->
    <div id="curriculum" class="content-section d-none">
      <h5>Curriculum</h5>
      <p>Add or organize lessons, topics, and modules for this subject.</p>
      <button class="btn btn-primary">Add Lesson</button>
    </div>

    <!-- Publish Subject -->
    <div id="publish" class="content-section d-none">
      <h5>Publish Subject</h5>
      <p>Review all details before publishing.</p>
      <button class="btn btn-success">Publish Course</button>
    </div>
  </div>
</div>

<!-- JavaScript -->
<script>
  document.querySelectorAll('[data-section]').forEach(btn => {
    btn.addEventListener('click', function() {
      // Remove active state from all buttons
      document.querySelectorAll('[data-section]').forEach(b => b.classList.remove('active-tab'));

      // Add active to clicked one
      this.classList.add('active-tab');

      // Hide all content sections
      document.querySelectorAll('.content-section').forEach(sec => sec.classList.add('d-none'));

      // Show selected section
      document.getElementById(this.dataset.section).classList.remove('d-none');
    });
  });
</script>


</main>
@endsection
