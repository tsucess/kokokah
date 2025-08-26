@extends('layouts.template' )
@section('content')
<div class="container my-5">
    <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <h1 class="display-5 fw-bold">Build your Child's Future with STEM</h1>
            <p class="lead text-muted">Description about STEM camps, summer schools, and tech-powered programs to ignite creativity and problem-solving in science, technology, engineering, and mathematics.</p>

            <div class="d-flex flex-column flex-sm-row">
                <button class="btn btn-dark btn-lg me-sm-3 mb-2 mb-sm-0">Register Now</button>
                <button class="btn btn-outline-dark btn-lg">Request a Demo</button>
            </div>

            <div class="row mt-4 align-items-center">
                <div class="col-sm-4 text-center">
                    <h4 class="fw-bold">2947</h4>
                    <p class="text-muted">Happy Students</p>
                </div>
                <div class="col-sm-4 text-center">
                    <h4 class="fw-bold">8</h4>
                    <p class="text-muted">Recent Award</p>
                </div>
                <div class="col-sm-4 text-center">
                    <h4 class="fw-bold">1700+</h4>
                    <p class="text-muted">Happy Parents</p>
                </div>
            </div>
        </div>

        <div class="col-lg-6 text-center">
            <img src="path/to/illustration-image.svg" alt="STEM illustration" class="img-fluid">
        </div>
    </div>
</div>


<div class="container my-5">
    <div class="row align-items-center">
        <div class="col-lg-5 mb-4 mb-lg-0">
            <div class="row g-2">
                <div class="col-6"><img src="path/to/image1.jpg" alt="" class="img-fluid rounded"></div>
                <div class="col-6"><img src="path/to/image2.jpg" alt="" class="img-fluid rounded"></div>
                <div class="col-6"><img src="path/to/image3.jpg" alt="" class="img-fluid rounded"></div>
                <div class="col-6"><img src="path/to/image4.jpg" alt="" class="img-fluid rounded"></div>
            </div>
        </div>

        <div class="col-lg-7">
            <h6 class="text-secondary fw-bold">WHY CHOOSE US</h6>
            <h2 class="fw-bold">Why STEM Labs is the Perfect Choice</h2>
            <p class="text-muted">Description about the company's philosophy and vision for the future.</p>
            <ul class="list-unstyled">
                <li class="mb-2">✅ Build real-world projects in coding, robotics, and engineering</li>
                <li class="mb-2">💡 Strengthen problem-solving, collaboration, and critical thinking skills</li>
                <li class="mb-2">🔬 Connect classroom theory with hands-on experiments</li>
                <li class="mb-2">🧑‍🏫 Learn directly from STEM professionals and industry mentors</li>
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

<div class="container my-5">
    <div class="row align-items-center bg-dark text-white p-4 rounded">
        <div class="col-lg-9 mb-3 mb-lg-0">
            <h5 class="fw-bold">JOIN OVER 5555 students passionate about science, technology, or engineering - no prior experience needed! We design beginner-friendly and advanced tracks to meet every student where they are.</h5>
        </div>
        <div class="col-lg-3 text-lg-end">
            <button class="btn btn-outline-light btn-lg">Register for your Next Boot Camp</button>
        </div>
    </div>
</div>
@endsection
