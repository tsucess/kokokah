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


        <button class = "btn btn-light borderlessconnectorbutton" type = "button" href = "/subjectmedia">
            <i class="fa-solid fa-dot-circle me-2"></i>
            Subject Media
            <i class="fa fa-arrow-right me-2"></i>
        </button>


        <button class = "btn btn-light btn-outline-dark connectorbutton" type = "button">
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
    {{-- <button class="btn-nav-curriculum">
      <i class="fa fa-plus me-2"></i>Add New Topic
    </button> --}}

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

</main>
@endsection
