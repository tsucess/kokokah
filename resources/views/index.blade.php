@extends('layouts.template')

@section('content')
<div class = "container-fluid p-5" style = "background: bg-green;">
<div class = "row mt-4">
<div class = "col-12 col-md-6 col-lg-6 my-auto">
<img src = "images/Platform.png" class = "img-fluid">
<p class = "mt-3">
    Kokokah combines School Management, Exam Prep, and a
    Learning Management System (LMS)—helping schools
    automate admin tasks, boost student performance, and
    deliver modern digital learning in one seamless platform.
</p>

<div class = "d-flex flex-column flex-sm-row gap-2">
    <button class = "btn text-white ps-4 pe-5 w-100 w-sm-auto" style = "background:#004A53;">Explore Kokokah Features</button>
    <button class = "btn btn-outline-success text-success ps-5 pe-5 w-100 w-sm-auto" >Get a Demo</button>
</div>


{{-- <div class="d-flex mt-4 justify-content-start">
<div class="rounded-circle d-flex align-items-center justify-content-center me-3  flex-shrink-0"
           style="width:50px; height:50px; background-color:#d3e3e3;">
        <i class="fa-solid fa-users" style="color:#004A53; font-size:24px;"></i>
      </div>
      <div class = "pe-2">
        <h6 class="mb-1">100,000</h6>
        <p class="mb-0 small">Users</p>
      </div>


      <div class="rounded-circle d-flex align-items-center justify-content-center m-1 mx-auto"
           style="width:50px; height:50px; background-color:#d3e3e3;">
        <i class="fa-solid fa-book-open" style="color:#004A53; font-size:24px;"></i>
      </div>
      <div class = "ms-2 pe-2" style = "width:150px;">
        <h6 class="mb-1">100,000</h6>
        <p class="mb-0 small">Learning Materials</p>
      </div>

       <div class="rounded-circle d-flex align-items-center justify-content-center m-1 mx-auto"
           style="width:50px; height:50px; background-color:#d3e3e3;">
        <i class="fa-solid fa-users" style="color:#004A53; font-size:24px;"></i>
      </div>
      <div class = "ms-2 pe-2">
        <h6 class="mb-1">100,000</h6>
        <p class="mb-0 small">Users</p>
      </div>

      <div class="rounded-circle d-flex align-items-center justify-content-center m-1 mx-auto"
           style="width:50px; height:50px; background-color:#d3e3e3;">
        <i class="fa-solid fa-book-open" style="color:#004A53; font-size:24px;"></i>
      </div>
      <div class = "ms-2 pe-2" style = "width:150px;">
        <h6 class="mb-1">100,000</h6>
        <p class="mb-0 small">Learning Materials</p>
      </div>

    </div> --}}

</div>


<div class = "col-12 col-md-6 col-lg-6">
<img src = "images/LMS.png" class = "img-fluid">
</div>

</div>
</div>

<div class = "container-fluid mb-5">
<div class = "row mt-4 bg-white">
<div class = "col-12 col-md-6 col-lg-6 my-auto">
<img src = "images/Video.png" class = "img-fluid">
</div>


<div class = "col-12 col-md-6 col-lg-6 my-auto">
    <h2>
        <span class = "fs-1 fw-bold" style = "color:#004A53;">Kokokah for</span> <span class = "fw-bold" style = "color: #F56824;">All.</span>
    </h2>
    <p>
        Kokokah combines School Management, Exam Prep, and a
        Learning Management System (LMS)—helping schools automate admin tasks,
        boost student performance, and deliver modern digital learning in one seamless platform.
        Kokokah combines School Management, Exam Prep, and a
        Learning Management System (LMS)—helping schools
        automate admin tasks, boost student performance, and deliver modern
        digital learning in one seamless platform.
    </p>
    <button class = "btn text-white ps-4 pe-5" style = "background:#004A53;">Discover Kokokah</button>
</div>

</div>
</div>


<div class = "container-fluid">
    <div class = "row d-flex justify-content-between">

        <div class = "col-12 col-md-3 col-lg-3">
    </div>

    <div class = "col-12 col-md-6 col-lg-6 text-center">
        {{-- <img src = "images/Kokokah_works.png" class = "img-fluid w-50"> --}}
        <h2>
        <span class = "fs-1 fw-bold" style = "color:#004A53;">Why Kokokah</span> <span class = "fw-bold" style = "color: #F56824;">Works</span>
    </h2>
        <p>
            Kokokah combines School Management, Exam Prep,
            and a Learning Management System (LMS)—helping schools automate
            admin tasks, boost student performance
        </p>
    </div>

    <div class = "col-12 col-md-3 col-lg-3">
        <img src = "images/trace1.png" class = "img-fluid w-25 float-end">
    </div>
</div>


<div class = "row d-flex justify-content-between">

        <div class = "col-12 col-md-3 col-lg-3">
    </div>

    <div class = "d-flex justify-content-between col-12 col-md-6 col-lg-6">
        <div class ="w-75">
            <i class="fa-light fa-download"></i>
            <h6 class = "h4 text-success fw-bold" style = "font-size:16px;">
                All-in-one Efficiency
            </h6>
            <p>
                Manage academics, operations, and digital learning in one place.
            </p>
        </div>

        <div class ="w-75">
            <i class="fa-light fa-download"></i>
            <h6 class = "h4 text-success fw-bold" style = "font-size:16px;">
                Built for African Schools
            </h6>
            <p>
                Offline access, SMS alerts, and flexible pricing.
            </p>
        </div>


        <div class ="w-75">
            <i class="fa-light fa-download"></i>
            <h6 class = "h4 text-success fw-bold" style = "font-size:16px;">
                Proven Results
            </h6>
            <p>
                Improve student performance and streamline admin work.
            </p>
        </div>

    </div>


    <div class = "col-12 col-md-3 col-lg-3">
    </div>

</div>


<div class = "row d-flex justify-content-between">

        <div class = "col-12 col-md-3 col-lg-3">
            <img src = "images/Kokokah_works2.png" class = "p-2 img-fluid w-25">
    </div>

            <div class = "col-12 col-md-6 col-lg-6">
    </div>

            <div class = "col-12 col-md-3 col-lg-3">
    </div>
</div>

<div class = "row">
    <div class = "col col-md-2 col-lg-2">

    </div>

    <div class = "col col-md-8 col-lg-8 text-center">
    <h2 class = "fw-bold" style = "color:#004A53;" >Kokokah for
    <span class = "fw-bold" style = "color: #F56824;">You.</span>
    </h2>
    <p>
        Kokokah combines School Management, Exam Prep, and a Learning Management System (LMS)—helping
        schools automate admin tasks, boost student performance, and deliver modern digital learning
        in one seamless platform. Kokokah combines School Management, Exam Prep, and a Learning
        Management System (LMS)—helping schools automate admin tasks,
        boost student performance, and deliver modern digital learning
        in one seamless platform.
    </p>
    <button class = "btn text-white ps-4 pe-5" style = "background:#004A53;">Explore Features</button>
</div>

<div class = "col col-md-2 col-lg-2">
    <img src = "images/Ellipse 24.png" class = "img-fluid w-25 float-end" />
    </div>


</div>


<div class = "row d-flex justify-content-between">

        <div class = "col-12 col-md-3 col-lg-3">
            <img src = "images/Ellipse 23.png" class = "p-2 img-fluid w-25">
    </div>

            <div class = "col-12 col-md-6 col-lg-6">
    </div>

            <div class = "col-12 col-md-3 col-lg-3">
    </div>
</div>

<div class="row w-100 mx-auto my-4 p-4"
     style="background: #CCDBDD; border-radius: 30px;">

    <!-- Text Section -->
    <div class="col-12 col-md-6 d-flex flex-column justify-content-center text-center p-3">
        <h2 class="fw-bold fs-1" style="color: #004A53;">
            Passnownow <span style="color: #F56824;">(Exam Prep)</span>
        </h2>
        <p class="fs-5">
            Ready-made question banks and secure online testing.
        </p>
    </div>

    <!-- Image Section -->
    <div class="col-12 col-md-6 text-center p-3">
        <img src="images/exam_prep.png" class="img-fluid w-75" alt="Exam Prep">
    </div>
</div>


<div class="row w-100 mx-auto my-4 p-2 border border-dark" style="border-radius: 30px;">

    <!-- Image Section -->
    <div class="col-12 col-md-6 text-center p-3">
        <img src="images/School Admin.png" class="img-fluid w-75" alt="School Admin">
    </div>

    <!-- Text Section -->
    <div class="col-12 col-md-6 d-flex flex-column justify-content-center text-center p-3">
        <h2 class="fw-bold fs-1" style="color: #004A53;">
            School Admin <span style="color: #F56824;">Tools</span>
        </h2>
        <p class="fs-5">
            Admissions, fees, attendance & timetables simplified.
        </p>
    </div>
</div>



<div class="row w-100 mx-auto my-4 p-4"
     style="background: #CCDBDD; border-radius: 30px;">

    <!-- Text Section -->
    <div class="col-12 col-md-6 d-flex flex-column justify-content-center text-center p-3">
        <h2 class="fw-bold fs-1" style="color: #004A53;">
            LMS for Teaching & <span style="color: #F56824;">Learning</span>
        </h2>
        <p class="fs-5">
            Digital classrooms, assignments & progress tracking.
        </p>
    </div>

    <!-- Image Section -->
    <div class="col-12 col-md-6 text-center p-3">
        <img src="images/lms system.png" class="img-fluid w-75" alt="LMS system">
    </div>
</div>

</div>


@endsection
