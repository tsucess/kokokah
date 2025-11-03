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

<div class = "container">

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
<div class = "d-flex col-md-12 col-lg-12 justify-content-end ">
    <div class = "d-flex flex-column flex-lg-row gap-3 px-0">
          <button class="btn btn-nav-secondary" style="padding: 12px 120px;">Cancel</button>
          <button class="btn btn-nav-primary" style="padding: 12px 120px;">Continue</button>
        </div>
</div>
</div>

</div>


</main>
@endsection
