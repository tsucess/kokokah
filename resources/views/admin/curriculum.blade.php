@extends('layouts.dashboardtemp')

@section('content')
<main>
    <div class = "container">

        <div class = "d-flex justify-content-between">

            <div>
            <h4 class = "fw-bold">Create New Course</h4>
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
        <button  type = "button" class = "btn btn-link text-decoration-none border-1 rounded  connectlink" >
            <i class="fa-solid fa-dot-circle me-2"></i>
            Create New Subject
            <i class="fa fa-arrow-right me-1"></i>
        </button>


          <button  type = "button" class = "btn btn-link text-decoration-none border-1 rounded connectlink">
            <i class="fa-solid fa-dot-circle me-2"></i>
            Subject Media
            <i class="fa fa-arrow-right me-1"></i>
        </button>

         <button  type = "button" class = "btn btn-link text-decoration-none border-1 rounded me-4 connectlink">
            <i class="fa-solid fa-dot-circle me-2"></i>
            Curriculum
            <i class="fa fa-arrow-right me-1"></i>
        </button>

        <button  type = "button" class = "btn btn-outline-dark border-1 rounded w-25  connectlink">
            <i class="fa-solid fa-dot-circle me-2"></i>
             Publish Course
            <i class="fa fa-arrow-right me-1"></i>
        </button>


    </div>
    </div>

    <div class = "container">
        <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h5 class="fw-bold">Curriculum</h5>
      <p class="text-muted mb-0">Intro Course Overview Provide type (Mp4, Youtube,etc)</p>
    </div>
    {{-- <button class="btn btn-teal text-white rounded-pill px-4" style="background:#0d9488;">
      <i class="fa fa-plus me-2"></i>Create New Course
    </button> --}}

    <div>
    <button class="btn-nav-curriculum">
      <i class="fa fa-plus me-2"></i>Add New Topic
    </button>
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

        <button type="button" class="btn  btn-outline-dark btn-light" style="width: 150px;">
            <i class="fa-solid fa-plus"></i>
            Add Lesson
        </button>

    </div>


    {{-- <div class = "row float-end mx-auto">

        <div class="d-flex justify-content-end gap-3 px-0">
            <button class="btn-nav-secondary w-75">Previous</button>
            <button class="btn-nav-primary w-75">Continue</button>
                </div>
    </div> --}}

    <div class = "container">
    <div class="btn-row">
    <button class="btn btn-prev px-4 py-2">Previous</button>
    <button class="btn btn-continue px-4 py-2">Continue</button>
  </div>
    </div>

</main>
@endsection
