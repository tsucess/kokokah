@extends('layouts.template' )
@section('content')
<div class="container-fluid banner">
    <div class="row align-items-center">
        <div class="col-12 col-md-7 col-lg-7 p-5 ">
            <h2>Build your Child's Future with STEM</h2>

            <p>
                Description about STEM camps, summer
                schools, and tech-powered programs to ignite creativity
                and problem-solving in science, technology, engineering, and
                mathematics.
            </p>
                <div>
                <button class="btn btn-block w-100 primaryButton" style="background: #004A53;">Register Here</button>
                {{-- <button class="btn btn-outline-dark btn-lg">Request a Demo</button> --}}
            </div>

                        <div class="row mt-4 align-items-center">
                <div class="col-sm-3 text-center">
                    <img src = "images/stem_image.png" class = "img-fluid">
                </div>

                <div class="col-sm-3 text-center">
                    <h5 class="fw-bold">2947</h5>
                    <p class="text-muted">Happy Students</p>
                </div>
                <div class="col-sm-3 text-center">
                    <h5 class="fw-bold">8</h5>
                    <p class="text-muted">Recent Award</p>
                </div>
                <div class="col-sm-3 text-center">
                    <h5 class="fw-bold">1700+</h5>
                    <p class="text-muted">Happy Parents</p>
                </div>
            </div>

        </div>

        <div class="col-12 col-md-5 col-lg-5 text-center p-5">
            <img src="images/stem.png" alt="stem illustration" class="img-fluid ">
        </div>
    </div>
</div>


<div class="container mx-auto my-5">

    <div class="row align-items-center">

        <div class="col-lg-4 mb-4 mb-lg-0 h-25 ">
            <div class="row gx-2">
                <div class="col-6"><img src="images/stem1.png" alt="" class="img-fluid rounded"></div>
                <div class="col-6"><img src="images/stem2.png" alt="" class="img-fluid rounded"></div>
                <div class="col-6"><img src="images/stem3.png" alt="" class="img-fluid rounded"></div>
                <div class="col-6"><img src="images/stem4.png" alt="" class="img-fluid rounded"></div>
            </div>
        </div>


        <div class="col-12 col-md-8 col-lg-8">
            <h6>WHY CHOOSE US</h6>
            <h1 class = "fw-bold">
                Why STEM Labs is <br>
                the Perfect Choice
            </h1>
            <p>
                The world is driven by innovation — and Africa’s future leaders must be ready to
                create, not just consume.<br>
                 STEM Labs by Kokokah blends physical, in-person
                experiences with our powerful digital tools to give <br>
                secondary school students an edge in STEM fields for a brighter future.<br>
                Through boot camps, summer schools, workshops, and mentorship programs, students:
            </p>
            <ul class="list-unstyled">
                <li class="mb-2"><i class="fa-solid fa-circle-arrow-right" style="color:#F56824;"></i> <b>Build real-world projects in coding, robotics, and engineering</b></li>
                <li class="mb-2"><i class="fa-solid fa-circle-arrow-right" style="color:#F56824;"></i> <b>Strengthen problem-solving, collaboration, and critical thinking skills</b></li>
                <li class="mb-2"><i class="fa-solid fa-circle-arrow-right" style="color:#F56824;"></i> <b>Connect classroom theory with hands-on experiments</b></li>
                <li class="mb-2"><i class="fa-solid fa-circle-arrow-right" style="color:#F56824;"></i> <b>Learn directly from STEM professionals and industry mentors</b></li>
            </ul>
        </div>

    </div>
</div>





<!-- Programs Section -->
<div class="container my-5 text-center position-relative">
  <h2 class="mb-5 fw-bold">Programs We Offer</h2>

  <!-- Central Image -->
  <div class="position-absolute top-50 start-50 translate-middle d-none d-md-block" style="z-index: 3;">
    <div class=" rounded-3  d-flex align-items-center justify-content-center" style="width: 150px; height: 140px; padding-top:80px;">
      <img src="images/stems.png" alt="Central Illustration" class="img-fluid" style="width: 100px;"/>
    </div>
  </div>

  <!-- Grid of Cards -->
  <div class="row justify-content-center g-4 position-relative" style="z-index: 1; ">
    <div class="col-12 col-md-5 col-lg-5">
      <div class="card text-start p-4 text-white justify-content-center border-0" style="background-color: #2B6870; border-radius: 15px; height: 250px;">
        <h6 class="text-white fw-bold">STEM Boot Camps</h6>
        <p class = "text-white">Intensive short-term programs during school breaks</p>
      </div>
    </div>

    <div class="col-12 col-md-5 col-lg-5">
      <div class="card text-start p-4 justify-content-center text-white border-0" style="background-color: #CC571E; border-radius: 15px; height: 250px;">
        <h6 class="text-white fw-bold">STEM Boot Camps</h6>
        <p class = "text-white">Intensive short-term programs during school breaks</p>
      </div>
    </div>

    <div class="col-12 col-md-5 col-lg-5">
      <div class="card text-start p-4 text-white justify-content-center border-0" style="background-color: #FAB391; border-radius: 15px; height: 250px;">
        <h6 class="text-dark fw-bold">STEM Boot Camps</h6>
        <p class = "text-dark">Intensive short-term programs during school breaks</p>
      </div>
    </div>

    <div class="col-12 col-md-5 col-lg-5">
      <div class="card text-start p-4 text-white justify-content-center border-0" style="background-color: #FDAF22; border-radius: 15px; height:250px">
        <h6 class="text-dark fw-bold">STEM Boot Camps</h6>
        <p class = "text-dark">Intensive short-term programs during school breaks</p>
      </div>
    </div>
  </div>
</div>



<div class="container-fluid" >
    <div class = "d-flex justify-content-between" style="width:100%;">

<div class = "col-12 col-md-8 col-lg-8 p-5">
    <p style="color:#004A53;">WHO CAN JOIN</p>
    <h5>
        JSS1 to SSS3 students passionate about science, technology, or<br>
        engineering — no prior experience needed! We design beginner-<br>
        friendly
        and advanced tracks to meet every student where they are.
    </h5>
</div>

<div class = "col-12 col-md-4 col-lg-4 mt-4 p-5">
    <button class="btn text-dark w-100 primaryButton" style="button">Register for the Next Boot Camp</button>
</div>

    </div>
</div>
@endsection
