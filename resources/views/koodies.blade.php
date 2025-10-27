@extends('layouts.template')
@section('content')
<div class="position-relative text-white text-center py-5" style="background-image: url('images/heros.png'); background-size: cover; background-repeat:no-repeat; min-height: 400px;">
    <div class="position-absolute top-0 start-0 w-100 h-100"></div>
    <div class="position-relative h-100 d-flex flex-column justify-content-center align-items-center pt-5 px-5">
        <h1 class="display-4 fw-bold">
            Transforming African Education—One
            <br>
            School at a Time.
        </h1>
        <p class="col-lg-8 mx-auto">Kolokab combines School Management, Exam Prep, and a Learning Management System (LMS)—helping schools automate admin tasks, boost student performance, and deliver modern digital learning in one seamless platform.</p>
    </div>
</div>

<div class="container my-4">
    <div class="row g-2 justify-content-center align-items-center mb-1">
        {{-- <div class="col-sm-8 col-md-9">
            <input type="search" class="form-control rounded-pill" placeholder="Search Product">
        </div>
        <div class="col-sm-4 col-md-3">
            <button class="btn btn-dark w-100 rounded-pill">Search</button>
        </div> --}}

        <div class="col-sm-8 col-md-9">
        <div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Search Product" aria-label="Recipient’s username" aria-describedby="button-addon2">
  <button class="btn primaryButton" type="button" id="button-addon2">Search</button>
</div>
</div>
    </div>

    <div class="row g-2 justify-content-center mb-5">
        {{-- <div class="col-auto">
            <span class="badge rounded-pill bg-secondary text-dark p-2">Teen Health</span>
        </div>
        <div class="col-auto">
            <span class="badge rounded-pill bg-secondary text-dark p-2">Style</span>
        </div>
        <div class="col-auto">
            <span class="badge rounded-pill bg-secondary text-dark p-2">Beauty</span>
        </div>
        <div class="col-auto">
            <span class="badge rounded-pill bg-secondary text-dark p-2">Life & Politics</span>
        </div>
        <div class="col-auto">
            <span class="badge rounded-pill bg-secondary text-dark p-2">Health & Wellness</span>
        </div>
        <div class="col-auto">
            <span class="badge rounded-pill bg-secondary text-dark p-2">Love</span>
        </div>
        <div class="col-auto">
            <span class="badge rounded-pill bg-secondary text-dark p-2">Finance</span>
        </div> --}}
        <ul class="nav nav-pills justify-content-center" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" data-bs-toggle="pill" href="#teen">Teen Health</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-bs-toggle="pill" href="#style">Style</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-bs-toggle="pill" href="#beauty">Beauty</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-bs-toggle="pill" href="#life">Life & Politics</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-bs-toggle="pill" href="#health">Health & Wellness</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-bs-toggle="pill" href="#love">Love</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-bs-toggle="pill" href="#finance">Finance</a>
  </li>
</ul>
    </div>



    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="position-relative">
                    <img src="images/teen.png" class="card-img-top" alt="...">
                    <span class="position-absolute top-0 end-0 m-2 p-2 rounded-end" style = "background: #E5E6E7; color:#004A53;">TEEN HEALTH</span>
                </div>
                <div class="card-body">
                    <h6 class = "fw-bold">6 Signs Your Teen Needs More Support During Recovery</h6>
                    <p>Teenagers navigating the journey of recovery can face unique challenges that go unnoticed by parents and caregivers...</p>
                    <button class="btn w-100 secondaryButton" type = "buttton">Read More</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="position-relative">
                    <img src="images/teen.png" class="card-img-top" alt="...">
                    <span class="position-absolute top-0 end-0 m-2 p-2 rounded-end" style = "background: #E5E6E7; color:#004A53;">TEEN HEALTH</span>
                </div>
                <div class="card-body">
                    <h6 class = "fw-bold">6 Signs Your Teen Needs More Support During Recovery</h6>
                    <p>Teenagers navigating the journey of recovery can face unique challenges that go unnoticed by parents and caregivers...</p>
                    <button class="btn w-100 secondaryButton" type = "buttton">Read More</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="position-relative">
                    <img src="images/teen.png" class="card-img-top" alt="...">
                    <span class="position-absolute top-0 end-0 m-2 p-2 rounded-end" style = "background: #E5E6E7; color:#004A53;">TEEN HEALTH</span>
                </div>
                <div class="card-body">
                    <h6 class = "fw-bold">6 Signs Your Teen Needs More Support During Recovery</h6>
                    <p>Teenagers navigating the journey of recovery can face unique challenges that go unnoticed by parents and caregivers...</p>
                    <button class="btn w-100 secondaryButton" type = "buttton">Read More</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
