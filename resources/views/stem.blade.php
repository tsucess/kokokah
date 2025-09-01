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
                <button class="btn btn-block w-100 navButton">Register Now</button>
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
            <h6 style="color:#004A53;">WHY CHOOSE US</h6>
            <h1>
                Why STEM Labs is <br>
                the Perfect Choice
            </h1>
            <p class="text-muted">
                The world is driven by innovation — and Africa’s future leaders must be ready to
                create, not just consume.<br>
                 STEM Labs by Kokokah blends physical, in-person
                experiences with our powerful digital tools to give <br>
                secondary school students an edge in STEM fields for a brighter future.<br>
                Through boot camps, summer schools, workshops, and mentorship programs, students:
            </p>
            <ul class="list-unstyled">
                <li class="mb-2"><i class="fa-solid fa-circle-arrow-right" style="color:#F56824;"></i> Build real-world projects in coding, robotics, and engineering</li>
                <li class="mb-2"><i class="fa-solid fa-circle-arrow-right" style="color:#F56824;"></i> Strengthen problem-solving, collaboration, and critical thinking skills</li>
                <li class="mb-2"><i class="fa-solid fa-circle-arrow-right" style="color:#F56824;"></i> Connect classroom theory with hands-on experiments</li>
                <li class="mb-2"><i class="fa-solid fa-circle-arrow-right" style="color:#F56824;"></i> Learn directly from STEM professionals and industry mentors</li>
            </ul>
        </div>

    </div>
</div>


<div class="container my-5 text-center position-relative">
    <h2 class="fw-bold mb-5">Programs We Offer</h2>

    <div class="position-absolute top-50 start-50 translate-middle d-none d-lg-block">
        <img src="path/to/central-image.svg" alt="Central illustration" class="img-fluid" style="width: 150px;">
    </div>

    <div class="row justify-content-center g-4">
        <div class="col-md-5">
            <div class="card p-3 h-100">
                <h5 class="fw-bold">STEM Boot Camps</h5>
                <p class="text-muted">Intensive short-term programs during school breaks</p>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card p-3 h-100">
                <h5 class="fw-bold">STEM Boot Camps</h5>
                <p class="text-muted">Intensive short-term programs during school breaks</p>
            </div>
        </div>
        <div class="col-md-5 offset-md-1">
            <div class="card p-3 h-100">
                <h5 class="fw-bold">STEM Boot Camps</h5>
                <p class="text-muted">Intensive short-term programs during school breaks</p>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card p-3 h-100">
                <h5 class="fw-bold">STEM Boot Camps</h5>
                <p class="text-muted">Intensive short-term programs during school breaks</p>
            </div>
        </div>
    </div>
</div>

{{-- <div class="container my-5">
    <div class="row align-items-center bg-dark text-white p-4 rounded">
        <div class="col-lg-9 mb-3 mb-lg-0">
            <h5 class="fw-bold">JOIN OVER 5555 students passionate about science, technology, or engineering - no prior experience needed! We design beginner-friendly and advanced tracks to meet every student where they are.</h5>
        </div>
        <div class="col-lg-3 text-lg-end">
            <button class="btn btn-outline-light btn-lg">Register for your Next Boot Camp</button>
        </div>
    </div>
</div> --}}

<div class = "row">
    <div class = "d-flex justify-content-between">

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
    <button class="btn btn-block w-100 navButton">Register Now</button>
</div>

    </div>
</div>
@endsection
