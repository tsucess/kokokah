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
        <button  type = "button" class = "btn btn-outline-dark border-1 rounded me-4" style = "background: #004A53; color: #fff; width: 270px;">
            <i class="fa-solid fa-dot-circle me-2" ></i>
            Create New Subject
            <i class="fa fa-arrow-right me-2"></i>
        </button>


        <button class = "btn btn-light rounded w-25 me-4" href = "/subjectmedia" type = "button">
            <i class="fa-solid fa-dot-circle me-2"></i>
            Subject Media
            <i class="fa fa-arrow-right me-2"></i>
        </button>


        <button  type = "button" class = "btn btn-outline-dark border-1 rounded w-25 me-4" >
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

    <label for = "coursetitle"><b>Course Title</b></label>
    <div class="col-lg-12 col-md-12 col-12 mb-3">
      <input type="text" class="form-control"id = "coursetitle" placeholder="Course Title" name="coursetitle">
    </div>
</label>
</div>

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

</main>
@endsection
